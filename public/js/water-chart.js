/**
 * water-chart.js - Mori Nalove v1.0
 * Javascript Controller for River Water Level Line Chart (Chart.js + Annotation Plugin)
 * * SINKRONISASI MUTAKHIR: Skala Riil 500 cm & Label Batas Kebencanaan Akurat.
 */

// 1. Array labels waktu statis (Timeframe teratur per jam - 24 Jam Penuh)
const TIMEFRAME_LABELS = [
    '00:00', '01:00', '02:00', '03:00', '04:00', '05:00',
    '06:00', '07:00', '08:00', '09:00', '10:00', '11:00',
    '12:00', '13:00', '14:00', '15:00', '16:00', '17:00',
    '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'
];

// Menyimpan data simulasi cache untuk setiap sungai agar tidak ter-reset acak
const riverDataCache = {};

// Fungsi pembantu menghasilkan data level air (cm) realistis per sungai hingga batas 500cm
function generateRiverData(riverName) {
    if (riverDataCache[riverName]) {
        const oldData = riverDataCache[riverName];
        return oldData.map(val => {
            const drift = Math.floor(Math.random() * 21) - 10; // -10 s.d. +10 cm
            return Math.min(480, Math.max(50, val + drift));
        });
    }

    // Baseline awal centimeter per sungai untuk visualisasi dinamis
    let base = 180;
    if (riverName === "Sungai Lariang") base = 420;
    else if (riverName === "Sungai Lindu") base = 320;
    else if (riverName === "Sungai Pakuli") base = 280;
    else if (riverName === "Sungai Marawola") base = 190;
    else if (riverName === "Sungai Palolo") base = 210;
    else if (riverName === "Sungai Kulawi") base = 340;
    else if (riverName === "Sungai Ngatabaru") base = 160;
    else if (riverName === "Sungai Wuno") base = 170;
    else if (riverName === "Sungai Bangga") base = 290;
    else if (riverName === "Sungai Samba") base = 310;

    const values = [];
    let currentVal = base;
    for (let i = 0; i < TIMEFRAME_LABELS.length; i++) {
        const change = Math.floor(Math.random() * 41) - 20; // -20 s.d. +20 cm
        currentVal = Math.min(490, Math.max(40, currentVal + change));
        values.push(currentVal);
    }
    
    riverDataCache[riverName] = values;
    return values;
}

