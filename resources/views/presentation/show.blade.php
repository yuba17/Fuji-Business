@extends('layouts.presentation')

@push('scripts')
<script>
    // Pasar la URL de retorno al layout
    window.presentationReturnUrl = '{{ route('plans.show', $plan) }}';
</script>
@endpush

@section('content')
<div class="h-full overflow-hidden">
    <!-- Slide 1: Portada -->
    <div x-show="currentSlide === 1" 
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         class="min-h-screen flex items-center justify-center bg-gradient-to-br from-red-600 via-orange-600 to-red-700 text-white">
        <div class="text-center max-w-4xl px-8">
            <h1 class="text-6xl md:text-7xl font-bold mb-6">{{ $plan->name }}</h1>
            <p class="text-2xl md:text-3xl text-red-100 mb-8">{{ $plan->description }}</p>
            <div class="flex items-center justify-center gap-6 text-lg">
                @if($plan->planType)
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <span>{{ $plan->planType->name }}</span>
                    </div>
                @endif
                @if($plan->area)
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>{{ $plan->area->name }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @php
        $slideNumber = 2;
    @endphp

    <!-- Slides de Secciones -->
    @foreach($plan->sections as $section)
        <div x-show="currentSlide === {{ $slideNumber }}" 
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 transform translate-x-10"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             class="min-h-screen flex flex-col justify-center bg-white text-gray-900 px-12 py-16">
            <div class="max-w-5xl mx-auto">
                <div class="mb-8">
                    <span class="text-sm font-semibold text-red-600 uppercase tracking-wide">Sección {{ $loop->iteration }}</span>
                    <h2 class="text-5xl font-bold mt-2 mb-6">{{ $section->title }}</h2>
                </div>
                <div class="prose prose-lg max-w-none">
                    {!! nl2br(e($section->content)) !!}
                </div>
            </div>
        </div>
        @php
            $slideNumber++;
        @endphp
    @endforeach

    <!-- Slide de KPIs -->
    @if($plan->kpis->count() > 0)
        <div x-show="currentSlide === {{ $slideNumber }}" 
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 transform translate-x-10"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             class="min-h-screen flex flex-col justify-center bg-gradient-to-br from-blue-50 to-cyan-50 px-12 py-16">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-5xl font-bold mb-12 text-gray-900">Indicadores Clave (KPIs)</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($plan->kpis->take(6) as $kpi)
                        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $kpi->name }}</h3>
                            <p class="text-4xl font-bold text-blue-600 mb-2">{{ number_format($kpi->current_value, 2) }} {{ $kpi->unit }}</p>
                            <p class="text-sm text-gray-600">Objetivo: {{ number_format($kpi->target_value, 2) }} {{ $kpi->unit }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @php
            $slideNumber++;
        @endphp
    @endif

    <!-- Slide de Milestones -->
    @if($plan->milestones->count() > 0)
        <div x-show="currentSlide === {{ $slideNumber }}" 
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 transform translate-x-10"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             class="min-h-screen flex flex-col justify-center bg-gradient-to-br from-purple-50 to-pink-50 px-12 py-16">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-5xl font-bold mb-12 text-gray-900">Hitos del Plan</h2>
                <div class="space-y-4">
                    @foreach($plan->milestones->take(8) as $milestone)
                        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $milestone->name }}</h3>
                                    <p class="text-gray-600">{{ $milestone->description }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-semibold text-gray-900">{{ $milestone->target_date->format('d/m/Y') }}</p>
                                    <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-lg">{{ $milestone->status_label }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @php
            $slideNumber++;
        @endphp
    @endif

    <!-- Slide de Riesgos -->
    @if($plan->risks->count() > 0)
        <div x-show="currentSlide === {{ $slideNumber }}" 
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 transform translate-x-10"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             class="min-h-screen flex flex-col justify-center bg-gradient-to-br from-orange-50 to-red-50 px-12 py-16">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-5xl font-bold mb-12 text-gray-900">Riesgos Identificados</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($plan->risks->take(6) as $risk)
                        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 {{ $risk->category === 'critico' ? 'border-red-500' : ($risk->category === 'alto' ? 'border-orange-500' : 'border-yellow-500') }}">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $risk->title }}</h3>
                            <p class="text-gray-600 mb-3">{{ Str::limit($risk->description, 150) }}</p>
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 {{ $risk->category === 'critico' ? 'bg-red-100 text-red-800' : ($risk->category === 'alto' ? 'bg-orange-100 text-orange-800' : 'bg-yellow-100 text-yellow-800') }} text-xs font-semibold rounded-lg">
                                    {{ ucfirst($risk->category) }}
                                </span>
                                <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-lg">
                                    {{ ucfirst($risk->probability) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @php
            $slideNumber++;
        @endphp
    @endif

    <!-- Slide Final -->
    <div x-show="currentSlide === {{ $slideNumber }}" 
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white">
        <div class="text-center max-w-4xl px-8">
            <h1 class="text-6xl md:text-7xl font-bold mb-6">Gracias</h1>
            <p class="text-2xl md:text-3xl text-gray-300 mb-8">Strategos - Gestión Estratégica</p>
            <div class="flex items-center justify-center gap-4 text-lg text-gray-400">
                <span>{{ now()->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

