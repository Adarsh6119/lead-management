<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight">
                    📋 Lead #LEAD-{{ str_pad($lead->id, 4, '0', STR_PAD_LEFT) }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">{{ $lead->customer_name ?: 'Customer Enquiry' }} · {{ $lead->mobile_no }} · {{ $lead->source }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1.5 rounded-full text-xs font-black uppercase tracking-wider {{ $lead->status_color }}">
                    {{ $lead->status }}
                </span>
                <a href="{{ route('leads.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-xl transition-colors">
                    ← Back to Leads
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-xl text-sm font-bold shadow-sm">
                ✅ {{ session('success') }}
            </div>
            @endif

            <!-- Stale Lead or Active Follow-up Alert Banner -->
            @if($lead->isOverdueNewLead())
            <div class="p-4 bg-gradient-to-r from-rose-600 to-red-700 text-white rounded-2xl shadow-md flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🚨</span>
                    <div>
                        <div class="font-black text-sm uppercase tracking-wide">ATTENTION: Stale New Lead ({{ $lead->days_old }} Days Pending)</div>
                        <div class="text-xs text-rose-100 font-medium mt-0.5">
                            This lead has been sitting in "New Lead" status for over 5 days. Please contact customer and update status or add remarks.
                        </div>
                    </div>
                </div>
            </div>
            @elseif($lead->isFollowUp())
            <div class="p-4 bg-gradient-to-r from-amber-400 to-amber-500 text-slate-950 rounded-2xl shadow-md flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">⚡</span>
                    <div>
                        <div class="font-black text-sm uppercase tracking-wide">ACTIVE FOLLOW-UP LEAD</div>
                        <div class="text-xs text-slate-900 font-medium mt-0.5">
                            Active lead under follow-up. Add remarks after every customer call.
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left Column: Lead Details -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Lead Info Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-5 bg-slate-50/50 border-b border-slate-100">
                            <h3 class="font-extrabold text-slate-900">Lead & Customer Details</h3>
                        </div>
                        <div class="p-5 grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                            <div>
                                <div class="text-[10px] font-extrabold text-slate-500 uppercase">Customer Name</div>
                                <div class="font-bold text-slate-900 mt-0.5">{{ $lead->customer_name ?: '—' }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-extrabold text-slate-500 uppercase">Mobile</div>
                                <div class="font-bold font-mono text-slate-900 mt-0.5">📞 {{ $lead->mobile_no }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-extrabold text-slate-500 uppercase">Call Source</div>
                                <div class="font-bold text-slate-900 mt-0.5">{{ $lead->source }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-extrabold text-slate-500 uppercase">Pickup City</div>
                                <div class="font-bold text-slate-900 mt-0.5">{{ $lead->pickup_city ?: '—' }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-extrabold text-slate-500 uppercase">Destination</div>
                                <div class="font-bold text-slate-900 mt-0.5">{{ $lead->destination ?: '—' }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-extrabold text-slate-500 uppercase">Cab Type Requested</div>
                                <div class="font-bold text-amber-800 mt-0.5">{{ $lead->cab_type ?: '—' }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-extrabold text-slate-500 uppercase">Pickup Date & Time</div>
                                <div class="font-bold text-slate-900 mt-0.5">{{ $lead->pickup_date ? $lead->pickup_date->format('d M Y') : 'TBD' }} {{ $lead->pickup_time ? '· ' . $lead->pickup_time : '' }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-extrabold text-slate-500 uppercase">Return Date</div>
                                <div class="font-bold text-slate-900 mt-0.5">{{ $lead->return_date ? $lead->return_date->format('d M Y') : '—' }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-extrabold text-slate-500 uppercase">Trip Type</div>
                                <div class="font-bold text-slate-900 mt-0.5 uppercase text-xs">{{ str_replace('_', ' ', $lead->trip_type ?? 'one_way') }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-extrabold text-slate-500 uppercase">Customer State (GST)</div>
                                <div class="font-bold text-purple-700 mt-0.5">{{ $lead->state ?: 'Uttar Pradesh' }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-extrabold text-slate-500 uppercase">Web Rate (₹)</div>
                                <div class="font-bold text-slate-900 mt-0.5">₹{{ number_format($lead->web_rate, 2) }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-extrabold text-slate-500 uppercase">Final Quoted Rate (₹)</div>
                                <div class="font-bold text-emerald-700 mt-0.5">₹{{ number_format($lead->final_quoted_rate, 2) }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-extrabold text-slate-500 uppercase">Assigned Employee</div>
                                <div class="font-bold text-slate-900 mt-0.5">{{ $lead->employee_name }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Convert to Booking Form (Only if not already confirmed) -->
                    @if(!in_array($lead->status, ['Confirm Booking', 'Booking Cancelled', 'Close / Lost']))
                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-2xl shadow-sm border border-emerald-200 overflow-hidden">
                        <div class="p-5 bg-emerald-100/60 border-b border-emerald-200">
                            <h3 class="font-extrabold text-emerald-900 text-base">🎉 Convert to Confirmed Booking</h3>
                            <p class="text-xs text-emerald-700 mt-1">Fill in driver & payment details to generate CRS Booking Voucher</p>
                        </div>
                        <form method="POST" action="{{ route('leads.convert', $lead->id) }}" class="p-5 space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-extrabold text-emerald-800 uppercase mb-1">Reporting Address *</label>
                                    <textarea name="reporting_address" rows="2" required placeholder="Full pickup address..." class="w-full bg-white border-emerald-300 rounded-xl text-sm focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-xs font-extrabold text-emerald-800 uppercase mb-1">Trip Rate (₹) *</label>
                                        <input type="number" step="0.01" name="rate" value="{{ $lead->final_quoted_rate }}" required class="w-full bg-white border-emerald-300 rounded-xl text-sm font-bold focus:ring-emerald-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-extrabold text-emerald-800 uppercase mb-1">Advance Payment (₹) *</label>
                                        <input type="number" step="0.01" name="advance_payment" value="0" required class="w-full bg-white border-emerald-300 rounded-xl text-sm font-bold focus:ring-emerald-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-extrabold text-emerald-800 uppercase mb-1">Payment Mode *</label>
                                    <select name="payment_mode" required class="w-full bg-white border-emerald-300 rounded-xl text-sm focus:ring-emerald-500">
                                        <option value="UPI">UPI</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Card">Card</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-extrabold text-emerald-800 uppercase mb-1">Driver Name</label>
                                    <input type="text" name="driver_name" placeholder="Driver Name" class="w-full bg-white border-emerald-300 rounded-xl text-sm focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-extrabold text-emerald-800 uppercase mb-1">Driver Mobile</label>
                                    <input type="text" name="driver_mobile" placeholder="Driver Phone" class="w-full bg-white border-emerald-300 rounded-xl text-sm focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-extrabold text-emerald-800 uppercase mb-1">Cab / Vehicle Number</label>
                                    <input type="text" name="cab_number" placeholder="e.g. UP 65 AB 1234" class="w-full bg-white border-emerald-300 rounded-xl text-sm font-mono focus:ring-emerald-500">
                                </div>
                            </div>
                            <button type="submit" class="w-full py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-black text-sm rounded-xl shadow-lg hover:from-emerald-500 transition-all active:scale-95">
                                ✅ Confirm Booking & Generate CRS Voucher
                            </button>
                        </form>
                    </div>
                    @endif

                </div>

                <!-- Right Column: Remarks Timeline + TL Suggestion + Status Update -->
                <div class="space-y-6">

                    <!-- TL Suggestion / Advice Box -->
                    <div class="bg-white rounded-2xl shadow-sm border border-purple-200 overflow-hidden">
                        <div class="p-4 bg-gradient-to-r from-purple-900 to-indigo-900 text-white flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <span class="text-xl">💡</span>
                                <div>
                                    <h3 class="font-extrabold text-sm">Team Lead (TL) Suggestion</h3>
                                    <p class="text-[10px] text-purple-200">Guidance for assigned employee</p>
                                </div>
                            </div>
                            @if($lead->tl_note_at)
                                <span class="text-[10px] font-mono bg-purple-800/80 px-2 py-1 rounded text-purple-200">{{ $lead->tl_note_at->format('d M, h:i A') }}</span>
                            @endif
                        </div>

                        <div class="p-5">
                            @if($lead->tl_note)
                                <div class="p-3.5 bg-purple-50 border-l-4 border-purple-600 rounded-r-xl text-purple-950 font-medium text-xs leading-relaxed mb-4">
                                    <div class="font-black text-[10px] uppercase text-purple-700 tracking-wider mb-1">
                                        Note from {{ $lead->tl_note_by ?: 'Team Lead' }}:
                                    </div>
                                    "{{ $lead->tl_note }}"
                                </div>
                            @else
                                <div class="text-center py-3 text-xs text-slate-400 font-medium">No suggestion added by Team Lead yet for this lead.</div>
                            @endif

                            @if(Auth::user()->isAdmin() || Auth::user()->isHead())
                                <form method="POST" action="{{ route('leads.tl-note', $lead->id) }}" class="mt-3 pt-3 border-t border-slate-100 space-y-2">
                                    @csrf
                                    <label class="block text-[11px] font-bold text-purple-900 uppercase">
                                        {{ $lead->tl_note ? 'Update TL Suggestion' : '+ Add Suggestion for Employee' }}
                                    </label>
                                    <textarea name="tl_note" rows="2" required placeholder="Enter instructions, advice on handling customer, discount limits..." class="w-full bg-slate-50 border-purple-200 rounded-xl text-xs focus:ring-purple-500 focus:border-purple-500">{{ old('tl_note', $lead->tl_note) }}</textarea>
                                    <button type="submit" class="w-full py-2 bg-purple-900 hover:bg-purple-950 text-white font-extrabold text-xs rounded-xl transition-colors shadow">
                                        💾 Save TL Suggestion for Employee
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Add Remark Form -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-5 bg-slate-50/50 border-b border-slate-100">
                            <h3 class="font-extrabold text-slate-900 text-base">💬 Add Follow-Up Remark</h3>
                        </div>
                        <form method="POST" action="{{ route('leads.remark', $lead->id) }}" class="p-5 space-y-4">
                            @csrf
                            <textarea name="note" rows="3" required placeholder="Enter follow-up call notes, rate negotiation, customer response..." class="w-full bg-slate-50 border-slate-300 rounded-xl text-sm focus:ring-amber-500 focus:border-amber-500"></textarea>

                            <div>
                                <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Update Lead Status</label>
                                <select name="status" class="w-full bg-slate-50 border-slate-300 rounded-xl text-sm font-medium focus:ring-amber-500">
                                    <option value="">— Keep Current ({{ $lead->status }}) —</option>
                                    <option value="New Lead">New Lead</option>
                                    <option value="Follow Up">Follow Up</option>
                                    <option value="Booking Cancelled">Booking Cancelled</option>
                                    <option value="Close / Lost">Close / Lost</option>
                                </select>
                            </div>

                            <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-amber-500 hover:text-slate-950 text-white font-bold text-xs rounded-xl transition-colors">
                                Add Remark & Update
                            </button>
                        </form>
                    </div>

                    <!-- Remarks Timeline -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-5 bg-slate-50/50 border-b border-slate-100">
                            <h3 class="font-extrabold text-slate-900 text-base">📝 Remarks Timeline ({{ $lead->remarks->count() }})</h3>
                        </div>
                        <div class="p-5 space-y-4 max-h-[500px] overflow-y-auto">
                            @forelse($lead->remarks as $remark)
                            <div class="relative pl-5 border-l-2 border-amber-300">
                                <div class="absolute left-[-6px] top-1 w-2.5 h-2.5 bg-amber-400 rounded-full border-2 border-white shadow"></div>
                                <div class="text-xs text-slate-500 font-semibold">
                                    {{ $remark->created_at->format('d M Y, h:i A') }} · <span class="text-amber-700 font-bold">{{ $remark->added_by }}</span>
                                </div>
                                <div class="text-sm text-slate-800 mt-1 font-medium leading-relaxed">
                                    {{ $remark->note }}
                                </div>
                            </div>
                            @empty
                            <p class="text-center text-slate-400 text-xs py-4">No remarks yet. Add the first follow-up note above.</p>
                            @endforelse
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
