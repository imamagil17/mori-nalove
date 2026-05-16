<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-slate-800 leading-tight flex items-center gap-2">
            <i data-lucide="newspaper" class="w-6 h-6 text-blue-600"></i>
            {{ __('Kelola Berita Publik') }}
        </h2>
    </x-slot>

    <div class="py-8 relative min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl relative flex items-center gap-3 shadow-sm" role="alert">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-green-500"></i>
                <span class="block sm:inline font-semibold text-sm">{{ session('success') }}</span>
            </div>
            @endif

            <div class="bg-white/70 backdrop-blur-md rounded-3xl p-6 shadow-sm border border-slate-200/60 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full blur-3xl -z-10 group-hover:bg-blue-100 transition-colors"></div>
                
                <h3 class="text-lg font-bold text-slate-800 mb-5 flex items-center gap-2">
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-xl"><i data-lucide="plus" class="w-4 h-4"></i></div>
                    Tulis Berita Baru
                </h3>
                
                <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5 relative z-10">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Judul Berita</label>
                        <input type="text" name="judul" required placeholder="Misal: Peringatan Siaga Banjir dari BPBD..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-inner">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Isi Berita Lengkap</label>
                        <textarea name="konten" required rows="4" placeholder="Tulis deskripsi lengkap berita di sini..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-inner"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Foto/Gambar Banner <span class="text-slate-400 font-normal">(Maksimal 2MB, Format: JPG/PNG)</span></label>
                        <input type="file" name="foto" required accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition-all shadow-inner cursor-pointer">
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-colors flex items-center gap-2 shadow-md hover:shadow-lg">
                            <i data-lucide="send" class="w-4 h-4"></i> Terbitkan Berita
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white/70 backdrop-blur-md rounded-3xl p-6 shadow-sm border border-slate-200/60">
                <h3 class="text-lg font-bold text-slate-800 mb-5 flex items-center gap-2">
                    <div class="p-2 bg-indigo-100 text-indigo-600 rounded-xl"><i data-lucide="list-video" class="w-4 h-4"></i></div>
                    Daftar Berita Aktif
                </h3>

                <div class="overflow-x-auto rounded-xl border border-slate-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-[11px] uppercase tracking-wider">
                                <th class="p-4 font-extrabold rounded-tl-xl">Foto</th>
                                <th class="p-4 font-extrabold">Judul Berita</th>
                                <th class="p-4 font-extrabold">Tanggal Diterbitkan</th>
                                <th class="p-4 font-extrabold text-right rounded-tr-xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($beritas as $berita)
                            <tr class="hover:bg-blue-50/50 transition-colors group">
                                <td class="p-4">
                                    <div class="w-24 h-16 rounded-lg overflow-hidden border border-slate-200 shadow-sm">
                                        <img src="{{ asset('storage/' . $berita->foto) }}" alt="Foto" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    </div>
                                </td>
                                <td class="p-4">
                                    <p class="font-bold text-slate-800 text-sm line-clamp-2">{{ $berita->judul }}</p>
                                </td>
                                <td class="p-4 text-xs font-semibold text-slate-500">
                                    {{ $berita->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.berita.edit', $berita->id) }}" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-3 py-2 rounded-lg text-xs font-bold transition-colors inline-flex items-center gap-1.5 border border-blue-100">
                                            <i data-lucide="edit" class="w-3.5 h-3.5"></i> Edit
                                        </a>

                                        <form action="{{ route('admin.berita.destroy', $berita->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini secara permanen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-2 rounded-lg text-xs font-bold transition-colors inline-flex items-center gap-1.5 border border-red-100">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-10 text-center text-slate-400">
                                    <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-3 opacity-50"></i>
                                    <p class="text-sm font-semibold">Belum ada berita yang ditambahkan.</p>
                                    <p class="text-xs mt-1">Gunakan form di atas untuk menulis berita pertama Anda.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>