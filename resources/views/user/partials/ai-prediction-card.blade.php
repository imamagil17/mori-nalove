<div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl p-4 md:p-6 shadow-md text-white flex flex-col justify-between relative overflow-hidden transition-colors duration-500" id="aiInsightsCard">
    <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
    
    <div id="aiSkeleton" class="animate-pulse flex flex-col h-full justify-between">
        <div class="flex flex-col sm:flex-row sm:justify-between mb-4 gap-2 sm:gap-0">
            <div class="h-6 bg-white/20 rounded w-1/2"></div>
            <div class="h-6 bg-white/20 rounded w-16"></div>
        </div>
        <div class="flex flex-col sm:flex-row sm:justify-between items-start sm:items-end gap-2 sm:gap-0">
            <div class="h-12 bg-white/20 rounded w-20"></div>
            <div class="h-16 w-16 bg-white/20 rounded-full"></div>
        </div>
    </div>

    <div id="aiContent" class="hidden h-full flex flex-col justify-between relative z-10">
        <div class="flex flex-col sm:flex-row sm:justify-between items-start gap-2 sm:gap-0">
            <h3 class="text-sm font-semibold text-white/80 tracking-wider uppercase flex items-center gap-2">
                <i data-lucide="brain-circuit" class="w-4 h-4"></i> AI Prediction
            </h3>
            <span class="bg-white/20 px-2 py-1 rounded text-xs font-bold backdrop-blur-sm">30 MIN AHEAD</span>
        </div>
        <div class="flex flex-col sm:flex-row sm:justify-between items-start sm:items-end mt-4 gap-4 sm:gap-0">
            <div>
                <p class="text-white/80 text-xs font-medium mb-1 uppercase tracking-wider">Level Air</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-5xl font-black drop-shadow-sm" id="aiPredictedLevel">--</span>
                    <span class="text-xl font-bold text-white/70">%</span>
                </div>
                <p class="text-sm font-bold mt-1 px-2 py-0.5 bg-white/20 rounded inline-block truncate max-w-full" id="aiPredictedStatus">Memuat...</p>
            </div>
            <div class="text-left sm:text-right flex flex-col items-start sm:items-center">
                <p class="text-white/80 text-xs font-medium mb-2 uppercase tracking-wider">Risk Score</p>
                <div class="w-16 h-16 rounded-full border-4 border-white/30 flex items-center justify-center relative bg-white/10 backdrop-blur-md shadow-inner">
                    <span class="text-xl font-black drop-shadow-sm" id="aiRiskScore">--</span>
                </div>
            </div>
        </div>
    </div>
</div>
