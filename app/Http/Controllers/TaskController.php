<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Plan;
use App\Models\Area;
use App\Models\User;
use App\Models\TaskAttachment;
use App\Models\TaskComment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Task::class);
        
        // El componente Livewire TaskList maneja toda la lógica de filtrado y listado
        return view('tasks.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Task::class);
        
        $user = auth()->user();
        $plans = Plan::where('status', '!=', 'archived')->get();
        $areas = Area::where('is_active', true)->get();
        $users = User::all();
        $planId = $request->get('plan_id');
        
        return view('tasks.create', compact('plans', 'areas', 'users', 'planId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Task::class);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'plan_id' => 'required|exists:plans,id',
            'area_id' => 'nullable|exists:areas,id',
            'milestone_id' => 'nullable|exists:milestones,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'nullable|in:todo,in_progress,blocked,review,done',
            'priority' => 'nullable|in:low,medium,high,critical',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = $validated['status'] ?? 'todo';
        $validated['priority'] = $validated['priority'] ?? 'medium';

        $task = Task::create($validated);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Tarea creada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): View
    {
        $this->authorize('view', $task);
        
        $task->load([
            'plan', 
            'area', 
            'assignedUser', 
            'creator', 
            'milestone', 
            'parentTask', 
            'subtasks', 
            'attachments.uploader',
            'comments.user',
            'comments.replies.user'
        ]);
        
        $users = User::all(); // Para el autocompletado de menciones
        
        return view('tasks.show', compact('task', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task): View
    {
        $this->authorize('update', $task);
        
        $plans = Plan::where('status', '!=', 'archived')->get();
        $areas = Area::where('is_active', true)->get();
        $users = User::all();
        
        return view('tasks.edit', compact('task', 'plans', 'areas', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'plan_id' => 'required|exists:plans,id',
            'area_id' => 'nullable|exists:areas,id',
            'milestone_id' => 'nullable|exists:milestones,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'nullable|in:todo,in_progress,blocked,review,done',
            'priority' => 'nullable|in:low,medium,high,critical',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'actual_hours' => 'nullable|numeric|min:0',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Tarea actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);
        
        $task->delete();
        
        return redirect()->route('tasks.index')
            ->with('success', 'Tarea eliminada correctamente');
    }

    /**
     * Mostrar vista Kanban de tareas
     */
    public function kanban(Request $request): View
    {
        $this->authorize('viewAny', Task::class);
        
        $planId = $request->get('plan');
        
        return view('tasks.kanban', [
            'planId' => $planId,
        ]);
    }

    /**
     * Subir adjunto a una tarea
     */
    public function uploadAttachment(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);
        
        $validated = $request->validate([
            'file' => 'required|file|max:10240', // Máximo 10MB
            'description' => 'nullable|string|max:500',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('task-attachments', $fileName, 'public');

        TaskAttachment::create([
            'task_id' => $task->id,
            'file_name' => $fileName,
            'file_path' => $filePath,
            'original_name' => $originalName,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_by' => auth()->id(),
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Archivo subido correctamente');
    }

    /**
     * Eliminar adjunto de una tarea
     */
    public function deleteAttachment(Task $task, TaskAttachment $attachment): RedirectResponse
    {
        $this->authorize('update', $task);
        
        // Verificar que el adjunto pertenece a la tarea
        if ($attachment->task_id !== $task->id) {
            abort(403);
        }

        // Eliminar archivo del storage
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $attachment->delete();

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Archivo eliminado correctamente');
    }

    /**
     * Descargar adjunto
     */
    public function downloadAttachment(Task $task, TaskAttachment $attachment)
    {
        $this->authorize('view', $task);
        
        // Verificar que el adjunto pertenece a la tarea
        if ($attachment->task_id !== $task->id) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($attachment->file_path)) {
            abort(404, 'Archivo no encontrado');
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->original_name);
    }

    /**
     * Agregar comentario a una tarea
     */
    public function addComment(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('view', $task);
        
        $validated = $request->validate([
            'comment' => 'required|string|max:5000',
            'parent_comment_id' => 'nullable|exists:task_comments,id',
        ]);

        // Extraer menciones del comentario
        $mentions = TaskComment::extractMentions($validated['comment']);
        $mentionedUserIds = [];
        
        foreach ($mentions as $mention) {
            // Buscar usuario por nombre exacto o similar
            $user = User::where('name', $mention)
                ->orWhere('name', 'LIKE', "{$mention} %")
                ->orWhere('name', 'LIKE', "% {$mention}")
                ->orWhere('name', 'LIKE', "% {$mention} %")
                ->first();
            if ($user && !in_array($user->id, $mentionedUserIds)) {
                $mentionedUserIds[] = $user->id;
            }
        }

        TaskComment::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'comment' => $validated['comment'],
            'mentioned_user_ids' => !empty($mentionedUserIds) ? $mentionedUserIds : null,
            'parent_comment_id' => $validated['parent_comment_id'] ?? null,
        ]);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Comentario agregado correctamente');
    }

    /**
     * Eliminar comentario
     */
    public function deleteComment(Task $task, TaskComment $comment): RedirectResponse
    {
        $this->authorize('view', $task);
        
        // Verificar que el comentario pertenece a la tarea
        if ($comment->task_id !== $task->id) {
            abort(403);
        }

        // Solo el autor o un admin puede eliminar
        if ($comment->user_id !== auth()->id() && !auth()->user()->isDirector()) {
            abort(403);
        }

        $comment->delete();

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Comentario eliminado correctamente');
    }
}
