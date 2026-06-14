<x-app-layout>
    @section('title', 'Dashboard Pemantauan')

    @include('user.partials.news-slider')

    <div class="py-8 relative min-h-screen space-y-8">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 items-stretch">
                <div class="xl:col-span-1 flex flex-col space-y-8">
                    @include('user.partials.safety-guide')
                    @include('user.partials.telegram-alert')
                </div>

                <div class="xl:col-span-2 flex flex-col">
                    <div class="w-full h-full flex flex-col flex-grow">
                        @include('user.partials.water-chart')
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full block">
            @include('user.partials.checklist')
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- 🌟 KALIBRASI 1: items-start diganti ke items-stretch agar tinggi kolom kanan & kiri dipaksa sama rata --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-stretch">

                <div class="flex flex-col h-full">
                    @include('user.partials.citizen-report-form')
                </div>

                {{-- Pembungkus Utama Komponen Riwayat --}}
                <div class="bg-white/40 backdrop-blur-md border border-white/30 rounded-3xl p-4 sm:p-6 pt-6 sm:pt-[24px] shadow-sm transition-all duration-300 hover:bg-white/60 hover:shadow-md flex flex-col h-full flex-grow relative overflow-hidden w-full min-h-[580px]">
                    <div class="flex items-center justify-between mb-4 shrink-0 border-b border-slate-100/60 pb-3">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-rose-50 text-rose-600 rounded-xl shadow-sm border border-rose-100">
                                <i data-lucide="bell-ring" class="w-4 h-4"></i>
                            </div>
                            <h3 class="text-base font-black text-slate-800 tracking-tight">Riwayat Peringatan Sistem</h3>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider bg-slate-100/80 px-2 py-0.5 rounded-md">Live Log</span>
                    </div>

                    {{-- 🌟 KALIBRASI 2: Buang style max-height statis, ganti dengan flex-grow h-0 agar mengisi penuh sisa ruang bawah tanpa gantung --}}
                    <div class="flex-grow h-0 overflow-y-auto pr-2 space-y-3 custom-history-scroll" id="notificationContainer">
                        <div class="flex flex-col justify-center items-center h-full py-20 text-slate-400">
                            <i data-lucide="loader-2" class="w-6 h-6 animate-spin text-indigo-500"></i>
                            <span class="ml-2 text-xs font-bold text-slate-500 mt-2">Sinkronisasi Log Database...</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="w-full block pt-8">
            @include('user.partials.map')
        </div>

    </div>

    @include('user.partials.news-modal')
    @include('user.partials.chatbot-widget')
    @include('user.partials.map-modal')

    <style>
        /* 🌟 KALIBRASI 3: Memangkas padding vertikal item di dalam card agar space padat presisi */
        #notificationContainer > div {
            margin-top: 0px !important;
            margin-bottom: 0px !important;
            padding-top: 10px !important;
            padding-bottom: 10px !important;
        }

        /* Scrollbar minimalis khusus untuk area history transparan */
        .custom-history-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .custom-history-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-history-scroll::-webkit-scrollbar-thumb {
            background: rgba(203, 213, 225, 0.6);
            border-radius: 10px;
        }

        .custom-history-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 163, 184, 0.8);
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @keyframes wave {
            0% { transform: rotate(0deg); }
            10% { transform: rotate(14deg); }
            20% { transform: rotate(-8deg); }
            30% { transform: rotate(14deg); }
            40% { transform: rotate(-4deg); }
            50% { transform: rotate(10deg); }
            60%, 100% { transform: rotate(0deg); }
        }

        .animate-wave {
            animation: wave 2.5s infinite;
        }
    </style>

    {{-- Pemanggilan CDN Library Inti Terpusat --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@2.0.1/dist/chartjs-plugin-annotation.min.js"></script>

    <script>
        window.onOpenCvReady = function() {
            console.log('OpenCV.js is ready.');
        };
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>

    {{-- Aset Script Komponen Pendukung --}}
    <script src="{{ asset('js/classification.js') }}"></script>
    <script src="{{ asset('js/camera.js') }}"></script>
    <script async src="https://docs.opencv.org/4.8.0/opencv.js" onload="onOpenCvReady();"></script>

    {{-- Penyatuan Mesin Grafik Eksternal --}}
    <script src="{{ asset('js/water-chart.js') }}"></script>
    <script src="{{ asset('js/dashboard-api.js') }}"></script>

    <script src="{{ asset('js/chatbot.js') }}"></script>
    <script src="{{ asset('js/news-modal.js') }}"></script>
    <script src="{{ asset('js/user-dashboard-specific.js') }}"></script>

    {{-- INTERSEPTOR SAKTI: MEMBERSIHKAN TANDA BINTANG RAW DARI AJAX NOTIFIKASI --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const container = document.getElementById('notificationContainer');
            if (container) {
                const observer = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        if (mutation.addedNodes.length) {
                            const walker = document.createTreeWalker(container, NodeFilter.SHOW_TEXT, null, false);
                            let textNode;
                            const nodesToReplace = [];

                            while (textNode = walker.nextNode()) {
                                if (textNode.nodeValue.includes('*')) {
                                    nodesToReplace.push(textNode);
                                }
                            }

                            nodesToReplace.forEach(node => {
                                const span = document.createElement('span');
                                span.innerHTML = node.nodeValue.replace(/\*(.*?)\*/g, '<strong class="font-black text-slate-800">$1</strong>');
                                node.parentNode.replaceChild(span, node);
                            });
                        }
                    });
                });
                observer.observe(container, { childList: true, subtree: true });
            }
        });
    </script>
</x-app-layout>