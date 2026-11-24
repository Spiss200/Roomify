<!doctype html>
<html lang="id" prefix="schema: https://schema.org/ roomify: https://roomify.example/ontology#">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $landmark['nama'] }} – Landmark & Hotel Terdekat Medan | Roomify</title>

    <!-- Open Graph Metadata -->
    <meta property="og:type" content="place">
    <meta property="og:title" content="{{ $landmark['nama'] }} – Wisata Medan & Hotel Terdekat">
    <meta property="og:description" content="Temukan rekomendasi hotel terdekat di sekitar {{ $landmark['nama'] }}, {{ $landmark['alamat'] ?? 'Kota Medan' }}.">
    <meta property="og:image" content="{{ asset($landmark['foto'] ?? 'images/placeholder.svg') }}">
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
        .hotel-card {
            background: rgba(255,255,255,0.98);
            border: 1px solid rgba(226,232,240,0.9);
            transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        html.dark .hotel-card {
            background: #111827;
            border-color: #1f2937;
            color: #f1f5f9;
            box-shadow: 0 4px 20px rgba(0,0,0,0.35);
        }
        .hotel-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 32px rgba(37,99,235,0.12);
            border-color: #93c5fd;
        }
        html.dark .hotel-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 16px 32px rgba(0,0,0,0.6);
        }
    </style>
