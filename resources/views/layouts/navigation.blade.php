<!-- DESKTOP SIDEBAR -->
<aside class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0 z-50 bg-slate-950 border-r border-slate-800/80 text-slate-200 shadow-2xl font-sans">
    <!-- Brand Logo Header -->
    <div class="h-16 flex items-center px-5 bg-slate-900/80 border-b border-slate-800/80 justify-between">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-amber-400 to-amber-500 flex items-center justify-center text-slate-950 font-black text-xl shadow-lg shadow-amber-500/20 group-hover:scale-105 transition-transform duration-200">
                🚕
            </div>
            <div>
                <span class="font-black text-lg tracking-tight text-white group-hover:text-amber-400 transition-colors">TaxiCRM</span>
                <span class="block text-[9px] uppercase font-bold text-amber-400/90 tracking-widest">Lead & Booking Engine</span>
            </div>
        </a>
    </div>

    <!-- Logged in user pill -->
    <div class="p-3.5 mx-3 mt-3 bg-slate-900/90 rounded-xl border border-slate-800 flex items-center justify-between">
        <div class="overflow-hidden">
            <div class="text-xs font-black text-white truncate flex items-center gap-1">
                <span>{{ Auth::user()->name }}</span>
            </div>
            <div class="text-[10px] font-mono text-amber-400 font-bold flex items-center gap-1">
                <span>ID: {{ Auth::user()->login_id }}</span>
            </div>
        </div>
        <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider shrink-0 {{ Auth::user()->role === 'admin' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : (Auth::user()->role === 'accountant' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : (Auth::user()->isHead() ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : 'bg-blue-500/20 text-blue-300 border border-blue-500/30')) }}">
            {{ Auth::user()->isHead() ? 'TEAM LEAD' : Auth::user()->role }}
        </span>
    </div>

    <!-- Navigation Menu Items -->
    <div class="flex-1 overflow-y-auto px-3 py-4 space-y-6 scrollbar-thin">

        <!-- CORE MODULES -->
        <div>
            <div class="px-3 text-[10px] font-black uppercase tracking-widest text-slate-400/90 mb-2">
                📌 Core System
            </div>
            <nav class="space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-amber-400 text-slate-950 shadow-lg shadow-amber-400/20' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">
                    <span class="text-base mr-3">📊</span> Dashboard
                </a>
                <a href="{{ route('leads.index') }}" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('leads.*') ? 'bg-amber-400 text-slate-950 shadow-lg shadow-amber-400/20' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">
                    <span class="text-base mr-3">📋</span> Leads Management
                </a>
                <a href="{{ route('bookings.index') }}" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('bookings.*') ? 'bg-amber-400 text-slate-950 shadow-lg shadow-amber-400/20' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">
                    <span class="text-base mr-3">🚘</span> Bookings Engine
                </a>
            </nav>
        </div>

        <!-- FINANCIALS (Restricted from Head/TL) -->
        @if(in_array(Auth::user()->role, ['admin', 'accountant']))
        <div>
            <div class="px-3 text-[10px] font-black uppercase tracking-widest text-slate-400/90 mb-2">
                🧾 Finance & Billing
            </div>
            <nav class="space-y-1">
                <a href="{{ route('accounting.index') }}" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('accounting.*') ? 'bg-amber-400 text-slate-950 shadow-lg shadow-amber-400/20' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">
                    <span class="text-base mr-3">🧾</span> Accounting & GST
                </a>
            </nav>
        </div>
        @endif

        <!-- TEAM LEAD & ADMIN MONITORING -->
        @if(Auth::user()->isAdmin() || Auth::user()->isHead())
        <div>
            <div class="px-3 text-[10px] font-black uppercase tracking-widest text-amber-400 mb-2 flex items-center justify-between">
                <span>🛡️ {{ Auth::user()->isHead() ? 'TL Monitoring' : 'Admin Controls' }}</span>
                <span class="text-[9px] px-1.5 py-0.5 bg-amber-400/20 text-amber-300 rounded">{{ Auth::user()->isHead() ? 'HEAD / TL' : 'ADMIN' }}</span>
            </div>
            <nav class="space-y-1">
                <a href="{{ route('employees.performance') }}" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('employees.performance') ? 'bg-amber-400 text-slate-950 shadow-lg shadow-amber-400/20' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">
                    <span class="text-base mr-3">📈</span> Performance Reports
                </a>
                <a href="{{ route('employees.meeting-notes') }}" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('employees.meeting-notes') ? 'bg-amber-400 text-slate-950 shadow-lg shadow-amber-400/20' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">
                    <span class="text-base mr-3">📝</span> 1-on-1 Meeting Notes & Graphs
                </a>
                <a href="{{ route('employees.index') }}" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('employees.index', 'employees.create', 'employees.edit') ? 'bg-amber-400 text-slate-950 shadow-lg shadow-amber-400/20' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">
                    <span class="text-base mr-3">👥</span> Staff & Access Control
                </a>
                @if(Auth::user()->isAdmin())
                <a href="{{ route('cab-types.index') }}" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('cab-types.*') ? 'bg-amber-400 text-slate-950 shadow-lg shadow-amber-400/20' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">
                    <span class="text-base mr-3">🚕</span> Cab Types & Rates
                </a>
                @endif
            </nav>
        </div>
        @endif

        <!-- USER SETTINGS -->
        <div>
            <div class="px-3 text-[10px] font-black uppercase tracking-widest text-slate-400/90 mb-2">
                👤 My Account
            </div>
            <nav class="space-y-1">
                <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('profile.*') ? 'bg-amber-400 text-slate-950 shadow-lg shadow-amber-400/20' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">
                    <span class="text-base mr-3">⚙️</span> Profile Settings
                </a>
            </nav>
        </div>

    </div>

    <!-- Sidebar Footer / Logout -->
    <div class="p-3 border-t border-slate-800/80 bg-slate-900/60">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center px-3 py-2.5 bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 border border-rose-500/30 rounded-xl text-xs font-extrabold transition-all">
                <span class="mr-2">🚪</span> Log Out System
            </button>
        </form>
    </div>
