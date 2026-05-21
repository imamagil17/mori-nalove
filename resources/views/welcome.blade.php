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
            @include('welcome.partials.live-demo')
            @include('welcome.partials.features-section')
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