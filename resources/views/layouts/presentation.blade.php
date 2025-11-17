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
                <span class="text-sm text-gray-400" x-text="presenterMode ? 'Modo Presentador' : 'Modo Presentación'"></span>
            </div>
            <div class="flex items-center gap-3">
                <button @click="togglePresenterMode()" 
                        class="px-4 py-2 text-sm font-semibold text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition-colors">
                    <span x-text="presenterMode ? 'Vista Normal' : 'Modo Presentador'"></span>
                </button>
                <button @click="exitPresentation()" 
                        class="px-4 py-2 text-sm font-semibold text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition-colors">
                    Salir (ESC)
                </button>
            </div>
        </header>

        <!-- Presentation Content -->
        <main class="flex-1 overflow-hidden" :class="presenterMode ? 'flex' : ''">
            <!-- Vista Normal -->
            <div :class="presenterMode ? 'hidden' : 'w-full'">
                @yield('content')
            </div>
            
            <!-- Modo Presentador -->
            <template x-if="presenterMode">
                <div class="flex w-full h-full">
                    <!-- Pantalla Principal (para proyector) -->
                    <div class="flex-1 bg-gray-900">
                        @yield('content')
                    </div>
                    
                    <!-- Panel del Presentador -->
                    <div class="w-96 bg-gray-800 border-l border-gray-700 flex flex-col">
                        <!-- Información de la Slide Actual -->
                        <div class="p-4 border-b border-gray-700">
                            <div class="text-xs text-gray-400 mb-1">Slide Actual</div>
                            <div class="text-lg font-bold text-white" x-text="currentSlide"></div>
                            <div class="text-sm text-gray-300 mt-1" x-text="'de ' + totalSlides"></div>
                        </div>
                        
                        <!-- Vista Previa de la Siguiente Slide -->
                        <div class="p-4 border-b border-gray-700">
                            <div class="text-xs text-gray-400 mb-2">Siguiente Slide</div>
                            <div class="bg-gray-900 rounded-lg p-3 text-white text-sm min-h-[200px]">
                                <div x-show="currentSlide < totalSlides" class="text-gray-400">
                                    Preparando siguiente slide...
                                </div>
                                <div x-show="currentSlide >= totalSlides" class="text-gray-500 italic">
                                    Fin de la presentación
                                </div>
                            </div>
                        </div>
                        
                        <!-- Notas del Presentador -->
                        <div class="flex-1 p-4 border-b border-gray-700 overflow-y-auto">
                            <div class="text-xs text-gray-400 mb-2">Notas</div>
                            <div class="text-sm text-gray-300 whitespace-pre-wrap" x-html="currentNotes"></div>
                            <div x-show="!currentNotes" class="text-gray-500 italic text-sm">
                                No hay notas para esta slide
                            </div>
                        </div>
                        
                        <!-- Temporizador -->
                        <div class="p-4 border-b border-gray-700">
                            <div class="text-xs text-gray-400 mb-2">Tiempo</div>
                            <div class="text-2xl font-bold text-white" x-text="formatTime(presentationTime)"></div>
                            <div class="flex gap-2 mt-2">
                                <button @click="resetTimer()" class="flex-1 px-3 py-1.5 text-xs bg-gray-700 hover:bg-gray-600 text-white rounded transition-colors">
                                    Reiniciar
                                </button>
                                <button @click="pauseTimer()" class="flex-1 px-3 py-1.5 text-xs bg-gray-700 hover:bg-gray-600 text-white rounded transition-colors" x-text="timerPaused ? 'Reanudar' : 'Pausar'"></button>
                            </div>
                        </div>
                        
                        <!-- Controles Rápidos -->
                        <div class="p-4">
                            <div class="text-xs text-gray-400 mb-2">Controles</div>
                            <div class="grid grid-cols-2 gap-2">
                                <button @click="previousSlide()" class="px-3 py-2 text-xs bg-gray-700 hover:bg-gray-600 text-white rounded transition-colors">
                                    ← Anterior
                                </button>
                                <button @click="nextSlide()" class="px-3 py-2 text-xs bg-gray-700 hover:bg-gray-600 text-white rounded transition-colors">
                                    Siguiente →
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
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
                presenterMode: false,
                presentationTime: 0,
                timerPaused: false,
                timerInterval: null,
                slideNotes: @json($slideNotes ?? []),
                
                init() {
                    // Navegación por teclado
                    window.addEventListener('keydown', (e) => {
                        if (e.key === 'ArrowRight' || (e.key === ' ' && !e.shiftKey)) {
                            e.preventDefault();
                            this.nextSlide();
                        } else if (e.key === 'ArrowLeft' || (e.key === ' ' && e.shiftKey)) {
                            e.preventDefault();
                            this.previousSlide();
                        } else if (e.key === 'Escape') {
                            this.exitPresentation();
                        } else if (e.key === 'p' || e.key === 'P') {
                            if (e.ctrlKey || e.metaKey) {
                                e.preventDefault();
                                this.togglePresenterMode();
                            }
                        }
                    });
                    
                    // Iniciar temporizador
                    this.startTimer();
                },
                
                get currentNotes() {
                    return this.slideNotes[this.currentSlide] || '';
                },
                
                togglePresenterMode() {
                    this.presenterMode = !this.presenterMode;
                },
                
                startTimer() {
                    this.timerInterval = setInterval(() => {
                        if (!this.timerPaused) {
                            this.presentationTime++;
                        }
                    }, 1000);
                },
                
                pauseTimer() {
                    this.timerPaused = !this.timerPaused;
                },
                
                resetTimer() {
                    this.presentationTime = 0;
                    this.timerPaused = false;
                },
                
                formatTime(seconds) {
                    const hours = Math.floor(seconds / 3600);
                    const minutes = Math.floor((seconds % 3600) / 60);
                    const secs = seconds % 60;
                    
                    if (hours > 0) {
                        return `${hours}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                    }
                    return `${minutes}:${secs.toString().padStart(2, '0')}`;
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
                    if (this.timerInterval) {
                        clearInterval(this.timerInterval);
                    }
                    if (window.presentationReturnUrl) {
                        window.location.href = window.presentationReturnUrl;
                    } else if (window.opener) {
                        window.close();
                    } else {
                        window.history.back();
                    }
                }
            }
        }
    </script>

    @stack('scripts')
    @livewireScripts
</body>
</html>

