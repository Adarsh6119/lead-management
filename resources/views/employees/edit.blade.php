<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight">✏️ Edit Employee Account</h2>
                <p class="text-xs text-slate-500 mt-1">Updating profile details for {{ $employee->name }} ({{ $employee->login_id }})</p>
            </div>
            <a href="{{ route('employees.index') }}" class="px-3.5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-xs transition-colors">
                ← Back to Roster
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden p-6">
            
            <form action="{{ route('employees.update', $employee->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Login ID -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Personal Login ID <span class="text-rose-500">*</span></label>
                        <input type="text" name="login_id" required value="{{ old('login_id', $employee->login_id) }}"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-mono font-bold text-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all uppercase">
                        @error('login_id') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Full Name -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Full Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required value="{{ old('name', $employee->name) }}"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Role <span class="text-rose-500">*</span></label>
                        <select name="role" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            <option value="employee" {{ old('role', $employee->role) === 'employee' ? 'selected' : '' }}>Employee</option>
                            <option value="head" {{ in_array(old('role', $employee->role), ['head', 'tl', 'team_lead']) ? 'selected' : '' }}>Head / Team Lead</option>
                            <option value="accountant" {{ old('role', $employee->role) === 'accountant' ? 'selected' : '' }}>Accountant</option>
                            <option value="admin" {{ old('role', $employee->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Account Status <span class="text-rose-500">*</span></label>
                        <select name="status" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            <option value="active" {{ old('status', $employee->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $employee->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-mono text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('phone') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Address <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" required value="{{ old('email', $employee->email) }}"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-mono text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        @error('email') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Change Password (Optional) -->
                    <div class="md:col-span-2 bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Change Password (Leave blank to keep existing)</label>
                        <input type="password" name="password" placeholder="Enter new password to reset"
                            class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500 transition-all">
                        @error('password') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200 flex justify-end space-x-3">
                    <a href="{{ route('employees.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-sm transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-xl text-sm shadow-lg shadow-indigo-600/30 transition-all">
                        Save Changes 💾
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
