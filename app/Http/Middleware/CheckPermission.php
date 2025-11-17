<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$permissions
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Si no se especifican permisos, permitir acceso
        if (empty($permissions)) {
            return $next($request);
        }

        // Verificar si el usuario tiene alguno de los permisos requeridos
        $hasPermission = false;
        foreach ($permissions as $permission) {
            // Verificar si el permiso contiene un modelo (formato: "action.model" o "action")
            $parts = explode('.', $permission);
            
            if (count($parts) === 2) {
                // Formato: "action.model" (ej: "view.plan", "edit.task")
                [$action, $model] = $parts;
                
                // Intentar obtener el modelo de la ruta si está disponible
                $modelInstance = $this->getModelFromRoute($request, $model);
                
                if ($modelInstance) {
                    // Verificar permiso con el modelo específico usando Gate
                    if (Gate::forUser($user)->allows($action, $modelInstance)) {
                        $hasPermission = true;
                        break;
                    }
                } else {
                    // Verificar permiso general (ej: "viewAny")
                    if (Gate::forUser($user)->allows($action . 'Any', $model)) {
                        $hasPermission = true;
                        break;
                    }
                }
            } else {
                // Formato simple: solo acción (ej: "view_dashboard", "manage_users")
                // Verificar usando Gate directamente
                if (Gate::forUser($user)->allows($permission)) {
                    $hasPermission = true;
                    break;
                }
            }
        }

        if (!$hasPermission) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        return $next($request);
    }

    /**
     * Intentar obtener el modelo desde la ruta
     */
    protected function getModelFromRoute(Request $request, string $modelName): ?object
    {
        // Mapeo de nombres de modelos a clases
        $modelMap = [
            'plan' => \App\Models\Plan::class,
            'kpi' => \App\Models\Kpi::class,
            'task' => \App\Models\Task::class,
            'risk' => \App\Models\Risk::class,
            'decision' => \App\Models\Decision::class,
            'client' => \App\Models\Client::class,
            'project' => \App\Models\Project::class,
            'user' => \App\Models\User::class,
        ];

        $modelClass = $modelMap[strtolower($modelName)] ?? null;
        
        if (!$modelClass) {
            return null;
        }

        // Intentar obtener el ID del modelo desde los parámetros de la ruta
        $routeParameters = $request->route()->parameters();
        
        // Buscar parámetros comunes: {plan}, {plan_id}, {id}, etc.
        $possibleKeys = [
            strtolower($modelName),
            strtolower($modelName) . '_id',
            'id',
        ];

        foreach ($possibleKeys as $key) {
            if (isset($routeParameters[$key])) {
                $id = $routeParameters[$key];
                
                // Si es un modelo, devolverlo directamente
                if (is_object($id) && $id instanceof $modelClass) {
                    return $id;
                }
                
                // Si es un ID, cargar el modelo
                if (is_numeric($id)) {
                    return $modelClass::find($id);
                }
            }
        }

        return null;
    }
}

