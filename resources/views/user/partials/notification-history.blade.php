<div class="bg-white/40 backdrop-blur-md border border-white/30 rounded-3xl p-4 sm:p-6 shadow-sm transition-all duration-300 hover:bg-white/60 hover:shadow-md relative overflow-hidden w-full">
    <div class="flex items-center gap-3 mb-5">
        <div class="p-2 bg-rose-100/80 text-rose-600 rounded-xl">
            <i data-lucide="bell-ring" class="w-5 h-5"></i>
        </div>
        <h3 class="text-lg font-black text-slate-800 tracking-tight">Riwayat Peringatan Sistem</h3>
    </div>
    
    <div class="space-y-4 max-h-[350px] overflow-y-auto pr-1.5 custom-history-scroll" id="notificationContainer">
        <div class="flex justify-center items-center py-8 text-slate-400">
            <i data-lucide="loader-2" class="w-6 h-6 animate-spin"></i>
            <span class="ml-2 text-sm font-medium">Memuat riwayat peringatan...</span>
        </div>
    </div>
</div>

<style>
    .custom-history-scroll::-webkit-scrollbar {
        width: 4px; /* Lebar scrollbar dibuat tipis (4 pixel) */
    }
    .custom-history-scroll::-webkit-scrollbar-track {
        background: transparent; /* Latar belakang track kosong transparan */
    }
    .custom-history-scroll::-webkit-scrollbar-thumb {
        background: rgba(203, 213, 225, 0.6); /* Warna slate-300 tipis */
        border-radius: 9999px; /* Membuat ujung scrollbar membulat halus */
    }
    .custom-history-scroll::-webkit-scrollbar-thumb:hover {
        background: rgba(148, 163, 184, 0.8); /* Warna berubah sedikit lebih tegas saat kursor menyentuh scrollbar */
    }
</style>