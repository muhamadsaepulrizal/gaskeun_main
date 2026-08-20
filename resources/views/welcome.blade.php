<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GASKEUN - Sistem Kendali Elpiji Kabupaten Garut</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        teal: {
                            50: '#e9f8f1',
                            100: '#c8ecda',
                            500: '#22b573', 
                            600: '#1c9c63', 
                            700: '#178252', 
                            800: '#126942',
                            900: '#0e5133',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; overflow-x: hidden; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="text-slate-800 antialiased">

    <!-- 1. Navbar -->
    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-lg border-b border-slate-200/50 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20"> 
                
                <!-- Kiri: Logo -->
                <div class="flex items-center" data-aos="fade-right">
                    <img src="{{ asset('images/gaskeun01.png') }}" alt="Logo GASKEUN" class="h-12 md:h-20 w-auto object-contain scale-[1.5] md:scale-[2.5] origin-left -ml-4 md:-ml-6">
                </div>

                <!-- Kanan: Menu + Tombol Login -->
                <div class="hidden md:flex items-center space-x-10" data-aos="fade-left">
                    <div class="flex space-x-8">
                        <a href="#beranda" class="text-slate-600 hover:text-teal-500 font-medium transition">Beranda</a>
                        <a href="#alur" class="text-slate-600 hover:text-teal-500 font-medium transition">Alur Distribusi</a>
                        <a href="#fitur" class="text-slate-600 hover:text-teal-500 font-medium transition">Fitur</a>
                        <a href="#pengaduan" class="text-slate-600 hover:text-teal-500 font-medium transition">Pengaduan</a>
                    </div>
                    <a href="{{ route('login') }}" class="bg-teal-500 hover:bg-teal-600 text-white px-6 py-2.5 rounded-lg font-medium transition shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-right-to-bracket"></i> Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- 2. Hero Section dengan Gradient Ambient -->
    <section id="beranda" class="pt-32 pb-20 lg:pt-40 lg:pb-32 relative overflow-hidden bg-slate-50">
        <!-- Ambient Glow Background -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
            <div class="absolute -top-[10%] -left-[5%] w-[40%] h-[50%] rounded-full bg-teal-200/40 blur-[120px]"></div>
            <div class="absolute top-[20%] right-[0%] w-[30%] h-[40%] rounded-full bg-emerald-200/30 blur-[100px]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="lg:grid lg:grid-cols-2 lg:gap-12 items-center">
                <div class="mb-12 lg:mb-0" data-aos="fade-up" data-aos-duration="1000">
                    <h1 class="text-4xl lg:text-5xl font-bold text-slate-900 leading-tight mb-6">
                        Transparansi Distribusi <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-emerald-500">LPG 3kg</span> untuk Kabupaten Garut
                    </h1>
                    <p class="text-lg text-slate-600 mb-8 leading-relaxed">
                        Galeri Alokasi dan Sistem Kendali Elpiji Untuk Nilai Sasaran (GASKEUN). Memantau distribusi gas bersubsidi dari tingkat agen hingga tepat sasaran ke rumah tangga, UMKM, petani, dan nelayan.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 flex-wrap">
                        <a href="{{ route('public.peta') }}" class="bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white px-6 py-3.5 rounded-xl font-medium transition shadow-lg shadow-teal-500/30 text-center flex items-center justify-center gap-2">
                            <i class="fa-solid fa-map-location-dot"></i> Peta Pangkalan
                        </a>

                        <a href="{{ route('public.keluhan.create') }}" class="bg-white/80 backdrop-blur border-2 border-slate-200 hover:border-teal-500 hover:text-teal-500 text-slate-700 px-6 py-3.5 rounded-xl font-medium transition text-center flex items-center justify-center gap-2">
                            <i class="fa-solid fa-bullhorn"></i> Lapor Kendala
                        </a>
                    </div>
                </div>
                
                <div class="relative lg:ml-10" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                    <div class="absolute inset-0 bg-gradient-to-tr from-teal-200 to-emerald-100 rounded-3xl transform rotate-3 scale-105 -z-10 opacity-60"></div>
                    <div class="bg-white rounded-2xl shadow-xl border border-slate-100/50 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Peta Garut" class="w-full h-80 object-cover opacity-80 mix-blend-luminosity">
                        <div class="absolute inset-0 bg-gradient-to-tr from-teal-900/40 to-transparent"></div>
                        
                        <div class="absolute top-1/4 left-1/3 w-4 h-4 bg-teal-400 rounded-full border-2 border-white shadow-[0_0_0_4px_rgba(34,181,115,0.3)] animate-pulse"></div>
                        <div class="absolute top-1/2 left-1/2 w-4 h-4 bg-yellow-400 rounded-full border-2 border-white shadow-[0_0_0_4px_rgba(250,204,21,0.3)]"></div>
                        <div class="absolute bottom-1/3 right-1/4 w-4 h-4 bg-teal-400 rounded-full border-2 border-white shadow-[0_0_0_4px_rgba(34,181,115,0.3)]"></div>
                        <div class="absolute top-1/3 right-1/3 w-4 h-4 bg-red-500 rounded-full border-2 border-white shadow-[0_0_0_4px_rgba(239,68,68,0.3)] animate-pulse"></div>

                        <div class="absolute bottom-6 left-6 right-6 bg-white/90 backdrop-blur-md rounded-xl p-4 shadow-lg border border-slate-100/50">
                            <div class="flex items-center gap-4">
                                <div class="bg-gradient-to-br from-teal-50 to-teal-100 text-teal-600 w-12 h-12 rounded-lg flex items-center justify-center text-xl shadow-sm">
                                    <i class="fa-solid fa-chart-pie"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-800">Status Stok Terkini</h4>
                                    <p class="text-xs text-slate-500">Data termonitor secara visual</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Statistik Ringkas -->
    <section id="statistik" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl p-6 shadow-md shadow-slate-200/50 border border-slate-100 flex items-center gap-4" data-aos="fade-up" data-aos-delay="200">
                <div class="bg-emerald-50 w-14 h-14 rounded-full flex items-center justify-center text-emerald-500 text-2xl">
                    <i class="fa-solid fa-store"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500 font-medium">Pangkalan Aktif</p>
                    <h3 class="text-2xl font-bold text-slate-800"><span class="counter" data-target="{{ $pangkalanAktif ?? 0 }}">0</span> <span class="text-sm font-normal text-slate-500">Titik</span></h3>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-md shadow-slate-200/50 border border-slate-100 flex items-center gap-4" data-aos="fade-up" data-aos-delay="300">
                <div class="bg-blue-50 w-14 h-14 rounded-full flex items-center justify-center text-blue-500 text-2xl">
                    <i class="fa-solid fa-building-user"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500 font-medium">Agen Terdaftar</p>
                    <h3 class="text-2xl font-bold text-slate-800"><span class="counter" data-target="{{ $agenTerdaftar ?? 0 }}">0</span> <span class="text-sm font-normal text-slate-500">Agen</span></h3>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-md shadow-slate-200/50 border border-slate-100 flex items-center gap-4" data-aos="fade-up" data-aos-delay="400">
                <div class="bg-teal-50 w-14 h-14 rounded-full flex items-center justify-center text-teal-500 text-2xl">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500 font-medium">Kec. Berstatus Aman</p>
                    <h3 class="text-2xl font-bold text-slate-800"><span class="counter" data-target="{{ $kecamatanAman ?? 0 }}">0</span> / {{ $totalKecamatan ?? 42 }} <span class="text-sm font-normal text-slate-500">Kec.</span></h3>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Alur Distribusi -->
    <section id="alur" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-slate-900 mb-4">Alur Distribusi Digital Terintegrasi</h2>
                <p class="text-slate-600 max-w-2xl mx-auto">Sistem GASKEUN mendigitalisasi proses rantai pasok dari hulu ke hilir untuk memastikan ketersediaan data yang valid dan terkini.</p>
            </div>
            
            <div class="relative" data-aos="zoom-in" data-aos-duration="1000">
                <div class="hidden lg:block absolute top-1/2 left-0 right-0 h-1 bg-gradient-to-r from-teal-100 via-teal-300 to-slate-200 -translate-y-1/2 z-0 rounded-full"></div>
                <div class="flex overflow-x-auto hide-scrollbar gap-6 lg:gap-0 lg:justify-between relative z-10 pb-8 lg:pb-0">
                    
                    <div class="flex flex-col items-center flex-none w-48 text-center bg-white lg:bg-transparent p-4 lg:p-0 rounded-xl lg:rounded-none shadow-sm lg:shadow-none border lg:border-none border-slate-100">
                        <div class="w-16 h-16 bg-slate-50 rounded-full border-4 border-white shadow-md flex items-center justify-center text-teal-600 text-xl mb-4 relative z-10">
                            <i class="fa-solid fa-gas-pump"></i>
                        </div>
                        <h4 class="font-bold text-slate-800 mb-2">Pertamina</h4>
                        <p class="text-xs text-slate-500">Penetapan kuota wilayah</p>
                    </div>

                    <div class="flex items-center text-teal-300 lg:hidden"><i class="fa-solid fa-chevron-right"></i></div>

                    <div class="flex flex-col items-center flex-none w-48 text-center bg-white lg:bg-transparent p-4 lg:p-0 rounded-xl lg:rounded-none shadow-sm lg:shadow-none border lg:border-none border-slate-100">
                        <div class="w-16 h-16 bg-gradient-to-br from-teal-400 to-teal-500 rounded-full border-4 border-white shadow-md flex items-center justify-center text-white text-xl mb-4 relative z-10 hover:scale-110 transition">
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>
                        <h4 class="font-bold text-slate-800 mb-2">Agen Pengirim</h4>
                        <p class="text-xs text-slate-500">Input pengiriman & DO</p>
                    </div>

                    <div class="flex items-center text-teal-300 lg:hidden"><i class="fa-solid fa-chevron-right"></i></div>

                    <div class="flex flex-col items-center flex-none w-48 text-center bg-white lg:bg-transparent p-4 lg:p-0 rounded-xl lg:rounded-none shadow-sm lg:shadow-none border lg:border-none border-slate-100">
                        <div class="w-16 h-16 bg-gradient-to-br from-teal-400 to-teal-500 rounded-full border-4 border-white shadow-md flex items-center justify-center text-white text-xl mb-4 relative z-10 hover:scale-110 transition">
                            <i class="fa-solid fa-box-open"></i>
                        </div>
                        <h4 class="font-bold text-slate-800 mb-2">Pangkalan</h4>
                        <p class="text-xs text-slate-500">Verifikasi penerimaan fisik</p>
                    </div>

                    <div class="flex items-center text-teal-300 lg:hidden"><i class="fa-solid fa-chevron-right"></i></div>

                    <div class="flex flex-col items-center flex-none w-48 text-center bg-white lg:bg-transparent p-4 lg:p-0 rounded-xl lg:rounded-none shadow-sm lg:shadow-none border lg:border-none border-slate-100">
                        <div class="w-16 h-16 bg-gradient-to-br from-teal-400 to-teal-500 rounded-full border-4 border-white shadow-md flex items-center justify-center text-white text-xl mb-4 relative z-10 hover:scale-110 transition">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <h4 class="font-bold text-slate-800 mb-2">Konsumen Sasaran</h4>
                        <p class="text-xs text-slate-500">Penyaluran terdata</p>
                    </div>

                    <div class="flex items-center text-teal-300 lg:hidden"><i class="fa-solid fa-chevron-right"></i></div>

                    <div class="flex flex-col items-center flex-none w-48 text-center bg-white lg:bg-transparent p-4 lg:p-0 rounded-xl lg:rounded-none shadow-sm lg:shadow-none border lg:border-none border-slate-100">
                        <div class="w-16 h-16 bg-slate-800 rounded-full border-4 border-white shadow-md flex items-center justify-center text-white text-xl mb-4 relative z-10">
                            <i class="fa-solid fa-desktop"></i>
                        </div>
                        <h4 class="font-bold text-slate-800 mb-2">Disperindag</h4>
                        <p class="text-xs text-slate-500">Dashboard & Heatmap</p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- 5. Fitur Utama -->
    <section id="fitur" class="pt-24 pb-12 bg-gradient-to-b from-slate-50 to-white border-t border-slate-200/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-slate-900 mt-2 mb-4">Fitur Utama GASKEUN</h2>
                <p class="text-slate-600 max-w-2xl mx-auto">Dirancang khusus untuk memenuhi kebutuhan pengawasan distribusi logistik bersubsidi secara menyeluruh.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-lg hover:shadow-teal-100 transition group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-12 h-12 bg-teal-50 text-teal-500 rounded-lg flex items-center justify-center text-xl mb-6 group-hover:bg-gradient-to-br group-hover:from-teal-400 group-hover:to-teal-600 group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-sitemap"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-3">Manajemen Agen & Pangkalan</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Pengelolaan data master, pengaturan kuota bulanan, dan pemetaan koordinat lokasi GIS secara presisi.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-lg hover:shadow-teal-100 transition group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-12 h-12 bg-teal-50 text-teal-500 rounded-lg flex items-center justify-center text-xl mb-6 group-hover:bg-gradient-to-br group-hover:from-teal-400 group-hover:to-teal-600 group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-hand-holding-hand"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-3">Penyaluran Tepat Sasaran</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Log transaksi detail ke konsumen sasaran: rumah tangga, UMKM, petani sasaran, dan nelayan sasaran.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-lg hover:shadow-teal-100 transition group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-12 h-12 bg-teal-50 text-teal-500 rounded-lg flex items-center justify-center text-xl mb-6 group-hover:bg-gradient-to-br group-hover:from-teal-400 group-hover:to-teal-600 group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-3">GIS Pemetaan Pangkalan</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Pemantauan visual sebaran pangkalan dengan indikator warna status stok: aman, menipis, atau kosong.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-lg hover:shadow-teal-100 transition group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-12 h-12 bg-teal-50 text-teal-500 rounded-lg flex items-center justify-center text-xl mb-6 group-hover:bg-gradient-to-br group-hover:from-teal-400 group-hover:to-teal-600 group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-fire"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-3">Heatmap Kelangkaan LPG</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Analisis visual berbasis wilayah kecamatan untuk mendeteksi dini potensi kelangkaan pasokan gas.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-lg hover:shadow-teal-100 transition group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-12 h-12 bg-teal-50 text-teal-500 rounded-lg flex items-center justify-center text-xl mb-6 group-hover:bg-gradient-to-br group-hover:from-teal-400 group-hover:to-teal-600 group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-scale-balanced"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-3">Rekomendasi Kuota</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Sistem Pendukung Keputusan (SPK) berbasis algoritma kepadatan penduduk dan riwayat serapan.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-lg hover:shadow-teal-100 transition group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-12 h-12 bg-teal-50 text-teal-500 rounded-lg flex items-center justify-center text-xl mb-6 group-hover:bg-gradient-to-br group-hover:from-teal-400 group-hover:to-teal-600 group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-comment-dots"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-3">Kanal Pengaduan Warga</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Fasilitas lapor HET tidak sesuai, stok gaib, atau penimbunan, terintegrasi upload foto & koordinat GPS.</p>
                </div>
            </div>
        </div>
    </section>



    <!-- 7. Untuk Siapa Sistem Ini -->
    <section class="pt-12 pb-24 bg-gradient-to-b from-white to-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-slate-900 mb-4">Pengguna Sistem</h2>
                <p class="text-slate-600 max-w-2xl mx-auto">Sistem terpadu dengan hak akses berjenjang untuk menjaga akuntabilitas.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl p-8 border border-slate-200 text-center hover:border-teal-500 hover:shadow-lg transition duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-slate-800 text-white rounded-full flex items-center justify-center text-2xl mx-auto mb-6 shadow-md">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Pemerintah</h3>
                    <p class="text-sm text-slate-600">Disperindag & Pengawas. Akses monitoring, dashboard eksekutif, rekomendasi kuota, dan tindak lanjut laporan.</p>
                </div>

                <div class="bg-white rounded-xl p-8 border border-slate-200 text-center hover:border-teal-500 hover:shadow-lg transition duration-300" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-400 to-teal-600 text-white rounded-full flex items-center justify-center text-2xl mx-auto mb-6 shadow-md shadow-teal-500/30">
                        <i class="fa-solid fa-truck-moving"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Agen & Pangkalan</h3>
                    <p class="text-sm text-slate-600">Pelaku rantai pasok. Akses input pengiriman, verifikasi penerimaan, pencatatan penyaluran, dan update stok.</p>
                </div>

                <div class="bg-white rounded-xl p-8 border border-slate-200 text-center hover:border-teal-500 hover:shadow-lg transition duration-300" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-emerald-500 text-white rounded-full flex items-center justify-center text-2xl mx-auto mb-6 shadow-md shadow-emerald-500/30">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Masyarakat</h3>
                    <p class="text-sm text-slate-600">Warga Garut. Akses pencarian lokasi pangkalan terdekat, cek ketersediaan, dan portal pengaduan publik.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. Kanal Pengaduan dengan Gradient -->
    <section id="pengaduan" class="py-20 bg-gradient-to-r from-slate-900 via-slate-800 to-teal-900 relative overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 20px 20px;"></div>
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center" data-aos="zoom-in" data-aos-duration="800">
            <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 text-white rounded-full flex items-center justify-center text-2xl mx-auto mb-6 shadow-lg shadow-red-500/40">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h2 class="text-3xl font-bold text-white mb-4">Temukan Indikasi Pelanggaran?</h2>
            <p class="text-slate-300 text-lg mb-8 max-w-2xl mx-auto">
                Bantu kami menjaga kestabilan. Segera laporkan jika Anda menemukan harga jual di atas HET, kelangkaan tidak wajar, atau indikasi penimbunan LPG 3kg.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('public.keluhan.create') }}" class="bg-gradient-to-r from-teal-500 to-emerald-500 text-white hover:from-teal-400 hover:to-emerald-400 px-8 py-4 rounded-xl font-bold text-lg transition shadow-xl shadow-teal-500/20 flex items-center justify-center gap-3 transform hover:-translate-y-1">
                    <i class="fa-solid fa-camera"></i> Buat Laporan Pengaduan
                </a>
                <a href="{{ route('public.keluhan.status') }}" class="bg-slate-800 text-white hover:bg-slate-700 px-8 py-4 rounded-xl font-bold text-lg transition shadow-xl border border-slate-600 flex items-center justify-center gap-3 transform hover:-translate-y-1">
                    <i class="fa-solid fa-search"></i> Cek Status Laporan
                </a>
            </div>
            <p class="text-slate-400 text-sm mt-6">* Laporan dapat dilampirkan dengan foto bukti dan titik koordinat GPS.</p>
        </div>
    </section>

    <!-- 9. Footer -->
    <footer class="bg-slate-950 text-slate-400 py-12 lg:py-16 overflow-hidden relative">
        <!-- Subtle Glow in Footer -->
        <div class="absolute top-0 right-0 w-[300px] h-[300px] bg-teal-900/20 blur-[120px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-12 mb-12">
                
                <!-- Kolom 1: Logo dan Deskripsi (Porsi 4 dari 12 kolom) -->
                <div class="md:col-span-12 lg:col-span-4 lg:pr-8" data-aos="fade-up" data-aos-delay="100">
                    
                    <div class="mb-2 md:mb-4 flex items-center">
                        <img src="{{ asset('images/gaskeun01.png') }}" alt="Logo GASKEUN" class="h-24 md:h-[100px] w-auto object-contain scale-[2] md:scale-[2.5] origin-left -ml-6 md:-ml-10 brightness-0 invert opacity-80">
                    </div>
                    
                    <p class="text-sm leading-relaxed mb-6 relative z-10">
                        Galeri Alokasi dan Sistem Kendali Elpiji Untuk Nilai Sasaran. Sistem Informasi terpadu untuk monitoring distribusi LPG 3kg bersubsidi.
                    </p>
                    <div class="flex gap-4 relative z-10">
                        <a href="#" class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center hover:bg-teal-500 hover:text-white transition shadow-lg"><i class="fa-brands fa-facebook-f text-sm"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center hover:bg-teal-500 hover:text-white transition shadow-lg"><i class="fa-brands fa-instagram text-sm"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center hover:bg-teal-500 hover:text-white transition shadow-lg"><i class="fa-brands fa-twitter text-sm"></i></a>
                    </div>
                </div>

                <!-- Kolom 2: Hubungi Kami -->
                <div class="md:col-span-6 lg:col-span-4" data-aos="fade-up" data-aos-delay="200">
                    <h4 class="text-white font-bold mb-6 uppercase text-sm tracking-wider">Hubungi Kami</h4>
                    <ul class="space-y-4 text-sm">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-building text-teal-500 mt-1"></i>
                            <span>Disperindag ESDM<br>Pemerintah Kabupaten Garut</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-location-dot text-teal-500"></i>
                            <span>Jl. Pembangunan No.XX, Tarogong Kidul, Garut</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-envelope text-teal-500"></i>
                            <span>info@indagesdm.garutkab.go.id</span>
                        </li>
                    </ul>
                </div>

                <!-- Kolom 3: Link Cepat -->
                <div class="md:col-span-3 lg:col-span-2" data-aos="fade-up" data-aos-delay="300">
                    <h4 class="text-white font-bold mb-6 uppercase text-sm tracking-wider">Link Cepat</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#beranda" class="hover:text-teal-400 transition">Beranda</a></li>
                        <li><a href="#alur" class="hover:text-teal-400 transition">Alur Distribusi</a></li>
                        <li><a href="#fitur" class="hover:text-teal-400 transition">Fitur Sistem</a></li>
                    </ul>
                </div>

                <!-- Kolom 4: Bantuan & Legal -->
                <div class="md:col-span-3 lg:col-span-2" data-aos="fade-up" data-aos-delay="400">
                    <h4 class="text-white font-bold mb-6 uppercase text-sm tracking-wider">Bantuan & Legal</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="hover:text-teal-400 transition">Panduan Pengguna</a></li>
                        <li><a href="#" class="hover:text-teal-400 transition">FAQ</a></li>
                        <li><a href="#" class="hover:text-teal-400 transition">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-teal-400 transition">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="pt-8 border-t border-slate-800/50 text-center text-sm flex flex-col md:flex-row justify-between items-center gap-4">
                <p>&copy; 2026 Disperindag ESDM Kabupaten Garut. Hak Cipta Dilindungi.</p>
                <p>Dikembangkan untuk kesejahteraan masyarakat.</p>
            </div>
        </div>
    </footer>

    <!-- Script Init AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true, // Animasi cuma jalan sekali waktu di-scroll ke bawah
            offset: 50, // Muncul animasi pas elemen masuk 50px ke layar
            duration: 800, // Durasi smooth transisinya
        });

        // Script Animasi Counter Angka (Diperbarui biar lebih mulus dan durasi sama)
        const counters = document.querySelectorAll('.counter');
        const animationDuration = 2500; // Durasi animasi dalam milidetik (2.5 detik). Atur aja kalau mau lebih lama.

        const animateCounters = () => {
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                let startTime = null;

                const step = (currentTime) => {
                    if (!startTime) startTime = currentTime;
                    // Hitung progress dari 0 sampai 1
                    const progress = Math.min((currentTime - startTime) / animationDuration, 1);
                    
                    // Efek ease-out (makin dekat ke target makin pelan biar estetik)
                    const easeProgress = 1 - Math.pow(1 - progress, 3);
                    const currentVal = Math.floor(easeProgress * target);

                    // Update tulisan angka dengan format ribuan (titik)
                    counter.innerText = currentVal.toLocaleString('id-ID');

                    // Lanjut animasi kalau progress belum 1 (belum selesai)
                    if (progress < 1) {
                        requestAnimationFrame(step);
                    } else {
                        // Pastikan berhenti tepat di angka target pas durasi habis
                        counter.innerText = target.toLocaleString('id-ID'); 
                    }
                };
                
                requestAnimationFrame(step);
            });
        };

        // Bikin observer biar counter jalan PAS DILIHAT layar aja
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if(entry.isIntersecting) {
                    animateCounters();
                    observer.unobserve(entry.target); // Cukup jalan 1x
                }
            });
        }, { threshold: 0.5 }); // Jalan pas udah 50% kelihatan

        // Targetin box statistik
        const statsSection = document.querySelector('#statistik');
        if (statsSection) {
            observer.observe(statsSection);
        }
    </script>
</body>
</html>