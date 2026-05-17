<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-slate-800 leading-tight flex items-center gap-2">
            <i data-lucide="layout-dashboard" class="w-6 h-6 text-blue-600"></i>
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8 relative min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-20">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                
                <div class="xl:col-span-1 space-y-8">
                    <div class="bg-white/70 backdrop-blur-md rounded-3xl p-6 shadow-sm border border-slate-200/60 flex flex-col relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full blur-3xl -z-10 group-hover:bg-blue-100 transition-colors"></div>
                        <div class="flex items-center gap-3 mb-5">
                            <div class="p-2 bg-blue-100 text-blue-600 rounded-xl">
                                <i data-lucide="video" class="w-5 h-5"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 tracking-tight">Live Camera Feed</h3>
                        </div>
                        <div class="flex-1 flex flex-col items-center justify-center bg-slate-50/50 rounded-2xl border border-slate-100 p-8 text-center min-h-[200px]">
                            <div class="w-16 h-16 bg-slate-200 text-slate-400 rounded-full flex items-center justify-center mb-4">
                                <i data-lucide="camera" class="w-8 h-8"></i>
                            </div>
                            <p class="text-slate-500 font-medium text-sm">Sistem Siap. Tekan tombol di bawah untuk mengaktifkan HUD Pemindaian Kamera.</p>
                        </div>
                        <button id="startCamera" class="mt-6 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-2xl transition-all shadow-md hover:shadow-lg w-full flex items-center justify-center gap-2">
                            <i data-lucide="power" class="w-4 h-4"></i>
                            Mulai Kamera
                        </button>
                    </div>

                </div>

                <div class="xl:col-span-2 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl p-6 shadow-md text-white flex flex-col justify-between relative overflow-hidden group">
                            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-colors"></div>
                            
                            <div id="weatherSkeleton" class="animate-pulse flex flex-col h-full justify-between">
                                <div class="h-6 bg-white/20 rounded w-1/2 mb-4"></div>
                                <div class="flex gap-4 items-end">
                                    <div class="h-12 bg-white/20 rounded w-24"></div>
                                    <div class="h-6 bg-white/20 rounded w-32 mb-1"></div>
                                </div>
                            </div>

                            <div id="weatherContent" class="hidden h-full flex flex-col justify-between relative z-10">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-sm font-semibold text-blue-100 tracking-wider uppercase flex items-center gap-2">
                                        <i data-lucide="cloud" class="w-4 h-4"></i> Cuaca Saat Ini
                                    </h3>
                                    <span class="bg-white/20 px-2 py-1 rounded text-xs font-bold backdrop-blur-sm" id="weatherCity">Lokasi</span>
                                </div>
                                <div class="flex items-end gap-3 mt-4">
                                    <span class="text-6xl font-black tracking-tighter drop-shadow-sm" id="weatherTemp">--°</span>
                                    <div class="mb-2">
                                        <p class="font-bold text-xl drop-shadow-sm" id="weatherDesc">Memuat...</p>
                                        <p class="text-blue-100 text-sm font-medium flex items-center gap-1"><i data-lucide="droplets" class="w-3 h-3"></i> Kelembapan: <span id="weatherHumidity">--</span>%</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl p-6 shadow-md text-white flex flex-col justify-between relative overflow-hidden transition-colors duration-500" id="aiInsightsCard">
                            <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                            
                            <div id="aiSkeleton" class="animate-pulse flex flex-col h-full justify-between">
                                <div class="flex justify-between mb-4">
                                    <div class="h-6 bg-white/20 rounded w-1/2"></div>
                                    <div class="h-6 bg-white/20 rounded w-16"></div>
                                </div>
                                <div class="flex justify-between items-end">
                                    <div class="h-12 bg-white/20 rounded w-20"></div>
                                    <div class="h-16 w-16 bg-white/20 rounded-full"></div>
                                </div>
                            </div>

                            <div id="aiContent" class="hidden h-full flex flex-col justify-between relative z-10">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-sm font-semibold text-white/80 tracking-wider uppercase flex items-center gap-2">
                                        <i data-lucide="brain-circuit" class="w-4 h-4"></i> AI Prediction
                                    </h3>
                                    <span class="bg-white/20 px-2 py-1 rounded text-xs font-bold backdrop-blur-sm">30 MIN AHEAD</span>
                                </div>
                                <div class="flex justify-between items-end mt-4">
                                    <div>
                                        <p class="text-white/80 text-xs font-medium mb-1 uppercase tracking-wider">Level Air</p>
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-5xl font-black drop-shadow-sm" id="aiPredictedLevel">--</span>
                                            <span class="text-xl font-bold text-white/70">%</span>
                                        </div>
                                        <p class="text-sm font-bold mt-1 px-2 py-0.5 bg-white/20 rounded inline-block" id="aiPredictedStatus">Memuat...</p>
                                    </div>
                                    <div class="text-right flex flex-col items-center">
                                        <p class="text-white/80 text-xs font-medium mb-2 uppercase tracking-wider">Risk Score</p>
                                        <div class="w-16 h-16 rounded-full border-4 border-white/30 flex items-center justify-center relative bg-white/10 backdrop-blur-md shadow-inner">
                                            <span class="text-xl font-black drop-shadow-sm" id="aiRiskScore">--</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/70 backdrop-blur-md rounded-3xl p-6 shadow-sm border border-slate-200/60 flex flex-col h-96 relative group">
                        <div class="flex justify-between items-center mb-6">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-indigo-100 text-indigo-600 rounded-xl">
                                    <i data-lucide="activity" class="w-5 h-5"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-800 tracking-tight">Tren Ketinggian Air</h3>
                            </div>
                            <button onclick="fetchDashboardData()" class="px-3 py-1.5 text-xs font-bold bg-slate-100 text-slate-600 hover:bg-blue-50 hover:text-blue-600 rounded-lg flex items-center gap-1.5 transition-colors">
                                <i data-lucide="refresh-cw" class="w-3.5 h-3.5" id="refreshIcon"></i>
                                Refresh
                            </button>
                        </div>

                        <div id="chartSkeleton" class="absolute inset-0 top-20 bottom-6 left-6 right-6 animate-pulse flex items-end justify-between gap-2 pb-8">
                            <div class="w-full bg-slate-200 rounded-t h-1/4"></div>
                            <div class="w-full bg-slate-200 rounded-t h-1/3"></div>
                            <div class="w-full bg-slate-200 rounded-t h-1/2"></div>
                            <div class="w-full bg-slate-200 rounded-t h-3/4"></div>
                            <div class="w-full bg-slate-200 rounded-t h-2/3"></div>
                            <div class="w-full bg-slate-200 rounded-t h-1/4"></div>
                            <div class="w-full bg-slate-200 rounded-t h-1/5"></div>
                            <div class="w-full bg-slate-200 rounded-t h-2/5"></div>
                        </div>

                        <div class="flex-1 w-full relative opacity-0 transition-opacity duration-500" id="chartContainer">
                            <canvas id="waterChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-white/70 backdrop-blur-md rounded-3xl p-6 shadow-sm border border-slate-200/60">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-100 text-indigo-600 rounded-xl">
                            <i data-lucide="newspaper" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 tracking-tight">Preview Berita Publik</h3>
                    </div>
                    <a href="{{ route('admin.berita.index') }}" class="px-4 py-2 text-xs font-bold bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl flex items-center gap-2 transition-colors">
                        Kelola Berita <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                <div class="flex overflow-x-auto gap-5 pb-4 snap-x snap-mandatory hide-scrollbar" style="scrollbar-width: none; -ms-overflow-style: none;">
                    
                    @forelse($beritas as $berita)
                    <div class="min-w-[280px] max-w-[280px] snap-start bg-[#f4f9ff] rounded-3xl overflow-hidden shadow-sm border border-blue-100/50 flex flex-col group cursor-pointer hover:shadow-md transition-shadow">
                        <div class="relative h-44 overflow-hidden bg-slate-200 flex shrink-0">
                            @if($berita->foto)
                                <img src="{{ asset('storage/' . $berita->foto) }}" alt="Foto Berita" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    <i data-lucide="image" class="w-10 h-10"></i>
                                </div>
                            @endif
                            
                            <div class="absolute top-3 left-3 bg-[#2a3449] text-white text-[11px] font-semibold px-3 py-1.5 rounded-full shadow-sm backdrop-blur-sm bg-opacity-90">
                                {{ $berita->created_at->format('d M Y') }}
                            </div>
                        </div>
                        <div class="p-5 flex flex-col flex-grow justify-between bg-[#f4f9ff]">
                            <h4 class="font-bold text-slate-800 text-[15px] leading-snug line-clamp-3 mb-4">
                                {{ $berita->judul }}
                            </h4>
                            
                            <div id="konten-berita-{{ $berita->id }}" class="hidden">{{ $berita->konten }}</div>
                            <button onclick="bukaModalBerita('{{ $berita->id }}', '{{ addslashes($berita->judul) }}', '{{ $berita->created_at->format('d M Y') }}', '{{ $berita->foto ? asset('storage/'.$berita->foto) : '' }}')" class="text-amber-500 font-bold text-sm flex items-center justify-end gap-1.5 hover:text-amber-600 transition-colors w-full text-right mt-2 focus:outline-none">
                                Selengkapnya <i data-lucide="arrow-right-circle" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="w-full bg-slate-50 border border-dashed border-slate-200 rounded-3xl p-8 text-center flex flex-col items-center justify-center">
                        <i data-lucide="newspaper" class="w-10 h-10 text-slate-300 mb-3"></i>
                        <p class="text-slate-500 font-medium text-sm">Belum ada berita. Tambahkan berita di menu "Kelola Berita".</p>
                    </div>
                    @endforelse

                </div>
            </div>
            </div>
    </div>

    <div id="modalBerita" class="fixed inset-0 z-[100] hidden bg-slate-900/80 backdrop-blur-sm flex-col items-center justify-center p-4 md:p-8 transition-opacity duration-300 opacity-0">
        <div class="bg-white rounded-3xl w-full max-w-4xl overflow-hidden shadow-2xl flex flex-col max-h-[90vh] md:max-h-[85vh] transform scale-95 transition-transform duration-300" id="modalBeritaContent">
            
            <div class="flex justify-between items-center p-5 border-b border-slate-100 bg-slate-50/80">
                <div class="flex items-center gap-3">
                    <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-full flex items-center gap-1.5">
                        <i data-lucide="calendar" class="w-4 h-4"></i> <span id="modalBeritaTanggal"></span>
                    </span>
                    <span class="text-slate-400 text-sm font-medium hidden md:inline-block">— Biro Komunikasi Flood-Vision</span>
                </div>
                <button onclick="tutupModalBerita()" class="p-2 bg-slate-200 hover:bg-red-100 hover:text-red-600 text-slate-500 rounded-full transition-colors focus:outline-none">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6 md:p-10 custom-scrollbar bg-white">
                <img id="modalBeritaFoto" src="" class="w-full h-64 md:h-96 object-cover rounded-2xl mb-8 shadow-sm hidden" alt="Foto Berita">
                
                <h2 id="modalBeritaJudul" class="text-3xl md:text-4xl font-black text-slate-800 mb-8 leading-tight"></h2>
                
                <div id="modalBeritaDeskripsi" class="text-slate-800 text-[16px] md:text-[17px] leading-loose whitespace-pre-wrap font-normal text-justify"></div>
            </div>
        </div>
    </div>
    <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
        <div id="chatWindow" class="hidden w-80 lg:w-96 h-[28rem] mb-4 bg-white/80 backdrop-blur-xl shadow-2xl rounded-3xl border border-white/60 flex-col overflow-hidden transition-all duration-300 transform origin-bottom-right">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-4 text-white flex justify-between items-center shadow-md">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 p-1.5 rounded-lg backdrop-blur-sm">
                        <i data-lucide="bot" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm leading-tight">Flood Vision Assistant - Sistem Mitigasi Banjir Cerdas</h4>
                        <p class="text-[10px] text-blue-100 font-medium tracking-wider uppercase">AI Ready</p>
                    </div>
                </div>
                <button onclick="toggleChat()" class="text-white/70 hover:text-white transition-colors bg-white/10 hover:bg-white/20 p-1.5 rounded-lg">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div id="chatMessages" class="flex-1 p-5 overflow-y-auto space-y-4 bg-slate-50/50">
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center shrink-0 shadow-sm">
                        <i data-lucide="sparkles" class="w-4 h-4 text-white"></i>
                    </div>
                    <div class="bg-white p-3.5 rounded-2xl rounded-tl-none shadow-sm text-sm text-slate-700 border border-slate-100 leading-relaxed">
                        Halo! Saya Flood Vision Assistant - Sistem Mitigasi Banjir Cerdas. Berdasarkan data saat ini, adakah yang bisa saya bantu terkait mitigasi?
                    </div>
                </div>
            </div>
            <div class="p-3 bg-white border-t border-slate-100">
                <form id="chatForm" class="flex gap-2" onsubmit="sendChatMessage(event)">
                    <input type="text" id="chatInput" class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-all shadow-inner" placeholder="Tanya sesuatu..." required autocomplete="off">
                    <button type="submit" id="chatSubmit" class="bg-blue-600 hover:bg-blue-700 text-white p-2.5 rounded-xl transition-colors shadow-md hover:shadow-lg flex items-center justify-center">
                        <i data-lucide="send" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
        <button onclick="toggleChat()" class="w-14 h-14 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full shadow-xl flex items-center justify-center text-white hover:scale-105 transition-transform border-2 border-white/50 group relative">
            <div class="absolute inset-0 rounded-full animate-ping bg-blue-400 opacity-20"></div>
            <i data-lucide="message-square-text" class="w-6 h-6 group-hover:hidden"></i>
            <i data-lucide="chevron-up" class="w-6 h-6 hidden group-hover:block"></i>
        </button>
    </div>

    <div id="cameraModal" class="fixed inset-0 z-[60] hidden bg-black/90 backdrop-blur-md flex flex-col items-center justify-center transition-opacity duration-300">
        <button id="closeCameraModal" class="absolute top-6 right-6 p-3 bg-white/10 hover:bg-white/20 text-white rounded-full transition-colors backdrop-blur-md z-[70]">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
        
        <div class="w-full max-w-5xl px-4 relative flex flex-col items-center">
            <div class="relative w-full aspect-[4/3] md:aspect-video rounded-3xl overflow-hidden shadow-[0_0_50px_rgba(37,99,235,0.2)] border border-white/10 bg-slate-900 ring-1 ring-white/20">
                <video id="videoInput" style="display:none" autoplay playsinline muted></video>
                <canvas id="canvasOutput" class="w-full h-full object-cover"></canvas>
                
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none bg-slate-900" id="cameraPlaceholder">
                    <i data-lucide="loader-2" class="w-12 h-12 text-blue-500 animate-spin mb-4"></i>
                    <p class="text-blue-300 font-medium tracking-widest uppercase text-sm animate-pulse">Initializing OpenCV Tracking...</p>
                </div>
            </div>
            <p class="text-slate-400 text-xs mt-4 uppercase tracking-widest flex items-center gap-2"><i data-lucide="scan" class="w-3 h-3"></i> Canny Edge Analysis Active</p>
        </div>
    </div>

    <style>
        /* Menyembunyikan scrollbar untuk slider berita */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        window.onOpenCvReady = function() {
            console.log('OpenCV.js is ready.');
        };
        // Initialize Icons
        lucide.createIcons();
    </script>
    
    <script src="{{ asset('js/classification.js') }}"></script>
    <script src="{{ asset('js/camera.js') }}"></script>
    <script async src="https://docs.opencv.org/4.8.0/opencv.js" onload="onOpenCvReady();"></script>
    
    <script>
        let waterChart;
        let globalContext = "Level: --, Status: --, Risk Score: --";

        async function fetchDashboardData() {
            const refreshIcon = document.getElementById('refreshIcon');
            if(refreshIcon) refreshIcon.classList.add('animate-spin');

            // Fetch Weather
            let weatherDesc = '';
            try {
                const weatherRes = await fetch('/api/weather');
                const weatherJson = await weatherRes.json();
                if(weatherJson.success) {
                    const d = weatherJson.data;
                    document.getElementById('weatherTemp').innerText = d.temp + '°';
                    document.getElementById('weatherDesc').innerText = d.description;
                    document.getElementById('weatherCity').innerText = d.city;
                    document.getElementById('weatherHumidity').innerText = d.humidity;
                    weatherDesc = d.description;
                    
                    document.getElementById('weatherSkeleton').classList.add('hidden');
                    document.getElementById('weatherContent').classList.remove('hidden');
                }
            } catch(e) { console.error('Error fetching weather:', e); }

            // Fetch AI Analytics
            try {
                const aiRes = await fetch('/api/analytics');
                const aiJson = await aiRes.json();
                if(aiJson.success) {
                    const d = aiJson.data;
                    document.getElementById('aiPredictedLevel').innerText = d.predicted_level;
                    document.getElementById('aiPredictedStatus').innerText = d.prediction_status;
                    document.getElementById('aiRiskScore').innerText = d.risk_score;
                    
                    const card = document.getElementById('aiInsightsCard');
                    card.className = "bg-gradient-to-br rounded-3xl p-6 shadow-md text-white flex flex-col justify-between relative overflow-hidden transition-colors duration-500 ";
                    if(d.risk_score > 70) {
                        card.className += "from-red-500 to-rose-700";
                    } else if (d.risk_score > 35) {
                        card.className += "from-orange-500 to-amber-600";
                    } else {
                        card.className += "from-emerald-500 to-teal-600";
                    }

                    document.getElementById('aiSkeleton').classList.add('hidden');
                    document.getElementById('aiContent').classList.remove('hidden');

                    // Update Context for Gemini
                    globalContext = `Saat ini Level Air ${d.current_level}%. Prediksi 30 menit ke depan: ${d.predicted_level}% (${d.prediction_status}). Cuaca: ${weatherDesc}. Skor Risiko: ${d.risk_score}/100.`;
                }
            } catch(e) { console.error('Error fetching analytics:', e); }

            // Fetch Logs for Chart
            try {
                const logsRes = await fetch('/api/logs');
                const logsJson = await logsRes.json();
                if(logsJson.data) {
                    document.getElementById('chartSkeleton').classList.add('hidden');
                    document.getElementById('chartContainer').classList.remove('opacity-0');
                    updateChart(logsJson.data);
                }
            } catch(e) { console.error('Error fetching logs:', e); }

            if(refreshIcon) setTimeout(() => refreshIcon.classList.remove('animate-spin'), 500);
        }

        function updateChart(data) {
            const labels = data.map(item => {
                const date = new Date(item.created_at);
                const hh = String(date.getHours()).padStart(2, '0');
                const mm = String(date.getMinutes()).padStart(2, '0');
                const ss = String(date.getSeconds()).padStart(2, '0');
                return `${hh}:${mm}:${ss}`;
            });
            const values = data.map(item => item.nilai_level);

            if(waterChart) {
                waterChart.data.labels = labels;
                waterChart.data.datasets[0].data = values;
                waterChart.update();
            } else {
                const ctx = document.getElementById('waterChart').getContext('2d');
                
                // Create Gradient
                let gradient = ctx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, 'rgba(37, 99, 235, 0.2)'); // blue-600
                gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');

                waterChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Level Air (%)',
                            data: values,
                            borderColor: '#2563eb', // blue-600
                            backgroundColor: gradient,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#2563eb',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(255, 255, 255, 0.9)',
                                titleColor: '#1e293b',
                                bodyColor: '#1e293b',
                                borderColor: '#e2e8f0',
                                borderWidth: 1,
                                padding: 12,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return context.parsed.y + '%';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }

        // Chatbot Logic
        function toggleChat() {
            const win = document.getElementById('chatWindow');
            if(win.classList.contains('hidden')) {
                win.classList.remove('hidden');
                win.classList.add('flex');
                document.getElementById('chatInput').focus();
            } else {
                win.classList.add('hidden');
                win.classList.remove('flex');
            }
        }

        async function sendChatMessage(e) {
            e.preventDefault();
            const input = document.getElementById('chatInput');
            const btn = document.getElementById('chatSubmit');
            const msg = input.value;
            if(!msg) return;
            
            input.value = '';
            btn.disabled = true;
            btn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>`;
            lucide.createIcons();
            
            const chatBox = document.getElementById('chatMessages');
            
            // User Message
            chatBox.innerHTML += `
                <div class="flex justify-end mb-4">
                    <div class="bg-blue-600 p-3.5 rounded-2xl rounded-tr-none shadow-sm text-sm text-white max-w-[85%] leading-relaxed">
                        ${msg}
                    </div>
                </div>
            `;
            chatBox.scrollTop = chatBox.scrollHeight;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const res = await fetch('/api/chat', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ prompt: msg, context: globalContext })
                });
                const json = await res.json();
                
                // AI Response
                chatBox.innerHTML += `
                    <div class="flex gap-3 mb-4">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center shrink-0 shadow-sm">
                            <i data-lucide="sparkles" class="w-4 h-4 text-white"></i>
                        </div>
                        <div class="bg-white p-3.5 rounded-2xl rounded-tl-none shadow-sm text-sm text-slate-700 border border-slate-100 max-w-[85%] leading-relaxed">
                            ${json.reply}
                        </div>
                    </div>
                `;
            } catch(err) {
                chatBox.innerHTML += `
                    <div class="flex gap-3 mb-4">
                        <div class="w-8 h-8 rounded-full bg-red-500 flex items-center justify-center shrink-0 shadow-sm">
                            <i data-lucide="alert-circle" class="w-4 h-4 text-white"></i>
                        </div>
                        <div class="bg-red-50 p-3.5 rounded-2xl rounded-tl-none shadow-sm text-sm text-red-700 border border-red-100 max-w-[85%] leading-relaxed">
                            Gagal terhubung ke AI.
                        </div>
                    </div>
                `;
            }
            
            btn.disabled = false;
            btn.innerHTML = `<i data-lucide="send" class="w-4 h-4"></i>`;
            lucide.createIcons();
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        // 👇 SCRIPT POP-UP BERITA 👇
        function bukaModalBerita(id, judul, tanggal, fotoUrl) {
            document.getElementById('modalBeritaJudul').innerText = judul;
            document.getElementById('modalBeritaTanggal').innerText = tanggal;
            
            // Ambil konten dari div tersembunyi
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
            // Animasi masuk
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
            
            // Tunggu animasi selesai baru di-hide
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }
        // 👆 AKHIR SCRIPT POP-UP BERITA 👆

        // Intercept Classification logic for animated pulse on status
        const originalStatusTextUpdate = document.getElementById('statusText');
        if (originalStatusTextUpdate) {
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.target.innerText === 'AWAS') {
                        mutation.target.className = "px-4 py-1.5 rounded-full text-sm font-extrabold bg-red-100 text-red-600 tracking-wide animate-pulse shadow-[0_0_15px_rgba(220,38,38,0.5)]";
                    } else if (mutation.target.innerText === 'SIAGA') {
                        mutation.target.className = "px-4 py-1.5 rounded-full text-sm font-extrabold bg-orange-100 text-orange-600 tracking-wide";
                    } else if (mutation.target.innerText === 'WASPADA') {
                        mutation.target.className = "px-4 py-1.5 rounded-full text-sm font-extrabold bg-yellow-100 text-yellow-600 tracking-wide";
                    } else {
                        mutation.target.className = "px-4 py-1.5 rounded-full text-sm font-extrabold bg-green-100 text-green-600 tracking-wide";
                    }
                });
            });
            observer.observe(originalStatusTextUpdate, { childList: true, characterData: true, subtree: true });
        }

        // Initial Load
        document.addEventListener('DOMContentLoaded', () => {
            fetchDashboardData();
            // Refresh every 5 minutes
            setInterval(fetchDashboardData, 300000);
        });
    </script>
</x-app-layout>