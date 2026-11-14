<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roomify – Pesan Hotel Nyaman & Murah di Kota Medan</title>
    <meta name="description" content="Temukan 187+ hotel terbaik di Medan dekat landmark populer seperti Istana Maimun, Sun Plaza, dan Stasiun Kereta Api. Garansi harga hemat, check-in instan, dan navigasi Google Maps.">

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome 6.5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CDN with custom config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    colors: {
                        brand: {
                            50:  '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-delay': 'float 6s ease-in-out 2s infinite',
                        'float-slow': 'float 8s ease-in-out 4s infinite',
                        'pulse-glow': 'pulseGlow 4s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-14px)' },
                        },
                        pulseGlow: {
                            '0%, 100%': { opacity: 0.4, transform: 'scale(1)' },
                            '50%': { opacity: 0.8, transform: 'scale(1.08)' },
                        }
                    }
                }
            }
        }
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
    </script>

    <style>
        * { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; }
        body { scroll-behavior: smooth; background-color: #0b0f19; color: #f1f5f9; }

        /* ─── Hero Ambient Mesh with Authentic Landmark Backdrop ─── */
        .hero-mesh {
            background: linear-gradient(180deg, rgba(11, 15, 25, 0.72) 0%, rgba(11, 15, 25, 0.85) 60%, #0b0f19 100%),
                        url('/images/landmarks/masjid-raya.jpg') center/cover no-repeat;
            background-attachment: fixed;
        }

        /* ─── Glassmorphism ─── */
        .glass-nav {
            background: rgba(11, 15, 25, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .glass-card-light {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.07);
        }

        /* ─── Gradients & Typography ─── */
        .text-gradient {
            background: linear-gradient(135deg, #60a5fa 0%, #a78bfa 50%, #f472b6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .text-gradient-gold {
            background: linear-gradient(135deg, #fcd34d 0%, #f59e0b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #ec4899 100%);
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 32px -5px rgba(99, 102, 241, 0.6);
            filter: brightness(1.08);
        }

        /* ─── Landmark Card ─── */
        .landmark-card {
            border-radius: 22px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }
        .landmark-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 22px 45px -10px rgba(99, 102, 241, 0.35);
            border-color: rgba(165, 180, 252, 0.4);
            background: rgba(255, 255, 255, 0.07);
        }
        .landmark-img-wrap {
            position: relative;
            overflow: hidden;
            height: 210px;
        }
        .landmark-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .landmark-card:hover .landmark-img-wrap img {
            transform: scale(1.08);
        }

        /* ─── Form Inputs ─── */
        .search-input {
            background: rgba(255, 255, 255, 0.95);
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            color: #0f172a;
            font-size: 14px;
            padding: 12px 16px;
            transition: all 0.2s ease;
            width: 100%;
        }
        .search-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
            background: #ffffff;
        }

        /* ─── Facility Chips ─── */
        .fac-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border: 1.5px solid rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.06);
            color: #e2e8f0;
            transition: all 0.2s ease;
            user-select: none;
        }
        .fac-chip input { display: none; }
        .fac-chip.active {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border-color: #818cf8;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
        }

        /* ─── Autocomplete Dropdown ─── */
        #suggest-box {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            margin-top: 6px;
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.18);
            z-index: 60;
            overflow: hidden;
            display: none;
        }
        .suggest-item {
            padding: 12px 16px;
            font-size: 13px;
            color: #1e293b;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background 0.15s ease;
        }
        .suggest-item:hover {
            background: #f1f5f9;
            color: #4f46e5;
        }
    </style>
</head>
<body class="text-slate-100 antialiased overflow-x-hidden">

