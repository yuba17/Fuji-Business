@extends('layouts.dashboard')

@section('title', 'Calendario de equipo')

@section('breadcrumbs')
<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-red-600">
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                Dashboard
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Calendario de equipo</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 via-cyan-500 to-emerald-500 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Calendario de equipo</h1>
                <p class="text-sm md:text-base text-blue-50 mt-1">
                    Vista de tareas del equipo ordenadas por fecha. Ideal para planificar carga de trabajo.
                </p>
            </div>
        </div>
    </div>

    <!-- Lista de tareas por fecha -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M5 11h14M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5A2 2 0 003 7v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Próximas tareas del equipo</h2>
                    <p class="text-xs text-gray-500">Tareas asignadas a miembros de tu equipo, ordenadas por fecha de vencimiento</p>
                </div>
            </div>
        </div>

        @if($tasks->count() > 0)
            @php
                $grouped = $tasks->groupBy(fn($task) => optional($task->due_date)->format('Y-m-d') ?? 'Sin fecha');
            @endphp

            <div class="space-y-6">
                @foreach($grouped as $date => $tasksForDate)
                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-2 border-b border-gray-200 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                <span class="text-xs font-semibold text-gray-700">
                                    @if($date === 'Sin fecha')
                                        Sin fecha objetivo
                                    @else
                                        {{ \Carbon\Carbon::parse($date)->translatedFormat('d M Y') }}
                                    @endif
                                </span>
                            </div>
                            <span class="text-xs text-gray-500">{{ $tasksForDate->count() }} tarea(s)</span>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach($tasksForDate as $task)
                                <div class="px-4 py-3 flex items-start justify-between hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start gap-3">
                                        <div class="mt-1">
                                            @php
                                                $color = 'bg-gray-300';
                                                if ($task->priority === 'high') $color = 'bg-red-500';
                                                elseif ($task->priority === 'medium') $color = 'bg-amber-400';
                                                elseif ($task->priority === 'low') $color = 'bg-emerald-400';
                                            @endphp
                                            <span class="w-2 h-8 rounded-full block {{ $color }}"></span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $task->title }}</p>
                                            @if($task->plan)
                                                <p class="text-xs text-gray-500 mt-0.5">
                                                    Plan: <span class="font-medium">{{ $task->plan->name }}</span>
                                                </p>
                                            @endif
                                            @if($task->area)
                                                <p class="text-xs text-gray-500">
                                                    Área: <span class="font-medium">{{ $task->area->name }}</span>
                                                </p>
                                            @endif
                                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                                                {{ $task->description ?: 'Sin descripción detallada.' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right text-xs text-gray-500 flex flex-col items-end gap-1">
                                        @if($task->assignedUser)
                                            <div class="flex items-center gap-2">
                                                <span class="w-6 h-6 bg-gradient-to-br from-red-500 to-orange-500 rounded-full flex items-center justify-center text-white text-[10px] font-bold">
                                                    {{ $task->assignedUser->initials() }}
                                                </span>
                                                <span class="font-medium text-gray-700">{{ $task->assignedUser->name }}</span>
                                            </div>
                                        @endif
                                        <span class="px-2 py-0.5 rounded-full border border-gray-200 text-[10px] text-gray-600">
                                            Estado: {{ ucfirst($task->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-10 text-gray-500 text-sm">
                No hay tareas asignadas a tu equipo todavía.
            </div>
        @endif
    </div>
</div>
@endsection


