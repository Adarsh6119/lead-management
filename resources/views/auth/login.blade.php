<x-guest-layout>
    <div class="min-h-screen bg-slate-950 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden font-sans">
        <!-- Background Ambient Glow -->
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="sm:mx-auto sm:w-full sm:max-w-md z-10">
            <div class="flex justify-center">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-amber-400 to-amber-500 flex items-center justify-center text-slate-950 font-black text-3xl shadow-xl shadow-amber-500/20 transform hover:rotate-6 transition-transform">
                    🚕
                </div>
            </div>
            <h2 class="mt-4 text-center text-3xl font-black tracking-tight text-white">
                TaxiCRM Portal
            </h2>
            <p class="mt-1 text-center text-sm text-amber-400/90 font-medium">
                Lead Management & Booking System
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-lg z-10">
            <div class="bg-slate-900/90 border border-slate-800 backdrop-blur-xl py-8 px-6 shadow-2xl rounded-2xl sm:px-10">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form id="loginForm" method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Employee Login ID / Email -->
                    <div>
                        <label for="login_id" class="block text-sm font-semibold text-slate-300">
                            Employee Personal Login ID / Email
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                🔑
                            </div>
                            <input id="login_id" name="login_id" type="text" required autofocus
                                value="{{ old('login_id', 'ADMIN01') }}"
                                autocomplete="username"
                                placeholder="e.g. EMP001, ADMIN01, ACCT01"
                                class="block w-full pl-10 pr-3 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent text-sm font-mono tracking-wider transition-all" />
                        </div>
                        <x-input-error :messages="$errors->get('login_id')" class="mt-2 text-rose-400 text-xs" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-300">
                            Password
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                🔒
                            </div>
                            <input id="password" name="password" type="password" required
                                value="admin123"
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="block w-full pl-10 pr-3 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent text-sm transition-all" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-400 text-xs" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox"
                                class="h-4 w-4 bg-slate-950 border-slate-700 rounded text-amber-400 focus:ring-amber-400 focus:ring-offset-slate-900" />
                            <label for="remember_me" class="ml-2 block text-xs text-slate-400">
                                Keep me logged in
                            </label>
                        </div>
                    </div>

                    <div>
                        <button type="submit" id="submitBtn"
                            class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-extrabold text-slate-950 bg-gradient-to-r from-amber-400 via-amber-300 to-yellow-400 hover:from-amber-300 hover:to-amber-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-400 transform active:scale-95 transition-all">
                            Sign In to TaxiCRM Dashboard 🚀
                        </button>
                    </div>
                </form>

                <!-- Quick Demo Login Credentials Card -->
                <div class="mt-8 pt-6 border-t border-slate-800">
                    <div class="text-xs font-bold uppercase tracking-wider text-amber-400/90 mb-3 flex items-center justify-between">
                        <span>⚡ 1-Click Quick Demo Login</span>
                        <span class="text-[10px] text-slate-400 font-normal">Click to fill & login</span>
                    </div>

                    <div class="grid grid-cols-3 gap-2 text-xs">
                        <button type="button" onclick="quickLogin('ADMIN01', 'admin123')"
                            class="p-2.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 text-rose-300 text-left transition-colors">
                            <div class="font-bold flex items-center justify-between">ADMIN01 <span>⚡</span></div>
                            <div class="text-[10px] text-slate-400">Admin (admin123)</div>
                        </button>

                        <button type="button" onclick="quickLogin('ACCT01', 'accounts123')"
                            class="p-2.5 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-left transition-colors">
                            <div class="font-bold flex items-center justify-between">ACCT01 <span>⚡</span></div>
                            <div class="text-[10px] text-slate-400">Accounts (accounts123)</div>
                        </button>

                        <button type="button" onclick="quickLogin('EMP001', 'password123')"
                            class="p-2.5 rounded-lg bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 text-amber-300 text-left transition-colors">
                            <div class="font-bold flex items-center justify-between">EMP001 <span>⚡</span></div>
                            <div class="text-[10px] text-slate-400">Emp #1 (password123)</div>
                        </button>
                    </div>

                    <!-- 15 Employees Selector -->
                    <div class="mt-3 bg-slate-950 p-2.5 rounded-xl border border-slate-800">
                        <label class="block text-[11px] text-slate-400 mb-1 font-medium">Select Employee to Auto-Login (EMP001 to EMP015):</label>
                        <select onchange="if(this.value) quickLogin(this.value, 'password123')" class="w-full bg-slate-900 border border-slate-700 rounded-lg text-xs text-slate-300 p-2.5 focus:ring-amber-400 font-mono">
                            <option value="">-- Click to Select & Sign In --</option>
                            @for ($i = 1; $i <= 15; $i++)
                                @php $empCode = sprintf('EMP%03d', $i); @endphp
                                <option value="{{ $empCode }}">{{ $empCode }} — Employee {{ $i }} (Password: password123)</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function quickLogin(loginId, pass) {
            if (!loginId) return;
            var idEl = document.getElementById('login_id');
            var passEl = document.getElementById('password');
            idEl.value = loginId;
            passEl.value = pass;
            document.getElementById('loginForm').submit();
        }
    </script>
</x-guest-layout>
