<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roomify – Pesan Hotel Nyaman & Murah di Kota Medan</title>
    <meta name="description" content="Temukan 312 hotel terbaik di Medan dekat landmark populer seperti Istana Maimun, Sun Plaza, dan Stasiun Kereta Api. Garansi harga hemat, check-in instan, dan navigasi Google Maps.">

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
        function getAutoTimeTheme() {
            const hour = new Date().getHours();
            return (hour >= 6 && hour < 18) ? 'light' : 'dark';
        }
        function initTheme() {
            // Clear legacy lock
            if (localStorage.getItem('theme_user_override') === 'true' && !localStorage.getItem('theme_mode')) {
                localStorage.removeItem('theme_user_override');
                localStorage.setItem('theme_mode', 'auto');
            }
            const mode = localStorage.getItem('theme_mode') || 'auto';
            let theme = mode === 'auto' ? getAutoTimeTheme() : mode;
            applyTheme(theme);
        }
        function applyTheme(theme) {
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
        function toggleTheme() {
            if (typeof window.cycleThemeMode === 'function') {
                window.cycleThemeMode();
            } else {
                const isDark = document.documentElement.classList.contains('dark');
                const nextMode = isDark ? 'light' : 'dark';
                localStorage.setItem('theme_mode', nextMode);
                applyTheme(nextMode);
            }
        }
        initTheme();
    </script>

    <style>
        * { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; }
        body {
            scroll-behavior: smooth;
            background-color: #f8fafc;
            color: #0f172a;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        html.dark body {
            background-color: #0b0f19;
            color: #f1f5f9;
        }

        /* ─── Hero Ambient Mesh with Authentic Landmark Backdrop ─── */
        .hero-mesh {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.94) 0%, rgba(241, 245, 249, 0.96) 60%, #f8fafc 100%),
                        url('/images/landmarks/masjid-raya.jpg') center/cover no-repeat;
            background-attachment: fixed;
        }
        html.dark .hero-mesh {
            background: linear-gradient(180deg, rgba(11, 15, 25, 0.82) 0%, rgba(11, 15, 25, 0.92) 60%, #0b0f19 100%),
                        url('/images/landmarks/masjid-raya.jpg') center/cover no-repeat;
            background-attachment: fixed;
        }

        /* ─── Glassmorphism ─── */
        .glass-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            color: #0f172a;
        }
        html.dark .glass-nav {
            background: rgba(11, 15, 25, 0.95);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: #f1f5f9;
        }

        .glass-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
            color: #0f172a;
        }
        html.dark .glass-card {
            background: #111827;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.05);
            color: #f1f5f9;
        }

        .glass-card-light {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.07);
        }
        html.dark .glass-card-light {
            background: #111827;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* ─── Gradients & Typography ─── */
        .text-gradient {
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 50%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .text-gradient-gold {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
            box-shadow: 0 4px 14px 0 rgba(37, 99, 235, 0.25);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px 0 rgba(37, 99, 235, 0.35);
        }

        /* ─── Landmark Card ─── */
        .landmark-card {
            border-radius: 22px;
            overflow: hidden;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }
        html.dark .landmark-card {
            background: #111827;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: none;
        }
        .landmark-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 22px 45px -10px rgba(99, 102, 241, 0.25);
            border-color: #818cf8;
        }
        html.dark .landmark-card:hover {
            border-color: rgba(165, 180, 252, 0.4);
            background: #1e293b;
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
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
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
        html.dark .search-input {
            background: #1e293b;
            border-color: #334155;
            color: #f1f5f9;
        }
        html.dark .search-input:focus {
            background: #0f172a;
            border-color: #818cf8;
            box-shadow: 0 0 0 4px rgba(129, 140, 248, 0.2);
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
            border: 1.5px solid #cbd5e1;
            background: #f1f5f9;
            color: #334155;
            transition: all 0.2s ease;
            user-select: none;
        }
        html.dark .fac-chip {
            border-color: rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.06);
            color: #cbd5e1;
        }
        .fac-chip input { display: none; }
        .fac-chip.active {
            background: #2563eb !important;
            border-color: #2563eb !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
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
            border: 1px solid #cbd5e1;
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.18);
            z-index: 60;
            overflow: hidden;
            display: none;
        }
        html.dark #suggest-box {
            background: #1e293b;
            border-color: #334155;
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.6);
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
        html.dark .suggest-item {
            color: #f1f5f9;
        }
        .suggest-item:hover {
            background: #f1f5f9;
            color: #4f46e5;
        }
        html.dark .suggest-item:hover {
            background: #334155;
            color: #818cf8;
        }
    </style>
