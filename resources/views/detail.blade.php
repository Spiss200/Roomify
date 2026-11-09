<!doctype html>
<html lang="id" prefix="schema: https://schema.org/">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $hotel['nama'] }} — Roomify Medan</title>

    {{-- Open Graph Protocol --}}
    <meta property="og:title" content="{{ $hotel['nama'] }} — Roomify Medan">
    <meta property="og:description" content="{{ $hotel['deskripsi'] ?? $hotel['alamat'] }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ url($hotel['foto'] ?? '/images/placeholder.svg') }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans min-h-screen pb-12">

    <!-- Container Utama RDFa -->
    <main class="max-w-5xl mx-auto p-4 md:p-6" typeof="schema:Hotel" about="https://roomify.example/ontology#{{ $id ?? 'hotel' }}">
        
        <!-- Navigation Back -->
        <div class="mb-4">
            <a href="{{ url('/') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                &larr; Kembali ke Beranda
            </a>
        </div>

        <!-- Header Information -->
        <header class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 mb-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight" property="schema:name">
                        {{ $hotel['nama'] }}
                    </h1>
                    <p class="text-slate-500 mt-1 flex items-center gap-1" property="schema:address">
                        <span>📍</span> {{ $hotel['alamat'] }}
                    </p>
                    
                    <!-- Rating & Bintang (RDFa) -->
                    <div class="flex items-center gap-4 mt-3 text-sm">
                        @if(!empty($hotel['bintang']))
                        <span class="bg-amber-100 text-amber-800 font-semibold px-2.5 py-0.5 rounded-full flex items-center gap-1">
                            ★ <span property="schema:starRating">{{ $hotel['bintang'] }}</span> Bintang
                        </span>
                        @endif

                        @if(!empty($hotel['rating']))
                        <div property="schema:aggregateRating" typeof="schema:AggregateRating" class="flex items-center gap-1">
                            <span class="font-bold text-slate-900" property="schema:ratingValue">{{ $hotel['rating'] }}</span>/5
                            <span class="text-slate-400">
                                (<span property="schema:reviewCount">{{ $hotel['jumlahReview'] ?? 0 }}</span> ulasan)
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Harga Utama -->
                <div class="text-left md:text-right bg-slate-50 p-4 rounded-lg border border-slate-100" property="schema:makesOffer" typeof="schema:Offer">
                    <span class="text-xs text-slate-500 uppercase tracking-wider block">Mulai dari</span>
                    <span class="text-2xl font-black text-red-600" property="schema:price" content="{{ $hotel['harga'] ?? 0 }}">
                        Rp {{ number_format((int) ($hotel['harga'] ?? 0), 0, ',', '.') }}
                    </span>
                    <meta property="schema:priceCurrency" content="IDR" />
                    <span class="text-xs text-slate-400 block">/kamar/malam</span>
                </div>
            </div>

            <!-- Gambar Utama -->
            <div class="mt-6 overflow-hidden rounded-xl h-80 w-full relative">
                <img src="{{ $hotel['foto'] ?? '/images/placeholder.svg' }}"
                     alt="{{ $hotel['nama'] }}"
                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" 
                     property="schema:image">
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Kiri: Detail, Fasilitas & Kamar -->
            <div class="lg:col-span-2 space-y-6">
                
                @if(!empty($hotel['deskripsi']))
                <section class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                    <h2 class="text-lg font-bold text-slate-900 mb-2">Tentang Hotel</h2>
                    <p class="text-slate-600 leading-relaxed text-sm" property="schema:description">
                        {{ $hotel['deskripsi'] }}
                    </p>
                </section>
                @endif

                <!-- Fasilitas -->
                <section class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                    <h2 class="text-lg font-bold text-slate-900 mb-3">Fasilitas Hotel</h2>
                    <ul class="flex flex-wrap gap-2">
                        @forelse ($fasilitas ?? [] as $f)
                            <li class="bg-blue-50 text-blue-700 border border-blue-100 font-medium rounded-lg px-3 py-1.5 text-sm flex items-center gap-1.5" property="schema:amenityFeature">
                                <span>✔</span> {{ $f }}
                            </li>
                        @empty
                            <li class="text-slate-400 text-sm italic">Tidak ada informasi fasilitas.</li>
                        @endforelse
                    </ul>
                </section>

                <!-- Tipe Kamar -->
                <section class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                    <h2 class="text-lg font-bold text-slate-900 mb-4">Pilihan Tipe Kamar</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="bg-slate-100 text-slate-600 border-b">
                                    <th class="p-3 rounded-l-lg">Tipe Kamar</th>
                                    <th class="p-3">Kapasitas</th>
                                    <th class="p-3">Harga / Malam</th>
                                    <th class="p-3 rounded-r-lg text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($kamar ?? [] as $k)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="p-3 font-semibold text-slate-800">{{ $k['tipe'] }}</td>
                                        <td class="p-3 text-slate-600">👤 {{ $k['kapasitas'] }} Orang</td>
                                        <td class="p-3 font-bold text-slate-900">
                                            Rp {{ number_format((int) $k['harga'], 0, ',', '.') }}
                                        </td>
                                        <td class="p-3 text-right">
                                            <button class="bg-slate-200 text-slate-500 font-medium px-3 py-1.5 rounded-md text-xs cursor-not-allowed">
                                                Booking Menyusul
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-3 text-center text-slate-400 italic">Data kamar tidak tersedia.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <!-- Kolom Kanan: Peta & Landmark -->
            <div class="space-y-6">
                <!-- Peta Lokasi -->
                <section class="bg-white p-6 rounded-xl shadow-sm border border-slate-200" property="schema:geo" typeof="schema:GeoCoordinates">
                    <h2 class="text-lg font-bold text-slate-900 mb-3">Lokasi & Peta</h2>
                    <meta property="schema:latitude" content="{{ $hotel['lat'] ?? 3.5952 }}" />
                    <meta property="schema:longitude" content="{{ $hotel['lon'] ?? 98.6722 }}" />
                    
                    <div id="peta" class="h-64 rounded-lg border border-slate-200 z-0"></div>
                </section>

                <!-- Landmark Sekitar -->
                <section class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                    <h2 class="text-lg font-bold text-slate-900 mb-3">Tempat Menarik Sekitar</h2>
                    <ul class="space-y-3">
                        @forelse ($landmark ?? [] as $l)
                            <li class="flex items-start gap-2.5 text-sm pb-2 border-b border-slate-100 last:border-none last:pb-0">
                                <span class="text-red-500 mt-0.5">📍</span>
                                <div>
                                    <p class="font-medium text-slate-800">{{ $l['nama'] }}</p>
                                    <p class="text-xs text-slate-400">Sumber: {{ $l['sumber'] }}</p>
                                </div>
                            </li>
                        @empty
                            <li class="text-slate-400 text-sm italic">Tidak ada landmark terdekat.</li>
                        @endforelse
                    </ul>
                </section>
            </div>
        </div>
    </main>

    <!-- Script Peta Leaflet JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lat = {{ $hotel['lat'] ?? 3.5952 }};
            const lon = {{ $hotel['lon'] ?? 98.6722 }};
            const hotelNama = @json($hotel['nama'] ?? 'Hotel');
            const landmarks = @json($landmark ?? []);

            const peta = L.map('peta').setView([lat, lon], 14);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(peta);

            // Marker Hotel (Warna Biru)
            const hotelIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            L.marker([lat, lon], { icon: hotelIcon })
                .addTo(peta)
                .bindPopup(`<b>${hotelNama}</b><br>Lokasi Hotel`)
                .openPopup();

            // Marker Landmark Sekitar (Warna Merah)
            landmarks.forEach(l => {
                if (l.lat && l.lon) {
                    const landmarkIcon = L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                        iconSize: [20, 32],
                        iconAnchor: [10, 32],
                        popupAnchor: [1, -28],
                        shadowSize: [32, 32]
                    });

                    L.marker([l.lat, l.lon], { icon: landmarkIcon })
                        .addTo(peta)
                        .bindPopup(`<b>${l.nama}</b><br><small>Sumber: ${l.sumber}</small>`);
                }
            });
        });
    </script>
</body>
</html>