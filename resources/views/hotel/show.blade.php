<!doctype html>
<html lang="id" prefix="schema: https://schema.org/ roomify: https://roomify.example/ontology# rdfs: http://www.w3.org/2000/01/rdf-schema#">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $hotel['nama'] ?? 'Detail Hotel' }} – Roomify</title>

    <!-- Open Graph Metadata -->
    <meta property="og:type" content="hotel">
    <meta property="og:title" content="{{ $hotel['nama'] ?? 'Hotel' }} – Roomify">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit($hotel['deskripsi'] ?? 'Pesan kamar nyaman di ' . ($hotel['nama'] ?? 'Hotel') . ' Medan dengan Roomify.', 150) }}">
    <meta property="og:image" content="{{ asset($hotel['foto'] ?? 'images/placeholder.svg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="Roomify Medan">

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
        function getAutoTimeTheme() {
            const hour = new Date().getHours();
            return (hour >= 6 && hour < 18) ? 'light' : 'dark';
        }
        function initTheme() {
            if (localStorage.getItem('theme_user_override') === 'true' && !localStorage.getItem('theme_mode')) {
                localStorage.removeItem('theme_user_override');
                localStorage.setItem('theme_mode', 'auto');
            }
            const mode = localStorage.getItem('theme_mode') || 'auto';
            const effectiveTheme = (mode === 'auto') ? getAutoTimeTheme() : mode;
            if (effectiveTheme === 'dark') {
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
                if (nextMode === 'dark') document.documentElement.classList.add('dark');
                else document.documentElement.classList.remove('dark');
            }
        }
        initTheme();
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
        .btn-gradient { background:linear-gradient(135deg,#1d4ed8,#2563eb); color:white; font-weight:700; border-radius:12px; transition:all .2s; }
        .btn-gradient:hover { background:linear-gradient(135deg,#1e40af,#1d4ed8); box-shadow:0 4px 14px rgba(37,99,235,.35); transform:translateY(-1px); }

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
<body class="bg-slate-50 antialiased min-h-screen" typeof="schema:Hotel roomify:Hotel" about="https://roomify.example/ontology#{{ $id }}">

<!-- ══════════════════════════════════════════════════════
     STICKY TOP BAR: LIVE CLOCK & NAVBAR
════════════════════════════════════════════════════════ -->
<div class="sticky top-0 z-50">
    <x-live-clock-bar />
    <x-navbar active="search" />
</div>

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
             class="w-full h-72 sm:h-96 md:h-[480px] object-cover transition-transform duration-500 group-hover:scale-[1.02]"
             onerror="this.src='{{ asset('images/placeholder.svg') }}'; this.onerror=null;">
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
                 class="flex-shrink-0 h-16 w-24 sm:h-20 sm:w-28 object-cover rounded-xl border-2 border-transparent cursor-pointer hover:border-indigo-500 transition-all {{ $gi === 0 ? 'border-indigo-500' : '' }}"
                 onerror="this.src='{{ asset('images/placeholder.svg') }}'; this.onerror=null;">
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
            @if(!empty($hotel['startPrice']) && (int)$hotel['startPrice'] > 0)
                <div class="flex items-baseline gap-1.5">
                    <span class="text-slate-400 text-sm">Harga mulai:</span>
                    <span class="text-3xl font-black text-indigo-600" property="roomify:startPrice" content="{{ $hotel['startPrice'] }}">
                        Rp {{ number_format($hotel['startPrice'], 0, ',', '.') }}
                    </span>
                    <span class="text-slate-400 text-xs font-medium">/ malam</span>
                </div>
                <span class="text-xs text-emerald-600 bg-emerald-50 border border-emerald-200 px-2 py-1 rounded-full font-semibold">
                    <span class="pulse-dot inline-block w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1"></span>Tersedia
                </span>
                @if(!empty($hotel['kamar']))
                <button onclick="scrollToBooking()" class="ml-auto btn-gradient text-sm px-5 py-2.5 shadow-md">
                    <i class="fa-solid fa-calendar-check mr-1.5"></i>Pesan Sekarang
                </button>
                @else
                <button disabled class="ml-auto bg-slate-200 text-slate-400 text-xs font-bold px-4 py-2.5 rounded-xl cursor-not-allowed">
                    Kamar Belum Tersedia
                </button>
                @endif
            @else
                <div>
                    <span class="text-slate-400 text-[10px] block uppercase font-semibold">Tarif Kamar</span>
                    <span class="text-base font-bold text-slate-700">Harga belum tersedia</span>
                </div>
                <span class="text-xs text-sky-700 bg-sky-50 border border-sky-200 px-2.5 py-1 rounded-full font-semibold">
                    Sumber OpenStreetMap
                </span>
                <button disabled class="ml-auto bg-slate-200 text-slate-400 text-xs font-bold px-4 py-2.5 rounded-xl cursor-not-allowed">
                    Pemesanan Online Belum Tersedia
                </button>
            @endif
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
                <div class="rounded-2xl p-6 sm:p-8 text-center border-2 border-dashed border-amber-200 dark:border-amber-800/60 bg-amber-50/60 dark:bg-amber-950/30">
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400 flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                    </div>
                    <h3 class="font-extrabold text-slate-800 dark:text-white text-base mb-1">Data Kamar Belum Tersedia</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-md mx-auto leading-relaxed">
                        Data kamar belum tersedia untuk reservasi online instan (sumber data OpenStreetMap / direktori hotel). Silakan hubungi langsung resepsionis properti untuk reservasi dan tarif terkini.
                    </p>
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
                <div class="mb-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center text-sm shadow-sm shrink-0">
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
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="toggleHotelRouteSteps()" class="px-3 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                <i class="fa-solid fa-diamond-turn-right"></i> <span id="hotel-steps-btn-label">Petunjuk Belokan</span>
                            </button>
                            <a id="btn-open-gmaps-nav" href="#" target="_blank" class="btn-gradient text-white text-xs font-extrabold px-3.5 py-2 rounded-xl text-center flex items-center justify-center gap-1.5 shrink-0 shadow">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> Buka di Google Maps
                            </a>
                        </div>
                    </div>

                    <!-- Turn-by-Turn Expandable Steps Drawer for Hotel Detail -->
                    <div id="hotel-steps-drawer" class="hidden mt-3 max-h-56 overflow-y-auto p-3.5 rounded-xl bg-slate-900 text-slate-200 text-xs border border-slate-800 space-y-2 custom-scrollbar">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-800 font-bold text-white text-xs">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-route text-emerald-400"></i>
                                <span>Petunjuk Belokan Navigasi In-App</span>
                            </span>
                            <span id="hotel-steps-count" class="text-[10px] text-slate-400">0 Belokan</span>
                        </div>
                        <div id="hotel-steps-list" class="space-y-1.5 text-[11px]"></div>
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

            <!-- ── LANDMARK EKSTERNAL (SPARQL DBpedia / Wikidata) ── -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                    <h2 class="section-title mb-0">
                        <i class="fa-solid fa-globe text-emerald-500"></i>
                        Landmark sekitar — sumber: {{ strtolower($hotel['external_source'] ?? 'dbpedia') }}
                    </h2>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <i class="fa-solid fa-network-wired text-xs"></i>
                        SPARQL Federated: {{ ($hotel['external_source'] ?? '') === 'wikidata' ? 'query.wikidata.org' : 'dbpedia.org/sparql' }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mb-4">
                    Data titik menarik di sekitar hotel ini diambil langsung secara federasi melalui endpoint SPARQL eksternal (<strong class="text-slate-700">{{ strtoupper($hotel['external_source'] ?? 'DBpedia') }}</strong>).
                </p>
                @if(!empty($hotel['external_landmarks']))
                <ul class="divide-y divide-slate-50">
                    @foreach($hotel['external_landmarks'] as $elm)
                    <li class="landmark-item py-2.5 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                                <i class="fa-solid fa-landmark-dome text-xs"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-800 truncate">{{ $elm['nama'] ?? 'Landmark' }}</p>
                                <p class="text-[11px] text-slate-400">Koordinat: {{ number_format((float)($elm['lat'] ?? 0), 4) }}, {{ number_format((float)($elm['lon'] ?? 0), 4) }}</p>
                            </div>
                        </div>
                        @if(!empty($elm['jarak']))
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full shrink-0">
                            {{ $elm['jarak'] }}
                        </span>
                        @endif
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="text-center py-6 text-slate-400">
                    <p class="text-xs">Data titik penting sekitar terhubung dengan basis pengetahuan DBpedia.</p>
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

                        @if(!empty($hotel['kamar']))
                        <!-- Big CTA Button to Booking Form -->
                        <a href="{{ route('booking.form', ['id' => $id]) }}"
                           class="btn-gradient w-full py-4 rounded-2xl text-white font-extrabold text-sm flex items-center justify-center gap-2 shadow-xl hover:shadow-2xl transition transform hover:-translate-y-0.5 no-underline">
                            <span>Lanjut ke Formulir Pemesanan</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                        @else
                        <div class="p-3.5 rounded-2xl bg-amber-50 dark:bg-amber-950/50 border border-amber-200 dark:border-amber-800/60 text-xs text-amber-800 dark:text-amber-300 font-semibold text-center mb-2">
                            <i class="fa-solid fa-circle-info mr-1 text-amber-600"></i> Data kamar belum tersedia untuk reservasi online
                        </div>
                        <button type="button" disabled
                                class="w-full py-3.5 rounded-2xl bg-slate-200 dark:bg-slate-700 text-slate-400 dark:text-slate-500 font-bold text-sm flex items-center justify-center gap-2 cursor-not-allowed">
                            <i class="fa-solid fa-lock text-xs"></i>
                            <span>Pemesanan Belum Tersedia</span>
                        </button>
                        @endif

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
     RDF METADATA MODAL (RDFa, JSON-LD, Turtle, SPARQL)
════════════════════════════════════════════════════════ -->
<div id="rdf-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-3xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-5 relative max-h-[90vh] flex flex-col">

        <!-- Header Modal -->
        <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-3">
            <div>
                <h3 class="font-black text-slate-900 dark:text-white text-lg flex items-center gap-2">
                    <i class="fa-solid fa-code text-blue-500"></i>
                    Metadata Semantik & RDF Triples
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Representasi ontologi formal properti {{ $hotel['nama'] ?? 'Hotel' }}</p>
            </div>
            <button onclick="document.getElementById('rdf-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-white font-bold text-2xl leading-none">&times;</button>
        </div>

        <!-- 4 Format Tabs -->
        <div class="flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-2 overflow-x-auto text-xs">
            <button onclick="switchMetaTab('jsonld')" id="tab-meta-jsonld" class="px-4 py-2 rounded-xl font-bold bg-blue-600 text-white transition">JSON-LD</button>
            <button onclick="switchMetaTab('turtle')" id="tab-meta-turtle" class="px-4 py-2 rounded-xl font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">Turtle (.ttl)</button>
            <button onclick="switchMetaTab('rdfa')" id="tab-meta-rdfa" class="px-4 py-2 rounded-xl font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">RDFa (HTML)</button>
            <button onclick="switchMetaTab('sparql')" id="tab-meta-sparql" class="px-4 py-2 rounded-xl font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">SPARQL Query</button>
        </div>

        <!-- Tab 1: JSON-LD -->
        <div id="meta-panel-jsonld" class="meta-panel flex-1 overflow-hidden flex flex-col">
            <div class="flex items-center justify-between text-xs text-slate-400 mb-2">
                <span>Schema.org + Roomify Ontology Linked Data</span>
                <div class="flex gap-2">
                    <button onclick="copySnippet('code-jsonld')" class="hover:text-blue-500"><i class="fa-regular fa-copy"></i> Salin</button>
                    <button onclick="downloadSnippet('code-jsonld', 'hotel-{{ $id }}.json', 'application/ld+json')" class="hover:text-blue-500"><i class="fa-solid fa-download"></i> Unduh</button>
                </div>
            </div>
            <pre id="code-jsonld" class="bg-slate-900 text-emerald-400 p-4 rounded-2xl text-xs overflow-auto font-mono flex-1 leading-relaxed border border-slate-800">{
  "@@context": {
    "roomify": "https://roomify.example/ontology#",
    "schema": "https://schema.org/",
    "rdfs": "http://www.w3.org/2000/01/rdf-schema#"
  },
  "@id": "https://roomify.example/ontology#{{ $id }}",
  "@type": ["schema:Hotel", "roomify:Hotel"],
  "schema:name": "{{ addslashes($hotel['nama'] ?? '') }}",
  "schema:address": "{{ addslashes($hotel['alamat'] ?? '') }}",
  "roomify:dataSource": "{{ $hotel['dataSource'] ?? 'google_maps' }}",
  @if(!empty($hotel['startPrice']))"roomify:startPrice": {{ $hotel['startPrice'] }},@endif
  @if(!empty($hotel['starRating']))"roomify:starRating": {{ $hotel['starRating'] }},@endif
  @if(!empty($hotel['rating']))"roomify:rating": {{ $hotel['rating'] }},@endif
  "schema:geo": {
    "@type": "schema:GeoCoordinates",
    "schema:latitude": {{ $hotel['latitude'] ?? 3.5952 }},
    "schema:longitude": {{ $hotel['longitude'] ?? 98.6722 }}
  },
  "schema:amenityFeature": [
    @foreach(array_slice($hotel['fasilitas'] ?? [], 0, 8) as $fi => $f)
    "{{ addslashes($f) }}"{{ $fi < count(array_slice($hotel['fasilitas'] ?? [], 0, 8)) - 1 ? ',' : '' }}
    @endforeach
  ]
}</pre>
        </div>

        <!-- Tab 2: Turtle -->
        <div id="meta-panel-turtle" class="meta-panel hidden flex-1 overflow-hidden flex flex-col">
            <div class="flex items-center justify-between text-xs text-slate-400 mb-2">
                <span>W3C Turtle Triples RDF</span>
                <div class="flex gap-2">
                    <button onclick="copySnippet('code-turtle')" class="hover:text-blue-500"><i class="fa-regular fa-copy"></i> Salin</button>
                    <button onclick="downloadSnippet('code-turtle', 'hotel-{{ $id }}.ttl', 'text/turtle')" class="hover:text-blue-500"><i class="fa-solid fa-download"></i> Unduh</button>
                </div>
            </div>
            <pre id="code-turtle" class="bg-slate-900 text-sky-300 p-4 rounded-2xl text-xs overflow-auto font-mono flex-1 leading-relaxed border border-slate-800">@prefix roomify: &lt;https://roomify.example/ontology#&gt; .
@prefix schema: &lt;https://schema.org/&gt; .
@prefix xsd: &lt;http://www.w3.org/2001/XMLSchema#&gt; .
@prefix rdfs: &lt;http://www.w3.org/2000/01/rdf-schema#&gt; .

roomify:{{ $id }} a roomify:Hotel, schema:Hotel ;
    roomify:name "{{ addslashes($hotel['nama'] ?? '') }}" ;
    roomify:address "{{ addslashes($hotel['alamat'] ?? '') }}" ;
    roomify:dataSource "{{ $hotel['dataSource'] ?? 'google_maps' }}" ;
    @if(!empty($hotel['startPrice']))roomify:startPrice {{ $hotel['startPrice'] }} ;@endif
    @if(!empty($hotel['starRating']))roomify:starRating {{ $hotel['starRating'] }} ;@endif
    @if(!empty($hotel['rating']))roomify:rating {{ $hotel['rating'] }} ;@endif
    roomify:latitude {{ $hotel['latitude'] ?? 3.5952 }} ;
    roomify:longitude {{ $hotel['longitude'] ?? 98.6722 }} .</pre>
        </div>

        <!-- Tab 3: RDFa -->
        <div id="meta-panel-rdfa" class="meta-panel hidden flex-1 overflow-hidden flex flex-col">
            <div class="flex items-center justify-between text-xs text-slate-400 mb-2">
                <span>Snippet HTML5 + RDFa Markup</span>
                <div class="flex gap-2">
                    <button onclick="copySnippet('code-rdfa')" class="hover:text-blue-500"><i class="fa-regular fa-copy"></i> Salin</button>
                    <button onclick="downloadSnippet('code-rdfa', 'hotel-{{ $id }}.html', 'text/html')" class="hover:text-blue-500"><i class="fa-solid fa-download"></i> Unduh</button>
                </div>
            </div>
            <pre id="code-rdfa" class="bg-slate-900 text-amber-300 p-4 rounded-2xl text-xs overflow-auto font-mono flex-1 leading-relaxed border border-slate-800">&lt;div typeof="roomify:Hotel schema:Hotel" about="https://roomify.example/ontology#{{ $id }}"&gt;
  &lt;h1 property="schema:name roomify:name"&gt;{{ $hotel['nama'] ?? '' }}&lt;/h1&gt;
  &lt;p property="schema:address roomify:address"&gt;{{ $hotel['alamat'] ?? '' }}&lt;/p&gt;
  &lt;span property="roomify:dataSource" content="{{ $hotel['dataSource'] ?? 'google_maps' }}"&gt;&lt;/span&gt;
  @if(!empty($hotel['startPrice']))
  &lt;span property="roomify:startPrice" content="{{ $hotel['startPrice'] }}"&gt;Rp {{ number_format($hotel['startPrice'], 0, ',', '.') }}&lt;/span&gt;
  @endif
  @if(!empty($hotel['starRating']))
  &lt;span property="roomify:starRating schema:starRating"&gt;{{ $hotel['starRating'] }}&lt;/span&gt;
  @endif
  &lt;div property="schema:geo" typeof="schema:GeoCoordinates"&gt;
    &lt;meta property="schema:latitude" content="{{ $hotel['latitude'] ?? 3.5952 }}" /&gt;
    &lt;meta property="schema:longitude" content="{{ $hotel['longitude'] ?? 98.6722 }}" /&gt;
  &lt;/div&gt;
&lt;/div&gt;</pre>
        </div>

        <!-- Tab 4: SPARQL -->
        <div id="meta-panel-sparql" class="meta-panel hidden flex-1 overflow-hidden flex flex-col">
            <div class="flex items-center justify-between text-xs text-slate-400 mb-2">
                <span>SPARQL Query yang Digunakan</span>
                <div class="flex gap-2">
                    <button onclick="copySnippet('code-sparql')" class="hover:text-blue-500"><i class="fa-regular fa-copy"></i> Salin</button>
                    <button onclick="downloadSnippet('code-sparql', 'hotel-{{ $id }}.sparql', 'application/sparql-query')" class="hover:text-blue-500"><i class="fa-solid fa-download"></i> Unduh</button>
                </div>
            </div>
            <pre id="code-sparql" class="bg-slate-900 text-indigo-300 p-4 rounded-2xl text-xs overflow-auto font-mono flex-1 leading-relaxed border border-slate-800">PREFIX roomify: &lt;https://roomify.example/ontology#&gt;

SELECT ?nama ?alamat ?deskripsi ?bintang ?rating ?jumlahReview ?harga ?lat ?lon ?foto ?dataSource WHERE {
  roomify:{{ $id }} a roomify:Hotel ;
    roomify:name ?nama ;
    roomify:address ?alamat .
  OPTIONAL { roomify:{{ $id }} roomify:dataSource ?dataSource }
  OPTIONAL { roomify:{{ $id }} roomify:startPrice ?harga }
  OPTIONAL { roomify:{{ $id }} roomify:description ?deskripsi }
  OPTIONAL { roomify:{{ $id }} roomify:starRating ?bintang }
  OPTIONAL { roomify:{{ $id }} roomify:rating ?rating }
  OPTIONAL { roomify:{{ $id }} roomify:reviewCount ?jumlahReview }
  OPTIONAL { roomify:{{ $id }} roomify:latitude ?lat }
  OPTIONAL { roomify:{{ $id }} roomify:longitude ?lon }
  OPTIONAL { roomify:{{ $id }} roomify:hasPhoto ?ph . ?ph roomify:photoUrl ?foto }
} LIMIT 1</pre>
        </div>

        <!-- Footer Modal -->
        <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-slate-800">
            <a href="{{ url('/semantik') }}" class="text-xs font-bold text-blue-600 hover:underline">
                Buka SPARQL Playground & Ontologi Lengkap &rarr;
            </a>
            <button onclick="document.getElementById('rdf-modal').classList.add('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition">
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

    let hotelRouteStepsVisible = false;
    function toggleHotelRouteSteps() {
        const drawer = document.getElementById('hotel-steps-drawer');
        const label = document.getElementById('hotel-steps-btn-label');
        if (!drawer) return;
        hotelRouteStepsVisible = !hotelRouteStepsVisible;
        if (hotelRouteStepsVisible) {
            drawer.classList.remove('hidden');
            if (label) label.innerText = 'Tutup Belokan';
        } else {
            drawer.classList.add('hidden');
            if (label) label.innerText = 'Petunjuk Belokan';
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
                if (window.hotelPolyline) {
                    window.hotelPolyline.setMap(null);
                    window.hotelPolyline = null;
                }
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

                // Populate In-App Turn Steps
                const stepsList = document.getElementById('hotel-steps-list');
                const stepsCount = document.getElementById('hotel-steps-count');
                if (stepsList && leg.steps) {
                    if (stepsCount) stepsCount.innerText = `${leg.steps.length} Belokan`;
                    stepsList.innerHTML = leg.steps.map((st, i) => `
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
                console.warn('Google Directions failed (' + status + '), switching to OpenStreetMap OSRM...');
                drawRouteWithOSRM(originCoords, originName, modeVal);
            }
        });
    }

    async function drawRouteWithOSRM(originCoords, originName, modeVal) {
        try {
            const profile = modeVal === 'WALKING' ? 'foot' : 'driving';
            const url = `https://router.project-osrm.org/route/v1/${profile}/${originCoords.lng},${originCoords.lat};${HOTEL_LNG},${HOTEL_LAT}?overview=full&geometries=geojson&steps=true`;
            const res = await fetch(url);
            if (!res.ok) throw new Error('OSRM failed');
            const data = await res.json();
            if (data.code === 'Ok' && data.routes && data.routes.length > 0) {
                const route = data.routes[0];
                const coords = route.geometry.coordinates.map(c => ({ lat: c[1], lng: c[0] }));
                const km = (route.distance / 1000).toFixed(1) + ' km';
                const mins = Math.max(1, Math.round(route.duration / 60)) + ' mnt';

                if (directionsRenderer) directionsRenderer.setDirections({ routes: [] });
                if (window.hotelPolyline) window.hotelPolyline.setMap(null);

                window.hotelPolyline = new google.maps.Polyline({
                    path: coords,
                    strokeColor: '#2563eb',
                    strokeWeight: 6,
                    strokeOpacity: 0.85,
                    map: map
                });

                const bounds = new google.maps.LatLngBounds();
                coords.forEach(pt => bounds.extend(pt));
                map.fitBounds(bounds, { top: 40, right: 40, bottom: 40, left: 40 });

                const banner = document.getElementById('route-result-banner');
                if (banner) banner.classList.remove('hidden');

                const summaryEl = document.getElementById('route-summary-text');
                if (summaryEl) {
                    summaryEl.innerHTML = `
                        Rute ${modeVal === 'WALKING' ? 'Jalan Kaki' : 'Berkendara'} (OpenStreetMap): Jarak <strong>${km}</strong> (Estimasi <strong>${mins}</strong>)
                    `;
                }

                const originEl = document.getElementById('route-origin-text');
                if (originEl) {
                    originEl.innerText = `Dari: ${originName} → Menuju ${HOTEL_NAME}`;
                }

                const gmapsBtn = document.getElementById('btn-open-gmaps-nav');
                if (gmapsBtn) {
                    gmapsBtn.href = `https://www.google.com/maps/dir/?api=1&origin=${originCoords.lat},${originCoords.lng}&destination=${HOTEL_LAT},${HOTEL_LNG}&travelmode=${modeVal.toLowerCase()}`;
                }

                const stepsList = document.getElementById('hotel-steps-list');
                const stepsCount = document.getElementById('hotel-steps-count');
                const steps = route.legs[0]?.steps || [];
                if (stepsList && steps.length > 0) {
                    if (stepsCount) stepsCount.innerText = `${steps.length} Belokan`;
                    stepsList.innerHTML = steps.map((st, i) => {
                        let inst = st.maneuver.type;
                        if (st.maneuver.modifier) inst += ' ' + st.maneuver.modifier;
                        if (st.name) inst += ' ke ' + st.name;
                        const distStr = st.distance < 1000 ? Math.round(st.distance) + ' m' : (st.distance/1000).toFixed(1) + ' km';
                        return `
                            <div class="p-2 rounded-lg bg-slate-800/80 border border-slate-700/60 flex items-start gap-2.5">
                                <span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 font-bold text-[10px] flex items-center justify-center shrink-0 border border-emerald-500/30">
                                    ${i + 1}
                                </span>
                                <div class="flex-1">
                                    <div class="text-white font-medium">${inst.charAt(0).toUpperCase() + inst.slice(1)}</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">${distStr}</div>
                                </div>
                            </div>
                        `;
                    }).join('');
                }
                return;
            }
        } catch (e) {
            console.warn('OSRM error, drawing direct line', e);
        }

        // Direct fallback
        if (directionsRenderer) directionsRenderer.setDirections({ routes: [] });
        if (window.hotelPolyline) window.hotelPolyline.setMap(null);
        window.hotelPolyline = new google.maps.Polyline({
            path: [originCoords, { lat: HOTEL_LAT, lng: HOTEL_LNG }],
            strokeColor: '#2563eb',
            strokeWeight: 6,
            strokeOpacity: 0.85,
            map: map
        });
        const bounds = new google.maps.LatLngBounds();
        bounds.extend(originCoords);
        bounds.extend({ lat: HOTEL_LAT, lng: HOTEL_LNG });
        map.fitBounds(bounds, { top: 40, right: 40, bottom: 40, left: 40 });

        const banner = document.getElementById('route-result-banner');
        if (banner) banner.classList.remove('hidden');
        const summaryEl = document.getElementById('route-summary-text');
        if (summaryEl) summaryEl.innerHTML = `Navigasi Jalur Langsung`;
        const originEl = document.getElementById('route-origin-text');
        if (originEl) originEl.innerText = `Dari: ${originName} → Menuju ${HOTEL_NAME}`;
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
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap&libraries=places,geometry">
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
        // Fetch SPARQL Eksternal via vanilla JS
        const hotelId = "{{ $id }}";

        if (hotelId) {
            fetch(`/api/landmark-eksternal/${encodeURIComponent(hotelId)}`)
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('ext-source-badge');
                    const endpointText = document.getElementById('ext-endpoint-text');
                    const listContainer = document.getElementById('ext-landmarks-list');
                    const loadingEl = document.getElementById('ext-loading');

                    if (loadingEl) loadingEl.style.display = 'none';

                    if (badge && data.sumber) {
                        badge.textContent = data.sumber;
                        if (data.sumber === 'local-fuseki') {
                            badge.className = 'px-3 py-1 rounded-md text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200';
                        } else {
                            badge.className = 'px-3 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200';
                        }
                    }

                    if (endpointText && data.endpoint) {
                        endpointText.textContent = data.endpoint;
                    }

                    if (listContainer && data.items && data.items.length > 0) {
                        listContainer.innerHTML = data.items.map(item => `
                            <div class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <h4 class="font-bold text-sm text-slate-900 dark:text-white truncate">${item.nama}</h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                        Koordinat: ${item.lat.toFixed(4)}, ${item.lon.toFixed(4)}
                                    </p>
                                </div>
                                <span class="text-xs font-bold px-2.5 py-1 rounded-md bg-indigo-50 dark:bg-indigo-950/70 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 shrink-0">
                                    ${item.jarak}
                                </span>
                            </div>
                        `).join('');
                    } else if (listContainer) {
                        listContainer.innerHTML = '<p class="text-xs text-slate-400 py-3 text-center">Tidak ada landmark eksternal dalam radius pencarian.</p>';
                    }
                })
                .catch(() => {
                    const loadingEl = document.getElementById('ext-loading');
                    if (loadingEl) {
                        loadingEl.textContent = 'Gagal memuat landmark eksternal.';
                    }
                });
        }
    });
</script>

<!-- ══════════════════════════════════════════════════════
     SECTION: LANDMARK SEKITAR (SPARQL EKSTERNAL)
════════════════════════════════════════════════════════ -->
<section id="section-landmark-eksternal" class="max-w-7xl mx-auto px-4 sm:px-6 mb-12">
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 mb-4 border-b border-slate-100 dark:border-slate-700">
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-network-wired text-indigo-600 dark:text-indigo-400"></i>
                    <span>Landmark Sekitar (SPARQL Eksternal)</span>
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Titik penting di sekitar hotel dikueri langsung via SPARQL federasi dari basis pengetahuan web semantik.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-500 dark:text-slate-400">Sumber:</span>
                <span id="ext-source-badge" class="px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                    Memuat...
                </span>
            </div>
        </div>

        <div class="text-[11px] font-mono text-slate-400 mb-4 truncate">
            Endpoint: <span id="ext-endpoint-text" class="text-slate-600 dark:text-slate-300">Menghubungkan...</span>
        </div>

        <div id="ext-loading" class="text-xs text-slate-400 py-4 text-center">
            <i class="fa-solid fa-spinner fa-spin mr-1.5 text-indigo-500"></i>
            Mengambil data landmark dari SPARQL endpoint...
        </div>

        <div id="ext-landmarks-list" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3"></div>
    </div>
</section>

<x-ai-chat-widget />

</body>
</html>
