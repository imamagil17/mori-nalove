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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-start">

                <div class="flex flex-col">
                    @include('user.partials.citizen-report-form')
                </div>

                <div class="flex flex-col px-2">
                    <div class="flex items-center gap-3 mb-5 shrink-0">
                        <div class="p-2 bg-rose-50 text-rose-600 rounded-xl shadow-sm border border-rose-100">
                            <i data-lucide="bell-ring" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-800 tracking-tight">Riwayat Peringatan Sistem</h3>
                    </div>

                    <div class="flex-grow overflow-y-auto pr-2 space-y-4 custom-history-scroll"
                        id="notificationContainer" style="max-height: 440px;">
                        <div class="flex justify-center items-center py-4 text-slate-400">
                            <i data-lucide="loader-2" class="w-6 h-6 animate-spin"></i>
                            <span class="ml-2 text-sm font-medium">Memuat riwayat peringatan...</span>
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
            0% {
                transform: rotate(0deg);
            }

            10% {
                transform: rotate(14deg);
            }

            20% {
                transform: rotate(-8deg);
            }

            30% {
                transform: rotate(14deg);
            }

            40% {
                transform: rotate(-4deg);
            }

            50% {
                transform: rotate(10deg);
            }

            60%,
            100% {
                transform: rotate(0deg);
            }
        }

        .animate-wave {
            animation: wave 2.5s infinite;
        }
    </style>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@2.0.1/dist/chartjs-plugin-annotation.min.js">
    </script>

    <script>
        window.onOpenCvReady = function() {
            console.log('OpenCV.js is ready.');
        };
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        let waterChartInstance = null;

        function initChart() {
            const ctx = document.getElementById('waterChart').getContext('2d');
            waterChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Tinggi Air (cm)',
                        data: [],
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#4f46e5',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        annotation: {
                            annotations: {}
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: 500
                        }
                    }
                }
            });
        }

        // NAMA FUNGSI BARU AGAR KEBAL DARI FILE LAMA
        function muatDataAI(namaSungai) {
            document.getElementById('chartSkeleton')?.classList.remove('hidden');
            document.getElementById('chartContainer')?.classList.add('opacity-0');

            fetch(`/api/analytics?sungai=${encodeURIComponent(namaSungai)}`)
                .then(response => {
                    if (!response.ok) throw new Error("Gagal terhubung ke API");
                    return response.json();
                })
                .then(res => {
                    if (res.success && res.data) {
                        const data = res.data;

                        // JIKA SUNGAI BELUM PUNYA DATA VIDEO SAMA SEKALI
                        if (data.chart_data.length === 0) {
                            if (waterChartInstance) {
                                waterChartInstance.data.labels = ['Kosong'];
                                waterChartInstance.data.datasets[0].data = [0];
                                waterChartInstance.options.plugins.annotation.annotations = {};
                                waterChartInstance.update();
                            }
                            document.getElementById('ai_level_air').innerText = '--';
                            document.getElementById('ai_risk_score').innerText = '0';
                            const elStatus = document.getElementById('ai_status_keamanan');
                            if (elStatus) {
                                elStatus.innerText = 'BELUM ADA DATA';
                                elStatus.className =
                                    'text-sm font-bold mt-1 px-2 py-0.5 rounded inline-block truncate max-w-full bg-slate-500 text-white';
                            }
                        }
                        // JIKA DATA TERSEDIA, LUKIS GRAFIK!
                        else {
                            if (waterChartInstance) {
                                waterChartInstance.data.labels = data.chart_labels;
                                waterChartInstance.data.datasets[0].data = data.chart_data;

                                waterChartInstance.options.plugins.annotation.annotations = {
                                    lineAwas: {
                                        type: 'line',
                                        yMin: data.thresholds.bahaya,
                                        yMax: data.thresholds.bahaya,
                                        borderColor: '#ef4444',
                                        borderWidth: 2,
                                        borderDash: [5, 5],
                                        label: {
                                            content: 'Batas AWAS',
                                            display: true,
                                            position: 'end',
                                            backgroundColor: '#ef4444'
                                        }
                                    },
                                    lineWaspada: {
                                        type: 'line',
                                        yMin: data.thresholds.siaga,
                                        yMax: data.thresholds.siaga,
                                        borderColor: '#f97316',
                                        borderWidth: 2,
                                        borderDash: [5, 5],
                                        label: {
                                            content: 'Batas WASPADA',
                                            display: true,
                                            position: 'end',
                                            backgroundColor: '#f97316'
                                        }
                                    },
                                    lineSiaga: {
                                        type: 'line',
                                        yMin: data.thresholds.waspada,
                                        yMax: data.thresholds.waspada,
                                        borderColor: '#eab308',
                                        borderWidth: 2,
                                        borderDash: [5, 5],
                                        label: {
                                            content: 'Batas SIAGA',
                                            display: true,
                                            position: 'end',
                                            backgroundColor: '#eab308'
                                        }
                                    }
                                };

                                waterChartInstance.options.scales.y.suggestedMax = data.thresholds.max;
                                waterChartInstance.update();
                            }

                            // UPDATE KARTU AI
                            document.getElementById('ai_level_air').innerText = data.current_level;
                            document.getElementById('ai_risk_score').innerText = data.risk_score;

                            const elStatus = document.getElementById('ai_status_keamanan');
                            if (elStatus) {
                                elStatus.innerText = data.prediction_status;
                                if (data.prediction_status === 'AWAS' || data.prediction_status === 'BAHAYA') {
                                    elStatus.className =
                                        "text-sm font-bold mt-1 px-2 py-0.5 rounded inline-block truncate max-w-full bg-red-500 text-white";
                                } else if (data.prediction_status === 'WASPADA') {
                                    elStatus.className =
                                        "text-sm font-bold mt-1 px-2 py-0.5 rounded inline-block truncate max-w-full bg-orange-500 text-white";
                                } else {
                                    elStatus.className =
                                        "text-sm font-bold mt-1 px-2 py-0.5 rounded inline-block truncate max-w-full bg-emerald-500 text-white";
                                }
                            }
                        }
                    }
                    tampilkanGrafik();
                })
                .catch(error => {
                    console.error('Error memuat data:', error);
                    tampilkanGrafik();
                });
        }

        function tampilkanGrafik() {
            setTimeout(() => {
                document.getElementById('chartSkeleton')?.classList.add('hidden');
                document.getElementById('chartContainer')?.classList.remove('opacity-0');
            }, 300);
        }

        // FUNGSI BARU UNTUK TOMBOL REFRESH
        function refreshDataAI() {
            const sungai = document.getElementById('riverSelect').value;
            muatDataAI(sungai);

            const icon = document.getElementById('refreshIcon');
            if (icon) {
                icon.classList.add('animate-spin');
                setTimeout(() => icon.classList.remove('animate-spin'), 1000);
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            initChart();
            const riverSelect = document.getElementById('riverSelect');
            if (riverSelect) muatDataAI(riverSelect.value);
        });
    </script>

    <script src="{{ asset('js/classification.js') }}"></script>
    <script src="{{ asset('js/camera.js') }}"></script>
    <script async src="https://docs.opencv.org/4.8.0/opencv.js" onload="onOpenCvReady();"></script>
    <script src="{{ asset('js/dashboard-api.js') }}"></script>

    <!-- FILE JS DUMMY (water-chart.js) TELAH DIHAPUS DARI SINI -->

    <script src="{{ asset('js/chatbot.js') }}"></script>
    <script src="{{ asset('js/news-modal.js') }}"></script>
    <script src="{{ asset('js/citizen-reports.js') }}"></script>
    <script src="{{ asset('js/user-dashboard-specific.js') }}"></script>
</x-app-layout>
