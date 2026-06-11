<form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
    @csrf

    <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pelapor (Wajib)</label>
        <input type="text" name="nama_pelapor" required placeholder="Masukkan nama lengkap Anda..."
            class="w-full bg-white/60 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Kecamatan / Desa (Wajib)</label>
            <input type="text" name="kecamatan_desa" required placeholder="Contoh: Kec. Dolo Barat, Desa Loru"
                class="w-full bg-white/60 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Detail Sungai / Sub-DAS (Wajib)</label>
            <input type="text" name="lokasi" required placeholder="Contoh: Sub-DAS Jauh"
                class="w-full bg-white/60 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
        </div>
    </div>

    <div class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100 shadow-sm">
        <label class="block text-xs font-bold text-indigo-700 mb-1">Titik Koordinat GPS (Wajib)</label>
        <div class="flex flex-col sm:flex-row gap-2">
            <input type="text" id="koordinat_lokasi" name="koordinat_lokasi" readonly required
                placeholder="Klik tombol Ambil GPS..."
                class="w-full bg-white border border-indigo-200 rounded-xl px-3 py-2 text-sm text-slate-600 focus:outline-none">
            <button type="button" onclick="dapatkanLokasiGPS()"
                class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-indigo-700 transition shrink-0 shadow-md flex items-center justify-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="3 11 22 2 13 21 11 13 3 11"></polygon>
                </svg>
                Ambil GPS
            </button>
        </div>
        <p id="pesan_gps" class="text-[10px] text-amber-600 mt-1.5 font-semibold hidden">Meminta akses lokasi...</p>
    </div>

    <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Tingkat Genangan (Wajib)</label>
        <select name="tingkat_genangan" required
            class="w-full bg-white/60 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 cursor-pointer">
            <option value="" disabled selected>-- Pilih Perkiraan Ketinggian Air --</option>
            <option value="Rendah">Rendah / Aman (Semata kaki)</option>
            <option value="Sedang">Sedang / Siaga (Selutut)</option>
            <option value="Tinggi">Tinggi / Bahaya (Sepinggang atau lebih)</option>
        </select>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Warga Terdampak (Opsional)</label>
            <input type="number" name="jumlah_terdampak" placeholder="Contoh: 15 (Jiwa)"
                class="w-full bg-white/60 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Fasilitas Rusak (Opsional)</label>
            <input type="text" name="fasilitas_rusak" placeholder="Contoh: 1 Jembatan Putus"
                class="w-full bg-white/60 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
        </div>
    </div>

    <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan / Kondisi Air (Wajib)</label>
        <textarea name="deskripsi" required rows="3" placeholder="Gambarkan situasi genangan air secara singkat..."
            class="w-full bg-white/60 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500"></textarea>
    </div>

    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
        <label class="block text-xs font-bold text-slate-700 mb-1">Foto Bukti Lapangan (Bisa lebih dari 1, Max
            5)</label>
        <input type="file" name="foto_bukti[]" multiple required accept="image/*"
            class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200 cursor-pointer">
        <p class="text-[10px] text-slate-500 mt-1.5">*Tahan tombol <span class="font-bold text-slate-700">CTRL</span>
            (di PC) atau tahan gambar (di HP) untuk memilih beberapa foto sekaligus.</p>
    </div>

    <button type="submit"
        class="w-full mt-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-black py-3 rounded-xl shadow-lg hover:shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="22" y1="2" x2="11" y2="13"></line>
            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
        </svg>
        Kirim Laporan Bencana
    </button>
</form>

<script>
    function dapatkanLokasiGPS() {
        const pesanGps = document.getElementById('pesan_gps');
        const inputGps = document.getElementById('koordinat_lokasi');

        pesanGps.classList.remove('hidden');
        pesanGps.innerText = "Mencari satelit lokasi Anda...";
        pesanGps.className = "text-[10px] text-amber-600 mt-1.5 font-semibold";

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    inputGps.value = position.coords.latitude + ", " + position.coords.longitude;
                    pesanGps.innerText = "Lokasi berhasil ditemukan! ✅";
                    pesanGps.className = "text-[10px] text-emerald-600 mt-1.5 font-semibold";
                },
                function(error) {
                    pesanGps.innerText =
                        "Gagal mengambil lokasi. Pastikan GPS/Location Anda aktif dan diizinkan pada browser! ❌";
                    pesanGps.className = "text-[10px] text-red-600 mt-1.5 font-semibold";
                }, {
                    enableHighAccuracy: true
                } // Memaksa akurasi tinggi GPS
            );
        } else {
            pesanGps.innerText = "Browser Anda tidak mendukung pelacakan lokasi. ❌";
            pesanGps.className = "text-[10px] text-red-600 mt-1.5 font-semibold";
        }
    }
</script>