</aside>

<!-- MOBILE SIDEBAR OVERLAY & DRAWER -->
<div x-show="sidebarOpen" class="relative z-50 md:hidden" x-ref="dialog" aria-modal="true" style="display: none;">
    <!-- Backdrop -->
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm"></div>

    <div class="fixed inset-0 flex">
        <div x-show="sidebarOpen"
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="relative mr-16 flex w-full max-w-xs flex-1 flex-col bg-slate-950 text-white pt-5 pb-4">

            <!-- Close button -->
            <div class="absolute top-0 right-0 -mr-12 pt-2">
                <button type="button" @click="sidebarOpen = false" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-amber-400">
                    <span class="sr-only">Close sidebar</span>
                    <span class="text-white text-xl font-bold">✕</span>
                </button>
            </div>

            <!-- Mobile Brand Header -->
            <div class="flex items-center px-5 space-x-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-amber-400 to-amber-500 flex items-center justify-center text-slate-950 font-black text-xl shadow-lg">
                    🚕
                </div>
                <div>
                    <span class="font-black text-xl text-white">TaxiCRM</span>
                    <span class="block text-[10px] uppercase font-bold text-amber-400">Lead & Booking Engine</span>
                </div>
            </div>

            <!-- Mobile User info -->
            <div class="px-5 mb-4">
                <div class="p-3 bg-slate-900 rounded-xl border border-slate-800">
                    <div class="text-xs font-bold text-white">{{ Auth::user()->name }}</div>
                    <div class="text-[10px] font-mono text-amber-400">ID: {{ Auth::user()->login_id }} | Role: {{ Auth::user()->role }}</div>
                </div>
            </div>

            <!-- Mobile Menu Links -->
            <div class="h-0 flex-1 overflow-y-auto px-4 space-y-4">
                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-xl text-sm font-bold {{ request()->routeIs('dashboard') ? 'bg-amber-400 text-slate-950' : 'text-slate-200 hover:bg-slate-900' }}">📊 Dashboard</a>
                    <a href="{{ route('leads.index') }}" class="block px-3 py-2 rounded-xl text-sm font-bold {{ request()->routeIs('leads.*') ? 'bg-amber-400 text-slate-950' : 'text-slate-200 hover:bg-slate-900' }}">📋 Leads Management</a>
                    <a href="{{ route('bookings.index') }}" class="block px-3 py-2 rounded-xl text-sm font-bold {{ request()->routeIs('bookings.*') ? 'bg-amber-400 text-slate-950' : 'text-slate-200 hover:bg-slate-900' }}">🚘 Bookings Engine</a>
                    @if(in_array(Auth::user()->role, ['admin', 'accountant']))
                    <a href="{{ route('accounting.index') }}" class="block px-3 py-2 rounded-xl text-sm font-bold {{ request()->routeIs('accounting.*') ? 'bg-amber-400 text-slate-950' : 'text-slate-200 hover:bg-slate-900' }}">🧾 Accounting & GST</a>
                    @endif
                    @if(Auth::user()->role === 'admin')
                    <div class="pt-2 text-xs font-extrabold text-amber-400 uppercase">Admin Controls</div>
                    <a href="{{ route('employees.performance') }}" class="block px-3 py-2 rounded-xl text-sm font-bold {{ request()->routeIs('employees.performance') ? 'bg-amber-400 text-slate-950' : 'text-slate-200 hover:bg-slate-900' }}">📈 Employee Performance</a>
                    <a href="{{ route('employees.index') }}" class="block px-3 py-2 rounded-xl text-sm font-bold {{ request()->routeIs('employees.index', 'employees.create', 'employees.edit') ? 'bg-amber-400 text-slate-950' : 'text-slate-200 hover:bg-slate-900' }}">👥 Staff Roster (15 Users)</a>
                    <a href="{{ route('cab-types.index') }}" class="block px-3 py-2 rounded-xl text-sm font-bold {{ request()->routeIs('cab-types.*') ? 'bg-amber-400 text-slate-950' : 'text-slate-200 hover:bg-slate-900' }}">🚕 Cab Types</a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-200 hover:bg-slate-900">⚙️ Profile Settings</a>
                </div>
            </div>

            <div class="px-4 pt-3 border-t border-slate-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full px-3 py-2 bg-rose-600/30 text-rose-300 border border-rose-500/30 rounded-xl text-xs font-bold">🚪 Log Out</button>
                </form>
            </div>
        </div>
    </div>
</div>
