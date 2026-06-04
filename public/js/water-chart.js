/**
 * water-chart.js - Flood Vision v1.0
 * Javascript Controller for River Water Level Line Chart (Chart.js + Annotation Plugin)
 */

// 1. Array labels waktu statis (Timeframe teratur per jam - 24 Jam Penuh)
const TIMEFRAME_LABELS = [
    '00:00', '01:00', '02:00', '03:00', '04:00', '05:00',
    '06:00', '07:00', '08:00', '09:00', '10:00', '11:00',
    '12:00', '13:00', '14:00', '15:00', '16:00', '17:00',
    '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'
];

// Menyimpan data simulasi cache untuk setiap sungai agar saat berganti-ganti datanya tidak ter-reset acak total
const riverDataCache = {};

// Fungsi pembantu menghasilkan data level air (%) realistis per sungai
function generateRiverData(riverName) {
    if (riverDataCache[riverName]) {
        // Jika sudah ada data di cache, kita geser sedikit nilainya secara smooth (mengalir naik-turun)
        const oldData = riverDataCache[riverName];
        return oldData.map(val => {
            const drift = Math.floor(Math.random() * 7) - 3; // -3 s.d. +3
            return Math.min(100, Math.max(15, val + drift));
        });
    }

    // Baseline awal per sungai untuk visualisasi dinamis yang bervariasi
    let base = 40;
    if (riverName === "Sungai Lariang") base = 75;
    else if (riverName === "Sungai Lindu") base = 55;
    else if (riverName === "Sungai Pakuli") base = 45;
    else if (riverName === "Sungai Marawola") base = 42;
    else if (riverName === "Sungai Palolo") base = 48;
    else if (riverName === "Sungai Kulawi") base = 62;
    else if (riverName === "Sungai Ngatabaru") base = 38;
    else if (riverName === "Sungai Wuno") base = 39;
    else if (riverName === "Sungai Bangga") base = 50;
    else if (riverName === "Sungai Samba") base = 53;

    const values = [];
    let currentVal = base;
    for (let i = 0; i < TIMEFRAME_LABELS.length; i++) {
        const change = Math.floor(Math.random() * 11) - 5; // -5 s.d. +5
        currentVal = Math.min(100, Math.max(15, currentVal + change));
        values.push(currentVal);
    }
    
    riverDataCache[riverName] = values;
    return values;
}

// 2. Fungsi Utama Dipanggil dari API Auto-refresh
function updateChart(data) {
    // Ambil sungai yang saat ini terpilih di dropdown, default Sungai Gumbasa jika element belum siap
    const dropdown = document.querySelector('select[onchange="updateChartByRiver(this.value)"]');
    const selectedRiver = dropdown ? dropdown.value : "Sungai Gumbasa";
    
    // Gunakan dataset dinamis sungai terpilih
    const values = generateRiverData(selectedRiver);

    // Jika ada data riil dari sensor OpenCV/YOLO di database, kita suntikkan ke data point terakhir
    if (data && data.length > 0) {
        const latestLog = data[data.length - 1];
        if (latestLog && latestLog.nilai_level) {
            // Skala level air real-time YOLO disesuaikan ke data point terakhir (18:00 WITA)
            values[values.length - 1] = Math.min(100, Math.max(0, Math.round(latestLog.nilai_level)));
        }
    }

    const existingChart = Chart.getChart('waterChart');

    if (existingChart) {
        existingChart.data.labels = TIMEFRAME_LABELS;
        existingChart.data.datasets[0].data = values;
        existingChart.data.datasets[0].label = `Level Air ${selectedRiver} (%)`;
        existingChart.update('none'); 
    } else {
        const ctxEl = document.getElementById('waterChart');
        if (!ctxEl) return;
        const ctx = ctxEl.getContext('2d');
        
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.25)'); // blue-500
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

        window.waterChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: TIMEFRAME_LABELS,
                datasets: [{
                    label: `Level Air ${selectedRiver} (%)`,
                    data: values,
                    borderColor: '#3b82f6', // blue-500
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    fill: true,
                    tension: 0.35 
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#1e293b',
                        bodyColor: '#1e293b',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + '% (Terukur)';
                            }
                        }
                    },
                    // 3 Garis Ambang Batas (Threshold Lines) Statis
                    annotation: {
                        annotations: {
                            lineNormal: {
                                type: 'line',
                                yMin: 60,
                                yMax: 60,
                                borderColor: '#22c55e', // Hijau Aman
                                borderWidth: 2,
                                borderDash: [6, 6]
                            },
                            lineSiaga: {
                                type: 'line',
                                yMin: 80,
                                yMax: 80,
                                borderColor: '#f97316', // Jingga Siaga
                                borderWidth: 2,
                                borderDash: [6, 6]
                            },
                            lineBahaya: {
                                type: 'line',
                                yMin: 90,
                                yMax: 90,
                                borderColor: '#ef4444', // Merah Bahaya
                                borderWidth: 2,
                                borderDash: [6, 6]
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.04)',
                            drawBorder: false,
                        },
                        ticks: {
                            font: { family: "'Figtree', sans-serif", size: 11 },
                            color: '#64748b',
                            stepSize: 10
                        }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: {
                            font: { family: "'Figtree', sans-serif", size: 11 },
                            color: '#64748b',
                            autoSkip: false
                        }
                    }
                }
            }
        });
    }
}

