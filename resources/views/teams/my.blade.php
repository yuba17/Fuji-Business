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
    <div class="bg-white rounded-2xl shadow-md border border-gray-200 px-3 py-2 flex flex-wrap items-center gap-2">
        <button @click="activeTab = 'overview'"
                :class="activeTab === 'overview' ? 'bg-gradient-to-r from-purple-600 to-blue-600 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                class="px-3 py-1.5 rounded-xl text-xs font-semibold flex items-center gap-1.5 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m4 0h-4m4 0a2 2 0 012 2m-2-2a2 2 0 00-2-2m-4 2H9m4 0a2 2 0 01-2 2m2-2a2 2 0 002 2m-6-2H5m4 0a2 2 0 01-2 2" />
            </svg>
            Resumen
        </button>
        <button @click="activeTab = 'profiles'"
                :class="activeTab === 'profiles' ? 'bg-gradient-to-r from-indigo-600 to-purple-500 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                class="px-3 py-1.5 rounded-xl text-xs font-semibold flex items-center gap-1.5 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Perfiles
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

    <!-- Pestaña: Perfiles -->
    <div x-show="activeTab === 'profiles'" x-transition class="space-y-6">
        @if(isset($profileStats) && isset($allTeamMembers))
            <!-- Estadísticas de Perfiles -->
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-5 border-2 border-indigo-200">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                        📊
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Estado de Perfiles del Equipo</h2>
                        <p class="text-xs text-gray-600">Completitud y datos de los perfiles de tu equipo</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    <div class="bg-white rounded-lg p-3 border border-indigo-100">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Completitud Media</p>
                        <p class="text-2xl font-bold text-indigo-900">{{ $profileStats['avg_completion'] }}%</p>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5">
                            <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-1.5 rounded-full transition-all" 
                                 style="width: {{ $profileStats['avg_completion'] }}%"></div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-green-100">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Completos</p>
                        <p class="text-2xl font-bold text-green-900">{{ $profileStats['complete_profiles'] }}</p>
                        <p class="text-xs text-gray-600 mt-1">Perfiles al 100%</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-yellow-100">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Incompletos</p>
                        <p class="text-2xl font-bold text-yellow-900">{{ $profileStats['incomplete_profiles'] }}</p>
                        <p class="text-xs text-gray-600 mt-1">Pendientes</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-red-100">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Baja Completitud</p>
                        <p class="text-2xl font-bold text-red-900">{{ $profileStats['low_completion'] }}</p>
                        <p class="text-xs text-gray-600 mt-1">&lt; 50% completado</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-purple-100">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Total Miembros</p>
                        <p class="text-2xl font-bold text-purple-900">{{ $profileStats['total_members'] }}</p>
                        <p class="text-xs text-gray-600 mt-1">En tu equipo</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                    <div class="bg-white rounded-lg p-3 border border-purple-100">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Competencias</p>
                        <p class="text-xl font-bold text-purple-900">{{ $profileStats['total_competencies'] }}</p>
                        <p class="text-xs text-gray-600 mt-1">Total evaluadas</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-amber-100">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Certificaciones</p>
                        <p class="text-xl font-bold text-amber-900">{{ $profileStats['total_certifications'] }}</p>
                        <p class="text-xs text-gray-600 mt-1">Total obtenidas</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-pink-100">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Con Avatar</p>
                        <p class="text-xl font-bold text-pink-900">{{ $profileStats['users_with_avatar'] }}</p>
                        <p class="text-xs text-gray-600 mt-1">de {{ $profileStats['total_members'] }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-gray-100">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Sin Avatar</p>
                        <p class="text-xl font-bold text-gray-900">{{ $profileStats['users_without_avatar'] }}</p>
                        <p class="text-xs text-gray-600 mt-1">Pendientes</p>
                    </div>
                </div>
            </div>

            <!-- Lista de Perfiles -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200"
                 x-data="{ 
                     profileSearch: '',
                     profileFilter: 'all',
                     teamProfiles: @js($allTeamMembers->map(function($user) {
                         return [
                             'id' => $user->id,
                             'name' => $user->name,
                             'email' => $user->email,
                             'avatar_url' => $user->avatar_url,
                             'completion' => $user->profile_completion_percent ?? 0,
                             'competencies' => $user->competencies->count(),
                             'certifications' => $user->userCertifications->count(),
                             'has_avatar' => !is_null($user->avatar_url),
                             'internal_role' => $user->internalRole ? $user->internalRole->name : 'Sin rol',
                             'manager' => $user->manager ? $user->manager->name : null,
                         ];
                     })->values()->all()),
                     filterProfiles(users) {
                         let filtered = users;
                         
                         if (this.profileSearch) {
                             const search = this.profileSearch.toLowerCase();
                             filtered = filtered.filter(u => 
                                 u.name.toLowerCase().includes(search) || 
                                 u.email.toLowerCase().includes(search)
                             );
                         }
                         
                         if (this.profileFilter === 'complete') {
                             filtered = filtered.filter(u => u.completion >= 100);
                         } else if (this.profileFilter === 'incomplete') {
                             filtered = filtered.filter(u => u.completion < 100);
                         } else if (this.profileFilter === 'low') {
                             filtered = filtered.filter(u => u.completion < 50);
                         }
                         
                         return filtered;
                     }
                 }">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Perfiles del Equipo</h2>
                            <p class="text-xs text-gray-500">Gestiona y completa los perfiles de tu equipo</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="text" 
                               x-model="profileSearch" 
                               placeholder="Buscar por nombre..." 
                               class="text-xs border border-gray-300 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <select x-model="profileFilter" 
                                class="text-xs border border-gray-300 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="all">Todos</option>
                            <option value="complete">Completos</option>
                            <option value="incomplete">Incompletos</option>
                            <option value="low">Baja completitud</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <template x-for="user in filterProfiles(teamProfiles)" :key="user.id">
                        <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl border-2 p-4 hover:border-indigo-300 hover:shadow-lg transition-all"
                             :class="{
                                 'border-green-200': user.completion >= 100,
                                 'border-yellow-200': user.completion >= 50 && user.completion < 100,
                                 'border-red-200': user.completion < 50
                             }">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <template x-if="user.avatar_url">
                                            <img :src="user.avatar_url" 
                                                 :alt="user.name" 
                                                 class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-md">
                                        </template>
                                        <template x-if="!user.avatar_url">
                                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold border-2 border-white shadow-md">
                                                <span x-text="user.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase()"></span>
                                            </div>
                                        </template>
                                        <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full border-2 border-white flex items-center justify-center"
                                             :class="{
                                                 'bg-green-500': user.completion >= 100,
                                                 'bg-yellow-500': user.completion >= 50 && user.completion < 100,
                                                 'bg-red-500': user.completion < 50
                                             }">
                                            <span class="text-[10px] font-bold text-white" x-text="user.completion + '%'"></span>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-sm" x-text="user.name"></h3>
                                        <p class="text-xs text-gray-500" x-text="user.email"></p>
                                        <p class="text-[11px] text-gray-600 mt-0.5" x-text="user.internal_role"></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-medium text-gray-600">Completitud</span>
                                    <span class="text-xs font-bold" 
                                          :class="{
                                              'text-green-600': user.completion >= 100,
                                              'text-yellow-600': user.completion >= 50 && user.completion < 100,
                                              'text-red-600': user.completion < 50
                                          }"
                                          x-text="user.completion + '%'"></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full transition-all"
                                         :class="{
                                             'bg-green-500': user.completion >= 100,
                                             'bg-yellow-500': user.completion >= 50 && user.completion < 100,
                                             'bg-red-500': user.completion < 50
                                         }"
                                         :style="'width: ' + user.completion + '%'"></div>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-2 mb-3">
                                <div class="text-center p-2 bg-purple-50 rounded-lg">
                                    <p class="text-lg font-bold text-purple-900" x-text="user.competencies"></p>
                                    <p class="text-[10px] text-purple-700 font-medium">Competencias</p>
                                </div>
                                <div class="text-center p-2 bg-amber-50 rounded-lg">
                                    <p class="text-lg font-bold text-amber-900" x-text="user.certifications"></p>
                                    <p class="text-[10px] text-amber-700 font-medium">Certificaciones</p>
                                </div>
                                <div class="text-center p-2 rounded-lg"
                                     :class="user.has_avatar ? 'bg-green-50' : 'bg-gray-50'">
                                    <p class="text-lg font-bold"
                                       :class="user.has_avatar ? 'text-green-900' : 'text-gray-400'"
                                       x-text="user.has_avatar ? '✓' : '✗'"></p>
                                    <p class="text-[10px] font-medium"
                                       :class="user.has_avatar ? 'text-green-700' : 'text-gray-500'">Avatar</p>
                                </div>
                            </div>

                            <a :href="'/profile/' + user.id" 
                               class="block w-full text-center px-3 py-2 text-xs font-semibold bg-gradient-to-r from-indigo-600 to-purple-500 text-white rounded-lg hover:from-indigo-700 hover:to-purple-600 transition-all">
                                Ver/Editar Perfil
                            </a>
                        </div>
                    </template>
                </div>

                <div x-show="filterProfiles(teamProfiles).length === 0" class="text-center py-10 text-gray-500 text-sm">
                    No se encontraron perfiles que coincidan con los filtros.
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 text-center py-10 text-gray-500 text-sm">
                No tienes miembros en tu equipo todavía.
            </div>
        @endif
    </div>
</div>
@endsection


