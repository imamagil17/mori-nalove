<x-app-layout>
    @section('title')
    <!-- News Slider Publik di Posisi Paling Atas (Full-Width) -->
    @include('user.partials.news-slider')
    
    <div class="py-8 relative min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-20">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 items-stretch mb-8">
                
                <!-- Kolom Kiri: Panduan & Telegram Alert -->
                <div class="xl:col-span-1 flex flex-col space-y-8">
                    @include('user.partials.safety-guide')
                    @include('user.partials.telegram-alert')
                </div>

                <!-- Kolom Kanan: Grafik Tren Air (Dibuat membesar penuh tanpa space kosong) -->
                <div class="xl:col-span-2 flex flex-col">
                    <div class="w-full h-full flex flex-col flex-grow">
                        @include('user.partials.water-chart')
                    </div>
                </div>
            </div>

            <!-- Bagian Bawah Dashboard -->
            <div class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @include('user.partials.citizen-report-form')
                    @include('user.partials.checklist')
                </div>
                
                @include('user.partials.notification-history')
                

            </div>

        </div>
    </div>

    @include('user.partials.news-modal')
    @include('user.partials.chatbot-widget')
    @include('user.partials.map-modal')

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        
        /* Animasi Lambaian Tangan pada Banner */
        @keyframes wave {
            0% { transform: rotate(0deg); }
            10% { transform: rotate(14deg); }
            20% { transform: rotate(-8deg); }
            30% { transform: rotate(14deg); }
            40% { transform: rotate(-4deg); }
            50% { transform: rotate(10deg); }
            60%, 100% { transform: rotate(0deg); }
        }
        .animate-wave { animation: wave 2.5s infinite; }
    </style>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@2.0.1/dist/chartjs-plugin-annotation.min.js"></script>
    
    <script>
        window.onOpenCvReady = function() { console.log('OpenCV.js is ready.'); };
        lucide.createIcons();
    </script>
    
    <script src="{{ asset('js/classification.js') }}"></script>
    <script src="{{ asset('js/camera.js') }}"></script>
    <script async src="https://docs.opencv.org/4.8.0/opencv.js" onload="onOpenCvReady();"></script>

    <script src="{{ asset('js/dashboard-api.js') }}"></script>
    <script src="{{ asset('js/water-chart.js') }}"></script>
    <script src="{{ asset('js/chatbot.js') }}"></script>
    <script src="{{ asset('js/news-modal.js') }}"></script>
    <script src="{{ asset('js/citizen-reports.js') }}"></script>
    <script src="{{ asset('js/user-dashboard-specific.js') }}"></script>
</x-app-layout>