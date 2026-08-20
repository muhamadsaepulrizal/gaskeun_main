<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GASKEUN — @yield('title', 'Auth')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex font-['Space_Grotesk']">

    <!-- ═══ LEFT: Branding Panel ═══ -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden items-center justify-center bg-white border-r border-slate-200">
        <!-- Glow orbs -->
        <div class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 rounded-full bg-emerald-100/50 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-1/4 right-1/4 w-64 h-64 rounded-full bg-blue-100/50 blur-3xl pointer-events-none"></div>

        <!-- Content -->
        <div class="relative z-10 text-center px-14 max-w-lg">
            <!-- Logo icon -->
            <div class="flex justify-center mb-8">
                <div class="w-20 h-20 rounded-2xl flex items-center justify-center relative bg-gradient-to-br from-emerald-50 to-blue-50 border border-emerald-100 shadow-xl shadow-emerald-900/5">
                    <i class="fa-solid fa-fire-flame-curved text-4xl text-brand"></i>
                </div>
            </div>

            <h1 class="text-5xl font-extrabold tracking-tight text-slate-800 leading-tight">
                GAS<span class="text-transparent bg-clip-text bg-gradient-to-r from-brand to-emerald-400">KEUN.</span>
            </h1>
            <p class="mt-4 text-slate-500 text-sm leading-relaxed">
                Sistem Informasi Distribusi LPG Bersubsidi. Pantau penyaluran, kelangkaan, dan keluhan masyarakat secara real-time.
            </p>

            <!-- Feature pills -->
            <div class="flex flex-wrap justify-center gap-2 mt-8">
                <span class="text-xs px-3 py-1.5 rounded-full bg-blue-50 text-blue-600 border border-blue-100 font-semibold"><i class="fa-solid fa-map-location-dot mr-1"></i> Peta Distribusi</span>
                <span class="text-xs px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 font-semibold"><i class="fa-solid fa-chart-pie mr-1"></i> Heatmap</span>
                <span class="text-xs px-3 py-1.5 rounded-full bg-amber-50 text-amber-600 border border-amber-100 font-semibold"><i class="fa-solid fa-bolt mr-1"></i> Real-time</span>
            </div>
        </div>

        <!-- Bottom version tag -->
        <div class="absolute bottom-6 left-0 right-0 text-center">
            <span class="text-[10px] text-slate-400 font-bold tracking-widest uppercase">GASKEUN v2.0 · POWERED BY LARAVEL</span>
        </div>
    </div>

    <!-- ═══ RIGHT: Form Panel ═══ -->
    <div class="w-full lg:w-1/2 flex flex-col relative bg-slate-50">

        <!-- Back button -->
        <nav class="absolute top-0 right-0 p-6 z-10">
            <a href="/" class="flex items-center gap-2 text-xs font-bold text-slate-500 bg-white border border-slate-200 hover:bg-slate-50 hover:text-brand transition-colors px-4 py-2 rounded-xl shadow-sm">
                <i class="fa-solid fa-arrow-left"></i> Beranda
            </a>
        </nav>

        <!-- Mobile logo -->
        <div class="lg:hidden flex justify-center pt-16 pb-4">
            <span class="text-2xl font-extrabold tracking-tight text-slate-800">GAS<span class="text-brand">KEUN.</span></span>
        </div>

        <!-- Form Area -->
        <main class="flex-grow flex items-center justify-center px-8 sm:px-12 lg:px-16 py-12">
            <div class="w-full max-w-md">
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center gap-3 text-sm font-medium">
                        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 flex items-center gap-3 text-sm font-medium">
                        <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>
