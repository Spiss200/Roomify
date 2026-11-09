<!doctype html>
<html lang="id" prefix="schema: https://schema.org/ roomify: http://roomify.example.org/ontology#">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian Hotel — Roomify</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans min-h-screen pb-12">

    <!-- Header / Navbar Ringkas -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="/" class="text-2xl font-black text-blue-600 tracking-wider">ROOMIFY</a>
            <a href="/" class="text-sm font-medium text-slate-600 hover:text-blue-600">&larr; Beranda</a>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8">
        
        <!-- Judul & Info Pencarian -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Hasil Pencarian Hotel</h1>
                
            </div>
            <div class="text-xs text-slate-500 bg-white border border-slate-200 px-3 py-1.5 rounded-lg">
                Total Ditemukan: <strong class="text-slate-800">{{ count($hotels ?? []) }}</strong> Hotel
            </div>
        </div>

        <!-- Layout 2 Kolom (Sidebar Filter & Grid Hasil) -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- KOLOM KIRI: Sidebar Filter (Form GET ke /search) -->
            <aside class="lg:col-span-1">
                <form action="{{ url('/search') }}" method="GET" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-6 sticky top-20">
                    
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h2 class="font-bold text-slate-900 text-base">Filter Pencarian</h2>
                        <a href="{{ url('/search') }}" class="text-xs text-blue-600 hover:underline">Reset</a>
                    </div>

                    <!-- Filter 1: Input Kata Kunci -->
                    <div>
                        <label for="q" class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Nama / Alamat</label>
                        <input type="text" id="q" name="q" value="{{ request('q') }}" placeholder="Cari nama hotel..." 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>

                    <!-- Filter 2: Landmark Terdekat (.ttl Ontology) -->
                    <div>
                        <label for="landmark" class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Dekat Landmark</label>
                        <select id="landmark" name="landmark" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">Semua Landmark</option>
                            <option value="Istana Maimun" {{ request('landmark') == 'Istana Maimun' ? 'selected' : '' }}>🏛️ Istana Maimun</option>
                            <option value="Tjong A Fie Mansion" {{ request('landmark') == 'Tjong A Fie Mansion' ? 'selected' : '' }}>🏛️ Tjong A Fie Mansion</option>
                            <option value="Masjid Raya Al Mashun" {{ request('landmark') == 'Masjid Raya Al Mashun' ? 'selected' : '' }}>🕌 Masjid Raya Al Mashun</option>
                            <option value="Lapangan Merdeka" {{ request('landmark') == 'Lapangan Merdeka' ? 'selected' : '' }}>🌳 Lapangan Merdeka</option>
                            <option value="Graha Maria Annai Velangkanni" {{ request('landmark') == 'Graha Maria Annai Velangkanni' ? 'selected' : '' }}>⛪ Graha Maria Annai Velangkanni</option>
                        </select>
                    </div>

                    <!-- Filter 3: Kelas Bintang Hotel (roomify:starRating) -->
                    <div>
                        <label for="rating" class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Rating Bintang</label>
                        <select id="rating" name="rating" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">Semua Bintang</option>
                            <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (Bintang 5)</option>
                            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ (Bintang 4+)</option>
                            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>⭐⭐⭐ (Bintang 3+)</option>
                        </select>
                    </div>

                    <!-- Filter 4: Fasilitas Diinginkan (roomify:hasFacility) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Fasilitas Utama</label>
                        <div class="space-y-2">
                            @php $selectedFacilities = request('facilities', []); @endphp
                            <label class="flex items-center gap-2 text-xs text-slate-700 cursor-pointer">
                                <input type="checkbox" name="facilities[]" value="WiFi" {{ in_array('WiFi', $selectedFacilities) ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500">
                                📶 WiFi
                            </label>
                            <label class="flex items-center gap-2 text-xs text-slate-700 cursor-pointer">
                                <input type="checkbox" name="facilities[]" value="Kolam Renang" {{ in_array('Kolam Renang', $selectedFacilities) ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500">
                                🏊 Kolam Renang
                            </label>
                            <label class="flex items-center gap-2 text-xs text-slate-700 cursor-pointer">
                                <input type="checkbox" name="facilities[]" value="AC" {{ in_array('AC', $selectedFacilities) ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500">
                                ❄️ AC
                            </label>
                            <label class="flex items-center gap-2 text-xs text-slate-700 cursor-pointer">
                                <input type="checkbox" name="facilities[]" value="Sarapan" {{ in_array('Sarapan', $selectedFacilities) ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500">
                                🍳 Sarapan
                            </label>
                        </div>
                    </div>

                    <!-- Tombol Terapkan Filter -->
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow transition">
                        Terapkan Filter
                    </button>
                </form>
            </aside>

            <!-- KOLOM KANAN: Grid Kartu Hasil Pencarian Hotel -->
            <div class="lg:col-span-3">
                <div id="hotel-results-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($hotels ?? [] as $item)
                        <!-- Tag Semantik RDFa: roomify:Hotel & schema:Hotel -->
                        <article typeof="roomify:Hotel schema:Hotel" about="http://roomify.example.org/ontology#{{ $item['id'] ?? $loop->index }}" 
                                 class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                            
                            <div>
                                <img src="{{ asset($item['foto'] ?? '/images/placeholder.svg') }}" property="schema:image" alt="{{ $item['nama'] }}" class="w-full h-44 object-cover">
                                
                                <div class="p-4">
                                    <div class="flex justify-between items-start mb-2 gap-2">
                                        <!-- Nama Hotel (rdfs:label & schema:name) -->
                                        <h2 property="rdfs:label schema:name" class="font-bold text-base text-slate-900 line-clamp-1">
                                            {{ $item['nama'] }}
                                        </h2>

                                        <!-- Rating Bintang (roomify:starRating) -->
                                        @if(!empty($item['bintang']) || !empty($item['starRating']))
                                            <span property="roomify:starRating schema:starRating" class="text-xs bg-amber-100 text-amber-800 font-semibold px-2 py-0.5 rounded shrink-0">
                                                ★ {{ $item['bintang'] ?? $item['starRating'] }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Alamat Hotel (roomify:address) -->
                                    <p property="roomify:address schema:address" class="text-slate-500 text-xs mb-3 flex items-center gap-1">
                                        📍 {{ $item['alamat'] ?? 'Medan' }}
                                    </p>

                                    <!-- Fasilitas Khas Ontologi (roomify:hasFacility) -->
                                    @if(!empty($item['fasilitas']))
                                        <div class="flex flex-wrap gap-1 mb-3">
                                            @foreach(array_slice($item['fasilitas'], 0, 3) as $fac)
                                                <span rel="roomify:hasFacility" class="bg-blue-50 text-blue-700 text-[10px] font-medium px-2 py-0.5 rounded">
                                                    <span property="rdfs:label">{{ $fac }}</span>
                                                </span>
                                            @endforeach
                                            @if(count($item['fasilitas']) > 3)
                                                <span class="text-[10px] text-slate-400 self-center">+{{ count($item['fasilitas']) - 3 }}</span>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Landmark Terdekat (roomify:nearLandmark) -->
                                    @if(!empty($item['landmarks']))
                                        <p class="text-[11px] text-slate-500 flex items-center gap-1">
                                            🏛️ Dekat: 
                                            <span rel="roomify:nearLandmark" class="font-medium text-slate-700">
                                                <span property="rdfs:label">{{ is_array($item['landmarks']) ? $item['landmarks'][0] : $item['landmarks'] }}</span>
                                            </span>
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Footer Kartu: Harga (roomify:startPrice) & Link Detail -->
                            <div class="px-4 pb-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] text-slate-400 uppercase block font-semibold">Mulai dari</span>
                                    <span property="roomify:startPrice" content="{{ $item['harga'] ?? $item['startPrice'] ?? 0 }}" class="text-base font-extrabold text-blue-600">
                                        Rp {{ number_format((int)($item['harga'] ?? $item['startPrice'] ?? 0), 0, ',', '.') }}
                                    </span>
                                </div>
                                <a href="{{ route('hotel.detail', ['id' => $item['id'] ?? 1]) ?? '#' }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg transition">
                                    Detail
                                </a>
                            </div>

                        </article>
                    @empty
                        <div class="col-span-full bg-white p-12 rounded-xl border border-slate-200 text-center">
                            <p class="text-slate-500 text-base">Tidak ada hotel yang sesuai dengan filter pencarian Anda di Medan.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </main>
</body>
</html>