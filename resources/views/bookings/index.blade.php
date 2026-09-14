<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight">🚘 Confirm Booking Records</h2>
                <p class="text-xs text-slate-500 mt-1">CRS Taxi Vouchers, Driver Assignments & Trip Status</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-xl text-sm font-bold shadow-sm">
                ✅ {{ session('success') }}
            </div>
            @endif

            <!-- Search Bar -->
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
                <form method="GET" action="{{ route('bookings.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Search Booking / Customer / Phone</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="CRS-ID, Name, Mobile..." class="w-full bg-slate-50 border-slate-300 rounded-lg text-xs font-medium focus:ring-amber-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Status</label>
                        <select name="status" class="w-full bg-slate-50 border-slate-300 rounded-lg text-xs font-medium focus:ring-amber-500">
                            <option value="">All Statuses</option>
                            @foreach(['Confirmed', 'Completed', 'Cancelled'] as $st)
                                <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="w-full py-2 bg-slate-900 text-white font-bold text-xs rounded-lg hover:bg-slate-800">Filter</button>
                        <a href="{{ route('bookings.index') }}" class="py-2 px-3 bg-slate-200 text-slate-700 font-bold text-xs rounded-lg hover:bg-slate-300">Reset</a>
                    </div>
                </form>
            </div>

            <!-- Bookings Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-100 text-slate-700 uppercase font-extrabold text-[10px] tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="p-3.5">CRS Booking ID</th>
                                <th class="p-3.5">Customer & Mobile</th>
                                <th class="p-3.5">Route</th>
                                <th class="p-3.5">Cab & Driver</th>
                                <th class="p-3.5">Rate / Advance (₹)</th>
                                <th class="p-3.5">Payment</th>
                                <th class="p-3.5">Status</th>
                                <th class="p-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($bookings as $booking)
                            <tr class="hover:bg-amber-50/40 transition-colors">
                                <td class="p-3.5">
                                    <div class="font-extrabold text-slate-900 font-mono text-xs">{{ $booking->booking_id }}</div>
                                    <div class="text-[10px] text-slate-400 font-semibold">{{ $booking->date?->format('d M Y') }}</div>
                                </td>
                                <td class="p-3.5">
                                    <div class="font-bold text-slate-900">{{ $booking->customer_name }}</div>
                                    <div class="font-mono text-slate-600 font-bold">📞 {{ $booking->mobile_no }}</div>
                                </td>
                                <td class="p-3.5">
                                    <div class="font-bold text-slate-800">{{ $booking->pickup_city }} ➔ {{ $booking->destination }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $booking->pickup_date?->format('d M') }} {{ $booking->pickup_time }}</div>
                                </td>
                                <td class="p-3.5">
                                    <div class="font-semibold text-amber-800">{{ $booking->cab_type ?: '—' }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $booking->driver_name ?: 'Not Assigned' }} · {{ $booking->cab_number ?: '' }}</div>
                                </td>
                                <td class="p-3.5">
                                    <div class="font-black text-emerald-700">₹{{ number_format($booking->rate, 0) }}</div>
                                    <div class="text-[10px] text-slate-500 font-mono">Adv: ₹{{ number_format($booking->advance_payment, 0) }}</div>
                                </td>
                                <td class="p-3.5">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-bold text-[10px]">{{ $booking->payment_mode }}</span>
                                </td>
                                <td class="p-3.5">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $booking->status_color }}">
                                        {{ $booking->booking_status }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-right space-x-1">
                                    <a href="{{ route('bookings.show', $booking->id) }}" class="inline-flex px-3 py-1.5 bg-slate-900 hover:bg-amber-500 hover:text-slate-950 text-white font-bold rounded-lg text-xs transition-colors">
                                        Details
                                    </a>
                                    <a href="{{ route('bookings.voucher', $booking->id) }}" class="inline-flex px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-800 font-bold rounded-lg text-xs transition-colors" target="_blank">
                                        🖨 Voucher
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-slate-400 font-medium">No confirmed bookings found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-slate-100 bg-slate-50">{{ $bookings->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
