<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Pangkalan LPG - GASKEUN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- MapLibre GL JS --}}
    <link href="https://unpkg.com/maplibre-gl@5/dist/maplibre-gl.css" rel="stylesheet" />
    <script src="https://unpkg.com/maplibre-gl@5/dist/maplibre-gl.js"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #F8FAFC; color: #0F172A; }
        .glass-header { background: rgba(255,255,255,0.9); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid rgba(0,0,0,0.06); box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        #map {
            height: calc(100vh - 120px);
            width: 100%;
            border-radius: 1rem;
            z-index: 10;
        }
        
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
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex-1">
                <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="inline-flex items-center text-sm font-medium mb-2 transition-colors text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-full">
                    <i class="fa-solid fa-arrow-left mr-2"></i>
                    <span>{{ auth()->check() ? 'Kembali' : 'Beranda' }}</span>
                </a>
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900 flex items-center gap-3">
                    <i class="fa-solid fa-map-location-dot text-teal-500"></i> Peta <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-emerald-500">Pangkalan</span>
                </h1>
            </div>
            
            <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3 md:gap-4 w-full md:w-auto">
                <div class="relative w-full md:w-64">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-filter"></i>
                    </div>
                    <select id="kecamatanFilter" class="bg-white border border-slate-300 text-slate-700 text-sm rounded-xl focus:ring-teal-500 focus:border-teal-500 block w-full pl-10 p-2.5 shadow-sm appearance-none cursor-pointer hover:border-teal-400 transition-colors">
                        <option value="">Semua Kecamatan</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </div>
                </div>
                
                <a href="{{ route('public.keluhan.create') }}" class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl text-sm font-bold transition-all transform hover:-translate-y-0.5 shadow-lg shadow-rose-500/30 text-white bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700">
                    <i class="fa-solid fa-bullhorn mr-2"></i> Laporkan Kelangkaan
                </a>
            </div>
        </div>
    </div>

    <div class="flex-grow max-w-7xl mx-auto w-full p-4 md:p-6">
        <div class="p-2 rounded-2xl relative bg-white border border-slate-200 shadow-sm" style="box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
            <!-- Map Container -->
            <div id="map"></div>
            <button id="btn-3d" class="terrain-toggle active" onclick="toggle3D()">
                <i class="fa-solid fa-cube mr-1"></i> 3D
            </button>
        </div>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // === DATA ===
            const pangkalanData = {!! json_encode($pangkalanList ?? []) !!};
            
            // Extract unique Kecamatan and populate dropdown
            const kecamatanSet = new Set();
            pangkalanData.forEach(p => {
                if (p.kecamatan && p.kecamatan.trim() !== '') {
                    kecamatanSet.add(p.kecamatan);
                }
            });
            const kecamatanList = Array.from(kecamatanSet).sort();
            const filterSelect = document.getElementById('kecamatanFilter');
            
            kecamatanList.forEach(kec => {
                const opt = document.createElement('option');
                opt.value = kec;
                opt.textContent = kec;
                filterSelect.appendChild(opt);
            });

            // === MAPLIBRE GL JS with OpenFreeMap 3D ===
            const map = new maplibregl.Map({
                container: 'map',
                style: 'https://tiles.openfreemap.org/styles/liberty', // OpenFreeMap Liberty style
                center: [107.9000, -7.2167],  // [lng, lat] format for MapLibre
                zoom: 10,
                pitch: 55, // For 3D view
                bearing: -15, // For 3D view
                antialias: true,
                maxPitch: 85,
            });

            map.addControl(new maplibregl.NavigationControl({ visualizePitch: true }), 'top-left');
            map.addControl(new maplibregl.ScaleControl({ maxWidth: 200 }));

            // === 3D Terrain Toggle ===
            let is3D = true;
            window.toggle3D = function() {
                const btn = document.getElementById('btn-3d');
                if (is3D) {
                    map.easeTo({ pitch: 0, bearing: 0, duration: 1000 });
                    btn.classList.remove('active');
                } else {
                    enable3DTerrain();
                    btn.classList.add('active');
                }
                is3D = !is3D;
            };

            function enable3DTerrain() {
                map.easeTo({ pitch: 55, bearing: -15, duration: 1000 });
                try {
                    // Extrude buildings for 3D effect if supported by style
                    const layers = map.getStyle().layers;
                    if (!layers) return;
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

            // === MARKERS & LAYERS ===
            let pangkalanMarkers = [];
            function clearAllMarkers() {
                pangkalanMarkers.forEach(m => m.remove());
                pangkalanMarkers = [];
            }

            function renderMarkers(filterKecamatan = '') {
                clearAllMarkers();
                const bounds = new maplibregl.LngLatBounds();
                let hasPoints = false;

                pangkalanData.forEach(p => {
                    const lat = parseFloat(p.latitude);
                    const lng = parseFloat(p.longitude);
                    if (isNaN(lat) || isNaN(lng)) return;
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

                    const popup = new maplibregl.Popup({ offset: [0, -30], maxWidth: '320px' })
                        .setHTML(createPopupHTML(p));

                    const marker = new maplibregl.Marker({ element: el, anchor: 'bottom' })
                        .setLngLat([lng, lat])
                        .setPopup(popup)
                        .addTo(map);

                    pangkalanMarkers.push(marker);
                    bounds.extend([lng, lat]);
                    hasPoints = true;
                });

                if (hasPoints) {
                    map.fitBounds(bounds, { padding: 60, maxZoom: 14, duration: 1500 });
                }
            }

            // === MAP LOAD ===
            map.on('load', () => {
                enable3DTerrain();
                renderMarkers();
            });

            // === EVENT HANDLERS ===
            filterSelect.addEventListener('change', function(e) {
                renderMarkers(e.target.value);
            });
        });
    </script>
</body>
</html>
