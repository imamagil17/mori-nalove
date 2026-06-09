<footer
    class="relative z-50 border-t border-slate-200 bg-white pt-16 pb-8 w-full shadow-[0_-4px_20px_-15px_rgba(0,0,0,0.1)] reveal-on-scroll">
    <div class="max-w-7xl mx-auto px-4 w-full">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">

            <div class="space-y-4">
                <a href="{{ url('/') }}" class="flex items-center gap-3 group cursor-pointer inline-flex">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white shadow-md group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="waves" class="w-5 h-5"></i>
                    </div>
                    <span
                        class="font-extrabold text-xl tracking-tight text-slate-800 group-hover:text-blue-600 transition-colors">Mori
                        Nalove</span>
                </a>
                <p class="text-slate-500 text-sm leading-relaxed max-w-sm">
                    Sistem mitigasi banjir cerdas berbasis Computer Vision dan Artificial Intelligence. Mengamankan
                    bantaran sungai dan memberikan peringatan dini kepada warga secara real-time.
                </p>
            </div>

            {{-- <div class="space-y-4">
                <h4 class="font-bold text-slate-800 text-base">Kontak Darurat Instan</h4>
                <ul class="space-y-3 text-sm text-slate-500">
                    <li class="flex items-center gap-3 hover:translate-x-1 hover:text-red-500 transition-all duration-300 cursor-pointer">
                        <div class="p-1.5 bg-red-50 text-red-500 rounded-lg"><i data-lucide="shield" class="w-4 h-4"></i></div> 
                        <span class="font-medium">BPBD Palu:</span> (0451) 421396
                    </li>
                    <li class="flex items-center gap-3 hover:translate-x-1 hover:text-orange-500 transition-all duration-300 cursor-pointer">
                        <div class="p-1.5 bg-orange-50 text-orange-500 rounded-lg"><i data-lucide="life-buoy" class="w-4 h-4"></i></div> 
                        <span class="font-medium">Tim SAR:</span> 115
                    </li>
                    <li class="flex items-center gap-3 hover:translate-x-1 hover:text-emerald-500 transition-all duration-300 cursor-pointer">
                        <div class="p-1.5 bg-emerald-50 text-emerald-500 rounded-lg"><i data-lucide="truck" class="w-4 h-4"></i></div> 
                        <span class="font-medium">Ambulans:</span> 119
                    </li>
                    <li class="flex items-center gap-3 hover:translate-x-1 hover:text-rose-500 transition-all duration-300 cursor-pointer">
                        <div class="p-1.5 bg-rose-50 text-rose-500 rounded-lg"><i data-lucide="flame" class="w-4 h-4"></i></div> 
                        <span class="font-medium">Damkar:</span> 113
                    </li>
                </ul>
            </div> --}}

            <div class="space-y-4">
                <h4 class="font-bold text-slate-800 text-base">Pusat Informasi Terpusat</h4>
                <ul class="space-y-4 text-sm text-slate-500">
                    <li
                        class="flex items-start gap-3 hover:translate-x-1 transition-all duration-300 cursor-pointer group">
                        <div
                            class="p-1.5 bg-blue-50 text-blue-500 rounded-lg shrink-0 mt-0.5 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <i data-lucide="map-pin" class="w-4 h-4"></i>
                        </div>
                        <span class="leading-relaxed group-hover:text-blue-600 transition-colors">Fakultas Teknik,
                            Universitas Tadulako<br>Kota Palu, Sulawesi Tengah.</span>
                    </li>
                    <li
                        class="flex items-center gap-3 hover:translate-x-1 transition-all duration-300 cursor-pointer group">
                        <div
                            class="p-1.5 bg-blue-50 text-blue-500 rounded-lg shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </div>
                        <span class="group-hover:text-blue-600 transition-colors">dev@mori-nalove.id</span>
                    </li>
                </ul>
            </div>
        </div>

        <div
            class="border-t border-slate-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs font-medium text-slate-400">
            <p>&copy; 2026 Mori Nalove System. Dikembangkan oleh Kelompok 12</p>
            <div class="flex gap-6">
                <a href="#" class="hover:text-blue-600 transition-colors hover:underline">Kebijakan Privasi</a>
                <a href="#" class="hover:text-blue-600 transition-colors hover:underline">Syarat & Ketentuan</a>
            </div>
        </div>
    </div>
</footer>
