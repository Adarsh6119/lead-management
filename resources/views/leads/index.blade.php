<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight">
                    📋 Lead Management
                </h2>
                <p class="text-xs text-slate-500 mt-1">Track enquiries, call sources, follow-up remarks, and conversion status.</p>
            </div>
            <a href="{{ route('leads.create') }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 text-slate-950 font-black text-sm rounded-xl shadow-md transition-all transform active:scale-95">
                ✨ + Add New Lead
            </a>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-xl text-sm font-bold shadow-sm">
                ✅ {{ session('success') }}
            </div>
            @endif

            <!-- Search & Filter Card -->
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
                <form method="GET" action="{{ route('leads.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 items-end">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Search Customer / Phone / City</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, mobile no, city..." class="w-full bg-slate-50 border-slate-300 rounded-lg text-xs font-medium focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Status Filter</label>
                        <select name="status" class="w-full bg-slate-50 border-slate-300 rounded-lg text-xs font-medium focus:ring-amber-500 focus:border-amber-500">
                            <option value="">All Statuses</option>
                            @foreach(['New Lead', 'Follow Up', 'Confirm Booking', 'Booking Cancelled', 'Close / Lost'] as $st)
                                <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Call Source</label>
                        <select name="source" class="w-full bg-slate-50 border-slate-300 rounded-lg text-xs font-medium focus:ring-amber-500 focus:border-amber-500">
                            <option value="">All Sources</option>
                            @foreach($sources as $src)
                                <option value="{{ $src }}" {{ request('source') == $src ? 'selected' : '' }}>{{ $src }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(Auth::user()->role !== 'employee')
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Assigned Employee</label>
                        <select name="employee_id" class="w-full bg-slate-50 border-slate-300 rounded-lg text-xs font-medium focus:ring-amber-500 focus:border-amber-500">
                            <option value="">All 15 Employees</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->login_id }} - {{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="flex items-center space-x-2">
                        <button type="submit" class="w-full py-2 bg-slate-900 text-white font-bold text-xs rounded-lg hover:bg-slate-800 transition-colors">
                            Filter
                        </button>
                        <a href="{{ route('leads.index') }}" class="py-2 px-3 bg-slate-200 text-slate-700 font-bold text-xs rounded-lg hover:bg-slate-300">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Leads Table Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-100 text-slate-700 uppercase font-extrabold text-[10px] tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="p-3.5">ID & Date</th>
                                <th class="p-3.5">Customer & Mobile</th>
                                <th class="p-3.5">Route & Trip</th>
                                <th class="p-3.5">Cab Requested</th>
                                <th class="p-3.5">Source</th>
                                <th class="p-3.5">Assigned Employee</th>
                                <th class="p-3.5">Status</th>
                                <th class="p-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($leads as $lead)
                            <tr class="hover:bg-amber-50/40 transition-colors">
                                <td class="p-3.5">
                                    <div class="font-extrabold text-slate-900 font-mono">#LEAD-{{ str_pad($lead->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    <div class="text-[10px] text-slate-400 font-semibold">{{ $lead->date_created }}</div>
                                </td>
                                <td class="p-3.5">
                                    <div class="font-bold text-slate-900">{{ $lead->customer_name ?: 'Valued Customer' }}</div>
                                    <div class="font-mono text-slate-600 font-bold flex items-center gap-1.5">
                                        <span>📞 {{ $lead->mobile_no }}</span>
                                    </div>
                                </td>
                                <td class="p-3.5">
                                    <div class="font-bold text-slate-800">{{ $lead->pickup_city ?: '—' }} ➔ {{ $lead->destination ?: '—' }}</div>
                                    <div class="text-[10px] text-slate-500 font-medium">
                                        Pickup: {{ $lead->pickup_date ? (is_string($lead->pickup_date) ? $lead->pickup_date : $lead->pickup_date->format('d M')) : 'TBD' }}
                                        @if($lead->return_date)
                                            · Return: {{ is_string($lead->return_date) ? $lead->return_date : $lead->return_date->format('d M') }}
                                        @endif
                                    </div>
                                </td>
                                <td class="p-3.5 font-semibold text-amber-800">
                                    {{ $lead->cab_type ?: 'Not Specified' }}
                                </td>
                                <td class="p-3.5">
                                    <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 font-extrabold text-[10px]">
                                        {{ $lead->source }}
                                    </span>
                                </td>
                                <td class="p-3.5 font-medium text-slate-700">
                                    {{ $lead->employee_name }}
                                </td>
                                <td class="p-3.5">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $lead->status_color }}">
                                        {{ $lead->status }}
                                    </span>
                                    @if($lead->tl_note)
                                        <div class="mt-1">
                                            <span class="px-2 py-0.5 rounded bg-purple-100 text-purple-900 border border-purple-300 font-extrabold text-[9px] inline-flex items-center gap-1">
                                                💡 TL Note Added
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="p-3.5 text-right space-x-1">
                                    <a href="{{ route('leads.show', $lead->id) }}" class="inline-flex items-center px-3 py-1.5 bg-slate-900 hover:bg-amber-500 hover:text-slate-950 text-white font-bold rounded-lg text-xs transition-colors">
                                        Manage & Remarks →
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-slate-400 font-medium">
                                    No leads found matching your search.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100 bg-slate-50">
                    {{ $leads->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
