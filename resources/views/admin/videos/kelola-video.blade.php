<x-app-layout>
    @section('title', 'Kelola Video')
    
    <div class="py-8 relative min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8 pb-20">
            
            @if (session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3 text-sm font-semibold shadow-sm animate-[slideDown_0.2s_ease-out]">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl flex items-center gap-3 text-sm font-semibold shadow-sm animate-[slideDown_0.2s_ease-out]">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-6 pb-2">
                <div class="space-y-1">
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                        <span class="p-2.5 bg-amber-500/10 text-amber-600 rounded-2xl backdrop-blur-sm">
                            <i data-lucide="video" class="w-5 h-5"></i>
                        </span>
                        Pusat Kendali Dokumentasi Video
                    </h2>
                    <p class="text-slate-500 font-medium text-sm max-w-2xl">
                        Unggah video pemantauan banjir atau perbarui konten edukasi bencana warga untuk sistem Mori Nalove.
                    </p>
                </div>

                <div class="bg-white/40 backdrop-blur-md border border-white/50 rounded-2xl p-3 flex items-center gap-4 shadow-sm w-fit shrink-0">
                    <div class="flex items-center gap-2 px-3 border-r border-slate-300/50">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                        <span class="text-xs font-black text-slate-700 uppercase tracking-wider">Feed Pemantauan</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-5 gap-8 items-start">
                
                <div class="xl:col-span-2 bg-white/40 backdrop-blur-md border border-white/50 rounded-3xl p-6 shadow-sm transition-all duration-300 hover:bg-white/50 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-48 h-48 bg-amber-50/30 rounded-full blur-3xl -z-10"></div>
                    
                    <div class="flex items-center gap-2.5 border-b border-slate-200/40 pb-4 mb-5">
                        <div class="p-2 bg-amber-100/80 text-amber-600 rounded-xl"><i data-lucide="plus-circle" class="w-4 h-4"></i></div>
                        <h3 class="text-base font-black text-slate-800 tracking-tight">Sematkan Tautan Pemantauan</h3>
                    </div>

                    <div class="relative z-10 text-slate-700">
                        @include('admin.partials.form-upload')
                    </div>
                </div>

                <div class="xl:col-span-3 bg-white/40 backdrop-blur-md border border-white/50 rounded-3xl p-6 shadow-sm transition-all duration-300 hover:bg-white/50 space-y-5">
                    
                    <div class="flex items-center justify-between border-b border-slate-200/40 pb-4">
                        <div class="flex items-center gap-2.5">
                            <div class="p-2 bg-indigo-100/80 text-indigo-600 rounded-xl"><i data-lucide="clapperboard" class="w-4 h-4"></i></div>
                            <h3 class="text-base font-black text-slate-800 tracking-tight">Koleksi Video Tersemat</h3>
                        </div>
                        <span class="text-xs font-bold text-slate-500 bg-white/60 px-3 py-1.5 rounded-xl border border-slate-200/40 shadow-sm">
                            Gumbasa & Lindu Player
                        </span>
                    </div>

                    <div class="text-slate-700">
                        @include('admin.partials.list-video')
                    </div>
                </div>
                
            </div>

        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
    
    <script src="{{ asset('js/kelola-video.js') }}"></script>
</x-app-layout>