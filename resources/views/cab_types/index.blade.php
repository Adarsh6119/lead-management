<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight">🚕 Cab Types & Fleet Config</h2>
                <p class="text-xs text-slate-500 mt-1">Manage cab categories available for customer lead allocations and booking vouchers.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold flex items-center justify-between">
                <span>✅ {{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Add Cab Type Form -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-extrabold text-slate-900 text-base mb-4 flex items-center gap-2">
                    <span>➕ Add New Cab Type</span>
                </h3>
                <form action="{{ route('cab-types.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Cab Category Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="e.g. Innova Hycross, Urbania 17 Seater"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-amber-500 focus:bg-white transition-all">
                        @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-slate-950 font-black rounded-xl text-sm shadow-md shadow-amber-500/20 transition-all">
                        Save Cab Type 🚖
                    </button>
                </form>
            </div>

            <!-- List of Cab Types -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-sm">Active Fleet Vehicle Categories</h3>
                    <span class="text-xs text-slate-500">{{ $cabTypes->count() }} Cab Types Configured</span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($cabTypes as $cab)
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 font-extrabold text-xl flex items-center justify-center">
                                    🚕
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-base">{{ $cab->name }}</h4>
                                    <span class="text-xs text-slate-400">Status: {{ $cab->is_active ? 'Active' : 'Disabled' }}</span>
                                </div>
                            </div>
                            <form action="{{ route('cab-types.toggle', $cab->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-colors {{ $cab->is_active ? 'bg-rose-100 hover:bg-rose-200 text-rose-700 border border-rose-200' : 'bg-emerald-100 hover:bg-emerald-200 text-emerald-700 border border-emerald-200' }}">
                                    {{ $cab->is_active ? 'Disable' : 'Enable' }}
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-400">No cab types configured yet.</div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
