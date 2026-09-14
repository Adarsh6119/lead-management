<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-black text-2xl text-slate-900 leading-tight">🧾 Accounting & GST Ledger</h2>
            <p class="text-xs text-slate-500 mt-1">Payment tracking, IGST/CGST/SGST breakdown, and bank reconciliation. Business State: {{ $businessState }}</p>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-xl text-sm font-bold">✅ {{ session('success') }}</div>
            @endif

            <!-- Financial Summary Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 border-l-4 border-l-slate-800">
                    <div class="text-[10px] font-extrabold text-slate-500 uppercase">Total Estimated</div>
                    <div class="text-xl font-black text-slate-900 mt-1">₹{{ number_format($totals['estimated'], 0) }}</div>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 border-l-4 border-l-emerald-500">
                    <div class="text-[10px] font-extrabold text-emerald-600 uppercase">Advance Collected</div>
                    <div class="text-xl font-black text-emerald-700 mt-1">₹{{ number_format($totals['advance'], 0) }}</div>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 border-l-4 border-l-rose-500">
                    <div class="text-[10px] font-extrabold text-rose-600 uppercase">Pending Amount</div>
                    <div class="text-xl font-black text-rose-700 mt-1">₹{{ number_format($totals['pending'], 0) }}</div>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 border-l-4 border-l-purple-500">
                    <div class="text-[10px] font-extrabold text-purple-600 uppercase">Total GST (5%)</div>
                    <div class="text-xl font-black text-purple-700 mt-1">₹{{ number_format($totals['total_gst'], 2) }}</div>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 border-l-4 border-l-indigo-500">
                    <div class="text-[10px] font-extrabold text-indigo-600 uppercase">IGST Total</div>
                    <div class="text-xl font-black text-indigo-700 mt-1">₹{{ number_format($totals['igst'], 2) }}</div>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 border-l-4 border-l-amber-500">
                    <div class="text-[10px] font-extrabold text-amber-600 uppercase">CGST+SGST</div>
                    <div class="text-xl font-black text-amber-700 mt-1">₹{{ number_format($totals['cgst'] + $totals['sgst'], 2) }}</div>
                </div>
            </div>

            <!-- Search & Filter -->
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
                <form method="GET" action="{{ route('accounting.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Search Booking / Customer</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="CRS ID, Name, Mobile..." class="w-full bg-slate-50 border-slate-300 rounded-lg text-xs focus:ring-amber-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Payment Status</label>
                        <select name="payment_status" class="w-full bg-slate-50 border-slate-300 rounded-lg text-xs focus:ring-amber-500">
                            <option value="">All</option>
                            @foreach(['Advance Paid', 'Fully Paid', 'Pending'] as $ps)
                                <option value="{{ $ps }}" {{ request('payment_status') == $ps ? 'selected' : '' }}>{{ $ps }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Customer State</label>
                        <select name="state" class="w-full bg-slate-50 border-slate-300 rounded-lg text-xs focus:ring-amber-500">
                            <option value="">All States</option>
                            @foreach(config('app.indian_states', []) as $st)
                                <option value="{{ $st }}" {{ request('state') == $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="w-full py-2 bg-slate-900 text-white font-bold text-xs rounded-lg hover:bg-slate-800">Filter</button>
                        <a href="{{ route('accounting.index') }}" class="py-2 px-3 bg-slate-200 text-slate-700 font-bold text-xs rounded-lg hover:bg-slate-300">Reset</a>
                    </div>
                </form>
            </div>

            <!-- Accounting Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-100 text-slate-700 uppercase font-extrabold text-[10px] tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="p-3">Booking ID</th>
                                <th class="p-3">Customer</th>
                                <th class="p-3">Estimated (₹)</th>
                                <th class="p-3">Advance (₹)</th>
                                <th class="p-3">GST on Adv (5%)</th>
                                <th class="p-3">Pending (₹)</th>
                                <th class="p-3">State & GST Type</th>
                                <th class="p-3">Payment</th>
                                <th class="p-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($accountings as $acc)
                            <tr class="hover:bg-amber-50/40 transition-colors">
                                <td class="p-3 font-extrabold font-mono text-slate-900 text-xs">{{ $acc->booking_id }}</td>
                                <td class="p-3">
                                    <div class="font-bold text-slate-900">{{ $acc->customer_name }}</div>
                                    <div class="font-mono text-slate-500 font-semibold">{{ $acc->mobile_no }}</div>
                                </td>
                                <td class="p-3 font-black text-slate-900">₹{{ number_format($acc->estimated_amount, 0) }}</td>
                                <td class="p-3 font-bold text-emerald-700">₹{{ number_format($acc->advance, 0) }}</td>
                                <td class="p-3 font-semibold text-purple-700">₹{{ number_format($acc->gst_on_advance, 2) }}</td>
                                <td class="p-3 font-bold text-rose-700">₹{{ number_format($acc->pending, 0) }}</td>
                                <td class="p-3">
                                    <div class="font-bold text-slate-800 text-xs">{{ $acc->customer_state }}</div>
                                    <div class="text-[10px] text-slate-500 font-mono">
                                        @if($acc->igst_advance > 0)
                                            IGST: ₹{{ number_format($acc->igst_advance, 2) }}
                                        @else
                                            C: ₹{{ number_format($acc->cgst_advance, 2) }} S: ₹{{ number_format($acc->sgst_advance, 2) }}
                                        @endif
                                    </div>
                                </td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold {{ $acc->payment_status === 'Fully Paid' ? 'bg-emerald-100 text-emerald-800' : ($acc->payment_status === 'Advance Paid' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                        {{ $acc->payment_status }}
                                    </span>
                                </td>
                                <td class="p-3 text-right">
                                    <form method="POST" action="{{ route('accounting.update', $acc->id) }}" class="inline-flex items-center space-x-1">
                                        @csrf
                                        <select name="payment_status" class="bg-slate-50 border-slate-300 rounded text-[10px] font-bold focus:ring-amber-500 py-1 px-1.5">
                                            @foreach(['Advance Paid', 'Fully Paid', 'Pending'] as $ps)
                                                <option value="{{ $ps }}" {{ $acc->payment_status === $ps ? 'selected' : '' }}>{{ $ps }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="px-2.5 py-1 bg-slate-900 text-white font-bold rounded text-[10px] hover:bg-amber-500 hover:text-slate-950 transition-colors">
                                            Save
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="p-8 text-center text-slate-400">No accounting records found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-slate-100 bg-slate-50">{{ $accountings->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
