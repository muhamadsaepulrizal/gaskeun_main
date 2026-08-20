<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Heatmap Kelangkaan LPG — GASKEUN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- MapLibre GL JS --}}
    <link href="https://unpkg.com/maplibre-gl@5/dist/maplibre-gl.css" rel="stylesheet" />
    <script src="https://unpkg.com/maplibre-gl@5/dist/maplibre-gl.js"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background: #F8FAFC; color: #0F172A; }
        .glass-header {
            background: rgba(255,255,255,0.9); backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0,0,0,0.06); box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        #map {
            height: calc(100vh - 180px);
            width: 100%; border-radius: 1rem; z-index: 10;
        }
        @media (min-width: 768px) {
            #map { height: calc(100vh - 120px); }
        }
        .legend {
            position: absolute; top: 20px; right: 20px; z-index: 999;
            background: rgba(255,255,255,0.95); backdrop-filter: blur(8px);
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 12px; padding: 14px 18px;
            font-size: 0.78rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            display: flex; gap: 15px; align-items: center;
        }
        .legend-item { display: flex; align-items: center; gap: 6px; color: #475569; font-weight: 600; font-size: 0.75rem;}
        .legend-dot { width: 12px; height: 12px; border-radius: 50%; }

        /* MapLibre Popup Override */
        .maplibregl-popup-content {
            background: rgba(255,255,255,0.98) !important;
            border: 1px solid rgba(0,0,0,0.05) !important;
            color: #0F172A !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.12) !important;
            border-radius: 14px !important;
            padding: 0 !important;
            max-width: 320px !important;
        }
        .maplibregl-popup-close-button {
            font-size: 18px; color: #94A3B8; padding: 4px 8px;
        }
        .maplibregl-popup-close-button:hover { color: #0F172A; }
        .maplibregl-popup-tip { border-top-color: rgba(255,255,255,0.98) !important; }

        .popup-inner {
            font-family: 'Poppins', sans-serif; min-width: 240px; padding: 16px;
        }
        .popup-inner h3 { font-weight: 700; font-size: 1rem; color: #0F172A; margin: 0 0 4px 0; line-height: 1.2; }
        .popup-inner .status-badge {
            font-size: 0.68rem; font-weight: 700; padding: 2px 10px; border-radius: 999px;
            display: inline-block; white-space: nowrap; margin-bottom: 10px;
        }
        .popup-inner .info-line { font-size: 0.78rem; color: #64748B; margin: 0 0 3px 0; }
        .popup-inner .stok-box {
            background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 10px; margin: 10px 0;
        }
        .popup-inner .stok-box .label { font-size: 0.68rem; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; margin: 0 0 4px 0; }
        .popup-inner .stok-box .value { font-weight: 800; font-size: 1.4rem; color: #0F172A; line-height: 1; }
        .popup-inner .stok-box .unit { font-size: 0.8rem; font-weight: 500; color: #64748B; }
        .popup-inner .nav-btn {
            display: block; text-align: center; background: linear-gradient(135deg, #0B5240, #10B981);
            color: #fff; font-size: 0.82rem; font-weight: 600; padding: 9px; border-radius: 8px;
            text-decoration: none; transition: 0.2s; margin-top: 4px;
        }
        .popup-inner .nav-btn:hover { opacity: 0.9; transform: translateY(-1px); }

        /* 3D Toggle Button */
        .terrain-toggle {
            position: absolute; bottom: 36px; left: 16px; z-index: 999;
            background: rgba(255,255,255,0.95); backdrop-filter: blur(8px);
            border: 1px solid rgba(0,0,0,0.08); border-radius: 10px;
            padding: 8px 14px; cursor: pointer; font-size: 0.78rem; font-weight: 600;
            color: #475569; box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.2s;
        }
        .terrain-toggle:hover { background: #fff; color: #0F172A; }
        .terrain-toggle.active { background: #0F172A; color: #fff; border-color: #0F172A; }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col relative">

    <div class="glass-header sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-4 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex-1">
                <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="inline-flex items-center text-sm font-medium mb-2 transition-colors text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-full">
                    <i class="fa-solid fa-arrow-left mr-2"></i>
                    <span>{{ auth()->check() ? 'Kembali' : 'Beranda' }}</span>
                </a>
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900 flex items-center gap-3">
                    <i class="fa-solid fa-fire text-cyan-500"></i> Heatmap <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-500">Kelangkaan</span>
                </h1>
                <p class="text-xs text-slate-500 mt-1 hidden md:block">Distribusi intensitas kelangkaan per wilayah — GASKEUN Garut</p>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto overflow-x-auto pb-2 sm:pb-0">
                <div class="relative w-full sm:w-48">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-filter"></i>
                    </div>
                    <select id="kecamatanFilter" class="bg-white border border-slate-300 text-slate-700 text-sm rounded-xl focus:ring-cyan-500 focus:border-cyan-500 block w-full pl-10 p-2.5 shadow-sm appearance-none cursor-pointer hover:border-cyan-400 transition-colors">
                        <option value="">Semua Kecamatan</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </div>
                </div>

                <div class="flex gap-2 shrink-0">
                    <button id="btn-toggle-heatmap" class="px-4 py-2 bg-cyan-100 text-cyan-700 border border-cyan-200 rounded-xl text-sm font-semibold hover:bg-cyan-200 transition whitespace-nowrap">
                        <i class="fa-solid fa-temperature-full mr-1"></i> Heatmap
                    </button>
                    <button id="btn-toggle-markers" class="px-4 py-2 bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl text-sm font-semibold hover:bg-emerald-200 transition whitespace-nowrap">
                        <i class="fa-solid fa-map-pin mr-1"></i> Pangkalan
                    </button>
                </div>

                <a href="{{ route('public.keluhan.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-bold transition-all transform hover:-translate-y-0.5 shadow-lg shadow-rose-500/30 text-white bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 whitespace-nowrap">
                    <i class="fa-solid fa-bullhorn mr-2"></i> Lapor
                </a>
            </div>
        </div>

        {{-- Stats Bar --}}
        <div class="max-w-7xl mx-auto px-4 md:px-6 pb-3 pt-1 flex flex-wrap gap-2 text-xs md:text-sm">
            <span class="bg-white border border-slate-200 rounded-full px-3 py-1 text-slate-600 shadow-sm"><i class="fa-solid fa-triangle-exclamation text-rose-500 mr-1"></i> Laporan: <strong class="text-slate-900" id="stat-keluhan">-</strong></span>
            <span class="bg-white border border-slate-200 rounded-full px-3 py-1 text-slate-600 shadow-sm"><i class="fa-solid fa-store text-emerald-500 mr-1"></i> Pangkalan: <strong class="text-slate-900" id="stat-pangkalan">-</strong></span>
            <span class="bg-white border border-slate-200 rounded-full px-3 py-1 text-slate-600 shadow-sm"><i class="fa-solid fa-battery-quarter text-amber-500 mr-1"></i> Stok Menipis/Kosong: <strong class="text-slate-900" id="stat-kritis">-</strong></span>
        </div>
    </div>

    <div class="flex-grow max-w-7xl mx-auto w-full p-4 md:p-6 relative">
        <div class="p-2 rounded-2xl relative bg-white border border-slate-200 shadow-sm" style="box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
            <div id="map"></div>

            <div class="legend" id="map-legend">
                <div style="font-weight: 700; color: #0F172A; margin-right: 8px;">Titik Kelangkaan</div>
                <div class="legend-item"><div class="legend-dot" style="background: #EF4444;"></div> <span>Kritis</span></div>
                <div class="legend-item"><div class="legend-dot" style="background: #F59E0B;"></div> <span>Waspada</span></div>
                <div class="legend-item"><div class="legend-dot" style="background: #10B981;"></div> <span>Aman</span></div>
            </div>

            <button id="btn-3d" class="terrain-toggle active" onclick="toggle3D()">
                <i class="fa-solid fa-cube mr-1"></i> 3D
            </button>
        </div>
    </div>

    <script>
        // === DATA ===
        const heatmapRaw = {!! json_encode($heatmapData ?? []) !!};
        const pangkalanData = {!! json_encode($pangkalanList ?? []) !!};

        document.getElementById('stat-keluhan').textContent   = heatmapRaw.length;
        document.getElementById('stat-pangkalan').textContent = pangkalanData.length;
        const stokKritis = pangkalanData.filter(p => p.status === 'Menipis' || p.status === 'Kosong').length;
        document.getElementById('stat-kritis').textContent = stokKritis;

        // Fallback heatmap data
        let heatData = heatmapRaw.length > 0
            ? heatmapRaw
            : [
                [-7.2167, 107.9000, 8, 'Tarogong Kidul'],  [-7.1800, 107.8700, 5, 'Samarang'],
                [-7.2500, 107.9300, 9, 'Garut Kota'],  [-7.1500, 107.9500, 2, 'Banyuresmi'],
                [-7.2900, 107.9700, 7, 'Cilawu'],  [-7.2100, 107.8400, 1, 'Pasirwangi'],
                [-7.3200, 107.9100, 6, 'Bayongbong'],  [-7.1900, 107.9800, 3, 'Karangpawitan'],
                [-7.2600, 107.8600, 1, 'Tarogong Kaler'],  [-7.3500, 107.9400, 8, 'Cisurupan'],
            ];

        // === MAPLIBRE GL JS with OpenFreeMap 3D ===
        const map = new maplibregl.Map({
            container: 'map',
            style: 'https://tiles.openfreemap.org/styles/liberty',
            center: [107.9000, -7.2167],  // [lng, lat] format for MapLibre
            zoom: 10,
            pitch: 55,
            bearing: -15,
            antialias: true,
            maxPitch: 85,
        });

        map.addControl(new maplibregl.NavigationControl({ visualizePitch: true }), 'top-left');
        map.addControl(new maplibregl.ScaleControl({ maxWidth: 200 }));

        // === 3D Terrain Toggle ===
        let is3D = true;

        function enable3DTerrain() {
            map.easeTo({ pitch: 55, bearing: -15, duration: 1000 });
            try {
                // Add 3D buildings from OpenFreeMap's data
                const layers = map.getStyle().layers;
                if (!layers) return;

                // Extrude buildings for 3D effect
                const buildingLayer = layers.find(l => l.id && l.id.toLowerCase().includes('building'));
                if (buildingLayer && buildingLayer.type === 'fill-extrusion') {
                    map.setPaintProperty(buildingLayer.id, 'fill-extrusion-height', ['get', 'render_height']);
                    map.setPaintProperty(buildingLayer.id, 'fill-extrusion-base', ['get', 'render_min_height']);
                    map.setPaintProperty(buildingLayer.id, 'fill-extrusion-opacity', 0.7);
                }
            } catch (e) {
                console.warn('3D Buildings extrusions not natively supported by this vector style:', e);
            }
        }

        function disable3DTerrain() {
            map.easeTo({ pitch: 0, bearing: 0, duration: 1000 });
        }

        function toggle3D() {
            const btn = document.getElementById('btn-3d');
            if (is3D) {
                disable3DTerrain();
                btn.classList.remove('active');
            } else {
                enable3DTerrain();
                btn.classList.add('active');
            }
            is3D = !is3D;
        }

        // === HELPERS ===
        function getStatusColor(status) {
            if (status === 'Krisis' || status === 'Kosong') return '#DC2626';
            if (status === 'Menipis') return '#F59E0B';
            return '#10B981';
        }

        function getStatusBg(status) {
            if (status === 'Krisis' || status === 'Kosong') return 'rgba(220,38,38,0.1)';
            if (status === 'Menipis') return 'rgba(245,158,11,0.1)';
            return 'rgba(16,185,129,0.1)';
        }

        function createPopupHTML(p) {
            const statusColor = getStatusColor(p.status);
            const statusBg = getStatusBg(p.status);
            return `
                <div class="popup-inner">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:6px;">
                        <h3>${p.nama}</h3>
                    </div>
                    <span class="status-badge" style="background:${statusBg}; border:1px solid ${statusColor}40; color:${statusColor};">${p.status}</span>
                    <p class="info-line"><i class="fa-solid fa-building text-slate-400 mr-1"></i> Agen: ${p.agen}</p>
                    <p class="info-line"><i class="fa-solid fa-map text-slate-400 mr-1"></i> Kec. ${p.kecamatan}</p>
                    <p class="info-line" style="margin-bottom:8px;">${p.alamat}</p>
                    <div class="stok-box">
                        <p class="label">Stok Saat Ini</p>
                        <p class="value">${p.stok} <span class="unit">Tabung</span></p>
                    </div>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=${p.latitude},${p.longitude}" target="_blank" class="nav-btn">
                        <i class="fa-solid fa-location-arrow mr-1"></i> Navigasi ke Lokasi
                    </a>
                </div>
            `;
        }

        // === KECAMATAN FILTER DROPDOWN ===
        const kecamatanSet = new Set();
        pangkalanData.forEach(p => {
            if (p.kecamatan && p.kecamatan.trim() !== '') kecamatanSet.add(p.kecamatan);
        });
        const kecamatanList = Array.from(kecamatanSet).sort();
        const filterSelect = document.getElementById('kecamatanFilter');
        kecamatanList.forEach(kec => {
            const opt = document.createElement('option');
            opt.value = kec;
            opt.textContent = kec;
            filterSelect.appendChild(opt);
        });

        // === MARKERS & LAYERS ===
        let pangkalanMarkers = [];
        let heatmapMarkers = [];
        let markersOn = true;
        let heatmapOn = true;
        let currentPopup = null;

        function clearAllMarkers() {
            pangkalanMarkers.forEach(m => m.remove());
            pangkalanMarkers = [];
            heatmapMarkers.forEach(m => m.remove());
            heatmapMarkers = [];
            if (currentPopup) { currentPopup.remove(); currentPopup = null; }
        }

        function renderMarkers(filterKecamatan = '') {
            clearAllMarkers();
            const bounds = new maplibregl.LngLatBounds();
            let hasPoints = false;

            // 1. Render Pangkalan Markers
            if (markersOn) {
                pangkalanData.forEach(p => {
                    if (!p.latitude || !p.longitude) return;
                    if (filterKecamatan !== '' && p.kecamatan !== filterKecamatan) return;

                    const statusColor = getStatusColor(p.status);

                    const el = document.createElement('div');
                    el.className = 'custom-marker';
                    el.style.cursor = 'pointer';
                    
                    const svgWrapper = document.createElement('div');
                    svgWrapper.style.transition = 'transform 0.2s';
                    svgWrapper.className = 'marker-svg-wrapper';
                    svgWrapper.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="30" height="30" style="fill:${statusColor}; filter:drop-shadow(0px 3px 5px rgba(0,0,0,0.3));">
                        <path d="M192 0C86 0 0 86 0 192c0 106 192 320 192 320s192-214 192-320c0-106-86-192-192-192zm0 288c-53 0-96-43-96-96s43-96 96-96 96 43 96 96-43 96-96 96z"/>
                    </svg>`;
                    
                    el.appendChild(svgWrapper);

                    el.addEventListener('mouseenter', () => svgWrapper.style.transform = 'scale(1.2) translateY(-3px)');
                    el.addEventListener('mouseleave', () => svgWrapper.style.transform = 'scale(1)');

                    const marker = new maplibregl.Marker({ element: el, anchor: 'bottom' })
                        .setLngLat([p.longitude, p.latitude])
                        .addTo(map);

                    el.addEventListener('click', () => {
                        if (currentPopup) currentPopup.remove();
                        currentPopup = new maplibregl.Popup({ offset: [0, -30], maxWidth: '320px' })
                            .setLngLat([p.longitude, p.latitude])
                            .setHTML(createPopupHTML(p))
                            .addTo(map);
                    });

                    pangkalanMarkers.push(marker);
                    bounds.extend([p.longitude, p.latitude]);
                    hasPoints = true;
                });
            }

            // 2. Render Heatmap Keluhan Dots
            if (heatmapOn) {
                heatData.forEach(h => {
                    const lat = h[0], lng = h[1], weight = h[2], kec = h[3] || '';
                    if (filterKecamatan !== '' && kec !== filterKecamatan) return;

                    let color, statusText;
                    if (weight >= 7) { color = '#EF4444'; statusText = 'Kritis'; }
                    else if (weight >= 4) { color = '#F59E0B'; statusText = 'Waspada'; }
                    else { color = '#10B981'; statusText = 'Aman'; }

                    // Pulsating circle for heatmap
                    const el = document.createElement('div');
                    el.style.width = '20px';
                    el.style.height = '20px';
                    el.style.borderRadius = '50%';
                    el.style.background = color;
                    el.style.border = '2.5px solid white';
                    el.style.boxShadow = `0 0 12px ${color}80, 0 2px 6px rgba(0,0,0,0.2)`;
                    el.style.cursor = 'pointer';
                    el.style.transition = 'transform 0.2s';
                    el.style.animation = 'pulse 2s infinite';

                    const marker = new maplibregl.Marker({ element: el })
                        .setLngLat([lng, lat])
                        .addTo(map);

                    el.addEventListener('click', () => {
                        if (currentPopup) currentPopup.remove();
                        currentPopup = new maplibregl.Popup({ offset: [0, -12], maxWidth: '260px' })
                            .setLngLat([lng, lat])
                            .setHTML(`
                                <div style="font-family:'Poppins',sans-serif; text-align:center; padding:14px;">
                                    <div style="font-size:0.72rem; color:#64748B; text-transform:uppercase; font-weight:700; margin-bottom:6px;">Status Area</div>
                                    <div style="font-size:1.2rem; font-weight:700; color:${color};">${statusText}</div>
                                    <div style="font-size:0.85rem; color:#0F172A; margin-top:8px;"><b>${weight}</b> Laporan Keluhan</div>
                                </div>
                            `)
                            .addTo(map);
                    });

                    heatmapMarkers.push(marker);
                    bounds.extend([lng, lat]);
                    hasPoints = true;
                });
            }

            if (hasPoints) {
                map.fitBounds(bounds, { padding: 60, maxZoom: 14, duration: 1500 });
            }
        }

        // === MAP LOAD ===
        map.on('load', () => {
            // Enable 3D buildings
            enable3DTerrain();
            // Render data
            renderMarkers();
        });

        // === EVENT HANDLERS ===
        filterSelect.addEventListener('change', e => renderMarkers(e.target.value));

        document.getElementById('btn-toggle-heatmap').addEventListener('click', function() {
            heatmapOn = !heatmapOn;
            const legend = document.getElementById('map-legend');
            if (heatmapOn) {
                this.innerHTML = '<i class="fa-solid fa-temperature-full mr-1"></i> Heatmap';
                this.classList.replace('bg-cyan-50', 'bg-cyan-100');
                if (legend) legend.style.display = 'flex';
            } else {
                this.innerHTML = '<i class="fa-solid fa-temperature-empty mr-1"></i> Heatmap';
                this.classList.replace('bg-cyan-100', 'bg-cyan-50');
                if (legend) legend.style.display = 'none';
            }
            renderMarkers(filterSelect.value);
        });

        document.getElementById('btn-toggle-markers').addEventListener('click', function() {
            markersOn = !markersOn;
            if (markersOn) {
                this.innerHTML = '<i class="fa-solid fa-map-pin mr-1"></i> Pangkalan';
                this.classList.replace('bg-emerald-50', 'bg-emerald-100');
            } else {
                this.innerHTML = '<i class="fa-solid fa-map-pin mr-1 text-slate-400"></i> Pangkalan';
                this.classList.replace('bg-emerald-100', 'bg-emerald-50');
            }
            renderMarkers(filterSelect.value);
        });

        // Notifikasi jika tidak ada data keluhan
        if (heatmapRaw.length === 0) {
            map.on('load', () => {
                const el = document.createElement('div');
                el.innerHTML = '<div style="background:rgba(255,255,255,0.95);border:1px solid #FDE047;color:#D97706;padding:8px 14px;border-radius:10px;font-size:0.75rem;backdrop-filter:blur(8px);box-shadow:0 4px 6px rgba(0,0,0,0.05);font-weight:600;position:fixed;bottom:20px;left:50%;transform:translateX(-50%);z-index:999;"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Data keluhan belum tersedia. Menampilkan data simulasi.</div>';
                document.body.appendChild(el);
                setTimeout(() => el.style.opacity = '0', 5000);
                setTimeout(() => el.remove(), 6000);
            });
        }
    </script>

    <style>
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 currentColor; }
            70% { box-shadow: 0 0 0 8px transparent; }
            100% { box-shadow: 0 0 0 0 transparent; }
        }
    </style>
</body>
</html>
