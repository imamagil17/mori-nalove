<div class="bg-white/40 backdrop-blur-md rounded-3xl p-4 md:p-6 shadow-sm border border-white/40 relative overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2 sm:gap-0">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-100 text-indigo-600 rounded-xl">
                <i data-lucide="megaphone" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-bold text-slate-800">Lapor Kondisi Area</h3>
        </div>
    </div>
    <p class="text-xs text-slate-500 mb-4 line-clamp-2">Laporkan jika terjadi genangan air abnormal di wilayah Anda.</p>
    
    <form id="citizenReportForm" class="space-y-4">
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Lokasi Detail</label>
            <input type="text" id="reportLokasi" required placeholder="Cth: Jl. Bantaran Sungai, RT 02" class="w-full bg-white/60 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Tingkat Genangan</label>
            <select id="reportGenangan" required class="w-full bg-white/60 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none appearance-none">
                <option value="" disabled selected>Pilih Tingkat...</option>
                <option value="Rendah">Rendah (Semata Kaki)</option>
                <option value="Sedang">Sedang (Selutut)</option>
                <option value="Tinggi">Tinggi (Se-pinggang/Lebih)</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Tambahan (Opsional)</label>
            <textarea id="reportDeskripsi" rows="2" placeholder="Cth: Butuh bantuan evakuasi lansia..." class="w-full bg-white/60 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none resize-none"></textarea>
        </div>
        <button type="submit" id="btnSubmitReport" class="w-full py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold text-sm rounded-xl transition-all shadow-md flex items-center justify-center gap-2">
            <i data-lucide="send" class="w-4 h-4"></i> Kirim Laporan Warga
        </button>
    </form>
</div>
