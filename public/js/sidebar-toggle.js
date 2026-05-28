document.addEventListener('DOMContentLoaded', function() {
    const btnToggle = document.getElementById('btnToggleSidebar');
    const sidebar = document.getElementById('sidebarAdmin');
    
    if (!btnToggle || !sidebar) return;

    // Ambil pembungkus logo paling atas di dalam sidebar
    const brandHeader = sidebar.querySelector('.h-16');

    function applyCollapsedState(isCollapsed) {
        if (isCollapsed) {
            document.documentElement.classList.add('sidebar-is-collapsed');
            sidebar.classList.remove('w-64');
            sidebar.classList.add('w-20');
            
            // Sembunyikan semua elemen teks deskripsi menu
            const hideElements = sidebar.querySelectorAll('.sidebar-hide');
            hideElements.forEach(el => el.classList.add('hidden'));
            
            // Pusatkan seluruh ikon menu navigasi utama
            const navLinks = sidebar.querySelectorAll('.flex-grow a');
            navLinks.forEach(link => {
                link.classList.remove('px-3.5');
                link.classList.add('justify-center');
            });
            
            // Pusatkan logo air dan hilangkan space kanan pada header
            if (brandHeader) {
                brandHeader.classList.remove('px-5', 'justify-between');
                brandHeader.classList.add('justify-center');
            }
        } else {
            document.documentElement.classList.remove('sidebar-is-collapsed');
            sidebar.classList.remove('w-20');
            sidebar.classList.add('w-64');
            
            // Kembalikan teks deskripsi menu navigasi
            const hideElements = sidebar.querySelectorAll('.sidebar-hide');
            hideElements.forEach(el => el.classList.remove('hidden'));
            
            // Kembalikan padding normal menu navigasi
            const navLinks = sidebar.querySelectorAll('.flex-grow a');
            navLinks.forEach(link => {
                link.classList.add('px-3.5');
                link.classList.remove('justify-center');
            });
            
            // Kembalikan padding default header logo
            if (brandHeader) {
                brandHeader.classList.add('px-5', 'justify-between');
                brandHeader.classList.remove('justify-center');
            }
        }
    }

    // 1. Cek memory local storage saat halaman pertama kali di-refresh
    const isCollapsed = localStorage.getItem('sidebar_collapsed') === 'true';
    if (window.innerWidth >= 768) {
        applyCollapsedState(isCollapsed);
    }

    // 2. Jalankan fungsi buka-tutup saat tombol hamburger diklik
    btnToggle.addEventListener('click', function(e) {
        e.stopPropagation(); // Mencegah bentrokan event
        if (window.innerWidth < 768) {
            sidebar.classList.toggle('-translate-x-full');
            sidebar.classList.toggle('translate-x-0');
        } else {
            const collapsing = sidebar.classList.contains('w-64');
            localStorage.setItem('sidebar_collapsed', collapsing.toString());
            applyCollapsedState(collapsing);
        }
    });
});