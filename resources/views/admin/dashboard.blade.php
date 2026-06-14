<x-app-layout>
    @section('title', 'Dashboard Admin')

    <div class="py-8 relative min-h-screen w-full transition-all duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-20">

            <div
                class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 bg-white/40 backdrop-blur-md border border-white/50 rounded-3xl p-6 shadow-sm transition-all duration-300 hover:bg-white/60 hover:shadow-md">
                <div>
                    <h1 class="text-2xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                        <i data-lucide="layout-dashboard" class="w-6 h-6 text-blue-600"></i> Command Center
                    </h1>
                    <p class="text-sm text-slate-500 font-medium mt-1">Monitoring deteksi ketinggian air sungai via Mori Nalove AI</p>
                </div>
                <a href="{{ route('admin.kelola_video.index') }}"
                    class="group relative inline-flex items-center justify-center gap-3 px-6 py-3.5 overflow-hidden font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-[0_8px_20px_rgba(79,70,229,0.25)] hover:shadow-[0_8px_25px_rgba(79,70,229,0.4)] transition-all duration-300 hover:-translate-y-1">
                    <span class="absolute w-0 h-0 transition-all duration-500 ease-out bg-white rounded-full group-hover:w-64 group-hover:h-56 opacity-10"></span>
                    <i data-lucide="cpu" class="w-5 h-5 animate-pulse"></i>
                    <span class="relative tracking-wide uppercase text-xs">Kelola Video YOLOv26</span>
                    <i data-lucide="arrow-right" class="w-4 h-4 relative group-hover:translate-x-1.5 transition-transform duration-300"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white/40 backdrop-blur-md border border-white/50 rounded-3xl p-6 shadow-sm transition-all duration-300 hover:bg-white/60 hover:shadow-md flex items-center justify-between group">
                    <div>
                        <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Total Laporan Warga</span>
                        <h3 class="text-3xl font-black tracking-tight text-slate-800 mt-2">{{ $totalReports ?? 0 }}</h3>
                    </div>
                    <div class="p-3 bg-indigo-500/10 text-indigo-600 rounded-2xl group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-bullhorn text-lg"></i>
                    </div>
                </div>

                <div class="bg-white/40 backdrop-blur-md border border-white/50 rounded-3xl p-6 shadow-sm transition-all duration-300 hover:bg-white/60 hover:shadow-md flex items-center justify-between group">
                    <div>
                        <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Total Berita</span>
                        <h3 class="text-3xl font-black tracking-tight text-slate-800 mt-2">{{ $totalBerita ?? 0 }}</h3>
                    </div>
                    <div class="p-3 bg-emerald-500/10 text-emerald-600 rounded-2xl group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-newspaper text-lg"></i>
                    </div>
                </div>

                <div class="bg-white/40 backdrop-blur-md border border-white/50 rounded-3xl p-6 shadow-sm transition-all duration-300 hover:bg-white/60 hover:shadow-md flex items-center justify-between group">
                    <div>
                        <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Video Teranalisis</span>
                        <h3 class="text-3xl font-black tracking-tight text-slate-800 mt-2">{{ $totalVideos ?? 0 }}</h3>
                    </div>
                    <div class="p-3 bg-blue-500/10 text-blue-600 rounded-2xl group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-video text-lg"></i>
                    </div>
                </div>

                <div class="bg-white/40 backdrop-blur-md border border-white/50 rounded-3xl p-6 shadow-sm transition-all duration-300 hover:bg-white/60 hover:shadow-md flex items-center justify-between group">
                    <div>
                        <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Peringatan Darurat</span>
                        <h3 class="text-3xl font-black tracking-tight text-slate-800 mt-2">{{ $totalAlerts ?? 0 }}</h3>
                    </div>
                    <div class="p-3 bg-rose-500/10 text-rose-600 rounded-2xl group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-bell text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <div class="w-full">
                    @include('admin.partials.water-chart')
                </div>

                <div class="w-full">
                    @include('admin.partials.recent-reports')
                </div>
            </div>

            <div class="mt-8">
                @include('admin.partials.news-slider')
            </div>
        </div>
    </div>

    @include('admin.partials.news-modal')
    @include('admin.partials.chatbot-widget')
    @include('admin.partials.camera-modal')

    <style>
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>

    {{-- Pemanggilan Library Inti Terpusat --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@2.0.1/dist/chartjs-plugin-annotation.min.js"></script>

    <script>
        // Logika fungsi utilitas global non-grafik tetap dipertahankan
        window.onOpenCvReady = function() {
            console.log('OpenCV.js is ready.');
        };
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>

    {{-- Skrip Komponen Aplikasi Utama --}}
    <script src="{{ asset('js/classification.js') }}"></script>
    <script src="{{ asset('js/camera.js') }}"></script>
    <script async src="https://docs.opencv.org/4.8.0/opencv.js" onload="onOpenCvReady();"></script>

    {{-- File Utama yang Sekarang Mengontrol Penuh Gambar dan Data Grafik --}}
    <script src="{{ asset('js/water-chart.js') }}"></script>
    <script src="{{ asset('js/dashboard-api.js') }}"></script>

    <script src="{{ asset('js/chatbot.js') }}"></script>
    <script src="{{ asset('js/news-modal.js') }}"></script>
    <script src="{{ asset('js/citizen-reports.js') }}"></script>
</x-app-layout>