// 2. Fungsi Utama Dipanggil dari API Auto-refresh
function updateChart(data) {
    const dropdown = document.getElementById('riverSelect') || document.getElementById('river_select') || document.querySelector('select');
    const selectedRiver = dropdown ? dropdown.value : "Sungai Gumbasa";
    
    const values = generateRiverData(selectedRiver);

    // INTEGRASI DATA REALTIME SENSOR
    if (data && data.length > 0) {
        const latestLog = data[data.length - 1];
        if (latestLog && latestLog.nilai_level && latestLog.nama_sungai === selectedRiver) {
            values[values.length - 1] = Math.min(500, Math.max(0, Math.round(latestLog.nilai_level)));
        }
    }

    const existingChart = Chart.getChart('waterChart');

    if (existingChart) {
        existingChart.data.labels = TIMEFRAME_LABELS;
        existingChart.data.datasets[0].data = values;
        existingChart.data.datasets[0].label = `Level Air ${selectedRiver} (cm)`;
        existingChart.update('none'); 
    } else {
        const ctxEl = document.getElementById('waterChart');
        if (!ctxEl) return;
        const ctx = ctxEl.getContext('2d');
        
        // Efek Gradasi Area Bawah Garis
        const gradient = ctx.createLinearGradient(0, 0, 0, ctxEl.clientHeight);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.25)');   
        gradient.addColorStop(0.5, 'rgba(79, 70, 229, 0.08)'); 
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');     

        window.waterChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: TIMEFRAME_LABELS,
                datasets: [{
                    label: `Level Air ${selectedRiver} (cm)`,
                    data: values,
                    borderColor: '#4f46e5', // Indigo-600 bawaan dashboard utama
                    backgroundColor: gradient, 
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true, 
                    tension: 0.4 
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
                                return context.parsed.y + ' cm (Terukur)';
                            }
                        }
                    },
                    // 🌟 3 GARIS AMBANG BATAS DINAMIS (SIAGA, WASPADA, AWAS) SESUAI DESAIN GRAFIK
                    annotation: {
                        annotations: {
                            lineSiaga: {
                                type: 'line',
                                yMin: 150,
                                yMax: 150,
                                borderColor: '#10b981', // 🟢 Hijau Emerald
                                borderWidth: 2,
                                borderDash: [6, 6],
                                label: {
                                    content: 'Batas SIAGA',
                                    display: true,
                                    position: 'end',
                                    backgroundColor: '#10b981',
                                    color: '#fff',
                                    font: { size: 10, weight: 'bold' }
                                }
                            },
                            lineWaspada: {
                                type: 'line',
                                yMin: 350,
                                yMax: 350,
                                borderColor: '#f59e0b', // 🟡 Oranye Amber
                                borderWidth: 2,
                                borderDash: [6, 6],
                                label: {
                                    content: 'Batas WASPADA',
                                    display: true,
                                    position: 'end',
                                    backgroundColor: '#f59e0b',
                                    color: '#fff',
                                    font: { size: 10, weight: 'bold' }
                                }
                            },
                            lineAwas: {
                                type: 'line',
                                yMin: 450,
                                yMax: 450,
                                borderColor: '#ef4444', // 🔴 Merah Rose
                                borderWidth: 2,
                                borderDash: [6, 6],
                                label: {
                                    content: 'Batas AWAS',
                                    display: true,
                                    position: 'end',
                                    backgroundColor: '#ef4444',
                                    color: '#fff',
                                    font: { size: 10, weight: 'bold' }
                                }
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 500, // 💡 Ketinggian diubah murni skala 500 cm
                        grid: {
                            color: 'rgba(0, 0, 0, 0.04)',
                            drawBorder: false,
                        },
                        ticks: {
                            font: { family: "'Figtree', sans-serif", size: 11 },
                            color: '#64748b',
                            stepSize: 50 // Kelipatan 50, 100, 150 ... 500
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

// 3. LOGIKA DROPDOWN UPDATE INTERAKTIF
function updateChartByRiver(riverName) {
    const values = generateRiverData(riverName);
    riverDataCache[riverName] = values;

    const existingChart = Chart.getChart('waterChart');

    if (existingChart) {
        existingChart.data.labels = TIMEFRAME_LABELS;
        existingChart.data.datasets[0].data = values;
        existingChart.data.datasets[0].label = `Level Air ${riverName} (cm)`;
        
        existingChart.update({
            duration: 800,
            easing: 'easeInOutQuad'
        });
    } else {
        updateChart([]);
    }

    updateAiPredictionCard(riverName);
}

// Fungsi Sinkronisasi Data Teks Info Card AI secara Realtime
function updateAiPredictionCard(riverName) {
    const predictionData = {
        'Sungai Gumbasa':   { level: 140, status: 'SIAGA',   score: 12, from: 'emerald-500', to: 'teal-600' },
        'Sungai Lariang':   { level: 465, status: 'AWAS',    score: 94, from: 'red-500',     to: 'rose-700' },
        'Sungai Lindu':     { level: 380, status: 'WASPADA', score: 68, from: 'orange-500',  to: 'amber-600' },
        'Sungai Samba':     { level: 365, status: 'WASPADA', score: 55, from: 'orange-500',  to: 'amber-600' },
        'Sungai Pakuli':    { level: 210, status: 'SIAGA',   score: 32, from: 'emerald-500', to: 'teal-600' },
        'Sungai Marawola':  { level: 115, status: 'SIAGA',   score: 10, from: 'emerald-500', to: 'teal-600' },
        'Sungai Palolo':    { level: 130, status: 'SIAGA',   score: 15, from: 'emerald-500', to: 'teal-600' },
        'Sungai Kulawi':    { level: 395, status: 'WASPADA', score: 72, from: 'orange-500',  to: 'amber-600' },
        'Sungai Ngatabaru': { level: 95,  status: 'SIAGA',   score: 8,  from: 'emerald-500', to: 'teal-600' },
        'Sungai Wuno':      { level: 120, status: 'SIAGA',   score: 14, from: 'emerald-500', to: 'teal-600' },
        'Sungai Bangga':    { level: 370, status: 'WASPADA', score: 64, from: 'orange-500',  to: 'amber-600' }
    };

    const d = predictionData[riverName] || { level: 120, status: 'SIAGA', score: 20, from: 'emerald-500', to: 'teal-600' };

    const aiLev = document.getElementById('aiPredictedLevel') || document.getElementById('ai_level_air');
    if (aiLev) aiLev.textContent = d.level;

    const aiStat = document.getElementById('aiPredictedStatus') || document.getElementById('ai_status_keamanan');
    if (aiStat) aiStat.textContent = d.status;

    const aiRisk = document.getElementById('aiRiskScore') || document.getElementById('ai_risk_score');
    if (aiRisk) aiRisk.textContent = d.score;

    const card = document.getElementById('aiInsightsCard');
    if (card) {
        card.className = `bg-gradient-to-br from-${d.from} to-${d.to} rounded-3xl p-6 shadow-md text-white flex flex-col justify-between relative overflow-hidden transition-all duration-500`;
    }
}