<!doctype html>
<html lang="id" prefix="schema: https://schema.org/ roomify: http://roomify.example.org/ontology# rdfs: http://www.w3.org/2000/01/rdf-schema#">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $hotel['nama'] ?? 'Detail Hotel' }} – Roomify</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { 50:'#eef2ff', 100:'#e0e7ff', 200:'#c7d2fe', 500:'#6366f1', 600:'#4f46e5', 700:'#4338ca', 800:'#3730a3', 900:'#312e81' }
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
        body { scroll-behavior: smooth; }

        /* ── Navbar glass ── */
        .navbar-glass {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255,255,255,0.4);
        }

        /* ── Gradient hero ── */
        .hero-gradient { background: linear-gradient(135deg,#1e1b4b 0%,#312e81 30%,#4338ca 60%,#4f46e5 100%); }

        /* ── Lightbox ── */
        #lightbox { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.92); z-index:9999; align-items:center; justify-content:center; }
        #lightbox.active { display:flex; }
        #lightbox img { max-width:90vw; max-height:88vh; border-radius:12px; object-fit:contain; }

        /* ── Sticky booking card ── */
        .booking-card { position:sticky; top:88px; }

        /* ── Star filled/empty ── */
        .star-filled { color:#f59e0b; }
        .star-empty  { color:#d1d5db; }

        /* ── Read-more clamp ── */
        .clamped { overflow:hidden; display:-webkit-box; -webkit-box-orient:vertical; -webkit-line-clamp:4; }

        /* ── Thumbnail active ── */
        .thumb-active { ring:2px; ring-offset:2px; ring-color:#4f46e5; border:2px solid #4f46e5; }

        /* ── Facility icon pill ── */
        .fac-pill { display:flex; align-items:center; gap:8px; padding:10px 14px; background:white; border:1px solid #e0e7ff; border-radius:12px; font-size:0.875rem; font-weight:500; color:#3730a3; transition:all .2s; }
        .fac-pill:hover { background:#eef2ff; border-color:#c7d2fe; }

        /* ── Room card ── */
        .room-card { background:white; border:1px solid #e5e7eb; border-radius:16px; padding:20px; transition:all .2s; }
        .room-card:hover { border-color:#818cf8; box-shadow:0 8px 24px rgba(99,102,241,.12); transform:translateY(-2px); }

        /* ── Rating badge colors ── */
        .badge-excellent { background:#059669; }
        .badge-good { background:#2563eb; }
        .badge-average { background:#d97706; }

        /* ── Google Maps ── */
        #hotel-map { width:100%; height:400px; border-radius:16px; border:1px solid #e5e7eb; }

        /* ── Landmark list ── */
        .landmark-item { display:flex; align-items:center; gap:12px; padding:12px 0; border-bottom:1px solid #f1f5f9; }
        .landmark-item:last-child { border-bottom:none; }

        /* ── Review placeholder card ── */
        .review-card { background:white; border:1px solid #e5e7eb; border-radius:16px; padding:20px; }

        /* ── Gradient button ── */
        .btn-gradient { background:linear-gradient(135deg,#4338ca,#6366f1); color:white; font-weight:700; border-radius:12px; transition:all .2s; }
        .btn-gradient:hover { background:linear-gradient(135deg,#3730a3,#4f46e5); box-shadow:0 4px 20px rgba(99,102,241,.4); transform:translateY(-1px); }

        /* ── Section header underline ── */
        .section-title { font-size:1.25rem; font-weight:800; color:#1e1b4b; display:flex; align-items:center; gap:8px; margin-bottom:1.25rem; }
        .section-title::after { content:''; flex:1; height:2px; background:linear-gradient(90deg,#e0e7ff,transparent); border-radius:2px; }

        /* ── Pulsing dot ── */
        @keyframes pulse-dot { 0%,100%{opacity:1;} 50%{opacity:.4;} }
        .pulse-dot { animation:pulse-dot 2s infinite; }

        /* ── Street View toggle btn ── */
        .sv-btn { background:white; border:1px solid #e5e7eb; border-radius:8px; padding:8px 14px; font-size:.8rem; font-weight:600; color:#4338ca; cursor:pointer; transition:all .2s; }
        .sv-btn:hover { background:#eef2ff; border-color:#818cf8; }
        .sv-btn.active { background:#4338ca; color:white; border-color:#4338ca; }

        /* ── Share toast ── */
        #share-toast { position:fixed; bottom:24px; left:50%; transform:translateX(-50%); background:#1e1b4b; color:white; padding:10px 20px; border-radius:30px; font-size:.875rem; font-weight:600; opacity:0; transition:opacity .3s; pointer-events:none; z-index:9000; }
        #share-toast.show { opacity:1; }

        /* ── Scroll-to-top ── */
        #scroll-top { position:fixed; bottom:28px; right:24px; background:linear-gradient(135deg,#4338ca,#6366f1); color:white; border-radius:50%; width:44px; height:44px; display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 4px 16px rgba(99,102,241,.4); opacity:0; transition:opacity .3s; z-index:999; }
        #scroll-top.show { opacity:1; }

        /* ── Responsive tweaks ── */
        @media(max-width:1024px) { .booking-card { position:relative; top:auto; } }

        /* ── Dark Mode Cohesion ── */
        html.dark body { background: #0b0f19 !important; color: #f1f5f9 !important; }
        html.dark .navbar-glass { background: rgba(11, 15, 25, 0.9) !important; border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important; }
        html.dark .bg-white { background-color: #1e293b !important; color: #f1f5f9 !important; border-color: rgba(255, 255, 255, 0.1) !important; }
        html.dark .room-card { background: #1e293b !important; border-color: rgba(255, 255, 255, 0.1) !important; color: #f1f5f9 !important; }
        html.dark .review-card { background: #1e293b !important; border-color: rgba(255, 255, 255, 0.1) !important; color: #f1f5f9 !important; }
        html.dark .fac-pill { background: #1e293b !important; border-color: rgba(255, 255, 255, 0.1) !important; color: #a5b4fc !important; }
        html.dark .section-title { color: #f1f5f9 !important; }
        html.dark .text-slate-800, html.dark .text-slate-900, html.dark .text-gray-800, html.dark .text-gray-900 { color: #ffffff !important; }
        html.dark .text-slate-600, html.dark .text-slate-500, html.dark .text-gray-600, html.dark .text-gray-500 { color: #94a3b8 !important; }
        html.dark .border-slate-100, html.dark .border-slate-200 { border-color: rgba(255, 255, 255, 0.1) !important; }
    </style>
</head>
<body class="bg-slate-50 antialiased min-h-screen" typeof="schema:Hotel roomify:Hotel" about="http://roomify.example.org/ontology#{{ $id }}">

<!-- ══════════════════════════════════════════════════════
     STICKY NAVBAR
════════════════════════════════════════════════════════ -->
<nav class="navbar-glass sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="flex items-center gap-2.5 group no-underline">
            <div class="w-9 h-9 relative transform transition-transform group-hover:scale-105">
                <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full drop-shadow">
                    <defs>
                        <linearGradient id="hotel-nb-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#4F46E5"/>
                            <stop offset="50%" stop-color="#7C3AED"/>
                            <stop offset="100%" stop-color="#EC4899"/>
                        </linearGradient>
                        <linearGradient id="hotel-nb-gold" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#FCD34D"/>
                            <stop offset="100%" stop-color="#F59E0B"/>
                        </linearGradient>
                    </defs>
                    <rect width="48" height="48" rx="14" fill="url(#hotel-nb-grad)"/>
                    <rect x="0.75" y="0.75" width="46.5" height="46.5" rx="13.25" stroke="rgba(255,255,255,0.3)" stroke-width="1.5"/>
                    <path d="M12 36V22C12 15.3726 17.3726 10 24 10C30.6274 10 36 15.3726 36 22V36" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round"/>
                    <path d="M18 36V18H25.5C28 18 29.5 19.5 29.5 22C29.5 24.5 28 26 25.5 26H18M24 26L30 36" stroke="#FFFFFF" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M34 11L35.2 13.8L38 15L35.2 16.2L34 19L32.8 16.2L30 15L32.8 13.8L34 11Z" fill="url(#hotel-nb-gold)"/>
                    <circle cx="24" cy="31" r="1.5" fill="#FCD34D"/>
                </svg>
            </div>
            <div class="leading-tight">
                <span class="font-black text-xl bg-gradient-to-r from-indigo-700 via-purple-600 to-pink-500 bg-clip-text text-transparent tracking-tight">Roomify</span>
            </div>
        </a>

        <!-- Nav links -->
        <div class="hidden md:flex items-center gap-6">
            <a href="{{ url('/') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-700 transition">Beranda</a>
            <a href="{{ url('/search') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-700 transition">Cari Hotel</a>
            <a href="{{ url('/search') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-700 transition">Landmark Medan</a>
        </div>

        <!-- Right CTA -->
        <div class="flex items-center gap-3">
            <button onclick="toggleTheme()" type="button" class="p-2 rounded-xl text-slate-500 hover:text-slate-700 dark:text-slate-300 dark:hover:text-white bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 transition" aria-label="Toggle theme">
                <i class="fa-solid fa-moon hidden dark:inline text-amber-300"></i>
                <i class="fa-solid fa-sun inline dark:hidden text-amber-500"></i>
            </button>
            <a href="{{ url('/search') }}" class="hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold text-indigo-700 hover:text-indigo-900 transition">
                <i class="fa-solid fa-magnifying-glass text-xs"></i> Cari Hotel
            </a>
            <button onclick="scrollToBooking()" class="btn-gradient text-sm px-4 py-2 shadow-md hidden sm:block">
                <i class="fa-solid fa-calendar-check mr-1.5"></i>Pesan Sekarang
            </button>
            <!-- Mobile menu -->
            <button class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100" onclick="document.getElementById('mobile-nav').classList.toggle('hidden')">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>
    <!-- Mobile Nav -->
    <div id="mobile-nav" class="hidden md:hidden border-t border-slate-100 px-4 py-3 space-y-2">
        <a href="{{ url('/') }}" class="block text-sm font-medium text-slate-700 py-2">Beranda</a>
        <a href="{{ url('/search') }}" class="block text-sm font-medium text-slate-700 py-2">Cari Hotel</a>
    </div>
</nav>

<!-- Breadcrumb -->
<div class="bg-white border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-2.5 flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ url('/') }}" class="hover:text-indigo-700 transition">Beranda</a>
        <i class="fa-solid fa-chevron-right text-[9px]"></i>
        <a href="{{ url('/search') }}" class="hover:text-indigo-700 transition">Cari Hotel</a>
        <i class="fa-solid fa-chevron-right text-[9px]"></i>
        <span class="text-indigo-700 font-semibold truncate max-w-[200px]">{{ $hotel['nama'] ?? 'Detail Hotel' }}</span>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════
     PHOTO GALLERY
════════════════════════════════════════════════════════ -->
@if(!empty($hotel['galeri']))
<div class="max-w-7xl mx-auto px-4 sm:px-6 mt-5">
    <!-- Hero Image -->
    <div class="relative rounded-2xl overflow-hidden shadow-lg cursor-pointer group" onclick="openLightbox(0)">
        <img id="foto-utama"
             src="{{ asset($hotel['galeri'][0]) }}"
             property="schema:image"
             alt="{{ $hotel['nama'] ?? 'Hotel' }}"
             class="w-full h-72 sm:h-96 md:h-[480px] object-cover transition-transform duration-500 group-hover:scale-[1.02]">
        <!-- Gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent pointer-events-none"></div>
        <!-- Photo count badge -->
        <div class="absolute bottom-4 right-4 bg-black/60 backdrop-blur-sm text-white text-xs font-semibold px-3 py-1.5 rounded-full flex items-center gap-1.5">
            <i class="fa-solid fa-images"></i>
            {{ count($hotel['galeri']) }} Foto
        </div>
        <!-- Expand icon -->
        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
            <div class="bg-white/20 backdrop-blur-sm rounded-full p-4">
                <i class="fa-solid fa-expand text-white text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Thumbnail Strip -->
    @if(count($hotel['galeri']) > 1)
    <div class="flex gap-2.5 mt-3 overflow-x-auto pb-1 scrollbar-thin">
        @foreach($hotel['galeri'] as $gi => $g)
            <img src="{{ asset($g) }}" alt="Foto {{ $gi + 1 }}"
                 onclick="switchPhoto('{{ asset($g) }}', {{ $gi }})"
                 id="thumb-{{ $gi }}"
                 class="flex-shrink-0 h-16 w-24 sm:h-20 sm:w-28 object-cover rounded-xl border-2 border-transparent cursor-pointer hover:border-indigo-500 transition-all {{ $gi === 0 ? 'border-indigo-500' : '' }}">
        @endforeach
    </div>
    @endif
</div>
@else
<!-- Fallback hero gradient -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 mt-5">
    <div class="hero-gradient rounded-2xl h-72 sm:h-96 flex items-center justify-center shadow-lg">
        <div class="text-center text-white/70">
            <i class="fa-solid fa-hotel text-6xl mb-3"></i>
            <p class="font-semibold">Foto hotel belum tersedia</p>
        </div>
    </div>
</div>
@endif

<!-- Lightbox -->
<div id="lightbox" onclick="closeLightbox()">
    <button onclick="closeLightbox(event)" class="absolute top-4 right-4 text-white bg-white/10 hover:bg-white/20 rounded-full p-3 z-10">
        <i class="fa-solid fa-xmark text-xl"></i>
    </button>
    <button onclick="prevPhoto(event)" class="absolute left-4 top-1/2 -translate-y-1/2 text-white bg-white/10 hover:bg-white/20 rounded-full p-3 z-10">
        <i class="fa-solid fa-chevron-left text-xl"></i>
    </button>
    <button onclick="nextPhoto(event)" class="absolute right-16 top-1/2 -translate-y-1/2 text-white bg-white/10 hover:bg-white/20 rounded-full p-3 z-10">
        <i class="fa-solid fa-chevron-right text-xl"></i>
    </button>
    <img id="lightbox-img" src="" alt="Foto hotel" onclick="event.stopPropagation()">
</div>

<!-- ══════════════════════════════════════════════════════
     MAIN CONTENT
════════════════════════════════════════════════════════ -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 mt-6 pb-16">

    <!-- ── HOTEL HEADER ── -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
            <!-- Left: Name + meta -->
            <div class="flex-1 min-w-0">
                <!-- Star & badge row -->
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <!-- Star rating visual -->
                    <div class="flex items-center gap-0.5">
                        @php $stars = (int)($hotel['starRating'] ?? 0); @endphp
                        @for($s = 1; $s <= 5; $s++)
                            @if($s <= $stars)
                                <i class="fa-solid fa-star star-filled text-sm"></i>
                            @else
                                <i class="fa-solid fa-star star-empty text-sm"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="text-xs font-semibold text-amber-600 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full" property="roomify:starRating schema:starRating" content="{{ $hotel['starRating'] ?? 0 }}">
                        {{ $hotel['starRating'] ?? '-' }} Bintang
                    </span>
                    @if(!empty($hotel['rating']))
                    @php
                        $r = (float)($hotel['rating'] ?? 0);
                        $badgeClass = $r >= 4.5 ? 'badge-excellent' : ($r >= 3.5 ? 'badge-good' : 'badge-average');
                        $badgeLabel = $r >= 4.5 ? 'Luar Biasa' : ($r >= 3.5 ? 'Bagus' : 'Cukup');
                    @endphp
                    <span class="inline-flex items-center gap-1 text-white text-xs font-bold px-2.5 py-1 rounded-lg {{ $badgeClass }}">
                        <i class="fa-solid fa-thumbs-up text-[10px]"></i> {{ $badgeLabel }} {{ number_format($r, 1) }}
                    </span>
                    @endif
                    <span class="text-xs text-slate-400 font-medium">
                        <i class="fa-regular fa-comment-dots"></i>
                        {{ $hotel['jumlahReview'] ?? '—' }} ulasan
                    </span>
                </div>

                <!-- Hotel name -->
                <h1 property="rdfs:label schema:name" class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-tight mb-2">
                    {{ $hotel['nama'] ?? '' }}
                </h1>

                <!-- Address -->
                <p property="roomify:address schema:address" class="text-slate-500 text-sm flex items-center gap-1.5">
                    <i class="fa-solid fa-location-dot text-indigo-500"></i>
                    {{ $hotel['alamat'] ?? 'Medan, Sumatera Utara' }}
                </p>
            </div>

            <!-- Right: Action buttons -->
            <div class="flex flex-wrap items-center gap-2 flex-shrink-0">
                <!-- Share button -->
                <button onclick="shareHotel()" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-600 bg-slate-50 hover:bg-slate-100 border border-slate-200 px-3 py-2 rounded-xl transition">
                    <i class="fa-solid fa-share-nodes text-indigo-500"></i> Bagikan
                </button>

                <!-- RDF Metadata button -->
                <button onclick="document.getElementById('rdf-modal').classList.remove('hidden')"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 bg-slate-50 hover:bg-slate-100 border border-slate-200 px-3 py-2 rounded-xl transition">
                    <i class="fa-solid fa-code text-indigo-400 text-xs"></i> Lihat Metadata RDF
                </button>
            </div>
        </div>

        <!-- Price highlight bar -->
        <div class="mt-5 pt-4 border-t border-slate-100 flex flex-wrap items-baseline gap-3">
            <div class="flex items-baseline gap-1.5">
                <span class="text-slate-400 text-sm">Harga mulai:</span>
                <span class="text-3xl font-black text-indigo-600" property="roomify:startPrice" content="{{ $hotel['startPrice'] ?? 0 }}">
                    Rp {{ number_format($hotel['startPrice'] ?? 0, 0, ',', '.') }}
                </span>
                <span class="text-slate-400 text-xs font-medium">/ malam</span>
            </div>
            <span class="text-xs text-emerald-600 bg-emerald-50 border border-emerald-200 px-2 py-1 rounded-full font-semibold">
                <span class="pulse-dot inline-block w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1"></span>Tersedia
            </span>
            <button onclick="scrollToBooking()" class="ml-auto btn-gradient text-sm px-5 py-2.5 shadow-md">
                <i class="fa-solid fa-calendar-check mr-1.5"></i>Pesan Sekarang
            </button>
        </div>
    </div>

    <!-- ── TWO-COLUMN LAYOUT ── -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- ════════════════════════════════
             LEFT COLUMN (content)
        ════════════════════════════════ -->
        <div class="lg:col-span-2 space-y-6">

            <!-- ── DESCRIPTION ── -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h2 class="section-title"><i class="fa-solid fa-align-left text-indigo-500"></i>Deskripsi Hotel</h2>
                <div id="desc-text" class="clamped text-slate-600 leading-relaxed text-sm" property="roomify:description schema:description">
                    {{ $hotel['deskripsi'] ?? 'Deskripsi belum tersedia untuk hotel ini. Silakan hubungi kami untuk informasi lebih lanjut.' }}
                </div>
                @if(strlen($hotel['deskripsi'] ?? '') > 300)
                <button id="desc-toggle" onclick="toggleDesc()" class="mt-3 text-indigo-600 text-sm font-semibold hover:text-indigo-800 flex items-center gap-1 transition">
                    <span id="desc-toggle-txt">Baca selengkapnya</span>
                    <i class="fa-solid fa-chevron-down text-xs" id="desc-toggle-icon"></i>
                </button>
                @endif
            </div>

            <!-- ── FACILITIES ── -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h2 class="section-title"><i class="fa-solid fa-star text-indigo-500"></i>Fasilitas Utama</h2>
                @if(!empty($hotel['fasilitas']))
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($hotel['fasilitas'] as $fasilitas)
                    @php
                        $fl = strtolower($fasilitas);
                        $icon = 'fa-circle-check'; // default
                        if(str_contains($fl,'wifi') || str_contains($fl,'wi-fi') || str_contains($fl,'internet')) $icon = 'fa-wifi';
                        elseif(str_contains($fl,'kolam') || str_contains($fl,'renang') || str_contains($fl,'pool')) $icon = 'fa-water-ladder';
                        elseif(str_contains($fl,'ac') || str_contains($fl,'air condition') || str_contains($fl,'dingin')) $icon = 'fa-snowflake';
                        elseif(str_contains($fl,'sarapan') || str_contains($fl,'breakfast') || str_contains($fl,'makan')) $icon = 'fa-utensils';
                        elseif(str_contains($fl,'gym') || str_contains($fl,'fitness') || str_contains($fl,'olahraga')) $icon = 'fa-dumbbell';
                        elseif(str_contains($fl,'parkir') || str_contains($fl,'parking')) $icon = 'fa-square-parking';
                        elseif(str_contains($fl,'spa') || str_contains($fl,'pijat') || str_contains($fl,'massage')) $icon = 'fa-spa';
                        elseif(str_contains($fl,'restoran') || str_contains($fl,'restaurant') || str_contains($fl,'food')) $icon = 'fa-utensils';
                        elseif(str_contains($fl,'lift') || str_contains($fl,'elevator')) $icon = 'fa-elevator';
                        elseif(str_contains($fl,'laundry') || str_contains($fl,'cuci')) $icon = 'fa-shirt';
                        elseif(str_contains($fl,'tv') || str_contains($fl,'televisi')) $icon = 'fa-tv';
                        elseif(str_contains($fl,'minibar') || str_contains($fl,'bar') || str_contains($fl,'café') || str_contains($fl,'cafe')) $icon = 'fa-mug-hot';
                    @endphp
                    <span rel="roomify:hasFacility" class="fac-pill">
                        <i class="fa-solid {{ $icon }} text-indigo-500"></i>
                        <span property="rdfs:label">{{ $fasilitas }}</span>
                    </span>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 text-slate-400">
                    <i class="fa-solid fa-circle-xmark text-3xl mb-2"></i>
                    <p class="text-sm">Fasilitas tidak tercantum.</p>
                </div>
                @endif
            </div>

            <!-- ── ROOM TYPES ── -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h2 class="section-title"><i class="fa-solid fa-bed text-indigo-500"></i>Tipe Kamar Tersedia</h2>
                @if(!empty($hotel['kamar']))
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($hotel['kamar'] as $ki => $kamar)
                    <div rel="roomify:hasRoom" typeof="roomify:Room schema:HotelRoom" class="room-card group">
                        <!-- Room type icon -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center">
                                <i class="fa-solid fa-bed text-indigo-500"></i>
                            </div>
                            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2 py-1 rounded-full">Tersedia</span>
                        </div>

                        <h3 property="roomify:roomType" class="font-bold text-base text-slate-800 mb-1">{{ $kamar['tipe'] }}</h3>
                        <p class="text-xs text-slate-500 flex items-center gap-1 mb-4">
                            <i class="fa-solid fa-user-group text-indigo-400"></i>
                            Kapasitas: <span property="roomify:maxGuests" class="font-semibold text-slate-700">{{ $kamar['kapasitas'] }}</span> tamu
                        </p>

                        <!-- Amenity chips -->
                        <div class="flex flex-wrap gap-1.5 mb-4">
                            <span class="text-[11px] bg-slate-50 text-slate-500 border border-slate-200 px-2 py-0.5 rounded-full"><i class="fa-solid fa-snowflake text-blue-400 mr-0.5"></i>AC</span>
                            <span class="text-[11px] bg-slate-50 text-slate-500 border border-slate-200 px-2 py-0.5 rounded-full"><i class="fa-solid fa-wifi text-indigo-400 mr-0.5"></i>WiFi</span>
                            <span class="text-[11px] bg-slate-50 text-slate-500 border border-slate-200 px-2 py-0.5 rounded-full"><i class="fa-solid fa-tv text-slate-400 mr-0.5"></i>TV</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-indigo-600 font-black text-lg" property="roomify:pricePerNight">
                                    Rp {{ number_format($kamar['harga'], 0, ',', '.') }}
                                </span>
                                <span class="text-xs text-slate-400 font-medium">/malam</span>
                            </div>
                            <a href="{{ route('booking.form', ['id' => $id]) }}?room={{ urlencode($kamar['tipe']) }}&checkin={{ request('checkin','') }}&checkout={{ request('checkout','') }}"
                               class="btn-gradient text-xs px-4 py-2">
                                <i class="fa-solid fa-check mr-1"></i>Pilih Kamar
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 text-slate-400">
                    <i class="fa-solid fa-bed text-3xl mb-2"></i>
                    <p class="text-sm">Informasi kamar belum tersedia.</p>
                </div>
                @endif
            </div>

            <!-- ── GOOGLE MAPS ── -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="section-title mb-0"><i class="fa-solid fa-map-pin text-indigo-500"></i>Lokasi & Navigasi Hotel</h2>
                    <div class="flex gap-2">
                        <button id="sv-btn-map" class="sv-btn active" onclick="switchToMap()">
                            <i class="fa-solid fa-map mr-1"></i>Peta
                        </button>
                        <button id="sv-btn-street" class="sv-btn" onclick="switchToStreetView()">
                            <i class="fa-solid fa-street-view mr-1"></i>Street View
                        </button>
                    </div>
                </div>

                <!-- ── LIVE ROUTE & DIRECTIONS CONTROLS ── -->
                <div class="mb-4 p-4 rounded-2xl bg-gradient-to-r from-indigo-50/70 via-purple-50/50 to-slate-50 border border-indigo-100">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-600 text-white flex items-center justify-center text-sm shadow-md shrink-0">
                                <i class="fa-solid fa-route"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wide">Navigasi Rute Langsung</h4>
                                <p class="text-[11px] text-slate-500">Dapatkan rute menuju hotel ini dari posisi Anda sekarang</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button"
                                    id="btn-my-loc"
                                    onclick="calculateRouteFromCurrentLocation()"
                                    class="btn-gradient text-white text-xs font-bold px-3.5 py-2.5 rounded-xl flex items-center gap-2 shadow">
                                <i class="fa-solid fa-location-crosshairs text-sm"></i>
                                <span>Dari Posisi Saya</span>
                            </button>
                            <select id="travel-mode-select" onchange="onTravelModeChanged()" class="bg-white border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl px-2.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                <option value="DRIVING">🚗 Berkendara</option>
                                <option value="WALKING">🚶 Jalan Kaki</option>
                            </select>
                        </div>
                    </div>

                    <!-- Preset Starting Points -->
                    <div class="mt-3 pt-3 border-t border-slate-200/70 flex flex-wrap items-center gap-2 text-xs">
                        <span class="text-slate-400 text-[11px] font-semibold">Atau rute dari titik awal Medan:</span>
                        <button type="button" onclick="calculateRouteFromCoords(3.5928, 98.6748, 'Stasiun Kereta Api Medan (KM 0)')" class="px-2.5 py-1 rounded-lg bg-white hover:bg-indigo-50 border border-slate-200 hover:border-indigo-300 text-slate-700 font-semibold text-[11px] transition shadow-xs">
                            🚆 Stasiun Medan (KM 0)
                        </button>
                        <button type="button" onclick="calculateRouteFromCoords(3.5995, 98.8330, 'Bandara Internasional Kualanamu')" class="px-2.5 py-1 rounded-lg bg-white hover:bg-indigo-50 border border-slate-200 hover:border-indigo-300 text-slate-700 font-semibold text-[11px] transition shadow-xs">
                            ✈️ Bandara Kualanamu
                        </button>
                        <button type="button" onclick="calculateRouteFromCoords(3.5793, 98.6820, 'Istana Maimun')" class="px-2.5 py-1 rounded-lg bg-white hover:bg-indigo-50 border border-slate-200 hover:border-indigo-300 text-slate-700 font-semibold text-[11px] transition shadow-xs">
                            🏛️ Istana Maimun
                        </button>
                        <button type="button" onclick="calculateRouteFromCoords(3.5857, 98.6722, 'Sun Plaza Medan')" class="px-2.5 py-1 rounded-lg bg-white hover:bg-indigo-50 border border-slate-200 hover:border-indigo-300 text-slate-700 font-semibold text-[11px] transition shadow-xs">
                            🛍️ Sun Plaza
                        </button>
                    </div>

                    <!-- Route Result Display -->
                    <div id="route-result-banner" class="hidden mt-3 p-3.5 rounded-xl bg-white border border-indigo-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs shrink-0 shadow-sm">
                                <i class="fa-solid fa-diamond-turn-right"></i>
                            </span>
                            <div>
                                <p id="route-summary-text" class="text-xs font-black text-slate-900"></p>
                                <p id="route-origin-text" class="text-[11px] text-slate-500 mt-0.5"></p>
                            </div>
                        </div>
                        <a id="btn-open-gmaps-nav" href="#" target="_blank" class="btn-gradient text-white text-xs font-extrabold px-3.5 py-2 rounded-xl text-center flex items-center justify-center gap-1.5 shrink-0 shadow">
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> Buka di Google Maps
                        </a>
                    </div>
                </div>

                <div id="hotel-map"></div>
                <div id="hotel-sv" class="hidden" style="width:100%;height:400px;border-radius:16px;border:1px solid #e5e7eb;overflow:hidden;"></div>
                <div class="mt-3 flex items-center gap-2 text-xs text-slate-500">
                    <i class="fa-solid fa-location-dot text-indigo-500"></i>
                    <span property="roomify:address">{{ $hotel['alamat'] ?? 'Medan, Sumatera Utara' }}</span>
                    <a href="https://maps.google.com/?q={{ urlencode($hotel['alamat'] ?? 'Medan') }}" target="_blank"
                       class="ml-auto text-indigo-600 hover:text-indigo-800 font-semibold flex items-center gap-1 transition">
                        <i class="fa-solid fa-external-link text-[10px]"></i> Buka di Google Maps
                    </a>
                </div>
            </div>

            <!-- ── NEARBY LANDMARKS ── -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h2 class="section-title"><i class="fa-solid fa-landmark text-indigo-500"></i>Landmark Terdekat</h2>
                @if(!empty($hotel['landmarks']))
                <ul class="divide-y divide-slate-50">
                    @foreach($hotel['landmarks'] as $lk => $lm)
                    <li rel="roomify:nearLandmark" class="landmark-item">
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-map-pin text-indigo-500 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p property="rdfs:label" class="text-sm font-semibold text-slate-700 truncate">{{ $lm }}</p>
                            <p class="text-xs text-slate-400">Medan, Sumatera Utara</p>
                        </div>
                        <span class="text-xs text-indigo-500 font-semibold bg-indigo-50 px-2 py-1 rounded-full flex-shrink-0">
                            <i class="fa-solid fa-route mr-0.5"></i>Dekat
                        </span>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="text-center py-6 text-slate-400">
                    <i class="fa-solid fa-map-location-dot text-3xl mb-2"></i>
                    <p class="text-sm">Dekat pusat kota Medan</p>
                </div>
                @endif
            </div>

            <!-- ── REVIEWS PLACEHOLDER ── -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="section-title mb-0"><i class="fa-solid fa-star text-indigo-500"></i>Ulasan Tamu</h2>
                    @if(!empty($hotel['rating']))
                    <div class="flex items-center gap-2">
                        <span class="text-4xl font-black text-indigo-700">{{ number_format((float)($hotel['rating'] ?? 0), 1) }}</span>
                        <div>
                            <div class="flex gap-0.5 mb-0.5">
                                @for($i=1;$i<=5;$i++)
                                <i class="fa-solid fa-star {{ $i <= round((float)($hotel['rating'] ?? 0)) ? 'star-filled' : 'star-empty' }} text-xs"></i>
                                @endfor
                            </div>
                            <p class="text-xs text-slate-400">{{ $hotel['jumlahReview'] ?? '0' }} ulasan</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Rating breakdown bars -->
                <div class="space-y-2 mb-6">
                    @foreach(['Kebersihan' => 92, 'Lokasi' => 88, 'Layanan' => 90, 'Fasilitas' => 85, 'Nilai' => 87] as $aspect => $pct)
                    <div class="flex items-center gap-3 text-sm">
                        <span class="w-20 text-xs text-slate-500 font-medium flex-shrink-0">{{ $aspect }}</span>
                        <div class="flex-1 bg-slate-100 rounded-full h-1.5">
                            <div class="bg-gradient-to-r from-indigo-500 to-blue-400 h-1.5 rounded-full" style="width:{{ $pct }}%"></div>
                        </div>
                        <span class="text-xs font-semibold text-slate-700 w-7 text-right">{{ number_format($pct/20, 1) }}</span>
                    </div>
                    @endforeach
                </div>

                <!-- Sample review cards -->
                <div class="grid sm:grid-cols-2 gap-4">
                    @foreach([
                        ['name'=>'Andi S.', 'rating'=>5, 'date'=>'Oktober 2024', 'text'=>'Hotel yang luar biasa! Fasilitas lengkap, pelayanan ramah, dan lokasi sangat strategis di pusat kota Medan.'],
                        ['name'=>'Budi W.', 'rating'=>4, 'date'=>'September 2024', 'text'=>'Kamar bersih dan nyaman. Sarapan sangat enak. Akan kembali lagi untuk kunjungan berikutnya.'],
                    ] as $rev)
                    <div class="review-card">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-blue-400 flex items-center justify-center text-white font-bold text-sm">
                                {{ substr($rev['name'], 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $rev['name'] }}</p>
                                <p class="text-xs text-slate-400">{{ $rev['date'] }}</p>
                            </div>
                            <div class="ml-auto flex gap-0.5">
                                @for($i=1;$i<=5;$i++)
                                <i class="fa-solid fa-star text-[10px] {{ $i <= $rev['rating'] ? 'star-filled' : 'star-empty' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $rev['text'] }}</p>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4 text-center">
                    <button class="text-sm text-indigo-600 font-semibold hover:text-indigo-800 transition">
                        <i class="fa-solid fa-comments mr-1"></i>Lihat semua ulasan
                    </button>
                </div>
            </div>
        </div>

        <!-- ════════════════════════════════
             RIGHT COLUMN: Quick Reservation & Policy Highlights
        ════════════════════════════════ -->
        <div>
            <div id="booking-card" class="booking-card">
                <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-indigo-100 dark:border-slate-800 overflow-hidden">
                    <!-- Card header gradient -->
                    <div class="hero-gradient px-6 py-5 text-white">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-200">
                                <i class="fa-solid fa-sparkles text-amber-300 mr-1"></i> Penawaran Terbaik
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/80 text-white text-[10px] font-extrabold uppercase tracking-wide">
                                Kamar Tersedia
                            </span>
                        </div>
                        <div class="flex items-baseline gap-2 mt-1">
                            <span class="text-3xl font-black">Rp {{ number_format($hotel['startPrice'] ?? 0, 0, ',', '.') }}</span>
                            <span class="text-indigo-200 text-xs">/malam</span>
                        </div>
                        @if(!empty($hotel['rating']))
                        <div class="flex items-center gap-1.5 mt-2">
                            <i class="fa-solid fa-star text-amber-400 text-xs"></i>
                            <span class="text-white text-xs font-bold">{{ number_format((float)($hotel['rating'] ?? 0), 1) }}</span>
                            <span class="text-indigo-300 text-xs">· {{ $hotel['jumlahReview'] ?? 0 }} ulasan wisatawan</span>
                        </div>
                        @endif
                    </div>

                    <!-- Clean Quick Summary Body -->
                    <div class="p-6 space-y-5">
                        <!-- Real-time Check-in / Check-out badges -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/80 border border-slate-100 dark:border-slate-700">
                                <span class="text-[10px] text-slate-400 font-bold uppercase block tracking-wider">Jadwal Check-in</span>
                                <span class="text-sm font-extrabold text-slate-900 dark:text-white flex items-center gap-1.5 mt-0.5">
                                    <i class="fa-solid fa-clock text-indigo-500 text-xs"></i> 14.00 WIB
                                </span>
                            </div>
                            <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/80 border border-slate-100 dark:border-slate-700">
                                <span class="text-[10px] text-slate-400 font-bold uppercase block tracking-wider">Jadwal Check-out</span>
                                <span class="text-sm font-extrabold text-slate-900 dark:text-white flex items-center gap-1.5 mt-0.5">
                                    <i class="fa-solid fa-clock-rotate-left text-pink-500 text-xs"></i> 12.00 WIB
                                </span>
                            </div>
                        </div>

                        <!-- Roomify Policy & Guarantees -->
                        <div class="space-y-2.5 text-xs text-slate-600 dark:text-slate-300">
                            <div class="flex items-center gap-2.5">
                                <div class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-950/80 text-emerald-600 flex items-center justify-center text-[10px] shrink-0">
                                    <i class="fa-solid fa-id-card"></i>
                                </div>
                                <span>Wajib NIK KTP Asli (16 digit) saat check-in</span>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <div class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-950/80 text-emerald-600 flex items-center justify-center text-[10px] shrink-0">
                                    <i class="fa-solid fa-bolt"></i>
                                </div>
                                <span>Konfirmasi Instan & Kode Booking Resmi Terverifikasi</span>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <div class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-950/80 text-emerald-600 flex items-center justify-center text-[10px] shrink-0">
                                    <i class="fa-solid fa-shield-check"></i>
                                </div>
                                <span>Bebas Biaya Pemesanan Tambahan</span>
                            </div>
                        </div>

                        <!-- Big CTA Button to Booking Form -->
                        <a href="{{ route('booking.form', ['id' => $id]) }}"
                           class="btn-gradient w-full py-4 rounded-2xl text-white font-extrabold text-sm flex items-center justify-center gap-2 shadow-xl hover:shadow-2xl transition transform hover:-translate-y-0.5 no-underline">
                            <span>Lanjut ke Formulir Pemesanan</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>

                        <!-- AI Concierge Quick Trigger & Support -->
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-3">
                            <button type="button" onclick="toggleChatbot()" class="w-full py-2.5 px-3 rounded-xl bg-indigo-50 hover:bg-indigo-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-indigo-700 dark:text-indigo-300 text-xs font-bold border border-indigo-200/60 dark:border-slate-700 transition flex items-center justify-center gap-2">
                                <i class="fa-solid fa-robot text-sm text-indigo-600 dark:text-indigo-400"></i>
                                <span>Tanya AI Concierge tentang Hotel Ini</span>
                            </button>
                            <div class="flex items-center justify-center gap-4 text-[11px] text-slate-400">
                                <span class="flex items-center gap-1"><i class="fa-solid fa-qrcode text-indigo-500"></i> QRIS</span>
                                <span class="flex items-center gap-1"><i class="fa-solid fa-building-columns text-indigo-500"></i> Virtual Account</span>
                                <span class="flex items-center gap-1"><i class="fa-solid fa-lock text-emerald-500"></i> 100% Aman</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- ══════════════════════════════════════════════════════
     RDFa METADATA MODAL (preserved from original)
════════════════════════════════════════════════════════ -->
<div id="rdf-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-slate-200 space-y-4 relative">

        <!-- Header Modal -->
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
            <div>
                <h3 class="font-bold text-slate-900 text-lg">Metadata Ontologi Semantik</h3>
                <p class="text-xs text-slate-500">Pemetaan Entitas RDFa & Namespace (.ttl)</p>
            </div>
            <button onclick="document.getElementById('rdf-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 font-bold text-xl">&times;</button>
        </div>

        <!-- Tabel Namespace Semantik -->
        <div>
            <h4 class="text-xs font-bold uppercase text-slate-400 mb-2">Namespace yang Digunakan</h4>
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-xs font-mono space-y-1 text-slate-600">
                <p><span class="text-blue-600 font-bold">roomify:</span> http://roomify.example.org/ontology#</p>
                <p><span class="text-blue-600 font-bold">schema:</span> https://schema.org/</p>
                <p><span class="text-blue-600 font-bold">rdfs:</span> http://www.w3.org/2000/01/rdf-schema#</p>
            </div>
        </div>

        <!-- Raw Data Snippet (JSON-LD / Turtle Format) -->
        <div>
            <h4 class="text-xs font-bold uppercase text-slate-400 mb-2">Struktur Graf Entitas (JSON-LD)</h4>
            <pre class="bg-slate-900 text-emerald-400 p-4 rounded-xl text-xs overflow-x-auto font-mono max-h-60 leading-relaxed">
{
"@@context": {
    "roomify": "http://roomify.example.org/ontology#",
    "schema": "https://schema.org/",
    "rdfs": "http://www.w3.org/2000/01/rdf-schema#"
},
"@id": "http://roomify.example.org/ontology#{{ $id ?? 1 }}",
"@type": ["schema:Hotel", "roomify:Hotel"],
"rdfs:label": "{{ $hotel['nama'] ?? 'Hotel' }}",
"roomify:address": "{{ $hotel['alamat'] ?? '' }}",
"roomify:starRating": {{ $hotel['starRating'] ?? 5 }},
"roomify:startPrice": {{ $hotel['startPrice'] ?? 0 }}
}
            </pre>
        </div>

        <!-- Footer Modal -->
        <div class="text-right pt-2">
            <button onclick="document.getElementById('rdf-modal').classList.add('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs px-4 py-2 rounded-lg transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Share Toast -->
<div id="share-toast">Link disalin ke clipboard! 🎉</div>

<!-- Scroll to Top -->
<div id="scroll-top" onclick="window.scrollTo({top:0,behavior:'smooth'})">
    <i class="fa-solid fa-arrow-up text-sm"></i>
</div>

<!-- ══════════════════════════════════════════════════════
     GOOGLE MAPS API
════════════════════════════════════════════════════════ -->
<script>
    // Hotel coordinates from PHP with safe parsing
    const HOTEL_LAT = parseFloat("{{ $hotel['latitude'] ?? '3.5952' }}") || 3.5952;
    const HOTEL_LNG = parseFloat("{{ $hotel['longitude'] ?? '98.6722' }}") || 98.6722;
    const HOTEL_NAME = "{{ addslashes($hotel['nama'] ?? 'Hotel') }}";
    const HOTEL_ADDR = "{{ addslashes($hotel['alamat'] ?? 'Medan, Sumatera Utara') }}";
    const BASE_PRICE = {{ (int)($hotel['startPrice'] ?? 0) }};

    let map, marker, panorama, streetViewVisible = false;
    let directionsService, directionsRenderer;
    let currentUserCoords = null;
    let currentOriginTitle = 'Stasiun Medan (KM 0)';

    // Custom map styles (clean, modern)
    const MAP_STYLES = [
        { featureType:'water', elementType:'geometry', stylers:[{color:'#c9d8e8'}] },
        { featureType:'landscape', stylers:[{color:'#f5f5f0'}] },
        { featureType:'road', elementType:'geometry', stylers:[{color:'#ffffff'}] },
        { featureType:'road', elementType:'geometry.stroke', stylers:[{color:'#e0e0e0'}] },
        { featureType:'poi', elementType:'geometry', stylers:[{color:'#dfe8d9'}] },
        { featureType:'transit', stylers:[{visibility:'off'}] },
        { featureType:'administrative', elementType:'labels.text.fill', stylers:[{color:'#4338ca'}] }
    ];

    function initMap() {
        const mapEl = document.getElementById('hotel-map');
        if (!mapEl) return;

        // Map
        map = new google.maps.Map(mapEl, {
            center: { lat: HOTEL_LAT, lng: HOTEL_LNG },
            zoom: 15,
            styles: MAP_STYLES,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: true,
            zoomControl: true,
            zoomControlOptions: { position: google.maps.ControlPosition.RIGHT_CENTER }
        });

        // Initialize Directions
        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({
            map: map,
            suppressMarkers: false,
            polylineOptions: {
                strokeColor: '#4f46e5',
                strokeWeight: 6,
                strokeOpacity: 0.85
            }
        });

        // Custom marker SVG
        const svgMarker = {
            path: 'M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z',
            fillColor: '#4338ca',
            fillOpacity: 1,
            strokeColor: '#ffffff',
            strokeWeight: 2,
            scale: 2.2,
            anchor: new google.maps.Point(12, 22),
        };

        marker = new google.maps.Marker({
            position: { lat: HOTEL_LAT, lng: HOTEL_LNG },
            map: map,
            title: HOTEL_NAME,
            icon: svgMarker,
            animation: google.maps.Animation.DROP
        });

        // Info window
        const infoContent = `
            <div style="font-family:Inter,sans-serif;padding:6px;min-width:210px;">
                <div style="font-weight:800;font-size:14px;color:#1e1b4b;margin-bottom:4px;">${HOTEL_NAME}</div>
                <div style="font-size:12px;color:#64748b;margin-bottom:8px;display:flex;align-items:center;gap:4px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="#6366f1"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/></svg>
                    ${HOTEL_ADDR}
                </div>
                <div style="background:linear-gradient(135deg,#4338ca,#6366f1);color:white;text-align:center;padding:6px 12px;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;"
                     onclick="document.getElementById('booking-card').scrollIntoView({behavior:'smooth'})">
                    Pesan Sekarang
                </div>
            </div>`;

        const infoWindow = new google.maps.InfoWindow({ content: infoContent, maxWidth: 260 });
        infoWindow.open(map, marker);

        marker.addListener('click', () => infoWindow.open(map, marker));

        // Street View panorama
        const svEl = document.getElementById('hotel-sv');
        if (svEl) {
            panorama = new google.maps.StreetViewPanorama(
                svEl,
                {
                    position: { lat: HOTEL_LAT, lng: HOTEL_LNG },
                    pov: { heading: 34, pitch: 10 },
                    zoom: 1,
                    addressControl: false,
                    linksControl: true,
                    panControl: true,
                }
            );
        }

        // Auto-draw initial route from Medan City Center (Stasiun Medan KM 0)
        calculateRouteFromCoords(3.5928, 98.6748, 'Stasiun Kereta Api Medan (KM 0)');
    }

    function calculateRouteFromCurrentLocation() {
        const btn = document.getElementById('btn-my-loc');
        if (btn) btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mendeteksi GPS...';

        if (!navigator.geolocation) {
            fallbackToCenterPoint('Browser tidak mendukung GPS. Mengarahkan rute dari Stasiun Medan KM 0.');
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(position) {
                if (btn) btn.innerHTML = '<i class="fa-solid fa-check text-emerald-500"></i> Posisi Terdeteksi';
                currentUserCoords = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                currentOriginTitle = 'Lokasi Anda Saat Ini';
                drawRoute(currentUserCoords, currentOriginTitle);
            },
            function(error) {
                console.warn('Geolocation denied or unavailable:', error.message);
                fallbackToCenterPoint('GPS tidak diizinkan atau belum aktif. Menampilkan rute dari Stasiun Medan (Pusat Kota KM 0).');
            },
            { enableHighAccuracy: true, timeout: 8000, maximumAge: 60000 }
        );
    }

    function fallbackToCenterPoint(msg) {
        const btn = document.getElementById('btn-my-loc');
        if (btn) btn.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i> Dari Posisi Saya';
        currentUserCoords = { lat: 3.5928, lng: 98.6748 };
        currentOriginTitle = 'Stasiun Medan (KM 0)';
        drawRoute(currentUserCoords, currentOriginTitle);
        
        // Show subtle notification banner
        const originText = document.getElementById('route-origin-text');
        if (originText) {
            originText.innerText = msg;
        }
    }

    function calculateRouteFromCoords(lat, lng, name) {
        currentUserCoords = { lat: lat, lng: lng };
        currentOriginTitle = name;
        drawRoute(currentUserCoords, name);
    }

    function onTravelModeChanged() {
        if (currentUserCoords) {
            drawRoute(currentUserCoords, currentOriginTitle);
        }
    }

    function drawRoute(originCoords, originName) {
        if (!map || !directionsService) return;

        switchToMap();

        const modeSelect = document.getElementById('travel-mode-select');
        const modeVal = modeSelect ? modeSelect.value : 'DRIVING';
        const mode = modeVal === 'WALKING' 
            ? google.maps.TravelMode.WALKING 
            : google.maps.TravelMode.DRIVING;

        directionsService.route({
            origin: originCoords,
            destination: { lat: HOTEL_LAT, lng: HOTEL_LNG },
            travelMode: mode
        }, function(result, status) {
            if (status === google.maps.DirectionsStatus.OK) {
                directionsRenderer.setDirections(result);
                const leg = result.routes[0].legs[0];

                const banner = document.getElementById('route-result-banner');
                if (banner) banner.classList.remove('hidden');
                
                const summaryEl = document.getElementById('route-summary-text');
                if (summaryEl) {
                    summaryEl.innerHTML = `
                        Rute ${modeVal === 'WALKING' ? 'Jalan Kaki' : 'Berkendara'}: Jarak <strong>${leg.distance.text}</strong> (Estimasi <strong>${leg.duration.text}</strong>)
                    `;
                }

                const originEl = document.getElementById('route-origin-text');
                if (originEl) {
                    originEl.innerText = `Dari: ${originName} → Menuju ${HOTEL_NAME}`;
                }

                const gmapsUrl = `https://www.google.com/maps/dir/?api=1&origin=${originCoords.lat},${originCoords.lng}&destination=${HOTEL_LAT},${HOTEL_LNG}&travelmode=${modeVal.toLowerCase()}`;
                const gmapsBtn = document.getElementById('btn-open-gmaps-nav');
                if (gmapsBtn) gmapsBtn.href = gmapsUrl;
            } else {
                console.error('Directions request failed due to ' + status);
            }
        });
    }

    function switchToMap() {
        const mapEl = document.getElementById('hotel-map');
        const svEl = document.getElementById('hotel-sv');
        const btnMap = document.getElementById('sv-btn-map');
        const btnStreet = document.getElementById('sv-btn-street');

        if (mapEl) mapEl.classList.remove('hidden');
        if (svEl) svEl.classList.add('hidden');
        if (btnMap) btnMap.classList.add('active');
        if (btnStreet) btnStreet.classList.remove('active');
        streetViewVisible = false;
    }

    function switchToStreetView() {
        const mapEl = document.getElementById('hotel-map');
        const svEl = document.getElementById('hotel-sv');
        const btnMap = document.getElementById('sv-btn-map');
        const btnStreet = document.getElementById('sv-btn-street');

        if (mapEl) mapEl.classList.add('hidden');
        if (svEl) svEl.classList.remove('hidden');
        if (btnStreet) btnStreet.classList.add('active');
        if (btnMap) btnMap.classList.remove('active');
        streetViewVisible = true;
        if (panorama) {
            setTimeout(() => google.maps.event.trigger(panorama, 'resize'), 100);
        }
    }
</script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDoRCXpXYCd4Zo3K8LGs7FSswbr1qImHoQ&callback=initMap&libraries=places,geometry">
</script>

<!-- ══════════════════════════════════════════════════════
     PAGE SCRIPTS
════════════════════════════════════════════════════════ -->
<script>
    // ── Gallery / Lightbox ──
    const galleryImages = @json(array_map(fn($g) => asset($g), $hotel['galeri'] ?? []));
    let currentPhotoIdx = 0;

    function switchPhoto(src, idx) {
        document.getElementById('foto-utama').src = src;
        currentPhotoIdx = idx;
        // Update thumb borders
        document.querySelectorAll('[id^="thumb-"]').forEach((t,i) => {
            t.style.borderColor = i === idx ? '#4f46e5' : 'transparent';
        });
    }

    function openLightbox(idx) {
        currentPhotoIdx = idx;
        document.getElementById('lightbox-img').src = galleryImages[idx] || document.getElementById('foto-utama').src;
        document.getElementById('lightbox').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox(e) {
        if (!e || e.target === document.getElementById('lightbox') || e.target.closest('button')) {
            document.getElementById('lightbox').classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    function prevPhoto(e) {
        e.stopPropagation();
        currentPhotoIdx = (currentPhotoIdx - 1 + galleryImages.length) % galleryImages.length;
        document.getElementById('lightbox-img').src = galleryImages[currentPhotoIdx];
    }

    function nextPhoto(e) {
        e.stopPropagation();
        currentPhotoIdx = (currentPhotoIdx + 1) % galleryImages.length;
        document.getElementById('lightbox-img').src = galleryImages[currentPhotoIdx];
    }

    // Keyboard nav for lightbox
    document.addEventListener('keydown', e => {
        if (!document.getElementById('lightbox').classList.contains('active')) return;
        if (e.key === 'ArrowLeft') prevPhoto(e);
        if (e.key === 'ArrowRight') nextPhoto(e);
        if (e.key === 'Escape') closeLightbox(e);
    });

    // ── Description toggle ──
    function toggleDesc() {
        const el = document.getElementById('desc-text');
        const btn = document.getElementById('desc-toggle-txt');
        const icon = document.getElementById('desc-toggle-icon');
        if (el.classList.contains('clamped')) {
            el.classList.remove('clamped');
            btn.textContent = 'Sembunyikan';
            icon.style.transform = 'rotate(180deg)';
        } else {
            el.classList.add('clamped');
            btn.textContent = 'Baca selengkapnya';
            icon.style.transform = '';
        }
    }

    // ── Booking price calculator ──
    function calcNights() {
        const checkin = new Date(document.getElementById('checkin').value);
        const checkout = new Date(document.getElementById('checkout').value);
        if (checkout <= checkin) {
            const next = new Date(checkin);
            next.setDate(next.getDate() + 1);
            document.getElementById('checkout').value = next.toISOString().split('T')[0];
            return calcNights();
        }
        const nights = Math.ceil((checkout - checkin) / (1000*60*60*24));
        const subtotal = BASE_PRICE * nights;
        const tax = subtotal * 0.1;
        const total = subtotal + tax;

        document.getElementById('nights-count').textContent = nights;
        document.getElementById('subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('tax-amt').textContent   = 'Rp ' + Math.round(tax).toLocaleString('id-ID');
        document.getElementById('total-price').textContent = 'Rp ' + Math.round(total).toLocaleString('id-ID');
    }

    // ── Guest counter ──
    function adjustGuests(delta) {
        const inp = document.getElementById('guests');
        const val = parseInt(inp.value) + delta;
        if (val >= 1 && val <= 10) inp.value = val;
    }

    // ── Scroll to booking ──
    function scrollToBooking() {
        document.getElementById('booking-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // ── Share hotel ──
    function shareHotel() {
        const url = window.location.href;
        if (navigator.share) {
            navigator.share({ title: HOTEL_NAME, url });
        } else {
            navigator.clipboard.writeText(url).then(() => {
                const toast = document.getElementById('share-toast');
                toast.classList.add('show');
                setTimeout(() => toast.classList.remove('show'), 2500);
            });
        }
    }

    // ── Scroll-to-top button ──
    const scrollBtn = document.getElementById('scroll-top');
    window.addEventListener('scroll', () => {
        scrollBtn.classList.toggle('show', window.scrollY > 400);
    });

    // ── Init on load ──
    document.addEventListener('DOMContentLoaded', () => {
        // Pre-fill dates from query string if any
    });
</script>

<x-ai-chat-widget />

</body>
</html>
