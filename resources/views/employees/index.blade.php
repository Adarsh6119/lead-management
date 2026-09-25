<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight">👥 Employee & Staff Roster</h2>
                <p class="text-xs text-slate-500 mt-1">Manage personal Login IDs for 15+ employees, roles, and access credentials.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('employees.performance') }}" class="px-4 py-2.5 bg-amber-400 hover:bg-amber-500 text-slate-950 font-black rounded-xl text-xs shadow-md flex items-center gap-1.5 transition-all">
                    <span>📈 Targets & Performance</span>
                </a>
                @if(Auth::user()->isAdmin() || Auth::user()->isHead())
                <a href="{{ route('employees.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-md shadow-indigo-600/30 flex items-center gap-2 transition-all">
                    <span>➕ Add New Employee</span>
                </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold flex items-center justify-between">
                <span>✅ {{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-semibold flex items-center justify-between">
                <span>❌ {{ session('error') }}</span>
            </div>
        @endif

        <!-- Summary Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Staff</div>
                <div class="text-3xl font-black text-slate-900 mt-1">{{ $employees->count() }}</div>
                <div class="text-xs text-emerald-600 font-semibold mt-1">Active Accounts</div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <div class="text-xs font-bold text-amber-500 uppercase tracking-wider">Sales Employees</div>
                <div class="text-3xl font-black text-amber-600 mt-1">{{ $employees->filter(fn($e) => strtolower(trim($e->role ?? '')) === 'employee')->count() }}</div>
                <div class="text-xs text-slate-400 mt-1">Handling Leads & Bookings</div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <div class="text-xs font-bold text-emerald-500 uppercase tracking-wider">Accountants</div>
                <div class="text-3xl font-black text-emerald-600 mt-1">{{ $employees->filter(fn($e) => $e->isAccountant())->count() }}</div>
                <div class="text-xs text-slate-400 mt-1">Managing GST & Billing</div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <div class="text-xs font-bold text-rose-500 uppercase tracking-wider">Admins</div>
                <div class="text-3xl font-black text-rose-600 mt-1">{{ $employees->filter(fn($e) => $e->isAdmin())->count() }}</div>
                <div class="text-xs text-slate-400 mt-1">System Administrators</div>
            </div>
        </div>

        <!-- Employee Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 text-sm">All Registered System Users ({{ $employees->count() }})</h3>
                <span class="text-xs text-slate-500">Personal Login IDs auto-configured</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100/70 border-b border-slate-200 text-slate-500 uppercase text-[11px] font-bold tracking-wider">
                            <th class="py-3 px-4">Login ID</th>
                            <th class="py-3 px-4">Employee Name</th>
                            <th class="py-3 px-4">Role</th>
                            <th class="py-3 px-4">Contact Phone</th>
                            <th class="py-3 px-4">Email</th>
                            <th class="py-3 px-4 text-center">Leads Created</th>
                            <th class="py-3 px-4 text-center">Bookings Done</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach($employees as $emp)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4 font-mono font-bold text-indigo-700">
                                    <span class="px-2.5 py-1 bg-indigo-50 border border-indigo-200 rounded-md">
                                        🔑 {{ $emp->login_id }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    {{ $emp->name }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold uppercase tracking-wide {{ $emp->isAdmin() ? 'bg-rose-100 text-rose-800 border border-rose-200' : ($emp->isAccountant() ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : ($emp->isHead() ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-amber-100 text-amber-800 border border-amber-200')) }}">
                                        {{ $emp->isHead() ? 'HEAD / TL' : strtoupper($emp->role) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-slate-600">
                                    {{ $emp->phone ?? '—' }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-500 text-xs font-mono">
                                    {{ $emp->email }}
                                </td>
                                <td class="py-3.5 px-4 text-center font-bold text-slate-800">
                                    {{ $emp->leads_count ?? 0 }}
                                </td>
                                <td class="py-3.5 px-4 text-center font-bold text-emerald-700">
                                    {{ $emp->bookings_count ?? 0 }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($emp->status === 'active' || $emp->is_active)
                                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 rounded text-xs font-bold">Active</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-rose-100 text-rose-800 rounded text-xs font-bold">Inactive</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right space-x-1.5 flex items-center justify-end">
                                    <a href="{{ route('employees.meeting-notes', $emp->id) }}" class="px-2.5 py-1 bg-purple-100 hover:bg-purple-200 text-purple-900 rounded-lg text-xs font-bold border border-purple-300 transition-colors" title="1-on-1 Meeting Notes & Performance Analytics Graph">
                                        📝 Notes & Graph
                                    </a>
                                    
                                    @if(Auth::user()->isAdmin() || (Auth::user()->isHead() && strtolower(trim($emp->role ?? '')) === 'employee'))
                                        <form method="POST" action="{{ route('employees.toggle-access', $emp->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 rounded-lg text-xs font-bold border transition-colors {{ ($emp->status === 'active' || $emp->is_active) ? 'bg-rose-50 text-rose-700 border-rose-300 hover:bg-rose-100' : 'bg-emerald-50 text-emerald-700 border-emerald-300 hover:bg-emerald-100' }}">
                                                {{ ($emp->status === 'active' || $emp->is_active) ? '🚫 Revoke Access' : '✅ Grant Access' }}
                                            </button>
                                        </form>

                                        <a href="{{ route('employees.edit', $emp->id) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold border border-slate-300 transition-colors">
                                            ✏️ Edit
                                        </a>

                                        @if(Auth::user()->id !== $emp->id)
                                            <form method="POST" action="{{ route('employees.destroy', $emp->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete employee {{ $emp->name }} ({{ $emp->login_id }})? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold shadow-sm transition-colors" title="Permanently Delete Employee">
                                                    🗑️ Delete
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-400 border border-slate-200 cursor-not-allowed inline-flex items-center gap-1" title="TL cannot alter Admin, Accountant or TL accounts">
                                            🔒 Restricted
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
