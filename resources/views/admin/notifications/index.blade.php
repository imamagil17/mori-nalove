<x-app-layout>
    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Log Notifikasi Darurat</h2>
                    <p class="text-slate-500 mt-2">Pantau riwayat pesan peringatan dini yang dikirim otomatis oleh sistem ke grup Telegram warga.</p>
                </div>
                
                <form action="{{ route('admin.notifications.test') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 transition-all transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        Uji Tembak Telegram
                    </button>
                </form>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3 shadow-sm">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-rose-100 border border-rose-200 text-rose-700 rounded-xl flex items-center gap-3 shadow-sm">
                    <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2 m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white/80 backdrop-blur-xl border border-slate-200 shadow-xl shadow-slate-200/50 rounded-3xl p-6 overflow-hidden">
                <h3 class="text-xl font-bold text-slate-800 mb-6">Riwayat Broadcast Sistem</h3>
                
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr class="text-slate-600 text-sm">
                                <th class="py-4 px-6 font-bold w-1/4">Waktu Penyiaran</th>
                                <th class="py-4 px-6 font-bold w-1/2">Isi Pesan Peringatan</th>
                                <th class="py-4 px-6 font-bold w-1/4 text-center">Status API</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse ($logs as $log)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="py-4 px-6 text-slate-500 font-medium">
                                        {{ $log->created_at->format('d M 2026, H:i') }} WITA
                                    </td>
                                    <td class="py-4 px-6 text-slate-700 font-medium whitespace-pre-line leading-relaxed">
                                        {{ $log->message }}
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $log->status == 'Terkirim ✅' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                            {{ $log->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-16 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <svg class="w-14 h-14 mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                            <span class="font-bold text-base text-slate-700">Belum Ada Log Penyiaran</span>
                                            <span class="text-sm text-slate-400 mt-1">Sistem belum pernah mengirimkan pesan darurat otomatis.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>