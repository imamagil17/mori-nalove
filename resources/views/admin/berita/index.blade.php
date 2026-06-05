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

            <!-- 🌟 TOP BAR HEADER & UTILITY FILTER: Diadopsi dari Log Notifikasi (image_989301.png) -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="space-y-1">
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                        <span class="p-2.5 bg-blue-500/10 text-blue-600 rounded-2xl backdrop-blur-sm">
                            <i data-lucide="newspaper" class="w-5 h-5"></i>
                        </span>
                        Kelola Berita & Informasi
                    </h2>
                    <p class="text-slate-500 font-medium text-sm max-w-2xl">
                        Tulis berita mitigasi baru atau pantau seluruh riwayat informasi publik yang telah disiarkan ke dalam sistem Mori Nalove.
                    </p>
                </div>

            </div>

            <!-- 🌟 AREA DUA KOLOM: FORM KIRI & RIWAYAT KANAN (BENTO LAYOUT MODERN) -->
            <div class="grid grid-cols-1 xl:grid-cols-5 gap-8 items-start">
                
                <!-- KOLOM KIRI: FORM TULIS BERITA BARU (2/5 RUANG) -->
                <div id="formTulisBerita" class="xl:col-span-2 bg-white/40 backdrop-blur-md border border-white/50 rounded-3xl p-6 shadow-sm transition-all duration-300 hover:bg-white/50 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50/50 rounded-full blur-3xl -z-10"></div>
                    
                    <h3 class="text-base font-black text-slate-800 mb-5 tracking-tight flex items-center gap-2.5">
                        <div class="p-2 bg-blue-100/80 text-blue-600 rounded-xl"><i data-lucide="plus" class="w-4 h-4"></i></div>
                        Tulis Berita Baru
                    </h3>
                    
                    <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 relative z-10">
                        @csrf
                        
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Judul Berita</label>
                            <input type="text" name="judul" required placeholder="Misal: Peringatan Siaga Banjir dari BPBD..." class="w-full bg-white/60 border border-slate-200/80 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Isi Berita Lengkap</label>
                            <textarea name="konten" required rows="5" placeholder="Tulis deskripsi lengkap berita di sini..." class="w-full bg-white/60 border border-slate-200/80 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Foto/Gambar Banner <span class="text-slate-400 font-normal lowercase">(maks 2MB, jpg/png)</span></label>
                            <input type="file" name="foto" required accept="image/*" class="w-full bg-white/60 border border-slate-200/80 rounded-xl px-4 py-2 text-sm text-slate-500 focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-1.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition-all shadow-sm cursor-pointer">
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-black text-xs uppercase tracking-wide transition-all flex items-center justify-center gap-2 shadow-md hover:shadow-lg shadow-blue-500/10 active:scale-[0.98]">
                                <i data-lucide="send" class="w-4 h-4"></i> Terbitkan Berita
                            </button>
                        </div>
                    </form>
                </div>

                <!-- KOLOM KANAN: TABEL RIWAYAT BERITA AKTIF (3/5 RUANG - DIADOPSI DARI GAMBAR KEDUAMU) -->
                <div class="xl:col-span-3 bg-white/40 backdrop-blur-md border border-white/50 rounded-3xl p-6 shadow-sm transition-all duration-300 hover:bg-white/50 space-y-4">
                    
                    <div class="flex items-center justify-between border-b border-slate-200/40 pb-4">
                        <h3 class="text-base font-black text-slate-800 tracking-tight flex items-center gap-2.5">
                            <div class="p-2 bg-indigo-100/80 text-indigo-600 rounded-xl"><i data-lucide="list-video" class="w-4 h-4"></i></div>
                            Riwayat Berita Terbit
                        </h3>
                        <span class="text-xs font-bold text-slate-500 bg-white/60 px-3 py-1.5 rounded-xl border border-slate-200/40 shadow-sm">
                            Total: {{ count($beritas ?? []) }} Berita
                        </span>
                    </div>

                    <!-- SEAMLESS TRANSPARENT TABLE -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200/40">
                                    <th class="pb-4 pt-2 font-black text-slate-400 text-[11px] uppercase tracking-wider w-[25%]">Waktu Terbit</th>
                                    <th class="pb-4 pt-2 font-black text-slate-400 text-[11px] uppercase tracking-wider w-[55%]">Preview Konten</th>
                                    <th class="pb-4 pt-2 font-black text-slate-400 text-[11px] uppercase tracking-wider w-[20%] text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200/30">
                                @forelse($beritas ?? [] as $berita)
                                <tr class="hover:bg-white/40 transition-colors duration-150 group">
                                    <!-- Kolom Waktu Terbit -->
                                    <td class="py-4 pr-3 align-top">
                                        <span class="text-sm font-bold text-slate-700 block group-hover:text-slate-900 transition-colors">
                                            {{ $berita->created_at->format('d M Y') }}
                                        </span>
                                        <span class="text-xs text-slate-400 font-semibold block mt-0.5">
                                            {{ $berita->created_at->format('H:i') }} WITA
                                        </span>
                                    </td>
                                    
                                    <!-- Kolom Detail Ringkasan Konten -->
                                    <td class="py-4 pr-3 align-top">
                                        <div class="flex flex-col sm:flex-row gap-3 items-start">
                                            @if($berita->foto)
                                                <div class="w-20 h-14 rounded-xl overflow-hidden border border-slate-200/60 shadow-sm shrink-0 bg-slate-100">
                                                    <img src="{{ asset('storage/' . $berita->foto) }}" alt="Foto" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                                </div>
                                            @endif
                                            <div class="space-y-1">
                                                <p class="font-extrabold text-slate-800 text-sm leading-snug line-clamp-2 group-hover:text-blue-600 transition-colors">
                                                    {{ $berita->judul }}
                                                </p>
                                                <p class="text-xs text-slate-400 font-medium line-clamp-1">
                                                    {{ strip_tags($berita->konten) }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Kolom Tombol Aksi Kelola -->
                                    <td class="py-4 align-top text-right">
                                        <div class="flex items-center justify-end gap-1.5">
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
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="p-12 text-center text-slate-400">
                                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-3 opacity-40 text-slate-400"></i>
                                        <p class="text-sm font-bold text-slate-600">Belum ada berita yang diterbitkan.</p>
                                        <p class="text-xs text-slate-400 mt-1">Gunakan panel formulir di sisi kiri untuk menyiarkan informasi baru.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Script Pendukung Lucide Icon -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
</x-app-layout>