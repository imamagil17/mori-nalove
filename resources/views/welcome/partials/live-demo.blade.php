<section class="relative z-10 max-w-7xl mx-auto px-4 w-full mb-10 scroll-mt-24 reveal-on-scroll" id="monitoring">
    <div class="text-center mb-12">
        <span class="text-xs font-bold text-blue-600 uppercase tracking-widest bg-blue-50 px-3 py-1 rounded-full">Live Demo</span>
        <h2 class="text-3xl md:text-4xl font-black text-slate-800 mt-3">Pantauan Real-Time</h2>
        <p class="text-slate-500 mt-3 max-w-lg mx-auto">Status terkini bantaran sungai dan prediksi cuaca langsung dari perangkat cerdas di lapangan.</p>
    </div>

    <div class="flex justify-center max-w-lg mx-auto">

        <!-- WIDGET PRAKIRAAN CUACA 3 HARI KE DEPAN -->
        <div class="bg-white/40 backdrop-blur-md rounded-3xl p-8 shadow-sm border border-white/40 relative overflow-hidden flex flex-col justify-between group transition-all duration-500 hover:shadow-xl hover:-translate-y-1">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-sky-400 to-indigo-500 opacity-50"></div>
            <div class="flex-grow flex flex-col">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2.5 bg-sky-100 text-sky-600 rounded-xl shadow-sm">
                        <i data-lucide="calendar-days" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">Prakiraan 3 Hari</h3>
                        <p class="text-xs text-slate-500">Prediksi berbasis data cuaca API</p>
                    </div>
                </div>
                
                <div class="space-y-4 flex-grow flex flex-col justify-center">
                    <div class="flex items-center justify-between p-4 bg-white/60 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group-hover:bg-white/80 hover:-translate-y-0.5">
                        <div class="flex items-center gap-4">
                            <div class="p-2 bg-amber-50 rounded-xl text-amber-500 shadow-sm"><i data-lucide="sun" class="w-6 h-6"></i></div>
                            <div>
                                <p class="text-sm font-bold text-slate-700">Besok</p>
                                <p class="text-xs text-slate-500">Cerah Berawan</p>
                            </div>
                        </div>
                        <span class="text-xl font-black text-slate-800">32°</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-white/60 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group-hover:bg-white/80 hover:-translate-y-0.5">
                        <div class="flex items-center gap-4">
                            <div class="p-2 bg-blue-50 rounded-xl text-blue-500 shadow-sm"><i data-lucide="cloud-rain" class="w-6 h-6"></i></div>
                            <div>
                                <p class="text-sm font-bold text-slate-700">Lusa</p>
                                <p class="text-xs text-slate-500">Hujan Ringan</p>
                            </div>
                        </div>
                        <span class="text-xl font-black text-slate-800">28°</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-white/60 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group-hover:bg-white/80 hover:-translate-y-0.5">
                        <div class="flex items-center gap-4">
                            <div class="p-2 bg-indigo-50 rounded-xl text-indigo-500 shadow-sm"><i data-lucide="cloud-lightning" class="w-6 h-6"></i></div>
                            <div>
                                <p class="text-sm font-bold text-slate-700">H+3</p>
                                <p class="text-xs text-slate-500">Hujan Petir</p>
                            </div>
                        </div>
                        <span class="text-xl font-black text-slate-800">26°</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
