<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight flex items-center gap-2">
                    📈 Employee Performance & Live Worklog
                </h2>
                <p class="text-xs text-slate-500 mt-1">Monthly targets, lead follow-ups, conversion tracking, and real-time employee activity logs.</p>
            </div>

            <!-- Month / Year Selector -->
            <form method="GET" action="{{ route('employees.performance') }}" class="flex items-center gap-2 bg-slate-900 p-2 rounded-xl border border-slate-800 shadow-md">
                <span class="text-xs text-amber-400 font-bold px-2">📅</span>
                <select name="month" onchange="this.form.submit()" class="bg-slate-800 text-white text-xs font-bold rounded-lg border-slate-700 focus:ring-amber-400 py-1.5 px-3">
                    @foreach($months as $num => $name)
                        <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                <select name="year" onchange="this.form.submit()" class="bg-slate-800 text-white text-xs font-bold rounded-lg border-slate-700 focus:ring-amber-400 py-1.5 px-3">
                    @for($y = date('Y'); $y >= 2024; $y--)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>
    </x-slot>

    <div class="py-6 max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold">
                ✅ {{ session('success') }}
            </div>
        @endif

        <!-- Summary KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Top Performer -->
            <div class="bg-gradient-to-br from-amber-500/10 via-amber-400/5 to-white border border-amber-500/30 rounded-2xl p-5 shadow-lg relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 text-6xl opacity-10 pointer-events-none">🏆</div>
                <div class="text-[11px] font-extrabold uppercase tracking-wider text-amber-600">🏆 Top Performer</div>
                @if($topPerformer)
                    <div class="text-xl font-black text-slate-900 mt-1">{{ $topPerformer['name'] }}</div>
                    <div class="text-xs text-amber-600 font-bold mt-1 font-mono">
                        {{ $topPerformer['conversion'] }}% conversion · {{ $topPerformer['bookings'] }} bookings
                    </div>
                @else
                    <div class="text-lg font-bold text-slate-400 mt-2">No data yet</div>
                @endif
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <div class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">📋 Total Leads</div>
                <div class="text-3xl font-black text-indigo-900 mt-1">{{ number_format($totalLeadsMonth) }}</div>
                <div class="text-xs text-slate-500 mt-1">{{ count($performanceData) }} Sales Staff</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <div class="text-[11px] font-extrabold uppercase tracking-wider text-emerald-600">✅ Confirmed Bookings</div>
                <div class="text-3xl font-black text-emerald-600 mt-1">{{ number_format($totalBookingsMonth) }}</div>
                <div class="text-xs text-emerald-700 font-semibold mt-1">
                    {{ $totalLeadsMonth > 0 ? round(($totalBookingsMonth / $totalLeadsMonth) * 100, 1) : 0 }}% conversion
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <div class="text-[11px] font-extrabold uppercase tracking-wider text-amber-600">💰 Revenue</div>
                <div class="text-3xl font-black text-slate-900 mt-1">₹{{ number_format($totalRevenueMonth) }}</div>
                <div class="text-xs text-amber-600 mt-1">{{ $months[$selectedMonth] }} {{ $selectedYear }}</div>
            </div>
        </div>

        <!-- ===== EMPLOYEE PERFORMANCE CARDS ===== -->
        @forelse($performanceData as $index => $item)
            @php
                $emp = $item['employee'];
                $score = $item['overall_score'];
                $scoreBg = $score >= 80 ? 'bg-emerald-500' : ($score >= 50 ? 'bg-amber-500' : 'bg-rose-500');
            @endphp
            <div x-data="{ showWorklog: false, showTarget: false }" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <!-- Employee Header Row -->
                <div class="p-4 flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-slate-800 to-indigo-900 text-white font-extrabold flex items-center justify-center text-base shadow-lg shrink-0">
                            {{ strtoupper(substr($emp->name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="font-black text-slate-900 text-base flex items-center gap-2">
                                {{ $emp->name }}
                                @if($index === 0 && $item['total_leads'] > 0)
                                    <span title="Top Ranked">👑</span>
                                @endif
                                <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded-md text-[10px] font-mono font-bold border border-indigo-200">🔑 {{ $emp->login_id }}</span>
                            </div>
                            <div class="text-xs text-slate-500 font-medium mt-0.5 flex items-center gap-3">
                                <span>{{ $emp->phone ?? '—' }}</span>
                                <span>·</span>
                                <span class="font-mono">{{ $emp->email }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats & Actions -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <!-- Active Leads Badge -->
                        <span class="px-2.5 py-1 bg-sky-50 text-sky-700 border border-sky-200 rounded-lg text-[11px] font-extrabold">
                            📋 {{ $item['active_leads_count'] }} Active
                        </span>
                        <!-- Overdue Follow-ups -->
                        @if($item['overdue_followups'] > 0)
                        <span class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-lg text-[11px] font-extrabold animate-pulse">
                            🔴 {{ $item['overdue_followups'] }} Overdue
                        </span>
                        @endif
                        <!-- Today Follow-ups -->
                        @if($item['today_followups'] > 0)
                        <span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-[11px] font-extrabold">
                            🟡 {{ $item['today_followups'] }} Today
                        </span>
                        @endif
                        <!-- Upcoming Follow-ups -->
                        @if($item['upcoming_followups'] > 0)
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-[11px] font-extrabold">
                            🟢 {{ $item['upcoming_followups'] }} Upcoming
                        </span>
                        @endif
                        <!-- Score Badge -->
                        <span class="px-3 py-1 rounded-full text-xs font-black text-white {{ $scoreBg }}">
                            Score: {{ $score }}%
                        </span>
                    </div>
                </div>

                <!-- Targets & Progress Row -->
                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 bg-slate-50/50">
                    <!-- Conversion Rate -->
                    <div class="text-center">
                        <div class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Conversion</div>
                        <div class="text-2xl font-black text-slate-900 mt-0.5">{{ $item['conversion_rate'] }}%</div>
                        <div class="text-[10px] text-slate-500 font-mono">{{ $item['confirmed_bookings'] }}/{{ $item['total_leads'] }} leads</div>
                    </div>

                    <!-- Lead Target Progress -->
                    <div>
                        <div class="flex justify-between text-[10px] font-bold mb-1">
                            <span class="text-slate-600">📋 Leads {{ $item['total_leads'] }}/{{ $item['lead_target'] }}</span>
                            <span class="text-slate-500">{{ $item['lead_pct'] }}%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                            <div class="h-3 rounded-full transition-all duration-500 {{ $item['lead_pct'] >= 100 ? 'bg-emerald-500' : ($item['lead_pct'] >= 60 ? 'bg-amber-400' : 'bg-indigo-500') }}"
                                 style="width: {{ min(100, $item['lead_pct']) }}%"></div>
                        </div>
                    </div>

                    <!-- Booking Target Progress -->
                    <div>
                        <div class="flex justify-between text-[10px] font-bold mb-1">
                            <span class="text-emerald-700">✅ Bookings {{ $item['confirmed_bookings'] }}/{{ $item['booking_target'] }}</span>
                            <span class="text-emerald-600">{{ $item['booking_pct'] }}%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                            <div class="h-3 rounded-full transition-all duration-500 {{ $item['booking_pct'] >= 100 ? 'bg-emerald-500' : ($item['booking_pct'] >= 60 ? 'bg-amber-400' : 'bg-rose-400') }}"
                                 style="width: {{ min(100, $item['booking_pct']) }}%"></div>
                        </div>
                    </div>

                    <!-- Revenue Target Progress -->
                    <div>
                        <div class="flex justify-between text-[10px] font-bold mb-1">
                            <span class="text-slate-700">💰 ₹{{ number_format($item['revenue']) }}</span>
                            <span class="text-slate-400">/ ₹{{ number_format($item['revenue_target']) }}</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                            <div class="h-3 rounded-full transition-all duration-500 {{ $item['revenue_pct'] >= 100 ? 'bg-emerald-500' : ($item['revenue_pct'] >= 60 ? 'bg-amber-400' : 'bg-sky-500') }}"
                                 style="width: {{ min(100, $item['revenue_pct']) }}%"></div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col gap-1.5 justify-center">
                        <button @click="showWorklog = !showWorklog" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-[11px] font-extrabold transition-all flex items-center justify-center gap-1">
                            <span>🔍</span> <span x-text="showWorklog ? 'Hide Worklog' : 'Full Worklog & Follow-ups'"></span>
                        </button>
                        <button @click="showTarget = !showTarget" class="px-3 py-1.5 bg-amber-400 hover:bg-amber-500 text-slate-950 rounded-xl text-[11px] font-extrabold transition-all flex items-center justify-center gap-1">
                            ⚙️ Set Monthly Target
                        </button>
                    </div>
                </div>

                <!-- Lead Status Breakdown Mini Bar -->
                <div class="px-4 py-3 bg-slate-900 text-white">
                    <div class="text-[10px] font-extrabold uppercase text-amber-400 tracking-wider mb-2">📊 Lead Status Breakdown — {{ $months[$selectedMonth] }} {{ $selectedYear }}</div>
                    <div class="grid grid-cols-5 gap-2 text-center text-xs">
                        <div class="py-1.5 rounded-lg bg-sky-500/20 border border-sky-500/30">
                            <div class="text-sky-300 text-[10px] font-bold">🆕 New</div>
                            <div class="text-lg font-black">{{ $item['new_leads'] }}</div>
                        </div>
                        <div class="py-1.5 rounded-lg bg-amber-500/20 border border-amber-500/30">
                            <div class="text-amber-300 text-[10px] font-bold">📞 Follow Up</div>
                            <div class="text-lg font-black">{{ $item['followup_leads'] }}</div>
                        </div>
                        <div class="py-1.5 rounded-lg bg-emerald-500/20 border border-emerald-500/30">
                            <div class="text-emerald-300 text-[10px] font-bold">✅ Confirmed</div>
                            <div class="text-lg font-black">{{ $item['confirmed_bookings'] }}</div>
                        </div>
                        <div class="py-1.5 rounded-lg bg-rose-500/20 border border-rose-500/30">
                            <div class="text-rose-300 text-[10px] font-bold">❌ Cancelled</div>
                            <div class="text-lg font-black">{{ $item['cancelled_leads'] }}</div>
                        </div>
                        <div class="py-1.5 rounded-lg bg-slate-500/20 border border-slate-600/30">
                            <div class="text-slate-400 text-[10px] font-bold">🔒 Lost</div>
                            <div class="text-lg font-black">{{ $item['lost_leads'] }}</div>
                        </div>
                    </div>
                </div>

                <!-- ===== EXPANDABLE: FULL WORKLOG & FOLLOW-UPS ===== -->
                <div x-show="showWorklog" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="border-t border-slate-200">

                    <div class="grid grid-cols-1 lg:grid-cols-3 divide-y lg:divide-y-0 lg:divide-x divide-slate-200">

                        <!-- LEFT: Active Leads Table -->
                        <div class="lg:col-span-2 p-4">
                            <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                                📋 Active Leads & Follow-up Schedule ({{ $item['active_leads_count'] }} Active)
                            </h4>

                            @if($item['active_leads']->count() > 0)
                            <div class="overflow-x-auto max-h-80 overflow-y-auto border border-slate-200 rounded-xl">
                                <table class="w-full text-xs text-left border-collapse">
                                    <thead class="bg-slate-100 sticky top-0">
                                        <tr class="text-slate-500 uppercase text-[10px] font-black tracking-wider">
                                            <th class="py-2 px-3">Customer</th>
                                            <th class="py-2 px-3">Phone</th>
                                            <th class="py-2 px-3">Route</th>
                                            <th class="py-2 px-3">Pickup</th>
                                            <th class="py-2 px-3">Status</th>
                                            <th class="py-2 px-3">Next Followup</th>
                                            <th class="py-2 px-3">Latest Remark</th>
                                            <th class="py-2 px-3">View</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($item['active_leads'] as $lead)
                                            @php
                                                $followupDate = $lead->next_followup_date;
                                                $followupBadge = '';
                                                if ($followupDate) {
                                                    $fDate = \Carbon\Carbon::parse($followupDate);
                                                    if ($fDate->lt($today)) {
                                                        $followupBadge = 'bg-rose-100 text-rose-800 border-rose-200';
                                                        $followupLabel = '🔴 Overdue';
                                                    } elseif ($fDate->isToday()) {
                                                        $followupBadge = 'bg-amber-100 text-amber-800 border-amber-200';
                                                        $followupLabel = '🟡 Today';
                                                    } else {
                                                        $followupBadge = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                                                        $followupLabel = '🟢 ' . $fDate->format('d M');
                                                    }
                                                } else {
                                                    $followupBadge = 'bg-slate-100 text-slate-500 border-slate-200';
                                                    $followupLabel = '— Not set';
                                                }
                                                $latestRemark = $lead->remarks->first();
                                            @endphp
                                            <tr class="hover:bg-slate-50 transition-colors">
                                                <td class="py-2 px-3 font-bold text-slate-800">{{ $lead->customer_name ?: '—' }}</td>
                                                <td class="py-2 px-3 font-mono text-slate-600">{{ $lead->mobile_no }}</td>
                                                <td class="py-2 px-3 text-slate-600">{{ $lead->pickup_city ?? '—' }} → {{ $lead->destination ?? '—' }}</td>
                                                <td class="py-2 px-3 text-slate-500">{{ $lead->pickup_date ? $lead->pickup_date->format('d M') : '—' }}</td>
                                                <td class="py-2 px-3">
                                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $lead->status_color }}">{{ $lead->status }}</span>
                                                </td>
                                                <td class="py-2 px-3">
                                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold border {{ $followupBadge }}">{{ $followupLabel }}</span>
                                                </td>
                                                <td class="py-2 px-3 max-w-[180px]">
                                                    @if($latestRemark)
                                                        <div class="truncate text-slate-600" title="{{ $latestRemark->note }}">
                                                            💬 {{ Str::limit($latestRemark->note, 40) }}
                                                        </div>
                                                        <div class="text-[9px] text-slate-400 mt-0.5">{{ $latestRemark->created_at->diffForHumans() }}</div>
                                                    @else
                                                        <span class="text-slate-400">No remarks</span>
                                                    @endif
                                                </td>
                                                <td class="py-2 px-3">
                                                    <a href="{{ route('leads.show', $lead->id) }}" class="px-2 py-1 bg-indigo-100 text-indigo-700 border border-indigo-200 rounded text-[10px] font-bold hover:bg-indigo-200 transition-colors">
                                                        Open →
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                                <div class="py-6 text-center text-slate-400 text-xs bg-slate-50 rounded-xl border border-slate-200">
                                    No active leads right now for this employee.
                                </div>
                            @endif
                        </div>

                        <!-- RIGHT: Recent Remarks Activity Feed -->
                        <div class="p-4">
                            <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                                💬 Recent Follow-up Remarks Log
                            </h4>

                            @if($item['recent_remarks']->count() > 0)
                            <div class="space-y-2.5 max-h-80 overflow-y-auto pr-1">
                                @foreach($item['recent_remarks'] as $remark)
                                    <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200 hover:bg-slate-100 transition-colors">
                                        <div class="text-[11px] text-slate-800 font-medium leading-snug">
                                            💬 {{ Str::limit($remark->note, 100) }}
                                        </div>
                                        <div class="flex items-center justify-between mt-1.5">
                                            <div class="text-[9px] text-slate-400 font-mono">
                                                {{ $remark->created_at->format('d M Y, h:i A') }}
                                            </div>
                                            @if($remark->lead)
                                                <a href="{{ route('leads.show', $remark->lead_id) }}" class="text-[9px] text-indigo-500 font-bold hover:underline">
                                                    {{ $remark->lead->customer_name ?: $remark->lead->mobile_no }} →
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @else
                                <div class="py-6 text-center text-slate-400 text-xs bg-slate-50 rounded-xl border border-slate-200">
                                    No recent remarks recorded.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- ===== EXPANDABLE: SET TARGET FORM ===== -->
                <div x-show="showTarget" x-transition class="border-t border-slate-200 p-4 bg-slate-900">
                    <form method="POST" action="{{ route('employees.update-target', $emp->id) }}" class="grid grid-cols-1 sm:grid-cols-5 gap-3 items-end">
                        @csrf
                        <input type="hidden" name="month" value="{{ $selectedMonth }}">
                        <input type="hidden" name="year" value="{{ $selectedYear }}">

                        <div>
                            <label class="block text-[10px] font-bold text-amber-400 mb-1 uppercase">Leads Target</label>
                            <input type="number" name="lead_target" value="{{ $item['lead_target'] }}" required min="1" class="w-full bg-slate-950 border border-slate-700 rounded-xl py-2 px-3 text-white text-xs focus:ring-amber-400">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-amber-400 mb-1 uppercase">Bookings Target</label>
                            <input type="number" name="booking_target" value="{{ $item['booking_target'] }}" required min="1" class="w-full bg-slate-950 border border-slate-700 rounded-xl py-2 px-3 text-white text-xs focus:ring-amber-400">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-amber-400 mb-1 uppercase">Revenue Target (₹)</label>
                            <input type="number" step="0.01" name="revenue_target" value="{{ $item['revenue_target'] }}" required min="0" class="w-full bg-slate-950 border border-slate-700 rounded-xl py-2 px-3 text-white text-xs focus:ring-amber-400">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase">Period</label>
                            <div class="py-2 px-3 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-300 font-mono">{{ $months[$selectedMonth] }} {{ $selectedYear }}</div>
                        </div>
                        <div>
                            <button type="submit" class="w-full py-2.5 bg-amber-400 hover:bg-amber-500 text-slate-950 font-black rounded-xl text-xs shadow-lg transition-all">
                                💾 Save Target
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="py-12 text-center text-slate-500 font-medium bg-white rounded-2xl border border-slate-200">
                No sales employees found in system.
            </div>
        @endforelse

    </div>
</x-app-layout>
