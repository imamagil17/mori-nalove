<div class="bg-white/70 backdrop-blur-md rounded-3xl p-4 md:p-6 shadow-sm border border-slate-200/60 relative overflow-hidden">
    <div class="flex items-center gap-3 mb-4">
        <div class="p-2 bg-amber-100 text-amber-600 rounded-xl">
            <i data-lucide="life-buoy" class="w-5 h-5"></i>
        </div>
        <h3 class="text-base font-bold text-slate-800">Panduan Keselamatan</h3>
    </div>
    <ul class="space-y-3" id="safetyGuide">
        <li class="flex items-start gap-3 text-sm">
            <span class="mt-0.5 w-5 h-5 rounded-full bg-green-100 text-green-600 flex items-center justify-center shrink-0 text-xs font-bold">1</span>
            <span class="text-slate-600">Pantau terus pemberitahuan dari sistem ini secara berkala.</span>
        </li>
        <li class="flex items-start gap-3 text-sm mb-4">
            <span class="mt-0.5 w-5 h-5 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0 text-xs font-bold">!</span>
            <span class="text-slate-600">Jika status <strong class="text-red-600">AWAS</strong>, segera evakuasi ke titik kumpul terdekat.</span>
        </li>
    </ul>

    <!-- SEKSI 3: Hub Kontak Darurat Instan -->
    <div class="mt-4">
        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Panggilan Darurat Instan</p>
        <div class="grid grid-cols-2 gap-3">
            <a href="tel:0451421396" class="flex flex-col items-center justify-center p-3 bg-white/50 border border-red-100 rounded-2xl hover:bg-red-50 hover:border-red-200 hover:shadow-[0_0_15px_rgba(239,68,68,0.2)] hover:scale-[1.02] transition-all duration-300 group cursor-pointer">
                <div class="p-2 bg-red-100 text-red-600 rounded-full mb-2 group-hover:bg-red-600 group-hover:text-white transition-colors">
                    <i data-lucide="shield" class="w-4 h-4"></i>
                </div>
                <span class="text-xs font-bold text-slate-700">BPBD</span>
            </a>
            <a href="tel:115" class="flex flex-col items-center justify-center p-3 bg-white/50 border border-orange-100 rounded-2xl hover:bg-orange-50 hover:border-orange-200 hover:shadow-[0_0_15px_rgba(249,115,22,0.2)] hover:scale-[1.02] transition-all duration-300 group cursor-pointer">
                <div class="p-2 bg-orange-100 text-orange-600 rounded-full mb-2 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                    <i data-lucide="life-buoy" class="w-4 h-4"></i>
                </div>
                <span class="text-xs font-bold text-slate-700">Tim SAR</span>
            </a>
            <a href="tel:119" class="flex flex-col items-center justify-center p-3 bg-white/50 border border-emerald-100 rounded-2xl hover:bg-emerald-50 hover:border-emerald-200 hover:shadow-[0_0_15px_rgba(16,185,129,0.2)] hover:scale-[1.02] transition-all duration-300 group cursor-pointer">
                <div class="p-2 bg-emerald-100 text-emerald-600 rounded-full mb-2 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <i data-lucide="truck" class="w-4 h-4"></i>
                </div>
                <span class="text-xs font-bold text-slate-700">Ambulans</span>
            </a>
            <a href="tel:113" class="flex flex-col items-center justify-center p-3 bg-white/50 border border-rose-100 rounded-2xl hover:bg-rose-50 hover:border-rose-200 hover:shadow-[0_0_15px_rgba(244,63,94,0.2)] hover:scale-[1.02] transition-all duration-300 group cursor-pointer">
                <div class="p-2 bg-rose-100 text-rose-600 rounded-full mb-2 group-hover:bg-rose-600 group-hover:text-white transition-colors">
                    <i data-lucide="flame" class="w-4 h-4"></i>
                </div>
                <span class="text-xs font-bold text-slate-700">Damkar</span>
            </a>
        </div>
    </div>

    <button onclick="toggleMapModal()" class="mt-5 w-full py-2.5 bg-white/60 hover:bg-blue-50 text-slate-700 hover:text-blue-600 font-bold text-sm rounded-xl transition-colors flex items-center justify-center gap-2 border border-slate-200 shadow-sm backdrop-blur-sm">
        <i data-lucide="map" class="w-4 h-4"></i> Lihat Peta Evakuasi
    </button>
</div>
