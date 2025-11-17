@extends('layouts.dashboard')

@section('title', 'Mi Equipo')

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
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Mi equipo</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'overview' }">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-600 via-blue-600 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Mi equipo</h1>
                <p class="text-sm md:text-base text-purple-100 mt-1">
                    Vista de las personas y áreas que dependen de ti o comparten tus áreas de trabajo.
                </p>
            </div>
            <div class="hidden md:flex items-center gap-3">
                <div class="bg-white/10 rounded-xl px-4 py-2 text-xs font-semibold flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-300"></span>
                    Modo equipos activo
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl shadow-lg p-5 border-l-4 border-purple-500">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Miembros del equipo</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $teamMembers->count() }}</p>
            <p class="mt-1 text-xs text-gray-500">Personas con las que compartes áreas y responsabilidad</p>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-5 border-l-4 border-blue-500">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Áreas cubiertas</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $areas->count() }}</p>
            <p class="mt-1 text-xs text-gray-500">Áreas donde tienes responsabilidad o presencia</p>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-5 border-l-4 border-emerald-500">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Rol</p>
            <p class="mt-2 text-xl font-bold text-gray-900">
                @if($user->isDirector())
                    Director
                @elseif($user->isManager())
                    Manager
                @elseif($user->isTecnico())
                    Técnico
                @else
                    Visualización
                @endif
            </p>
            <p class="mt-1 text-xs text-gray-500">Según tu rol, verás más o menos alcance de equipos</p>
            <div class="mt-3 border-t border-gray-100 pt-3">
                <p class="text-[11px] font-medium text-gray-500 uppercase tracking-wide">Rol interno</p>
                @if($user->internalRole)
                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $user->internalRole->name }}
                    </p>
                    <p class="text-[11px] text-gray-500">
                        {{ $user->internalRole->level ?? 'Sin nivel definido' }}
                        @if($user->internalRole->track)
                            · {{ $user->internalRole->track }}
                        @endif
                    </p>
                @else
                    <p class="mt-1 text-xs text-gray-400">Sin rol interno asignado todavía.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-200 px-3 py-2 flex items-center gap-2">
        <button @click="activeTab = 'overview'"
                :class="activeTab === 'overview' ? 'bg-gradient-to-r from-purple-600 to-blue-600 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                class="px-3 py-1.5 rounded-xl text-xs font-semibold flex items-center gap-1.5 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m4 0h-4m4 0a2 2 0 012 2m-2-2a2 2 0 00-2-2m-4 2H9m4 0a2 2 0 01-2 2m2-2a2 2 0 002 2m-6-2H5m4 0a2 2 0 01-2 2" />
            </svg>
            Resumen
        </button>
        <button @click="activeTab = 'direct-reports'"
                :class="activeTab === 'direct-reports' ? 'bg-gradient-to-r from-emerald-600 to-green-500 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                class="px-3 py-1.5 rounded-xl text-xs font-semibold flex items-center gap-1.5 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0l-3-3m3 3l3-3m-9 9h12" />
            </svg>
            Mi equipo directo
        </button>
        <button @click="activeTab = 'managers'"
                :class="activeTab === 'managers' ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                class="px-3 py-1.5 rounded-xl text-xs font-semibold flex items-center gap-1.5 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M12 11a4 4 0 110-8 4 4 0 010 8z" />
            </svg>
            Managers a mi cargo
        </button>
        <button @click="activeTab = 'all-levels'"
                :class="activeTab === 'all-levels' ? 'bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                class="px-3 py-1.5 rounded-xl text-xs font-semibold flex items-center gap-1.5 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h4l3 8 4-12 3 8h4" />
            </svg>
            Todo mi árbol
        </button>
    </div>

    <!-- Áreas -->
    <div x-show="activeTab === 'overview'" class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 bg-gradient-to-br from-purple-500 to-blue-500 rounded-xl flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h6v10H3zM9 7l6-4 6 4v10l-6 4-6-4z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Áreas de responsabilidad</h2>
                    <p class="text-xs text-gray-500">Estructura de áreas relacionadas con tu equipo</p>
                </div>
            </div>
        </div>

        @if($areas->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($areas as $area)
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200 p-4 hover:border-purple-300 hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-bold text-gray-900 truncate">{{ $area->name }}</h3>
                            @if($area->director)
                                <span class="px-2 py-0.5 bg-purple-100 text-purple-700 text-[10px] font-semibold rounded-full">
                                    Director: {{ $area->director->initials() }}
                                </span>
                            @endif
                        </div>
                        @if($area->managers->count() > 0)
                            <p class="text-[11px] text-gray-600 mb-1">
                                Managers:
                                @foreach($area->managers as $mgr)
                                    <span class="font-medium">{{ $mgr->name }}</span>@if(!$loop->last),@endif
                                @endforeach
                            </p>
                        @endif
                        <p class="text-[11px] text-gray-500 line-clamp-2">
                            {{ $area->description ?: 'Área sin descripción detallada.' }}
                        </p>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500 text-sm">
                No se han identificado áreas asociadas a tu equipo todavía.
            </div>
        @endif
    </div>

    <!-- Miembros del equipo -->
    <div x-show="activeTab === 'overview'" class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 bg-gradient-to-br from-emerald-500 to-green-500 rounded-xl flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M12 11a4 4 0 110-8 4 4 0 010 8z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Personas en mi equipo</h2>
                    <p class="text-xs text-gray-500">Colaboradores con los que compartes planes, tareas y áreas</p>
                </div>
            </div>
        </div>

        @if($teamMembers->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Persona</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rol interno</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Áreas</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($teamMembers as $member)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-3 py-3 text-sm text-gray-900">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-orange-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                            {{ $member->initials() }}
                                        </div>
                                        <div>
                                            <p class="font-semibold">{{ $member->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $member->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-3 text-xs text-gray-700">
                                    @if($member->internalRole)
                                        <div class="flex flex-col">
                                            <span class="font-semibold">{{ $member->internalRole->name }}</span>
                                            <span class="text-[11px] text-gray-500">
                                                {{ $member->internalRole->level ?? 'Sin nivel definido' }}
                                                @if($member->internalRole->track)
                                                    · {{ $member->internalRole->track }}
                                                @endif
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs">Sin rol interno</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-sm">
                                    @php
                                        $roleLabel = 'Visualización';
                                        if ($member->isDirector()) $roleLabel = 'Director';
                                        elseif ($member->isManager()) $roleLabel = 'Manager';
                                        elseif ($member->isTecnico()) $roleLabel = 'Técnico';
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold
                                        @if($member->isDirector())
                                            bg-red-100 text-red-700
                                        @elseif($member->isManager())
                                            bg-blue-100 text-blue-700
                                        @elseif($member->isTecnico())
                                            bg-emerald-100 text-emerald-700
                                        @else
                                            bg-gray-100 text-gray-700
                                        @endif
                                    ">{{ $roleLabel }}</span>
                                </td>
                                <td class="px-3 py-3 text-xs text-gray-600">
                                    @php
                                        $primaryArea = $member->area ?? null;
                                    @endphp
                                    @if($primaryArea)
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded-full">{{ $primaryArea->name }}</span>
                                    @elseif($member->areas && $member->areas->count() > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($member->areas as $memberArea)
                                                <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded-full">{{ $memberArea->name }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400">Sin áreas asignadas</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-10 text-gray-500 text-sm">
                Por ahora no se ha detectado ningún equipo asociado a tus áreas. A medida que se asignen usuarios a las mismas áreas, aparecerán aquí.
            </div>
        @endif
    </div>

    <!-- Mi equipo directo (por jerarquía manager_id) -->
    <div x-show="activeTab === 'direct-reports'" class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 bg-gradient-to-br from-emerald-500 to-green-500 rounded-xl flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0l-3-3m3 3l3-3m-9 9h12" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Personas que reportan directamente a mí</h2>
                    <p class="text-xs text-gray-500">Equipo que me reporta vía manager directo</p>
                </div>
            </div>
        </div>
        @if($directReports->count() > 0)
            @include('teams.partials.team-table', ['members' => $directReports])
        @else
            <p class="text-center text-sm text-gray-500 py-6">No tienes personas asignadas directamente a tu cargo todavía.</p>
        @endif
    </div>

    <!-- Managers a mi cargo -->
    <div x-show="activeTab === 'managers'" class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M12 11a4 4 0 110-8 4 4 0 010 8z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Managers a mi cargo</h2>
                    <p class="text-xs text-gray-500">Responsables intermedios que gestionan equipos bajo tu dirección</p>
                </div>
            </div>
        </div>
        @if($managers->count() > 0)
            @include('teams.partials.team-table', ['members' => $managers])
        @else
            <p class="text-center text-sm text-gray-500 py-6">No tienes managers asignados directamente a tu cargo.</p>
        @endif
    </div>

    <!-- Todo mi árbol (directos + segundo nivel) -->
    <div x-show="activeTab === 'all-levels'" class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 bg-gradient-to-br from-orange-500 to-amber-500 rounded-xl flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h4l3 8 4-12 3 8h4" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Todo mi árbol de equipo</h2>
                    <p class="text-xs text-gray-500">Equipo directo y equipos de mis managers</p>
                </div>
            </div>
        </div>
        @php
            $allTreeMembers = $directReports->merge($secondLevelReports)->unique('id');
        @endphp
        @if($allTreeMembers->count() > 0)
            @include('teams.partials.team-table', ['members' => $allTreeMembers])
        @else
            <p class="text-center text-sm text-gray-500 py-6">Todavía no hay estructura de equipo definida bajo tu responsabilidad.</p>
        @endif
    </div>
</div>
@endsection


