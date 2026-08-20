<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — GASKEUN</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
    </style>
</head>
<body class="h-screen overflow-hidden antialiased bg-white text-slate-800">
    <main class="h-screen flex flex-col lg:flex-row">
        
        <!-- PANEL KIRI (Gradient Hijau Elegan) -->
        <section class="hidden lg:flex w-1/2 bg-gradient-to-br from-brand via-[#0f6b53] to-brand-dark text-white p-8 lg:p-12 flex-col relative justify-center">
            
            <!-- Background Glow/Accent -->
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-white opacity-5 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-brand-light opacity-10 rounded-full blur-[100px] pointer-events-none"></div>
            
            <!-- Area Konten Kiri -->
            <div class="w-full max-w-md mx-auto relative z-10">
                
                <!-- Logo Gede (Trik minus margin bawah biar teks ditarik ke atas) -->
                <div class="mb-0 -ml-4 relative z-20">
                    <img src="{{ asset('images/gaskeun01.png') }}" alt="Logo GASKEUN" class="w-72 md:w-80 h-auto object-contain brightness-0 invert filter drop-shadow-md -mb-6 md:-mb-8">
                </div>

                <!-- Kalimat Pendek (Ditarik rapet ke logo) -->
                <p class="text-teal-100/90 text-[13px] mb-6 leading-relaxed font-light pr-8 relative z-10">
                    Sistem informasi terpadu untuk pengawasan dan pengendalian distribusi logistik bersubsidi secara digital dan presisi.
                </p>

                <!-- 3 Card Fitur Berjejer -->
                <div class="flex flex-col gap-2.5 w-full relative z-10">
                    
                    <!-- Card 1 -->
                    <div class="bg-white/5 border border-white/10 rounded-xl py-3 px-4 flex gap-4 backdrop-blur-sm items-start transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/10 hover:border-white/30 hover:shadow-xl hover:shadow-black/20 group cursor-default">
                        <div class="bg-brand-dark p-2.5 rounded-lg flex items-center justify-center shrink-0 transition-colors duration-300 group-hover:bg-brand-light shadow-inner">
                            <i class="fa-solid fa-chart-line text-white text-sm"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-white mb-0.5 text-sm group-hover:text-teal-100 transition-colors">Real-time Monitoring</h3>
                            <p class="text-[11px] text-white/80 leading-snug">Pantau ketersediaan dan distribusi LPG 3kg secara langsung dari pangkalan hingga agen di seluruh wilayah Garut.</p>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white/5 border border-white/10 rounded-xl py-3 px-4 flex gap-4 backdrop-blur-sm items-start transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/10 hover:border-white/30 hover:shadow-xl hover:shadow-black/20 group cursor-default">
                        <div class="bg-brand-dark p-2.5 rounded-lg flex items-center justify-center shrink-0 transition-colors duration-300 group-hover:bg-brand-light shadow-inner">
                            <i class="fa-solid fa-map text-white text-sm"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-white mb-0.5 text-sm group-hover:text-teal-100 transition-colors">GIS Mapping</h3>
                            <p class="text-[11px] text-white/80 leading-snug">Pemetaan geografis interaktif untuk menganalisis titik distribusi dan mengidentifikasi area yang membutuhkan pasokan ekstra.</p>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white/5 border border-white/10 rounded-xl py-3 px-4 flex gap-4 backdrop-blur-sm items-start transition-all duration-300 hover:-translate-y-1.5 hover:bg-white/10 hover:border-white/30 hover:shadow-xl hover:shadow-black/20 group cursor-default">
                        <div class="bg-brand-dark p-2.5 rounded-lg flex items-center justify-center shrink-0 transition-colors duration-300 group-hover:bg-brand-light shadow-inner">
                            <i class="fa-solid fa-table-cells-large text-white text-sm"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-white mb-0.5 text-sm group-hover:text-teal-100 transition-colors">Executive Dashboard</h3>
                            <p class="text-[11px] text-white/80 leading-snug">Laporan komprehensif dan analitik data untuk mendukung pengambilan keputusan strategis oleh pimpinan dinas.</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- PANEL KANAN (Putih - Area Login) -->
        <section class="w-full lg:w-1/2 relative flex items-center justify-center p-8 bg-white h-screen">
            
            <!-- Tombol Kembali (Posisi Absolute di Pojok Kanan Atas) -->
            <a href="{{ route('home') }}" class="absolute top-6 right-6 lg:top-8 lg:right-8 text-sm font-semibold bg-brand text-white border-2 border-brand px-4 py-2 rounded-lg hover:bg-white hover:text-brand transition-colors duration-300 flex items-center gap-2 shadow-sm z-10">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>

            <!-- Container Form -->
            <div class="w-full max-w-sm">
                
                <!-- Munculin logo di mobile aja -->
                <div class="lg:hidden flex justify-center mb-8">
                    <img src="{{ asset('images/gaskeun01.png') }}" alt="Logo GASKEUN" class="h-12 w-auto object-contain">
                </div>

                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Masuk ke Akun Anda</h2>
                </div>

                @if (session('success'))
                    <div class="mb-5 rounded-lg px-4 py-3 text-sm bg-emerald-50 text-emerald-700 border border-emerald-200">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Input Username -->
                    <div>
                        <label for="username" class="block mb-2 text-xs font-bold text-slate-700">Username</label>
                        <div class="relative flex items-center">
                            <div class="absolute left-4 text-slate-400">
                                <i class="fa-regular fa-user"></i>
                            </div>
                            <input id="username" name="username" type="text" required autofocus value="{{ old('username') }}" placeholder="Masukkan username Anda"
                                class="w-full pl-11 pr-4 py-3.5 bg-white border border-slate-300 rounded-lg text-sm transition focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none text-slate-800 placeholder:text-slate-400">
                        </div>
                        @error('username')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <!-- Input Password -->
                    <div>
                        <label for="password" class="block mb-2 text-xs font-bold text-slate-700">Password</label>
                        <div class="relative flex items-center">
                            <div class="absolute left-4 text-slate-400">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <input id="password" name="password" type="password" required placeholder="••••••••"
                                class="w-full pl-11 pr-12 py-3.5 bg-white border border-slate-300 rounded-lg text-sm transition focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none text-slate-800 placeholder:text-slate-400 tracking-widest">
                            
                            <!-- Toggle Eye Icon -->
                            <button type="button" class="absolute right-4 text-slate-400 hover:text-slate-600 transition" onclick="togglePassword()">
                                <i class="fa-regular fa-eye" id="eye-icon"></i>
                            </button>
                        </div>
                        @error('password')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between mt-4">
                        <label for="remember" class="flex items-center gap-2 cursor-pointer text-sm text-slate-600">
                            <input id="remember" name="remember" type="checkbox" class="w-4 h-4 rounded border-slate-300 text-brand focus:ring-brand accent-brand">
                            Remember me
                        </label>
                        <a href="#" class="text-sm font-semibold text-brand hover:text-brand-light transition">Forgot password?</a>
                    </div>

                    <!-- Tombol Masuk -->
                    <button type="submit" class="w-full bg-brand hover:bg-brand-dark text-white rounded-lg px-4 py-3.5 text-sm font-semibold transition flex items-center justify-center gap-2 mt-4 shadow-md shadow-brand/20">
                        Masuk <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>

                <p class="mt-8 text-center text-sm text-slate-500">
                    Mengalami masalah? <a href="#" class="font-semibold text-brand hover:text-brand-light">Hubungi Admin Disperindag</a>
                </p>
            </div>

            <!-- Footer Area Kanan (Terkunci di bawah) -->
            <div class="absolute bottom-6 left-8 right-8 flex justify-between items-center text-xs text-slate-500 border-t border-slate-100 pt-4 px-2 lg:px-4">
                <div class="flex gap-4">
                    <a href="#" class="hover:text-slate-800 transition">Bantuan</a>
                    <span class="text-slate-300">•</span>
                    <a href="#" class="hover:text-slate-800 transition">Kebijakan Privasi</a>
                </div>
                <div>v2.4.0</div>
            </div>

        </section>
    </main>

    <!-- Script simpel buat toggle password hide/show -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordInput.classList.remove('tracking-widest'); // hapus spasi lebar kalo nampilin teks
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordInput.classList.add('tracking-widest');
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>