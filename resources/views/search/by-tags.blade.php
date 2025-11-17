@extends('layouts.dashboard')

@section('title', 'Búsqueda por Etiquetas')

@section('breadcrumbs')
<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-red-600">
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                <span class="truncate">Dashboard</span>
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('search.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-red-600 md:ml-2">Búsqueda</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Por Etiquetas</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent break-words">
        🏷️ Búsqueda por Etiquetas
    </h1>
    <p class="text-gray-600 mt-2">Busca contenido por etiquetas.</p>
</div>

@if(!empty($results))
    <div class="space-y-6">
        @if(isset($results['plans']) && $results['plans']->count() > 0)
            <x-ui.card>
                <h2 class="text-xl font-bold text-gray-900 mb-4">Planes ({{ $results['plans']->count() }})</h2>
                <div class="space-y-3">
                    @foreach($results['plans'] as $plan)
                        <a href="{{ route('plans.show', $plan) }}" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $plan->name }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($plan->description, 150) }}</p>
                        </a>
                    @endforeach
                </div>
            </x-ui.card>
        @endif

        @if(isset($results['tasks']) && $results['tasks']->count() > 0)
            <x-ui.card>
                <h2 class="text-xl font-bold text-gray-900 mb-4">Tareas ({{ $results['tasks']->count() }})</h2>
                <div class="space-y-3">
                    @foreach($results['tasks'] as $task)
                        <a href="{{ route('tasks.show', $task) }}" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $task->title }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($task->description, 150) }}</p>
                        </a>
                    @endforeach
                </div>
            </x-ui.card>
        @endif

        @if(isset($results['risks']) && $results['risks']->count() > 0)
            <x-ui.card>
                <h2 class="text-xl font-bold text-gray-900 mb-4">Riesgos ({{ $results['risks']->count() }})</h2>
                <div class="space-y-3">
                    @foreach($results['risks'] as $risk)
                        <a href="{{ route('risks.show', $risk) }}" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $risk->name }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($risk->description, 150) }}</p>
                        </a>
                    @endforeach
                </div>
            </x-ui.card>
        @endif

        @if(isset($results['decisions']) && $results['decisions']->count() > 0)
            <x-ui.card>
                <h2 class="text-xl font-bold text-gray-900 mb-4">Decisiones ({{ $results['decisions']->count() }})</h2>
                <div class="space-y-3">
                    @foreach($results['decisions'] as $decision)
                        <a href="{{ route('decisions.show', $decision) }}" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $decision->title }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($decision->description, 150) }}</p>
                        </a>
                    @endforeach
                </div>
            </x-ui.card>
        @endif
    </div>
@else
    <x-ui.card>
        <div class="text-center py-12 text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <p class="text-sm mb-4">No se encontraron resultados para las etiquetas seleccionadas.</p>
            <a href="{{ route('search.index') }}" class="text-sm font-medium text-teal-600 hover:text-teal-700">
                Volver a búsqueda avanzada →
            </a>
        </div>
    </x-ui.card>
@endif
@endsection

