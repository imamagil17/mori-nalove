<div class="bg-white/40 backdrop-blur-md border border-white/50 rounded-3xl p-6 shadow-sm transition-all duration-300 hover:bg-white/60 hover:shadow-md flex flex-col relative overflow-hidden group">
    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full blur-3xl -z-10 group-hover:bg-blue-100 transition-colors"></div>
    <div class="flex items-center gap-3 mb-5">
        <div class="p-2 bg-blue-100 text-blue-600 rounded-xl">
            <i data-lucide="video" class="w-5 h-5"></i>
        </div>
        <h3 class="text-lg font-black text-slate-800 tracking-tight">Live Camera Feed</h3>
    </div>

    <!-- Dropdown Pilihan Sungai Pengujian -->
    <div class="mb-4">
        <label class="block text-[10px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">Pilih Sungai Pengujian</label>
        <div class="relative">
            <select id="camera_river_select" name="sungai" class="w-full bg-slate-50/50 border border-slate-200 rounded-xl pl-4 pr-10 py-2.5 text-xs font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 cursor-pointer appearance-none transition-all">
                <option value="Sungai Gumbasa" selected>Sungai Gumbasa</option>
                <option value="Sungai Lariang">Sungai Lariang</option>
                <option value="Sungai Lindu">Sungai Lindu</option>
                <option value="Sungai Samba">Sungai Samba</option>
                <option value="Sungai Pakuli">Sungai Pakuli</option>
                <option value="Sungai Marawola">Sungai Marawola</option>
                <option value="Sungai Palolo">Sungai Palolo</option>
                <option value="Sungai Kulawi">Sungai Kulawi</option>
                <option value="Sungai Ngatabaru">Sungai Ngatabaru</option>
                <option value="Sungai Wuno">Sungai Wuno</option>
                <option value="Sungai Bangga">Sungai Bangga</option>
            </select>
            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="chevron-down" class="w-4 h-4"></i>
            </div>
        </div>
    </div>

    <div class="flex-1 flex flex-col items-center justify-center bg-slate-50/50 rounded-2xl border border-slate-100 p-8 text-center min-h-[200px]">
        <div class="w-16 h-16 bg-slate-200 text-slate-400 rounded-full flex items-center justify-center mb-4">
            <i data-lucide="camera" class="w-8 h-8"></i>
        </div>
        <p class="text-slate-500 font-medium text-sm">Sistem Siap. Tekan tombol di bawah untuk mengaktifkan HUD Pemindaian Kamera.</p>
    </div>
    <button id="startCamera" class="mt-6 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-2xl transition-all shadow-md hover:shadow-lg w-full flex items-center justify-center gap-2">
        <i data-lucide="power" class="w-4 h-4"></i>
        Mulai Kamera
    </button>
</div>