// 3. LOGIKA DYNAMIC UPDATE PER SUNGAI
function updateChartByRiver(riverName) {
    // Jalankan pergeseran nilai secara halus menggunakan cache
    const values = generateRiverData(riverName);
    
    // Perbarui data cache agar nilainya stabil/sedikit berubah pada pergeseran berikutnya
    riverDataCache[riverName] = values;

    const existingChart = Chart.getChart('waterChart');

    if (existingChart) {
        existingChart.data.labels = TIMEFRAME_LABELS;
        existingChart.data.datasets[0].data = values;
        existingChart.data.datasets[0].label = `Level Air ${riverName} (%)`;
        
        // Update Chart dengan transisi animasi yang smooth (mengalir naik-turun)
        existingChart.update({
            duration: 800,
            easing: 'easeInOutQuad'
        });
    } else {
        // Fallback jika chart belum siap diinisialisasi
        updateChart([]);
    }

    // Sinkronkan data card AI Prediction secara interaktif
    updateAiPredictionCard(riverName);
}

// Fungsi Sinkronisasi Halaman: Update komponen AI Prediction Card secara dinamis
function updateAiPredictionCard(riverName) {
    const predictionData = {
        'Sungai Gumbasa':  { level: 62, status: 'NORMAL',  score: 12, from: 'emerald-500', to: 'teal-600' },
        'Sungai Lariang':   { level: 91, status: 'BAHAYA',  score: 94, from: 'red-500',     to: 'rose-700' },
        'Sungai Lindu':     { level: 82, status: 'SIAGA',   score: 68, from: 'orange-500',  to: 'amber-600' },
        'Sungai Samba':     { level: 74, status: 'SIAGA',   score: 55, from: 'orange-500',  to: 'amber-600' },
        'Sungai Pakuli':    { level: 58, status: 'SIAGA',   score: 42, from: 'orange-500',  to: 'amber-600' },
        'Sungai Marawola':  { level: 38, status: 'NORMAL',  score: 10, from: 'emerald-500', to: 'teal-600' },
        'Sungai Palolo':    { level: 48, status: 'NORMAL',  score: 20, from: 'emerald-500', to: 'teal-600' },
        'Sungai Kulawi':    { level: 30, status: 'NORMAL',  score: 18, from: 'emerald-500', to: 'teal-600' },
        'Sungai Ngatabaru': { level: 25, status: 'NORMAL',  score: 8,  from: 'emerald-500', to: 'teal-600' },
        'Sungai Wuno':      { level: 32, status: 'NORMAL',  score: 14, from: 'emerald-500', to: 'teal-600' },
        'Sungai Bangga':    { level: 68, status: 'SIAGA',   score: 38, from: 'orange-500',  to: 'amber-600' }
    };

    const d = predictionData[riverName] || { level: 50, status: 'NORMAL', score: 25, from: 'emerald-500', to: 'teal-600' };

    // Failsafe DOM Updates: Dukung kedua versi ID (traditional & requested)
    const aiLev = document.getElementById('aiPredictedLevel') || document.getElementById('ai_level_air');
    if (aiLev) aiLev.textContent = d.level;

    const aiStat = document.getElementById('aiPredictedStatus') || document.getElementById('ai_status_keamanan');
    if (aiStat) aiStat.textContent = d.status;

    const aiRisk = document.getElementById('aiRiskScore') || document.getElementById('ai_risk_score');
    if (aiRisk) aiRisk.textContent = d.score;

    // Serasikan warna gradien background card berdasarkan tingkat risiko / status
    const card = document.getElementById('aiInsightsCard');
    if (card) {
        card.className = `bg-gradient-to-br from-${d.from} to-${d.to} rounded-3xl p-6 shadow-md text-white flex flex-col justify-between relative overflow-hidden transition-all duration-500`;
    }
}
