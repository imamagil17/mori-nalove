<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Flood-Vision | Sistem Mitigasi Banjir Cerdas</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased bg-slate-50 text-slate-800 min-h-screen flex flex-col font-[Figtree] relative overflow-x-hidden">

    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-400/20 rounded-full blur-[120px] z-0 pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-indigo-300/20 rounded-full blur-[120px] z-0 pointer-events-none"></div>

    <nav class="relative z-50 px-6 py-6 max-w-7xl mx-auto w-full flex justify-between items-center">
        <div class="flex items-center gap-3 group cursor-pointer">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 12c2.667 0 5.333-2 8-2s5.333 2 8 2 5.333-2 8-2M2 18c2.667 0 5.333-2 8-2s5.333 2 8 2 5.333-2 8-2M2 6c2.667 0 5.333-2 8-2s5.333 2 8 2 5.333-2 8-2"></path>
                </svg>
            </div>
            <span class="font-extrabold text-2xl tracking-tight text-slate-800">Flood Vision</span>
        </div>

        @if (Route::has('login'))
            <div class="flex items-center gap-5">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-blue-600 hover:text-blue-800 transition">Ke Dashboard &rarr;</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition">Log in</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-sm font-semibold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white px-6 py-2.5 rounded-full shadow-lg shadow-blue-600/30 transition-all transform hover:-translate-y-0.5">Register</a>
                    @endif
                @endauth
            </div>
        @endif
    </nav>

    <main class="relative z-10 flex-grow flex flex-col justify-center items-center text-center px-4 max-w-5xl mx-auto mt-12 md:mt-0">
        
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 border border-blue-200 text-blue-700 text-xs font-bold uppercase tracking-wider mb-8 shadow-sm">
            <span class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-pulse"></span>
            Sistem Aktif & Memantau Palu
        </div>
        
        <h1 class="text-5xl md:text-7xl font-extrabold text-slate-800 mb-6 leading-tight tracking-tight">
            Pemantauan Banjir <br/> 
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Cerdas & Real-Time</span>
        </h1>
        
        <p class="text-lg md:text-xl text-slate-600 max-w-2xl mb-10 leading-relaxed">
            Sistem mitigasi dini terintegrasi <b>Computer Vision</b> dan <b>AI</b> untuk mengamankan wilayah Kota Palu. Pantau level air, cuaca, dan prediksi risiko dalam satu platform modern.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 mb-16">
            <a href="{{ route('login') }}" class="group relative inline-flex items-center justify-center px-8 py-4 font-bold text-white transition-all duration-200 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full hover:from-blue-500 hover:to-indigo-500 shadow-lg shadow-blue-500/30 transform hover:-translate-y-1">
                Mulai Pantau Sekarang
                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
            <a href="#fitur" class="inline-flex items-center justify-center px-8 py-4 font-bold text-slate-700 bg-white border border-slate-200 rounded-full hover:bg-slate-50 transition-colors shadow-sm">
                Pelajari Fitur
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-12 pt-8 border-t border-slate-200/60 w-full max-w-3xl">
            <div>
                <h4 class="text-3xl font-extrabold text-blue-600">24/7</h4>
                <p class="text-sm font-medium text-slate-500 mt-1">Monitoring Aktif</p>
            </div>
            <div>
                <h4 class="text-3xl font-extrabold text-indigo-600">AI</h4>
                <p class="text-sm font-medium text-slate-500 mt-1">Powered Analytics</p>
            </div>
            <div>
                <h4 class="text-3xl font-extrabold text-cyan-600">OpenCV</h4>
                <p class="text-sm font-medium text-slate-500 mt-1">Visual Processing</p>
            </div>
            <div>
                <h4 class="text-3xl font-extrabold text-emerald-500">Real-time</h4>
                <p class="text-sm font-medium text-slate-500 mt-1">Sinkronisasi Cuaca</p>
            </div>
        </div>
    </main>

    <section id="fitur" class="relative z-10 max-w-7xl mx-auto px-4 w-full mb-20 mt-24 scroll-mt-24">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-slate-800">Teknologi di Balik Flood-Vision</h2>
            <p class="text-slate-500 mt-3">Integrasi multi-disiplin ilmu untuk mitigasi bencana yang lebih baik.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white/80 backdrop-blur-xl border border-slate-100 shadow-xl shadow-slate-200/50 rounded-3xl p-8 hover:-translate-y-2 transition-transform duration-300 group">
                <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mb-6 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">Visual Detection</h3>
                <p class="text-slate-600 text-sm leading-relaxed">Pemindaian debit air dan tinggi muka air sungai secara otomatis menggunakan teknologi Computer Vision.</p>
            </div>

            <div class="bg-white/80 backdrop-blur-xl border border-slate-100 shadow-xl shadow-slate-200/50 rounded-3xl p-8 hover:-translate-y-2 transition-transform duration-300 group">
                <div class="w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center mb-6 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">AI Analytics</h3>
                <p class="text-slate-600 text-sm leading-relaxed">Asisten mitigasi cerdas yang memproses data untuk memberikan skor risiko dan peringatan dini secara akurat.</p>
            </div>

            <div class="bg-white/80 backdrop-blur-xl border border-slate-100 shadow-xl shadow-slate-200/50 rounded-3xl p-8 hover:-translate-y-2 transition-transform duration-300 group">
                <div class="w-14 h-14 bg-cyan-100 rounded-2xl flex items-center justify-center mb-6 text-cyan-600 group-hover:bg-cyan-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">Live Weather Sync</h3>
                <p class="text-slate-600 text-sm leading-relaxed">Integrasi data cuaca Kota Palu secara real-time untuk memprediksi potensi genangan akibat curah hujan.</p>
            </div>
        </div>
    </section>

    <footer class="relative z-10 border-t border-slate-200 bg-white/50 mt-auto py-8 text-center text-slate-500 text-sm">
        <p>&copy; 2026 Flood-Vision System. Dikembangkan untuk mitigasi cerdas Kota Palu.</p>
    </footer>

</body>
</html>