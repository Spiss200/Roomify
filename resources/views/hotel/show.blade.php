<!doctype html>
<html lang="id" prefix="schema: https://schema.org/ roomify: http://roomify.example.org/ontology#">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $hotel['nama'] ?? 'Detail Hotel' }} – Roomify Medan</title>

    <!-- Tailwind CSS & Leaflet -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans min-h-screen pb-12">

    <!-- Container Utama RDFa -->
    <main class="max-w-6xl mx-auto p-4 md:p-6" typeof="schema:Hotel roomify:Hotel" about="http://roomify.example.org/ontology#{{ $id }}">
        
        <!-- Navigation Back -->
        <div class="mb-4">
            <a href="javascript:history.back()" class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-800 transition">
                ← Kembali ke Pencarian
            </a>
        </div>

        <!-- Header Banner Hotel -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 property="rdfs:label schema:name" class="text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ $hotel['nama'] ?? '' }}
                    </h1>
                    <p property="roomify:address schema:address" class="text-slate-500 mt-1 flex items-center gap-1">
                        📍 {{ $hotel['alamat'] ?? 'Medan, Sumatera Utara' }}
                    </p>
                </div>

                                <!-- Tombol untuk Membuka Modal (Letakkan di dekat header hotel) -->
                <button onclick="document.getElementById('rdf-modal').classList.remove('hidden')" 
                        class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-lg border border-slate-300 transition">
                    ⚙️ Lihat Metadata RDF
                </button>

                <!-- Komponent Modal Pop-up Metadata Semantik -->
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
                            <button onclick="document.getElementById('rdf-modal').classList.add('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-4 py-2 rounded-lg transition">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="bg-amber-50 border border-amber-200 px-4 py-2 rounded-xl text-amber-700 font-bold text-center">
                        ⭐ <span property="roomify:starRating schema:starRating">{{ $hotel['starRating'] ?? '4.5' }}</span> / 5.0
                    </div>
                </div>
            </div>

            <!-- Price Highlight -->
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-baseline gap-2">
                <span class="text-slate-500 text-sm">Harga mulai dari:</span>
                <span class="text-3xl font-extrabold text-blue-600" property="roomify:startPrice" content="{{ $hotel['startPrice'] ?? 0 }}">
                    Rp {{ number_format($hotel['startPrice'] ?? 0, 0, ',', '.') }}
                </span>
                <span class="text-slate-400 text-xs">/ malam</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Kiri: Deskripsi, Fasilitas & Kamar -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Deskripsi -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-3">Deskripsi Hotel</h2>
                    <p property="roomify:description schema:description" class="text-slate-600 leading-relaxed">
                        {{ $hotel['deskripsi'] ?? 'Deskripsi belum tersedia.' }}
                    </p>
                </div>

                <!-- Fasilitas (roomify:hasFacility) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">Fasilitas Utama</h2>
                    <div class="flex flex-wrap gap-2">
                        @if(!empty($hotel['fasilitas']))
                            @foreach($hotel['fasilitas'] as $fasilitas)
                                <span rel="roomify:hasFacility" class="bg-blue-50 text-blue-700 border border-blue-100 px-3.5 py-1.5 rounded-lg text-sm font-medium">
                                    <span property="rdfs:label">✨ {{ $fasilitas }}</span>
                                </span>
                            @endforeach
                        @else
                            <p class="text-slate-400 text-sm">Fasilitas tidak tercantum.</p>
                        @endif
                    </div>
                </div>

                <!-- Pilihan Kamar (roomify:hasRoom) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">Tipe Kamar Tersedia</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if(!empty($hotel['kamar']))
                            @foreach($hotel['kamar'] as $kamar)
                                <div rel="roomify:hasRoom" typeof="roomify:Room schema:HotelRoom" class="border border-slate-200 rounded-xl p-4 bg-slate-50/50 hover:border-blue-300 transition">
                                    <h3 property="roomify:roomType" class="font-bold text-lg text-slate-800">{{ $kamar['tipe'] }}</h3>
                                    <p class="text-sm text-slate-500 mt-1">👥 Kapasitas: <span property="roomify:maxGuests" class="font-semibold">{{ $kamar['kapasitas'] }}</span> Tamu</p>
                                    <div class="mt-4 flex justify-between items-center">
                                        <span class="text-blue-600 font-bold text-lg">
                                            Rp <span property="roomify:pricePerNight">{{ number_format($kamar['harga'], 0, ',', '.') }}</span>
                                        </span>
                                        <span class="text-xs text-slate-400">/malam</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-slate-400 text-sm">Informasi kamar belum tersedia.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Peta & Landmark Terdekat -->
            <div class="space-y-6">
                <!-- Peta Lokasi -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-3">Lokasi (Peta)</h2>
                    <div id="map" class="w-full h-56 rounded-xl border border-slate-200"></div>
                </div>

                <!-- Landmark Terdekat (roomify:nearLandmark) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-3">Landmark Terdekat</h2>
                    <ul class="space-y-2">
                        @if(!empty($hotel['landmarks']))
                            @foreach($hotel['landmarks'] as $lm)
                                <li rel="roomify:nearLandmark" class="flex items-center gap-2 text-sm text-slate-600">
                                    <span class="text-blue-500">🏛️</span>
                                    <span property="rdfs:label">{{ $lm }}</span>
                                </li>
                            @endforeach
                        @else
                            <li class="text-slate-400 text-sm">Dekat pusat kota Medan</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <!-- Script Peta Leaflet.js -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lat = {{ $hotel['latitude'] ?? 3.5952 }};
            const lng = {{ $hotel['longitude'] ?? 98.6722 }};
            
            const map = L.map('map').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            L.marker([lat, lng]).addTo(map)
                .bindPopup('<b>{{ $hotel['nama'] ?? 'Hotel' }}</b>')
                .openPopup();
        });
    </script>
</body>
</html>