{{-- ═══════════════════════════════════════════════════════════
     TOP LIVE SYSTEM CLOCK & STICKY GLASS NAVBAR
═══════════════════════════════════════════════════════════ --}}
<div class="fixed top-0 left-0 right-0 z-50">
    <x-live-clock-bar />
    <header class="glass-nav transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">

                {{-- BRAND LOGO --}}
                <a href="{{ url('/') }}" class="flex items-center gap-3 group no-underline">
                    <div class="w-11 h-11 relative transform transition-transform duration-300 group-hover:scale-105">
                        <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full drop-shadow-lg">
                            <defs>
                                <linearGradient id="nb-bg-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#4F46E5"/>
                                    <stop offset="50%" stop-color="#7C3AED"/>
                                    <stop offset="100%" stop-color="#EC4899"/>
                                </linearGradient>
                                <linearGradient id="nb-gold-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#FCD34D"/>
                                    <stop offset="100%" stop-color="#F59E0B"/>
                                </linearGradient>
                            </defs>
                            <rect width="48" height="48" rx="14" fill="url(#nb-bg-grad)"/>
                            <rect x="0.75" y="0.75" width="46.5" height="46.5" rx="13.25" stroke="rgba(255,255,255,0.3)" stroke-width="1.5"/>
                            <path d="M12 36V22C12 15.3726 17.3726 10 24 10C30.6274 10 36 15.3726 36 22V36" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round"/>
                            <path d="M18 36V18H25.5C28 18 29.5 19.5 29.5 22C29.5 24.5 28 26 25.5 26H18M24 26L30 36" stroke="#FFFFFF" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M34 11L35.2 13.8L38 15L35.2 16.2L34 19L32.8 16.2L30 15L32.8 13.8L34 11Z" fill="url(#nb-gold-grad)"/>
                            <circle cx="24" cy="31" r="1.5" fill="#FCD34D"/>
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl font-black tracking-tight text-white group-hover:text-indigo-200 transition">
                                Roomify
                            </span>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-400 tracking-wider uppercase">Pemesanan Hotel Medan</span>
                    </div>
                </a>

                {{-- NAV LINKS --}}
                <nav class="hidden md:flex items-center gap-1 bg-white/5 p-1.5 rounded-2xl border border-white/10 backdrop-blur-md">
                    <a href="#destinasi" class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-300 hover:text-white hover:bg-white/10 transition">
                        <i class="fa-solid fa-map-location-dot mr-1.5 text-indigo-400"></i>Landmark Medan
                    </a>
                    <a href="{{ url('/search') }}" class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-300 hover:text-white hover:bg-white/10 transition">
                        <i class="fa-solid fa-magnifying-glass mr-1.5 text-indigo-400"></i>Cari Hotel
                    </a>
                    <a href="#hotel-pilihan" class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-300 hover:text-white hover:bg-white/10 transition">
                        <i class="fa-solid fa-hotel mr-1.5 text-indigo-400"></i>Hotel Pilihan
                    </a>
                    <a href="#keunggulan" class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-300 hover:text-white hover:bg-white/10 transition">
                        <i class="fa-solid fa-shield-halved mr-1.5 text-indigo-400"></i>Keunggulan
                    </a>
                </nav>

                {{-- ACTION BUTTONS --}}
                <div class="flex items-center gap-3">
                    <button onclick="toggleTheme()" type="button" class="p-2.5 rounded-xl text-slate-300 hover:text-white bg-white/10 hover:bg-white/20 border border-white/10 transition" aria-label="Toggle theme">
                        <i class="fa-solid fa-moon hidden dark:inline text-amber-300"></i>
                        <i class="fa-solid fa-sun inline dark:hidden text-amber-400"></i>
                    </button>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-slate-300 hover:text-white px-3 py-2 rounded-xl hover:bg-white/5 transition flex items-center gap-2">
                            <i class="fa-solid fa-user-circle text-indigo-400 text-lg"></i>
                            <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                        </a>
                        <a href="{{ url('/search') }}" class="btn-gradient text-white text-sm font-bold px-5 py-2.5 rounded-xl flex items-center gap-2">
                            <i class="fa-solid fa-compass"></i>
                            <span>Jelajahi</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-300 hover:text-white px-4 py-2 rounded-xl hover:bg-white/10 transition hidden sm:inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-right-to-bracket text-indigo-400"></i> Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn-gradient text-white text-sm font-bold px-5 py-2.5 rounded-xl flex items-center gap-1.5">
                            <i class="fa-solid fa-user-plus"></i> Daftar
                        </a>
                    @endauth
                    <!-- Mobile menu toggle -->
                    <button type="button" class="md:hidden p-2 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition" onclick="document.getElementById('mobile-nav-welcome').classList.toggle('hidden')">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                </div>

            </div>
        </div>
        <!-- Mobile dropdown menu -->
        <div id="mobile-nav-welcome" class="hidden md:hidden px-4 py-3 bg-slate-900/95 border-b border-white/10 space-y-2 backdrop-blur-xl">
            <a href="{{ url('/search') }}" class="block px-3 py-2 rounded-xl text-sm font-bold text-white bg-indigo-600/30 border border-indigo-500/40">
                <i class="fa-solid fa-hotel mr-2 text-indigo-400"></i>Cari Seluruh 187 Hotel
            </a>
            <a href="#destinasi" class="block px-3 py-2 rounded-xl text-sm text-slate-300 hover:text-white">
                <i class="fa-solid fa-map-location-dot mr-2 text-indigo-400"></i>13 Landmark Medan
            </a>
            <a href="#hotel-pilihan" class="block px-3 py-2 rounded-xl text-sm text-slate-300 hover:text-white">
                <i class="fa-solid fa-star mr-2 text-amber-400"></i>Hotel Pilihan
            </a>
            <a href="#keunggulan" class="block px-3 py-2 rounded-xl text-sm text-slate-300 hover:text-white">
                <i class="fa-solid fa-shield-halved mr-2 text-indigo-400"></i>Keunggulan Roomify
            </a>
        </div>
    </header>
</div>

{{-- ═══════════════════════════════════════════════════════════
     HERO SECTION WITH GLOWING MESH & COMPREHENSIVE SEARCH
═══════════════════════════════════════════════════════════ --}}
<section class="hero-mesh pt-44 pb-24 px-4 sm:px-6 lg:px-8 relative overflow-hidden min-h-[92vh] flex items-center">
    <!-- Ambient Glowing Orbs -->
    <div class="absolute top-1/4 left-10 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none animate-pulse-glow"></div>
    <div class="absolute bottom-10 right-10 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl pointer-events-none animate-pulse-glow" style="animation-delay: 2s;"></div>

    <div class="max-w-7xl mx-auto w-full relative z-10">

        <!-- Hero Header -->
        <div class="text-center max-w-4xl mx-auto mb-12">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/15 backdrop-blur-md mb-6 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-200">
                    Platform Pemesanan Hotel & Penginapan Resmi di Kota Medan
                </span>
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight text-white mb-6">
                Pesan Kamar Hotel Nyaman di <br class="hidden sm:inline">
                <span class="text-gradient">Kota Medan</span> Dekat Landmark Favorit
            </h1>
            <p class="text-base sm:text-lg text-slate-300 max-w-2xl mx-auto leading-relaxed">
                Pilihan akomodasi terlengkap mulai hotel hemat hingga bintang lima mewah dengan garansi harga terbaik, konfirmasi instan, dan navigasi rute Google Maps langsung.
            </p>
        </div>

        <!-- Live Stats Floating Badges -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto mb-10">
            <div class="glass-card rounded-2xl p-4 text-center transform transition-transform hover:-translate-y-1">
                <div class="text-2xl sm:text-3xl font-black text-indigo-400">187+</div>
                <div class="text-xs text-slate-300 font-semibold uppercase tracking-wider mt-1">Hotel Pilihan</div>
            </div>
            <div class="glass-card rounded-2xl p-4 text-center transform transition-transform hover:-translate-y-1">
                <div class="text-2xl sm:text-3xl font-black text-purple-400">13</div>
                <div class="text-xs text-slate-300 font-semibold uppercase tracking-wider mt-1">Landmark Ikonik</div>
            </div>
            <div class="glass-card rounded-2xl p-4 text-center transform transition-transform hover:-translate-y-1">
                <div class="text-2xl sm:text-3xl font-black text-gradient-gold">4.8★</div>
                <div class="text-xs text-slate-300 font-semibold uppercase tracking-wider mt-1">Rating Kepuasan</div>
            </div>
            <div class="glass-card rounded-2xl p-4 text-center transform transition-transform hover:-translate-y-1">
                <div class="text-2xl sm:text-3xl font-black text-emerald-400">100%</div>
                <div class="text-xs text-slate-300 font-semibold uppercase tracking-wider mt-1">Konfirmasi Instan</div>
            </div>
        </div>

        <!-- ── GLASS SEARCH BOX ── -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 max-w-4xl mx-auto shadow-2xl relative">
            <form action="{{ url('/search') }}" method="GET" id="search-form">

                <!-- Row 1: Keyword & Landmark Dropdown -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <!-- Keyword Autocomplete Input -->
                    <div class="relative">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">
                            <i class="fa-solid fa-magnifying-glass text-indigo-400 mr-1.5"></i> Nama Hotel / Lokasi
                        </label>
                        <div class="relative">
                            <input type="text"
                                   id="search-q"
                                   name="q"
                                   value="{{ request('q') }}"
                                   autocomplete="off"
                                   placeholder="Contoh: Adimulia, Grand Mercure, wifi..."
                                   class="search-input pl-11">
                            <i class="fa-solid fa-hotel absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        </div>
                        <div id="suggest-box"></div>
                    </div>

                    <!-- Landmark Dropdown (All 13 Iconic Landmarks) -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">
                            <i class="fa-solid fa-landmark text-indigo-400 mr-1.5"></i> Dekat Landmark Medan
                        </label>
                        <div class="relative">
                            <select name="landmark" class="search-input pl-11 appearance-none cursor-pointer">
                                <option value="">Semua Landmark Medan</option>
                                <option value="Istana Maimun" {{ request('landmark') == 'Istana Maimun' ? 'selected' : '' }}>🏛️ Istana Maimun (Medan Maimun)</option>
                                <option value="Tjong A Fie Mansion" {{ request('landmark') == 'Tjong A Fie Mansion' ? 'selected' : '' }}>🏮 Tjong A Fie Mansion (Kesawan)</option>
                                <option value="Masjid Raya Al Mashun" {{ request('landmark') == 'Masjid Raya Al Mashun' ? 'selected' : '' }}>🕌 Masjid Raya Al-Mashun (Medan Kota)</option>
                                <option value="Lapangan Merdeka" {{ request('landmark') == 'Lapangan Merdeka' ? 'selected' : '' }}>🌳 Lapangan Merdeka & KM 0</option>
                                <option value="Graha Maria Annai Velangkanni" {{ request('landmark') == 'Graha Maria Annai Velangkanni' ? 'selected' : '' }}>⛪ Graha Maria Annai Velangkanni</option>
                                <option value="Sun Plaza" {{ request('landmark') == 'Sun Plaza' ? 'selected' : '' }}>🛍️ Sun Plaza Medan (Petisah)</option>
                                <option value="Maha Vihara Maitreya" {{ request('landmark') == 'Maha Vihara Maitreya' ? 'selected' : '' }}>🪷 Maha Vihara Maitreya (Cemara Asri)</option>
                                <option value="Museum Rahmat" {{ request('landmark') == 'Museum Rahmat' ? 'selected' : '' }}>🦁 Museum Rahmat International Gallery</option>
                                <option value="Merdeka Walk" {{ request('landmark') == 'Merdeka Walk' ? 'selected' : '' }}>🍜 Merdeka Walk & Kesawan Heritage</option>
                                <option value="Menara Air Tirtanadi" {{ request('landmark') == 'Menara Air Tirtanadi' ? 'selected' : '' }}>🗼 Menara Air Tirtanadi 1908</option>
                                <option value="Centre Point Mall" {{ request('landmark') == 'Centre Point Mall' ? 'selected' : '' }}>🚆 Centre Point Mall & Stasiun KAI</option>
                                <option value="Kebun Binatang Medan" {{ request('landmark') == 'Kebun Binatang Medan' ? 'selected' : '' }}>🐯 Medan Zoo (Simalingkar)</option>
                                <option value="Kuil Shri Mariamman" {{ request('landmark') == 'Kuil Shri Mariamman' ? 'selected' : '' }}>🕉️ Kuil Shri Mariamman (Kampung Madras)</option>
                            </select>
                            <i class="fa-solid fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Star Rating, Max Price, Dates -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">
                            <i class="fa-solid fa-star text-amber-400 mr-1"></i> Bintang
                        </label>
                        <select name="star" class="search-input">
                            <option value="">Semua Bintang</option>
                            <option value="5" {{ request('star') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 5 Bintang</option>
                            <option value="4" {{ request('star') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ 4+ Bintang</option>
                            <option value="3" {{ request('star') == '3' ? 'selected' : '' }}>⭐⭐⭐ 3+ Bintang</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">
                            <i class="fa-solid fa-tag text-emerald-400 mr-1"></i> Harga Maks.
                        </label>
                        <input type="number"
                               name="max_price"
                               value="{{ request('max_price') }}"
                               placeholder="Contoh: 750000"
                               step="50000"
                               class="search-input">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">
                            <i class="fa-solid fa-calendar-check text-indigo-400 mr-1"></i> Check-in
                        </label>
                        <input type="date"
                               name="checkin"
                               value="{{ request('checkin', date('Y-m-d')) }}"
                               class="search-input">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">
                            <i class="fa-solid fa-calendar-xmark text-pink-400 mr-1"></i> Check-out
                        </label>
                        <input type="date"
                               name="checkout"
                               value="{{ request('checkout', date('Y-m-d', strtotime('+1 day'))) }}"
                               class="search-input">
                    </div>
                </div>

                <!-- Row 3: Facilities Toggles -->
                <div class="mb-6">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2.5">
                        <i class="fa-solid fa-sliders text-indigo-400 mr-1"></i> Fasilitas Populer
                    </label>
                    <div class="flex flex-wrap gap-2">
                        @php
                            $facilities = [
                                ['id' => 'wifi', 'name' => 'WiFi', 'icon' => 'fa-wifi'],
                                ['id' => 'kolam-renang', 'name' => 'Kolam Renang', 'icon' => 'fa-person-swimming'],
                                ['id' => 'ac', 'name' => 'AC', 'icon' => 'fa-snowflake'],
                                ['id' => 'sarapan', 'name' => 'Sarapan', 'icon' => 'fa-utensils'],
                                ['id' => 'gym', 'name' => 'Gym', 'icon' => 'fa-dumbbell'],
                                ['id' => 'parkir', 'name' => 'Parkir', 'icon' => 'fa-square-parking'],
                                ['id' => 'spa', 'name' => 'Spa', 'icon' => 'fa-spa'],
                            ];
                            $activeFacs = (array) request('facilities', []);
                        @endphp
                        @foreach($facilities as $f)
                            <label class="fac-chip {{ in_array($f['name'], $activeFacs) ? 'active' : '' }}">
                                <input type="checkbox" name="facilities[]" value="{{ $f['name'] }}" {{ in_array($f['name'], $activeFacs) ? 'checked' : '' }} onchange="this.parentElement.classList.toggle('active', this.checked)">
                                <i class="fa-solid {{ $f['icon'] }}"></i>
                                <span>{{ $f['name'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-gradient w-full py-4 rounded-2xl text-white font-extrabold text-base flex items-center justify-center gap-3 tracking-wide">
                    <i class="fa-solid fa-magnifying-glass text-lg"></i>
                    <span>Cari Hotel di Medan Sekarang</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     DESTINASI LANDMARK MEDAN (13 ICONIC LANDMARKS)
═══════════════════════════════════════════════════════════ --}}
<section id="destinasi" class="py-24 px-4 sm:px-6 lg:px-8 bg-slate-900/60 border-t border-b border-white/5 relative">
    <div class="max-w-7xl mx-auto">

        <!-- Section Title -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-extrabold uppercase tracking-widest text-indigo-400 bg-indigo-500/10 px-3 py-1 rounded-full border border-indigo-500/20">
                Pusat Daya Tarik Kota Medan
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-white mt-3 mb-4 tracking-tight">
                Jelajahi Hotel Berdasarkan <span class="text-gradient">Landmark Ikonik</span>
            </h2>
            <p class="text-slate-400 text-sm sm:text-base leading-relaxed">
                Pilih landmark favorit Anda di Kota Medan dan temukan puluhan akomodasi terbaik di sekitarnya dengan jarak tempuh terdekat.
            </p>
        </div>

        <!-- Landmarks Grid (13 Cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            <!-- 1. Istana Maimun -->
            <a href="{{ url('/search?landmark=Istana+Maimun') }}" class="landmark-card group no-underline">
                <div class="landmark-img-wrap">
                    <img src="{{ asset('images/landmarks/istana-maimun.jpg') }}" alt="Istana Maimun Medan" loading="lazy">
                    <span class="absolute top-3 right-3 text-[11px] font-bold px-2.5 py-1 rounded-full bg-indigo-600/90 text-white backdrop-blur-md">
                        <i class="fa-solid fa-crown mr-1"></i> Ikon Utama
                    </span>
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-extrabold text-white text-lg group-hover:text-indigo-300 transition">Istana Maimun</h3>
                        <p class="text-xs text-slate-400 mt-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-location-dot text-indigo-500"></i> Jl. Brigjend Katamso, Medan Maimun
                        </p>
                        <p class="text-xs text-slate-300 mt-2 line-clamp-2 leading-relaxed">
                            Istana megah Kesultanan Deli peninggalan Sultan Ma'mun Al Rasyid (1888) dengan perpaduan arsitektur Melayu & Timur Tengah.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-xs font-bold text-indigo-400">
                        <span>Lihat Hotel Terdekat</span>
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition"></i>
                    </div>
                </div>
            </a>

            <!-- 2. Tjong A Fie Mansion -->
            <a href="{{ url('/search?landmark=Tjong+A+Fie+Mansion') }}" class="landmark-card group no-underline">
                <div class="landmark-img-wrap">
                    <img src="{{ asset('images/landmarks/tjong-a-fie.jpg') }}" alt="Tjong A Fie Mansion" loading="lazy">
                    <span class="absolute top-3 right-3 text-[11px] font-bold px-2.5 py-1 rounded-full bg-amber-600/90 text-white backdrop-blur-md">
                        <i class="fa-solid fa-landmark mr-1"></i> Cagar Budaya
                    </span>
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-extrabold text-white text-lg group-hover:text-indigo-300 transition">Tjong A Fie Mansion</h3>
                        <p class="text-xs text-slate-400 mt-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-location-dot text-indigo-500"></i> Jl. Ahmad Yani, Kesawan
                        </p>
                        <p class="text-xs text-slate-300 mt-2 line-clamp-2 leading-relaxed">
                            Manor megah bergaya Tionghoa-Eropa abad ke-19 milik tokoh terkemuka Medan di jantung heritage Kesawan.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-xs font-bold text-indigo-400">
                        <span>Lihat Hotel Terdekat</span>
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition"></i>
                    </div>
                </div>
            </a>

            <!-- 3. Masjid Raya Al-Mashun -->
            <a href="{{ url('/search?landmark=Masjid+Raya+Al+Mashun') }}" class="landmark-card group no-underline">
                <div class="landmark-img-wrap">
                    <img src="{{ asset('images/landmarks/masjid-raya.jpg') }}" alt="Masjid Raya Al Mashun" loading="lazy">
                    <span class="absolute top-3 right-3 text-[11px] font-bold px-2.5 py-1 rounded-full bg-emerald-600/90 text-white backdrop-blur-md">
                        <i class="fa-solid fa-mosque mr-1"></i> Wisata Religi
                    </span>
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-extrabold text-white text-lg group-hover:text-indigo-300 transition">Masjid Raya Al-Mashun</h3>
                        <p class="text-xs text-slate-400 mt-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-location-dot text-indigo-500"></i> Jl. Mahkamah, Medan Kota
                        </p>
                        <p class="text-xs text-slate-300 mt-2 line-clamp-2 leading-relaxed">
                            Mahakarya arsitektur Islam bernuansa Maroko dan Timur Tengah yang berdiri megah sejak tahun 1909.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-xs font-bold text-indigo-400">
                        <span>Lihat Hotel Terdekat</span>
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition"></i>
                    </div>
                </div>
            </a>

            <!-- 4. Lapangan Merdeka & KM 0 -->
            <a href="{{ url('/search?landmark=Lapangan+Merdeka') }}" class="landmark-card group no-underline">
                <div class="landmark-img-wrap">
                    <img src="{{ asset('images/landmarks/lapangan-merdeka.jpg') }}" alt="Lapangan Merdeka Medan" loading="lazy">
                    <span class="absolute top-3 right-3 text-[11px] font-bold px-2.5 py-1 rounded-full bg-blue-600/90 text-white backdrop-blur-md">
                        <i class="fa-solid fa-map-pin mr-1"></i> KM 0 Medan
                    </span>
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-extrabold text-white text-lg group-hover:text-indigo-300 transition">Lapangan Merdeka</h3>
                        <p class="text-xs text-slate-400 mt-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-location-dot text-indigo-500"></i> Jl. Balai Kota, Kesawan
                        </p>
                        <p class="text-xs text-slate-300 mt-2 line-clamp-2 leading-relaxed">
                            Alun-alun bersejarah titik nol Kota Medan, dikelilingi cagar budaya kolonial dan stasiun utama kota.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-xs font-bold text-indigo-400">
                        <span>Lihat Hotel Terdekat</span>
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition"></i>
                    </div>
                </div>
            </a>

            <!-- 5. Graha Maria Annai Velangkanni -->
            <a href="{{ url('/search?landmark=Graha+Maria+Annai+Velangkanni') }}" class="landmark-card group no-underline">
                <div class="landmark-img-wrap">
                    <img src="{{ asset('images/landmarks/graha-maria.jpg') }}" alt="Graha Maria Annai Velangkanni" loading="lazy">
                    <span class="absolute top-3 right-3 text-[11px] font-bold px-2.5 py-1 rounded-full bg-purple-600/90 text-white backdrop-blur-md">
                        <i class="fa-solid fa-church mr-1"></i> Indo-Mogul
                    </span>
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-extrabold text-white text-lg group-hover:text-indigo-300 transition">Graha Maria Velangkanni</h3>
                        <p class="text-xs text-slate-400 mt-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-location-dot text-indigo-500"></i> Jl. Sakura III, Tanjung Selamat
                        </p>
                        <p class="text-xs text-slate-300 mt-2 line-clamp-2 leading-relaxed">
                            Gereja Katolik berarsitektur kuil Indo-Mogul yang spektakuler, destinasi religi internasional unik di Medan.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-xs font-bold text-indigo-400">
                        <span>Lihat Hotel Terdekat</span>
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition"></i>
                    </div>
                </div>
            </a>

            <!-- 6. Sun Plaza Medan -->
            <a href="{{ url('/search?landmark=Sun+Plaza') }}" class="landmark-card group no-underline">
                <div class="landmark-img-wrap">
                    <img src="{{ asset('images/landmarks/sun-plaza.jpg') }}" alt="Sun Plaza Medan" loading="lazy">
                    <span class="absolute top-3 right-3 text-[11px] font-bold px-2.5 py-1 rounded-full bg-pink-600/90 text-white backdrop-blur-md">
                        <i class="fa-solid fa-bag-shopping mr-1"></i> Mall & Lifestyle
                    </span>
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-extrabold text-white text-lg group-hover:text-indigo-300 transition">Sun Plaza Medan</h3>
                        <p class="text-xs text-slate-400 mt-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-location-dot text-indigo-500"></i> Jl. KH. Zainul Arifin, Medan Petisah
                        </p>
                        <p class="text-xs text-slate-300 mt-2 line-clamp-2 leading-relaxed">
                            Pusat belanja mewah paling bergengsi di Medan dengan tenant internasional, bioskop premier, dan kuliner modern.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-xs font-bold text-indigo-400">
                        <span>Lihat Hotel Terdekat</span>
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition"></i>
                    </div>
                </div>
            </a>

            <!-- 7. Maha Vihara Maitreya -->
            <a href="{{ url('/search?landmark=Maha+Vihara+Maitreya') }}" class="landmark-card group no-underline">
                <div class="landmark-img-wrap">
                    <img src="{{ asset('images/landmarks/maha-vihara-maitreya.jpg') }}" alt="Maha Vihara Maitreya" loading="lazy">
                    <span class="absolute top-3 right-3 text-[11px] font-bold px-2.5 py-1 rounded-full bg-fuchsia-600/90 text-white backdrop-blur-md">
                        <i class="fa-solid fa-spa mr-1"></i> Vihara Megah
                    </span>
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-extrabold text-white text-lg group-hover:text-indigo-300 transition">Maha Vihara Maitreya</h3>
                        <p class="text-xs text-slate-400 mt-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-location-dot text-indigo-500"></i> Kompleks Cemara Asri, Medan
                        </p>
                        <p class="text-xs text-slate-300 mt-2 line-clamp-2 leading-relaxed">
                            Salah satu vihara Buddha terbesar di Indonesia dengan danau bangau alami, pagoda megah, dan lingkungan asri.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-xs font-bold text-indigo-400">
                        <span>Lihat Hotel Terdekat</span>
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition"></i>
                    </div>
                </div>
            </a>

            <!-- 8. Museum Rahmat International Gallery -->
            <a href="{{ url('/search?landmark=Museum+Rahmat') }}" class="landmark-card group no-underline">
                <div class="landmark-img-wrap">
                    <img src="{{ asset('images/landmarks/museum-rahmat.jpg') }}" alt="Museum Rahmat Medan" loading="lazy">
                    <span class="absolute top-3 right-3 text-[11px] font-bold px-2.5 py-1 rounded-full bg-amber-700/90 text-white backdrop-blur-md">
                        <i class="fa-solid fa-paw mr-1"></i> Galeri Satwa
                    </span>
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-extrabold text-white text-lg group-hover:text-indigo-300 transition">Museum Rahmat</h3>
                        <p class="text-xs text-slate-400 mt-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-location-dot text-indigo-500"></i> Jl. S. Parman No. 217, Petisah
                        </p>
                        <p class="text-xs text-slate-300 mt-2 line-clamp-2 leading-relaxed">
                            Museum satwa liar internasional pertama di Asia Tenggara dengan koleksi ribuan spesimen fauna dari seluruh penjuru dunia.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-xs font-bold text-indigo-400">
                        <span>Lihat Hotel Terdekat</span>
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition"></i>
                    </div>
                </div>
            </a>

            <!-- 9. Merdeka Walk & Kesawan -->
            <a href="{{ url('/search?landmark=Merdeka+Walk') }}" class="landmark-card group no-underline">
                <div class="landmark-img-wrap">
                    <img src="{{ asset('images/landmarks/merdeka-walk.jpg') }}" alt="Merdeka Walk Medan" loading="lazy">
                    <span class="absolute top-3 right-3 text-[11px] font-bold px-2.5 py-1 rounded-full bg-cyan-600/90 text-white backdrop-blur-md">
                        <i class="fa-solid fa-utensils mr-1"></i> Kuliner Heritage
                    </span>
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-extrabold text-white text-lg group-hover:text-indigo-300 transition">Merdeka Walk Kesawan</h3>
                        <p class="text-xs text-slate-400 mt-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-location-dot text-indigo-500"></i> Jl. Pos No. 1, Medan Barat
                        </p>
                        <p class="text-xs text-slate-300 mt-2 line-clamp-2 leading-relaxed">
                            Kawasan kuliner legendaris bernuansa heritage di pusat kota Medan, tempat berkumpul dan mencicipi aneka sajian khas.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-xs font-bold text-indigo-400">
                        <span>Lihat Hotel Terdekat</span>
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition"></i>
                    </div>
                </div>
            </a>

            <!-- 10. Menara Air Tirtanadi -->
            <a href="{{ url('/search?landmark=Menara+Air+Tirtanadi') }}" class="landmark-card group no-underline">
                <div class="landmark-img-wrap">
                    <img src="{{ asset('images/landmarks/menara-air.jpg') }}" alt="Menara Air Tirtanadi" loading="lazy">
                    <span class="absolute top-3 right-3 text-[11px] font-bold px-2.5 py-1 rounded-full bg-teal-600/90 text-white backdrop-blur-md">
                        <i class="fa-solid fa-tower-observation mr-1"></i> Kolonial 1908
                    </span>
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-extrabold text-white text-lg group-hover:text-indigo-300 transition">Menara Air Tirtanadi</h3>
                        <p class="text-xs text-slate-400 mt-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-location-dot text-indigo-500"></i> Jl. SM Raja No. 1, Medan Kota
                        </p>
                        <p class="text-xs text-slate-300 mt-2 line-clamp-2 leading-relaxed">
                            Menara air ikonik peninggalan era kolonial Belanda tahun 1908 yang menjadi simbol identitas visual Kota Medan.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-xs font-bold text-indigo-400">
                        <span>Lihat Hotel Terdekat</span>
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition"></i>
                    </div>
                </div>
            </a>

            <!-- 11. Centre Point Mall -->
            <a href="{{ url('/search?landmark=Centre+Point+Mall') }}" class="landmark-card group no-underline">
                <div class="landmark-img-wrap">
                    <img src="{{ asset('images/landmarks/centre-point.jpg') }}" alt="Centre Point Mall Medan" loading="lazy">
                    <span class="absolute top-3 right-3 text-[11px] font-bold px-2.5 py-1 rounded-full bg-violet-600/90 text-white backdrop-blur-md">
                        <i class="fa-solid fa-train mr-1"></i> Railink KAI
                    </span>
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-extrabold text-white text-lg group-hover:text-indigo-300 transition">Centre Point Mall</h3>
                        <p class="text-xs text-slate-400 mt-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-location-dot text-indigo-500"></i> Jl. Jawa No. 8, Gang Buntu
                        </p>
                        <p class="text-xs text-slate-300 mt-2 line-clamp-2 leading-relaxed">
                            Pusat perbelanjaan terpadu yang terhubung dengan akses Kereta Api Bandara Kualanamu Railink dan kawasan bisnis.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-xs font-bold text-indigo-400">
                        <span>Lihat Hotel Terdekat</span>
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition"></i>
                    </div>
                </div>
            </a>

            <!-- 12. Medan Zoo -->
            <a href="{{ url('/search?landmark=Kebun+Binatang+Medan') }}" class="landmark-card group no-underline">
                <div class="landmark-img-wrap">
                    <img src="{{ asset('images/landmarks/kebun-binatang.jpg') }}" alt="Medan Zoo" loading="lazy">
                    <span class="absolute top-3 right-3 text-[11px] font-bold px-2.5 py-1 rounded-full bg-emerald-700/90 text-white backdrop-blur-md">
                        <i class="fa-solid fa-tree mr-1"></i> Wisata Alam
                    </span>
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-extrabold text-white text-lg group-hover:text-indigo-300 transition">Medan Zoo</h3>
                        <p class="text-xs text-slate-400 mt-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-location-dot text-indigo-500"></i> Simalingkar, Medan Tuntungan
                        </p>
                        <p class="text-xs text-slate-300 mt-2 line-clamp-2 leading-relaxed">
                            Kebun binatang seluas 30 hektar dengan koleksi satwa Harimau Sumatera dan kawasan rekreasi keluarga yang hijau.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-xs font-bold text-indigo-400">
                        <span>Lihat Hotel Terdekat</span>
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition"></i>
                    </div>
                </div>
            </a>

        </div>

        <!-- View All Destinations CTA -->
        <div class="mt-12 text-center">
            <a href="{{ url('/search') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-2xl bg-white/10 hover:bg-white/15 text-white font-bold text-sm border border-white/20 transition-all hover:scale-105">
                <i class="fa-solid fa-compass text-indigo-400"></i>
                <span>Jelajahi Seluruh 187 Hotel di Kota Medan</span>
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </a>
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     HOTEL PILIHAN PREMIUM MEDAN (CURATED SHOWCASE)
═══════════════════════════════════════════════════════════ --}}
<section id="hotel-pilihan" class="py-24 px-4 sm:px-6 lg:px-8 bg-slate-900 relative">
    <div class="max-w-7xl mx-auto">

        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <span class="text-xs font-extrabold uppercase tracking-widest text-indigo-400 bg-indigo-500/10 px-3 py-1 rounded-full border border-indigo-500/20">
                    Akomodasi Unggulan
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-white mt-3 tracking-tight">
                    Rekomendasi Hotel <span class="text-gradient">Terbaik di Medan</span>
                </h2>
                <p class="text-slate-400 text-sm sm:text-base mt-2">
                    Koleksi hotel berbintang dengan fasilitas lengkap dan rating tertinggi dari wisatawan.
                </p>
            </div>
            <a href="{{ url('/search') }}" class="mt-4 md:mt-0 inline-flex items-center gap-2 text-indigo-400 hover:text-indigo-300 font-bold text-sm">
                Lihat Semua Hotel <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        @php
            $featuredHotels = [
                [
                    'id' => 'hotel-005',
                    'nama' => 'Adimulia Hotel Medan',
                    'alamat' => 'Jl. Pangeran Diponegoro No. 8, Medan Petisah',
                    'star' => 5,
                    'rating' => 4.8,
                    'reviews' => 4250,
                    'harga' => 1250000,
                    'foto' => '/images/hotels/adimulia-hotel-medan.jpg',
                    'landmark' => 'Sun Plaza (350 m)',
                    'facilities' => ['Kolam Renang', 'Gym', 'WiFi', 'Spa', 'Restoran']
                ],
                [
                    'id' => 'hotel-001',
                    'nama' => "d'primahotel Kualanamu Medan",
                    'alamat' => 'Jalan Sultan Serdang No. 88, Medan',
                    'star' => 4,
                    'rating' => 4.5,
                    'reviews' => 2880,
                    'harga' => 514240,
                    'foto' => '/images/hotels/dprimahotelkualanamumedan/1.jpg',
                    'landmark' => 'Bandara & Railink',
                    'facilities' => ['Kolam Renang', 'Parkir', 'WiFi', 'AC']
                ],
                [
                    'id' => 'hotel-002',
                    'nama' => 'Grand City Hall Medan',
                    'alamat' => 'Jl. Balai Kota No. 1, Medan Barat',
                    'star' => 5,
                    'rating' => 4.7,
                    'reviews' => 3120,
                    'harga' => 1100000,
                    'foto' => '/images/hotels/alam-hotel-by-cordela.jpg',
                    'landmark' => 'Lapangan Merdeka (100 m)',
                    'facilities' => ['Kolam Renang', 'Spa', 'Gym', 'WiFi', 'Sarapan']
                ],
                [
                    'id' => 'hotel-003',
                    'nama' => 'Santika Premiere Dyandra Hotel',
                    'alamat' => 'Jl. Kapten Maulana Lubis No. 7, Medan Petisah',
                    'star' => 4,
                    'rating' => 4.6,
                    'reviews' => 2640,
                    'harga' => 785000,
                    'foto' => '/images/hotels/antares-hotel.jpg',
                    'landmark' => 'Tjong A Fie Mansion (800 m)',
                    'facilities' => ['Kolam Renang', 'WiFi', 'Parkir', 'AC', 'Sarapan']
                ],
            ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredHotels as $hotel)
                <div class="glass-card rounded-2xl overflow-hidden flex flex-col justify-between group transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ asset($hotel['foto']) }}" alt="{{ $hotel['nama'] }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" onerror="this.src='/images/placeholder.svg'">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute top-3 left-3 flex items-center gap-1 bg-amber-500 text-white text-[11px] font-black px-2 py-0.5 rounded-lg shadow">
                            <i class="fa-solid fa-star text-[10px]"></i> {{ $hotel['star'] }} Bintang
                        </div>
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-slate-800 text-[11px] font-bold px-2 py-0.5 rounded-lg">
                            ⭐ {{ $hotel['rating'] }} <span class="text-slate-400">({{ $hotel['reviews'] }})</span>
                        </div>
                        <div class="absolute bottom-2 left-3 text-white text-xs font-semibold flex items-center gap-1">
                            <i class="fa-solid fa-route text-indigo-400"></i> {{ $hotel['landmark'] }}
                        </div>
                    </div>

                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-extrabold text-white text-base leading-snug group-hover:text-indigo-300 transition">
                                <a href="{{ route('hotel.detail', ['id' => $hotel['id']]) }}" class="no-underline text-inherit">
                                    {{ $hotel['nama'] }}
                                </a>
                            </h3>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-1">
                                <i class="fa-solid fa-location-dot text-indigo-500 mr-1"></i> {{ $hotel['alamat'] }}
                            </p>

                            <!-- Facility Chips -->
                            <div class="flex flex-wrap gap-1 mt-3">
                                @foreach(array_slice($hotel['facilities'], 0, 3) as $f)
                                    <span class="text-[10px] font-semibold bg-white/10 text-slate-300 px-2 py-0.5 rounded-md">
                                        {{ $f }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-5 pt-4 border-t border-white/10 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-slate-400 block font-medium">Mulai dari</span>
                                <span class="text-base font-black text-indigo-600">Rp {{ number_format($hotel['harga'], 0, ',', '.') }}</span>
                                <span class="text-[10px] text-slate-400">/malam</span>
                            </div>
                            <a href="{{ route('hotel.detail', ['id' => $hotel['id']]) }}" class="px-3.5 py-2 rounded-xl bg-indigo-600 text-white font-bold text-xs hover:bg-indigo-700 transition flex items-center gap-1 shadow-sm">
                                Detail <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     KEUNGGULAN MEMESAN DI ROOMIFY
═══════════════════════════════════════════════════════════ --}}
<section id="keunggulan" class="py-24 px-4 sm:px-6 lg:px-8 bg-slate-950 border-t border-white/5 relative">
    <div class="max-w-7xl mx-auto">

        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-extrabold uppercase tracking-widest text-indigo-400 bg-indigo-500/10 px-3 py-1 rounded-full border border-indigo-500/20">
                Pengalaman Menginap Nyaman & Pasti
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-white mt-3 mb-4 tracking-tight">
                Kenapa Memesan di <span class="text-gradient">Roomify</span>?
            </h2>
            <p class="text-slate-400 text-sm sm:text-base leading-relaxed">
                Nikmati kemudahan reservasi hotel dengan harga transparan, navigasi rute Google Maps langsung dari ponsel Anda, serta jaminan check-in tepat waktu.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- Card 1: Garansi Harga Hemat & Transparan -->
            <div class="glass-card rounded-2xl p-6 sm:p-8 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-xl mb-5 border border-indigo-500/30">
                        <i class="fa-solid fa-tags"></i>
                    </div>
                    <h3 class="text-xl font-black text-white mb-2">Garansi Harga Transparan</h3>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed mb-4">
                        Harga final tanpa biaya siluman. Pilihan metode pembayaran instan terlengkap via QRIS (BCA, Mandiri, BRI, GoPay, OVO), Transfer Virtual Account, dan Kartu Kredit.
                    </p>
                    <div class="bg-black/40 rounded-xl p-3 border border-white/10 text-xs text-emerald-400 font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>E-Voucher & Bukti Pemesanan Terbit Instan</span>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-white/10 flex items-center gap-2 text-xs text-indigo-400 font-semibold">
                    <i class="fa-solid fa-shield-check"></i>
                    <span>Pembayaran 100% Aman & Terverifikasi</span>
                </div>
            </div>

            <!-- Card 2: Navigasi Google Maps Presisi -->
            <div class="glass-card rounded-2xl p-6 sm:p-8 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-pink-500/20 text-pink-400 flex items-center justify-center text-xl mb-5 border border-pink-500/30">
                        <i class="fa-solid fa-route"></i>
                    </div>
                    <h3 class="text-xl font-black text-white mb-2">Navigasi Google Maps Presisi</h3>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed mb-4">
                        Tak perlu tersesat mencari lobi hotel. Roomify dilengkapi panduan navigasi real-time langsung dari lokasi GPS Anda ke hotel yang Anda tuju di Kota Medan.
                    </p>
                    <div class="bg-black/40 rounded-xl p-3 border border-white/10 text-xs text-pink-300 font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-diamond-turn-right"></i>
                        <span>Turn-by-turn Navigation & Estimasi Waktu</span>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-white/10 flex items-center gap-2 text-xs text-pink-400 font-semibold">
                    <i class="fa-solid fa-location-dot"></i>
                    <span>Terintegrasi 13 Landmark Populer Medan</span>
                </div>
            </div>

            <!-- Card 3: Waktu Pasti & AI Concierge -->
            <div class="glass-card rounded-2xl p-6 sm:p-8 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-500/20 text-purple-400 flex items-center justify-center text-xl mb-5 border border-purple-500/30">
                        <i class="fa-solid fa-bell-concierge"></i>
                    </div>
                    <h3 class="text-xl font-black text-white mb-2">Waktu Pasti & AI Concierge</h3>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed mb-4">
                        Waktu Check-In resmi pukul 14:00 WIB dan Check-Out pukul 12:00 WIB. Didampingi asisten cerdas Roomify AI untuk memantau status e-tiket pesanan Anda 24 jam nonstop.
                    </p>
                    <div class="bg-black/40 rounded-xl p-3 border border-white/10 text-xs text-purple-300 font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-id-card"></i>
                        <span>Verifikasi NIK e-KTP 16 Digit Terenkripsi</span>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-white/10 flex items-center gap-2 text-xs text-purple-400 font-semibold">
                    <i class="fa-solid fa-robot"></i>
                    <span>Layanan AI Concierge Siap 24 Jam</span>
                </div>
            </div>

        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════════════════════ --}}
<footer class="bg-slate-950 text-slate-400 border-t border-white/10 pt-16 pb-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">

            <!-- Col 1: Brand Info -->
            <div class="space-y-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9">
                        <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full drop-shadow">
                            <defs>
                                <linearGradient id="ft-bg-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#4F46E5"/>
                                    <stop offset="50%" stop-color="#7C3AED"/>
                                    <stop offset="100%" stop-color="#EC4899"/>
                                </linearGradient>
                            </defs>
                            <rect width="48" height="48" rx="14" fill="url(#ft-bg-grad)"/>
                            <path d="M12 36V22C12 15.3726 17.3726 10 24 10C30.6274 10 36 15.3726 36 22V36" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round"/>
                            <path d="M18 36V18H25.5C28 18 29.5 19.5 29.5 22C29.5 24.5 28 26 25.5 26H18M24 26L30 36" stroke="#FFFFFF" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <span class="text-xl font-black text-white">Roomify</span>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Platform pemesanan hotel terbaik di Kota Medan. Temukan 187+ pilihan akomodasi nyaman dekat landmark bersejarah, pusat bisnis, dan kuliner khas Medan dengan garansi harga hemat dan navigasi Google Maps presisi.
                </p>
                <div class="flex items-center gap-3 text-slate-400 text-sm">
                    <a href="#" class="hover:text-white transition"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="hover:text-white transition"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="hover:text-white transition"><i class="fa-brands fa-linkedin"></i></a>
                </div>
            </div>

            <!-- Col 2: Landmark Populer -->
            <div>
                <h4 class="text-white text-xs font-black uppercase tracking-wider mb-4">Landmark Populer</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ url('/search?landmark=Istana+Maimun') }}" class="hover:text-white transition">Istana Maimun</a></li>
                    <li><a href="{{ url('/search?landmark=Tjong+A+Fie+Mansion') }}" class="hover:text-white transition">Tjong A Fie Mansion</a></li>
                    <li><a href="{{ url('/search?landmark=Masjid+Raya+Al+Mashun') }}" class="hover:text-white transition">Masjid Raya Al-Mashun</a></li>
                    <li><a href="{{ url('/search?landmark=Lapangan+Merdeka') }}" class="hover:text-white transition">Lapangan Merdeka (KM 0)</a></li>
                    <li><a href="{{ url('/search?landmark=Sun+Plaza') }}" class="hover:text-white transition">Sun Plaza Medan</a></li>
                    <li><a href="{{ url('/search?landmark=Graha+Maria+Annai+Velangkanni') }}" class="hover:text-white transition">Graha Maria Velangkanni</a></li>
                </ul>
            </div>

            <!-- Col 3: Navigasi Cepat -->
            <div>
                <h4 class="text-white text-xs font-black uppercase tracking-wider mb-4">Navigasi Platform</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ url('/search') }}" class="hover:text-white transition">Cari Semua Hotel (187)</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-white transition">Masuk Akun</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-white transition">Daftar Akun Baru</a></li>
                    <li><a href="#destinasi" class="hover:text-white transition">13 Landmark Medan</a></li>
                    <li><a href="#keunggulan" class="hover:text-white transition">Keunggulan Roomify</a></li>
                </ul>
            </div>

            <!-- Col 4: Layanan & Jam Operasional -->
            <div>
                <h4 class="text-white text-xs font-black uppercase tracking-wider mb-4">Ketentuan & Layanan</h4>
                <ul class="space-y-2 text-xs">
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Check-in: 14:00 WIB</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-pink-400"></span> Check-out: 12:00 WIB</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span> Wajib e-KTP 16 Digit</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-purple-400"></span> Navigasi Google Maps Real-time</li>
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span> AI Concierge Siap 24/7</li>
                </ul>
            </div>

        </div>

        <div class="pt-8 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 gap-4">
            <p>© {{ date('Y') }} Roomify Medan. Hak cipta dilindungi undang-undang.</p>
            <p class="flex items-center gap-1">
                <span>Solusi Pemesanan Hotel & Navigasi Wisata Terpercaya di Kota Medan</span>
            </p>
        </div>
    </div>
