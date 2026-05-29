<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Flood-Vision') }} | Sistem Mitigasi Banjir Cerdas</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
        .reveal-on-scroll { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.5, 0, 0, 1); }
        .reveal-on-scroll.is-visible { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body class="antialiased bg-slate-50 text-slate-800 font-[Figtree] relative overflow-x-hidden">

    <div class="fixed top-[-10%] left-[-10%] w-[500px] h-[500px] bg-blue-400/20 rounded-full blur-[120px] z-0 pointer-events-none"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-indigo-300/20 rounded-full blur-[120px] z-0 pointer-events-none"></div>

    <div class="w-full relative z-10">
        @include('welcome.partials.navbar')

        <main class="w-full">
            @include('welcome.partials.hero-section')
            @include('welcome.partials.features-section')
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-16">
                <div class="bg-white/60 backdrop-blur-md border border-slate-200/80 rounded-3xl p-6 md:p-8 shadow-[0_4px_20px_rgba(0,0,0,0.02)]">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-5 space-y-4">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-600 border border-blue-100 text-xs font-bold uppercase tracking-wider">
                                Core Intelligence
                            </div>
                            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">
                                Analisis Presisi Berbasis <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">YOLOv26 Nano</span>
                            </h2>
                            <p class="text-sm text-slate-600 leading-relaxed font-medium">
                                Sistem ini mengintegrasikan model arsitektur *Neural Network* tercanggih untuk melakukan ekstraksi level ketinggian air secara *real-time*. Kecepatan tinggi komputasi *inference* memastikan data mitigasi tersaji instan tanpa latensi.
                            </p>
                        </div>
                        
                        <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="p-5 bg-white border border-slate-100 rounded-2xl shadow-sm hover:border-blue-300 transition-all group">
                                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-3 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                    <i data-lucide="layers" class="w-5 h-5"></i>
                                </div>
                                <h4 class="font-bold text-sm text-slate-800 mb-1">Feature Extraction</h4>
                                <p class="text-xs text-slate-500 leading-normal">Lapisan *Hidden Layers (Backbone)* mengekstrak geometri dan koordinat piksel pembatas rambu ukur air sungai secara presisi.</p>
                            </div>

                            <div class="p-5 bg-white border border-slate-100 rounded-2xl shadow-sm hover:border-indigo-300 transition-all group">
                                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-3 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                    <i data-lucide="zap" class="w-5 h-5"></i>
                                </div>
                                <h4 class="font-bold text-sm text-slate-800 mb-1">Ultra-Fast Inference</h4>
                                <p class="text-xs text-slate-500 leading-normal">Optimasi bobot (*weights*) berukuran ringan memastikan performa deteksi berjalan di bawah jangkauan waktu milidetik per-frame.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('welcome.partials.architecture-section')
            @include('welcome.partials.location-focus-section')
            @include('welcome.partials.cta-section')
            @include('welcome.partials.public-news-slider')
        </main>
    </div>

    @include('user.partials.news-modal')
    @include('welcome.partials.main-footer')

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
    <script src="{{ asset('js/news-modal.js') }}"></script>
    <script src="{{ asset('js/welcome-animations.js') }}"></script>
</body>
</html>