<div id="area-laporan-warga" class="relative">

    @if (session('success'))
        <div id="flash-success"
            class="fixed top-10 left-1/2 transform -translate-x-1/2 z-[9999] px-6 py-4 bg-emerald-500 text-white font-black rounded-2xl shadow-2xl flex items-center gap-3 animate-[slideDown_0.3s_ease-out] transition-opacity duration-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            Laporan Anda Berhasil Terkirim!
        </div>
    @endif

    @if ($errors->any())
        <div id="flash-error"
            class="fixed top-10 left-1/2 transform -translate-x-1/2 z-[9999] px-6 py-4 bg-red-600 text-white font-black rounded-2xl shadow-2xl flex flex-col gap-1 animate-[slideDown_0.3s_ease-out] transition-opacity duration-500">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>Gagal Mengirim Laporan!</span>
            </div>
            <ul class="text-xs font-medium pl-6 list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success') || $errors->any())
        <script>
            window.addEventListener('load', function() {
                // 1. Otomatis gulir (scroll) ke form pelaporan
                setTimeout(function() {
                    const area = document.getElementById('area-laporan-warga');
                    if (area) {
                        area.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                }, 300);

                // 2. Hilangkan notifikasi setelah 4 detik (4000 milidetik)
                setTimeout(function() {
                    const successAlert = document.getElementById('flash-success');
                    const errorAlert = document.getElementById('flash-error');

                    if (successAlert) {
                        successAlert.style.opacity = '0'; // Buat memudar
                        setTimeout(() => successAlert.remove(), 500); // Hapus dari HTML
                    }
                    if (errorAlert) {
                        errorAlert.style.opacity = '0'; // Buat memudar
                        setTimeout(() => errorAlert.remove(), 500); // Hapus dari HTML
                    }
                }, 4000);
            });
        </script>
    @endif

    <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data"
        class="space-y-5 bg-white p-6 rounded-3xl shadow-sm border border-slate-100 mt-4">
        @csrf

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pelapor (Wajib)</label>
            <input type="text" name="nama_pelapor" required placeholder="Masukkan nama lengkap Anda..."
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Kecamatan / Desa (Wajib)</label>
                <input type="text" name="kecamatan_desa" required placeholder="Contoh: Kec. Dolo Barat, Desa Loru"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Detail Sungai / Sub-DAS (Wajib)</label>
                <input type="text" name="lokasi" required placeholder="Contoh: Sub-DAS Jauh"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>

        <div class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100 shadow-sm">
            <label class="block text-xs font-bold text-indigo-700 mb-1">Titik Koordinat GPS (Wajib)</label>
            <div class="flex flex-col sm:flex-row gap-2">
                <input type="text" id="koordinat_lokasi" name="koordinat_lokasi" readonly required
                    placeholder="Klik tombol Ambil GPS..."
                    class="w-full bg-white border border-indigo-200 rounded-xl px-4 py-3 text-sm text-slate-600 focus:outline-none">
                <button type="button" onclick="dapatkanLokasiGPS()"
                    class="bg-indigo-600 text-white px-5 py-3 rounded-xl text-xs font-bold hover:bg-indigo-700 transition shrink-0 shadow-md flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
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
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 cursor-pointer">
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
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Fasilitas Rusak (Opsional)</label>
                <input type="text" name="fasilitas_rusak" placeholder="Contoh: 1 Jembatan Putus"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan / Kondisi Air (Wajib)</label>
            <textarea name="deskripsi" required rows="3" placeholder="Gambarkan situasi genangan air secara singkat..."
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500"></textarea>
        </div>

        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
            <label class="block text-xs font-bold text-slate-700 mb-1">Foto Bukti Lapangan (Bisa lebih dari 1, Max
                5)</label>
            <input type="file" name="foto_bukti[]" multiple required accept="image/*"
                class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200 cursor-pointer">
        </div>

        <button type="submit"
            class="w-full mt-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-black py-4 rounded-xl shadow-lg hover:shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                stroke-linejoin="round">
                <line x1="22" y1="2" x2="11" y2="13"></line>
                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
            </svg>
            Kirim Laporan Bencana
        </button>
    </form>
</div>

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
                }
            );
        } else {
            pesanGps.innerText = "Browser Anda tidak mendukung pelacakan lokasi. ❌";
            pesanGps.className = "text-[10px] text-red-600 mt-1.5 font-semibold";
        }
    }
</script>
