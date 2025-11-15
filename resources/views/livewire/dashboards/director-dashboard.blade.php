<div>
<!-- Header Ejecutivo - Diseño Limpio y Moderno -->
<div class="mb-8">
    <div class="bg-gradient-to-r from-red-500 via-orange-500 to-red-600 rounded-2xl shadow-lg p-8 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-white/10 via-transparent to-transparent"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/30">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold mb-2">Dashboard Ejecutivo</h1>
                        <p class="text-red-50 text-sm">Vista general de planes estratégicos, métricas y áreas de responsabilidad</p>
                    </div>
                </div>
                <button wire:click="refresh" 
                        class="p-2 rounded-lg text-white/80 hover:bg-white/20 hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas Principales - Diseño Limpio con Fondos Blancos -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Planes -->
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
        <p class="text-gray-600 text-sm font-medium mb-2">Total Planes</p>
        <p class="text-4xl font-bold text-gray-900 mb-3">{{ $totalPlans }}</p>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-lg">{{ $activePlans }} activos</span>
        </div>
    </div>

    <!-- KPIs Activos -->
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
        </div>
        <p class="text-gray-600 text-sm font-medium mb-2">KPIs Activos</p>
        <p class="text-4xl font-bold text-gray-900 mb-3">{{ $totalKpis }}</p>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-lg">En seguimiento</span>
        </div>
    </div>

    <!-- Riesgos Críticos -->
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
        <p class="text-gray-600 text-sm font-medium mb-2">Riesgos Críticos</p>
        <p class="text-4xl font-bold text-gray-900 mb-3">{{ $criticalRisks }}</p>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-lg">Requieren atención</span>
        </div>
    </div>

    <!-- Tareas Pendientes -->
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
        <p class="text-gray-600 text-sm font-medium mb-2">Tareas Pendientes</p>
        <p class="text-4xl font-bold text-gray-900 mb-3">{{ $pendingTasks }}</p>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-orange-100 text-orange-800 text-xs font-semibold rounded-lg">Por completar</span>
        </div>
    </div>
</div>

<!-- Acciones Rápidas - Diseño Limpio -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Planes Estratégicos -->
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500 hover:shadow-lg transition-shadow">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-orange-500 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Planes Estratégicos</h2>
                <p class="text-sm text-gray-600">Gestión integral de planes</p>
            </div>
        </div>
        <p class="text-gray-700 text-sm mb-6">Gestiona todos los planes estratégicos, comerciales y de desarrollo interno de la organización.</p>
        <div class="flex gap-3">
            <a href="{{ route('plans.index') }}" class="flex-1 px-4 py-2.5 text-sm font-semibold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-lg hover:from-red-700 hover:to-orange-700 transition-all text-center shadow-sm">
                Ver Planes
            </a>
            <a href="{{ route('plans.create') }}" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all text-center">
                Crear Plan
            </a>
        </div>
    </div>

    <!-- Indicadores KPIs -->
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-shadow">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Indicadores KPIs</h2>
                <p class="text-sm text-gray-600">Monitoreo y seguimiento</p>
            </div>
        </div>
        <p class="text-gray-700 text-sm mb-6">Monitorea los indicadores clave de rendimiento de todas las áreas y planes estratégicos.</p>
        <div class="flex gap-3">
            <a href="{{ route('kpis.index') }}" class="flex-1 px-4 py-2.5 text-sm font-semibold bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-lg hover:from-blue-700 hover:to-cyan-700 transition-all text-center shadow-sm">
                Ver KPIs
            </a>
            <a href="{{ route('kpis.create') }}" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all text-center">
                Crear KPI
            </a>
        </div>
    </div>
</div>

<!-- Planes Recientes - Diseño Limpio -->
<div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900">Planes Recientes</h2>
        </div>
        <a href="{{ route('plans.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all">
            Ver Todos
        </a>
    </div>
    
    @if($recentPlans->count() > 0)
        <div class="space-y-3">
            @foreach($recentPlans as $plan)
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-red-300 hover:bg-gray-100 transition-all group">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-semibold text-gray-900 group-hover:text-red-600 transition-colors">{{ $plan->name }}</h3>
                                <x-ui.badge variant="{{ $plan->status === 'approved' ? 'success' : ($plan->status === 'in_progress' ? 'info' : 'warning') }}">
                                    {{ $plan->status_label }}
                                </x-ui.badge>
                            </div>
                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    <span class="font-medium">{{ $plan->planType->name ?? 'Sin tipo' }}</span>
                                </div>
                                @if($plan->area)
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <span class="font-medium">{{ $plan->area->name }}</span>
                                    </div>
                                @endif
                                @if($plan->manager)
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span class="font-medium">{{ $plan->manager->name }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('plans.show', $plan) }}" class="ml-4 px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-lg border border-gray-300 transition-all flex items-center gap-2">
                            Ver
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="text-gray-600 text-sm mb-4 font-medium">No hay planes creados aún</p>
            <a href="{{ route('plans.create') }}" class="inline-block px-6 py-3 text-sm font-semibold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-lg hover:from-red-700 hover:to-orange-700 transition-all shadow-sm">
                Crear Primer Plan
            </a>
        </div>
    @endif
</div>
</div>
