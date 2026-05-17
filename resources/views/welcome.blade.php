<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Flood-Vision') }} | Sistem Mitigasi Banjir Cerdas</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>
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
            <span class="font-extrabold text-xl tracking-tight text-slate-800">Flood Vision</span>
        </div>

        @if (Route::has('login'))
            <div class="flex items-center gap-5">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-blue-600 hover:text-blue-800 transition flex items-center gap-1">Ke Dashboard &rarr;</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition">Log in</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-sm font-semibold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white px-6 py-2.5 rounded-full shadow-lg shadow-blue-600/30 transition-all transform hover:-translate-y-0.5">Register</a>
                    @endif
                @endauth
            </div>
        @endif
    </nav>

    <main class="relative z-10 flex-grow flex flex-col justify-center items-center text-center px-4 max-w-5xl mx-auto mt-16 md:mt-24">
        
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 border border-blue-200 text-blue-700 text-xs font-bold uppercase tracking-wider mb-8 shadow-sm">
            <span class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-pulse"></span>
            Sistem Aktif & Memantau Lokasi
        </div>
        
        <h1 class="text-5xl md:text-7xl font-extrabold text-slate-800 mb-6 leading-tight tracking-tight">
            Pemantauan Banjir <br/> 
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Cerdas & Real-Time</span>
        </h1>
        
        <p class="text-lg md:text-xl text-slate-600 max-w-2xl mb-10 leading-relaxed">
            Sistem mitigasi dini terintegrasi <b>Computer Vision</b> dan <b>AI</b> sebagai wujud Sistem Mitigasi Banjir Cerdas. Pantau level air, cuaca, and prediksi risiko dalam satu platform modern.
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

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-12 pt-8 border-t border-slate-200/60 w-full max-w-3xl mb-24">
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
                <p class="text-sm font-medium text-slate-500 mt-1">Notifikasi Telegram</p>
            </div>
        </div>
    </main>

    <section id="fitur" class="relative z-10 max-w-7xl mx-auto px-4 w-full mb-24 scroll-mt-24">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-black text-slate-800">Teknologi di Balik Flood-Vision</h2>
            <p class="text-slate-500 mt-3 max-w-lg mx-auto">Integrasi arsitektur perangkat lunak modern untuk kecepatan pengiriman data informasi bencana.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white/80 backdrop-blur-xl border border-slate-200/50 shadow-sm rounded-3xl p-8 hover:-translate-y-2 transition-transform duration-300 group">
                <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mb-6 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                    <i data-lucide="scan-eye" class="w-7 h-7"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">Visual Detection (OpenCV)</h3>
                <p class="text-slate-600 text-sm leading-relaxed">Algoritma analisis tepi (*Canny Edge Detection*) mendeteksi pergerakan tinggi air sungai secara instan langsung dari tangkapan sensor kamera lapangan.</p>
            </div>

            <div class="bg-white/80 backdrop-blur-xl border border-slate-200/50 shadow-sm rounded-3xl p-8 hover:-translate-y-2 transition-transform duration-300 group">
                <div class="w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center mb-6 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                    <i data-lucide="brain" class="w-7 h-7"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">AI Prediction Engine</h3>
                <p class="text-slate-600 text-sm leading-relaxed">Asisten kecerdasan buatan terintegrasi yang menghitung skor risiko mitigasi serta memprediksi level kenaikan air untuk 30 menit ke depan.</p>
            </div>

            <div class="bg-white/80 backdrop-blur-xl border border-slate-200/50 shadow-sm rounded-3xl p-8 hover:-translate-y-2 transition-transform duration-300 group">
                <div class="w-14 h-14 bg-cyan-100 rounded-2xl flex items-center justify-center mb-6 text-cyan-600 group-hover:bg-cyan-600 group-hover:text-white transition-colors duration-300">
                    <i data-lucide="send" class="w-7 h-7"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">Otomatisasi Telegram</h3>
                <p class="text-slate-600 text-sm leading-relaxed">Menembakkan bot API Telegram secara instan ke grup warga ketika status kamera mendeteksi level bahaya **SIAGA** maupun **AWAS**.</p>
            </div>
        </div>
    </section>

    <section class="relative z-10 max-w-6xl mx-auto px-4 w-full mb-28">
        <div class="bg-white/60 backdrop-blur-md rounded-[2.5rem] p-8 md:p-12 border border-slate-200/60 shadow-sm">
            <div class="text-center mb-12">
                <span class="text-xs font-bold text-blue-600 uppercase tracking-widest bg-blue-50 px-3 py-1 rounded-full">Arsitektur Kerja</span>
                <h2 class="text-3xl font-black text-slate-800 mt-3">Bagaimana Sistem Bekerja?</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative">
                
                <div class="flex flex-col items-center text-center relative z-10 p-5 rounded-3xl border border-transparent hover:border-slate-200/80 hover:bg-white hover:shadow-xl hover:shadow-blue-500/5 hover:-translate-y-3 transition-all duration-300 group cursor-pointer">
                    <div class="w-16 h-16 bg-blue-600 text-white rounded-2xl flex items-center justify-center font-bold text-xl shadow-lg shadow-blue-500/20 mb-4 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">1</div>
                    <h4 class="font-bold text-slate-800 mb-1 text-base group-hover:text-blue-600 transition-colors">Capture Kamera</h4>
                    <p class="text-xs text-slate-500 max-w-[180px]">Kamera node menangkap tayangan air permukaan sungai secara real-time.</p>
                </div>

                <div class="flex flex-col items-center text-center relative z-10 p-5 rounded-3xl border border-transparent hover:border-slate-200/80 hover:bg-white hover:shadow-xl hover:shadow-indigo-500/5 hover:-translate-y-3 transition-all duration-300 group cursor-pointer">
                    <div class="w-16 h-16 bg-indigo-600 text-white rounded-2xl flex items-center justify-center font-bold text-xl shadow-lg shadow-indigo-500/20 mb-4 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">2</div>
                    <h4 class="font-bold text-slate-800 mb-1 text-base group-hover:text-indigo-600 transition-colors">Proses OpenCV</h4>
                    <p class="text-xs text-slate-500 max-w-[180px]">Matriks visual dihitung menggunakan canny edge untuk menentukan persentase level air.</p>
                </div>

                <div class="flex flex-col items-center text-center relative z-10 p-5 rounded-3xl border border-transparent hover:border-slate-200/80 hover:bg-white hover:shadow-xl hover:shadow-cyan-500/5 hover:-translate-y-3 transition-all duration-300 group cursor-pointer">
                    <div class="w-16 h-16 bg-cyan-600 text-white rounded-2xl flex items-center justify-center font-bold text-xl shadow-lg shadow-cyan-500/20 mb-4 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">3</div>
                    <h4 class="font-bold text-slate-800 mb-1 text-base group-hover:text-cyan-600 transition-colors">Analisis AI</h4>
                    <p class="text-xs text-slate-500 max-w-[180px]">Data dikombinasikan dengan API cuaca untuk memprediksi skor risiko banjir ke depan.</p>
                </div>

                <div class="flex flex-col items-center text-center relative z-10 p-5 rounded-3xl border border-transparent hover:border-slate-200/80 hover:bg-white hover:shadow-xl hover:shadow-emerald-500/5 hover:-translate-y-3 transition-all duration-300 group cursor-pointer">
                    <div class="w-16 h-16 bg-emerald-600 text-white rounded-2xl flex items-center justify-center font-bold text-xl shadow-lg shadow-emerald-500/20 mb-4 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">4</div>
                    <h4 class="font-bold text-slate-800 mb-1 text-base group-hover:text-emerald-600 transition-colors">Siaran Darurat</h4>
                    <p class="text-xs text-slate-500 max-w-[180px]">Bot otomatis menyiarkan perintah evakuasi dan pesan siaga ke grup Telegram warga.</p>
                </div>

            </div>
        </div>
    </section>

    <section class="relative z-10 max-w-7xl mx-auto px-4 w-full mb-28">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <div class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 uppercase tracking-wider bg-indigo-50 px-3 py-1 rounded-full">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Fokus Lokasi Pemantauan
                </div>
                <h2 class="text-3xl md:text-4xl font-black text-slate-800 leading-tight">
                    Sistem Mitigasi Banjir Cerdas <br>Untuk Mengamankan Bantaran Sungai
                </h2>
                <p class="text-slate-600 leading-relaxed text-sm md:text-base">
                    Sistem difokuskan untuk melakukan pemantauan intensif di titik rawan luapan air, terutama di sekitar area perumahan warga melalui penerapan Sistem Mitigasi Banjir Cerdas.
                </p>
                
                <div class="grid grid-cols-2 gap-4 pt-2">
                    <div class="p-4 bg-white border border-slate-100 rounded-2xl shadow-sm flex items-center gap-3 hover:-translate-y-1.5 hover:shadow-md hover:border-blue-100 transition-all duration-300 group cursor-pointer">
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-xl group-hover:bg-blue-600 group-hover:text-white group-hover:scale-105 transition-all duration-300">
                            <i data-lucide="lightning" class="w-5 h-5 hidden"></i> <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div>
                            <h5 class="font-bold text-slate-800 text-sm group-hover:text-blue-600 transition-colors">Respons Cepat</h5>
                            <p class="text-xs text-slate-400">Deteksi hitungan detik</p>
                        </div>
                    </div>
                    
                    <div class="p-4 bg-white border border-slate-100 rounded-2xl shadow-sm flex items-center gap-3 hover:-translate-y-1.5 hover:shadow-md hover:border-emerald-100 transition-all duration-300 group cursor-pointer">
                        <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl group-hover:bg-emerald-600 group-hover:text-white group-hover:scale-105 transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <h5 class="font-bold text-slate-800 text-sm group-hover:text-emerald-600 transition-colors">Akses Publik</h5>
                            <p class="text-xs text-slate-400">Dapat dipantau semua warga</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="relative">
                <div class="absolute inset-0 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-[2.5rem] transform rotate-2 opacity-5 scale-105"></div>
                <div class="bg-slate-900 rounded-[2.5rem] shadow-xl border border-slate-200/80 overflow-hidden relative aspect-video">
                    <video src="{{ asset('videos/arus.mp4') }}" class="w-full h-full object-cover" autoplay loop playsinline muted></video>
                </div>
            </div>

        </div>
    </section>

    <section class="relative z-10 max-w-5xl mx-auto px-4 w-full mb-20">
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[2.5rem] p-8 md:p-12 text-center text-white shadow-xl shadow-blue-500/20 relative overflow-hidden group">
            <div class="absolute -right-10 -bottom-10 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14 gang-0.18-.357.223-.548.223l.188-2.85 5.18-4.686c.223-.204-.054-.31-.346-.116l-6.405 4.026-2.76-.86c-.6-.188-.614-.6.125-.89l10.793-4.16c.5-.184.945.116.808.887z"/></svg>
            </div>
            <div class="relative z-10 max-w-2xl mx-auto space-y-6">
                <h2 class="text-3xl md:text-4xl font-black tracking-tight">Siap Melindungi Diri dan Keluarga?</h2>
                <p class="text-blue-100 text-sm md:text-base leading-relaxed">
                    Masuk ke sistem dashboard warga untuk memantau pergerakan grafik air sungai, mendapatkan prediksi analitik AI terbaru, dan melihat mading digital laporan kebencanaan secara langsung.
                </p>
                <div class="pt-4">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-white text-blue-600 px-8 py-3.5 rounded-full font-bold text-sm hover:bg-blue-50 transition-colors shadow-md">
                        Masuk ke Dashboard Warga
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- NEWS SECTION START -->
    <section class="relative z-10 max-w-7xl mx-auto px-4 w-full mb-20" id="berita">
        <div class="mt-8 bg-white/40 backdrop-blur-md rounded-3xl p-6 md:p-8 shadow-sm border border-slate-200/60 relative z-10">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-indigo-100/80 text-indigo-600 rounded-xl backdrop-blur-sm shadow-sm">
                        <i data-lucide="newspaper" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight">Mading Berita Publik</h3>
                        <p class="text-sm text-slate-500 mt-1">Informasi mitigasi terbaru dari Sistem Mitigasi Banjir Cerdas</p>
                    </div>
                </div>
            </div>

            <div class="flex overflow-x-auto gap-6 pb-6 snap-x snap-mandatory hide-scrollbar" style="scrollbar-width: none; -ms-overflow-style: none;">
                
                @forelse($beritas ?? [] as $berita)
                <div class="min-w-[300px] max-w-[300px] md:min-w-[350px] md:max-w-[350px] snap-start bg-white/60 backdrop-blur-md rounded-3xl overflow-hidden shadow-sm border border-white/50 flex flex-col group cursor-pointer hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="relative h-48 md:h-56 overflow-hidden bg-slate-200/50 flex shrink-0">
                        @if($berita->foto)
                            <img src="{{ asset('storage/' . $berita->foto) }}" alt="Foto Berita" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                <i data-lucide="image" class="w-12 h-12 opacity-50"></i>
                            </div>
                        @endif
                        
                        <div class="absolute top-4 left-4 bg-slate-900/70 text-white text-[11px] md:text-xs font-bold px-3.5 py-1.5 rounded-full shadow-sm backdrop-blur-md">
                            {{ $berita->created_at->format('d M Y') }}
                        </div>
                    </div>
                    <div class="p-6 flex flex-col flex-grow justify-between relative z-10">
                        <h4 class="font-bold text-slate-800 text-[16px] md:text-lg leading-snug line-clamp-3 mb-4 group-hover:text-blue-600 transition-colors">
                            {{ $berita->judul }}
                        </h4>
                        
                        <div id="konten-berita-{{ $berita->id }}" class="hidden">{{ $berita->konten }}</div>
                        <button onclick="bukaModalBerita('{{ $berita->id }}', '{{ addslashes($berita->judul) }}', '{{ $berita->created_at->format('d M Y') }}', '{{ $berita->foto ? asset('storage/'.$berita->foto) : '' }}')" class="text-amber-500 font-bold text-sm flex items-center justify-end gap-1.5 hover:text-amber-600 transition-colors w-full text-right mt-2 focus:outline-none">
                            Selengkapnya <i data-lucide="arrow-right-circle" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </div>
                @empty
                <div class="w-full bg-white/40 backdrop-blur-md border border-dashed border-slate-300 rounded-3xl p-10 text-center flex flex-col items-center justify-center">
                    <i data-lucide="newspaper" class="w-12 h-12 text-slate-300 mb-4"></i>
                    <p class="text-slate-500 font-medium text-base">Belum ada berita dirilis oleh Admin Sistem Mitigasi Banjir Cerdas.</p>
                </div>
                @endforelse

            </div>
        </div>
    </section>
    <!-- NEWS SECTION END -->

    <!-- MODAL BERITA START -->
    <div id="modalBerita" class="fixed inset-0 z-[100] hidden bg-slate-900/80 backdrop-blur-md flex-col items-center justify-center p-4 md:p-8 transition-opacity duration-300 opacity-0">
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl w-full max-w-4xl overflow-hidden shadow-2xl flex flex-col max-h-[90vh] md:max-h-[85vh] transform scale-95 transition-transform duration-300 border border-white/20" id="modalBeritaContent">
            
            <div class="flex justify-between items-center p-5 border-b border-slate-200/50 bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <span class="bg-blue-100/80 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-full flex items-center gap-1.5 backdrop-blur-sm">
                        <i data-lucide="calendar" class="w-4 h-4"></i> <span id="modalBeritaTanggal"></span>
                    </span>
                    <span class="text-slate-400 text-sm font-medium hidden md:inline-block">— Sistem Mitigasi Banjir Cerdas</span>
                </div>
                <button onclick="tutupModalBerita()" class="p-2 bg-slate-200/80 hover:bg-red-100 hover:text-red-600 text-slate-500 rounded-full transition-colors focus:outline-none backdrop-blur-sm">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6 md:p-10 custom-scrollbar bg-transparent">
                <img id="modalBeritaFoto" src="" class="w-full h-64 md:h-96 object-cover rounded-2xl mb-8 shadow-md hidden" alt="Foto Berita">
                
                <h2 id="modalBeritaJudul" class="text-3xl md:text-4xl font-black text-slate-800 mb-8 leading-tight"></h2>
                
                <div id="modalBeritaDeskripsi" class="text-slate-700 text-[16px] md:text-[17px] leading-loose whitespace-pre-wrap font-normal text-justify"></div>
            </div>
        </div>
    </div>
    <!-- MODAL BERITA END -->


    <footer class="relative z-10 border-t border-slate-200 bg-white/50 mt-auto py-8 text-center text-slate-500 text-sm">
        <p>&copy; 2026 Flood-Vision System. Dikembangkan sebagai Sistem Mitigasi Banjir Cerdas.</p>
    </footer>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();

        function bukaModalBerita(id, judul, tanggal, fotoUrl) {
            document.getElementById('modalBeritaJudul').innerText = judul;
            document.getElementById('modalBeritaTanggal').innerText = tanggal;
            
            const kontenText = document.getElementById('konten-berita-' + id).innerText;
            document.getElementById('modalBeritaDeskripsi').innerText = kontenText;

            const fotoEl = document.getElementById('modalBeritaFoto');
            if(fotoUrl) {
                fotoEl.src = fotoUrl;
                fotoEl.classList.remove('hidden');
            } else {
                fotoEl.classList.add('hidden');
            }

            const modal = document.getElementById('modalBerita');
            const content = document.getElementById('modalBeritaContent');
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
            }, 10);
            lucide.createIcons();
        }

        function tutupModalBerita() {
            const modal = document.getElementById('modalBerita');
            const content = document.getElementById('modalBeritaContent');
            
            modal.classList.add('opacity-0');
            content.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }
    </script>
</body>
</html>