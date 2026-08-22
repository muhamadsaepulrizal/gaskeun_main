<!DOCTYPE html>
<html lang="id" style="color-scheme: light;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GASKEUN — @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Tailwind CSS Config for CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        brand: {
                            DEFAULT: '#0B5240',
                            light: '#14765C',
                            dark: '#052b21'
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
        
        /* Paksa Scrollbar Konten Utama Jadi Terang */
        ::-webkit-scrollbar { width: 8px !important; height: 8px !important; }
        ::-webkit-scrollbar-track { background-color: #f8fafc !important; }
        ::-webkit-scrollbar-thumb { 
            background-color: #cbd5e1 !important; 
            border-radius: 10px !important; 
            border: 2px solid #f8fafc !important; 
        }
        ::-webkit-scrollbar-thumb:hover { background-color: #94a3b8 !important; }

        /* Paksa Scrollbar Sidebar Nyatu Sama Hijau */
        .sidebar-scroll::-webkit-scrollbar { width: 6px !important; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent !important; border: none !important; }
        .sidebar-scroll::-webkit-scrollbar-thumb { 
            background-color: rgba(255, 255, 255, 0.2) !important; 
            border-radius: 10px !important; 
            border: none !important; 
        }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover { background-color: rgba(255, 255, 255, 0.35) !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased h-screen overflow-hidden flex" x-data="{ sidebarOpen: false }">

    <!-- ═══════════════════════════════════════════
         SIDEBAR (Gradient Hijau Hardcode)
    ═══════════════════════════════════════════ -->
    <aside class="fixed inset-y-0 left-0 z-50 w-64 flex flex-col transition-transform duration-300 md:translate-x-0 md:static md:inset-0 shadow-xl shadow-brand/10"
           style="background: linear-gradient(135deg, #0B5240 0%, #14765C 50%, #052b21 100%);"
           :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">

        <!-- Logo & Role Info -->
        <div class="h-24 flex flex-col justify-center px-6 border-b border-white/10 shrink-0">
            <!-- Gambar Logo dengan filter putih -->
            <img src="{{ asset('images/gaskeun02.png') }}" alt="Logo GASKEUN" class="w-full h-auto max-h-12 object-contain object-left brightness-0 invert filter mb-1.5">
            <p class="text-white/70 text-xs font-medium">{{ auth()->user()->roles->first()->name ?? 'User' }}</p>
        </div>

        
        <!-- Nav Items -->
        @php
            $activeClass = 'text-white bg-white/10 border-l-4 border-teal-400';
            $inactiveClass = 'text-white/70 hover:text-white hover:bg-white/5 border-l-4 border-transparent hover:border-white/20';
        @endphp

        <nav class="flex-1 overflow-y-auto sidebar-scroll py-6 px-4 space-y-1">

            <!-- Dashboard -->
            <a href="/dashboard" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->is('dashboard') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-border-all w-5 text-center"></i>
                Dashboard
            </a>

            <!-- ─── Super Admin ─── -->
            @role('Super Admin')
            <div class="pt-4 pb-2 px-4">
                <p class="text-[10px] uppercase font-bold tracking-wider text-white/50">Manajemen Sistem</p>
            </div>
            <a href="{{ route('superadmin.users.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('superadmin.users.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-users-gear w-5 text-center"></i>
                Kelola User
            </a>
            <a href="{{ route('superadmin.roles.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('superadmin.roles.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-sliders w-5 text-center"></i>
                Role & Hak Akses
            </a>
            <a href="{{ route('superadmin.logs.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('superadmin.logs.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i>
                Log Aktivitas
            </a>

            <!-- Pemetaan untuk Super Admin -->
            <div class="pt-4 pb-2 px-4">
                <p class="text-[10px] uppercase font-bold tracking-wider text-white/50">Pemetaan & Analisis</p>
            </div>
            <a href="{{ route('public.peta') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('public.peta') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-map w-5 text-center"></i>
                Peta GIS Pangkalan
            </a>
            <a href="{{ route('public.heatmap') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('public.heatmap') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-fire w-5 text-center"></i>
                Heatmap Kelangkaan
            </a>
            @endrole

            <!-- ─── Disperindag ─── -->
            @role('Disperindag')
            <div class="pt-4 pb-2 px-4">
                <p class="text-[10px] uppercase font-bold tracking-wider text-white/50">Master Data</p>
            </div>
            <a href="{{ route('disperindag.kecamatans.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('disperindag.kecamatans.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-map-location w-5 text-center"></i> Kecamatan
            </a>
            <a href="{{ route('disperindag.desas.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('disperindag.desas.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-map-pin w-5 text-center"></i> Desa / Kelurahan
            </a>
            <a href="{{ route('disperindag.kks.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('disperindag.kks.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-id-card w-5 text-center"></i> Kartu Keluarga
            </a>
            <a href="{{ route('disperindag.penduduks.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('disperindag.penduduks.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-users w-5 text-center"></i> Penduduk
            </a>
            <a href="{{ route('disperindag.nelayans.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('disperindag.nelayans.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-ship w-5 text-center"></i> Nelayan
            </a>
            <a href="{{ route('disperindag.petanis.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('disperindag.petanis.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-tractor w-5 text-center"></i> Petani
            </a>
            <a href="{{ route('disperindag.umkms.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('disperindag.umkms.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-store w-5 text-center"></i> UMKM
            </a>
            <a href="{{ route('disperindag.rts.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('disperindag.rts.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-house-chimney-user w-5 text-center"></i> RTS
            </a>
            
            <div class="pt-4 pb-2 px-4">
                <p class="text-[10px] uppercase font-bold tracking-wider text-white/50">Layanan & Pemetaan</p>
            </div>
            <a href="{{ route('disperindag.keluhan.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('disperindag.keluhan.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-comments w-5 text-center"></i> Kelola Keluhan
            </a>
            <a href="{{ route('public.peta') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('public.peta') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-map w-5 text-center"></i> Peta GIS Pangkalan
            </a>
            <a href="{{ route('public.heatmap') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('public.heatmap') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-fire w-5 text-center"></i> Heatmap Kelangkaan
            </a>
            @endrole

            <!-- ─── Agen LPG ─── -->
            @role('Agen LPG')
            <div class="pt-4 pb-2 px-4">
                <p class="text-[10px] uppercase font-bold tracking-wider text-white/50">Distribusi</p>
            </div>
            <a href="{{ route('agen.dashboard') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('agen.dashboard') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-border-all w-5 text-center"></i> Dashboard
            </a>
            <a href="{{ route('agen.pangkalan-binaan.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('agen.pangkalan-binaan.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-store w-5 text-center"></i> Pangkalan Binaan
            </a>
            <a href="{{ route('agen.pengiriman.create') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('agen.pengiriman.create') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-truck-ramp-box w-5 text-center"></i> Input Pengiriman
            </a>
            <a href="{{ route('agen.pengiriman.status') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('agen.pengiriman.status') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-clipboard-check w-5 text-center"></i> Status Pengiriman
            </a>
            <a href="{{ route('agen.profil') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('agen.profil') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-building-user w-5 text-center"></i> Profil Agen
            </a>
            
            <div class="pt-4 pb-2 px-4">
                <p class="text-[10px] uppercase font-bold tracking-wider text-white/50">Pemetaan</p>
            </div>
            <a href="{{ route('public.heatmap') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('public.heatmap') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-fire w-5 text-center"></i> Heatmap Kelangkaan
            </a>
            @endrole

            <!-- ─── Pangkalan LPG ─── -->
            @role('Pangkalan LPG')
            <div class="pt-4 pb-2 px-4">
                <p class="text-[10px] uppercase font-bold tracking-wider text-white/50">Stok & Distribusi</p>
            </div>
            <a href="{{ route('pangkalan.pengiriman.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('pangkalan.pengiriman.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-box-open w-5 text-center"></i> Terima LPG
            </a>
            <a href="{{ route('pangkalan.penyaluran.create') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('pangkalan.penyaluran.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-hand-holding-hand w-5 text-center"></i> Salurkan LPG
            </a>
            <a href="{{ route('pangkalan.stok') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('pangkalan.stok') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-chart-pie w-5 text-center"></i> Monitoring Stok
            </a>

            <div class="pt-4 pb-2 px-4">
                <p class="text-[10px] uppercase font-bold tracking-wider text-white/50">Data Konsumen</p>
            </div>
            <a href="{{ route('pangkalan.konsumen.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('pangkalan.konsumen.index') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-users w-5 text-center"></i> Data Konsumen
            </a>
            <a href="{{ route('pangkalan.konsumen.create') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('pangkalan.konsumen.create') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-user-plus w-5 text-center"></i> Registrasi Konsumen
            </a>
            
            <div class="pt-4 pb-2 px-4">
                <p class="text-[10px] uppercase font-bold tracking-wider text-white/50">Pemetaan</p>
            </div>
            <a href="{{ route('public.heatmap') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('public.heatmap') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-fire w-5 text-center"></i> Heatmap Kelangkaan
            </a>
            @endrole

            <!-- ─── Pengawas ─── -->
            @role('Pengawas')
            <div class="pt-4 pb-2 px-4">
                <p class="text-[10px] uppercase font-bold tracking-wider text-white/50">Pengawasan Eksekutif</p>
            </div>
            <a href="{{ route('public.peta') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('public.peta') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-map-location-dot w-5 text-center"></i> Peta GIS Pangkalan
            </a>
            <a href="{{ route('public.heatmap') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('public.heatmap') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-fire w-5 text-center"></i> Heatmap Kelangkaan
            </a>
            @endrole

            <!-- ─── Publik ─── -->
            @role('Publik')
            <div class="pt-4 pb-2 px-4">
                <p class="text-[10px] uppercase font-bold tracking-wider text-white/50">Layanan Publik</p>
            </div>
            <a href="{{ route('public.peta') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('public.peta') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-map-location-dot w-5 text-center"></i> Peta Pangkalan
            </a>
            <a href="{{ route('public.heatmap') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('public.heatmap') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-fire w-5 text-center"></i> Heatmap Kelangkaan
            </a>
            <a href="{{ route('public.keluhan.create') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('public.keluhan.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-comment-medical w-5 text-center"></i> Kirim Keluhan
            </a>
            @endrole

        </nav>

        <!-- Ruang Kosong di Bawah Sidebar -->
        <div class="p-6 border-t border-white/10 shrink-0">
            <p class="text-center text-[10px] text-white/30 font-medium">GASKEUN v2.4.0</p>
        </div>
    </aside>

    <!-- Mobile overlay -->
    <div x-show="sidebarOpen" x-transition.opacity
         class="fixed inset-0 z-40 md:hidden bg-slate-900/60 backdrop-blur-sm"
         @click="sidebarOpen = false"></div>

    <!-- ═══════════════════════════════════════════
         MAIN CONTENT (Background Putih/Abu)
    ═══════════════════════════════════════════ -->
    <div class="flex-1 flex flex-col overflow-hidden bg-white">

        <!-- Topbar (Putih Bersih) -->
        <header class="h-20 flex items-center justify-between px-8 shrink-0 bg-white border-b border-slate-200 z-40">
            
            <div class="flex items-center gap-4">
                <!-- Mobile menu toggle -->
                <button @click="sidebarOpen = true" class="md:hidden text-slate-500 hover:text-[#0B5240] transition-colors p-2 -ml-2">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                
                <!-- Title & Subtitle Navbar -->
                <div>
                    <h2 class="text-sm md:text-base font-bold text-slate-800 leading-tight">@yield('title', 'Dashboard')</h2>
                    <p class="text-[11px] md:text-xs text-slate-500 font-medium mt-0.5">Sistem Informasi Distribusi LPG — Kabupaten Garut</p>
                </div>
            </div>

            <!-- Bagian Kanan Navbar (Lonceng & Avatar) -->
            <div class="flex items-center gap-6">
                <!-- Tombol Notifikasi -->
                <button class="relative text-slate-400 hover:text-[#0B5240] transition-colors">
                    <i class="fa-regular fa-bell text-xl"></i>
                    <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full"></span>
                </button>
                
                <!-- Avatar User dengan Dropdown Alpine -->
                <div x-data="{ openProfile: false }" class="relative">
                    <button @click="openProfile = !openProfile" @click.away="openProfile = false" 
                            class="w-10 h-10 rounded-full bg-slate-200 border-2 border-slate-100 flex items-center justify-center overflow-hidden shadow-sm hover:border-[#0B5240] transition cursor-pointer">
                        <span class="text-slate-600 font-bold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="openProfile" x-transition.opacity.duration.200ms x-cloak
                         class="absolute right-0 mt-3 w-56 bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden">
                        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-slate-500 truncate mt-0.5">{{ auth()->user()->email ?? auth()->user()->username }}</p>
                        </div>
                        <div class="py-1">
                            <a href="{{ route('profile.password.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-[#e6f2ef] hover:text-[#0B5240] transition">
                                <i class="fa-solid fa-key w-4 text-center"></i> Ubah Password
                            </a>
                        </div>
                        <div class="border-t border-slate-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                    <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content Area -->
        <main class="flex-1 overflow-y-auto">
            
            <!-- Alert Session Messages -->
            @if (session('success'))
                <div class="mx-6 mt-6 p-4 rounded-lg bg-teal-50 border border-teal-200 text-teal-700 flex items-center gap-3 text-sm">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mx-6 mt-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 flex items-center gap-3 text-sm">
                    <i class="fa-solid fa-circle-xmark"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Konten Utama -->
            @yield('content')
            
        </main>
    </div>

    @stack('scripts')
</body>
</html>