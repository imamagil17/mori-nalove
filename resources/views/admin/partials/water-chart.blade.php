<div
    class="bg-white/40 backdrop-blur-md border border-white/30 rounded-3xl p-4 sm:p-6 shadow-sm transition-all duration-300 hover:bg-white/60 hover:shadow-md flex flex-col h-full flex-grow relative group w-full min-h-[480px]">

    <div class="flex flex-col gap-4 mb-6 shrink-0 w-full">

        <div
            class="flex flex-col lg:flex-row lg:items-center lg:justify-between w-full border-b border-slate-100 pb-3 gap-3">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-100/80 text-indigo-600 rounded-xl shrink-0">
                    <i data-lucide="activity" class="w-5 h-5"></i>
                </div>
                <h3 class="text-base font-black text-slate-800 tracking-tight whitespace-nowrap">Tren Ketinggian Air</h3>
            </div>

            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-[11px] font-semibold text-slate-500">
                <span class="flex items-center gap-1.5"><span class="w-3 h-0.5 bg-red-500 rounded inline-block"></span>
                    Batas Bahaya (90%)</span>
                <span class="flex items-center gap-1.5"><span
                        class="w-3 h-0.5 bg-orange-500 rounded inline-block"></span> Batas Siaga (80%)</span>
                <span class="flex items-center gap-1.5"><span
                        class="w-3 h-0.5 bg-emerald-500 rounded inline-block"></span> Batas Normal (60%)</span>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between w-full gap-3">
            <span class="text-xs font-medium text-slate-400">Gunakan filter untuk memantau data historis sungai</span>

            <div class="flex items-center gap-2.5 w-full sm:w-auto justify-between sm:justify-end">
                <div class="relative flex-grow sm:flex-grow-0 w-full sm:w-56">
                    <select id="riverSelect" onchange="muatDataAI(this.value);"
                        class="w-full bg-white border border-slate-200/80 shadow-sm rounded-xl pl-4 pr-10 py-2.5 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 cursor-pointer appearance-none transition-all hover:border-indigo-300">
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
                        <div class="p-1 bg-slate-50 rounded-lg">
                            <i data-lucide="chevrons-up-down" class="w-3.5 h-3.5"></i>
                        </div>
                    </div>
                </div>

                <button onclick="refreshDataAI()"
                    class="px-4 py-2.5 text-xs font-bold bg-white border border-slate-200/80 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 rounded-xl flex items-center gap-2 transition-all shadow-sm shrink-0 active:scale-95">
                    <i data-lucide="refresh-cw" class="w-4 h-4" id="refreshIcon"></i>
                    <span class="hidden sm:inline-block">Refresh Data</span>
                </button>
            </div>
        </div>
    </div>

    <div id="chartSkeleton"
        class="absolute inset-x-6 top-36 bottom-8 animate-pulse flex items-end justify-between gap-2 pb-8 z-0">
        <div class="w-full bg-slate-200 rounded-t h-1/4"></div>
        <div class="w-full bg-slate-200 rounded-t h-1/3"></div>
        <div class="w-full bg-slate-200 rounded-t h-1/2"></div>
        <div class="w-full bg-slate-200 rounded-t h-3/4"></div>
        <div class="w-full bg-slate-200 rounded-t h-2/3"></div>
        <div class="w-full bg-slate-200 rounded-t h-1/4"></div>
        <div class="w-full bg-slate-200 rounded-t h-1/5"></div>
        <div class="w-full bg-slate-200 rounded-t h-2/5"></div>
    </div>

    <div class="w-full flex-grow relative opacity-0 transition-opacity duration-500 z-10 h-[320px] sm:h-[380px] xl:h-full"
        id="chartContainer">
        <div class="w-full h-full pb-2 min-h-[300px]">
            <canvas id="waterChart" class="w-full h-full"></canvas>
        </div>
    </div>
</div>
