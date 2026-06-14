<section class="bg-white/40 backdrop-blur-md border border-white/50 rounded-3xl p-6 shadow-sm transition-all duration-300 hover:bg-white/60 hover:shadow-md relative overflow-hidden group">
    <!-- Ornamen Amber Glow Premium -->
    <div class="absolute top-0 right-0 w-48 h-48 bg-amber-500/10 rounded-full blur-3xl -z-10"></div>

    <h2 class="text-base font-black text-slate-800 tracking-tight mb-5 flex items-center gap-2 border-b border-slate-200/40 pb-4">
        <i data-lucide="upload-cloud" class="w-5 h-5 text-amber-500"></i> Upload Video Baru
    </h2>

    <form id="standaloneVideoForm" class="space-y-5" action="{{ route('admin.kelola_video.store') }}" method="POST" enctype="multipart/form-data" onsubmit="showLoading(event)">
        @csrf

        <div id="dropzone" class="border-2 border-dashed border-white/50 hover:border-blue-500 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-2xl p-6 transition-all duration-300 text-center relative cursor-pointer group">
            <input type="file" name="video_file" id="videoFileInput" accept=".mp4,.mov,.avi" class="absolute inset-0 opacity-0 cursor-pointer z-10" required>
            <div class="flex flex-col items-center justify-center relative z-0">
                <div class="w-14 h-14 bg-blue-50 group-hover:bg-blue-100/80 rounded-2xl flex items-center justify-center text-blue-600 transition-colors mb-3">
                    <i data-lucide="video" class="w-6 h-6"></i>
                </div>
                <p class="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors">Seret & letakkan video di sini</p>
                <p class="text-xs text-slate-400 mt-1">atau klik untuk memilih file manual</p>
                <p class="text-[10px] font-bold text-indigo-400 tracking-wider uppercase bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md mt-3">Format: MP4, MOV, AVI • Maks. 200 MB</p>
            </div>
        </div>

        <div id="filePreview" class="hidden p-3 bg-blue-50/60 border border-blue-100 text-blue-700 rounded-xl text-xs font-bold truncate flex items-center gap-2">
            <i data-lucide="file-text" class="w-4 h-4 shrink-0"></i>
            <span id="filePreviewText"></span>
        </div>

        <h3 class="text-xs font-bold text-slate-400 tracking-wider uppercase pt-2 border-t border-slate-100">Informasi Video</h3>

        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Nama Sungai</label>
            <div class="relative">
                <select id="nama_sungai" name="nama_sungai" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-4 pr-10 py-2.5 text-sm text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 cursor-pointer appearance-none transition-all" required>
                    <option value="" disabled selected>Pilih Sungai</option>
                    <option value="Sungai Gumbasa">Sungai Gumbasa</option>
                    <option value="Sungai Lariang">Sungai Lariang</option>
                    <option value="Sungai Pakuli">Sungai Pakuli</option>
                    <option value="Sungai Marawola">Sungai Marawola</option>
                    <option value="Sungai Palolo">Sungai Palolo</option>
                    <option value="Sungai Kulawi">Sungai Kulawi</option>
                    <option value="Sungai Ngatabaru">Sungai Ngatabaru</option>
                    <option value="Sungai Wuno">Sungai Wuno</option>
                    <option value="Sungai Lindu">Sungai Lindu</option>
                    <option value="Sungai Bangga">Sungai Bangga</option>
                    <option value="Sungai Samba">Sungai Samba</option>
                </select>
                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Tanggal Rekaman</label>
            <input type="date" id="input_tanggal" name="waktu_rekaman" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer" required>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">Keterangan</label>
            <textarea id="keterangan" name="keterangan" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 resize-none transition-all" placeholder="Tambahkan catatan kondisi sungai..."></textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="reset" id="btnBatal" class="flex-1 border border-slate-200 hover:bg-slate-100 text-slate-600 font-semibold py-2.5 rounded-xl transition-colors text-sm">Batal</button>
            <button type="submit" id="btnSubmit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl shadow-md shadow-blue-500/10 hover:shadow-lg transition-all text-sm flex items-center justify-center gap-2">
                <i data-lucide="cpu" class="w-4 h-4"></i> Upload Video
            </button>
        </div>
    </form>
</section>

