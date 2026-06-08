<div class="w-full h-screen relative overflow-hidden bg-slate-100 left-0 right-0">
    
    <div class="absolute top-6 left-1/2 -translate-x-1/2 z-[40] w-auto whitespace-nowrap p-3 bg-white/80 backdrop-blur-lg border border-slate-200/50 rounded-2xl shadow-xl shadow-slate-900/5 animate-[slideDown_0.3s_ease-out]">
        
        <div class="flex items-center gap-5 px-4 py-2 bg-slate-100/60 rounded-xl border border-slate-200/40 text-xs font-bold text-slate-600 shadow-inner">
            <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse ring-4 ring-emerald-500/20"></span> Normal</span>
            <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-amber-500 animate-pulse ring-4 ring-amber-500/20"></span> Siaga</span>
            <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-rose-500 animate-pulse ring-4 ring-rose-500/20"></span> Bahaya</span>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    
    <style>
        /* Desain Kontrol Zoom Premium bawaan Leaflet */
        .leaflet-touch .leaflet-bar {
            border: none !important;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1) !important;
            margin-left: 24px !important;
            margin-top: 100px !important; /* Biar gak tabrakan dengan header floating */
        }
        .leaflet-touch .leaflet-bar a {
            background-color: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(8px);
            color: #1e293b !important;
            border-bottom: 1px solid #f1f5f9 !important;
            width: 40px !important;
            height: 40px !important;
            line-height: 40px !important;
            transition: all 0.2s;
        }
        .leaflet-touch .leaflet-bar a:first-child {
            border-top-left-radius: 12px !important;
            border-top-right-radius: 12px !important;
        }
        .leaflet-touch .leaflet-bar a:last-child {
            border-bottom-left-radius: 12px !important;
            border-bottom-right-radius: 12px !important;
            border-bottom: none !important;
        }
        .leaflet-touch .leaflet-bar a:hover {
            background-color: #ffffff !important;
            color: #2563eb !important;
        }

        /* Styling Popup Modern Premium minimalis */
        .leaflet-popup-content-wrapper {
            border-radius: 1.5rem !important;
            padding: 6px !important;
            box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.15) !important;
            border: 1px solid rgba(241, 245, 249, 1) !important;
            background: #ffffff !important;
        }
        .leaflet-popup-tip {
            background: #ffffff !important;
            box-shadow: none !important;
        }
        .leaflet-popup-close-button {
            top: 14px !important;
            right: 14px !important;
            color: #94a3b8 !important;
            font-size: 16px !important;
        }
        .custom-div-icon {
            background: transparent !important;
            border: none !important;
        }
    </style>

    <div id="map" class="w-full h-full z-10"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Inisialisasi Peta Fullscreen KUNCI TOTAL AKSI ZOOM LIAR
            // 🌟 FIX: Tingkat zoom diatur ke 10 agar menjauh (zoom out) 1x dari sebelumnya
            const map = L.map('map', {
                zoomControl: true,
                scrollWheelZoom: false,  // KUNCI: Matikan zoom lewat scroll mouse
                doubleClickZoom: false,  // KUNCI: Matikan zoom lewat klik dua kali
                boxZoom: false,          // KUNCI: Matikan zoom kotak shift+drag
                dragging: true           // Tetap izinkan geser peta lewat drag mouse/jari
            }).setView([-1.2200, 119.9500], 10);

            // Set posisi kontrol zoom agar turun ke bawah header floating
            map.zoomControl.setPosition('topleft');

            // Layer Peta Dasar: OpenStreetMap Standard
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Data 11 Titik Sungai RESMI Kabupaten Sigi (Koordinat Valid Google Maps)
            const riversData = [
                { name: "Sungai Gumbasa", lat: -1.2135, lon: 119.9441, status: "Normal" },
                { name: "Sungai Pakuli", lat: -1.2586, lon: 119.9515, status: "Siaga" },
                { name: "Sungai Lindu", lat: -1.3142, lon: 120.0435, status: "Normal" },
                { name: "Sungai Samba", lat: -1.1215, lon: 119.9021, status: "Normal" },
                { name: "Sungai Lariang", lat: -1.4523, lon: 120.1245, status: "Normal" },
                { name: "Sungai Marawola", lat: -1.0125, lon: 119.8342, status: "Normal" },
                { name: "Sungai Palolo", lat: -1.1845, lon: 120.1032, status: "Normal" },
                { name: "Sungai Kulawi", lat: -1.4321, lon: 119.9845, status: "Siaga" },
                { name: "Sungai Ngatabaru", lat: -0.9842, lon: 119.9125, status: "Normal" },
                { name: "Sungai Wuno", lat: -1.0245, lon: 119.8632, status: "Normal" },
                { name: "Sungai Bangga", lat: -1.2154, lon: 119.8241, status: "Bahaya" }
            ];

            // Fungsi membuat Marker DivIcon kustom bersinar dinamis sesuai status
            function getMarkerIcon(status) {
                let colorClass = 'bg-emerald-500';
                if (status === 'Siaga') {
                    colorClass = 'bg-amber-500';
                } else if (status === 'Bahaya') {
                    colorClass = 'bg-rose-500';
                }
                return L.divIcon({
                    className: 'custom-div-icon',
                    html: `
                        <div class="relative flex items-center justify-center">
                            <span class="animate-ping absolute inline-flex h-6 w-6 rounded-full ${colorClass} opacity-75"></span>
                            <div class="relative w-4.5 h-4.5 rounded-full ${colorClass} border-2 border-white flex items-center justify-center shadow-lg">
                                <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                            </div>
                        </div>
                    `,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
                });
            }

            // Loop data sungai untuk menaruh marker dan popup minimalis tanpa tombol
            riversData.forEach((river) => {
                let badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200/60';
                if (river.status === 'Siaga') {
                    badgeClass = 'bg-amber-50 text-amber-700 border-amber-200/60';
                } else if (river.status === 'Bahaya') {
                    badgeClass = 'bg-rose-50 text-rose-700 border-rose-200/60';
                }

                const popupContent = `
                    <div class="p-2.5 font-sans text-slate-800 min-w-[170px]">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Stasiun Pemantauan</p>
                        <h4 class="font-black text-sm text-slate-950 tracking-tight mb-3">
                            ${river.name}
                        </h4>
                        
                        <div class="flex items-center justify-between border-t border-slate-100 pt-2.5">
                            <span class="text-[10px] font-bold text-slate-400">STATUS:</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider border ${badgeClass}">
                                ${river.status}
                            </span>
                        </div>
                    </div>
                `;

                L.marker([river.lat, river.lon], { icon: getMarkerIcon(river.status) })
                    .addTo(map)
                    .bindPopup(popupContent, {
                        maxWidth: 260,
                        minWidth: 170
                    });
            });

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</div>