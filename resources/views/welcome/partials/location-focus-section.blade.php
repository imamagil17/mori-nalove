<section class="relative z-10 max-w-7xl mx-auto px-4 w-full mb-20 reveal-on-scroll">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="space-y-6">
            <div class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 uppercase tracking-wider bg-indigo-50 px-3 py-1 rounded-full">
                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Fokus Lokasi Pemantauan
            </div>
            <h2 class="text-3xl md:text-4xl font-black text-slate-800 leading-tight">
                Sistem Mitigasi Banjir Cerdas <br>Untuk Mengamankan Bantaran Sungai
            </h2>
            <p class="text-slate-600 leading-relaxed text-sm md:text-base">
                Sistem difokuskan untuk melakukan pemantauan intensif di titik rawan luapan air, terutama di sekitar area perumahan warga melalui penerapan Sistem Mitigasi Banjir Cerdas.
            </p>
            <div class="grid grid-cols-2 gap-4 pt-2">
                <div class="p-4 bg-white border border-slate-100 rounded-2xl shadow-sm flex items-center gap-3 hover:-translate-y-1.5 hover:shadow-md hover:border-blue-100 transition-all duration-300 group cursor-pointer">
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-xl group-hover:bg-blue-600 group-hover:text-white group-hover:scale-105 transition-all duration-300">
                        <i data-lucide="zap" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h5 class="font-bold text-slate-800 text-sm group-hover:text-blue-600 transition-colors">Respons Cepat</h5>
                        <p class="text-xs text-slate-400">Deteksi hitungan detik</p>
                    </div>
                </div>
                <div class="p-4 bg-white border border-slate-100 rounded-2xl shadow-sm flex items-center gap-3 hover:-translate-y-1.5 hover:shadow-md hover:border-emerald-100 transition-all duration-300 group-pointer">
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl group-hover:bg-emerald-600 group-hover:text-white group-hover:scale-105 transition-all duration-300">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h5 class="font-bold text-slate-800 text-sm group-hover:text-emerald-600 transition-colors">Akses Publik</h5>
                        <p class="text-xs text-slate-400">Dapat dipantau semua warga</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="relative">
            <div class="absolute inset-0 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-[2.5rem] transform rotate-2 opacity-5 scale-105"></div>
            <div class="bg-slate-900 rounded-[2.5rem] shadow-xl border border-slate-200/80 overflow-hidden relative aspect-video">
                <video src="{{ asset('videos/arus.mp4') }}" class="w-full h-full object-cover" autoplay loop playsinline muted></video>
            </div>
        </div>
    </div>
</section>
