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
                <form action="{{ route('admin.water_logs.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Ketinggian Air (%)</label>
                        <input type="number" name="water_level" step="0.1" placeholder="Contoh: 45.5" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm p-3 font-semibold" required>
                        @error('water_level')
                            <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Upload Component -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Dokumentasi Visual <span class="text-slate-400 font-normal">(Opsional, Max 2MB)</span></label>
                        <div class="relative w-full">
                            <input type="file" name="foto_visual" id="foto_visual" accept=".jpg,.jpeg,.png" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewFileName(this)">
                            <div class="w-full p-6 border-2 border-dashed border-slate-300 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors flex flex-col items-center justify-center text-center">
                                <i data-lucide="image-plus" class="w-8 h-8 text-blue-500 mb-3"></i>
                                <p class="text-sm font-semibold text-slate-700">Klik atau Drag & Drop foto di sini</p>
                                <p class="text-xs text-slate-500 mt-1">Hanya format JPG, JPEG, PNG</p>
                                <p id="file-name" class="mt-3 text-sm font-bold text-blue-600 truncate max-w-[200px] hidden"></p>
                            </div>
                        </div>
                        @error('foto_visual')
                            <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-xl shadow-md transition-colors flex items-center justify-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Simpan Data Log
                    </button>
                </form>
            </div>
            
            <script>
                function previewFileName(input) {
                    const fileNameElement = document.getElementById('file-name');
                    if (input.files && input.files[0]) {
                        fileNameElement.textContent = input.files[0].name;
                        fileNameElement.classList.remove('hidden');
                    } else {
                        fileNameElement.classList.add('hidden');
                    }
                }
            </script>

        </div>
    </div>
</x-app-layout>