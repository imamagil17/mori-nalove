<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@hasSection('title') @yield('title') | {{ config('app.name', 'Flood-Vision') }} @else {{ config('app.name', 'Flood-Vision') }} | Sistem Mitigasi Banjir Cerdas @endif</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="font-[Figtree] text-slate-800 antialiased bg-slate-50 min-h-screen flex flex-col justify-start sm:justify-center items-center py-12 relative overflow-y-auto">
        
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-blue-400/20 rounded-full blur-[100px] z-0 pointer-events-none"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-indigo-300/20 rounded-full blur-[100px] z-0 pointer-events-none"></div>

        <div class="relative z-10 w-full sm:max-w-md my-auto px-8 py-10 bg-white/70 backdrop-blur-xl border border-white shadow-2xl rounded-3xl">
            <div class="flex justify-center mb-8">
                <a href="/" class="flex flex-col items-center gap-3 group">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 12c2.667 0 5.333-2 8-2s5.333 2 8 2 5.333-2 8-2M2 18c2.667 0 5.333-2 8-2s5.333 2 8 2 5.333-2 8-2M2 6c2.667 0 5.333-2 8-2s5.333 2 8 2 5.333-2 8-2"></path>
                        </svg>
                    </div>
                    <span class="font-extrabold text-xl text-slate-800 tracking-tight">Flood Vision</span>
                </a>
            </div>

            {{ $slot }}
            
        </div>
    </body>
</html>