<div class="bg-white/40 backdrop-blur-md border border-white/50 rounded-3xl p-4 sm:p-6 shadow-sm transition-all duration-300 hover:bg-white/60 hover:shadow-md flex flex-col h-full flex-grow relative overflow-hidden w-full pt-6 sm:pt-[24px]">
    
    {{-- Header Judul Riwayat --}}
    <div class="flex items-center justify-between mb-4 shrink-0 border-b border-slate-100/60 pb-3">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-rose-50 text-rose-600 rounded-xl shadow-sm border border-rose-100">
                <i data-lucide="bell-ring" class="w-4 h-4"></i>
            </div>
            <h3 class="text-base font-black text-slate-800 tracking-tight">Riwayat Peringatan Sistem</h3>
        </div>
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider bg-slate-100/80 px-2 py-0.5 rounded-md">Live Log</span>
    </div>
    
    {{-- 💡 SOLUSI UTAMA: flex-grow h-0 memaksa area scrollbar ditarik lurus menghabiskan sisa ruang bawah boks --}}
    <div class="flex-grow h-0 overflow-y-auto pr-1 custom-history-scroll space-y-3" id="notificationContainer">
        <div class="flex flex-col justify-center items-center h-full py-20 text-slate-400">
            <i data-lucide="loader-2" class="w-6 h-6 animate-spin text-indigo-500"></i>
            <span class="ml-2 text-xs font-bold text-slate-500 mt-2">Sinkronisasi Log Database...</span>
        </div>
    </div>
</div>

<style>
    /* Mengompres padding internal list boks agar tampilannya padat */
    #notificationContainer > div {
        margin-top: 0px !important;
        margin-bottom: 0px !important;
        padding-top: 12px !important;
        padding-bottom: 12px !important;
    }
    
    .custom-history-scroll::-webkit-scrollbar {
        width: 4px;
    }
    .custom-history-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-history-scroll::-webkit-scrollbar-thumb {
        background: rgba(203, 213, 225, 0.6);
        border-radius: 9999px;
    }
    .custom-history-scroll::-webkit-scrollbar-thumb:hover {
        background: rgba(148, 163, 184, 0.8);
    }
</style>

{{-- Script Pemotong Karakter Bintang Raw Markdown --}}
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const container = document.getElementById('notificationContainer');
        if (container) {
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.addedNodes.length) {
                        const walker = document.createTreeWalker(container, NodeFilter.SHOW_TEXT, null, false);
                        let textNode;
                        const nodesToReplace = [];

                        while (textNode = walker.nextNode()) {
                            if (textNode.nodeValue.includes('*')) {
                                nodesToReplace.push(textNode);
                            }
                        }

                        nodesToReplace.forEach(node => {
                            const span = document.createElement('span');
                            span.innerHTML = node.nodeValue.replace(/\*(.*?)\*/g, '<strong class="font-black text-slate-800">$1</strong>');
                            node.parentNode.replaceChild(span, node);
                        });
                    }
                });
            });
            observer.observe(container, { childList: true, subtree: true });
        }
    });
</script>