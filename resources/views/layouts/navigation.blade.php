@if(auth()->check() && auth()->user()->role === 'admin')
    <!-- SIDEBAR FOR ADMINS (COLLAPSIBLE & RESPONSIVE) -->
    <aside id="sidebarAdmin" class="-translate-x-full fixed z-50 md:translate-x-0 md:relative w-64 min-h-screen bg-white text-slate-700 border-r border-slate-200 transition-all duration-300 flex flex-col shrink-0 shadow-sm">
        <!-- Sidebar Header / Logo -->
        <div id="btnToggleSidebar" class="h-16 flex items-center px-5 border-b border-slate-100 shrink-0 gap-3 cursor-pointer hover:bg-slate-50 transition-colors">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white shadow-md shrink-0">
                <i class="fa-solid fa-water text-lg"></i>
            </div>
            <span class="font-extrabold text-lg bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 tracking-tight sidebar-hide truncate transition-opacity duration-300">Flood Vision</span>
        </div>

        <!-- Navigation Links -->
        <div class="flex-grow py-6 px-4 space-y-1.5 overflow-y-auto hide-scrollbar">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl font-bold text-sm transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/10' : 'hover:bg-slate-50 hover:text-blue-600 text-slate-500' }}">
                <i class="fa-solid fa-house text-lg w-5 text-center shrink-0"></i>
                <span class="sidebar-hide truncate transition-opacity duration-300">Dashboard</span>
            </a>
            <a href="{{ route('admin.water_logs.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl font-bold text-sm transition-all duration-200 {{ request()->routeIs('admin.water_logs.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/10' : 'hover:bg-slate-50 hover:text-blue-600 text-slate-500' }}">
                <i class="fa-solid fa-chart-column text-lg w-5 text-center shrink-0"></i>
                <span class="sidebar-hide truncate transition-opacity duration-300">Riwayat Air</span>
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

        <!-- Premium Profile Floating White Solid Card at the bottom (Exactly like Gambar 2) -->
        <div class="p-4 pt-0 mt-auto shrink-0">
            <div x-data="{ open: false }" class="relative">
                <!-- Dropdown Upward Options -->
                <div x-show="open" @click.away="open = false" 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 translate-y-2 scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                     x-transition:leave="transition ease-in duration-75" 
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

                <!-- Admin Profile Card Trigger Button (Floating White Solid Card) -->
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
@else
    <!-- ORIGINAL USER TOP BAR NAVIGATION -->
    <nav x-data="{ open: false }" class="bg-white/70 backdrop-blur-md border-b border-slate-200/60 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="shrink-0 flex items-center">
                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="flex items-center gap-2">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white shadow-md">
                                <i data-lucide="waves" class="w-6 h-6"></i>
                            </div>
                            <span class="font-extrabold text-xl bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 tracking-tight">Flood Vision</span>
                        </a>
                    </div>

                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('admin.dashboard')" class="text-slate-600 font-semibold hover:text-blue-600 transition-colors">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        
                        @if(auth()->user()->role === 'admin')
                            <x-nav-link :href="route('admin.water_logs.index')" :active="request()->routeIs('admin.water_logs.*')" class="text-slate-600 font-semibold hover:text-blue-600 transition-colors">
                                {{ __('Riwayat Air') }}
                            </x-nav-link>

                            <x-nav-link :href="route('admin.notifications.index')" :active="request()->routeIs('admin.notifications.*')" class="text-slate-600 font-semibold hover:text-blue-600 transition-colors">
                                {{ __('Log Notifikasi') }}
                            </x-nav-link>

                            <x-nav-link :href="route('admin.berita.index')" :active="request()->routeIs('admin.berita.*')" class="text-slate-600 font-semibold hover:text-blue-600 transition-colors">
                                {{ __('Kelola Berita') }}
                            </x-nav-link>

                            <x-nav-link :href="route('admin.citizen_reports.index')" :active="request()->routeIs('admin.citizen_reports.*')" class="text-slate-600 font-semibold hover:text-blue-600 transition-colors">
                                {{ __('Laporan Warga') }}
                            </x-nav-link>

                            <x-nav-link :href="route('admin.kelola_video.index')" :active="request()->routeIs('admin.kelola_video.*')" class="text-slate-600 font-semibold hover:text-blue-600 transition-colors">
                                {{ __('Kelola Video') }}
                            </x-nav-link>
                        @endif
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 border border-slate-200 text-sm font-semibold rounded-full text-slate-600 bg-white hover:bg-slate-50 hover:text-blue-600 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-bold">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <span>{{ Auth::user()->name }}</span>
                                </div>

                                <div class="ms-1">
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400"></i>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')" class="font-medium text-slate-700 hover:text-blue-600">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();" class="font-medium text-red-600 hover:bg-red-50 hover:text-red-700">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none focus:bg-slate-100 focus:text-slate-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                
                @if(auth()->user()->role === 'admin')
                    <x-responsive-nav-link :href="route('admin.water_logs.index')" :active="request()->routeIs('admin.water_logs.*')">
                        {{ __('Riwayat Air') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.notifications.index')" :active="request()->routeIs('admin.notifications.*')">
                        {{ __('Log Notifikasi') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.berita.index')" :active="request()->routeIs('admin.berita.*')">
                        {{ __('Kelola Berita') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.citizen_reports.index')" :active="request()->routeIs('admin.citizen_reports.*')">
                        {{ __('Laporan Warga') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.kelola_video.index')" :active="request()->routeIs('admin.kelola_video.*')">
                        {{ __('Kelola Video') }}
                    </x-responsive-nav-link>
                @endif
            </div>

            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </nav>
@endif