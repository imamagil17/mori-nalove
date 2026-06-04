<div class="bg-white/70 backdrop-blur-lg border border-white/50 shadow-[0_8px_30px_rgb(0,0,0,0.04)] rounded-3xl p-6 flex flex-col h-full flex-grow relative group w-full">
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@2.0.1/dist/chartjs-plugin-annotation.min.js"></script>

    <div class="flex flex-col gap-4 mb-6 shrink-0 w-full">
        <div class="flex flex-row items-center justify-between w-full border-b border-slate-100 pb-2">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-100 text-indigo-600 rounded-xl shrink-0">
                    <i data-lucide="activity" class="w-5 h-5"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800 tracking-tight whitespace-nowrap">Tren Ketinggian Air</h3>
            </div>
            
            <div class="flex items-center gap-4 whitespace-nowrap text-[11px] font-semibold text-slate-500">
                <span class="flex items-center gap-1.5"><span class="w-3 h-0.5 bg-red-500 rounded inline-block"></span> Batas Bahaya (90%)</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-0.5 bg-orange-500 rounded inline-block"></span> Batas Siaga (80%)</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-0.5 bg-emerald-500 rounded inline-block"></span> Batas Normal (60%)</span>
            </div>
        </div>
        
        <div class="flex flex-row items-center justify-between w-full">
            <span class="text-xs font-medium text-slate-400">Gunakan filter untuk memantau data historis sungai</span>
            <div class="flex items-center gap-3">
                <div class="relative w-44 sm:w-48">
                    <select id="riverSelect" onchange="updateChartByRiver(this.value)" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-3 pr-8 py-1.5 text-xs font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 cursor-pointer appearance-none transition-all">
                        <option value="Sungai Gumbasa" selected>Sungai Gumbasa</option>
                        <option value="Sungai Lariang">Sungai Lariang</option>
                        <option value="Sungai Lindu">Sungai Lindu</option>
                        <option value="Sungai Samba">Sungai Samba</option>
                        <option value="Sungai Pakuli">Sungai Pakuli</option>
                        <option value="Sungai Marawola">Sungai Marawola</option>
                        <option value="Sungai Palolo">Sungai Palolo</option>
                        <option value="Sungai Kulawi">Sungai Kulawi</option>
                        <option value="Sungai Ngatabaru">Sungai Ngatabaru</option>
                        <option value="Sungai Wuno">Sungai Wuno</option>
                        <option value="Sungai Bangga">Sungai Bangga</option>
                    </select>
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>

                <button onclick="fetchDashboardData()" class="px-3 py-1.5 text-xs font-bold bg-slate-50 border border-slate-200 text-slate-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl flex items-center gap-1.5 transition-colors shrink-0">
                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5" id="refreshIcon"></i>
                    <span>Refresh</span>
                </button>
            </div>
        </div>
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

    <div class="w-full h-full relative opacity-0 transition-opacity duration-500" id="chartContainer">
        <div class="w-full h-full min-h-[350px]">
            <canvas id="waterChart" class="w-full h-full"></canvas>
        </div>
    </div>
</div>