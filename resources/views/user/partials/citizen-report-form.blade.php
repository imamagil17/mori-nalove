<div class="w-full px-2">    
    @if(session('success'))
        <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-2 text-sm font-medium animate-[slideDown_0.2s_ease-out]">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-2 text-sm font-medium animate-[slideDown_0.2s_ease-out]">
            <i data-lucide="alert-circle" class="w-4 h-4 text-red-500"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2 sm:gap-0">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-100/80 text-indigo-600 rounded-xl">
                <i data-lucide="megaphone" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-extrabold text-slate-800 tracking-tight">Lapor Kondisi Area</h3>
        </div>
    </div>
    <p class="text-xs text-slate-500 mb-4 line-clamp-2">Laporkan jika terjadi genangan air abnormal di wilayah Anda.</p>
    
    <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf 

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Lokasi Detail (Sungai)</label>
            <select name="lokasi" required class="w-full bg-white/60 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                <option value="" disabled selected>Pilih Aliran Sungai...</option>
                <option value="Sungai Gumbasa">Wilayah Sekitar Aliran Sungai Gumbasa</option>
                <option value="Sungai Lariang">Wilayah Sekitar Aliran Sungai Lariang</option>
                <option value="Sungai Lindu">Wilayah Sekitar Aliran Sungai Lindu</option>
                <option value="Sungai Samba">Wilayah Sekitar Aliran Sungai Samba</option>
                <option value="Sungai Pakuli">Wilayah Sekitar Aliran Sungai Pakuli</option>
                <option value="Sungai Marawola">Wilayah Sekitar Aliran Sungai Marawola</option>
                <option value="Sungai Palolo">Wilayah Sekitar Aliran Sungai Palolo</option>
                <option value="Sungai Kulawi">Wilayah Sekitar Aliran Sungai Kulawi</option>
                <option value="Sungai Ngatabaru">Wilayah Sekitar Aliran Sungai Ngatabaru</option>
                <option value="Sungai Wuno">Wilayah Sekitar Aliran Sungai Wuno</option>
                <option value="Sungai Bangga">Wilayah Sekitar Aliran Sungai Bangga</option>
            </select>
        </div>
        
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Tingkat Genangan</label>
            <select name="tingkat_genangan" required class="w-full bg-white/60 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none appearance-none">
                <option value="" disabled selected>Pilih Dampak Luapan...</option>
                <option value="Rendah">Rendah (Aman / Genangan Hujan Biasa)</option>
                <option value="Sedang">Sedang (Waspada / Air Masuk Jalan hingga Selutut)</option>
                <option value="Tinggi">Tinggi (Bahaya / Banjir Kritis Sepinggang atau Lebih)</option>
            </select>
        </div>
        
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Tambahan (Opsional)</label>
            <textarea name="deskripsi" rows="2" placeholder="Cth: Air sudah masuk ke pemukiman di Desa Oluboju, butuh bantuan evakuasi lansia..." class="w-full bg-white/60 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none resize-none"></textarea>
        </div>
        
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center gap-1.5">
                <i class="fa-solid fa-camera text-slate-400"></i> Foto Bukti Kondisi (Wajib)
            </label>
            <input type="file" name="foto_bukti" accept="image/*" required class="w-full bg-white/60 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
            @error('foto_bukti')
                <p class="text-red-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </div>
        
        <button type="submit" onclick="this.innerHTML='<i data-lucide=\'loader-2\' class=\'w-4 h-4 animate-spin\'></i> Mengirim...'; this.classList.add('opacity-75', 'cursor-wait'); lucide.createIcons();" class="w-full py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold text-sm rounded-xl transition-all shadow-md flex items-center justify-center gap-2">
            <i data-lucide="send" class="w-4 h-4"></i> Kirim Laporan Warga
        </button>
    </form>
</div>