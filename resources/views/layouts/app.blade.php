<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TaxiCRM') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-900 bg-slate-100">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen flex flex-col md:flex-row">
            
            <!-- Left Sidebar Navigation -->
            @include('layouts.navigation')

            <!-- Main Content Area -->
            <div class="flex-1 md:pl-64 flex flex-col min-w-0 min-h-screen">
                
                <!-- Top Header Bar -->
                <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
                    <div class="px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
                        
                        <!-- Mobile Hamburger Button & Title -->
                        <div class="flex items-center gap-3">
                            <button type="button" @click="sidebarOpen = true" class="md:hidden p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            <div class="hidden sm:flex items-center gap-2 text-xs text-slate-400 font-extrabold uppercase tracking-wider">
                                <span>🚕 TaxiCRM Control Panel</span>
                            </div>
                        </div>

                        <!-- Header Right Quick Actions -->
                        <div class="flex items-center gap-3">
                            <a href="{{ route('leads.create') }}" class="px-3.5 py-1.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white text-xs font-extrabold rounded-xl shadow-md shadow-emerald-600/20 flex items-center gap-1.5 transition-all">
                                <span>➕ Add New Lead</span>
                            </a>
                            <div class="h-4 w-px bg-slate-200 hidden sm:block"></div>
                            <div class="text-right hidden sm:block">
                                <div class="text-xs font-black text-slate-800">{{ Auth::user()->name }}</div>
                                <div class="text-[10px] text-indigo-600 font-mono font-bold">🔑 {{ Auth::user()->login_id }}</div>
                            </div>
                        </div>
                    </div>

                    @isset($header)
                        <div class="bg-slate-50/80 border-t border-slate-200/80 px-4 sm:px-6 lg:px-8 py-4">
                            {{ $header }}
                        </div>
                    @endisset
                </header>

                <!-- Main Page Body -->
                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
