<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@hasSection('title') @yield('title') | {{ config('app.name', 'Flood-Vision') }} @else {{ config('app.name', 'Flood-Vision') }} | Sistem Mitigasi Banjir Cerdas @endif</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            // Skrip instan untuk memblokir jeda melar (flickering layout)
            (function() {
                const isCollapsed = localStorage.getItem('sidebar_collapsed') === 'true';
                if (isCollapsed && window.innerWidth >= 768) {
                    document.documentElement.classList.add('sidebar-is-collapsed');
                }
            })();
        </script>
        <style>
            /* Instantly apply collapsed style if class is set in html root */
            html.sidebar-is-collapsed #sidebarAdmin {
                width: 5rem !important; /* w-20 */
            }
            html.sidebar-is-collapsed #sidebarAdmin .sidebar-hide,
            html.sidebar-is-collapsed #sidebarAdmin .menu-label {
                display: none !important;
            }
            html.sidebar-is-collapsed #sidebarAdmin .flex-grow a {
                padding-left: 0 !important;
                padding-right: 0 !important;
                justify-content: center !important;
            }
            html.sidebar-is-collapsed #btnToggleSidebar {
                padding-left: 0 !important;
                padding-right: 0 !important;
                justify-content: center !important;
            }
            html.sidebar-is-collapsed #sidebarAdmin .p-4.pt-0 {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }
            html.sidebar-is-collapsed #sidebarAdmin .p-4.pt-0 .relative > button {
                width: 2.75rem !important;
                height: 2.75rem !important;
                padding: 0 !important;
                justify-content: center !important;
                margin-left: auto !important;
                margin-right: auto !important;
                border-radius: 0.75rem !important;
            }
            html.sidebar-is-collapsed #sidebarAdmin .p-4.pt-0 .relative > button .w-9 {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
            html.sidebar-is-collapsed #sidebarAdmin .p-4.pt-0 .relative .absolute.bottom-full {
                bottom: 0 !important;
                left: 100% !important;
                right: auto !important;
                margin-left: 0.5rem !important;
                margin-bottom: 0 !important;
                width: 12rem !important;
                border-radius: 0.75rem !important;
                border-color: #f1f5f9 !important;
                box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1) !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased" style="font-family: 'Plus Jakarta Sans', sans-serif;">
        @if(auth()->check() && auth()->user()->role === 'admin')
            <!-- ADMIN LAYOUT (SIDE-BY-SIDE SIDEBAR) -->
            <div class="min-h-screen bg-slate-50 flex overflow-x-hidden">
                @include('layouts.navigation')

                <!-- RIGHT CONTENT AREA -->
                <div class="flex-grow flex flex-col min-w-0 min-h-screen">
                    <!-- HEADER TOPBAR -->
                    <header class="bg-slate-50 h-24 flex items-center justify-end px-8 shrink-0">
                        <div class="flex items-center gap-4">
                            <!-- Notification Bell with red badge 3 -->
                            <button class="relative p-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-full transition-colors focus:outline-none shadow-sm shrink-0">
                                <i data-lucide="bell" class="w-4 h-4"></i>
                                <span class="absolute -top-1 -right-1 w-4.5 h-4.5 bg-red-500 text-white text-[9px] font-black rounded-full flex items-center justify-center border border-white">3</span>
                            </button>
                            
                            <!-- Date & Time Widget -->
                            <div class="bg-white border border-slate-200 rounded-2xl px-4 py-2 flex items-center gap-3 shadow-sm text-xs text-slate-600 font-semibold h-11 shrink-0">
                                <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
                                <div class="leading-tight">
                                    <p class="font-bold text-slate-800" id="topbarDate">Sabtu, 17 Mei 2026</p>
                                    <p class="text-[10px] text-slate-400 font-medium" id="topbarTime">16:30 WITA</p>
                                </div>
                            </div>
                        </div>
                    </header>

                    <!-- Page Heading (Optional, e.g. for breadcrumbs) -->
                    @if (isset($header))
                        <div class="bg-white border-b border-slate-100 py-6">
                            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </div>
                    @endif

                    <!-- Page Content -->
                    <main class="flex-grow pb-12">
                        {{ $slot }}
                    </main>

                    <!-- Admin Footer -->
                    <footer class="border-t border-slate-200/60 bg-white/40 backdrop-blur-md py-6 text-center mt-auto shrink-0">
                        <p class="text-slate-500 text-xs font-semibold">&copy; 2026 Flood-Vision System &mdash; Panel Admin Cerdas.</p>
                    </footer>
                </div>
            </div>
        @else
            <!-- REGULAR USER LAYOUT (TOPBAR STACKED) -->
            <div class="min-h-screen bg-slate-50 flex flex-col">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main class="flex-grow pb-12">
                    {{ $slot }}
                </main>

                <!-- Global Footer -->
                <footer class="relative z-10 border-t border-slate-200/60 bg-white/40 backdrop-blur-md py-8 text-center mt-auto">
                    <p class="text-slate-500 text-sm font-medium">&copy; 2026 Flood-Vision System &mdash; Sistem Mitigasi Banjir Cerdas.</p>
                </footer>
            </div>
        @endif

        <!-- Sidebar Toggle Interactivity -->
        <script src="{{ asset('js/sidebar-toggle.js') }}"></script>
        
        <!-- Live Calendar Time Updater -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                function updateTopbarTime() {
                    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    
                    const now = new Date();
                    const dayName = days[now.getDay()];
                    const dateNum = now.getDate();
                    const monthName = months[now.getMonth()];
                    const year = now.getFullYear();
                    
                    const hours = now.getHours().toString().padStart(2, '0');
                    const minutes = now.getMinutes().toString().padStart(2, '0');
                    
                    const dateEl = document.getElementById('topbarDate');
                    const timeEl = document.getElementById('topbarTime');
                    
                    if (dateEl) dateEl.textContent = `${dayName}, ${dateNum} ${monthName} ${year}`;
                    if (timeEl) timeEl.textContent = `${hours}:${minutes} WITA`;
                }
                
                updateTopbarTime();
                setInterval(updateTopbarTime, 30000); // Update every 30 seconds
            });
        </script>
    </body>
</html>
