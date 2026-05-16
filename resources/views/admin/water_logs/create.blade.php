<x-app-layout>
    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('admin.water_logs.index') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                    ← Kembali ke Riwayat
                </a>
                <h2 class="text-2xl font-extrabold text-slate-800 mt-2">Input Data Air Manual</h2>
            </div>

            <div class="bg-white border border-slate-200 shadow-xl rounded-2xl p-6">
                <form action="{{ route('admin.water_logs.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Ketinggian Air (%)</label>
                        <input type="number" name="water_level" step="0.1" placeholder="Contoh: 45.5" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm p-3 font-semibold" required>
                        @error('water_level')
                            <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl shadow-md transition-colors">
                        Simpan Data
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>