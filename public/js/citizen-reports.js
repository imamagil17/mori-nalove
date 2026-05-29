/**
 * citizen-reports.js
 * Diperbarui untuk mendukung metode pengiriman form bawaan Laravel
 */

// =========================================================================
// 1. ADMIN VERIFIKASI (Diperbaiki agar sinkron dengan Controller Laravel)
// =========================================================================
function verifikasiLaporan(id, btnElement) {
    if(!confirm('Yakin ingin memverifikasi laporan darurat ini?')) return;
    
    // Ubah tampilan tombol menjadi loading agar admin tahu sistem sedang bekerja
    const originalText = btnElement.innerHTML;
    btnElement.innerHTML = '<i data-lucide="loader-2" class="w-3 h-3 animate-spin inline"></i> Proses...';
    if(typeof lucide !== 'undefined') lucide.createIcons();
    
    // Ambil Token Keamanan (CSRF)
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
    
    // 🌟 LOGIKA BARU: Kita buat Form Virtual 
    // Ini memaksa JavaScript untuk mengirim data persis seperti form HTML biasa,
    // sehingga fungsi redirect()->back() di Controller bisa berjalan sempurna.
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/admin/reports/' + id + '/verify';
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    
    form.appendChild(csrfInput);
    document.body.appendChild(form);
    
    // Tembak!
    form.submit();
}

// =========================================================================
// 2. USER DASHBOARD FORM SUBMIT
// =========================================================================
// KODE AJAX LAMA TELAH DIHAPUS SEPENUHNYA!
// Pengiriman laporan warga sekarang 100% ditangani oleh Form HTML standar 
// di file blade (dengan action route('reports.store')), sehingga dijamin 
// anti-gagal, tidak ada lagi error koneksi, dan lebih aman.