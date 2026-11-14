<!doctype html>
<html lang="id" prefix="schema: https://schema.org/ roomify: http://roomify.example.org/ontology# rdfs: http://www.w3.org/2000/01/rdf-schema#">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hasil Pencarian Hotel — Roomify</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            50:  '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
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
        * { font-family: 'Inter', sans-serif; }

        /* Glass Navbar */
        .glass-nav {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255,255,255,0.3);
        }

        /* Page gradient background */
        body {
            background: linear-gradient(135deg, #f0f4ff 0%, #e8f0fe 30%, #f5f3ff 60%, #faf5ff 100%);
            min-height: 100vh;
        }

        /* Sidebar glass card */
        .filter-card {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.6);
            box-shadow: 0 8px 32px rgba(37,99,235,0.08), 0 1px 4px rgba(0,0,0,0.04);
        }

        /* Hotel card hover */
        .hotel-card {
            background: rgba(255,255,255,0.95);
            border: 1px solid rgba(226,232,240,0.8);
            transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .hotel-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(37,99,235,0.12), 0 4px 12px rgba(0,0,0,0.06);
            border-color: rgba(147,197,253,0.6);
        }
        .hotel-card.highlighted {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(37,99,235,0.25), 0 4px 12px rgba(37,99,235,0.15);
            border-color: #3b82f6;
            outline: 2px solid #3b82f6;
        }

        /* Map container */
        #search-map {
            width: 100%;
            height: 420px;
            border-radius: 16px;
            overflow: hidden;
        }

        /* Rating badge colors */
        .rating-excellent { background: #dcfce7; color: #166534; }
        .rating-good      { background: #dbeafe; color: #1e40af; }
        .rating-average   { background: #fef9c3; color: #854d0e; }
        .rating-poor      { background: #fee2e2; color: #991b1b; }

        /* Gradient button */
        .btn-gradient {
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            transition: all 0.2s;
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #4338ca 100%);
            box-shadow: 0 8px 20px rgba(37,99,235,0.35);
            transform: translateY(-1px);
        }

        /* Custom range slider */
        input[type=range] {
            -webkit-appearance: none;
            height: 6px;
            border-radius: 3px;
            background: #dbeafe;
            outline: none;
        }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 18px; height: 18px;
            border-radius: 50%;
            background: #2563eb;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(37,99,235,0.4);
        }

        /* Checkbox custom */
        input[type=checkbox] {
            accent-color: #2563eb;
            width: 15px; height: 15px;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }

        /* Logo gradient */
        .logo-gradient {
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Section heading underline */
        .section-line {
            height: 3px;
            width: 40px;
            background: linear-gradient(90deg, #2563eb, #7c3aed);
            border-radius: 2px;
        }

        /* Map marker info window custom style injected via JS */
        .gm-style .custom-iw { font-family: 'Inter', sans-serif; }

        /* Animated pulse for map button */
        @keyframes pulse-blue {
            0%, 100% { box-shadow: 0 0 0 0 rgba(37,99,235,0.4); }
            50%       { box-shadow: 0 0 0 8px rgba(37,99,235,0); }
        }
        .pulse-btn { animation: pulse-blue 2s infinite; }

        /* Top Loader Progress Bar */
        #top-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
            z-index: 99999;
            transition: width 0.25s ease;
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.7);
        }

        /* Page Entrance Animation */
        main {
            animation: pageFadeIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes pageFadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Shimmer Effect for Skeleton Cards */
        @keyframes shimmer {
            100% { transform: translateX(100%); }
        }
        .shimmer-box {
            position: relative;
            overflow: hidden;
            background: #e2e8f0;
        }
        html.dark .shimmer-box {
            background: #1e293b;
        }
        .shimmer-box::after {
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            transform: translateX(-100%);
            background: linear-gradient(90deg, rgba(255,255,255,0) 0, rgba(255,255,255,0.4) 50%, rgba(255,255,255,0) 100%);
            animation: shimmer 1.5s infinite;
            content: '';
        }
        html.dark .shimmer-box::after {
            background: linear-gradient(90deg, rgba(255,255,255,0) 0, rgba(255,255,255,0.08) 50%, rgba(255,255,255,0) 100%);
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        html.dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }

        /* Dark Mode Comprehensive Styling */
        html.dark body {
            background: #0b0f19 !important;
            color: #f1f5f9 !important;
        }
        html.dark .glass-nav {
            background: rgba(11, 15, 25, 0.92) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        html.dark .filter-card {
            background: #111827 !important;
            border: 1px solid #1f2937 !important;
            color: #f1f5f9 !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
        }
        html.dark .filter-card label,
        html.dark .filter-card h2,
        html.dark .filter-card .filter-label {
            color: #f1f5f9 !important;
        }
        html.dark .filter-card select,
        html.dark .filter-card input[type=text],
        html.dark .filter-card input[type=number] {
            background: #1f2937 !important;
            color: #ffffff !important;
            border: 1px solid #374151 !important;
        }
        html.dark .filter-card select:focus,
        html.dark .filter-card input:focus {
            border-color: #60a5fa !important;
        }
        html.dark .facility-option {
            background: #1f2937 !important;
            border-color: #374151 !important;
            color: #cbd5e1 !important;
        }
        html.dark .facility-option:hover {
            background: #374151 !important;
            border-color: #60a5fa !important;
        }
        html.dark .hotel-card {
            background: #111827 !important;
            border: 1px solid #1f2937 !important;
            color: #f1f5f9 !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35) !important;
        }
        html.dark .hotel-card:hover {
            border-color: #3b82f6 !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6) !important;
        }
        html.dark .hotel-card h3 a {
            color: #ffffff !important;
        }
        html.dark .hotel-card h3 a:hover {
            color: #60a5fa !important;
        }
        html.dark .text-slate-900,
        html.dark .text-slate-800,
        html.dark .text-slate-700 {
            color: #f8fafc !important;
        }
        html.dark .text-slate-600,
        html.dark .text-slate-500 {
            color: #94a3b8 !important;
        }
        html.dark .bg-slate-50 {
            background-color: #111827 !important;
        }
        html.dark .border-slate-200,
        html.dark .border-slate-100 {
            border-color: #1f2937 !important;
        }
        html.dark #mobile-menu {
            background: #111827 !important;
            border-color: #1f2937 !important;
        }
        html.dark #map-toggle-btn {
            background: #1f2937 !important;
            color: #f1f5f9 !important;
            border-color: #374151 !important;
        }
        html.dark .landmark-banner {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(15, 23, 42, 0.9)) !important;
            border-color: #374151 !important;
        }
    </style>
</head>
<body class="antialiased min-h-screen">
    <!-- Top Progress Bar -->
    <div id="top-loader"></div>

    <!-- ============================================================
         STICKY TOP BAR: LIVE CLOCK & GLASS NAVBAR
    ============================================================ -->
    <div class="sticky top-0 z-50">
        <x-live-clock-bar />
        <nav class="glass-nav">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">

            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 shrink-0 group no-underline">
                <div class="w-9 h-9 relative transform transition-transform group-hover:scale-105">
                    <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full drop-shadow">
                        <defs>
                            <linearGradient id="nb-search-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#4F46E5"/>
                                <stop offset="50%" stop-color="#7C3AED"/>
                                <stop offset="100%" stop-color="#EC4899"/>
                            </linearGradient>
                            <linearGradient id="nb-gold-search" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#FCD34D"/>
                                <stop offset="100%" stop-color="#F59E0B"/>
                            </linearGradient>
                        </defs>
                        <rect width="48" height="48" rx="14" fill="url(#nb-search-grad)"/>
                        <rect x="0.75" y="0.75" width="46.5" height="46.5" rx="13.25" stroke="rgba(255,255,255,0.3)" stroke-width="1.5"/>
                        <path d="M12 36V22C12 15.3726 17.3726 10 24 10C30.6274 10 36 15.3726 36 22V36" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round"/>
                        <path d="M18 36V18H25.5C28 18 29.5 19.5 29.5 22C29.5 24.5 28 26 25.5 26H18M24 26L30 36" stroke="#FFFFFF" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M34 11L35.2 13.8L38 15L35.2 16.2L34 19L32.8 16.2L30 15L32.8 13.8L34 11Z" fill="url(#nb-gold-search)"/>
                        <circle cx="24" cy="31" r="1.5" fill="#FCD34D"/>
                    </svg>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-xl font-black bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 bg-clip-text text-transparent tracking-tight">Roomify</span>
                </div>
            </a>

            <!-- Nav Links -->
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ url('/') }}" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-house text-xs"></i> Beranda
                </a>
                <a href="{{ url('/search') }}" class="text-sm font-semibold text-blue-600 flex items-center gap-1.5">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i> Cari Hotel
                </a>
                <a href="{{ url('/') }}#destinasi" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-map-pin text-xs"></i> Landmark Medan
                </a>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-3">
                <button onclick="toggleTheme()" type="button" class="p-2 rounded-xl text-slate-500 hover:text-slate-700 dark:text-slate-300 dark:hover:text-white bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 transition" aria-label="Toggle theme">
                    <i class="fa-solid fa-moon hidden dark:inline text-amber-300"></i>
                    <i class="fa-solid fa-sun inline dark:hidden text-amber-500"></i>
                </button>
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-slate-700 hover:text-blue-600 px-3 py-1.5 rounded-lg transition hidden sm:flex items-center gap-1.5">
                        <i class="fa-solid fa-user-circle"></i>
                        <span>Akun</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-700 hover:text-blue-600 px-3 py-1.5 rounded-lg transition hidden sm:block">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-gradient text-white text-sm font-semibold px-4 py-1.5 rounded-xl hidden sm:block">Daftar</a>
                @endauth
                <!-- Mobile menu btn -->
                <button class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-100 px-4 py-3 space-y-1 bg-white/95">
            <a href="{{ url('/') }}" class="block px-3 py-2 rounded-lg text-sm text-slate-700 hover:bg-slate-50">🏠 Beranda</a>
            <a href="{{ url('/search') }}" class="block px-3 py-2 rounded-lg text-sm text-blue-600 font-semibold bg-blue-50">🔍 Cari Hotel</a>
            <a href="{{ url('/') }}#landmarks" class="block px-3 py-2 rounded-lg text-sm text-slate-700 hover:bg-slate-50">📍 Landmark Medan</a>
        </div>
    </nav>
    </div>

    <!-- ============================================================
         MAIN CONTENT
    ============================================================ -->
    <main class="max-w-screen-xl mx-auto px-4 sm:px-6 py-8">

        <!-- Page Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <div class="section-line"></div>
                    <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Hasil Pencarian</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900">Hotel di <span class="logo-gradient">Medan</span></h1>
                @if(request('q') || request('landmark'))
                    <p class="text-sm text-slate-500 mt-0.5">
                        @if(request('q'))<span class="font-medium text-slate-700">"{{ request('q') }}"</span>@endif
                        @if(request('landmark')) · Dekat <span class="font-medium text-slate-700">{{ request('landmark') }}</span>@endif
                    </p>
                @endif
            </div>

            <!-- Result Count Badge -->
            <div class="flex items-center gap-2 shrink-0">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-bold" style="background: linear-gradient(135deg, #dbeafe, #ede9fe); color: #1e40af;">
                    <i class="fa-solid fa-hotel text-blue-500"></i>
                    <span id="hotel-count-badge">{{ count($hotels ?? []) }}</span> Hotel Ditemukan
                </div>
                <!-- Toggle Map Button -->
                <button onclick="toggleMap()" id="map-toggle-btn" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm font-medium text-slate-700 hover:border-blue-400 hover:text-blue-600 transition shadow-sm">
                    <i class="fa-solid fa-map-location-dot text-blue-500"></i>
                    <span id="map-toggle-label">Sembunyikan Peta</span>
                </button>
            </div>
        </div>

        <!-- Two-Panel Layout -->
        <div class="flex flex-col lg:flex-row gap-7">

            <!-- =====================================================
                 LEFT: FILTER SIDEBAR (SCROLLABLE INDEPENDENTLY)
            ===================================================== -->
            <aside class="w-full lg:w-72 xl:w-80 shrink-0">
                <form action="{{ url('/search') }}" method="GET" id="search-filter-form"
                      class="filter-card rounded-2xl p-5 space-y-5 lg:sticky lg:top-20 lg:max-h-[calc(100vh-5.5rem)] lg:overflow-y-auto custom-scrollbar">

                    <!-- Hidden Geolocation Coordinates for Real-Time Distance Filter -->
                    <input type="hidden" name="user_lat" id="filter-user-lat" value="{{ request('user_lat', $userLat ?? '') }}">
                    <input type="hidden" name="user_lng" id="filter-user-lng" value="{{ request('user_lng', $userLng ?? '') }}">

                    <!-- Sidebar Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #2563eb, #7c3aed);">
                                <i class="fa-solid fa-sliders text-white text-xs"></i>
                            </div>
                            <h2 class="font-bold text-slate-900 text-sm">Filter Pencarian</h2>
                        </div>
                        <a href="{{ url('/search') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 hover:underline">
                            <i class="fa-solid fa-rotate-left text-[10px]"></i> Reset
                        </a>
                    </div>

                    <!-- Filter 1: Keyword -->
                    <div class="space-y-1.5">
                        <label for="q" class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <i class="fa-solid fa-magnifying-glass text-blue-400"></i> Kata Kunci
                        </label>
                        <div class="relative">
                            <input type="text" id="q" name="q" value="{{ request('q') }}"
                                   placeholder="Nama hotel, lokasi..."
                                   class="w-full bg-slate-50/80 border border-slate-200 rounded-xl px-3 py-2.5 pl-9 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        </div>
                    </div>

                    <!-- Filter 2: Landmark -->
                    <div class="space-y-1.5">
                        <label for="landmark" class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <i class="fa-solid fa-map-pin text-blue-400"></i> Dekat Landmark
                        </label>
                        <div class="relative">
                            <select id="landmark" name="landmark"
                                    class="w-full bg-slate-50/80 border border-slate-200 rounded-xl px-3 py-2.5 pr-8 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition appearance-none">
                                <option value="">Semua Landmark Medan</option>
                                <option value="Istana Maimun"             {{ request('landmark') == 'Istana Maimun' ? 'selected' : '' }}>🏛️ Istana Maimun</option>
                                <option value="Tjong A Fie Mansion"       {{ request('landmark') == 'Tjong A Fie Mansion' ? 'selected' : '' }}>🏮 Tjong A Fie Mansion</option>
                                <option value="Masjid Raya Al Mashun"     {{ request('landmark') == 'Masjid Raya Al Mashun' ? 'selected' : '' }}>🕌 Masjid Raya Al-Mashun</option>
                                <option value="Lapangan Merdeka"          {{ request('landmark') == 'Lapangan Merdeka' ? 'selected' : '' }}>🌳 Lapangan Merdeka (KM 0)</option>
                                <option value="Graha Maria Annai Velangkanni" {{ request('landmark') == 'Graha Maria Annai Velangkanni' ? 'selected' : '' }}>⛪ Graha Maria Annai Velangkanni</option>
                                <option value="Sun Plaza"                 {{ request('landmark') == 'Sun Plaza' ? 'selected' : '' }}>🛍️ Sun Plaza Medan</option>
                                <option value="Maha Vihara Maitreya"      {{ request('landmark') == 'Maha Vihara Maitreya' ? 'selected' : '' }}>🪷 Maha Vihara Maitreya (Cemara Asri)</option>
                                <option value="Museum Rahmat"             {{ request('landmark') == 'Museum Rahmat' ? 'selected' : '' }}>🦁 Museum Rahmat International Gallery</option>
                                <option value="Merdeka Walk"              {{ request('landmark') == 'Merdeka Walk' ? 'selected' : '' }}>🍜 Merdeka Walk Kesawan</option>
                                <option value="Menara Air Tirtanadi"      {{ request('landmark') == 'Menara Air Tirtanadi' ? 'selected' : '' }}>🗼 Menara Air Tirtanadi 1908</option>
                                <option value="Centre Point Mall"         {{ request('landmark') == 'Centre Point Mall' ? 'selected' : '' }}>🚆 Centre Point Mall & Railink</option>
                                <option value="Kebun Binatang Medan"      {{ request('landmark') == 'Kebun Binatang Medan' ? 'selected' : '' }}>🐯 Medan Zoo (Simalingkar)</option>
                                <option value="Kuil Shri Mariamman"       {{ request('landmark') == 'Kuil Shri Mariamman' ? 'selected' : '' }}>🕉️ Kuil Shri Mariamman</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>

                    <!-- Filter 3: Star Rating -->
                    <div class="space-y-1.5">
                        <label for="rating" class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <i class="fa-solid fa-star text-amber-400"></i> Rating Bintang
                        </label>
                        <div class="grid grid-cols-5 gap-1.5">
                            @foreach([1,2,3,4,5] as $star)
                                <label class="cursor-pointer">
                                    <input type="radio" name="rating" value="{{ $star }}" class="sr-only peer" {{ request('rating') == $star ? 'checked' : '' }}>
                                    <div class="text-center py-2 rounded-xl border-2 border-slate-200 text-xs font-bold text-slate-500 peer-checked:border-amber-400 peer-checked:bg-amber-50 peer-checked:text-amber-700 hover:border-amber-300 transition cursor-pointer">
                                        {{ $star }}★
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <div class="text-center">
                            <label class="inline-flex items-center gap-1 cursor-pointer text-xs text-slate-400 hover:text-slate-600">
                                <input type="radio" name="rating" value="" {{ !request('rating') ? 'checked' : '' }} class="sr-only"> Semua bintang
                            </label>
                        </div>
                    </div>

                    <!-- Filter 4: Price Range -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <i class="fa-solid fa-tag text-blue-400"></i> Rentang Harga
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-[10px] text-slate-400 font-medium block mb-1">Min (Rp)</label>
                                <input type="number" name="price_min" value="{{ request('price_min') }}"
                                       placeholder="0"
                                       class="w-full bg-slate-50/80 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-400 font-medium block mb-1">Max (Rp)</label>
                                <input type="number" name="price_max" value="{{ request('price_max') }}"
                                       placeholder="∞"
                                       class="w-full bg-slate-50/80 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                            </div>
                        </div>
                    </div>

                    <!-- Filter 5: Facilities -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <i class="fa-solid fa-concierge-bell text-blue-400"></i> Fasilitas
                        </label>
                        @php $selectedFacilities = request('facilities', []); @endphp
                        <div class="grid grid-cols-2 gap-1.5">
                            @php
                                $facilityList = [
                                    ['WiFi',         'fa-wifi',          'WiFi Gratis'],
                                    ['Kolam Renang', 'fa-water-ladder',   'Kolam Renang'],
                                    ['AC',           'fa-snowflake',      'AC'],
                                    ['Sarapan',      'fa-utensils',       'Sarapan'],
                                    ['Gym',          'fa-dumbbell',       'Gym'],
                                    ['Parkir',       'fa-square-parking', 'Parkir'],
                                    ['Spa',          'fa-spa',            'Spa'],
                                    ['Restaurant',   'fa-fork-knife',     'Restoran'],
                                ];
                            @endphp
                            @foreach($facilityList as [$val, $icon, $label])
                                <label class="facility-option flex items-center gap-2 px-2.5 py-2 rounded-xl border border-slate-200 cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition {{ in_array($val, $selectedFacilities) ? 'border-blue-400 bg-blue-50' : '' }}">
                                    <input type="checkbox" name="facilities[]" value="{{ $val }}"
                                           {{ in_array($val, $selectedFacilities) ? 'checked' : '' }}
                                           class="shrink-0">
                                    <span class="flex items-center gap-1.5 text-xs text-slate-700 font-medium">
                                        <i class="fa-solid {{ $icon }} text-blue-500 w-3.5 text-center"></i>
                                        {{ $label }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Filter 6: Sort -->
                    <div class="space-y-1.5">
                        <label for="sort" class="flex items-center gap-1.5 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <i class="fa-solid fa-arrow-up-wide-short text-blue-400"></i> Urutkan
                        </label>
                        <div class="relative">
                            <select id="sort" name="sort"
                                    class="w-full bg-slate-50/80 border border-slate-200 rounded-xl px-3 py-2.5 pr-8 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-400 transition appearance-none">
                                <option value=""          {{ !request('sort') ? 'selected' : '' }}>Relevansi</option>
                                <option value="terdekat"  {{ request('sort') == 'terdekat' ? 'selected' : '' }}>📍 Terdekat dari Lokasi Saya</option>
                                <option value="harga_asc" {{ request('sort') == 'harga_asc' ? 'selected' : '' }}>💲 Harga Terendah</option>
                                <option value="harga_desc"{{ request('sort') == 'harga_desc' ? 'selected' : '' }}>💰 Harga Tertinggi</option>
                                <option value="rating"    {{ request('sort') == 'rating' ? 'selected' : '' }}>⭐ Rating Tertinggi</option>
                                <option value="nama"      {{ request('sort') == 'nama' ? 'selected' : '' }}>🔤 Nama A–Z</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>

                    <!-- Apply Button -->
                    <button type="submit"
                            class="btn-gradient w-full text-white font-bold py-3 px-4 rounded-xl text-sm shadow-lg flex items-center justify-center gap-2 pulse-btn">
                        <i class="fa-solid fa-filter"></i>
                        Terapkan Filter
                    </button>
                </form>
            </aside>

            <!-- =====================================================
                 RIGHT: MAP + HOTEL CARDS
            ===================================================== -->
            <div class="flex-1 min-w-0 space-y-6">

                <!-- GOOGLE MAP SECTION -->
                <div id="map-section" class="rounded-2xl overflow-hidden" style="box-shadow: 0 8px 32px rgba(37,99,235,0.1), 0 2px 8px rgba(0,0,0,0.06);">
                    <!-- Map Header -->
                    <div class="flex items-center justify-between px-4 py-3"
                         style="background: linear-gradient(135deg, #1e3a8a 0%, #312e81 100%);">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-map-location-dot text-blue-300 text-sm"></i>
                            <span class="text-white font-semibold text-sm">Peta Hotel Medan</span>
                            <span class="text-blue-300 text-xs font-medium">· {{ count($hotels ?? []) }} lokasi</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="detectUserLocation()" id="btn-detect-loc" class="px-3 py-1 rounded-lg bg-blue-600/70 hover:bg-blue-600 text-white text-xs font-bold border border-blue-400/40 flex items-center gap-1.5 transition shadow-sm">
                                <i class="fa-solid fa-location-crosshairs text-[11px]"></i>
                                <span>Lokasi Saya</span>
                            </button>
                            <div class="flex items-center gap-1.5 pl-2 border-l border-blue-400/30">
                                <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                                <span class="text-blue-200 text-xs font-medium">Live</span>
                            </div>
                        </div>
                    </div>

                    <!-- Live Route Navigation Info Bar -->
                    <div id="map-route-bar" class="hidden px-4 py-2.5 bg-indigo-950 text-white text-xs flex flex-wrap items-center justify-between gap-2 border-b border-indigo-800/60">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                            <span class="font-extrabold text-indigo-200">Rute Aktif:</span>
                            <span id="map-route-destination" class="font-bold text-white truncate max-w-[160px] sm:max-w-xs">Hotel Tujuan</span>
                            <span id="map-route-stats" class="px-2 py-0.5 rounded-full bg-indigo-800 text-[11px] font-semibold text-emerald-300">0 km · 0 mnt</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="toggleRouteSteps()" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-[10px] font-bold transition flex items-center gap-1 shadow-sm">
                                <i class="fa-solid fa-diamond-turn-right"></i> <span id="btn-steps-label">Petunjuk Belokan</span>
                            </button>
                            <a id="btn-open-gmaps" href="#" target="_blank" rel="noopener" class="px-2.5 py-1 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-bold transition flex items-center gap-1">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Google Maps
                            </a>
                            <button type="button" onclick="clearNavigationRoute()" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white text-[10px] font-bold transition">
                                <i class="fa-solid fa-xmark mr-1"></i>Tutup Rute
                            </button>
                        </div>
                    </div>

                    <!-- Turn-by-Turn Expandable Steps Drawer -->
                    <div id="route-steps-drawer" class="hidden max-h-56 overflow-y-auto px-4 py-3 bg-slate-900 text-slate-200 text-xs border-b border-slate-800 space-y-2 custom-scrollbar">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-800 font-bold text-white text-xs">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-route text-emerald-400"></i>
                                <span>Petunjuk Belokan Navigasi In-App</span>
                            </span>
                            <span id="route-steps-count" class="text-[10px] text-slate-400">0 Belokan</span>
                        </div>
                        <div id="route-steps-list" class="space-y-1.5 text-[11px]"></div>
                    </div>

                    <!-- Map Container -->
                    <div id="search-map"></div>
                </div>

                <!-- LANDMARK INFO BANNER (IF FILTERED) -->
                @if(!empty($landmarkInfo))
                    <div class="landmark-banner rounded-2xl p-5 bg-gradient-to-r from-amber-500/10 via-indigo-500/10 to-purple-500/10 border border-amber-300/50 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-500 to-amber-600 text-white flex items-center justify-center text-xl shadow-md shrink-0">
                                <i class="fa-solid fa-landmark"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800">Landmark Acuan Terpilih</span>
                                <h3 class="text-base font-extrabold text-slate-900 mt-1">Hotel di Sekitar {{ $landmarkInfo['name'] }}</h3>
                                <p class="text-xs text-slate-500">Peta interaktif menampilkan pin emas lokasi {{ $landmarkInfo['name'] }} beserta hotel terdekat.</p>
                            </div>
                        </div>
                        <a href="{{ url('/search') }}" class="text-xs font-bold text-slate-600 hover:text-indigo-600 px-3.5 py-2 rounded-xl bg-white border border-slate-200 transition shadow-xs shrink-0 flex items-center gap-1.5">
                            <i class="fa-solid fa-rotate-left text-[10px]"></i> Reset Landmark
                        </a>
                    </div>
                @endif

                <!-- HOTEL CARDS GRID -->
                <div>
                    <!-- Grid Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="section-line"></div>
                            <h2 class="font-bold text-slate-800 text-base">Daftar Hotel di Medan</h2>
                        </div>
                        <div class="text-xs text-slate-500">
                            Menampilkan <span class="font-semibold text-slate-700">{{ count($hotels ?? []) }}</span> hotel
                        </div>
                    </div>

                    <!-- SKELETON LOADING GRID (DISPLAYED ON FILTER/SORT/NAV) -->
                    <div id="skeleton-grid" class="hidden grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 mb-5">
                        @for($i = 0; $i < 6; $i++)
                            <div class="rounded-2xl overflow-hidden bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col">
                                <div class="h-48 shimmer-box"></div>
                                <div class="p-4 space-y-3 flex-1 flex flex-col justify-between">
                                    <div class="space-y-2">
                                        <div class="h-4 w-3/4 shimmer-box rounded"></div>
                                        <div class="h-3 w-1/2 shimmer-box rounded"></div>
                                        <div class="flex gap-2 pt-2">
                                            <div class="h-5 w-14 shimmer-box rounded-lg"></div>
                                            <div class="h-5 w-16 shimmer-box rounded-lg"></div>
                                        </div>
                                    </div>
                                    <div class="pt-3 border-t border-slate-100 dark:border-slate-700 flex justify-between items-center">
                                        <div class="h-6 w-24 shimmer-box rounded"></div>
                                        <div class="h-8 w-20 shimmer-box rounded-xl"></div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>

                    <!-- Results Grid -->
                    <div id="hotel-results-grid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                        @forelse ($hotels ?? [] as $item)
                            {{-- RDFa: roomify:Hotel & schema:Hotel --}}
                            <article
                                typeof="roomify:Hotel schema:Hotel"
                                about="http://roomify.example.org/ontology#{{ $item['id'] ?? $loop->index }}"
                                id="hotel-card-{{ $item['id'] ?? $loop->index }}"
                                data-lat="{{ $item['latitude'] ?? '' }}"
                                data-lng="{{ $item['longitude'] ?? '' }}"
                                data-hotel-id="{{ $item['id'] ?? $loop->index }}"
                                class="hotel-card rounded-2xl overflow-hidden flex flex-col cursor-pointer transition transform hover:-translate-y-1"
                                onclick="window.location.href='{{ route('hotel.detail', ['id' => $item['id'] ?? 1]) }}'"
                            >
                                <!-- Hotel Photo -->
                                <div class="relative overflow-hidden h-48 bg-gradient-to-br from-blue-100 to-indigo-100">
                                    <img src="{{ asset($item['foto'] ?? 'images/placeholder.svg') }}"
                                         property="schema:image"
                                         alt="{{ $item['nama'] ?? 'Hotel' }}"
                                         class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <!-- Fallback gradient placeholder -->
                                    <div class="absolute inset-0 items-center justify-center hidden"
                                         style="background: linear-gradient(135deg, #1e3a8a 0%, #312e81 100%);">
                                        <div class="text-center text-white">
                                            <i class="fa-solid fa-hotel text-4xl mb-2 opacity-60"></i>
                                            <p class="text-xs font-medium opacity-80">{{ $item['nama'] ?? 'Hotel' }}</p>
                                        </div>
                                    </div>

                                    <!-- Star Rating Badge (top-left) -->
                                    @if(!empty($item['bintang']) || !empty($item['starRating']))
                                        @php $stars = (int)($item['bintang'] ?? $item['starRating'] ?? 0); @endphp
                                        <div class="absolute top-3 left-3">
                                            <span property="roomify:starRating schema:starRating"
                                                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold text-white shadow-lg"
                                                  style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                                                <i class="fa-solid fa-star text-[10px]"></i>
                                                {{ $stars }}
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Review Score Badge (top-right) -->
                                    @if(!empty($item['reviewScore']) || !empty($item['rating']))
                                        @php
                                            $score = (float)($item['reviewScore'] ?? $item['rating'] ?? 0);
                                            $ratingClass = $score >= 8.5 ? 'rating-excellent' : ($score >= 7 ? 'rating-good' : ($score >= 5 ? 'rating-average' : 'rating-poor'));
                                            $ratingLabel = $score >= 8.5 ? 'Luar Biasa' : ($score >= 7 ? 'Bagus' : ($score >= 5 ? 'Cukup' : 'Kurang'));
                                        @endphp
                                        <div class="absolute top-3 right-3">
                                            <div class="flex flex-col items-center px-2.5 py-1.5 rounded-xl shadow-lg {{ $ratingClass }}">
                                                <span class="text-sm font-black leading-none">{{ number_format($score, 1) }}</span>
                                                <span class="text-[9px] font-semibold mt-0.5">{{ $ratingLabel }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Map Action Buttons (Rute & Peta) -->
                                    @if(!empty($item['latitude']) && !empty($item['longitude']))
                                        <div class="absolute bottom-3 right-3 z-10 flex items-center gap-1.5">
                                            <button type="button"
                                                    onclick="event.stopPropagation(); startNavigationToHotel({{ $item['latitude'] }}, {{ $item['longitude'] }}, '{{ addslashes($item['nama'] ?? 'Hotel') }}')"
                                                    title="Petunjuk arah rute Google Maps"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-white/95 hover:bg-emerald-600 hover:text-white text-emerald-700 dark:bg-slate-900/90 dark:text-emerald-400 shadow transition">
                                                <i class="fa-solid fa-diamond-turn-right text-emerald-500 text-[9px]"></i> Rute
                                            </button>
                                            <button type="button"
                                                    onclick="event.stopPropagation(); focusHotelOnMap('{{ $item['id'] ?? $loop->index }}', {{ $item['latitude'] ?? 'null' }}, {{ $item['longitude'] ?? 'null' }})"
                                                    title="Fokuskan hotel di peta"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-white/95 hover:bg-blue-600 hover:text-white text-blue-700 dark:bg-slate-900/90 dark:text-blue-400 shadow transition">
                                                <i class="fa-solid fa-location-dot text-blue-500 text-[9px]"></i> Peta
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                <!-- Card Body -->
                                <div class="p-4 flex-1 flex flex-col">

                                    <!-- Hotel Name -->
                                    <h3 property="rdfs:label schema:name"
                                        class="font-bold text-slate-900 dark:text-white text-base leading-tight mb-1.5 line-clamp-1">
                                        <a href="{{ route('hotel.detail', ['id' => $item['id'] ?? 1]) }}"
                                           class="hover:text-blue-500 transition">
                                            {{ $item['nama'] ?? 'Nama Hotel' }}
                                        </a>
                                    </h3>

                                    <!-- Address -->
                                    <p property="roomify:address schema:address"
                                       class="text-xs text-slate-500 dark:text-slate-400 flex items-start gap-1.5 mb-2.5">
                                        <i class="fa-solid fa-location-dot text-blue-400 mt-0.5 shrink-0"></i>
                                        <span class="line-clamp-1">{{ $item['alamat'] ?? 'Medan, Sumatera Utara' }}</span>
                                    </p>

                                    <!-- Facilities Chips -->
                                    @if(!empty($item['fasilitas']))
                                        @php
                                            $facIcons = [
                                                'WiFi'         => 'fa-wifi',
                                                'Kolam Renang' => 'fa-water-ladder',
                                                'AC'           => 'fa-snowflake',
                                                'Sarapan'      => 'fa-utensils',
                                                'Gym'          => 'fa-dumbbell',
                                                'Parkir'       => 'fa-square-parking',
                                                'Spa'          => 'fa-spa',
                                                'Restaurant'   => 'fa-fork-knife',
                                            ];
                                        @endphp
                                        <div class="flex flex-wrap gap-1.5 mb-2.5">
                                            @foreach(array_slice($item['fasilitas'], 0, 4) as $fac)
                                                <span rel="roomify:hasFacility"
                                                      class="inline-flex items-center gap-1 bg-blue-50 dark:bg-slate-800 text-blue-700 dark:text-blue-300 text-[10px] font-semibold px-2 py-0.5 rounded-lg border border-blue-100/50 dark:border-slate-700">
                                                    <i class="fa-solid {{ $facIcons[$fac] ?? 'fa-check' }} text-blue-400 text-[9px]"></i>
                                                    <span property="rdfs:label">{{ $fac }}</span>
                                                </span>
                                            @endforeach
                                            @if(count($item['fasilitas']) > 4)
                                                <span class="inline-flex items-center text-[10px] text-slate-400 font-medium px-2 py-0.5 bg-slate-50 dark:bg-slate-800/60 rounded-lg">
                                                    +{{ count($item['fasilitas']) - 4 }} lagi
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Nearby Landmark -->
                                    @if(!empty($item['landmarks']))
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1.5 mb-2">
                                            <i class="fa-solid fa-landmark text-purple-400 text-[10px]"></i>
                                            <span>Dekat: </span>
                                            <span rel="roomify:nearLandmark" class="font-semibold text-slate-700 dark:text-slate-200">
                                                <span property="rdfs:label">{{ is_array($item['landmarks']) ? $item['landmarks'][0] : $item['landmarks'] }}</span>
                                            </span>
                                        </p>
                                    @endif

                                    <!-- Distance Badge (Populated when user coords exist or on GPS detection) -->
                                    <div class="dist-badge {{ !empty($item['jarak']) ? '' : 'hidden' }} mb-2.5 text-[11px] font-bold text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/70 border border-indigo-100 dark:border-indigo-800/80 px-2 py-0.5 rounded-lg inline-flex items-center gap-1">
                                        <i class="fa-solid fa-location-crosshairs text-[10px]"></i>
                                        <span class="dist-val">{{ $item['jarak'] ?? '' }}</span> dari lokasi Anda
                                    </div>

                                    <!-- Spacer -->
                                    <div class="flex-1"></div>

                                    <!-- Price + CTA -->
                                    <div class="pt-3 mt-2 border-t border-slate-100 dark:border-slate-800 flex items-end justify-between gap-2">
                                        <div>
                                            <span class="text-[10px] text-slate-400 font-semibold uppercase block tracking-wide">Mulai dari</span>
                                            <span property="roomify:startPrice"
                                                  content="{{ $item['harga'] ?? $item['startPrice'] ?? 0 }}"
                                                  class="text-lg font-black text-blue-600 dark:text-blue-400">
                                                Rp&nbsp;{{ number_format((int)($item['harga'] ?? $item['startPrice'] ?? 0), 0, ',', '.') }}
                                            </span>
                                            <span class="text-[10px] text-slate-400">/malam</span>
                                        </div>
                                        <a href="{{ route('hotel.detail', ['id' => $item['id'] ?? 1]) }}"
                                           onclick="event.stopPropagation()"
                                           class="btn-gradient text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-md shrink-0 flex items-center gap-1.5">
                                            Lihat Detail
                                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>

                        @empty
                            <!-- EMPTY STATE -->
                            <div class="col-span-full">
                                <div class="rounded-2xl p-12 text-center" style="background: rgba(255,255,255,0.9);">
                                    <!-- Illustration SVG -->
                                    <div class="w-28 h-28 mx-auto mb-6 relative">
                                        <div class="absolute inset-0 rounded-full opacity-20" style="background: linear-gradient(135deg, #2563eb, #7c3aed);"></div>
                                        <div class="absolute inset-3 rounded-full opacity-30" style="background: linear-gradient(135deg, #2563eb, #7c3aed);"></div>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <svg width="64" height="64" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="34" cy="34" r="28" stroke="#3b82f6" stroke-width="4" fill="none" opacity="0.3"/>
                                                <circle cx="34" cy="34" r="20" stroke="#2563eb" stroke-width="3" fill="#eff6ff" opacity="0.8"/>
                                                <line x1="55" y1="55" x2="72" y2="72" stroke="#2563eb" stroke-width="5" stroke-linecap="round" opacity="0.6"/>
                                                <path d="M24 34 Q34 24 44 34" stroke="#93c5fd" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                                                <circle cx="34" cy="38" r="2" fill="#93c5fd"/>
                                            </svg>
                                        </div>
                                    </div>

                                    <h3 class="text-xl font-bold text-slate-800 mb-2">Hotel Tidak Ditemukan</h3>
                                    <p class="text-slate-500 text-sm mb-6 max-w-sm mx-auto">
                                        Tidak ada hotel yang cocok dengan filter pencarian Anda di Medan. Coba ubah kata kunci atau hapus beberapa filter.
                                    </p>

                                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                                        <a href="{{ url('/search') }}"
                                           class="btn-gradient text-white font-bold px-6 py-2.5 rounded-xl shadow-md flex items-center gap-2">
                                            <i class="fa-solid fa-rotate-left"></i>
                                            Reset Semua Filter
                                        </a>
                                        <a href="{{ url('/') }}"
                                           class="border border-slate-200 text-slate-700 font-semibold px-6 py-2.5 rounded-xl hover:border-blue-300 hover:text-blue-600 transition flex items-center gap-2">
                                            <i class="fa-solid fa-house"></i>
                                            Kembali ke Beranda
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- ============================================================
         FOOTER
    ============================================================ -->
    <footer class="mt-16 border-t border-white/60" style="background: linear-gradient(135deg, #1e3a8a 0%, #312e81 100%);">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center bg-white/20">
                    <i class="fa-solid fa-hotel text-white text-sm"></i>
                </div>
                <span class="text-white font-black text-lg tracking-tight">Roomify</span>
                <span class="text-blue-300 text-xs font-medium">· Medan</span>
            </div>
            <p class="text-blue-200 text-xs text-center">
                © {{ date('Y') }} Roomify — Platform pencarian hotel berbasis Semantic Web di Medan, Indonesia.
            </p>
            <div class="flex items-center gap-4">
                <a href="{{ url('/') }}" class="text-blue-200 hover:text-white text-xs transition">Beranda</a>
                <a href="{{ url('/search') }}" class="text-blue-200 hover:text-white text-xs transition">Cari Hotel</a>
            </div>
        </div>
    </footer>

    <!-- ============================================================
         GOOGLE MAPS JAVASCRIPT API & LIVE NAVIGATION
    ============================================================ -->
    <script>
        // Hotel data from PHP
        const hotelData = @json($hotels ?? []);

        let map = null;
        let markers = [];
        let infoWindows = [];
        let activeInfoWindow = null;
        let mapVisible = true;
        let directionsService = null;
        let directionsRenderer = null;
        let currentUserCoords = @json(($userLat && $userLng) ? ['lat' => $userLat, 'lng' => $userLng] : null);

        // Custom map styles
        const mapStyles = [
            { featureType: "water",        elementType: "geometry",   stylers: [{ color: "#bfdbfe" }] },
            { featureType: "landscape",     elementType: "geometry",   stylers: [{ color: "#f8fafc" }] },
            { featureType: "road.highway",  elementType: "geometry",   stylers: [{ color: "#93c5fd" }, { lightness: 10 }] },
            { featureType: "road.arterial", elementType: "geometry",   stylers: [{ color: "#bfdbfe" }] },
            { featureType: "road.local",    elementType: "geometry",   stylers: [{ color: "#e2e8f0" }] },
            { featureType: "poi",           elementType: "geometry",   stylers: [{ color: "#dbeafe" }] },
            { featureType: "poi.park",      elementType: "geometry",   stylers: [{ color: "#bbf7d0" }] },
            { featureType: "transit",       elementType: "geometry",   stylers: [{ color: "#e0e7ff" }] },
            { elementType: "labels.text.stroke", stylers: [{ color: "#f8fafc" }] },
            { elementType: "labels.text.fill",   stylers: [{ color: "#475569" }] },
            { featureType: "administrative.locality", elementType: "labels.text.fill", stylers: [{ color: "#1e40af" }] },
            { featureType: "poi",                     elementType: "labels.text.fill", stylers: [{ color: "#64748b" }] },
            { featureType: "road",                    elementType: "labels.text.fill", stylers: [{ color: "#64748b" }] },
        ];

        function initMap() {
            map = new google.maps.Map(document.getElementById('search-map'), {
                center: { lat: 3.5952, lng: 98.6722 },
                zoom: 13,
                styles: mapStyles,
                mapTypeControl: false,
                streetViewControl: true,
                fullscreenControl: true,
                zoomControl: true,
                gestureHandling: 'cooperative',
                clickableIcons: false,
            });

            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                map: map,
                suppressMarkers: false,
                polylineOptions: {
                    strokeColor: '#4f46e5',
                    strokeWeight: 5,
                    strokeOpacity: 0.85
                }
            });

            // Add markers for hotels with coordinates
            hotelData.forEach((hotel, idx) => {
                const lat = parseFloat(hotel.latitude);
                const lng = parseFloat(hotel.longitude);
                if (!lat || !lng || isNaN(lat) || isNaN(lng)) return;

                const markerSvg = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="38" height="50" viewBox="0 0 38 50">
                        <defs>
                            <linearGradient id="mg${idx}" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#2563eb"/>
                                <stop offset="100%" style="stop-color:#4f46e5"/>
                            </linearGradient>
                            <filter id="shadow${idx}" x="-20%" y="-20%" width="140%" height="140%">
                                <feDropShadow dx="0" dy="2" stdDeviation="2" flood-color="rgba(37,99,235,0.4)"/>
                            </filter>
                        </defs>
                        <path d="M19 2C10.7 2 4 8.7 4 17c0 11.3 15 29 15 29s15-17.7 15-29c0-8.3-6.7-15-15-15z"
                              fill="url(#mg${idx})" filter="url(#shadow${idx})"/>
                        <circle cx="19" cy="17" r="8" fill="white" opacity="0.95"/>
                        <text x="19" y="21" text-anchor="middle" font-size="10" fill="#2563eb" font-weight="bold" font-family="Inter,sans-serif">🏨</text>
                    </svg>`;

                const marker = new google.maps.Marker({
                    position: { lat: lat, lng: lng },
                    map: map,
                    title: hotel.nama ?? 'Hotel',
                    icon: {
                        url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(markerSvg),
                        scaledSize: new google.maps.Size(38, 50),
                        anchor: new google.maps.Point(19, 50),
                    },
                    animation: google.maps.Animation.DROP,
                });

                const stars = parseInt(hotel.bintang ?? hotel.starRating ?? 0);
                const starStr = '★'.repeat(Math.min(stars, 5));
                const price = parseInt(hotel.harga ?? hotel.startPrice ?? 0);
                const priceStr = price > 0 ? 'Rp ' + price.toLocaleString('id-ID') : 'Hubungi kami';
                const safeName = (hotel.nama ?? 'Hotel').replace(/'/g, "\\'");

                const infoContent = `
                    <div style="font-family:Inter,sans-serif; min-width:220px; max-width:260px; padding:4px 0;">
                        <div style="font-size:13px; font-weight:800; color:#1e3a8a; margin-bottom:4px; line-height:1.3;">
                            ${hotel.nama ?? 'Hotel'}
                        </div>
                        ${stars > 0 ? `<div style="color:#f59e0b; font-size:12px; margin-bottom:4px;">${starStr}</div>` : ''}
                        <div style="font-size:11px; color:#64748b; margin-bottom:6px; display:flex; align-items:flex-start; gap:4px;">
                            <span style="color:#3b82f6;">📍</span>
                            ${hotel.alamat ?? 'Medan, Sumatera Utara'}
                        </div>
                        <div style="display:flex; align-items:center; justify-content:space-between; margin-top:8px; padding-top:8px; border-top:1px solid #e2e8f0;">
                            <span style="font-size:13px; font-weight:900; color:#2563eb;">${priceStr}</span>
                            <div style="display:flex; gap:4px;">
                                <button type="button" onclick="startNavigationToHotel(${lat}, ${lng}, '${safeName}')"
                                        style="background:#059669; color:white; font-size:10px; font-weight:700; padding:4px 8px; border-radius:8px; border:none; cursor:pointer;">
                                    🧭 Rute
                                </button>
                                <a href="/hotel/${hotel.id ?? ''}" style="background:linear-gradient(135deg,#2563eb,#4f46e5); color:white; font-size:10px; font-weight:700; padding:4px 10px; border-radius:8px; text-decoration:none;">Detail →</a>
                            </div>
                        </div>
                    </div>`;

                const infoWindow = new google.maps.InfoWindow({
                    content: infoContent,
                    pixelOffset: new google.maps.Size(0, -10),
                });

                marker.addListener('click', () => {
                    if (activeInfoWindow) activeInfoWindow.close();
                    infoWindow.open({ anchor: marker, map });
                    activeInfoWindow = infoWindow;
                    highlightCard(hotel.id ?? idx);
                });

                markers.push({ marker, hotelId: hotel.id ?? idx, lat, lng });
                infoWindows.push(infoWindow);
            });

            if (markers.length > 0) {
                const bounds = new google.maps.LatLngBounds();
                markers.forEach(m => bounds.extend(m.marker.getPosition()));
                if (markers.length === 1) {
                    map.setCenter(markers[0].marker.getPosition());
                    map.setZoom(15);
                } else {
                    map.fitBounds(bounds, { top: 40, right: 40, bottom: 40, left: 40 });
                }
            }

            // Place Landmark Pin if landmark is selected
            const landmarkInfo = @json($landmarkInfo ?? null);
            if (landmarkInfo && landmarkInfo.lat && landmarkInfo.lon) {
                const lmMarkerSvg = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="44" height="56" viewBox="0 0 44 56">
                        <defs>
                            <linearGradient id="lmGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#f59e0b"/>
                                <stop offset="100%" style="stop-color:#b45309"/>
                            </linearGradient>
                            <filter id="lmShadow" x="-20%" y="-20%" width="140%" height="140%">
                                <feDropShadow dx="0" dy="3" stdDeviation="3" flood-color="rgba(180,83,9,0.5)"/>
                            </filter>
                        </defs>
                        <path d="M22 2C12.06 2 4 10.06 4 20c0 13.5 18 34 18 34s18-20.5 18-34c0-9.94-8.06-18-18-18z"
                              fill="url(#lmGrad)" filter="url(#lmShadow)"/>
                        <circle cx="22" cy="20" r="9" fill="white"/>
                        <text x="22" y="24" text-anchor="middle" font-size="11">🏛️</text>
                    </svg>`;

                const lmPos = { lat: parseFloat(landmarkInfo.lat), lng: parseFloat(landmarkInfo.lon) };
                const lmMarker = new google.maps.Marker({
                    position: lmPos,
                    map: map,
                    title: landmarkInfo.name,
                    icon: {
                        url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(lmMarkerSvg),
                        scaledSize: new google.maps.Size(44, 56),
                        anchor: new google.maps.Point(22, 56),
                    },
                    zIndex: 9999
                });

                const lmInfoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="font-family:Inter,sans-serif;padding:6px;min-width:180px;">
                            <span style="font-size:10px;font-weight:800;color:#b45309;text-transform:uppercase;background:#fef3c7;padding:2px 6px;border-radius:4px;">Landmark Acuan</span>
                            <div style="font-size:13px;font-weight:800;color:#0f172a;margin-top:4px;">${landmarkInfo.name}</div>
                            <div style="font-size:11px;color:#64748b;margin-top:2px;">Pusat radius pencarian hotel terdekat</div>
                        </div>`,
                });
                lmMarker.addListener('click', () => {
                    if (activeInfoWindow) activeInfoWindow.close();
                    lmInfoWindow.open({ anchor: lmMarker, map });
                    activeInfoWindow = lmInfoWindow;
                });

                if (markers.length === 0) {
                    map.setCenter(lmPos);
                    map.setZoom(15);
                }
            }
        }

        let routeStepsVisible = false;
        function toggleRouteSteps() {
            const drawer = document.getElementById('route-steps-drawer');
            const label = document.getElementById('btn-steps-label');
            if (!drawer) return;
            routeStepsVisible = !routeStepsVisible;
            if (routeStepsVisible) {
                drawer.classList.remove('hidden');
                if (label) label.innerText = 'Tutup Belokan';
            } else {
                drawer.classList.add('hidden');
                if (label) label.innerText = 'Petunjuk Belokan';
            }
        }

        // Live Route Navigation to Selected Hotel
        function startNavigationToHotel(destLat, destLng, destName) {
            if (!map || !directionsService || !directionsRenderer) return;

            // Make map visible if hidden
            if (!mapVisible) toggleMap();
            document.getElementById('map-section')?.scrollIntoView({ behavior: 'smooth', block: 'center' });

            const origin = currentUserCoords || { lat: 3.5928, lng: 98.6748 }; // Default Stasiun Medan KM 0

            directionsService.route(
                {
                    origin: origin,
                    destination: { lat: parseFloat(destLat), lng: parseFloat(destLng) },
                    travelMode: google.maps.TravelMode.DRIVING
                },
                function(result, status) {
                    if (status === google.maps.DirectionsStatus.OK) {
                        directionsRenderer.setDirections(result);

                        const route = result.routes[0].legs[0];
                        const routeBar = document.getElementById('map-route-bar');
                        const destText = document.getElementById('map-route-destination');
                        const statsText = document.getElementById('map-route-stats');
                        const gmapsBtn = document.getElementById('btn-open-gmaps');

                        if (routeBar) routeBar.classList.remove('hidden');
                        if (destText) destText.innerText = destName;
                        if (statsText) statsText.innerText = `${route.distance.text} · ${route.duration.text}`;
                        if (gmapsBtn) {
                            gmapsBtn.href = `https://www.google.com/maps/dir/?api=1&destination=${destLat},${destLng}&travelmode=driving`;
                        }

                        // Populate Turn-by-Turn Steps
                        const stepsList = document.getElementById('route-steps-list');
                        const stepsCount = document.getElementById('route-steps-count');
                        if (stepsList && route.steps) {
                            if (stepsCount) stepsCount.innerText = `${route.steps.length} Belokan`;
                            stepsList.innerHTML = route.steps.map((st, i) => `
                                <div class="p-2 rounded-lg bg-slate-800/80 border border-slate-700/60 flex items-start gap-2.5">
                                    <span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 font-bold text-[10px] flex items-center justify-center shrink-0 border border-emerald-500/30">
                                        ${i + 1}
                                    </span>
                                    <div class="flex-1">
                                        <div class="text-white font-medium">${st.instructions}</div>
                                        <div class="text-[10px] text-slate-400 mt-0.5">${st.distance.text} · ${st.duration.text}</div>
                                    </div>
                                </div>
                            `).join('');
                        }
                    } else {
                        alert('Tidak dapat membuat rute navigasi: ' + status);
                    }
                }
            );
        }

        function clearNavigationRoute() {
            if (directionsRenderer) {
                directionsRenderer.setDirections({ routes: [] });
            }
            const routeBar = document.getElementById('map-route-bar');
            const drawer = document.getElementById('route-steps-drawer');
            if (routeBar) routeBar.classList.add('hidden');
            if (drawer) drawer.classList.add('hidden');
            routeStepsVisible = false;
            const label = document.getElementById('btn-steps-label');
            if (label) label.innerText = 'Petunjuk Belokan';
        }

        // Detect user live location & calculate distances to all hotel cards
        function detectUserLocation() {
            const btn = document.getElementById('btn-detect-loc');
            if (btn) btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mendeteksi...';

            if (!navigator.geolocation) {
                alert('Browser Anda tidak mendukung Geolocation.');
                if (btn) btn.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i> Lokasi Saya';
                return;
            }

            navigator.geolocation.getCurrentPosition(
                function(pos) {
                    const uLat = pos.coords.latitude;
                    const uLng = pos.coords.longitude;
                    currentUserCoords = { lat: uLat, lng: uLng };

                    if (btn) btn.innerHTML = '<i class="fa-solid fa-check text-emerald-400"></i> Lokasi Aktif';

                    // Update hidden inputs for future filters
                    const inLat = document.getElementById('filter-user-lat');
                    const inLng = document.getElementById('filter-user-lng');
                    if (inLat) inLat.value = uLat;
                    if (inLng) inLng.value = uLng;

                    const userMarkerSvg = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36">
                            <circle cx="18" cy="18" r="14" fill="#3b82f6" fill-opacity="0.25"/>
                            <circle cx="18" cy="18" r="8" fill="#2563eb" stroke="#ffffff" stroke-width="2.5"/>
                        </svg>`;

                    new google.maps.Marker({
                        position: { lat: uLat, lng: uLng },
                        map: map,
                        title: 'Posisi Anda Saat Ini',
                        icon: {
                            url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(userMarkerSvg),
                            scaledSize: new google.maps.Size(36, 36),
                            anchor: new google.maps.Point(18, 18),
                        },
                        zIndex: 10000
                    });

                    map.panTo({ lat: uLat, lng: uLng });
                    map.setZoom(14);

                    // Compute distance to each hotel card and dynamically reorder
                    const grid = document.getElementById('hotel-results-grid');
                    const cards = Array.from(document.querySelectorAll('.hotel-card'));

                    cards.forEach(card => {
                        const hLat = parseFloat(card.dataset.lat);
                        const hLng = parseFloat(card.dataset.lng);
                        if (hLat && hLng) {
                            const d = calcDistanceKm(uLat, uLng, hLat, hLng);
                            card.dataset.distKm = d;
                            const badge = card.querySelector('.dist-badge');
                            if (badge) {
                                const distStr = d < 1 ? Math.round(d * 1000) + ' m' : d.toFixed(1) + ' km';
                                badge.innerHTML = `<i class="fa-solid fa-location-crosshairs text-[10px]"></i> ${distStr} dari posisi Anda`;
                                badge.classList.remove('hidden');
                            }
                        }
                    });

                    // Sort cards in DOM from closest to farthest
                    cards.sort((a, b) => {
                        const dA = parseFloat(a.dataset.distKm) || 999999;
                        const dB = parseFloat(b.dataset.distKm) || 999999;
                        return dA - dB;
                    });
                    cards.forEach(c => grid.appendChild(c));
                },
                function(err) {
                    alert('Izin akses lokasi tidak diaktifkan pada browser Anda.');
                    if (btn) btn.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i> Lokasi Saya';
                },
                { enableHighAccuracy: true, timeout: 8000 }
            );
        }

        function calcDistanceKm(lat1, lon1, lat2, lon2) {
            const R = 6371;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLon/2) * Math.sin(dLon/2);
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        }

        function highlightCard(hotelId) {
            document.querySelectorAll('.hotel-card').forEach(card => {
                card.classList.remove('highlighted');
            });
            const card = document.getElementById('hotel-card-' + hotelId);
            if (card) {
                card.classList.add('highlighted');
                card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }

        function focusHotelOnMap(hotelId, lat, lng) {
            if (!lat || !lng || !map) return;
            const target = markers.find(m => String(m.hotelId) === String(hotelId));
            if (target) {
                map.panTo({ lat: parseFloat(lat), lng: parseFloat(lng) });
                map.setZoom(15);
                if (activeInfoWindow) activeInfoWindow.close();
                google.maps.event.trigger(target.marker, 'click');
            }
            highlightCard(hotelId);
        }

        function toggleMap() {
            const section = document.getElementById('map-section');
            const label   = document.getElementById('map-toggle-label');
            mapVisible = !mapVisible;
            if (mapVisible) {
                section.style.display = '';
                label.textContent = 'Sembunyikan Peta';
            } else {
                section.style.display = 'none';
                label.textContent = 'Tampilkan Peta';
            }
        }

        // Top Loader & Skeleton Triggers
        function triggerTopLoader() {
            const bar = document.getElementById('top-loader');
            if (bar) {
                bar.style.width = '40%';
                setTimeout(() => { bar.style.width = '80%'; }, 200);
            }
        }

        window.addEventListener('beforeunload', () => {
            triggerTopLoader();
        });

        document.getElementById('search-filter-form')?.addEventListener('submit', () => {
            triggerTopLoader();
            const skeleton = document.getElementById('skeleton-grid');
            const results = document.getElementById('hotel-results-grid');
            if (skeleton && results) {
                skeleton.classList.remove('hidden');
                results.classList.add('opacity-30');
            }
        });
    </script>

    <!-- Load Google Maps JS API -->
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap&libraries=places,geometry">
    </script>

    <!-- Roomify AI Chatbot Concierge Component -->
    <x-ai-chat-widget />

</body>
</html>