</footer>

<!-- Floating AI Chatbot Widget -->
<x-ai-chat-widget />

<!-- ══════════════════════════════════════════════════════
     SCRIPTS: AUTOCOMPLETE & SCROLL
════════════════════════════════════════════════════════ -->
<script>
    // Autocomplete /suggest
    const searchInput = document.getElementById('search-q');
    const suggestBox = document.getElementById('suggest-box');

    let debounceTimer;
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const q = this.value.trim();
            if (q.length < 3) {
                suggestBox.style.display = 'none';
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`{{ url('/suggest') }}?q=${encodeURIComponent(q)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            suggestBox.innerHTML = data.map(item => `
                                <div class="suggest-item" onclick="selectSuggest('${item.replace(/'/g, "\\'")}')">
                                    <i class="fa-solid fa-hotel text-indigo-500 text-xs"></i>
                                    <span>${item}</span>
                                </div>
                            `).join('');
                            suggestBox.style.display = 'block';
                        } else {
                            suggestBox.style.display = 'none';
                        }
                    })
                    .catch(() => {
                        suggestBox.style.display = 'none';
                    });
            }, 250);
        });

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !suggestBox.contains(e.target)) {
                suggestBox.style.display = 'none';
            }
        });
    }

    function selectSuggest(name) {
        searchInput.value = name;
        suggestBox.style.display = 'none';
        document.getElementById('search-form').submit();
    }
</script>

</body>
</html>
