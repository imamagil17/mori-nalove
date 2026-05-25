document.addEventListener('DOMContentLoaded', function() {
    const btnToggle = document.getElementById('btnToggleSidebar');
    const sidebar = document.getElementById('sidebarAdmin');
    
    if (!btnToggle || !sidebar) return;

    function applyCollapsedState(isCollapsed) {
        if (isCollapsed) {
            document.documentElement.classList.add('sidebar-is-collapsed');
            // Ubah lebar sidebar
            sidebar.classList.remove('w-64');
            sidebar.classList.add('w-20');
            
            // Sembunyikan elemen teks/ilustrasi
            const hideElements = sidebar.querySelectorAll('.sidebar-hide');
            hideElements.forEach(el => {
                el.classList.add('hidden');
            });
            
            // Sesuaikan padding/margin untuk menyelaraskan icon menu di tengah
            const navLinks = sidebar.querySelectorAll('.flex-grow a');
            navLinks.forEach(link => {
                link.classList.remove('px-3.5');
                link.classList.add('justify-center');
            });
            
            // Pusatkan Logo Header
            btnToggle.classList.remove('px-5');
            btnToggle.classList.add('justify-center');
        } else {
            document.documentElement.classList.remove('sidebar-is-collapsed');
            // Kembalikan lebar sidebar
            sidebar.classList.remove('w-20');
            sidebar.classList.add('w-64');
            
            // Tampilkan elemen teks/ilustrasi
            const hideElements = sidebar.querySelectorAll('.sidebar-hide');
            hideElements.forEach(el => {
                el.classList.remove('hidden');
            });
            
            // Kembalikan padding menu
            const navLinks = sidebar.querySelectorAll('.flex-grow a');
            navLinks.forEach(link => {
                link.classList.add('px-3.5');
                link.classList.remove('justify-center');
            });
            
            // Kembalikan padding Logo Header
            btnToggle.classList.add('px-5');
            btnToggle.classList.remove('justify-center');
        }
    }

    // 1. Cek status memory saat halaman pertama kali dimuat
    const isCollapsed = localStorage.getItem('sidebar_collapsed') === 'true';
    if (window.innerWidth >= 768) {
        applyCollapsedState(isCollapsed);
    }

    // 2. Jalankan fungsi toggle saat tombol diklik
    btnToggle.addEventListener('click', function() {
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
