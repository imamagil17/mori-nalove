<x-app-layout>
    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Riwayat Ketinggian Air</h2>
                    <p class="text-slate-500 mt-2">Data log historis ketinggian air yang tercatat di dalam sistem.</p>
                </div>
                
                <a href="{{ route('admin.water_logs.create') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 transition-all transform hover:-translate-y-0.5 text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Data Manual
                </a>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3 shadow-sm">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white/80 backdrop-blur-xl border border-slate-200 shadow-xl shadow-slate-200/50 rounded-3xl p-6 overflow-hidden">
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr class="text-slate-600 text-sm">
                                <th class="py-4 px-6 font-bold">Nama Sungai</th>
                                <th class="py-4 px-6 font-bold">Tanggal</th>
                                <th class="py-4 px-6 font-bold">Waktu</th>
                                <th class="py-4 px-6 font-bold">Cuaca</th>
                                <th class="py-4 px-6 font-bold">Tinggi Air</th>
                                <th class="py-4 px-6 font-bold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse ($logs as $log)
                                @php
                                    // Ambil data dengan fallback agar aman jika properti DB berbeda
                                    $sungai = $log->nama_sungai ?? 'Sungai Gumbasa';
                                    
                                    $timeObj = null;
                                    if (isset($log->recorded_at)) {
                                        $timeObj = \Carbon\Carbon::parse($log->recorded_at);
                                    } elseif (isset($log->waktu_rekaman)) {
                                        $timeObj = \Carbon\Carbon::parse($log->waktu_rekaman);
                                    } else {
                                        $timeObj = $log->created_at;
                                    }
                                    
                                    $tanggal = $timeObj->format('d M Y');
                                    $waktu = $timeObj->format('H:i') . ' WITA';
                                    
                                    $level = $log->water_level ?? $log->nilai_level ?? 0;
                                    
                                    // Hitung status secara dinamis jika status_kondisi tidak didefinisikan
                                    $status = 'Normal';
                                    $colorClass = 'text-emerald-600';
                                    
                                    if (isset($log->status_kondisi)) {
                                        $status = $log->status_kondisi;
                                    } else {
                                        if ($level >= 80) {
                                            $status = 'Bahaya';
                                        } elseif ($level >= 50) {
                                            $status = 'Siaga';
                                        } else {
                                            $status = 'Normal';
                                        }
                                    }
                                    
                                    $statusUpper = strtoupper($status);
                                    if ($statusUpper === 'BAHAYA' || $statusUpper === 'AWAS') {
                                        $colorClass = 'text-red-600';
                                    } elseif ($statusUpper === 'SIAGA' || $statusUpper === 'WASPADA') {
                                        $colorClass = 'text-orange-500';
                                    } else {
                                        $colorClass = 'text-emerald-600';
                                    }
                                    
                                    // Simulasi Cuaca dinamis berdasarkan level air
                                    $cuaca = 'Cerah';
                                    $icon = 'sun';
                                    $iconColor = 'text-amber-500';
                                    
                                    if (in_array($statusUpper, ['BAHAYA', 'AWAS', 'SIAGA', 'WASPADA'])) {
                                        $cuaca = 'Hujan Deras';
                                        $icon = 'cloud-lightning';
                                        $iconColor = 'text-indigo-500';
                                    } else {
                                        $cuaca = 'Berawan';
                                        $icon = 'cloud-sun';
                                        $iconColor = 'text-slate-400';
                                    }
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="py-4 px-6 text-blue-600 font-bold hover:underline cursor-pointer">
                                        @if($log->image_path)
                                            <a href="{{ asset('storage/' . $log->image_path) }}" target="_blank">
                                                {{ $sungai }} 📸
                                            </a>
                                        @else
                                            {{ $sungai }}
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-slate-500 font-medium">
                                        {{ $tanggal }}
                                    </td>
                                    <td class="py-4 px-6 text-slate-500 font-medium">
                                        {{ $waktu }}
                                    </td>
                                    <td class="py-4 px-6 text-slate-600 font-medium">
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="{{ $icon }}" class="w-4 h-4 {{ $iconColor }}"></i>
                                            <span>{{ $cuaca }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 font-bold text-slate-700">
                                        {{ $level }}{{ isset($log->water_level) ? ' %' : ' cm' }}
                                    </td>
                                    <td class="py-4 px-6 font-extrabold uppercase tracking-wide {{ $colorClass }}">
                                        {{ $status }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-16 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <svg class="w-14 h-14 mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                            </svg>
                                            <span class="font-bold text-base text-slate-700">Belum Ada Data Sensor</span>
                                            <span class="text-sm text-slate-400 mt-1">Kamera OpenCV belum mengirimkan log ketinggian air ke database.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $logs->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>