@extends('layouts.dashboard')

@section('title', 'Dashboard - Visualización')

@section('breadcrumbs')
<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-red-600">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                Dashboard
            </a>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent">
        👁️ Dashboard Visualización
    </h1>
    <p class="text-gray-600 mt-2">Vista de solo lectura de dashboards y reportes</p>
</div>

<x-ui.alert variant="info" title="Modo Visualización">
    Tienes acceso de solo lectura. Puedes visualizar dashboards y reportes, pero no puedes crear ni editar contenido.
</x-ui.alert>

<!-- Estadísticas Generales -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 mt-8">
    <x-ui.card variant="gradient" border-color="blue">
        <div class="text-center">
            <p class="text-blue-100 text-xs font-medium uppercase tracking-wide">Total Planes</p>
            <p class="text-3xl font-bold mt-1 text-white">{{ $total_plans }}</p>
        </div>
    </x-ui.card>

    <x-ui.card variant="gradient" border-color="green">
        <div class="text-center">
            <p class="text-green-100 text-xs font-medium uppercase tracking-wide">KPIs Activos</p>
            <p class="text-3xl font-bold mt-1 text-white">{{ $total_kpis }}</p>
        </div>
    </x-ui.card>

    <x-ui.card variant="gradient" border-color="orange">
        <div class="text-center">
            <p class="text-orange-100 text-xs font-medium uppercase tracking-wide">Riesgos</p>
            <p class="text-3xl font-bold mt-1 text-white">{{ $total_risks }}</p>
        </div>
    </x-ui.card>
</div>

<!-- Estadísticas Adicionales -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
    <x-ui.card variant="gradient" border-color="purple">
        <div class="text-center">
            <p class="text-purple-100 text-xs font-medium uppercase tracking-wide">Total Tareas</p>
            <p class="text-3xl font-bold mt-1 text-white">{{ $total_tasks }}</p>
        </div>
    </x-ui.card>
</div>
@endsection

