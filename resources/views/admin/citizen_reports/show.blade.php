<x-app-layout>
    @section('title', 'Detail Laporan Warga')
    <div class="py-12">
        <div class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8">
            <div
                class="bg-white/40 backdrop-blur-md border border-white/50 rounded-3xl p-6 shadow-sm transition-all duration-300 hover:bg-white/60 hover:shadow-md overflow-hidden">

                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4 w-full">
                        <a href="{{ route('admin.citizen_reports.index') }}"
                            class="p-3 bg-white/40 hover:bg-white/60 text-slate-600 rounded-2xl border border-white/50 shadow-sm transition-colors shrink-0">
                            <i data-lucide="arrow-left" class="w-5 h-5"></i>
                        </a>
                        <div class="flex items-center justify-between w-full">
                            <div>
                                <h1 class="text-2xl font-black text-slate-800">Detail Laporan Warga</h1>
                                <p class="text-sm text-slate-500">Dilaporkan oleh: <span
                                        class="font-bold text-slate-700">{{ $report->nama_pelapor ?: 'Anonim' }}</span>
                                    pada {{ $report->created_at->format('d M Y - H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div>
                        <span class="text-xs uppercase font-extrabold text-slate-400 block mb-3">Foto Bukti Lapangan
                            (Galeri)</span>

                        @if ($report->foto_bukti && is_array($report->foto_bukti))
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach ($report->foto_bukti as $index => $foto)
                                    <div
                                        class="w-full rounded-2xl overflow-hidden border border-slate-200 shadow-sm bg-slate-50 relative group {{ $index == 0 && count($report->foto_bukti) % 2 != 0 ? 'sm:col-span-2' : '' }}">
                                        <img src="{{ asset('storage/' . $foto) }}" alt="Foto Bukti {{ $index + 1 }}"
                                            class="w-full h-48 sm:h-64 object-cover hover:scale-105 transition-transform duration-500">
                                        <a href="{{ asset('storage/' . $foto) }}" target="_blank"
                                            class="absolute bottom-3 right-3 bg-slate-900/80 hover:bg-slate-900 text-white p-2 rounded-xl text-xs font-bold transition-colors shadow-md">
                                            <i data-lucide="maximize-2" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @elseif ($report->foto_bukti && is_string($report->foto_bukti))
                            <div
                                class="w-full rounded-2xl overflow-hidden border border-slate-200 shadow-sm bg-slate-50 relative group">
                                <img src="{{ asset('storage/' . $report->foto_bukti) }}" alt="Foto Bukti"
                                    class="w-full h-auto object-cover max-h-[450px]">
                            </div>
                        @else
                            <div
                                class="w-full h-64 rounded-2xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400 bg-slate-50/50">
                                <i data-lucide="image-off" class="w-12 h-12 text-slate-300 mb-2"></i>
                                <span class="text-sm font-semibold">Tidak ada foto bukti terlampir</span>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-6">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div
                                class="bg-indigo-50/50 backdrop-blur-sm p-4 rounded-2xl border border-indigo-100 shadow-sm">
                                <span class="text-[10px] uppercase font-extrabold text-indigo-400 block mb-1">Kecamatan
                                    / Desa</span>
                                <span
                                    class="text-sm font-black text-indigo-700">{{ $report->kecamatan_desa ?: '-' }}</span>
                            </div>

                            <div class="bg-white/30 backdrop-blur-sm p-4 rounded-2xl border border-white/40 shadow-sm">
                                <span class="text-[10px] uppercase font-extrabold text-slate-400 block mb-1">Detail
                                    Sungai (Sub-DAS)</span>
                                <span class="text-sm font-extrabold text-slate-800">{{ $report->lokasi }}</span>
                            </div>

                            <div class="bg-white/30 backdrop-blur-sm p-4 rounded-2xl border border-white/40 shadow-sm">
                                <span class="text-[10px] uppercase font-extrabold text-slate-400 block mb-1">Tanggal &
                                    Waktu</span>
                                <span
                                    class="text-sm font-bold text-slate-700">{{ $report->created_at->format('d M Y, H:i') }}
                                    WITA</span>
                            </div>

                            <div class="bg-white/30 backdrop-blur-sm p-4 rounded-2xl border border-white/40 shadow-sm">
                                <span class="text-[10px] uppercase font-extrabold text-slate-400 block mb-1">Tingkat
                                    Genangan</span>
                                <div class="mt-1">
                                    @if ($report->tingkat_genangan == 'Tinggi')
                                        <span
                                            class="px-3 py-1 text-xs font-bold rounded-full bg-red-100/70 text-red-700 inline-block">Tinggi</span>
                                    @elseif($report->tingkat_genangan == 'Sedang')
                                        <span
                                            class="px-3 py-1 text-xs font-bold rounded-full bg-orange-100/70 text-orange-700 inline-block">Sedang</span>
                                    @else
                                        <span
                                            class="px-3 py-1 text-xs font-bold rounded-full bg-blue-100/70 text-blue-700 inline-block">Rendah
                                            / Aman</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-emerald-50/30 backdrop-blur-sm p-4 sm:p-5 rounded-2xl border border-emerald-100 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <span class="text-[10px] uppercase font-extrabold text-emerald-600 block mb-1">Koordinat
                                    GPS Titik Bencana</span>
                                <span
                                    class="text-sm font-mono font-bold text-slate-700">{{ $report->koordinat_lokasi ?: 'Tidak ada data satelit' }}</span>
                            </div>
                            @if ($report->koordinat_lokasi)
                                <a href="https://maps.google.com/?q={{ urlencode($report->koordinat_lokasi) }}"
                                    target="_blank"
                                    class="px-4 py-2 bg-emerald-600 text-white hover:bg-emerald-700 rounded-xl text-xs font-bold transition-colors flex items-center gap-1.5 shadow-sm whitespace-nowrap">
                                    <i data-lucide="map" class="w-4 h-4"></i> Lihat Peta
                                </a>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div
                                class="bg-rose-50/50 backdrop-blur-sm p-4 rounded-2xl border border-rose-100 shadow-sm">
                                <span class="text-[10px] uppercase font-extrabold text-rose-500 block mb-1">Warga
                                    Terdampak</span>
                                <span class="text-xl font-black text-rose-700">{{ $report->jumlah_terdampak ?: 0 }}
                                    <span class="text-xs font-bold text-rose-500">Jiwa</span></span>
                            </div>
                            <div
                                class="bg-amber-50/50 backdrop-blur-sm p-4 rounded-2xl border border-amber-100 shadow-sm">
                                <span class="text-[10px] uppercase font-extrabold text-amber-600 block mb-1">Fasilitas
                                    Publik Rusak</span>
                                <span
                                    class="text-sm font-bold text-amber-800 leading-snug">{{ $report->fasilitas_rusak ?: 'Nihil / Tidak Dilaporkan' }}</span>
                            </div>
                        </div>

                        <div class="bg-white/30 backdrop-blur-sm p-5 rounded-2xl border border-white/40 shadow-sm">
                            <span class="text-[10px] uppercase font-extrabold text-slate-400 block mb-1">Catatan /
                                Deskripsi Tambahan</span>
                            <p class="text-sm font-semibold text-slate-700 leading-relaxed whitespace-pre-wrap">
                                {{ $report->deskripsi ?: 'Tidak ada catatan tambahan.' }}</p>
                        </div>

                        <div class="bg-white/30 backdrop-blur-sm p-4 rounded-2xl border border-white/40 shadow-sm">
                            <span class="text-[10px] uppercase font-extrabold text-slate-400 block mb-1">Status
                                Verifikasi BNPB</span>
                            <div class="mt-1">
                                @if ($report->status == 'Pending')
                                    <span
                                        class="px-3 py-1 text-xs font-bold rounded-full bg-amber-100/70 text-amber-700 inline-block">Menunggu
                                        Evaluasi</span>
                                @else
                                    <span
                                        class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-100/70 text-emerald-700 inline-flex items-center gap-1">
                                        <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Terverifikasi Sah
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if ($report->status == 'Pending')
                            <div
                                class="flex flex-col sm:flex-row items-center justify-end gap-3 mt-6 pt-6 border-t border-slate-200/40">
                                <form action="/admin/citizen_reports/{{ $report->id }}" method="POST"
                                    class="m-0 w-full sm:w-auto"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menolak dan menghapus laporan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full sm:w-auto px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-xs shadow-md transition-all flex items-center justify-center gap-1.5">
                                        <i data-lucide="trash-2" class="w-4 h-4 shrink-0"></i> Tolak Laporan
                                    </button>
                                </form>

                                <form action="/admin/citizen_reports/{{ $report->id }}/verify" method="POST"
                                    class="m-0 w-full sm:w-auto" onsubmit="verifyLoading(event)">
                                    @csrf
                                    <button type="submit"
                                        class="w-full sm:w-auto px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-md transition-all flex items-center justify-center gap-1.5">
                                        <i data-lucide="check-check" class="w-4 h-4 shrink-0"></i> Setujui Laporan
                                    </button>
                                </form>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();

        function verifyLoading(event) {
            const form = event.currentTarget;
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML =
                    `<i data-lucide="loader-2" class="w-4 h-4 animate-spin shrink-0"></i> Memproses Verifikasi...`;
                btn.classList.add('opacity-70', 'cursor-not-allowed');
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
        }
    </script>
</x-app-layout>
