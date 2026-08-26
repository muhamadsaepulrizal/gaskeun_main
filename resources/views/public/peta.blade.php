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
                    Peta <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-emerald-500">Pangkalan</span>
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

            // === HIERARCHICAL CLUSTERING DATA ===
            const groupedByKecamatan = {};
            const groupedByDesa = {};
            
            pangkalanData.forEach((p, idx) => {
                p.id = idx;
                const lat = parseFloat(p.latitude);
                const lng = parseFloat(p.longitude);
                if (isNaN(lat) || isNaN(lng)) return;
                
                const kecName = p.kecamatan || 'Lainnya';
                const desaName = p.desa || 'Lainnya';
                const keyDesa = `${kecName}-${desaName}`;

                if (!groupedByKecamatan[kecName]) {
                    groupedByKecamatan[kecName] = { name: kecName, count: 0, sumLat: 0, sumLng: 0, bounds: new maplibregl.LngLatBounds() };
                }
                groupedByKecamatan[kecName].count++;
                groupedByKecamatan[kecName].sumLat += lat;
                groupedByKecamatan[kecName].sumLng += lng;
                groupedByKecamatan[kecName].bounds.extend([lng, lat]);

                if (!groupedByDesa[keyDesa]) {
                    groupedByDesa[keyDesa] = { name: desaName, kecName: kecName, count: 0, sumLat: 0, sumLng: 0, bounds: new maplibregl.LngLatBounds() };
                }
                groupedByDesa[keyDesa].count++;
                groupedByDesa[keyDesa].sumLat += lat;
                groupedByDesa[keyDesa].sumLng += lng;
                groupedByDesa[keyDesa].bounds.extend([lng, lat]);
            });

            // Calculate centroids
            Object.values(groupedByKecamatan).forEach(k => { k.lat = k.sumLat / k.count; k.lng = k.sumLng / k.count; });
            Object.values(groupedByDesa).forEach(d => { d.lat = d.sumLat / d.count; d.lng = d.sumLng / d.count; });

            let currentMarkers = {};
            let activeFilter = '';

            function createClusterElement(label, count, onClick) {
                const el = document.createElement('div');
                el.style.cursor = 'pointer';
                el.className = 'cluster-marker';
                
                let size = count < 10 ? 45 : (count < 50 ? 55 : 65);
                
                el.innerHTML = `
                <div style="display:flex; flex-direction:column; align-items:center;">
                    <div style="background: linear-gradient(135deg, #0F766E, #10B981); color: white; border-radius: 50%; width: ${size}px; height: ${size}px; display: flex; align-items: center; justify-content: center; font-weight: 700; border: 3px solid rgba(255,255,255,0.9); box-shadow: 0 5px 15px rgba(0,0,0,0.25); font-size: ${Math.max(14, size/3.5)}px; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); backdrop-filter: blur(4px);">
                        ${count}
                    </div>
                    <div style="background:rgba(255,255,255,0.95); padding:3px 10px; border-radius:10px; font-size:11px; font-weight:700; color:#0F172A; margin-top:4px; box-shadow:0 3px 6px rgba(0,0,0,0.15); border:1px solid rgba(15,118,110,0.2); text-align:center; max-width:120px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        ${label}
                    </div>
                </div>`;
                
                const circle = el.querySelector('div > div:first-child');
                el.addEventListener('mouseenter', () => circle.style.transform = 'scale(1.15) translateY(-2px)');
                el.addEventListener('mouseleave', () => circle.style.transform = 'scale(1) translateY(0)');
                el.addEventListener('click', onClick);
                return el;
            }

            function updateClusters() {
                const zoom = map.getZoom();
                const newMarkers = {};
                
                const addMarker = (id, el, lng, lat, popup) => {
                    if (!currentMarkers[id]) {
                        const marker = new maplibregl.Marker({ element: el, anchor: 'center' })
                            .setLngLat([lng, lat])
                            .addTo(map);
                        if(popup) marker.setPopup(popup);
                        newMarkers[id] = marker;
                    } else {
                        newMarkers[id] = currentMarkers[id];
                        delete currentMarkers[id];
                    }
                };

                if (zoom < 11.5) {
                    // 1. KECAMATAN LEVEL
                    Object.values(groupedByKecamatan).forEach(k => {
                        if (activeFilter && k.name !== activeFilter) return;
                        const id = `kec-${k.name}`;
                        if (!currentMarkers[id]) {
                            const el = createClusterElement(k.name, k.count, () => {
                                map.fitBounds(k.bounds, { padding: 80, maxZoom: 13, duration: 1500 });
                                map.once('moveend', () => {
                                    if (map.getZoom() < 11.5) map.easeTo({ zoom: 12, duration: 500 });
                                });
                            });
                            addMarker(id, el, k.lng, k.lat);
                        } else addMarker(id, null);
                    });
                } else if (zoom < 13.5) {
                    // 2. DESA LEVEL
                    Object.values(groupedByDesa).forEach(d => {
                        if (activeFilter && d.kecName !== activeFilter) return;
                        const id = `desa-${d.kecName}-${d.name}`;
                        if (!currentMarkers[id]) {
                            const el = createClusterElement(d.name, d.count, () => {
                                if (d.count === 1) {
                                    map.easeTo({ center: [d.lng, d.lat], zoom: 14.5, duration: 1500 });
                                } else {
                                    map.fitBounds(d.bounds, { padding: 80, maxZoom: 14.5, duration: 1500 });
                                    map.once('moveend', () => {
                                        if (map.getZoom() < 13.5) map.easeTo({ zoom: 14, duration: 500 });
                                    });
                                }
                            });
                            addMarker(id, el, d.lng, d.lat);
                        } else addMarker(id, null);
                    });
                } else {
                    // 3. PANGKALAN LEVEL
                    pangkalanData.forEach(p => {
                        const lat = parseFloat(p.latitude);
                        const lng = parseFloat(p.longitude);
                        if (isNaN(lat) || isNaN(lng)) return;
                        if (activeFilter && p.kecamatan !== activeFilter) return;

                        const id = `point-${p.id}`;
                        if (!currentMarkers[id]) {
                            const el = document.createElement('div');
                            el.className = 'custom-marker';
                            el.style.cursor = 'pointer';
                            const statusColor = getStatusColor(p.status);
                            
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

                            addMarker(id, el, lng, lat, popup);
                        } else addMarker(id, null);
                    });
                }

                for (const id in currentMarkers) {
                    currentMarkers[id].remove();
                }
                currentMarkers = newMarkers;
            }

            function fitMapToBounds(filterKecamatan = '') {
                activeFilter = filterKecamatan;
                if (filterKecamatan !== '' && groupedByKecamatan[filterKecamatan]) {
                    const k = groupedByKecamatan[filterKecamatan];
                    map.fitBounds(k.bounds, { padding: 60, maxZoom: 13, duration: 1500 });
                    map.once('moveend', () => {
                        if (map.getZoom() < 11.5) map.easeTo({ zoom: 12, duration: 500 });
                    });
                } else {
                    // Fit to all data
                    const bounds = new maplibregl.LngLatBounds();
                    Object.values(groupedByKecamatan).forEach(k => {
                        bounds.extend([k.lng, k.lat]);
                    });
                    if (Object.keys(groupedByKecamatan).length > 0) {
                        map.fitBounds(bounds, { padding: 60, maxZoom: 10, duration: 1500 });
                    }
                }
            }

            // Update clusters when map is moved or zoomed
            map.on('move', () => {
                updateClusters(activeFilter);
            });

            // === MAP LOAD ===
            map.on('load', () => {
                enable3DTerrain();
                fitMapToBounds('');
                // updateClusters will be called by 'move' event triggered by fitBounds
                // but just in case, call it initially
                setTimeout(() => updateClusters(''), 100); 
            });

            // === EVENT HANDLERS ===
            filterSelect.addEventListener('change', function(e) {
                activeFilter = e.target.value;
                fitMapToBounds(activeFilter);
                updateClusters(activeFilter);
            });
        });
    </script>
</body>
</html>
