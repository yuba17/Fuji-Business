@extends('layouts.dashboard')

@section('title', 'Todos los equipos')

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
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Todos los equipos</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-emerald-600 via-green-500 to-emerald-600 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Todos los equipos</h1>
                <p class="text-sm md:text-base text-emerald-50 mt-1">
                    Vista global de equipos por área. Solo disponible para Directores.
                </p>
            </div>
        </div>
    </div>

    <!-- Tabla de equipos por área -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 bg-gradient-to-br from-emerald-500 to-green-500 rounded-xl flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M12 11a4 4 0 110-8 4 4 0 010 8z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Estructura de equipos</h2>
                    <p class="text-xs text-gray-500">Resumen de director, managers y carga de trabajo por área</p>
                </div>
            </div>
        </div>

        @if($areasWithTeams->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Área</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Director</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Managers</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tareas activas</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($areasWithTeams as $area)
                            @php
                                $activeTasks = $area->tasks->whereNotIn('status', ['completed', 'cancelled'])->count();
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-3 py-3 text-sm text-gray-900">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-8 rounded-full bg-emerald-500"></span>
                                        <div>
                                            <p class="font-semibold">{{ $area->name }}</p>
                                            <p class="text-xs text-gray-500 line-clamp-1">{{ $area->description ?: 'Sin descripción' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-700">
                                    @if($area->director)
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 bg-gradient-to-br from-red-500 to-orange-500 rounded-full flex items-center justify-center text-white text-[10px] font-bold">
                                                {{ $area->director->initials() }}
                                            </span>
                                            <span class="text-xs font-medium">{{ $area->director->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">Sin director asignado</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-xs text-gray-700">
                                    @if($area->managers->count() > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($area->managers as $mgr)
                                                <span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 font-medium">
                                                    {{ $mgr->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">Sin managers asignados</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-sm">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                        @if($activeTasks === 0)
                                            bg-emerald-50 text-emerald-700
                                        @elseif($activeTasks < 10)
                                            bg-amber-50 text-amber-700
                                        @else
                                            bg-red-50 text-red-700
                                        @endif
                                    ">
                                        {{ $activeTasks }} activas
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-10 text-gray-500 text-sm">
                No se han encontrado áreas activas con equipos configurados.
            </div>
        @endif
    </div>
</div>
@endsection


