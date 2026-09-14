<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight">
                    📝 1-on-1 Team Lead Meeting Notes & Performance Analytics
                </h2>
                <p class="text-xs text-slate-500 mt-1">Record feedback checkpoints, monitor bottlenecks, and view employee performance gap graphs.</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('employees.performance') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-xl transition-colors">
                    ← Performance Reports
                </a>
                <a href="{{ route('employees.index') }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition-colors">
                    👥 Staff Roster
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

            <!-- Employee Selection Filter -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base">Select Employee for 1-on-1 Evaluation</h3>
                    <p class="text-xs text-slate-500">View meeting history & performance bottleneck analytics graph</p>
                </div>
                <form method="GET" action="{{ route('employees.meeting-notes') }}" class="flex items-center space-x-2 w-full sm:w-auto">
                    <select name="employee_id" onchange="this.form.submit()" class="w-full sm:w-64 bg-slate-50 border-slate-300 rounded-xl text-sm font-bold focus:ring-purple-500 focus:border-purple-500">
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ $selectedEmployee && $selectedEmployee->id == $emp->id ? 'selected' : '' }}>
                                {{ $emp->login_id }} — {{ $emp->name }} ({{ strtoupper($emp->role) }})
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            @if($selectedEmployee)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left 2 Cols: Performance Analytics Graph & Add Meeting Form -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Performance Bottleneck Graph Chart -->
                    <div class="bg-white rounded-2xl shadow-sm border border-purple-200 overflow-hidden">
                        <div class="p-5 bg-gradient-to-r from-purple-900 to-indigo-900 text-white flex justify-between items-center">
                            <div>
                                <h3 class="font-extrabold text-base flex items-center gap-2">
                                    <span>📊</span> Performance Issues & Bottlenecks Graph
                                </h3>
                                <p class="text-xs text-purple-200 mt-0.5">Calculated from 1-on-1 meeting checkpoints for <strong>{{ $selectedEmployee->name }}</strong></p>
                            </div>
                            <span class="px-3 py-1 bg-purple-800 text-purple-200 rounded-full text-xs font-mono font-bold">
                                {{ $meetingNotes->count() }} Meeting(s) Logged
                            </span>
                        </div>
                        <div class="p-6">
                            <div class="h-64 relative">
                                <canvas id="checkpointChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Record New 1-on-1 Meeting Note Form -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-5 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-base">➕ Record New 1-on-1 Meeting Note</h3>
                                <p class="text-xs text-slate-500">Evaluate employee performance checkpoints & issue guidance</p>
                            </div>
                            <span class="px-2.5 py-1 bg-amber-100 text-amber-800 font-extrabold text-[10px] rounded-lg">TL / Head Mode</span>
                        </div>
                        <form method="POST" action="{{ route('employees.store-meeting-note', $selectedEmployee->id) }}" class="p-6 space-y-5">
                            @csrf
                            <div>
                                <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Meeting Date *</label>
                                <input type="date" name="meeting_date" value="{{ date('Y-m-d') }}" required class="w-full sm:w-64 bg-slate-50 border-slate-300 rounded-xl text-sm font-medium focus:ring-purple-500 focus:border-purple-500">
                            </div>

                            <div>
                                <label class="block text-xs font-extrabold text-purple-900 uppercase mb-2">Check Performance Gaps Identified in Meeting:</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    
                                    <label class="flex items-center space-x-3 p-3 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-purple-50 hover:border-purple-300 transition-colors cursor-pointer">
                                        <input type="checkbox" name="quotation_not_sending" value="1" class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                                        <span class="text-xs font-bold text-slate-800">a. Quotation not sending</span>
                                    </label>

                                    <label class="flex items-center space-x-3 p-3 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-purple-50 hover:border-purple-300 transition-colors cursor-pointer">
                                        <input type="checkbox" name="images_not_sending" value="1" class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                                        <span class="text-xs font-bold text-slate-800">b. Images not sending</span>
                                    </label>

                                    <label class="flex items-center space-x-3 p-3 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-purple-50 hover:border-purple-300 transition-colors cursor-pointer">
                                        <input type="checkbox" name="followup_not_regular" value="1" class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                                        <span class="text-xs font-bold text-slate-800">c. Followup not regular</span>
                                    </label>

                                    <label class="flex items-center space-x-3 p-3 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-purple-50 hover:border-purple-300 transition-colors cursor-pointer">
                                        <input type="checkbox" name="cannot_convince_customer" value="1" class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                                        <span class="text-xs font-bold text-slate-800">d. Not able to convince customer</span>
                                    </label>

                                    <label class="flex items-center space-x-3 p-3 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-purple-50 hover:border-purple-300 transition-colors cursor-pointer">
                                        <input type="checkbox" name="not_providing_discount" value="1" class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                                        <span class="text-xs font-bold text-slate-800">e. Not providing discount to customer</span>
                                    </label>

                                    <label class="flex items-center space-x-3 p-3 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-purple-50 hover:border-purple-300 transition-colors cursor-pointer">
                                        <input type="checkbox" name="conversation_not_good" value="1" class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                                        <span class="text-xs font-bold text-slate-800">f. Conversation is not good</span>
                                    </label>

                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-extrabold text-slate-700 uppercase mb-1">Additional Meeting Discussion Notes & Action Items</label>
                                <textarea name="remarks" rows="3" placeholder="Enter custom feedback discussed during meeting, improvement deadlines, guidance..." class="w-full bg-slate-50 border-slate-300 rounded-xl text-xs font-medium focus:ring-purple-500 focus:border-purple-500"></textarea>
                            </div>

                            <button type="submit" class="w-full py-3 bg-purple-900 hover:bg-purple-950 text-white font-extrabold text-xs rounded-xl transition-all shadow-md active:scale-95">
                                💾 Save Meeting Note & Update Analytics Graph
                            </button>
                        </form>
                    </div>

                </div>

                <!-- Right Column: Previous 1-on-1 Meeting History Logs -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-5 bg-slate-50 border-b border-slate-100">
                            <h3 class="font-extrabold text-slate-900 text-base">📜 Meeting History Logs</h3>
                            <p class="text-xs text-slate-500">Past evaluation notes for {{ $selectedEmployee->name }}</p>
                        </div>
                        <div class="p-5 space-y-4 max-h-[750px] overflow-y-auto">
                            @forelse($meetingNotes as $note)
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                                <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                                    <span class="font-black text-xs text-purple-900">📅 {{ $note->meeting_date->format('d M Y') }}</span>
                                    <span class="text-[10px] text-slate-500 font-semibold">By: {{ $note->teamLead?->name ?: 'Team Lead' }}</span>
                                </div>

                                <div class="flex flex-wrap gap-1 mt-1">
                                    @if($note->quotation_not_sending)
                                        <span class="px-2 py-0.5 bg-rose-100 text-rose-800 text-[10px] font-bold rounded">Quotation Issue</span>
                                    @endif
                                    @if($note->images_not_sending)
                                        <span class="px-2 py-0.5 bg-rose-100 text-rose-800 text-[10px] font-bold rounded">Images Issue</span>
                                    @endif
                                    @if($note->followup_not_regular)
                                        <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold rounded">Followup Delay</span>
                                    @endif
                                    @if($note->cannot_convince_customer)
                                        <span class="px-2 py-0.5 bg-rose-100 text-rose-800 text-[10px] font-bold rounded">Cannot Convince</span>
                                    @endif
                                    @if($note->not_providing_discount)
                                        <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold rounded">No Discount Offered</span>
                                    @endif
                                    @if($note->conversation_not_good)
                                        <span class="px-2 py-0.5 bg-rose-100 text-rose-800 text-[10px] font-bold rounded">Conversation Issue</span>
                                    @endif
                                </div>

                                @if($note->remarks)
                                <p class="text-xs text-slate-700 font-medium italic mt-2">
                                    "{{ $note->remarks }}"
                                </p>
                                @endif
                            </div>
                            @empty
                            <div class="text-center py-8 text-slate-400 text-xs font-medium">
                                No meeting notes recorded yet for this employee. Use the form to record the first meeting.
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
            @endif

        </div>
    </div>

    <!-- Chart.js Library for Rendering Analytics Graph -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('checkpointChart').getContext('2d');
            const dataCounts = @json(array_values($checkpointCounts));
            const labels = @json(array_keys($checkpointCounts));

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Checkpoint Frequency',
                        data: dataCounts,
                        backgroundColor: [
                            'rgba(239, 68, 68, 0.85)',   // Red
                            'rgba(249, 115, 22, 0.85)',  // Orange
                            'rgba(234, 179, 8, 0.85)',   // Yellow
                            'rgba(168, 85, 247, 0.85)',  // Purple
                            'rgba(59, 130, 246, 0.85)',  // Blue
                            'rgba(236, 72, 153, 0.85)'   // Pink
                        ],
                        borderRadius: 8,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y', // Horizontal Bar Chart
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: { precision: 0, font: { weight: 'bold' } }
                        },
                        y: {
                            ticks: { font: { weight: 'bold', size: 11 } }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
