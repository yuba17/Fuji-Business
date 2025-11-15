<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Strategos') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: window.innerWidth >= 1024, sidebarCollapsed: false }" x-init="if (localStorage.getItem('sidebarCollapsed') === 'true') { sidebarCollapsed = true; }">
    <div class="min-h-screen flex">
        <!-- Sidebar Component -->
        <x-layout.sidebar :collapsed="false" />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden transition-all duration-300" :class="sidebarCollapsed && window.innerWidth >= 1024 ? 'lg:ml-20' : 'lg:ml-0'">
            <!-- Header Component -->
            <x-layout.header />

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
                    @if(session('success'))
                        <x-ui.alert variant="success" class="mb-4">
                            {{ session('success') }}
                        </x-ui.alert>
                    @endif

                    @if(session('error'))
                        <x-ui.alert variant="error" class="mb-4">
                            {{ session('error') }}
                        </x-ui.alert>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen && window.innerWidth < 1024" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden"
         style="display: none;"></div>

    @livewireScripts
</body>
</html>

