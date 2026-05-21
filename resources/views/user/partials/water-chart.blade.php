<div class="bg-white/40 backdrop-blur-md rounded-3xl p-4 md:p-6 shadow-sm border border-white/40 flex flex-col h-[350px] md:h-96 relative group mt-8">
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
