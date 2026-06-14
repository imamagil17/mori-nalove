<x-app-layout>
    @section('title', 'Log Notifikasi')
    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Log Notifikasi Darurat</h2>
                    <p class="text-slate-500 mt-2">Pantau riwayat pesan peringatan dini yang dikirim otomatis oleh sistem ke grup Telegram warga.</p>
                </div>
                
                {{-- Form Uji Tembak Simulasi --}}
                <form action="{{ route('admin.notifications.test') }}" method="POST" class="w-full lg:w-auto bg-white/40 backdrop-blur-md border border-white/50 rounded-2xl p-4 flex flex-col sm:flex-row items-end gap-3.5 shadow-sm shrink-0 transition-all duration-300 hover:bg-white/60 hover:shadow-md">
                    @csrf
                    <div class="w-full sm:w-44">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Pilih Sungai</label>
                        <div class="relative">
                            <select name="sungai" id="river_select" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-3 pr-8 py-2 text-xs font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 cursor-pointer appearance-none transition-all" required>
                                <option value="" disabled selected>Pilih Sungai</option>
                                <option value="Sungai Gumbasa">Sungai Gumbasa</option>
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
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="w-full sm:w-36">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tingkat Status</label>
                        <div class="relative">
                            <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-3 pr-8 py-2 text-xs font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 cursor-pointer appearance-none transition-all" required>
                                <option value="Normal" selected>Siaga (Aman)</option>
                                <option value="Siaga">Waspada</option>
                                <option value="Bahaya">Awas (Darurat)</option>
                            </select>
                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-500/10 hover:shadow-lg transition-all transform hover:-translate-y-0.5 shrink-0 h-[36px]">
                        <svg class="w-4 h-4 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
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

            {{-- Tabel Riwayat Rekap Log --}}
            <div class="bg-white/40 backdrop-blur-md border border-white/50 rounded-3xl p-6 shadow-sm transition-all duration-300 hover:bg-white/60 hover:shadow-md overflow-hidden">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6">
                    <h3 class="text-xl font-bold text-slate-800">Riwayat Broadcast Sistem</h3>
                    
                    <div class="relative w-full sm:w-60">
                        <select onchange="window.location.href = this.value ? '?sungai=' + encodeURIComponent(this.value) : '?'" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-4 pr-10 py-2.5 text-xs font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 cursor-pointer appearance-none transition-all">
                            <option value="">Semua Sungai</option>
                            <option value="Sungai Gumbasa" {{ request('sungai') == 'Sungai Gumbasa' ? 'selected' : '' }}>Sungai Gumbasa</option>
                            <option value="Sungai Lariang" {{ request('sungai') == 'Sungai Lariang' ? 'selected' : '' }}>Sungai Lariang</option>
                            <option value="Sungai Lindu" {{ request('sungai') == 'Sungai Lindu' ? 'selected' : '' }}>Sungai Lindu</option>
                            <option value="Sungai Samba" {{ request('sungai') == 'Sungai Samba' ? 'selected' : '' }}>Sungai Samba</option>
                            <option value="Sungai Pakuli" {{ request('sungai') == 'Sungai Pakuli' ? 'selected' : '' }}>Sungai Pakuli</option>
                            <option value="Sungai Marawola" {{ request('sungai') == 'Sungai Marawola' ? 'selected' : '' }}>Sungai Marawola</option>
                            <option value="Sungai Palolo" {{ request('sungai') == 'Sungai Palolo' ? 'selected' : '' }}>Sungai Palolo</option>
                            <option value="Sungai Kulawi" {{ request('sungai') == 'Sungai Kulawi' ? 'selected' : '' }}>Sungai Kulawi</option>
                            <option value="Sungai Ngatabaru" {{ request('sungai') == 'Sungai Ngatabaru' ? 'selected' : '' }}>Sungai Ngatabaru</option>
                            <option value="Sungai Wuno" {{ request('sungai') == 'Sungai Wuno' ? 'selected' : '' }}>Sungai Wuno</option>
                            <option value="Sungai Bangga" {{ request('sungai') == 'Sungai Bangga' ? 'selected' : '' }}>Sungai Bangga</option>
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto rounded-xl border border-white/40">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-white/20 border-b border-white/40">
                            <tr class="text-slate-800 text-sm">
                                <th class="py-4 px-6 text-slate-800 font-black text-xs uppercase tracking-wider w-1/4">Waktu Penyiaran</th>
                                <th class="py-4 px-6 text-slate-800 font-black text-xs uppercase tracking-wider w-1/2">Isi Pesan Peringatan</th>
                                <th class="py-4 px-6 text-slate-800 font-black text-xs uppercase tracking-wider w-1/4 text-center">Status Keamanan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse ($logs as $log)
                                @php
                                    $currentStatus = strtoupper($log->status_kondisi);
                                @endphp
                                <tr class="hover:bg-white/40 transition-colors duration-150 group">
                                    <td class="py-4 px-6 text-slate-500 font-medium">
                                        {{ $log->created_at->format('d M Y, H:i') }} WITA
                                    </td>
                                    <td class="py-4 px-6 text-slate-700 font-medium whitespace-pre-line leading-relaxed">
                                        @if($currentStatus === 'NORMAL' || $currentStatus === 'AMAN')
                                            🟢 <span class="font-extrabold text-emerald-600">[SIAGA - {{ $log->nama_sungai }}]</span>
                                            Pemantauan otomatis model YOLO & OpenCV berjalan dengan normal. Kondisi aliran sungai saat ini terpantau aman di bawah ambang batas.
                                        @elseif($currentStatus === 'SIAGA')
                                            🟡 <span class="font-extrabold text-amber-500">[WASPADA - {{ $log->nama_sungai }}]</span>
                                            Sistem Mori Nalove mendeteksi adanya kenaikan volume air sungai melewati batas wajar pada titik pantau aktif.
                                        @else
                                            🚨 🔴 <span class="font-extrabold text-rose-600">[AWAS - {{ $log->nama_sungai }}]</span>
                                            Sistem Mori Nalove mendeteksi lonjakan ekstrem debit air yang berpotensi kuat memicu luapan banjir besar di area pemukiman sekitar.
                                        @endif
                                        
                                        <div class="mt-2 pt-2 border-t border-slate-100/60 text-xs text-slate-500 space-y-0.5">
                                            <p>• Waktu Deteksi: {{ \Carbon\Carbon::parse($log->waktu_rekaman)->format('H:i') }} WITA</p>
                                            <p>• Ketinggian Air: <span class="font-bold text-slate-700">{{ $log->nilai_level }} cm</span></p>
                                        </div>

                                        @if($currentStatus === 'SIAGA')
                                            <p class="mt-2 text-xs text-amber-700 bg-amber-50 rounded-lg p-2 border border-amber-100">⚠️ *HIMBAUAN KEAMANAN:* Warga yang beraktivitas di sekitar sempadan aliran diminta meningkatkan kewaspadaan dan mengamankan barang berharga.</p>
                                        @elseif($currentStatus !== 'NORMAL' && $currentStatus !== 'AMAN')
                                            <p class="mt-2 text-xs text-rose-700 bg-rose-50 rounded-lg p-2 border border-rose-100">🚨 *PERINTAH EVAKUASI:* Warga di sepanjang bantaran aliran diwajibkan segera mengungsi ke titik aman utama dan mengikuti instruksi tim evakuasi lapangan.</p>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        @if($currentStatus === 'NORMAL' || $currentStatus === 'AMAN')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 shadow-sm border border-emerald-200">
                                                SIAGA
                                            </span>
                                        @elseif($currentStatus === 'SIAGA')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 shadow-sm border border-amber-200">
                                                WASPADA
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 shadow-sm border border-rose-200 animate-pulse">
                                                AWAS
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-16 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <svg class="w-14 h-14 mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                            <span class="font-bold text-base text-slate-700">Belum Ada Log Broadcast</span>
                                            <span class="text-sm text-slate-400 mt-1">Belum ada riwayat aktivitas pengiriman pesan sistem yang tercatat.</span>
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