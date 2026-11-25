<!doctype html>
<html lang="id" prefix="schema: https://schema.org/ roomify: https://roomify.example/ontology#">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>24 Landmark & Destinasi Ikonik Kota Medan – Roomify</title>

    <!-- Open Graph Metadata -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="24 Landmark & Destinasi Ikonik Kota Medan – Roomify">
    <meta property="og:description" content="Jelajahi seluruh landmark bersejarah, budaya, dan religi di Kota Medan serta temukan hotel terdekat berbasis Web Semantik.">
    <meta property="og:image" content="{{ asset('favicon.svg') }}">
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
        function getAutoTimeTheme() {
            try {
                const str = new Intl.DateTimeFormat('en-US', { timeZone: 'Asia/Jakarta', hour: 'numeric', hour12: false }).format(new Date());
                const h = parseInt(str, 10);
                const hour = isNaN(h) ? new Date().getHours() : h;
                return (hour >= 6 && hour < 18) ? 'light' : 'dark';
            } catch (e) {
                const hour = (new Date().getUTCHours() + 7) % 24;
                return (hour >= 6 && hour < 18) ? 'light' : 'dark';
            }
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
        body {
            background-color: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
        }
        html.dark body {
            background-color: #0b0f19;
            color: #f1f5f9;
        }
        .btn-gradient {
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            transition: all 0.2s;
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #4338ca 100%);
            box-shadow: 0 8px 20px rgba(37,99,235,0.35);
            transform: translateY(-1px);
        }
        .landmark-card {
            background: rgba(255,255,255,0.98);
            border: 1px solid rgba(226,232,240,0.9);
            transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        html.dark .landmark-card {
            background: #111827;
            border-color: #1f2937;
            color: #f1f5f9;
            box-shadow: 0 4px 20px rgba(0,0,0,0.35);
        }
        .landmark-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 32px rgba(37,99,235,0.12);
            border-color: #93c5fd;
        }
        html.dark .landmark-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 16px 32px rgba(0,0,0,0.6);
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col justify-between">

    <!-- Top Live System Clock & Unified Navbar -->
    <div class="sticky top-0 z-50">
        <x-live-clock-bar />
        <x-navbar active="landmarks" />
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full"
          typeof="schema:ItemList roomify:LandmarkDirectory">

        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-6">
            <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Beranda</a>
            <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i>
            <span class="font-bold text-slate-800 dark:text-slate-200">Landmark Kota Medan (24 Titik)</span>
        </nav>

        <!-- Hero Header -->
        <div class="rounded-3xl p-6 sm:p-10 mb-8 text-white relative overflow-hidden shadow-xl"
             style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%);">
            <div class="relative z-10 max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-blue-200 text-xs font-bold mb-3 border border-white/20 backdrop-blur-md">
                    <i class="fa-solid fa-landmark text-amber-300"></i>
                    <span>Direktori Web Semantik • 24 Titik Acuan</span>
                </div>
                <h1 class="text-2xl sm:text-4xl font-black tracking-tight leading-tight mb-3">
                    Landmark & Destinasi Ikonik Kota Medan
                </h1>
                <p class="text-xs sm:text-sm text-blue-100 leading-relaxed mb-6 max-w-2xl">
                    Temukan landmark bersejarah, religi, dan budaya Kota Medan yang dimodelkan dalam triplestore RDF. Setiap landmark terhubung dengan rekomendasi hotel terdekat menggunakan inferensi spasial semantik.
                </p>

                <!-- Search and Filter Bar inside Hero -->
                <form action="{{ route('landmark.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch gap-2.5 max-w-2xl">
                    <div class="relative flex-1">
                        <input type="text" name="q" value="{{ $q }}"
                               placeholder="Cari nama landmark, jalan, atau kawasan..."
                               class="w-full bg-white/95 dark:bg-slate-900/95 text-slate-900 dark:text-white placeholder-slate-400 rounded-xl px-4 py-3 pl-10 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 shadow-md">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    </div>

                    @if(!empty($selectedCategory))
                        <input type="hidden" name="category" value="{{ $selectedCategory }}">
                    @endif

                    <div class="flex items-center gap-2">
                        <select name="sort" onchange="this.form.submit()"
                                class="bg-white/95 dark:bg-slate-900/95 text-slate-800 dark:text-slate-200 rounded-xl px-3 py-3 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-400 shadow-md">
                            <option value="ulasan" {{ $sort === 'ulasan' ? 'selected' : '' }}>Ulasan Terbanyak</option>
                            <option value="rating" {{ $sort === 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                            <option value="nama"   {{ $sort === 'nama' ? 'selected' : '' }}>Nama A–Z</option>
                        </select>
                        <button type="submit"
                                class="px-5 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-black text-xs sm:text-sm shadow-md transition flex items-center justify-center gap-1.5 shrink-0">
                            <span>Cari</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Category Filter Rail -->
        <div class="mb-8 flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
            <a href="{{ route('landmark.index', array_merge(request()->except('category'), ['category' => ''])) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition {{ empty($selectedCategory) ? 'bg-blue-600 text-white shadow-md' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-blue-400' }}">
                Semua Kategori ({{ $totalCount }})
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('landmark.index', array_merge(request()->except('category'), ['category' => $cat])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition {{ $selectedCategory === $cat ? 'bg-blue-600 text-white shadow-md' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-blue-400' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>

        <!-- Section: Interactive All-Landmarks Map -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/90 dark:border-slate-800 p-6 shadow-sm mb-10">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-map-location-dot text-blue-600 dark:text-blue-400"></i>
                        Peta Sebaran 24 Landmark Kota Medan
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Klik pin emas pada peta untuk melihat informasi landmark dan hotel terdekat.
                    </p>
                </div>
                <div class="flex items-center gap-2 text-xs">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 font-bold border border-amber-200 dark:border-amber-800">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> 24 Landmark Terdata
                    </span>
                </div>
            </div>
            <div id="all-landmarks-map" class="w-full h-80 sm:h-96 rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-800 bg-slate-100 dark:bg-slate-800"></div>
        </div>

        <!-- Landmark Cards Grid -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">
                        Daftar Landmark ({{ count($landmarks) }} Ditemukan)
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                        Pilih landmark untuk melihat detail spasial, foto galeri, dan hotel sekitar.
                    </p>
                </div>
                @if(!empty($q) || !empty($selectedCategory))
                    <a href="{{ route('landmark.index') }}" class="text-xs font-bold text-blue-600 hover:underline flex items-center gap-1">
                        <i class="fa-solid fa-rotate-left"></i> Reset Filter
                    </a>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($landmarks as $lm)
                <article class="landmark-card rounded-2xl overflow-hidden flex flex-col group relative"
                         property="schema:itemListElement"
                         typeof="roomify:Landmark schema:TouristAttraction"
                         about="https://roomify.example/ontology#{{ $lm['id'] }}">
                    <!-- Photo -->
                    <div class="relative h-48 bg-slate-100 dark:bg-slate-800 overflow-hidden">
                        <img src="{{ asset($lm['foto']) }}"
                             alt="{{ $lm['nama'] }}"
                             property="schema:image"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                             onerror="this.src='{{ asset('images/placeholder.svg') }}'">

                        <!-- Category badge -->
                        <div class="absolute top-3 left-3">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold text-white bg-slate-900/85 backdrop-blur-md shadow-sm border border-white/20">
                                <i class="fa-solid fa-landmark text-amber-400 text-[10px]"></i>
                                <span property="roomify:category">{{ $lm['kat'] }}</span>
                            </span>
                        </div>

                        <!-- Rating Badge -->
                        @if(!empty($lm['rating']))
                        <div class="absolute top-3 right-3">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold text-white bg-emerald-600 shadow-sm">
                                <i class="fa-solid fa-star text-[10px] text-amber-300"></i> {{ number_format((float)$lm['rating'], 1) }}
                            </span>
                        </div>
                        @endif

                        <!-- Focus on map button on photo -->
                        <div class="absolute bottom-3 right-3">
                            <button type="button"
                                    onclick="focusLandmarkOnMap({{ $lm['lat'] }}, {{ $lm['lon'] }}, '{{ addslashes($lm['nama']) }}')"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-white/95 dark:bg-slate-900/90 hover:bg-blue-600 hover:text-white text-blue-700 dark:text-blue-300 shadow-md transition">
                                <i class="fa-solid fa-crosshairs text-[9px]"></i> Peta
                            </button>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span class="text-[11px] font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/60 px-2.5 py-0.5 rounded-lg border border-blue-100 dark:border-blue-900/60 flex items-center gap-1">
                                    <i class="fa-solid fa-hotel text-[10px]"></i> {{ $lm['hotel_count'] ?? 12 }} Hotel Sekitar
                                </span>
                                @if(!empty($lm['ulasan']))
                                <span class="text-[11px] text-slate-400">
                                    {{ number_format((int)$lm['ulasan'], 0, ',', '.') }} ulasan
                                </span>
                                @endif
                            </div>

                            <h3 class="font-extrabold text-slate-900 dark:text-white text-base leading-snug mb-1.5 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition" property="roomify:name schema:name">
                                <a href="{{ route('landmark.show', ['id' => $lm['id']]) }}">
                                    {{ $lm['nama'] }}
                                </a>
                            </h3>

                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 mb-3" property="roomify:address schema:address">
                                {{ $lm['alamat'] }}
                            </p>

                            <!-- Mini Geo Tag -->
                            <div class="text-[10px] font-mono text-slate-400 dark:text-slate-400 mb-3" property="schema:geo" typeof="schema:GeoCoordinates">
                                <span>Lat: <strong class="text-slate-600 dark:text-slate-300" property="schema:latitude">{{ $lm['lat'] }}</strong></span> ·
                                <span>Lon: <strong class="text-slate-600 dark:text-slate-300" property="schema:longitude">{{ $lm['lon'] }}</strong></span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between gap-2">
                            <a href="{{ route('search', ['landmark' => $lm['nama']]) }}"
                               class="text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 flex items-center gap-1">
                                <i class="fa-solid fa-magnifying-glass text-[10px]"></i> Hotel Sekitar
                            </a>
                            <a href="{{ route('landmark.show', ['id' => $lm['id']]) }}"
                               class="btn-gradient px-3.5 py-1.5 rounded-xl text-xs font-bold text-white shadow-sm flex items-center gap-1">
                                Detail <i class="fa-solid fa-chevron-right text-[9px]"></i>
                            </a>
                        </div>
                    </div>
                </article>
                @empty
                <div class="col-span-full py-16 text-center text-slate-400">
                    <i class="fa-solid fa-landmark text-4xl mb-3 text-slate-300"></i>
                    <p class="text-base font-bold text-slate-700 dark:text-slate-200">Tidak ada landmark yang sesuai.</p>
                    <p class="text-xs text-slate-500 mt-1">Coba gunakan kata kunci pencarian lain atau klik Reset Filter.</p>
                </div>
                @endforelse
            </div>
        </div>

    </main>

    <!-- Branded Footer -->
    <x-footer />

    <!-- AI Chatbot Widget -->
    <x-ai-chat-widget />

    <!-- Google Maps JS API -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initAllLandmarksMap" async defer></script>

    <script>
        const ALL_LANDMARKS = @json($landmarks);
        let allMap = null;
        let activeInfoWindow = null;

        function initAllLandmarksMap() {
            if (typeof google === 'undefined' || !google.maps) return;

            const center = { lat: 3.5952, lng: 98.6722 }; // Lapangan Merdeka (KM 0)
            allMap = new google.maps.Map(document.getElementById('all-landmarks-map'), {
                zoom: 13,
                center: center,
                mapTypeControl: false,
                fullscreenControl: true,
                streetViewControl: false
            });

            const bounds = new google.maps.LatLngBounds();

            ALL_LANDMARKS.forEach(lm => {
                const lat = parseFloat(lm.lat);
                const lng = parseFloat(lm.lon);
                if (isNaN(lat) || isNaN(lng)) return;

                const pos = { lat, lng };
                bounds.extend(pos);

                const lmSvg = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="38" height="48" viewBox="0 0 38 48">
                        <defs>
                            <linearGradient id="lmg_${lm.id}" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#f59e0b"/>
                                <stop offset="100%" style="stop-color:#b45309"/>
                            </linearGradient>
                        </defs>
                        <path d="M19 2C10.7 2 4 8.7 4 17c0 11.3 15 29 15 29s15-17.7 15-29c0-8.3-6.7-15-15-15z"
                              fill="url(#lmg_${lm.id})" stroke="#ffffff" stroke-width="2"/>
                        <circle cx="19" cy="17" r="7.5" fill="#ffffff"/>
                        <text x="19" y="20.5" text-anchor="middle" font-size="9">🏛️</text>
                    </svg>`;

                const marker = new google.maps.Marker({
                    position: pos,
                    map: allMap,
                    title: lm.nama,
                    icon: {
                        url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(lmSvg),
                        scaledSize: new google.maps.Size(38, 48),
                        anchor: new google.maps.Point(19, 48)
                    }
                });

                const info = new google.maps.InfoWindow({
                    content: `<div style="font-family:Inter,sans-serif;padding:6px;max-width:220px;">
                        <span style="font-size:10px;font-weight:800;color:#d97706;text-transform:uppercase;">🏛️ ${lm.kat}</span>
                        <h4 style="font-size:13px;font-weight:900;margin:2px 0;color:#0f172a;">${lm.nama}</h4>
                        <p style="font-size:11px;color:#64748b;margin:0 0 8px;">${lm.alamat ?? 'Kota Medan'}</p>
                        <div style="display:flex;gap:6px;">
                            <a href="/landmark/${lm.id}" style="background:#2563eb;color:white;font-size:10px;font-weight:700;padding:4px 8px;border-radius:6px;text-decoration:none;">Detail &rarr;</a>
                            <a href="/search?landmark=${encodeURIComponent(lm.nama)}" style="background:#059669;color:white;font-size:10px;font-weight:700;padding:4px 8px;border-radius:6px;text-decoration:none;">Hotel Sekitar</a>
                        </div>
                    </div>`
                });

                marker.addListener('click', () => {
                    if (activeInfoWindow) activeInfoWindow.close();
                    info.open(allMap, marker);
                    activeInfoWindow = info;
                });
            });

            if (ALL_LANDMARKS.length > 0) {
                allMap.fitBounds(bounds, { top: 30, right: 30, bottom: 30, left: 30 });
            }
        }

        function focusLandmarkOnMap(lat, lon, name) {
            if (!allMap) return;
            document.getElementById('all-landmarks-map')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            allMap.panTo({ lat: parseFloat(lat), lng: parseFloat(lon) });
            allMap.setZoom(16);
        }
    </script>
</body>
</html>
