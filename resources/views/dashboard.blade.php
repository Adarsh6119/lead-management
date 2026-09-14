<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight flex items-center gap-2">
                    <span>📊 Lead & Booking Analytics</span>
                    <span class="text-xs font-semibold px-2.5 py-1 bg-amber-100 text-amber-800 rounded-full border border-amber-300">
                        Varanasi HQ (Uttar Pradesh)
                    </span>
                </h2>
                <p class="text-xs text-slate-500 mt-1">Real-time overview of enquiry conversion, follow-ups, and GST ledger.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('leads.create') }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-black text-sm rounded-xl shadow-md transition-transform active:scale-95">
                    ✨ + Create New Lead
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Bar Card -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
                <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-6 gap-3 items-end">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">From Date</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full bg-slate-50 border-slate-300 rounded-lg text-xs font-medium focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">To Date</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full bg-slate-50 border-slate-300 rounded-lg text-xs font-medium focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Cab Type</label>
                        <select name="cab_type" class="w-full bg-slate-50 border-slate-300 rounded-lg text-xs font-medium focus:ring-amber-500 focus:border-amber-500">
                            <option value="">All Cab Types</option>
                            @foreach($cabTypes as $cab)
                                <option value="{{ $cab }}" {{ request('cab_type') == $cab ? 'selected' : '' }}>{{ $cab }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Call Source</label>
                        <select name="source" class="w-full bg-slate-50 border-slate-300 rounded-lg text-xs font-medium focus:ring-amber-500 focus:border-amber-500">
                            <option value="">All Sources</option>
                            @foreach($sources as $src)
                                <option value="{{ $src }}" {{ request('source') == $src ? 'selected' : '' }}>{{ $src }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(Auth::user()->role !== 'employee')
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Employee</label>
                        <select name="employee_id" class="w-full bg-slate-50 border-slate-300 rounded-lg text-xs font-medium focus:ring-amber-500 focus:border-amber-500">
                            <option value="">All 15 Employees</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->login_id }} - {{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="flex items-center space-x-2">
                        <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-lg transition-colors shadow-sm">
                            🔍 Filter
                        </button>
                        <a href="{{ route('dashboard') }}" class="py-2 px-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-lg transition-colors">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Metric Cards Row 1: Lead Stats -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 border-l-4 border-l-slate-800">
                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Leads</div>
                    <div class="text-2xl font-black text-slate-900 mt-1">{{ number_format($totalLeads) }}</div>
                    <div class="text-[10px] text-slate-400 mt-1">Captured enquiries</div>
                </div>

                <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 border-l-4 border-l-sky-500">
                    <div class="text-[11px] font-bold text-sky-600 uppercase tracking-wider">New Leads</div>
                    <div class="text-2xl font-black text-sky-900 mt-1">{{ number_format($newLeads) }}</div>
                    <div class="text-[10px] text-sky-500 mt-1">Pending contact</div>
                </div>

                <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 border-l-4 border-l-amber-500">
                    <div class="text-[11px] font-bold text-amber-600 uppercase tracking-wider">In Follow-Up</div>
                    <div class="text-2xl font-black text-amber-900 mt-1">{{ number_format($followUpLeads) }}</div>
                    <div class="text-[10px] text-amber-500 mt-1">Active remarks</div>
                </div>

                <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 border-l-4 border-l-emerald-500">
                    <div class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider">Confirmed</div>
                    <div class="text-2xl font-black text-emerald-900 mt-1">{{ number_format($confirmedBookings) }}</div>
                    <div class="text-[10px] text-emerald-500 mt-1">CRS Tickets</div>
                </div>

                <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 border-l-4 border-l-rose-500">
                    <div class="text-[11px] font-bold text-rose-600 uppercase tracking-wider">Lost / Cancelled</div>
                    <div class="text-2xl font-black text-rose-900 mt-1">{{ number_format($cancelledLeads + $lostLeads) }}</div>
                    <div class="text-[10px] text-rose-500 mt-1">Closed leads</div>
                </div>

                <div class="bg-gradient-to-br from-indigo-900 to-slate-900 p-4 rounded-2xl shadow-md text-white">
                    <div class="text-[11px] font-bold text-indigo-300 uppercase tracking-wider">Conversion %</div>
                    <div class="text-2xl font-black text-amber-400 mt-1">{{ $conversionRate }}%</div>
                    <div class="w-full bg-indigo-950 h-1.5 rounded-full mt-2 overflow-hidden">
                        <div class="bg-amber-400 h-1.5 rounded-full" style="width: {{ min(100, $conversionRate) }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Financial Summary Cards (Admin / Accountant / Employee summary) -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-gradient-to-br from-slate-900 to-indigo-950 p-5 rounded-2xl shadow-md text-white border border-indigo-500/20">
                    <div class="text-xs font-bold text-indigo-300 uppercase tracking-widest">Total Revenue (Estimated)</div>
                    <div class="text-3xl font-black text-amber-400 mt-2">₹{{ number_format($totalRevenue, 2) }}</div>
                    <div class="text-[11px] text-slate-300 mt-1">Confirmed booking totals</div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 border-t-4 border-t-emerald-500">
                    <div class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Advance Received</div>
                    <div class="text-3xl font-black text-emerald-700 mt-2">₹{{ number_format($totalAdvance, 2) }}</div>
                    <div class="text-[11px] text-slate-500 mt-1">Advance payment collected</div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 border-t-4 border-t-rose-500">
                    <div class="text-xs font-bold text-rose-600 uppercase tracking-widest">Pending Payment</div>
                    <div class="text-3xl font-black text-rose-700 mt-2">₹{{ number_format($totalPending, 2) }}</div>
                    <div class="text-[11px] text-slate-500 mt-1">Due on trip completion</div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 border-t-4 border-t-purple-500">
                    <div class="text-xs font-bold text-purple-600 uppercase tracking-widest">Total GST Liability (5%)</div>
                    <div class="text-3xl font-black text-purple-900 mt-2">₹{{ number_format($totalGst, 2) }}</div>
                    <div class="text-[10px] text-slate-500 mt-1 flex justify-between font-mono">
                        <span>IGST: ₹{{ number_format($igstTotal, 0) }}</span>
                        <span>CGST/SGST: ₹{{ number_format($cgstTotal, 0) }}</span>
                    </div>
                </div>
            </div>

            <!-- Two-Column Layout: Recent Leads & Source Breakdown -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left 2 Cols: Recent Leads Table -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-base">Recent Leads & Enquiries</h3>
                            <p class="text-xs text-slate-500">Latest activity from call sources</p>
                        </div>
                        <a href="{{ route('leads.index') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700">View All →</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-100 text-slate-600 uppercase font-bold text-[10px]">
                                <tr>
                                    <th class="p-3">Customer & Mobile</th>
                                    <th class="p-3">Route & Cab</th>
                                    <th class="p-3">Source</th>
                                    <th class="p-3">Assigned To</th>
                                    <th class="p-3">Status</th>
                                    <th class="p-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($recentLeads as $lead)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-3">
                                        <div class="font-bold text-slate-900">{{ $lead->customer_name ?: 'Unnamed Enquirer' }}</div>
                                        <div class="font-mono text-slate-500 font-semibold">{{ $lead->mobile_no }}</div>
                                    </td>
                                    <td class="p-3">
                                        <div class="font-semibold text-slate-800">{{ $lead->pickup_city }} ➔ {{ $lead->destination }}</div>
                                        <div class="text-[10px] text-amber-700 font-medium">{{ $lead->cab_type }}</div>
                                    </td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-bold text-[10px]">
                                            {{ $lead->source }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-slate-600 font-medium">
                                        {{ $lead->employee_name }}
                                    </td>
                                    <td class="p-3">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wide {{ $lead->status_color }}">
                                            {{ $lead->status }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-right">
                                        <a href="{{ route('leads.show', $lead->id) }}" class="px-2.5 py-1 bg-slate-900 text-white hover:bg-amber-400 hover:text-slate-950 font-bold rounded-lg text-[10px] transition-colors">
                                            View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="p-6 text-center text-slate-400">No leads recorded yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Right Col: Call Source Distribution & Quick Actions -->
                <div class="space-y-6">

                    <!-- Call Source Breakdown -->
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
                        <h3 class="font-extrabold text-slate-900 text-base mb-1">Call Source Breakdown</h3>
                        <p class="text-xs text-slate-500 mb-4">Leads generated by channel</p>

                        <div class="space-y-3">
                            @foreach(['IVR', 'Missed Call', 'Offer Campaign', 'Website Enquiry', 'Direct Call', 'WhatsApp'] as $src)
                                @php
                                    $cnt = $sourceBreakdown[$src] ?? 0;
                                    $pct = $totalLeads > 0 ? round(($cnt / $totalLeads) * 100) : 0;
                                @endphp
                                <div>
                                    <div class="flex justify-between text-xs font-semibold text-slate-700 mb-1">
                                        <span>{{ $src }}</span>
                                        <span class="font-mono text-slate-500">{{ $cnt }} ({{ $pct }}%)</span>
                                    </div>
                                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                        <div class="bg-amber-400 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Confirm Booking Record Link -->
                    <div class="bg-gradient-to-tr from-amber-400 via-amber-300 to-yellow-400 p-5 rounded-2xl shadow-md text-slate-950">
                        <div class="font-black text-lg">Confirm Booking Record</div>
                        <p class="text-xs font-medium text-slate-800 mt-1">Generate official CRS taxi vouchers & driver assign slips.</p>
                        <a href="{{ route('bookings.index') }}" class="mt-4 inline-block w-full py-2.5 text-center bg-slate-950 text-amber-300 hover:text-white font-extrabold text-xs rounded-xl shadow-lg transition-transform active:scale-95">
                            Open Booking Engine 🚗
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
