@if(auth()->check() && auth()->user()->role === 'admin')
    <div class="w-full h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 md:hidden fixed top-0 left-0 z-40 shadow-sm">
        <div class="flex items-center gap-2">
            <img src="{{ asset('img/logo-mori-nalove.png') }}" alt="Mori Nalove Logo" class="h-7 w-auto object-contain">
        </div>
        <button onclick="toggleMobileSidebar()" class="p-2 text-slate-600 hover:bg-slate-100 active:bg-slate-200 rounded-xl border border-slate-200/60 shadow-inner transition-colors">
            <i class="fa-solid fa-bars text-base"></i>
        </button>
    </div>

    <aside id="sidebarAdmin" class="-translate-x-full fixed md:translate-x-0 md:relative top-0 left-0 z-50 w-64 h-screen bg-white text-slate-700 border-r border-slate-200 transition-all duration-300 flex flex-col shrink-0 shadow-xl md:shadow-sm">
        
        <div class="h-16 flex items-center justify-between md:justify-center px-4 border-b border-slate-100 shrink-0 w-full">
            <img src="{{ asset('img/logo-mori-nalove.png') }}" alt="Mori Nalove Logo" class="h-9 w-auto object-contain shrink-0 md:mx-auto">
            
            <button onclick="toggleMobileSidebar()" class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg md:hidden">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="flex-grow py-6 px-4 space-y-1.5 overflow-y-auto hide-scrollbar">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl font-bold text-sm transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/10' : 'hover:bg-slate-50 hover:text-blue-600 text-slate-500' }}">
                <i class="fa-solid fa-house text-lg w-5 text-center shrink-0"></i>
                <span class="sidebar-hide truncate transition-opacity duration-300">Dashboard</span>
            </a>

            <a href="{{ route('admin.notifications.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl font-bold text-sm transition-all duration-200 {{ request()->routeIs('admin.notifications.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/10' : 'hover:bg-slate-50 hover:text-blue-600 text-slate-500' }}">
                <i class="fa-solid fa-bell text-lg w-5 text-center shrink-0"></i>
                <span class="sidebar-hide truncate transition-opacity duration-300">Log Notifikasi</span>
            </a>
            
            <a href="{{ route('admin.berita.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl font-bold text-sm transition-all duration-200 {{ request()->routeIs('admin.berita.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/10' : 'hover:bg-slate-50 hover:text-blue-600 text-slate-500' }}">
                <i class="fa-solid fa-newspaper text-lg w-5 text-center shrink-0"></i>
                <span class="sidebar-hide truncate transition-opacity duration-300">Kelola Berita</span>
            </a>
            
            <a href="{{ route('admin.citizen_reports.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl font-bold text-sm transition-all duration-200 {{ request()->routeIs('admin.citizen_reports.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/10' : 'hover:bg-slate-50 hover:text-blue-600 text-slate-500' }}">
                <i class="fa-solid fa-bullhorn text-lg w-5 text-center shrink-0"></i>
                <span class="sidebar-hide truncate transition-opacity duration-300">Laporan Warga</span>
            </a>
            
            <a href="{{ route('admin.kelola_video.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl font-bold text-sm transition-all duration-200 {{ request()->routeIs('admin.kelola_video.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/10' : 'hover:bg-slate-50 hover:text-blue-600 text-slate-500' }}">
                <i class="fa-solid fa-video text-lg w-5 text-center shrink-0"></i>
                <span class="sidebar-hide truncate transition-opacity duration-300">Kelola Video</span>
            </a>
        </div>

        <div class="p-4 pt-0 mt-auto shrink-0">
            <div x-data="{ open: false }" class="relative">
                <div x-show="open" @click.away="open = false" 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 translate-y-2 scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                     x-transition:leave="transition ease-in duration-77" 
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                     x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                     class="absolute bottom-full left-0 right-0 mb-3 bg-white border border-slate-200 rounded-2xl p-1.5 shadow-2xl z-50"
                     style="display: none;">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs hover:bg-slate-50 text-slate-700 hover:text-blue-600 transition-all duration-200">
                        <i data-lucide="user" class="w-4 h-4 shrink-0 text-slate-400"></i>
                        <span>Profil Saya</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs hover:bg-red-50 text-red-600 hover:text-red-700 transition-all duration-200 text-left">
                            <i data-lucide="log-out" class="w-4 h-4 shrink-0 text-red-400"></i>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>

                <button @click="open = !open" 
                        class="w-full flex items-center gap-3 p-3.5 rounded-2xl bg-white border border-slate-200 hover:bg-slate-50 transition-all duration-200 focus:outline-none text-left justify-center shadow-md hover:shadow-lg">
                    <div class="w-9 h-9 bg-blue-600 text-white rounded-xl flex items-center justify-center text-sm font-black shrink-0 shadow-md mx-auto">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="min-w-0 flex-grow menu-label sidebar-hide transition-opacity duration-300 hidden md:flex items-center justify-between">
                        <div class="min-w-0 pr-2">
                            <p class="text-xs font-black text-slate-800 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] font-bold text-slate-400 truncate uppercase tracking-wider">Administrator</p>
                        </div>
                        <i data-lucide="chevron-up" class="w-4 h-4 text-slate-400 shrink-0"></i>
                    </div>
                </button>
            </div>
        </div>
    </aside>

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebarAdmin');
            if (sidebar) {
                sidebar.classList.toggle('-translate-x-full');
            }
        }
    </script>

    <style>
        @media (max-w: 767px) {
            /* Jalankan rule ini hanya ketika dibuka dari layar HP */
            body, .py-8, .min-h-screen {
                padding-top: 4rem !important; /* Memberi ruang 64px agar konten Command Center turun di bawah Navbar HP */
            }
        }
    </style>
@endif