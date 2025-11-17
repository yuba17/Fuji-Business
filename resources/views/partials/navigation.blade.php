@php
    $user = auth()->user();
    $isDirector = $user->isDirector();
    $isManager = $user->isManager();
    $isTecnico = $user->isTecnico();
@endphp

<a href="{{ route('dashboard') }}" 
   :class="sidebarCollapsed ? 'justify-center px-3' : 'px-4'"
   class="group flex items-center gap-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-red-600 via-orange-600 to-red-600 text-white shadow-lg shadow-red-500/30' : 'text-gray-700 hover:bg-gradient-to-r hover:from-red-50 hover:via-orange-50 hover:to-red-50 hover:text-red-600' }}"
   :title="sidebarCollapsed ? 'Dashboard' : ''">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
    </svg>
    <span x-show="!sidebarCollapsed" 
          x-transition:enter="transition ease-out duration-200"
          x-transition:enter-start="opacity-0"
          x-transition:enter-end="opacity-100"
          x-transition:leave="transition ease-in duration-150"
          x-transition:leave-start="opacity-100"
          x-transition:leave-end="opacity-0"
          class="whitespace-nowrap">Dashboard</span>
</a>

<a href="{{ route('dashboards.index') }}" 
   :class="sidebarCollapsed ? 'justify-center px-3' : 'px-4'"
   class="group flex items-center gap-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('dashboards.*') ? 'bg-gradient-to-r from-purple-600 via-pink-600 to-purple-600 text-white shadow-lg shadow-purple-500/30' : 'text-gray-700 hover:bg-gradient-to-r hover:from-purple-50 hover:via-pink-50 hover:to-purple-50 hover:text-purple-600' }}"
   :title="sidebarCollapsed ? 'Dashboards' : ''">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1v-3z"/>
    </svg>
    <span x-show="!sidebarCollapsed" 
          x-transition:enter="transition ease-out duration-200"
          x-transition:enter-start="opacity-0"
          x-transition:enter-end="opacity-100"
          x-transition:leave="transition ease-in duration-150"
          x-transition:leave-start="opacity-100"
          x-transition:leave-end="opacity-0"
          class="whitespace-nowrap">Mis Dashboards</span>
</a>

@if($isDirector || $isManager)
    <a href="{{ route('plans.index') }}" 
       :class="sidebarCollapsed ? 'justify-center px-3' : 'px-4'"
       class="group flex items-center gap-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('plans.*') ? 'bg-gradient-to-r from-red-600 via-orange-600 to-red-600 text-white shadow-lg shadow-red-500/30' : 'text-gray-700 hover:bg-gradient-to-r hover:from-red-50 hover:via-orange-50 hover:to-red-50 hover:text-red-600' }}"
       :title="sidebarCollapsed ? 'Planes' : ''">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <span x-show="!sidebarCollapsed" 
              x-transition:enter="transition ease-out duration-200"
              x-transition:enter-start="opacity-0"
              x-transition:enter-end="opacity-100"
              x-transition:leave="transition ease-in duration-150"
              x-transition:leave-start="opacity-100"
              x-transition:leave-end="opacity-0"
              class="whitespace-nowrap">Planes</span>
    </a>
@endif

@if($isDirector || $isManager)
    <a href="{{ route('kpis.index') }}" 
       :class="sidebarCollapsed ? 'justify-center px-3' : 'px-4'"
       class="group flex items-center gap-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('kpis.*') ? 'bg-gradient-to-r from-blue-600 via-cyan-600 to-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:via-cyan-50 hover:to-blue-50 hover:text-blue-600' }}"
       :title="sidebarCollapsed ? 'KPIs' : ''">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
        <span x-show="!sidebarCollapsed" 
              x-transition:enter="transition ease-out duration-200"
              x-transition:enter-start="opacity-0"
              x-transition:enter-end="opacity-100"
              x-transition:leave="transition ease-in duration-150"
              x-transition:leave-start="opacity-100"
              x-transition:leave-end="opacity-0"
              class="whitespace-nowrap">KPIs</span>
    </a>
@endif

@if($isDirector || $isManager || $isTecnico)
    <a href="{{ route('tasks.index') }}" 
       :class="sidebarCollapsed ? 'justify-center px-3' : 'px-4'"
       class="group flex items-center gap-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('tasks.*') ? 'bg-gradient-to-r from-green-600 via-emerald-600 to-green-600 text-white shadow-lg shadow-green-500/30' : 'text-gray-700 hover:bg-gradient-to-r hover:from-green-50 hover:via-emerald-50 hover:to-green-50 hover:text-green-600' }}"
       :title="sidebarCollapsed ? 'Tareas' : ''">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <span x-show="!sidebarCollapsed" 
              x-transition:enter="transition ease-out duration-200"
              x-transition:enter-start="opacity-0"
              x-transition:enter-end="opacity-100"
              x-transition:leave="transition ease-in duration-150"
              x-transition:leave-start="opacity-100"
              x-transition:leave-end="opacity-0"
              class="whitespace-nowrap">Tareas</span>
    </a>
