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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script>
        (function() {
            const saved = localStorage.getItem('theme');
            if (saved === 'dark' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();

        function toggleTheme() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
    </script>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen font-['Inter',sans-serif] antialiased">

    <!-- Top Live System Clock -->
    <x-live-clock-bar />

    <!-- Navigation Bar -->
    <header class="sticky top-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                    <span class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black text-lg shadow-sm">R</span>
                    <span class="font-extrabold text-xl tracking-tight text-slate-900 dark:text-white">Roomify</span>
                </a>
                <nav class="hidden md:flex items-center gap-5 text-sm font-semibold text-slate-600 dark:text-slate-300">
                    <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Beranda</a>
                    <a href="{{ route('search') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Cari Hotel</a>
                    <a href="{{ route('home') }}#landmarks" class="text-blue-600 dark:text-blue-400">Landmark Medan</a>
                    <a href="{{ url('/semantik') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Web Semantik</a>
                </nav>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="toggleTheme()" type="button" class="p-2 rounded-xl text-slate-500 hover:text-slate-700 dark:text-slate-300 dark:hover:text-white bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 transition" aria-label="Toggle theme">
                    <i class="fa-solid fa-moon hidden dark:inline text-amber-300"></i>
                    <i class="fa-solid fa-sun inline dark:hidden text-amber-500"></i>
                </button>
                <a href="{{ route('search') }}" class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i> Cari Semua Hotel
                </a>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
          typeof="roomify:Landmark schema:TouristAttraction"
          about="https://roomify.example/ontology#{{ $landmark['id'] }}">

        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-6">
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Beranda</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <a href="{{ route('home') }}#landmarks" class="hover:text-blue-600 transition">Landmark Medan</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span class="font-semibold text-slate-800 dark:text-slate-200" property="schema:name">{{ $landmark['nama'] }}</span>
        </nav>

        <!-- Landmark Hero Card -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-0">
                <!-- Landmark Photo -->
                <div class="lg:col-span-5 relative min-h-[280px] lg:min-h-[380px] bg-slate-100 dark:bg-slate-800">
                    <img src="{{ asset($landmark['foto'] ?? 'images/placeholder.svg') }}"
                         alt="{{ $landmark['nama'] }}"
                         property="schema:image"
                         class="w-full h-full object-cover"
                         onerror="this.src='{{ asset('images/placeholder.svg') }}'">
                    <div class="absolute top-4 left-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold text-white bg-slate-900/80 backdrop-blur-md shadow-sm">
                            <i class="fa-solid fa-landmark text-amber-300"></i>
                            <span property="roomify:category">{{ $landmark['kat'] ?? 'Landmark' }}</span>
                        </span>
                    </div>
                </div>

                <!-- Landmark Details -->
                <div class="lg:col-span-7 p-6 sm:p-8 flex flex-col justify-between">
                    <div>
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-2">
                            <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Tengara Ikonik Kota Medan</span>
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
                            <i class="fa-solid fa-location-dot text-blue-500 mt-1 shrink-0"></i>
                            <span>{{ $landmark['alamat'] ?? 'Kota Medan, Sumatera Utara' }}</span>
                        </p>

                        <!-- Coordinates -->
                        <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 dark:text-slate-400 pb-4 mb-4 border-b border-slate-100 dark:border-slate-800"
                             property="schema:geo" typeof="schema:GeoCoordinates">
                            <span><strong class="text-slate-700 dark:text-slate-300">Latitude:</strong> <span property="schema:latitude">{{ $landmark['lat'] }}</span></span>
                            <span><strong class="text-slate-700 dark:text-slate-300">Longitude:</strong> <span property="schema:longitude">{{ $landmark['lon'] }}</span></span>
                            <span><strong class="text-slate-700 dark:text-slate-300">URI Triplestore:</strong> <code class="text-blue-600 dark:text-blue-400 font-mono">roomify:{{ $landmark['id'] }}</code></span>
                        </div>

                        <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                            Landmark bersejarah dan titik daya tarik utama di Kota Medan. Di bawah ini tercantum hotel dan penginapan terdekat yang terhubung secara semantik dengan relasi <code class="text-xs bg-slate-100 dark:bg-slate-800 text-blue-600 dark:text-blue-400 px-1.5 py-0.5 rounded font-mono">roomify:nearLandmark</code>.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <a href="{{ route('search', ['landmark' => $landmark['nama']]) }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition">
                            <i class="fa-solid fa-hotel"></i>
                            Lihat Semua Hotel di Sekitar Landmark Ini
                        </a>
                        <button type="button"
                                onclick="openExternalGoogleMaps({{ $landmark['lat'] }}, {{ $landmark['lon'] }}, '{{ addslashes($landmark['nama']) }}')"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs text-blue-500"></i>
                            Buka di Google Maps
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Interactive Map Focus -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm mb-10">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-map-location-dot text-blue-500"></i>
                        Peta Lokasi & Hotel Terdekat
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Pin emas menandai lokasi {{ $landmark['nama'] }}, sedangkan pin biru menandai hotel-hotel di sekitarnya.</p>
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
            <div id="landmark-map" class="w-full h-80 sm:h-96 rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-800 bg-slate-100 dark:bg-slate-800"></div>
        </div>

        <!-- Section: Nearest Hotels Grid -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">
                        12 Rekomendasi Hotel Terdekat
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Diurutkan berdasarkan jarak terdekat dari {{ $landmark['nama'] }}.</p>
                </div>
                <a href="{{ route('search', ['landmark' => $landmark['nama']]) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 flex items-center gap-1">
                    Lihat Semua <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($nearestHotels as $h)
                <article class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col group"
                         typeof="roomify:Hotel schema:Hotel"
                         about="https://roomify.example/ontology#{{ $h['id'] }}">
                    <!-- Photo -->
                    <div class="relative h-44 bg-slate-100 dark:bg-slate-800 overflow-hidden">
                        <img src="{{ asset($h['foto']) }}"
                             alt="{{ $h['nama'] }}"
                             property="schema:image"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                             onerror="this.src='{{ asset('images/placeholder.svg') }}'">
                        
                        <!-- Star / Rating badge -->
                        @if(!empty($h['bintang']))
                        <div class="absolute top-2.5 left-2.5">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold text-white bg-amber-500 shadow-sm">
                                <i class="fa-solid fa-star text-[10px]"></i> {{ $h['bintang'] }}
                            </span>
                        </div>
                        @endif

                        @if(!empty($h['rating']))
                        <div class="absolute top-2.5 right-2.5">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold text-white bg-emerald-600 shadow-sm">
                                <i class="fa-solid fa-star text-[10px] text-amber-300"></i> {{ number_format((float)$h['rating'], 1) }}
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Body -->
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <!-- Provenance & Distance -->
                            <div class="flex items-center justify-between gap-2 mb-2">
                                @if(($h['dataSource'] ?? '') === 'osm' || empty($h['harga']))
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-sky-50 text-sky-700 border border-sky-200 dark:bg-sky-950/60 dark:text-sky-300 dark:border-sky-800">OpenStreetMap</span>
                                @else
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800">Terverifikasi (Google)</span>
                                @endif

                                @if(!empty($h['jarak']))
                                <span class="text-[11px] font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/60 px-2 py-0.5 rounded-lg border border-blue-100 dark:border-blue-900/60 flex items-center gap-1">
                                    <i class="fa-solid fa-location-dot text-[9px]"></i> {{ $h['jarak'] }}
                                </span>
                                @endif
                            </div>

                            <h3 class="font-bold text-slate-900 dark:text-white text-base leading-snug mb-1 line-clamp-1 group-hover:text-blue-600 transition" property="schema:name">
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
                                    <span class="text-base font-extrabold text-blue-600 dark:text-blue-400" property="roomify:startPrice">
                                        Rp {{ number_format((int)$h['harga'], 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-[10px] text-slate-400 uppercase font-semibold block">Tarif Kamar</span>
                                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Harga belum tersedia</span>
                                @endif
                            </div>
                            <a href="{{ route('hotel.detail', ['id' => $h['id']]) }}"
                               class="px-3 py-1.5 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 transition flex items-center gap-1">
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
        <div class="bg-slate-100 dark:bg-slate-900/60 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="fa-solid fa-compass text-blue-500"></i>
                Jelajahi Landmark Medan Lainnya (24 Lokasi)
            </h2>
            <div class="flex flex-wrap gap-2">
                @foreach($allLandmarks as $alm)
                    <a href="{{ route('landmark.show', ['id' => $alm['id']]) }}"
                       class="px-3 py-1.5 rounded-xl text-xs font-medium border {{ $alm['id'] === $landmark['id'] ? 'bg-blue-600 text-white border-blue-600 font-bold' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-blue-400 hover:text-blue-600 transition' }}">
                        {{ $alm['name'] }}
                    </a>
                @endforeach
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="mt-16 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 py-8 text-center text-xs text-slate-500">
        <p>© 2026 Roomify – Direktori & Pemesanan Hotel Berbasis Web Semantik Kota Medan.</p>
    </footer>

    <!-- Google Maps JS API -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initLandmarkMap" async defer></script>

    <script>
        const LANDMARK = {
            id: @json($landmark['id']),
            name: @json($landmark['nama']),
            lat: {{ $landmark['lat'] }},
            lon: {{ $landmark['lon'] }}
        };

        const HOTELS = @json($nearestHotels);

        function initLandmarkMap() {
            if (typeof google === 'undefined' || !google.maps) return;

            const center = { lat: LANDMARK.lat, lng: LANDMARK.lon };
            const map = new google.maps.Map(document.getElementById('landmark-map'), {
                zoom: 14,
                center: center,
                mapTypeControl: false,
                fullscreenControl: true,
                streetViewControl: false
            });

            // Golden Landmark Marker
            new google.maps.Marker({
                position: center,
                map: map,
                title: LANDMARK.name,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 10,
                    fillColor: '#f59e0b',
                    fillOpacity: 1,
                    strokeColor: '#ffffff',
                    strokeWeight: 3
                }
            });

            // Hotel Markers
            const bounds = new google.maps.LatLngBounds();
            bounds.extend(center);

            HOTELS.forEach(h => {
                if (h.latitude && h.longitude) {
                    const pos = { lat: parseFloat(h.latitude), lng: parseFloat(h.longitude) };
                    bounds.extend(pos);

                    const marker = new google.maps.Marker({
                        position: pos,
                        map: map,
                        title: h.nama,
                        icon: {
                            path: google.maps.SymbolPath.CIRCLE,
                            scale: 6,
                            fillColor: '#2563eb',
                            fillOpacity: 0.9,
                            strokeColor: '#ffffff',
                            strokeWeight: 2
                        }
                    });

                    const info = new google.maps.InfoWindow({
                        content: `<div style="font-family:Inter,sans-serif;padding:4px;">
                            <strong style="font-size:12px;">${h.nama}</strong><br>
                            <span style="font-size:11px;color:#64748b;">${h.jarak ? 'Jarak: ' + h.jarak : ''}</span><br>
                            <a href="/hotel/${h.id}" style="color:#2563eb;font-size:11px;font-weight:bold;">Lihat Hotel &rarr;</a>
                        </div>`
                    });

                    marker.addListener('click', () => info.open(map, marker));
                }
            });

            map.fitBounds(bounds);
        }

        function openExternalGoogleMaps(lat, lon, label) {
            const url = `https://www.google.com/maps/search/?api=1&query=${lat},${lon}`;
            window.open(url, '_blank');
        }
    </script>
</body>
</html>
