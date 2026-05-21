<div class="mt-8 bg-white/40 backdrop-blur-md rounded-3xl p-4 md:p-6 shadow-sm border border-white/40 relative z-10">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-100/80 text-indigo-600 rounded-xl backdrop-blur-sm">
                <i data-lucide="newspaper" class="w-5 h-5"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 tracking-tight">Preview Berita Publik</h3>
        </div>
    </div>

    <div class="flex overflow-x-auto gap-5 pb-4 snap-x snap-mandatory hide-scrollbar" style="scrollbar-width: none; -ms-overflow-style: none;">
        
        @forelse($beritas ?? [] as $berita)
        <div class="min-w-[280px] max-w-[280px] snap-start bg-white/60 backdrop-blur-md rounded-3xl overflow-hidden shadow-sm border border-white/50 flex flex-col group cursor-pointer hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="relative h-44 overflow-hidden bg-slate-200/50 flex shrink-0">
                @if($berita->foto)
                    <img src="{{ asset('storage/' . $berita->foto) }}" alt="Foto Berita" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                        <i data-lucide="image" class="w-10 h-10 opacity-50"></i>
                    </div>
                @endif
                
                <div class="absolute top-3 left-3 bg-slate-900/70 text-white text-[11px] font-semibold px-3 py-1.5 rounded-full shadow-sm backdrop-blur-md">
                    {{ $berita->created_at->format('d M Y') }}
                </div>
            </div>
            <div class="p-5 flex flex-col flex-grow justify-between relative z-10">
                <h4 class="font-bold text-slate-800 text-[15px] leading-snug line-clamp-3 mb-4 group-hover:text-blue-600 transition-colors">
                    {{ $berita->judul }}
                </h4>
                
                <div id="konten-berita-{{ $berita->id }}" class="hidden">{{ $berita->konten }}</div>
                <button onclick="bukaModalBerita('{{ $berita->id }}', '{{ addslashes($berita->judul) }}', '{{ $berita->created_at->format('d M Y') }}', '{{ $berita->foto ? asset('storage/'.$berita->foto) : '' }}')" class="text-amber-500 font-bold text-sm flex items-center justify-end gap-1.5 hover:text-amber-600 transition-colors w-full text-right mt-2 focus:outline-none">
                    Selengkapnya <i data-lucide="arrow-right-circle" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>
        </div>
        @empty
        <div class="w-full bg-white/40 backdrop-blur-md border border-dashed border-slate-300 rounded-3xl p-8 text-center flex flex-col items-center justify-center">
            <i data-lucide="newspaper" class="w-10 h-10 text-slate-300 mb-3"></i>
            <p class="text-slate-500 font-medium text-sm">Belum ada berita dirilis oleh Admin Sistem Mitigasi Banjir Cerdas.</p>
        </div>
        @endforelse

    </div>
</div>
