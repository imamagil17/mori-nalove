<section class="relative z-10 max-w-7xl mx-auto px-4 w-full mb-6 reveal-on-scroll" id="berita">
        <div class="mt-8 bg-white/40 backdrop-blur-md rounded-3xl p-6 md:p-8 shadow-sm border border-white/40 relative z-10">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-indigo-100/80 text-indigo-600 rounded-xl backdrop-blur-sm shadow-sm">
                    <i data-lucide="newspaper" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight">Mading Berita Publik</h3>
                    <p class="text-sm text-slate-500 mt-1">Informasi mitigasi terbaru dari Sistem Mitigasi Banjir Cerdas</p>
                </div>
            </div>
        </div>

        <div class="flex overflow-x-auto gap-6 pb-6 snap-x snap-mandatory hide-scrollbar" style="scrollbar-width: none; -ms-overflow-style: none;">
            
            @forelse($beritas ?? [] as $berita)
            <div class="min-w-[300px] max-w-[300px] md:min-w-[350px] md:max-w-[350px] snap-start bg-white/60 backdrop-blur-md rounded-3xl overflow-hidden shadow-sm border border-white/50 flex flex-col group cursor-pointer hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                <div class="relative h-48 md:h-56 overflow-hidden bg-slate-200/50 flex shrink-0">
                    @if($berita->foto)
                        <img src="{{ asset('storage/' . $berita->foto) }}" alt="Foto Berita" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-400">
                            <i data-lucide="image" class="w-12 h-12 opacity-50"></i>
                        </div>
                    @endif
                    
                    <div class="absolute top-4 left-4 bg-slate-900/70 text-white text-[11px] md:text-xs font-bold px-3.5 py-1.5 rounded-full shadow-sm backdrop-blur-md">
                        {{ $berita->created_at->format('d M Y') }}
                    </div>
                </div>
                <div class="p-6 flex flex-col flex-grow justify-between relative z-10">
                    <h4 class="font-bold text-slate-800 text-[16px] md:text-lg leading-snug line-clamp-3 mb-4 group-hover:text-blue-600 transition-colors">
                        {{ $berita->judul }}
                    </h4>
                    
                    <div id="konten-berita-{{ $berita->id }}" class="hidden">{{ $berita->konten }}</div>
                    <button onclick="bukaModalBerita('{{ $berita->id }}', '{{ addslashes($berita->judul) }}', '{{ $berita->created_at->format('d M Y') }}', '{{ $berita->foto ? asset('storage/'.$berita->foto) : '' }}')" class="text-amber-500 font-bold text-sm flex items-center justify-end gap-1.5 hover:text-amber-600 transition-colors w-full text-right mt-2 focus:outline-none">
                        Selengkapnya <i data-lucide="arrow-right-circle" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>
            </div>
            @empty
            <div class="w-full bg-white/40 backdrop-blur-md border border-dashed border-slate-300 rounded-3xl p-10 text-center flex flex-col items-center justify-center">
                <i data-lucide="newspaper" class="w-12 h-12 text-slate-300 mb-4"></i>
                <p class="text-slate-500 font-medium text-base">Belum ada berita dirilis oleh Admin Sistem Mitigasi Banjir Cerdas.</p>
            </div>
            @endforelse

        </div>
    </div>
</section>
