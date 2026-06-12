<div
    class="bg-white/60 backdrop-blur-xl border border-white/50 rounded-[2rem] p-5 sm:p-7 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-500 flex flex-col h-full flex-grow relative group w-full min-h-[480px] overflow-hidden">

    <!-- Efek Cahaya Latar (Glow) -->
    <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-50/50 rounded-full blur-3xl pointer-events-none"></div>

    <div class="flex flex-col gap-5 mb-6 shrink-0 w-full relative z-10">
        <!-- Header & Legend -->
        <div
            class="flex flex-col xl:flex-row xl:items-center xl:justify-between w-full border-b border-slate-200/60 pb-4 gap-4">
            <div class="flex items-center gap-3.5">
                <div
                    class="p-2.5 bg-gradient-to-br from-indigo-100 to-blue-100 text-indigo-600 rounded-xl shrink-0 shadow-sm border border-white">
                    <i data-lucide="activity" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-800 tracking-tight whitespace-nowrap">Tren Ketinggian Air
                    </h3>
                    <p class="text-[11px] font-semibold text-slate-400 mt-0.5">Analisis Sentinel YOLOv26 Real-time</p>
                </div>
            </div>

            <!-- Legend Batas Air (Lebih Estetik) -->
            <div
                class="flex flex-wrap items-center gap-x-5 gap-y-2 bg-slate-50/80 px-4 py-2 rounded-xl border border-slate-100">
                <span class="flex items-center gap-2 text-[11px] font-bold text-slate-600">
                    <span class="relative flex h-2.5 w-2.5">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                    </span>
                    Bahaya (90%)
                </span>
                <span class="flex items-center gap-2 text-[11px] font-bold text-slate-600">
                    <span class="relative flex h-2.5 w-2.5">
                        <span
                            class="relative inline-flex rounded-full h-2.5 w-2.5 bg-orange-500 border border-white"></span>
                    </span>
                    Siaga (80%)
                </span>
                <span class="flex items-center gap-2 text-[11px] font-bold text-slate-600">
                    <span class="relative flex h-2.5 w-2.5">
                        <span
                            class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500 border border-white"></span>
                    </span>
                    Normal (60%)
                </span>
            </div>
        </div>

        <!-- Filter Controls -->
        <div
            class="flex flex-col sm:flex-row sm:items-center sm:justify-between w-full gap-4 bg-white/40 p-1.5 rounded-2xl">
            <span class="text-xs font-bold text-slate-400 pl-2 hidden sm:block">Filter Historis:</span>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">
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

    <!-- Animasi Skeleton / Loading -->
    <div id="chartSkeleton"
        class="absolute inset-x-6 top-48 bottom-8 animate-pulse flex items-end justify-between gap-3 pb-8 z-0 px-4">
        <div class="w-full bg-gradient-to-t from-slate-200/80 to-transparent rounded-t-xl h-[20%]"></div>
        <div class="w-full bg-gradient-to-t from-slate-200/80 to-transparent rounded-t-xl h-[35%]"></div>
        <div class="w-full bg-gradient-to-t from-slate-200/80 to-transparent rounded-t-xl h-[50%]"></div>
        <div class="w-full bg-gradient-to-t from-slate-200/80 to-transparent rounded-t-xl h-[80%]"></div>
        <div class="w-full bg-gradient-to-t from-slate-200/80 to-transparent rounded-t-xl h-[65%]"></div>
        <div class="w-full bg-gradient-to-t from-slate-200/80 to-transparent rounded-t-xl h-[30%]"></div>
        <div class="w-full bg-gradient-to-t from-slate-200/80 to-transparent rounded-t-xl h-[40%]"></div>
    </div>

    <!-- Container Grafik -->
    <div class="w-full flex-grow relative opacity-0 transition-opacity duration-700 z-10 h-[320px] sm:h-[380px] xl:h-full bg-slate-50/30 rounded-2xl p-2 border border-slate-100/50"
        id="chartContainer">
        <div class="w-full h-full pb-2 min-h-[300px]">
            <canvas id="waterChart" class="w-full h-full"></canvas>
        </div>
    </div>
</div>
