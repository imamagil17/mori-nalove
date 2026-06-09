<x-app-layout>
    @section('title', 'Laporan Warga')
    
    <div class="py-8 relative min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- 🌟 TOP BAR HEADER: Desain Melayang & Sinkron dengan Halaman Lain -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 pb-2">
                <div class="space-y-1">
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                        <span class="p-2.5 bg-indigo-500/10 text-indigo-600 rounded-2xl backdrop-blur-sm">
                            <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                        </span>
                        Daftar Laporan Genangan
                    </h2>
                    <p class="text-slate-500 font-medium text-sm max-w-2xl">
                        Kelola dan tinjau berkas validasi laporan kondisi genangan yang dikirimkan langsung oleh warga.
                    </p>
                </div>

                <!-- BADGE STATISTIK MINI -->
                <div class="flex items-center gap-3 bg-white/40 backdrop-blur-md border border-white/50 rounded-2xl p-2.5 shadow-sm w-fit">
                    <div class="px-4 py-1 text-center">
                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-wider">Total Masuk</span>
                        <span class="text-sm font-black text-slate-700">{{ $reports->total() }} Laporan</span>
                    </div>
                </div>
            </div>

            <!-- 🌟 MODERN FLOATING TABLE LAYOUT (Tanpa Card Putih Kaku Pembungkus) -->
            <div class="overflow-x-auto pb-4">
                <table class="w-full text-left border-separate border-spacing-y-3.5">
                    <thead>
                        <tr class="text-slate-400 text-[11px] font-black uppercase tracking-wider">
                            <th class="px-6 py-2">Tanggal / Waktu</th>
                            <th class="px-6 py-2">Pelapor</th>
                            <th class="px-6 py-2">Lokasi Area</th>
                            <th class="px-6 py-2">Tingkat Genangan</th>
                            <th class="px-6 py-2 text-center">Foto Bukti</th>
                            <th class="px-6 py-2">Deskripsi</th>
                            <th class="px-6 py-2">Status Validasi</th>
                            <th class="px-6 py-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <!-- Baris Data Dibuat Melayang seperti Kartu Transparan Mandiri -->
                        <tr class="bg-white/40 backdrop-blur-md border border-white/50 shadow-sm rounded-3xl transition-all duration-300 hover:bg-white/60 hover:shadow-md group">
                            
                            <!-- Tanggal (Sisi kiri melengkung halus) -->
                            <td class="px-6 py-4 text-sm font-semibold text-slate-600 rounded-l-3xl whitespace-nowrap">
                                {{ $report->created_at->format('d M Y') }}
                                <span class="block text-xs text-slate-400 font-medium mt-0.5">{{ $report->created_at->format('H:i') }} WITA</span>
                            </td>
                            
                            <!-- Pelapor -->
                            <td class="px-6 py-4 text-sm font-extrabold text-slate-700 whitespace-nowrap">
                                {{ $report->nama_pelapor ?: ($report->user ? $report->user->name : 'Anonim') }}
                            </td>
                            
                            <!-- Lokasi -->
                            <td class="px-6 py-4 text-sm font-black text-slate-800 tracking-tight">
                                {{ $report->lokasi }}
                            </td>
                            
                            <!-- Tingkat Genangan Status Badge -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($report->tingkat_genangan == 'Tinggi')
                                    <span class="px-3 py-1.5 text-xs font-black rounded-full bg-red-500/10 text-red-700 inline-flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600 animate-pulse"></span> Tinggi
                                    </span>
                                @elseif($report->tingkat_genangan == 'Sedang')
                                    <span class="px-3 py-1.5 text-xs font-black rounded-full bg-orange-500/10 text-orange-700 inline-flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> Sedang
                                    </span>
                                @else
                                    <span class="px-3 py-1.5 text-xs font-black rounded-full bg-blue-500/10 text-blue-700 inline-flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Rendah
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Foto Bukti -->
                            <td class="px-6 py-4 text-center whitespace-nowrap flex justify-center">
                                @if($report->foto_bukti)
                                    <a href="{{ asset('storage/' . $report->foto_bukti) }}" target="_blank" class="block w-12 h-12 rounded-xl overflow-hidden border border-white/60 shadow-sm hover:scale-110 hover:rotate-2 transition-all duration-300">
                                        <img src="{{ asset('storage/' . $report->foto_bukti) }}" alt="Foto Bukti" class="w-full h-full object-cover">
                                    </a>
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-slate-200/40 flex items-center justify-center text-slate-400" title="Tidak ada foto">
                                        <i data-lucide="image-off" class="w-4 h-4 opacity-40"></i>
                                    </div>
                                @endif
                            </td>
                            
                            <!-- Deskripsi -->
                            <td class="px-6 py-4 text-sm font-medium text-slate-500 max-w-[200px] truncate" title="{{ $report->deskripsi }}">
                                {{ $report->deskripsi ?: '-' }}
                            </td>
                            
                            <!-- Status Logika -->
                            <td class="px-6 py-4 whitespace-nowrap" id="status-report-{{ $report->id }}">
                                @if($report->status == 'Pending')
                                    <span class="px-3 py-1.5 text-xs font-black rounded-full bg-amber-500/10 text-amber-700 inline-block">
                                        Pending
                                    </span>
                                @else
                                    <span class="px-3 py-1.5 text-xs font-black rounded-full bg-emerald-500/10 text-emerald-700 inline-flex items-center gap-1.5">
                                        <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Terverifikasi
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Tombol Aksi (Sisi kanan melengkung halus) -->
                            <td class="px-6 py-4 text-right whitespace-nowrap rounded-r-3xl">
                                <div class="flex items-center justify-end">
                                    <a href="{{ route('admin.citizen_reports.show', $report->id) }}" class="px-4 py-2.5 text-xs font-black bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 active:scale-95 transition-all inline-flex items-center gap-1.5">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i> 
                                        <span>Lihat Detail</span>
                                    </a>
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-16">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="p-4 bg-slate-100/50 rounded-2xl mb-3 border border-slate-200/40">
                                        <i data-lucide="inbox" class="w-8 h-8 opacity-40"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-600">Belum ada laporan masuk</p>
                                    <p class="text-xs text-slate-400 mt-1">Laporan warga seputar genangan air akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- 🌟 PAGINASI LARAVEL BERGAYA MINIMALIS -->
            @if($reports->hasPages())
            <div class="mt-4 bg-white/20 backdrop-blur-sm p-4 rounded-2xl border border-white/30 shadow-sm">
                {{ $reports->links() }}
            </div>
            @endif
            
        </div>
    </div>

    <!-- Script Renderer Ikon Lucide -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</x-app-layout>