</head>
<body class="text-slate-800 dark:text-slate-100 antialiased overflow-x-hidden">

{{-- ═══════════════════════════════════════════════════════════
     TOP LIVE SYSTEM CLOCK & STICKY GLASS NAVBAR
═══════════════════════════════════════════════════════════ --}}
<div class="fixed top-0 left-0 right-0 z-50">
    <x-live-clock-bar />
    <x-navbar active="home" />
</div>

{{-- ═══════════════════════════════════════════════════════════
     HERO SECTION WITH GLOWING MESH & COMPREHENSIVE SEARCH
═══════════════════════════════════════════════════════════ --}}
<section class="hero-mesh pt-32 pb-20 px-4 sm:px-6 lg:px-8 relative overflow-hidden min-h-[85vh] flex items-center">
    <div class="max-w-7xl mx-auto w-full relative z-10">

        <!-- Hero Header -->
        <div class="text-center max-w-4xl mx-auto mb-12">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-50 dark:bg-white/10 border border-indigo-200 dark:border-white/15 backdrop-blur-md mb-6 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-700 dark:text-indigo-200">
                    Platform Pemesanan Hotel & Penginapan Resmi di Kota Medan
                </span>
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight text-slate-900 dark:text-white mb-6">
                Pesan Kamar Hotel Nyaman di <br class="hidden sm:inline">
                <span class="text-gradient">Kota Medan</span> Dekat Landmark Favorit
            </h1>
            <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 max-w-2xl mx-auto leading-relaxed">
                Pilihan akomodasi terlengkap mulai hotel hemat hingga bintang lima mewah dengan garansi harga terbaik, konfirmasi instan, dan navigasi rute Google Maps langsung.
            </p>
        </div>

        <!-- Live Stats Floating Badges -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto mb-10">
            <div class="glass-card rounded-2xl p-4 text-center transform transition-transform hover:-translate-y-1">
                <div class="text-2xl sm:text-3xl font-black text-indigo-600 dark:text-indigo-400">312</div>
                <div class="text-xs text-slate-600 dark:text-slate-300 font-semibold uppercase tracking-wider mt-1">Hotel Terdaftar</div>
            </div>
            <div class="glass-card rounded-2xl p-4 text-center transform transition-transform hover:-translate-y-1">
                <div class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-slate-200">24</div>
                <div class="text-xs text-slate-600 dark:text-slate-300 font-semibold uppercase tracking-wider mt-1">Landmark Ikonik</div>
            </div>
            <div class="glass-card rounded-2xl p-4 text-center transform transition-transform hover:-translate-y-1">
                <div class="text-2xl sm:text-3xl font-black text-gradient-gold">4.8★</div>
                <div class="text-xs text-slate-600 dark:text-slate-300 font-semibold uppercase tracking-wider mt-1">Rating Kepuasan</div>
            </div>
            <div class="glass-card rounded-2xl p-4 text-center transform transition-transform hover:-translate-y-1">
                <div class="text-2xl sm:text-3xl font-black text-emerald-600 dark:text-emerald-400">100%</div>
                <div class="text-xs text-slate-600 dark:text-slate-300 font-semibold uppercase tracking-wider mt-1">Konfirmasi Instan</div>
            </div>
        </div>

        <!-- ── GLASS SEARCH BOX ── -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 max-w-4xl mx-auto shadow-2xl relative">
            <form action="{{ url('/search') }}" method="GET" id="search-form">

                <!-- Row 1: Keyword & Landmark Dropdown -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <!-- Keyword Autocomplete Input -->
                    <div class="relative">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                            <i class="fa-solid fa-magnifying-glass text-indigo-500 dark:text-indigo-400 mr-1.5"></i> Nama Hotel / Lokasi
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

                    <!-- Landmark Dropdown (All 24 Iconic Landmarks) -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                            <i class="fa-solid fa-landmark text-indigo-500 dark:text-indigo-400 mr-1.5"></i> Dekat Landmark Medan
                        </label>
                        <div class="relative">
                            <select name="landmark" class="search-input pl-11 appearance-none cursor-pointer">
                                <option value="">Semua Landmark Medan (24 Lokasi)</option>
                                @foreach($landmarks ?? [] as $lm)
                                    <option value="{{ $lm['nama'] }}" {{ request('landmark') == $lm['nama'] ? 'selected' : '' }}>
                                        📍 {{ $lm['nama'] }} ({{ $lm['kat'] ?? 'Landmark' }})
                                    </option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Star Rating, Max Price, Dates -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                            <i class="fa-solid fa-star text-amber-500 mr-1"></i> Bintang
                        </label>
                        <select name="star" class="search-input">
                            <option value="">Semua Bintang</option>
                            <option value="5" {{ request('star') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 5 Bintang</option>
                            <option value="4" {{ request('star') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ 4+ Bintang</option>
                            <option value="3" {{ request('star') == '3' ? 'selected' : '' }}>⭐⭐⭐ 3+ Bintang</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                            <i class="fa-solid fa-tag text-emerald-500 mr-1"></i> Harga Maks.
                        </label>
                        <input type="number"
                               name="max_price"
                               value="{{ request('max_price') }}"
                               placeholder="Contoh: 750000"
                               step="50000"
                               class="search-input">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                            <i class="fa-solid fa-calendar-check text-indigo-500 dark:text-indigo-400 mr-1"></i> Check-in
                        </label>
                        <input type="date"
                               name="checkin"
                               value="{{ request('checkin', date('Y-m-d')) }}"
                               class="search-input">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                            <i class="fa-solid fa-calendar-xmark text-pink-500 dark:text-pink-400 mr-1"></i> Check-out
                        </label>
                        <input type="date"
                               name="checkout"
                               value="{{ request('checkout', date('Y-m-d', strtotime('+1 day'))) }}"
                               class="search-input">
                    </div>
                </div>

                <!-- Row 3: Facilities Toggles -->
                <div class="mb-6">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2.5">
                        <i class="fa-solid fa-sliders text-indigo-500 dark:text-indigo-400 mr-1"></i> Fasilitas Populer
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
<section id="destinasi" class="py-24 px-4 sm:px-6 lg:px-8 bg-slate-50 dark:bg-slate-900/60 border-t border-b border-slate-200 dark:border-white/5 relative">
    <div class="max-w-7xl mx-auto">

        <!-- Section Title -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-extrabold uppercase tracking-widest text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10 px-3 py-1 rounded-full border border-indigo-200 dark:border-indigo-500/20">
                Pusat Daya Tarik Kota Medan
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white mt-3 mb-4 tracking-tight">
                Jelajahi Hotel Berdasarkan <span class="text-gradient">Landmark Ikonik</span>
            </h2>
            <p class="text-slate-600 dark:text-slate-400 text-sm sm:text-base leading-relaxed">
                Pilih landmark favorit Anda di Kota Medan dan temukan puluhan akomodasi terbaik di sekitarnya dengan jarak tempuh terdekat.
            </p>
        </div>

        <!-- Landmarks Grid (24 Cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($landmarks ?? [] as $lm)
            <div class="landmark-card group flex flex-col justify-between">
                <div>
                    <a href="{{ route('landmark.show', ['id' => $lm['id']]) }}" class="block landmark-img-wrap relative">
                        <img src="{{ asset($lm['foto'] ?? 'images/placeholder.svg') }}"
                             alt="{{ $lm['nama'] }}"
                             loading="lazy"
                             onerror="this.src='{{ asset('images/placeholder.svg') }}'">
                        <span class="absolute top-3 right-3 text-[11px] font-bold px-2.5 py-1 rounded-full bg-slate-900/80 text-white backdrop-blur-md">
                            <i class="fa-solid fa-landmark text-amber-400 mr-1"></i> {{ $lm['kat'] ?? 'Landmark' }}
                        </span>
                    </a>
                    <div class="p-5 pb-3">
                        <div class="flex items-center justify-between gap-2 mb-1.5">
                            <h3 class="font-extrabold text-slate-900 dark:text-white text-base group-hover:text-blue-600 dark:group-hover:text-blue-400 transition line-clamp-1">
                                <a href="{{ route('landmark.show', ['id' => $lm['id']]) }}">
                                    {{ $lm['nama'] }}
                                </a>
                            </h3>
                            @if(!empty($lm['rating']))
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-600 bg-amber-50 dark:bg-amber-950/60 px-2 py-0.5 rounded-md border border-amber-200 dark:border-amber-900">
                                <i class="fa-solid fa-star text-[9px]"></i> {{ $lm['rating'] }}
                            </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 flex items-start gap-1.5 mb-2 line-clamp-1">
                            <i class="fa-solid fa-location-dot text-blue-500 shrink-0 mt-0.5"></i>
                            <span>{{ $lm['alamat'] ?? 'Kota Medan' }}</span>
                        </p>
                    </div>
                </div>

                <div class="px-5 pb-5 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between gap-2">
                    <a href="{{ route('landmark.show', ['id' => $lm['id']]) }}"
                       class="text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition flex items-center gap-1">
                        Profil <i class="fa-solid fa-circle-info text-[10px]"></i>
                    </a>
                    <a href="{{ route('search', ['landmark' => $lm['nama']]) }}"
                       class="btn-gradient text-white text-xs font-bold px-3 py-1.5 rounded-xl shadow-sm flex items-center gap-1 hover:brightness-105 transition">
                        Hotel Terdekat <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- View All Destinations CTA -->
        <div class="mt-12 text-center flex flex-wrap items-center justify-center gap-4">
            <a href="{{ route('landmark.index') }}" class="btn-gradient inline-flex items-center gap-2 px-7 py-3.5 rounded-2xl text-white font-bold text-sm shadow-lg transition-all hover:scale-105">
                <i class="fa-solid fa-landmark text-amber-300"></i>
                <span>Buka Direktori 24 Landmark & Peta Interaktif</span>
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </a>
            <a href="{{ url('/search') }}" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-2xl bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-800 dark:text-white font-bold text-sm border border-slate-200 dark:border-slate-700 transition-all hover:scale-105 shadow-sm">
                <i class="fa-solid fa-hotel text-blue-600"></i>
                <span>Jelajahi Seluruh 312 Hotel di Medan</span>
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </a>
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     HOTEL PILIHAN PREMIUM MEDAN (CURATED SHOWCASE)
═══════════════════════════════════════════════════════════ --}}
<section id="hotel-pilihan" class="py-24 px-4 sm:px-6 lg:px-8 bg-white dark:bg-slate-900 relative">
    <div class="max-w-7xl mx-auto">

        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <span class="text-xs font-extrabold uppercase tracking-widest text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10 px-3 py-1 rounded-full border border-indigo-200 dark:border-indigo-500/20">
                    Akomodasi Unggulan
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white mt-3 tracking-tight">
                    Rekomendasi Hotel <span class="text-gradient">Terbaik di Medan</span>
                </h2>
                <p class="text-slate-600 dark:text-slate-400 text-sm sm:text-base mt-2">
                    Koleksi hotel berbintang dengan fasilitas lengkap dan rating tertinggi dari wisatawan.
                </p>
            </div>
            <a href="{{ url('/search') }}" class="mt-4 md:mt-0 inline-flex items-center gap-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-bold text-sm">
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
                            <h3 class="font-extrabold text-slate-900 dark:text-white text-base leading-snug group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition">
                                <a href="{{ route('hotel.detail', ['id' => $hotel['id']]) }}" class="no-underline text-inherit">
                                    {{ $hotel['nama'] }}
                                </a>
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-1">
                                <i class="fa-solid fa-location-dot text-indigo-500 mr-1"></i> {{ $hotel['alamat'] }}
                            </p>

                            <!-- Facility Chips -->
                            <div class="flex flex-wrap gap-1 mt-3">
                                @foreach(array_slice($hotel['facilities'], 0, 3) as $f)
                                    <span class="text-[10px] font-semibold bg-slate-100 dark:bg-white/10 text-slate-700 dark:text-slate-300 px-2 py-0.5 rounded-md border border-slate-200 dark:border-white/5">
                                        {{ $f }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-5 pt-4 border-t border-slate-200 dark:border-white/10 flex items-center justify-between">
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
<section id="keunggulan" class="py-24 px-4 sm:px-6 lg:px-8 bg-slate-50 dark:bg-slate-950 border-t border-slate-200 dark:border-white/5 relative">
    <div class="max-w-7xl mx-auto">

        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-extrabold uppercase tracking-widest text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10 px-3 py-1 rounded-full border border-indigo-200 dark:border-indigo-500/20">
                Pengalaman Menginap Nyaman & Pasti
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white mt-3 mb-4 tracking-tight">
                Kenapa Memesan di <span class="text-gradient">Roomify</span>?
            </h2>
            <p class="text-slate-600 dark:text-slate-400 text-sm sm:text-base leading-relaxed">
                Nikmati kemudahan reservasi hotel dengan harga transparan, navigasi rute Google Maps langsung dari ponsel Anda, serta jaminan check-in tepat waktu.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- Card 1: Garansi Harga Hemat & Transparan -->
            <div class="glass-card rounded-2xl p-6 sm:p-8 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl mb-5 border border-indigo-200 dark:border-indigo-500/30">
                        <i class="fa-solid fa-tags"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white mb-2">Garansi Harga Transparan</h3>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                        Harga final tanpa biaya siluman. Pilihan metode pembayaran instan terlengkap via QRIS (BCA, Mandiri, BRI, GoPay, OVO), Transfer Virtual Account, dan Kartu Kredit.
                    </p>
                    <div class="bg-emerald-50 dark:bg-black/40 rounded-xl p-3 border border-emerald-200 dark:border-white/10 text-xs text-emerald-700 dark:text-emerald-400 font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>E-Voucher & Bukti Pemesanan Terbit Instan</span>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-200 dark:border-white/10 flex items-center gap-2 text-xs text-indigo-600 dark:text-indigo-400 font-semibold">
                    <i class="fa-solid fa-shield-check"></i>
                    <span>Pembayaran 100% Aman & Terverifikasi</span>
                </div>
            </div>

            <!-- Card 2: Navigasi Google Maps Presisi -->
            <div class="glass-card rounded-2xl p-6 sm:p-8 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-pink-50 dark:bg-pink-500/20 text-pink-600 dark:text-pink-400 flex items-center justify-center text-xl mb-5 border border-pink-200 dark:border-pink-500/30">
                        <i class="fa-solid fa-route"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white mb-2">Navigasi Google Maps Presisi</h3>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                        Tak perlu tersesat mencari lobi hotel. Roomify dilengkapi panduan navigasi real-time langsung dari lokasi GPS Anda ke hotel yang Anda tuju di Kota Medan.
                    </p>
                    <div class="bg-pink-50 dark:bg-black/40 rounded-xl p-3 border border-pink-200 dark:border-white/10 text-xs text-pink-700 dark:text-pink-300 font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-diamond-turn-right"></i>
                        <span>Turn-by-turn Navigation & Estimasi Waktu</span>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-200 dark:border-white/10 flex items-center gap-2 text-xs text-pink-600 dark:text-pink-400 font-semibold">
                    <i class="fa-solid fa-location-dot"></i>
                    <span>Terintegrasi 13 Landmark Populer Medan</span>
                </div>
            </div>

            <!-- Card 3: Waktu Pasti & AI Concierge -->
            <div class="glass-card rounded-2xl p-6 sm:p-8 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-500/20 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xl mb-5 border border-purple-200 dark:border-purple-500/30">
                        <i class="fa-solid fa-bell-concierge"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white mb-2">Waktu Pasti & AI Concierge</h3>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                        Waktu Check-In resmi pukul 14:00 WIB dan Check-Out pukul 12:00 WIB. Didampingi asisten cerdas Roomify AI untuk memantau status e-tiket pesanan Anda 24 jam nonstop.
                    </p>
                    <div class="bg-purple-50 dark:bg-black/40 rounded-xl p-3 border border-purple-200 dark:border-white/10 text-xs text-purple-700 dark:text-purple-300 font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-id-card"></i>
                        <span>Verifikasi NIK e-KTP 16 Digit Terenkripsi</span>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-200 dark:border-white/10 flex items-center gap-2 text-xs text-purple-600 dark:text-purple-400 font-semibold">
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
                    Platform pemesanan hotel terbaik di Kota Medan. Temukan 312 pilihan akomodasi nyaman dekat landmark bersejarah, pusat bisnis, dan kuliner khas Medan dengan garansi harga hemat dan navigasi Google Maps presisi.
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