@endif

@if($isDirector || $isManager)
    <a href="{{ route('risks.index') }}" 
       :class="sidebarCollapsed ? 'justify-center px-3' : 'px-4'"
       class="group flex items-center gap-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('risks.*') ? 'bg-gradient-to-r from-orange-600 via-amber-600 to-orange-600 text-white shadow-lg shadow-orange-500/30' : 'text-gray-700 hover:bg-gradient-to-r hover:from-orange-50 hover:via-amber-50 hover:to-orange-50 hover:text-orange-600' }}"
       :title="sidebarCollapsed ? 'Riesgos' : ''">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <span x-show="!sidebarCollapsed" 
              x-transition:enter="transition ease-out duration-200"
              x-transition:enter-start="opacity-0"
              x-transition:enter-end="opacity-100"
              x-transition:leave="transition ease-in duration-150"
              x-transition:leave-start="opacity-100"
              x-transition:leave-end="opacity-0"
              class="whitespace-nowrap">Riesgos</span>
    </a>
@endif

@if($isDirector)
    <a href="{{ route('decisions.index') }}" 
       :class="sidebarCollapsed ? 'justify-center px-3' : 'px-4'"
       class="group flex items-center gap-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('decisions.*') ? 'bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-600 text-white shadow-lg shadow-purple-500/30' : 'text-gray-700 hover:bg-gradient-to-r hover:from-purple-50 hover:via-indigo-50 hover:to-purple-50 hover:text-purple-600' }}"
       :title="sidebarCollapsed ? 'Decisiones' : ''">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <span x-show="!sidebarCollapsed" 
              x-transition:enter="transition ease-out duration-200"
              x-transition:enter-start="opacity-0"
              x-transition:enter-end="opacity-100"
              x-transition:leave="transition ease-in duration-150"
              x-transition:leave-start="opacity-100"
              x-transition:leave-end="opacity-0"
              class="whitespace-nowrap">Decisiones</span>
    </a>
@endif

@if($isDirector || $isManager)
    <a href="{{ route('clients.index') }}" 
       :class="sidebarCollapsed ? 'justify-center px-3' : 'px-4'"
       class="group flex items-center gap-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('clients.*') ? 'bg-gradient-to-r from-indigo-600 via-blue-600 to-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-gray-700 hover:bg-gradient-to-r hover:from-indigo-50 hover:via-blue-50 hover:to-indigo-50 hover:text-indigo-600' }}"
       :title="sidebarCollapsed ? 'Clientes' : ''">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <span x-show="!sidebarCollapsed" 
              x-transition:enter="transition ease-out duration-200"
              x-transition:enter-start="opacity-0"
              x-transition:enter-end="opacity-100"
              x-transition:leave="transition ease-in duration-150"
              x-transition:leave-start="opacity-100"
              x-transition:leave-end="opacity-0"
              class="whitespace-nowrap">Clientes</span>
    </a>
@endif

<a href="{{ route('search.index') }}" 
   :class="sidebarCollapsed ? 'justify-center px-3' : 'px-4'"
   class="group flex items-center gap-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('search.*') ? 'bg-gradient-to-r from-teal-600 via-cyan-600 to-teal-600 text-white shadow-lg shadow-teal-500/30' : 'text-gray-700 hover:bg-gradient-to-r hover:from-teal-50 hover:via-cyan-50 hover:to-teal-50 hover:text-teal-600' }}"
   :title="sidebarCollapsed ? 'Búsqueda' : ''">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
    </svg>
    <span x-show="!sidebarCollapsed" 
          x-transition:enter="transition ease-out duration-200"
          x-transition:enter-start="opacity-0"
          x-transition:enter-end="opacity-100"
          x-transition:leave="transition ease-in duration-150"
          x-transition:leave-start="opacity-100"
          x-transition:leave-end="opacity-0"
          class="whitespace-nowrap">Búsqueda</span>
</a>

@if($isDirector || $isManager)
    <a href="{{ route('tags.index') }}" 
       :class="sidebarCollapsed ? 'justify-center px-3' : 'px-4'"
       class="group flex items-center gap-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('tags.*') ? 'bg-gradient-to-r from-pink-600 via-rose-600 to-pink-600 text-white shadow-lg shadow-pink-500/30' : 'text-gray-700 hover:bg-gradient-to-r hover:from-pink-50 hover:via-rose-50 hover:to-pink-50 hover:text-pink-600' }}"
       :title="sidebarCollapsed ? 'Etiquetas' : ''">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
        </svg>
        <span x-show="!sidebarCollapsed" 
              x-transition:enter="transition ease-out duration-200"
              x-transition:enter-start="opacity-0"
              x-transition:enter-end="opacity-100"
              x-transition:leave="transition ease-in duration-150"
              x-transition:leave-start="opacity-100"
              x-transition:leave-end="opacity-0"
              class="whitespace-nowrap">Etiquetas</span>
    </a>
@endif
