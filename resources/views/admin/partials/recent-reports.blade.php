<div class="bg-white/40 backdrop-blur-md border border-white/50 rounded-3xl p-6 shadow-sm transition-all duration-300 hover:bg-white/60 hover:shadow-md">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-rose-100 text-rose-600 rounded-xl">
                <i data-lucide="megaphone" class="w-4 h-4"></i>
            </div>
            <h3 class="text-sm font-black text-slate-800 tracking-tight">Laporan Darurat Baru</h3>
        </div>
        <a href="{{ route('admin.citizen_reports.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 transition-colors">
            Kelola <i data-lucide="arrow-right" class="w-3 h-3"></i>
        </a>
    </div>

    <div class="space-y-3">
        @forelse($reports ?? [] as $report)
        <div class="bg-white/30 backdrop-blur-sm border border-white/40 p-3 rounded-2xl relative group hover:bg-white/50 transition-all duration-200">
            <div class="flex justify-between items-start mb-2">
                <span class="text-xs font-bold text-slate-700 truncate w-2/3" title="{{ $report->lokasi }}">{{ $report->lokasi }}</span>
                <span class="text-[10px] text-slate-400 font-medium">{{ $report->created_at->diffForHumans() }}</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex gap-2">
                    @if($report->tingkat_genangan == 'Tinggi')
                    <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full bg-red-100/70 text-red-700">Tinggi</span>
                    @elseif($report->tingkat_genangan == 'Sedang')
                    <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full bg-orange-100/70 text-orange-700">Sedang</span>
                    @else
                    <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full bg-blue-100/70 text-blue-700">Rendah</span>
                    @endif

                    <div id="status-report-{{ $report->id }}">
                        @if($report->status == 'Pending')
                        <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full bg-amber-100/70 text-amber-700">Pending</span>
                        @else
                        <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100/70 text-emerald-700"><i data-lucide="check" class="w-2.5 h-2.5 inline"></i> Verif</span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center">
                    <a href="{{ route('admin.citizen_reports.show', $report->id) }}" class="px-2.5 py-1 text-[10px] font-bold bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-1 shadow-sm">
                        <i data-lucide="eye" class="w-3 h-3"></i> Lihat Detail
                    </a>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-2 truncate">{{ $report->deskripsi ?: 'Tanpa catatan' }}</p>
        </div>
        @empty
        <div class="text-center py-6 text-slate-400 text-xs">Belum ada laporan terbaru.</div>
        @endforelse
    </div>
</div>