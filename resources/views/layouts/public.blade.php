<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Layanan Publik — GASKEUN')</title>
    <!-- Google Fonts: Poppins (selaras dengan dashboard) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        
        /* Step Indicator Styles (Light Theme) */
        .step-indicator { display: flex; align-items: flex-start; justify-content: space-between; position: relative; }
        .step { flex: 1; text-align: left; position: relative; z-index: 2; display: flex; align-items: flex-start; gap: 12px; }
        .step-circle {
            width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-weight: bold; font-size: 14px; background: white; border: 2px solid #E2E8F0; color: #94A3B8;
            transition: all 0.3s; box-shadow: 0 2px 4px rgba(0,0,0,0.02); shrink: 0;
        }
        .step-circle.active { background: #0B5240; border-color: #0B5240; color: white; box-shadow: 0 4px 12px rgba(11,82,64,0.3); }
        .step-circle.done { background: #10B981; border-color: #10B981; color: white; }
        .step-label { font-size: 12px; color: #94A3B8; margin-top: 2px; }
        .step-line {
            position: absolute; top: 18px; left: 40px; right: calc(100% - 30px); height: 2px;
            background: #E2E8F0; z-index: 1; transition: 0.3s;
        }
        .step-line.done { background: #10B981; }
        
        #line-1-2 { left: 16%; width: 34%; }
        #line-2-3 { left: 66%; width: 34%; }
        
        @media (max-width: 640px) {
            .step-indicator { flex-direction: column; gap: 20px; }
            .step-line { display: none; }
        }
        @yield('styles')
    </style>
</head>
<body class="bg-slate-50 min-h-screen text-slate-800">
    <!-- Header Minimalis -->
    <header class="bg-white border-b border-slate-200 shadow-sm relative z-10">
        <div class="max-w-4xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/gaskeun02.png') }}" alt="Logo GASKEUN" class="h-8 group-hover:opacity-80 transition">
            </a>
            <a href="{{ route('home') }}" class="text-sm font-semibold text-slate-500 hover:text-[#0B5240] transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </header>

    <div class="max-w-4xl mx-auto px-4 py-10 pb-24 relative z-0">
        @hasSection('header')
            @yield('header')
        @else
            <div class="text-center mb-10">
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#e6f2ef] text-[#0B5240] border border-[#14765C]/20 text-[10px] font-bold tracking-widest uppercase mb-4 shadow-sm">
                    <i class="fa-solid fa-bullhorn"></i> LAYANAN PUBLIK
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-3 tracking-tight">@yield('page-title')</h1>
                <p class="text-slate-500 text-sm md:text-base font-medium">@yield('page-subtitle')</p>
            </div>
        @endif

        @yield('content')
    </div>

    @yield('scripts')
</body>
</html>
