window.globalContext = "Level: --, Status: --, Risk Score: --";

async function fetchNotificationHistory() {
    try {
        const res = await fetch('/api/notifications');
        const json = await res.json();
        
        const container = document.getElementById('notificationContainer');
        if (!container) return;
        
        if (json.success && json.data.length > 0) {
            container.innerHTML = json.data.map(item => `
                <div class="flex items-start gap-3 p-3 bg-slate-50/60 rounded-2xl border border-slate-100">
                    <div class="p-2 bg-rose-100 text-rose-600 rounded-xl mt-0.5">
                        <i data-lucide="bell" class="w-4 h-4"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-1">
                            <h4 class="font-bold text-sm text-slate-800">${item.title || 'Peringatan Sistem'}</h4>
                            <span class="text-xs text-slate-400">${new Date(item.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed whitespace-pre-line">${item.message || item.keterangan || ''}</p>
                    </div>
                </div>
            `).join('');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        } else {
            container.innerHTML = `
                <div class="flex justify-center items-center py-4 text-slate-400">
                    <i data-lucide="info" class="w-6 h-6"></i>
                    <span class="ml-2 text-sm font-medium">Belum ada riwayat peringatan.</span>
                </div>
            `;
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    } catch(e) {
        console.error('Error fetching notification history:', e);
    }
}

async function fetchDashboardData() {
    const refreshIcon = document.getElementById('refreshIcon');
    if(refreshIcon) refreshIcon.classList.add('animate-spin');

    let weatherDesc = '';
    try {
        const weatherRes = await fetch('/api/weather');
        const weatherJson = await weatherRes.json();
        if(weatherJson.success) {
            const d = weatherJson.data;
            const tempEl = document.getElementById('weatherTemp');
            if(tempEl) tempEl.innerText = d.temp + '°';
            const descEl = document.getElementById('weatherDesc');
            if(descEl) descEl.innerText = d.description;
            const cityEl = document.getElementById('weatherCity');
            if(cityEl) cityEl.innerText = d.city;
            const humEl = document.getElementById('weatherHumidity');
            if(humEl) humEl.innerText = d.humidity;
            weatherDesc = d.description;
            
            const wSkel = document.getElementById('weatherSkeleton');
            if(wSkel) wSkel.classList.add('hidden');
            const wCont = document.getElementById('weatherContent');
            if(wCont) wCont.classList.remove('hidden');
        }
    } catch(e) { console.error('Error fetching weather:', e); }

    try {
        const aiRes = await fetch('/api/analytics');
        const aiJson = await aiRes.json();
        if(aiJson.success) {
            const d = aiJson.data;
            const aiLev = document.getElementById('aiPredictedLevel');
            if(aiLev) aiLev.innerText = d.predicted_level;
            const aiStat = document.getElementById('aiPredictedStatus');
            if(aiStat) aiStat.innerText = d.prediction_status;
            const aiRisk = document.getElementById('aiRiskScore');
            if(aiRisk) aiRisk.innerText = d.risk_score;
            
            const card = document.getElementById('aiInsightsCard');
            if (card) {
                card.className = "bg-gradient-to-br rounded-3xl p-6 shadow-md text-white flex flex-col justify-between relative overflow-hidden transition-colors duration-500 ";
                if(d.risk_score > 70) {
                    card.className += "from-red-500 to-rose-700";
                } else if (d.risk_score > 35) {
                    card.className += "from-orange-500 to-amber-600";
                } else {
                    card.className += "from-emerald-500 to-teal-600";
                }
            }

            const aiSkel = document.getElementById('aiSkeleton');
            if(aiSkel) aiSkel.classList.add('hidden');
            const aiCont = document.getElementById('aiContent');
            if(aiCont) aiCont.classList.remove('hidden');

            window.globalContext = `Saat ini Level Air ${d.current_level}%. Prediksi 30 menit ke depan: ${d.predicted_level}% (${d.prediction_status}). Cuaca: ${weatherDesc}. Skor Risiko: ${d.risk_score}/100.`;

            if (typeof updateUserStatusPanel === 'function') {
                updateUserStatusPanel(d.current_level, d.prediction_status);
            }
        }
    } catch(e) { console.error('Error fetching analytics:', e); }

    try {
        const logsRes = await fetch('/api/logs');
        const logsJson = await logsRes.json();
        if(logsJson.data) {
            const chartSkel = document.getElementById('chartSkeleton');
            if(chartSkel) chartSkel.classList.add('hidden');
            const chartCont = document.getElementById('chartContainer');
            if(chartCont) chartCont.classList.remove('opacity-0');
            
            if (typeof updateChart === 'function') {
                updateChart(logsJson.data);
            }
        }
    } catch(e) { console.error('Error fetching logs:', e); }

    await fetchNotificationHistory();

    if(refreshIcon) setTimeout(() => refreshIcon.classList.remove('animate-spin'), 500);
}

document.addEventListener('DOMContentLoaded', () => {
    fetchDashboardData();
    setInterval(fetchDashboardData, 10000);
});
