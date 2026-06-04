<div class="lg:col-span-7 space-y-8">
    
    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm flex flex-col relative overflow-hidden" id="yoloPreviewCard">
        <h2 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i data-lucide="scan" class="w-5 h-5 text-indigo-600 animate-pulse"></i> Status Proses AI YOLO
        </h2>
        
        <div id="previewPlaceholder" class="flex flex-col items-center justify-center py-12 text-center text-slate-400">
            <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center text-slate-400 mb-3">
                <i data-lucide="video-off" class="w-6 h-6"></i>
            </div>
            <p class="text-sm font-bold text-slate-700">Belum Ada Video Diproses</p>
            <p class="text-xs text-slate-400 mt-1 max-w-[280px]">Unggah file video di panel kiri untuk memulai pengujian YOLOv26 ke server Python.</p>
        </div>

        <div id="previewLoader" class="hidden flex flex-col items-center justify-center py-12 text-center text-slate-400">
            <div class="relative w-16 h-16 mb-4">
                <div class="absolute inset-0 rounded-full border-4 border-slate-100 border-t-indigo-600 animate-spin"></div>
                <div class="absolute inset-2 bg-white rounded-full flex items-center justify-center">
                    <i data-lucide="cpu" class="w-5 h-5 text-indigo-600 animate-pulse"></i>
                </div>
            </div>
            <p class="text-sm font-bold text-slate-700 animate-pulse">Menganalisis Video di Server Python...</p>
            <p class="text-xs text-slate-400 mt-1 max-w-[280px]">Model YOLOv26 sedang mengekstrak koordinat piksel dan mengkonversinya ke satuan Centimeter.</p>
        </div>
    </div>

    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm flex flex-col">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <h2 class="text-base font-bold text-slate-800">Riwayat Video Tersimpan</h2>
            
            <div class="relative w-full sm:w-60 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 flex items-center gap-2 focus-within:ring-2 focus-within:ring-blue-500/20 focus-within:border-blue-500 transition-all">
                <i data-lucide="search" class="w-4 h-4 text-slate-400"></i>
                <input type="text" id="searchVideo" class="bg-transparent outline-none border-none text-xs text-slate-700 placeholder-slate-400 w-full" placeholder="Cari video atau sungai...">
            </div>
        </div>

        <div class="space-y-3 max-h-[500px] overflow-y-auto pr-2" id="videoListContainer">
            @forelse($videos as $v)
                <div class="flex items-center justify-between p-4 bg-white border border-slate-100 hover:border-blue-200 hover:bg-blue-50/20 rounded-2xl transition-all video-card-item" data-name="{{ strtolower($v->nama_sungai . ' ' . $v->file_video) }}">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-12 h-12 rounded-xl bg-slate-900 flex items-center justify-center text-slate-400 shrink-0 shadow-sm relative overflow-hidden">
                            <i data-lucide="video" class="w-5 h-5"></i>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-bold text-sm text-slate-800 truncate" title="{{ basename($v->file_video) }}">
                                {{ Str::limit(basename($v->file_video), 25) }}
                            </h4>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1 text-xs text-slate-500">
                                <span class="font-bold text-slate-700">{{ $v->nama_sungai }}</span>
                                <span>•</span>
                                <span>{{ \Carbon\Carbon::parse($v->waktu_rekaman)->format('d/m/Y') }}</span>
                                <span>•</span>
                                <span>{{ $v->ukuran_file }}</span>
                                <span>•</span>
                                <span class="font-bold text-blue-600">YOLO: <span class="cm-display">{{ $v->nilai_level }}</span> cm</span>
                            </div>
                            <div class="mt-2 flex items-center gap-2">
                                @php
                                    $badgeColor = 'bg-emerald-50 text-emerald-600 border-emerald-200';
                                    if(strtoupper($v->status_kondisi) == 'BAHAYA' || strtoupper($v->status_kondisi) == 'AWAS') $badgeColor = 'bg-red-50 text-red-600 border-red-200';
                                    elseif(strtoupper($v->status_kondisi) == 'SIAGA' || strtoupper($v->status_kondisi) == 'WASPADA') $badgeColor = 'bg-orange-50 text-orange-600 border-orange-200';
                                @endphp
                                <span class="text-[10px] font-extrabold px-2 py-0.5 {{ $badgeColor }} border rounded-md uppercase tracking-wide inline-block">
                                    {{ $v->status_kondisi }}
                                </span>
                                
                                @if($v->keterangan)
                                    <span class="text-[9px] font-medium text-slate-400 max-w-[150px] truncate" title="{{ $v->keterangan }}">
                                        {{ $v->keterangan }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 pl-4 shrink-0">
                        <a href="{{ asset('storage/' . $v->file_video) }}" target="_blank" class="p-2 hover:bg-slate-100 text-blue-600 rounded-xl transition-colors">
                            <i data-lucide="play" class="w-4 h-4 fill-blue-600/10"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-10">
                    <i data-lucide="database" class="w-8 h-8 text-slate-300 mx-auto mb-3"></i>
                    <p class="text-sm font-medium text-slate-500">Belum ada data video yang tersimpan di database.</p>
                </div>
            @endforelse
        </div>
        
        @if($videos->count() > 5)
            <button class="mt-4 w-full py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-500 hover:text-slate-800 border border-slate-200 rounded-2xl text-xs font-bold transition-all flex items-center justify-center gap-2">
                <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Muat lebih banyak
            </button>
        @endif
    </div>
</div>