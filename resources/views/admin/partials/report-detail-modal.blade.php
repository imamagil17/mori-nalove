<div id="modalDetailReport" class="fixed inset-0 z-[100] hidden bg-slate-900/85 backdrop-blur-sm flex-col items-center justify-center p-4 transition-opacity duration-300 opacity-0">
    <div class="bg-white rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl flex flex-col max-h-[90vh] transform scale-95 transition-transform duration-300" id="modalDetailReportContent">
        <div class="flex justify-between items-center p-5 border-b border-slate-100 bg-slate-50/80">
            <div class="flex items-center gap-2">
                <i data-lucide="clipboard-list" class="w-5 h-5 text-blue-600"></i>
                <h4 class="font-bold text-slate-800 text-sm">Detail Informasi Laporan Warga</h4>
            </div>
            <button onclick="closeModalDetail()" class="p-2 bg-slate-200 hover:bg-red-100 hover:text-red-600 text-slate-500 rounded-full transition-colors focus:outline-none">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="overflow-y-auto p-6 space-y-6 bg-white flex-grow">
            <div id="detailFotoContainer" class="w-full h-72 rounded-2xl overflow-hidden border border-slate-200 shadow-sm relative group bg-slate-50">
                <img id="detailFoto" src="" class="w-full h-full object-cover" alt="Foto Bukti">
                <a id="detailFotoLink" href="" target="_blank" class="absolute bottom-4 right-4 bg-slate-900/80 hover:bg-slate-900 text-white px-3 py-1.5 rounded-xl text-[10px] font-bold transition-colors flex items-center gap-1.5 shadow-md">
                    <i data-lucide="external-link" class="w-3 h-3"></i> Lihat Ukuran Penuh
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-slate-50/80 p-3.5 rounded-2xl border border-slate-100 shadow-sm">
                    <span class="text-[9px] uppercase font-extrabold text-slate-400 block mb-1">Tanggal Laporan</span>
                    <span id="detailTanggal" class="text-xs font-bold text-slate-700"></span>
                </div>
                <div class="bg-slate-50/80 p-3.5 rounded-2xl border border-slate-100 shadow-sm">
                    <span class="text-[9px] uppercase font-extrabold text-slate-400 block mb-1">Pelapor</span>
                    <span id="detailPelapor" class="text-xs font-bold text-slate-700"></span>
                </div>
                <div class="bg-slate-50/80 p-3.5 rounded-2xl border border-slate-100 shadow-sm">
                    <span class="text-[9px] uppercase font-extrabold text-slate-400 block mb-1">Lokasi (Aliran Sungai)</span>
                    <span id="detailLokasi" class="text-xs font-extrabold text-slate-800"></span>
                </div>
                <div class="bg-slate-50/80 p-3.5 rounded-2xl border border-slate-100 shadow-sm">
                    <span class="text-[9px] uppercase font-extrabold text-slate-400 block mb-1">Tingkat Genangan</span>
                    <div id="detailGenanganBadge" class="mt-0.5"></div>
                </div>
            </div>

            <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-100 shadow-sm">
                <span class="text-[9px] uppercase font-extrabold text-slate-400 block mb-1">Catatan Tambahan</span>
                <p id="detailDeskripsi" class="text-xs font-semibold text-slate-700 leading-relaxed whitespace-pre-wrap"></p>
            </div>
        </div>

        <div class="p-5 border-t border-slate-100 bg-slate-50/80 flex justify-end gap-3" id="detailActions">
            <form id="formTolak" action="" method="POST" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin menolak dan menghapus laporan ini secara permanen?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-xs shadow-md hover:shadow-lg transition-colors flex items-center gap-1.5">
                    <i data-lucide="trash-2" class="w-4 h-4"></i> Tolak Laporan
                </button>
            </form>

            <form id="formVerifikasi" action="" method="POST" class="m-0">
                @csrf
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-md hover:shadow-lg transition-colors flex items-center gap-1.5">
                    <i data-lucide="check" class="w-4 h-4"></i> Verifikasi / Setujui Laporan
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    function openModalDetail(id, tanggal, pelapor, lokasi, genangan, deskripsi, fotoBukti, status) {
        document.getElementById('detailTanggal').textContent = tanggal + ' WITA';
        document.getElementById('detailPelapor').textContent = pelapor || 'Anonim';
        document.getElementById('detailLokasi').textContent = lokasi;
        document.getElementById('detailDeskripsi').textContent = deskripsi || 'Tidak ada catatan tambahan.';

        // Badge genangan
        const badgeContainer = document.getElementById('detailGenanganBadge');
        if (genangan === 'Tinggi') {
            badgeContainer.innerHTML = '<span class="px-2.5 py-1 text-[10px] font-bold rounded-lg bg-red-100 text-red-700 border border-red-200 inline-block shadow-sm">Tinggi</span>';
        } else if (genangan === 'Sedang') {
            badgeContainer.innerHTML = '<span class="px-2.5 py-1 text-[10px] font-bold rounded-lg bg-orange-100 text-orange-700 border border-orange-200 inline-block shadow-sm">Sedang</span>';
        } else {
            badgeContainer.innerHTML = '<span class="px-2.5 py-1 text-[10px] font-bold rounded-lg bg-blue-100 text-blue-700 border border-blue-200 inline-block shadow-sm">Rendah</span>';
        }

        // Foto Bukti
        const img = document.getElementById('detailFoto');
        const link = document.getElementById('detailFotoLink');
        const fotoContainer = document.getElementById('detailFotoContainer');
        if (fotoBukti) {
            img.src = '/storage/' + fotoBukti;
            link.href = '/storage/' + fotoBukti;
            fotoContainer.style.display = 'block';
        } else {
            img.src = '';
            link.href = '';
            fotoContainer.style.display = 'none';
        }

        // 🌟 FIX SAKTI: URL Target Aksi Menggunakan Rute Mentah Langsung
        document.getElementById('formTolak').action = `/admin/citizen_reports/${id}`;
        document.getElementById('formVerifikasi').action = `/admin/citizen_reports/${id}/verify`;

        // Actions container visibility based on status
        const actions = document.getElementById('detailActions');
        if (status === 'Pending') {
            actions.style.display = 'flex';
        } else {
            actions.style.display = 'none';
        }

        // Open Modal
        const modal = document.getElementById('modalDetailReport');
        const modalContent = document.getElementById('modalDetailReportContent');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
        
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    function closeModalDetail() {
        const modal = document.getElementById('modalDetailReport');
        const modalContent = document.getElementById('modalDetailReportContent');
        
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 300);
    }
</script>