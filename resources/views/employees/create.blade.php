<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight">➕ Add New Employee / Staff</h2>
                <p class="text-xs text-slate-500 mt-1">Assign personal Login ID for 15+ staff system access.</p>
            </div>
            <a href="{{ route('employees.index') }}" class="px-3.5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-xs transition-colors">
                ← Back to Roster
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden p-6">
            
            <form action="{{ route('employees.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Login ID -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Personal Login ID <span class="text-rose-500">*</span></label>
                        <input type="text" name="login_id" required value="{{ old('login_id', 'EMP' . sprintf('%03d', \App\Models\User::count() + 1)) }}"
                            placeholder="e.g. EMP016"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-mono font-bold text-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all uppercase">
                        <p class="text-[11px] text-slate-400 mt-1">Used by employee to sign into portal.</p>
                        @error('login_id') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Full Name -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Full Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required value="{{ old('name') }}" placeholder="e.g. Rajesh Kumar"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Role & Permissions <span class="text-rose-500">*</span></label>
                        <select name="role" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            <option value="employee" {{ old('role') === 'employee' ? 'selected' : '' }}>Employee (Leads & Booking Entry)</option>
                            @if(Auth::user()->isAdmin())
                                <option value="head" {{ old('role') === 'head' ? 'selected' : '' }}>Head / Team Lead (Monitoring & Access Control)</option>
                                <option value="accountant" {{ old('role') === 'accountant' ? 'selected' : '' }}>Accountant (GST & Ledger)</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin (Full System Access)</option>
                            @endif
                        </select>
                        @error('role') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Phone / Mobile No.</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="e.g. 9876543210"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-mono text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('phone') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Address <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" required value="{{ old('email') }}" placeholder="e.g. rajesh@taxicrm.com"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-mono text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('email') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Account Password <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" required placeholder="Minimum 6 characters"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('password') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200 flex justify-end space-x-3">
                    <a href="{{ route('employees.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-sm transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-xl text-sm shadow-lg shadow-indigo-600/30 transition-all">
                        Create Employee Account 🚀
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
