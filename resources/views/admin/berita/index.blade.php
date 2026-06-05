<x-app-layout>
    @section('title', 'Kelola Berita')
    
    <div class="py-8 relative min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- 🌟 ALERTS NOTIFIKASI SUCCESS -->
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl relative flex items-center gap-3 shadow-sm" role="alert">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-green-500"></i>
                <span class="block sm:inline font-semibold text-sm">{{ session('success') }}</span>
            </div>
            @endif

            <!-- 🌟 STRUKTUR HEADER UTAMA & UTILITY BAR (BAGIAN ATAS) -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-4">
                <div>
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                        <span class="p-2.5 bg-blue-500/10 text-blue-600 rounded-2xl backdrop-blur-sm">
                            <i data-lucide="newspaper" class="w-6 h-6"></i>
                        </span>
                        Kelola Berita & Informasi
                    </h2>
                    <p class="text-slate-500 mt-2 text-sm">Tulis berita mitigasi baru atau pantau seluruh riwayat informasi publik yang telah disiarkan.</p>
                </div>
                
                <!-- Utility Bar / Dropdown Filter -->
                <div class="w-full sm:w-auto bg-white/40 backdrop-blur-md border border-white/50 rounded-2xl p-4 flex flex-col sm:flex-row items-end gap-3.5 shadow-sm shrink-0 transition-all duration-300 hover:bg-white/60 hover:shadow-md">
                    <div class="w-full sm:w-48">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Filter Kategori</label>
                        <div class="relative">
                            <select id="kategori_filter" class="w-full bg-white/30 border border-white/40 rounded-xl pl-3 pr-8 py-2 text-xs font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 cursor-pointer appearance-none transition-all">
                                <option value="semua">Semua Berita</option>
                                <option value="peringatan">Peringatan Banjir</option>
                                <option value="imbauan">Imbauan/Panduan</option>
                                <option value="cuaca">Info Cuaca</option>
                            </select>
                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 🌟 PENATAAN FORM "TULIS BERITA BARU" (BAGIAN TENGAH - FULL WIDTH) -->
            <div id="formTulisBerita" class="w-full bg-white/40 backdrop-blur-md border border-white/50 rounded-3xl p-6 shadow-sm transition-all duration-300 hover:bg-white/50 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50/50 rounded-full blur-3xl -z-10"></div>
                
                <h3 class="text-lg font-black text-slate-800 mb-5 tracking-tight flex items-center gap-2.5">
                    <div class="p-2 bg-blue-100/80 text-blue-600 rounded-xl"><i data-lucide="plus" class="w-4 h-4"></i></div>
                    Tulis Berita Baru
                </h3>
                
                <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5 relative z-10">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-1.5">Judul Berita</label>
                                <input type="text" name="judul" required placeholder="Misal: Peringatan Siaga Banjir dari BPBD..." class="w-full bg-white/60 border border-slate-200/80 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-1.5">Foto/Gambar Banner <span class="text-slate-400 font-normal lowercase">(maks 2MB, jpg/png)</span></label>
                                <input type="file" name="foto" required accept="image/*" class="w-full bg-white/60 border border-slate-200/80 rounded-xl px-4 py-2 text-sm text-slate-500 focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-1.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition-all shadow-sm cursor-pointer">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-1.5">Isi Berita Lengkap</label>
                            <textarea name="konten" required rows="5" placeholder="Tulis deskripsi lengkap berita di sini..." class="w-full bg-white/60 border border-slate-200/80 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm h-[calc(100%-24px)] resize-none"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-black text-xs uppercase tracking-wide transition-all flex items-center gap-2 shadow-md hover:shadow-lg shadow-blue-500/10 active:scale-[0.98]">
                            <i data-lucide="send" class="w-4 h-4"></i> Terbitkan Berita
                        </button>
                    </div>
                </form>
            </div>

            <!-- 🌟 PENAMBAHAN TABEL "RIWAYAT BERITA TERBIT" (BAGIAN BAWAH - FULL WIDTH) -->
            <div class="w-full bg-white/40 backdrop-blur-md border border-white/50 rounded-3xl p-6 shadow-sm transition-all duration-300 hover:bg-white/60 space-y-5">
                
                <div class="flex items-center justify-between border-b border-slate-200/40 pb-4">
                    <h3 class="text-lg font-black text-slate-800 tracking-tight flex items-center gap-2.5">
                        <div class="p-2 bg-indigo-100/80 text-indigo-600 rounded-xl"><i data-lucide="list-video" class="w-4 h-4"></i></div>
                        Riwayat Berita Terbit
                    </h3>
                    <span class="text-xs font-bold text-slate-500 bg-white/60 px-3 py-1.5 rounded-xl border border-slate-200/40 shadow-sm">
                        Total: {{ count($beritas ?? []) }} Berita
                    </span>
                </div>

                <!-- SEAMLESS TRANSPARENT TABLE -->
                <div class="overflow-x-auto rounded-2xl border border-white/40">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/20 text-slate-800 text-[11px] uppercase tracking-wider border-b border-white/40">
                                <th class="p-4 font-black text-slate-800 text-xs uppercase tracking-wider rounded-tl-xl w-[20%]">Waktu Terbit</th>
                                <th class="p-4 font-black text-slate-800 text-xs uppercase tracking-wider w-[60%]">Preview Konten</th>
                                <th class="p-4 font-black text-slate-800 text-xs uppercase tracking-wider text-right rounded-tr-xl w-[20%]">Status & Aksi Kelola</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/30 text-sm">
                            @forelse($beritas ?? [] as $berita)
                            <tr class="hover:bg-white/40 transition-colors duration-150 group news-row">
                                <!-- Kolom Waktu Terbit -->
                                <td class="p-4 align-middle">
                                    <span class="text-sm font-bold text-slate-700 block group-hover:text-slate-900 transition-colors">
                                        {{ $berita->created_at->format('d M Y') }}
                                    </span>
                                    <span class="text-xs text-slate-400 font-semibold block mt-0.5">
                                        {{ $berita->created_at->format('H:i') }} WITA
                                    </span>
                                </td>
                                
                                <!-- Kolom Detail Ringkasan Konten -->
                                <td class="p-4 align-middle">
                                    <div class="flex items-center gap-4">
                                        @if($berita->foto)
                                            <div class="w-20 h-14 rounded-xl overflow-hidden border border-slate-200/60 shadow-sm shrink-0 bg-slate-100">
                                                <img src="{{ asset('storage/' . $berita->foto) }}" alt="Foto" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                            </div>
                                        @endif
                                        <div class="space-y-1 min-w-0">
                                            <p class="font-extrabold text-slate-800 text-sm leading-snug line-clamp-1 group-hover:text-blue-600 transition-colors">
                                                {{ $berita->judul }}
                                            </p>
                                            <p class="text-xs text-slate-500 font-medium line-clamp-2 leading-relaxed">
                                                {{ strip_tags($berita->konten) }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kolom Tombol Aksi Kelola -->
                                <td class="p-4 align-middle text-right">
                                    <div class="flex flex-col items-end gap-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100/70 text-emerald-700">
                                            Diterbitkan
                                        </span>
                                        <div class="flex items-center justify-end gap-1.5 mt-1">
                                            <!-- Edit Button -->
                                            <a href="{{ route('admin.berita.edit', $berita->id) }}" class="text-blue-600 hover:text-white bg-blue-500/10 hover:bg-blue-600 p-2 rounded-xl transition-all border border-blue-500/10 group/btn" title="Edit Berita">
                                                <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                            </a>

                                            <!-- Hapus Button Form -->
                                            <form action="{{ route('admin.berita.destroy', $berita->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini secara permanen?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-white bg-red-500/10 hover:bg-red-600 p-2 rounded-xl transition-all border border-red-500/10" title="Hapus Berita">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="p-12 text-center text-slate-400">
                                    <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-3 opacity-40 text-slate-400"></i>
                                    <p class="text-sm font-bold text-slate-600">Belum ada berita yang diterbitkan.</p>
                                    <p class="text-xs text-slate-400 mt-1">Gunakan panel formulir di atas untuk menyiarkan informasi baru.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Script Pendukung Lucide Icon & Filter Kategori Klien -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();

            // Client-side Filter logic
            const filterSelect = document.getElementById('kategori_filter');
            if (filterSelect) {
                filterSelect.addEventListener('change', function() {
                    const filterValue = this.value;
                    const rows = document.querySelectorAll('.news-row');
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        if (filterValue === 'semua') {
                            row.style.display = '';
                        } else if (filterValue === 'peringatan' && (text.includes('peringatan') || text.includes('banjir') || text.includes('siaga') || text.includes('waspada') || text.includes('bahaya') || text.includes('darurat'))) {
                            row.style.display = '';
                        } else if (filterValue === 'imbauan' && (text.includes('imbauan') || text.includes('panduan') || text.includes('himbauan') || text.includes('tips') || text.includes('evakuasi') || text.includes('kesiapsiagaan'))) {
                            row.style.display = '';
                        } else if (filterValue === 'cuaca' && (text.includes('cuaca') || text.includes('hujan') || text.includes('bmkg') || text.includes('mendung') || text.includes('cerah') || text.includes('awan'))) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
</x-app-layout>