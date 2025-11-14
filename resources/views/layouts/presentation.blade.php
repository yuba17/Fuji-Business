<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="presentationMode()" x-cloak>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Strategos') }} - Presentación</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-900">
    <div class="min-h-screen flex flex-col">
        <!-- Presentation Header (minimal) -->
        <header class="bg-gray-900/80 backdrop-blur-sm border-b border-gray-800 px-6 py-3 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <h1 class="text-lg font-bold text-white">Strategos</h1>
                <span class="text-sm text-gray-400">Modo Presentación</span>
            </div>
            <div class="flex items-center gap-3">
                <button @click="exitPresentation()" 
                        class="px-4 py-2 text-sm font-semibold text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition-colors">
                    Salir (ESC)
                </button>
            </div>
        </header>

        <!-- Presentation Content -->
        <main class="flex-1 overflow-y-auto">
            <div class="container mx-auto px-8 py-12">
                @yield('content')
            </div>
        </main>

        <!-- Navigation Controls -->
        <div class="fixed bottom-6 right-6 flex items-center gap-2">
            <button @click="previousSlide()" 
                    class="p-3 bg-gray-800/80 backdrop-blur-sm text-white rounded-lg hover:bg-gray-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <span class="px-4 py-2 bg-gray-800/80 backdrop-blur-sm text-white text-sm font-medium rounded-lg">
                <span x-text="currentSlide"></span> / <span x-text="totalSlides"></span>
            </span>
            <button @click="nextSlide()" 
                    class="p-3 bg-gray-800/80 backdrop-blur-sm text-white rounded-lg hover:bg-gray-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </div>

    <script>
        function presentationMode() {
            return {
                currentSlide: 1,
                totalSlides: {{ $totalSlides ?? 1 }},
                
                init() {
                    // Navegación por teclado
                    window.addEventListener('keydown', (e) => {
                        if (e.key === 'ArrowRight' || e.key === ' ') {
                            this.nextSlide();
                        } else if (e.key === 'ArrowLeft') {
                            this.previousSlide();
                        } else if (e.key === 'Escape') {
                            this.exitPresentation();
                        }
                    });
                },
                
                nextSlide() {
                    if (this.currentSlide < this.totalSlides) {
                        this.currentSlide++;
                    }
                },
                
                previousSlide() {
                    if (this.currentSlide > 1) {
                        this.currentSlide--;
                    }
                },
                
                exitPresentation() {
                    window.location.href = '{{ route('dashboard') }}';
                }
            }
        }
    </script>

    @livewireScripts
</body>
</html>