</head>
<body class="antialiased min-h-screen">

    <!-- Top Live System Clock & Unified Navbar -->
    <div class="sticky top-0 z-50">
        <x-live-clock-bar />
        <x-navbar active="landmarks" />
    </div>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
          typeof="roomify:Landmark schema:TouristAttraction"
          about="https://roomify.example/ontology#{{ $landmark['id'] }}">

        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-6">
            <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Beranda</a>
            <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i>
            <a href="{{ url('/search') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Pencarian Hotel</a>
            <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i>
            <span class="font-bold text-slate-800 dark:text-slate-200" property="schema:name">{{ $landmark['nama'] }}</span>
        </nav>

        <!-- Landmark Hero Card -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/90 dark:border-slate-800 shadow-sm overflow-hidden mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-0">
                <!-- Landmark Photo -->
                <div class="lg:col-span-5 relative min-h-[300px] lg:min-h-[400px] bg-slate-100 dark:bg-slate-800 overflow-hidden">
                    <img src="{{ asset($landmark['foto'] ?? 'images/placeholder.svg') }}"
                         alt="{{ $landmark['nama'] }}"
                         property="schema:image"
                         class="w-full h-full object-cover"
                         onerror="this.src='{{ asset('images/placeholder.svg') }}'">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent lg:hidden"></div>
                    <div class="absolute top-4 left-4 flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold text-white bg-slate-900/85 backdrop-blur-md shadow-md border border-white/20">
                            <i class="fa-solid fa-landmark text-amber-400"></i>
                            <span property="roomify:category">{{ $landmark['kat'] ?? 'Landmark' }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-full text-xs font-bold text-emerald-300 bg-emerald-950/85 backdrop-blur-md shadow-md border border-emerald-500/30">
                            <i class="fa-solid fa-circle-check text-[10px]"></i> Titik Wisata
                        </span>
                    </div>
                </div>

                <!-- Landmark Details -->
                <div class="lg:col-span-7 p-6 sm:p-8 flex flex-col justify-between">
                    <div>
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                            <span class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fa-solid fa-star-of-life text-[10px]"></i> Tengara Ikonik Kota Medan
                            </span>
                            @if(!empty($landmark['rating']))
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold text-emerald-700 bg-emerald-50 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                <i class="fa-solid fa-star text-amber-400"></i>
                                <span>{{ $landmark['rating'] }}</span>
                                @if(!empty($landmark['ulasan']))
                                <span class="text-slate-400 font-normal">({{ number_format((int)$landmark['ulasan'], 0, ',', '.') }} ulasan)</span>
                                @endif
                            </div>
                            @endif
                        </div>

                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 dark:text-white leading-tight mb-3" property="roomify:name schema:name">
                            {{ $landmark['nama'] }}
                        </h1>

                        <p class="text-sm text-slate-600 dark:text-slate-300 flex items-start gap-2 mb-4" property="roomify:address schema:address">
                            <i class="fa-solid fa-location-dot text-blue-600 dark:text-blue-400 mt-1 shrink-0"></i>
                            <span>{{ $landmark['alamat'] ?? 'Kota Medan, Sumatera Utara' }}</span>
                        </p>

                        <!-- Coordinates & Triplestore Meta -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 text-xs text-slate-600 dark:text-slate-300 mb-5"
                             property="schema:geo" typeof="schema:GeoCoordinates">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Latitude</span>
                                <span class="font-mono font-bold text-slate-800 dark:text-slate-200" property="schema:latitude">{{ $landmark['lat'] }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Longitude</span>
                                <span class="font-mono font-bold text-slate-800 dark:text-slate-200" property="schema:longitude">{{ $landmark['lon'] }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">URI Ontologi</span>
                                <code class="font-mono text-blue-600 dark:text-blue-400 font-bold">roomify:{{ $landmark['id'] }}</code>
                            </div>
                        </div>

                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                            Pusat daya tarik wisata dan landmark budaya Kota Medan. Temukan hotel dan akomodasi terdekat yang terhubung secara semantik dengan relasi <code class="text-xs bg-blue-50 dark:bg-slate-800 text-blue-600 dark:text-blue-400 px-1.5 py-0.5 rounded font-mono font-semibold">roomify:nearLandmark</code>.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <a href="{{ route('search', ['landmark' => $landmark['nama']]) }}"
                           class="btn-gradient inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white shadow-md transition">
                            <i class="fa-solid fa-hotel"></i>
                            Lihat Semua Hotel di Sekitar Landmark Ini
                        </a>
                        <button type="button"
                                onclick="openExternalGoogleMaps({{ $landmark['lat'] }}, {{ $landmark['lon'] }})"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs text-blue-500"></i>
                            Buka di Google Maps
                        </button>
                        <button type="button"
                                onclick="openExternalOpenStreetMap({{ $landmark['lat'] }}, {{ $landmark['lon'] }})"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                            <i class="fa-solid fa-map text-xs text-emerald-500"></i>
                            Buka di OpenStreetMap
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Interactive Map Focus with Dual-Engine Navigation -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/90 dark:border-slate-800 p-6 shadow-sm mb-10">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-map-location-dot text-blue-600 dark:text-blue-400"></i>
                        Peta Interaktif & Navigasi Hotel Sekitar
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Pin emas menandai <span class="font-bold text-slate-700 dark:text-slate-300">{{ $landmark['nama'] }}</span>, pin biru menandai hotel-hotel terdekat. Klik salah satu hotel untuk melihat rute belokan langsung.
                    </p>
                </div>
                <div class="flex items-center gap-2 text-xs">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 font-semibold border border-amber-200 dark:border-amber-800">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Landmark
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 font-semibold border border-blue-200 dark:border-blue-800">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span> Hotel
                    </span>
                </div>
            </div>

            <!-- Route Info Bar -->
            <div id="landmark-route-bar" class="hidden mb-3 px-4 py-2.5 rounded-xl bg-slate-900 text-white text-xs flex flex-wrap items-center justify-between gap-2 border border-slate-700 shadow-md">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span class="font-extrabold text-blue-300">Rute dari Landmark:</span>
                    <span id="landmark-route-destination" class="font-bold text-white truncate max-w-[200px]">Hotel Tujuan</span>
                    <span id="landmark-route-stats" class="px-2 py-0.5 rounded-full bg-slate-800 text-[11px] font-semibold text-emerald-300">0 km · 0 mnt</span>
                    <span id="landmark-route-engine-badge" class="px-2 py-0.5 rounded-full bg-indigo-900 text-[10px] font-bold text-indigo-200">OSRM Engine</span>
                </div>
                <div class="flex items-center gap-2">
                    <a id="btn-open-gmaps-lm" href="#" target="_blank" rel="noopener" class="px-2.5 py-1 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-bold transition">
                        <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> Google Maps
                    </a>
                    <button type="button" onclick="clearLandmarkRoute()" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white text-[10px] font-bold transition">
                        <i class="fa-solid fa-xmark mr-1"></i> Tutup Rute
                    </button>
                </div>
            </div>

            <!-- Map Container -->
            <div id="landmark-map" class="w-full h-80 sm:h-96 rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-800 bg-slate-100 dark:bg-slate-800"></div>
        </div>

        <!-- Section: Nearest Hotels Grid -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">
                        12 Rekomendasi Hotel Terdekat
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                        Diurutkan secara akurat berdasarkan jarak terdekat dari {{ $landmark['nama'] }}.
                    </p>
                </div>
                <a href="{{ route('search', ['landmark' => $landmark['nama']]) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 flex items-center gap-1">
                    Lihat Semua <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @forelse($nearestHotels as $h)
                <article class="hotel-card rounded-2xl overflow-hidden flex flex-col group relative"
                         typeof="roomify:Hotel schema:Hotel"
                         about="https://roomify.example/ontology#{{ $h['id'] }}">
                    <!-- Photo -->
                    <div class="relative h-44 bg-slate-100 dark:bg-slate-800 overflow-hidden">
                        <img src="{{ asset($h['foto']) }}"
                             alt="{{ $h['nama'] }}"
                             property="schema:image"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                             onerror="this.src='{{ asset('images/placeholder.svg') }}'">

                        <!-- Badges -->
                        <div class="absolute top-2.5 left-2.5 flex items-center gap-1">
                            @if(!empty($h['bintang']))
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold text-white bg-amber-500 shadow-sm">
                                <i class="fa-solid fa-star text-[9px]"></i> {{ $h['bintang'] }}
                            </span>
                            @endif
                        </div>

                        @if(!empty($h['rating']))
                        <div class="absolute top-2.5 right-2.5">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold text-white bg-emerald-600 shadow-sm">
                                <i class="fa-solid fa-star text-[9px] text-amber-300"></i> {{ number_format((float)$h['rating'], 1) }}
                            </span>
                        </div>
                        @endif

                        <!-- Quick Route Button on card -->
                        @if(!empty($h['latitude']) && !empty($h['longitude']))
                        <div class="absolute bottom-2.5 right-2.5">
                            <button type="button"
                                    onclick="startRouteFromLandmark({{ $h['latitude'] }}, {{ $h['longitude'] }}, '{{ addslashes($h['nama']) }}')"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-white/95 dark:bg-slate-900/90 hover:bg-emerald-600 hover:text-white text-emerald-700 dark:text-emerald-400 shadow-md transition">
                                <i class="fa-solid fa-diamond-turn-right text-emerald-500 text-[9px]"></i> Rute
                            </button>
                        </div>
                        @endif
                    </div>

                    <!-- Body -->
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-2">
                                @if(($h['dataSource'] ?? '') === 'osm' || empty($h['harga']))
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-sky-50 text-sky-700 border border-sky-200 dark:bg-sky-950/60 dark:text-sky-300 dark:border-sky-800">OSM</span>
                                @else
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800">Terverifikasi</span>
                                @endif

                                @if(!empty($h['jarak']))
                                <span class="text-[11px] font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/60 px-2 py-0.5 rounded-lg border border-blue-100 dark:border-blue-900/60 flex items-center gap-1">
                                    <i class="fa-solid fa-location-dot text-[9px]"></i> {{ $h['jarak'] }}
                                </span>
                                @endif
                            </div>

                            <h3 class="font-bold text-slate-900 dark:text-white text-sm leading-snug mb-1 line-clamp-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition" property="schema:name">
                                <a href="{{ route('hotel.detail', ['id' => $h['id']]) }}">
                                    {{ $h['nama'] }}
                                </a>
                            </h3>

                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1 mb-3" property="schema:address">
                                {{ $h['alamat'] }}
                            </p>
                        </div>

                        <!-- Price & CTA -->
                        <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between gap-2">
                            <div>
                                @if(!empty($h['harga']))
                                    <span class="text-[10px] text-slate-400 uppercase font-semibold block">Mulai</span>
                                    <span class="text-sm sm:text-base font-extrabold text-blue-600 dark:text-blue-400" property="roomify:startPrice">
                                        Rp {{ number_format((int)$h['harga'], 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-[10px] text-slate-400 uppercase font-semibold block">Tarif</span>
                                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Hubungi Hotel</span>
                                @endif
                            </div>
                            <a href="{{ route('hotel.detail', ['id' => $h['id']]) }}"
                               class="btn-gradient px-3 py-1.5 rounded-xl text-xs font-bold text-white shadow-sm flex items-center gap-1">
                                Detail <i class="fa-solid fa-chevron-right text-[9px]"></i>
                            </a>
                        </div>
                    </div>
                </article>
                @empty
                <div class="col-span-full py-12 text-center text-slate-400">
                    <p class="text-sm">Tidak ada hotel ditemukan di sekitar landmark ini.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Section: Other Landmarks in Medan -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 border border-slate-200/90 dark:border-slate-800 shadow-sm">
            <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="fa-solid fa-compass text-blue-600 dark:text-blue-400"></i>
                Jelajahi Landmark Medan Lainnya (25 Titik Lokasi)
            </h2>
            <div class="flex flex-wrap gap-2">
                @foreach($allLandmarks as $alm)
                    <a href="{{ route('landmark.show', ['id' => $alm['id']]) }}"
                       class="px-3 py-1.5 rounded-xl text-xs font-medium border transition {{ $alm['id'] === $landmark['id'] ? 'bg-blue-600 text-white border-blue-600 font-bold shadow-sm' : 'bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-blue-400 hover:text-blue-600 dark:hover:text-blue-400' }}">
                        {{ $alm['name'] }}
                    </a>
                @endforeach
            </div>
        </div>

    </main>

    <!-- Branded Footer -->
    <footer class="mt-16 border-t border-slate-200 dark:border-slate-800 py-10" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e1b4b 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <div class="flex items-center justify-center gap-2 mb-3">
                <span class="w-7 h-7 rounded-lg bg-blue-600 text-white flex items-center justify-center font-black text-sm">R</span>
                <span class="font-black text-lg tracking-tight">Roomify</span>
            </div>
            <p class="text-xs text-blue-200 max-w-md mx-auto leading-relaxed">
                Platform Pencarian & Navigasi Hotel Berbasis Web Semantik (Ontologi RDF, SPARQL Triplestore & Google Maps / OpenStreetMap) di Kota Medan.
            </p>
            <div class="mt-6 pt-4 border-t border-white/10 text-[11px] text-blue-300">
                &copy; 2026 Roomify Medan. Seluruh hak cipta dilindungi.
            </div>
        </div>
    </footer>

    <!-- AI Chatbot Widget -->
    <x-ai-chat-widget />

    <!-- Google Maps JS API -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initLandmarkMap" async defer></script>

    <script>
        const LANDMARK = {
            id: @json($landmark['id']),
            name: @json($landmark['nama']),
            lat: {{ (float)($landmark['lat'] ?? 3.5952) }},
            lon: {{ (float)($landmark['lon'] ?? 98.6722) }}
        };

        const HOTELS = @json($nearestHotels);
        let landmarkMap = null;
        let activeLmPolyline = null;
        let directionsService = null;
        let directionsRenderer = null;

        function initLandmarkMap() {
            if (typeof google === 'undefined' || !google.maps) return;

            const center = { lat: LANDMARK.lat, lng: LANDMARK.lon };
            landmarkMap = new google.maps.Map(document.getElementById('landmark-map'), {
                zoom: 14,
                center: center,
                mapTypeControl: false,
                fullscreenControl: true,
                streetViewControl: false
            });

            try {
                directionsService = new google.maps.DirectionsService();
                directionsRenderer = new google.maps.DirectionsRenderer({
                    map: landmarkMap,
                    suppressMarkers: false,
                    polylineOptions: { strokeColor: '#2563eb', strokeWeight: 5 }
                });
            } catch (e) {
                console.warn('Google directions not available, will use OpenStreetMap OSRM fallback', e);
            }

            // Distinctive Golden Landmark Marker SVG
            const lmSvg = `
                <svg xmlns="http://www.w3.org/2000/svg" width="46" height="58" viewBox="0 0 38 50">
                    <defs>
                        <linearGradient id="lmg" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#f59e0b"/>
                            <stop offset="100%" style="stop-color:#b45309"/>
                        </linearGradient>
                        <filter id="lmsh" x="-20%" y="-20%" width="140%" height="140%">
                            <feDropShadow dx="0" dy="3" stdDeviation="3" flood-color="rgba(180,83,9,0.5)"/>
                        </filter>
                    </defs>
                    <path d="M19 2C10.7 2 4 8.7 4 17c0 11.3 15 29 15 29s15-17.7 15-29c0-8.3-6.7-15-15-15z"
                          fill="url(#lmg)" filter="url(#lmsh)" stroke="#ffffff" stroke-width="2"/>
                    <circle cx="19" cy="17" r="9" fill="#ffffff"/>
                    <text x="19" y="21" text-anchor="middle" font-size="11" font-weight="bold">🏛️</text>
                </svg>`;

            const marker = new google.maps.Marker({
                position: center,
                map: landmarkMap,
                title: LANDMARK.name,
                zIndex: 9999,
                icon: {
                    url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(lmSvg),
                    scaledSize: new google.maps.Size(46, 58),
                    anchor: new google.maps.Point(23, 58)
                }
            });

            const lmInfo = new google.maps.InfoWindow({
                content: `<div style="font-family:Inter,sans-serif;padding:6px;max-width:200px;">
                    <span style="font-size:10px;font-weight:800;color:#d97706;text-transform:uppercase;">🏛️ Landmark</span>
                    <h4 style="font-size:13px;font-weight:900;margin:2px 0;color:#0f172a;">${LANDMARK.name}</h4>
                </div>`
            });
            marker.addListener('click', () => lmInfo.open(landmarkMap, marker));

            // Hotel Markers
            const bounds = new google.maps.LatLngBounds();
            bounds.extend(center);

            HOTELS.forEach(h => {
                if (h.latitude && h.longitude) {
                    const pos = { lat: parseFloat(h.latitude), lng: parseFloat(h.longitude) };
                    bounds.extend(pos);

                    const hMarker = new google.maps.Marker({
                        position: pos,
                        map: landmarkMap,
                        title: h.nama,
                        icon: {
                            path: google.maps.SymbolPath.CIRCLE,
                            scale: 7,
                            fillColor: '#2563eb',
                            fillOpacity: 0.95,
                            strokeColor: '#ffffff',
                            strokeWeight: 2
                        }
                    });

                    const info = new google.maps.InfoWindow({
                        content: `<div style="font-family:Inter,sans-serif;padding:6px;max-width:210px;">
                            <strong style="font-size:12px;color:#0f172a;">${h.nama}</strong><br>
                            <span style="font-size:11px;color:#64748b;">${h.jarak ? 'Jarak: ' + h.jarak : ''}</span><br>
                            <div style="margin-top:6px;display:flex;gap:4px;">
                                <button type="button" onclick="startRouteFromLandmark(${h.latitude}, ${h.longitude}, '${addslashes(h.nama)}')"
                                        style="background:#059669;color:white;font-size:10px;font-weight:700;padding:3px 8px;border-radius:6px;border:none;cursor:pointer;">
                                    🧭 Rute
                                </button>
                                <a href="/hotel/${h.id}" style="color:#2563eb;font-size:11px;font-weight:bold;align-self:center;">Detail &rarr;</a>
                            </div>
                        </div>`
                    });

                    hMarker.addListener('click', () => info.open(landmarkMap, hMarker));
                }
            });

            landmarkMap.fitBounds(bounds);
        }

        // Dual-Engine Routing to Hotel
        async function startRouteFromLandmark(destLat, destLng, destName) {
            if (!landmarkMap) return;
            document.getElementById('landmark-map')?.scrollIntoView({ behavior: 'smooth', block: 'center' });

            const origin = { lat: LANDMARK.lat, lng: LANDMARK.lon };
            const destination = { lat: parseFloat(destLat), lng: parseFloat(destLng) };

            // Attempt Google Directions first
            if (directionsService && directionsRenderer) {
                directionsService.route(
                    {
                        origin: origin,
                        destination: destination,
                        travelMode: google.maps.TravelMode.DRIVING
                    },
                    function(result, status) {
                        if (status === google.maps.DirectionsStatus.OK) {
                            if (activeLmPolyline) activeLmPolyline.setMap(null);
                            directionsRenderer.setDirections(result);
                            const leg = result.routes[0].legs[0];
                            showLandmarkRouteBar(destName, leg.distance.text, leg.duration.text, 'Google Maps', origin, destination);
                        } else {
                            console.warn('Google Directions failed (' + status + '), switching to OpenStreetMap OSRM...');
                            routeWithOSRM(origin, destination, destName);
                        }
                    }
                );
            } else {
                routeWithOSRM(origin, destination, destName);
            }
        }

        async function routeWithOSRM(origin, destination, destName) {
            try {
                const url = `https://router.project-osrm.org/route/v1/driving/${origin.lng},${origin.lat};${destination.lng},${destination.lat}?overview=full&geometries=geojson&steps=true`;
                const res = await fetch(url);
                if (!res.ok) throw new Error('OSRM error');
                const data = await res.json();
                if (data.code === 'Ok' && data.routes && data.routes.length > 0) {
                    const r = data.routes[0];
                    const coords = r.geometry.coordinates.map(c => ({ lat: c[1], lng: c[0] }));
                    const km = (r.distance / 1000).toFixed(1) + ' km';
                    const mins = Math.max(1, Math.round(r.duration / 60)) + ' mnt';
                    renderPolyline(coords);
                    showLandmarkRouteBar(destName, km, mins, 'OpenStreetMap (OSRM)', origin, destination);
                    return;
                }
            } catch (e) {
                console.warn('OSRM fetch failed, drawing direct route', e);
            }

            // Fallback direct interpolated route
            const dist = calcDist(origin.lat, origin.lng, destination.lat, destination.lng);
            const kmStr = dist.toFixed(1) + ' km';
            const minsStr = Math.max(1, Math.round((dist / 35) * 60)) + ' mnt';
            renderPolyline([origin, destination]);
            showLandmarkRouteBar(destName, kmStr, minsStr, 'Navigasi Langsung', origin, destination);
        }

        function renderPolyline(coords) {
            if (activeLmPolyline) activeLmPolyline.setMap(null);
            if (directionsRenderer) directionsRenderer.set('directions', null);

            activeLmPolyline = new google.maps.Polyline({
                path: coords,
                strokeColor: '#2563eb',
                strokeWeight: 5,
                strokeOpacity: 0.9,
                map: landmarkMap
            });

            const b = new google.maps.LatLngBounds();
            coords.forEach(pt => b.extend(pt));
            landmarkMap.fitBounds(b);
        }

        function showLandmarkRouteBar(destName, distStr, durStr, engineName, origin, dest) {
            const bar = document.getElementById('landmark-route-bar');
            const destEl = document.getElementById('landmark-route-destination');
            const statsEl = document.getElementById('landmark-route-stats');
            const badge = document.getElementById('landmark-route-engine-badge');
            const gmapsBtn = document.getElementById('btn-open-gmaps-lm');

            if (bar) bar.classList.remove('hidden');
            if (destEl) destEl.innerText = destName;
            if (statsEl) statsEl.innerText = `${distStr} · ${durStr}`;
            if (badge) badge.innerText = engineName;
            if (gmapsBtn) {
                gmapsBtn.href = `https://www.google.com/maps/dir/?api=1&origin=${origin.lat},${origin.lng}&destination=${dest.lat},${dest.lng}&travelmode=driving`;
            }
        }

        function clearLandmarkRoute() {
            if (activeLmPolyline) activeLmPolyline.setMap(null);
            if (directionsRenderer) directionsRenderer.set('directions', null);
            const bar = document.getElementById('landmark-route-bar');
            if (bar) bar.classList.add('hidden');
        }

        function calcDist(lat1, lon1, lat2, lon2) {
            const R = 6371;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLon/2) * Math.sin(dLon/2);
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        }

        function openExternalGoogleMaps(lat, lon) {
            window.open(`https://www.google.com/maps/search/?api=1&query=${lat},${lon}`, '_blank');
        }

        function openExternalOpenStreetMap(lat, lon) {
            window.open(`https://www.openstreetmap.org/?mlat=${lat}&mlon=${lon}#map=16/${lat}/${lon}`, '_blank');
        }

        function addslashes(string) {
            return String(string).replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '\\"');
        }
    </script>
</body>
</html>
