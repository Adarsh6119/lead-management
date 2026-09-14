<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight">
                    ✨ Add New Lead / Customer Enquiry
                </h2>
                <p class="text-xs text-slate-500 mt-1">Capture details from IVR, Missed Calls, Offer campaigns, or Website.</p>
            </div>
            <a href="{{ route('leads.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-xl transition-colors">
                ← Back to Leads
            </a>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-100 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">

                <!-- Live Duplicate Warning Alert Box -->
                <div id="duplicateAlert" class="hidden mb-6 p-4 bg-amber-50 border-l-4 border-amber-500 rounded-xl text-amber-900 shadow-sm animate-pulse">
                    <div class="flex items-center gap-2 font-black text-sm">
                        <span>⚠️ DUPLICATE MOBILE NUMBER DETECTED!</span>
                    </div>
                    <p class="text-xs text-amber-800 mt-1" id="duplicateDetails"></p>
                </div>

                <form method="POST" action="{{ route('leads.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        <!-- Date Created -->
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Enquiry Date *</label>
                            <input type="date" name="date_created" value="{{ old('date_created', date('Y-m-d')) }}" required class="w-full bg-slate-50 border-slate-300 rounded-xl text-sm font-medium focus:ring-amber-500 focus:border-amber-500">
                        </div>

                        <!-- Call Source -->
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Call Source / Channel *</label>
                            <select name="source" required class="w-full bg-slate-50 border-slate-300 rounded-xl text-sm font-medium focus:ring-amber-500 focus:border-amber-500">
                                @foreach($sources as $src)
                                    <option value="{{ $src }}">{{ $src }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Mobile Number (with live JS duplicate check) -->
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Customer Mobile No * (With Duplicate Check)</label>
                            <input type="text" id="mobile_no" name="mobile_no" value="{{ old('mobile_no') }}" required placeholder="e.g. 9812345678" oninput="checkDuplicateMobile(this.value)" class="w-full bg-slate-50 border-slate-300 rounded-xl text-sm font-mono font-bold text-slate-900 focus:ring-amber-500 focus:border-amber-500">
                        </div>

                        <!-- Customer Name -->
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Customer Name</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name') }}" placeholder="Full Name" class="w-full bg-slate-50 border-slate-300 rounded-xl text-sm font-medium focus:ring-amber-500 focus:border-amber-500">
                        </div>

                        <!-- Pickup City -->
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Pickup City</label>
                            <input type="text" name="pickup_city" value="{{ old('pickup_city') }}" placeholder="e.g. Varanasi, Lucknow, Delhi" class="w-full bg-slate-50 border-slate-300 rounded-xl text-sm font-medium focus:ring-amber-500 focus:border-amber-500">
                        </div>

                        <!-- Destination -->
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Destination City</label>
                            <input type="text" name="destination" value="{{ old('destination') }}" placeholder="e.g. Ayodhya, Prayagraj, Agra" class="w-full bg-slate-50 border-slate-300 rounded-xl text-sm font-medium focus:ring-amber-500 focus:border-amber-500">
                        </div>

                        <!-- Pickup Date -->
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Pickup Date</label>
                            <input type="date" name="pickup_date" value="{{ old('pickup_date') }}" class="w-full bg-slate-50 border-slate-300 rounded-xl text-sm font-medium focus:ring-amber-500 focus:border-amber-500">
                        </div>

                        <!-- Pickup Time -->
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Pickup Time</label>
                            <input type="time" name="pickup_time" value="{{ old('pickup_time', '09:00') }}" class="w-full bg-slate-50 border-slate-300 rounded-xl text-sm font-medium focus:ring-amber-500 focus:border-amber-500">
                        </div>

                        <!-- Return Date -->
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Return Date (For Round Trip)</label>
                            <input type="date" name="return_date" value="{{ old('return_date') }}" class="w-full bg-slate-50 border-slate-300 rounded-xl text-sm font-medium focus:ring-amber-500 focus:border-amber-500">
                        </div>

                        <!-- Trip Type -->
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Trip Type</label>
                            <select name="trip_type" class="w-full bg-slate-50 border-slate-300 rounded-xl text-sm font-medium focus:ring-amber-500 focus:border-amber-500">
                                <option value="one_way" {{ old('trip_type') == 'one_way' ? 'selected' : '' }}>One Way Trip</option>
                                <option value="round_trip" {{ old('trip_type') == 'round_trip' ? 'selected' : '' }}>Round Trip</option>
                                <option value="local" {{ old('trip_type') == 'local' ? 'selected' : '' }}>Local Rental</option>
                            </select>
                        </div>

                        <!-- Cab Type -->
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Requested Cab Type</label>
                            <select name="cab_type" class="w-full bg-slate-50 border-slate-300 rounded-xl text-sm font-medium focus:ring-amber-500 focus:border-amber-500">
                                <option value="">Select Cab Category</option>
                                @foreach($cabTypes as $cab)
                                    <option value="{{ $cab }}">{{ $cab }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- State (For GST Calculation) -->
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Customer State (For GST Split)</label>
                            <select name="state" class="w-full bg-slate-50 border-slate-300 rounded-xl text-sm font-medium focus:ring-amber-500 focus:border-amber-500">
                                @foreach(config('app.indian_states', []) as $st)
                                    <option value="{{ $st }}" {{ old('state', 'Uttar Pradesh') == $st ? 'selected' : '' }}>
                                        {{ $st }} {{ $st === config('app.business_state', 'Uttar Pradesh') ? '(Business Home State - CGST+SGST)' : '(IGST Applicable)' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Quoted Rate / Web Rate -->
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Web Rate (₹)</label>
                            <input type="number" step="0.01" name="web_rate" value="{{ old('web_rate', 0) }}" class="w-full bg-slate-50 border-slate-300 rounded-xl text-sm font-medium focus:ring-amber-500">
                        </div>

                        <!-- Final Quoted Rate -->
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Final Quoted Rate (₹)</label>
                            <input type="number" step="0.01" name="final_quoted_rate" value="{{ old('final_quoted_rate', 0) }}" class="w-full bg-slate-50 border-slate-300 rounded-xl text-sm font-bold text-amber-900 focus:ring-amber-500">
                        </div>

                        @if(Auth::user()->role !== 'employee')
                        <!-- Assign to Employee -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Assign to Employee</label>
                            <select name="employee_id" class="w-full bg-slate-50 border-slate-300 rounded-xl text-sm font-medium focus:ring-amber-500">
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ Auth::id() == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->login_id }} — {{ $emp->name }} ({{ $emp->phone ?? 'Active' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                    </div>

                    <!-- Initial Remark / Note -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Initial Lead Remark / Discussion Notes</label>
                        <textarea name="remark" rows="3" placeholder="Enter customer requirements, quoted rates, discount discussions..." class="w-full bg-slate-50 border-slate-300 rounded-xl text-sm font-medium focus:ring-amber-500"></textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end space-x-3">
                        <a href="{{ route('leads.index') }}" class="px-5 py-3 bg-slate-200 text-slate-700 font-bold text-xs rounded-xl hover:bg-slate-300">
                            Cancel
                        </a>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-amber-400 via-amber-300 to-yellow-400 text-slate-950 font-black text-sm rounded-xl shadow-lg hover:from-amber-300 hover:to-amber-500 transition-all transform active:scale-95">
                            💾 Save & Open Lead Record
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- Duplicate Mobile Checker Script -->
    <script>
        let timer;
        function checkDuplicateMobile(val) {
            clearTimeout(timer);
            const alertBox = document.getElementById('duplicateAlert');
            const detailsBox = document.getElementById('duplicateDetails');

            if (val.trim().length < 5) {
                alertBox.classList.add('hidden');
                return;
            }

            timer = setTimeout(() => {
                fetch(`{{ route('leads.check-duplicate') }}?mobile_no=${encodeURIComponent(val)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.exists && data.leads.length > 0) {
                            const l = data.leads[0];
                            detailsBox.innerHTML = `Mobile <strong>${l.mobile_no}</strong> already exists in system for <strong>${l.customer_name || 'Customer'}</strong> (Status: <strong>${l.status}</strong>, Managed by: <strong>${l.employee_name}</strong> on ${l.date_created}).`;
                            alertBox.classList.remove('hidden');
                        } else {
                            alertBox.classList.add('hidden');
                        }
                    });
            }, 300);
        }
    </script>
</x-app-layout>
