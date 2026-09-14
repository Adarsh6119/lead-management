<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight">
                    🚘 Booking: {{ $booking->booking_id }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">{{ $booking->customer_name }} · {{ $booking->mobile_no }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1.5 rounded-full text-xs font-black uppercase {{ $booking->status_color }}">{{ $booking->booking_status }}</span>
                <a href="{{ route('bookings.voucher', $booking->id) }}" target="_blank" class="px-4 py-2 bg-amber-400 hover:bg-amber-500 text-slate-950 font-black text-xs rounded-xl transition-colors">
                    🖨 Print Voucher
                </a>
                <a href="{{ route('bookings.index') }}" class="px-4 py-2 bg-slate-200 text-slate-700 font-bold text-xs rounded-xl hover:bg-slate-300">← Back</a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-100 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-xl text-sm font-bold">✅ {{ session('success') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Booking Details -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-5 bg-slate-50/50 border-b border-slate-100">
                        <h3 class="font-extrabold text-slate-900">Booking & Trip Details</h3>
                    </div>
                    <div class="p-5 grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <div class="text-[10px] font-extrabold text-slate-500 uppercase">CRS Booking ID</div>
                            <div class="font-black text-slate-900 font-mono mt-0.5">{{ $booking->booking_id }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] font-extrabold text-slate-500 uppercase">Booking Date</div>
                            <div class="font-bold text-slate-900 mt-0.5">{{ $booking->date?->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] font-extrabold text-slate-500 uppercase">Customer Name</div>
                            <div class="font-bold text-slate-900 mt-0.5">{{ $booking->customer_name }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] font-extrabold text-slate-500 uppercase">Mobile</div>
                            <div class="font-bold font-mono text-slate-900 mt-0.5">📞 {{ $booking->mobile_no }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] font-extrabold text-slate-500 uppercase">Pickup City</div>
                            <div class="font-bold text-slate-900 mt-0.5">{{ $booking->pickup_city }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] font-extrabold text-slate-500 uppercase">Destination</div>
                            <div class="font-bold text-slate-900 mt-0.5">{{ $booking->destination }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] font-extrabold text-slate-500 uppercase">Pickup Date & Time</div>
                            <div class="font-bold text-slate-900 mt-0.5">{{ $booking->pickup_date?->format('d M Y') }} · {{ $booking->pickup_time }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] font-extrabold text-slate-500 uppercase">Return Date</div>
                            <div class="font-bold text-slate-900 mt-0.5">{{ $booking->return_date ? $booking->return_date->format('d M Y') : '—' }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] font-extrabold text-slate-500 uppercase">Trip Type</div>
                            <div class="font-bold text-slate-900 mt-0.5 uppercase text-xs">{{ str_replace('_', ' ', $booking->trip_type ?? 'one_way') }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] font-extrabold text-slate-500 uppercase">Cab Type</div>
                            <div class="font-bold text-amber-800 mt-0.5">{{ $booking->cab_type }}</div>
                        </div>
                        <div class="col-span-2">
                            <div class="text-[10px] font-extrabold text-slate-500 uppercase">Reporting Address</div>
                            <div class="font-bold text-slate-900 mt-0.5">{{ $booking->reporting_address ?: '—' }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] font-extrabold text-slate-500 uppercase">Employee</div>
                            <div class="font-bold text-slate-900 mt-0.5">{{ $booking->employee_name }}</div>
                        </div>
                    </div>
                </div>

                <!-- Driver & Payment + Edit Form -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-5 bg-slate-50/50 border-b border-slate-100">
                        <h3 class="font-extrabold text-slate-900">Driver Assignment & Payment</h3>
                    </div>
                    <form method="POST" action="{{ route('bookings.update', $booking->id) }}" class="p-5 space-y-4">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-1">Driver Name</label>
                                <input type="text" name="driver_name" value="{{ $booking->driver_name }}" class="w-full bg-slate-50 border-slate-300 rounded-lg text-sm font-bold focus:ring-amber-500">
                            </div>
                            <div>
                                <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-1">Driver Mobile</label>
                                <input type="text" name="driver_mobile" value="{{ $booking->driver_mobile }}" class="w-full bg-slate-50 border-slate-300 rounded-lg text-sm font-mono font-bold focus:ring-amber-500">
                            </div>
                            <div>
                                <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-1">Cab / Vehicle No</label>
                                <input type="text" name="cab_number" value="{{ $booking->cab_number }}" class="w-full bg-slate-50 border-slate-300 rounded-lg text-sm font-mono font-bold focus:ring-amber-500">
                            </div>
                            <div>
                                <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-1">Booking Status</label>
                                <select name="booking_status" class="w-full bg-slate-50 border-slate-300 rounded-lg text-sm font-bold focus:ring-amber-500">
                                    @foreach(['Confirmed', 'Completed', 'Cancelled'] as $st)
                                        <option value="{{ $st }}" {{ $booking->booking_status === $st ? 'selected' : '' }}>{{ $st }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-1">Reporting Address</label>
                            <textarea name="reporting_address" rows="2" class="w-full bg-slate-50 border-slate-300 rounded-lg text-sm focus:ring-amber-500">{{ $booking->reporting_address }}</textarea>
                        </div>

                        <!-- Payment Summary -->
                        <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-200 grid grid-cols-3 gap-3 text-center">
                            <div>
                                <div class="text-[10px] font-extrabold text-emerald-600 uppercase">Total Rate</div>
                                <div class="text-lg font-black text-emerald-900">₹{{ number_format($booking->rate, 0) }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-extrabold text-emerald-600 uppercase">Advance Paid</div>
                                <div class="text-lg font-black text-emerald-700">₹{{ number_format($booking->advance_payment, 0) }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-extrabold text-rose-600 uppercase">Pending</div>
                                <div class="text-lg font-black text-rose-700">₹{{ number_format($booking->pending_amount, 0) }}</div>
                            </div>
                        </div>
                        <div class="text-xs text-slate-500 font-medium">Payment Mode: <span class="font-bold text-slate-700">{{ $booking->payment_mode }}</span></div>

                        <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-amber-500 hover:text-slate-950 text-white font-bold text-xs rounded-xl transition-colors">
                            💾 Update Driver & Booking Details
                        </button>
                    </form>
                </div>

            </div>

            <!-- Accounting GST Summary (if exists) -->
            @if($accounting)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-5 bg-purple-50 border-b border-purple-100">
                    <h3 class="font-extrabold text-purple-900 text-base">🧾 GST & Accounting Record</h3>
                </div>
                <div class="p-5 grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4 text-sm">
                    <div>
                        <div class="text-[10px] font-extrabold text-slate-500 uppercase">Estimated Amount</div>
                        <div class="font-black text-slate-900 mt-0.5">₹{{ number_format($accounting->estimated_amount, 2) }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-extrabold text-slate-500 uppercase">Advance</div>
                        <div class="font-black text-emerald-700 mt-0.5">₹{{ number_format($accounting->advance, 2) }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-extrabold text-slate-500 uppercase">GST on Advance (5%)</div>
                        <div class="font-bold text-purple-700 mt-0.5">₹{{ number_format($accounting->gst_on_advance, 2) }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-extrabold text-slate-500 uppercase">Pending (No GST)</div>
                        <div class="font-black text-rose-700 mt-0.5">₹{{ number_format($accounting->pending, 2) }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-extrabold text-slate-500 uppercase">Total GST (on Advance)</div>
                        <div class="font-black text-purple-900 mt-0.5">₹{{ number_format($accounting->total_gst, 2) }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-extrabold text-slate-500 uppercase">Customer State</div>
                        <div class="font-bold text-slate-900 mt-0.5">{{ $accounting->customer_state }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-extrabold text-slate-500 uppercase">IGST (Adv)</div>
                        <div class="font-bold text-slate-900 mt-0.5">₹{{ number_format($accounting->igst_advance, 2) }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-extrabold text-slate-500 uppercase">CGST (Adv)</div>
                        <div class="font-bold text-slate-900 mt-0.5">₹{{ number_format($accounting->cgst_advance, 2) }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-extrabold text-slate-500 uppercase">SGST (Adv)</div>
                        <div class="font-bold text-slate-900 mt-0.5">₹{{ number_format($accounting->sgst_advance, 2) }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-extrabold text-slate-500 uppercase">Payment Status</div>
                        <div class="font-bold text-slate-900 mt-0.5">{{ $accounting->payment_status }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-extrabold text-slate-500 uppercase">Payment Mode</div>
                        <div class="font-bold text-slate-900 mt-0.5">{{ $accounting->payment_mode }}</div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
