<div class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
    <div id="chatWindow" class="hidden w-[calc(100vw-3rem)] sm:w-80 lg:w-96 h-[28rem] mb-4 bg-white/80 backdrop-blur-xl shadow-2xl rounded-3xl border border-white/60 flex-col overflow-hidden transition-all duration-300 transform origin-bottom-right">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-4 text-white flex justify-between items-center shadow-md">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 p-1.5 rounded-lg backdrop-blur-sm">
                    <i data-lucide="bot" class="w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm leading-tight">Flood Vision Assistant</h4>
                    <p class="text-[10px] text-blue-100 font-medium tracking-wider uppercase">Sistem Mitigasi Cerdas</p>
                </div>
            </div>
            <button onclick="toggleChat()" class="text-white/70 hover:text-white transition-colors bg-white/10 hover:bg-white/20 p-1.5 rounded-lg">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        
        <div id="chatMessages" class="flex-1 p-5 overflow-y-auto bg-slate-50/50 custom-scrollbar">
            <div class="flex gap-3 mb-4">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center shrink-0 shadow-sm">
                    <i data-lucide="sparkles" class="w-4 h-4 text-white"></i>
                </div>
                <div class="bg-white p-3.5 rounded-2xl rounded-tl-none shadow-sm text-sm text-slate-700 border border-slate-100 max-w-[85%] leading-relaxed">
                    Halo! Saya AI Flood Vision. Ada yang bisa saya bantu terkait informasi mitigasi atau evakuasi hari ini?
                </div>
            </div>
        </div>
        
        <div class="p-3 bg-white border-t border-slate-100/60 backdrop-blur-md">
            <form class="flex gap-2" onsubmit="sendChatMessage(event)">
                <input type="text" id="chatInput" class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" placeholder="Tanya sesuatu..." autocomplete="off">
                <button type="submit" id="chatSubmit" class="bg-blue-600 hover:bg-blue-700 text-white p-2.5 rounded-xl transition-colors shadow-md hover:shadow-lg flex items-center justify-center disabled:opacity-50">
                    <i data-lucide="send" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>
    <button onclick="toggleChat()" class="w-14 h-14 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full shadow-xl flex items-center justify-center text-white hover:scale-105 transition-transform border-2 border-white/50 group relative">
        <div class="absolute inset-0 rounded-full animate-ping bg-blue-400 opacity-20"></div>
        <i data-lucide="message-square-text" class="w-6 h-6 group-hover:hidden"></i>
        <i data-lucide="chevron-up" class="w-6 h-6 hidden group-hover:block"></i>
    </button>
</div>
