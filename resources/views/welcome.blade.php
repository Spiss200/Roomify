<!doctype html>
<html lang="id" prefix="schema: https://schema.org/ roomify: http://roomify.example.org/ontology#">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roomify  Pencarian Hotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans min-h-screen">

    <!-- Navbar Header Modern -->
<header class="bg-white/95 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-50 shadow-sm transition-all">
    <div class="w-full px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        
        <!-- Logo Brand Mepet Kiri & Lebih Besar -->
        <a href="/" class="flex items-center gap-2 group focus:outline-none">
            <span class="text-3xl sm:text-4xl font-black text-blue-600 tracking-wider group-hover:text-blue-700 transition">
                ROOMIFY
            </span>
            <span class="text-xs font-extrabold uppercase bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-1 rounded-full tracking-wide">
                Medan
            </span>
        </a>

        <!-- Navigasi Kanan -->
        <nav class="flex items-center gap-2 sm:gap-4">
            <!-- Link Landmark -->
            <a href="#landmark-section" 
               class="text-sm font-semibold text-slate-600 hover:text-blue-600 hover:bg-slate-100/80 px-3.5 py-2.5 rounded-xl transition-all">
                🏛️ Landmark Medan
            </a>

            <!-- Tombol CTA Semua Hotel -->
            <a href="{{ url('/search') }}" 
               class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm hover:shadow transition-all active:scale-95">
                <span>Cari Semua Hotel</span>
                <span class="text-xs">&rarr;</span>
            </a>
        </nav>

    </div>
</header>

    <!-- Hero Section dengan Form Pencarian -->
    <section class="bg-gradient-to-b from-blue-600 to-blue-800 text-white py-16 px-4">
        <div class="max-w-4xl mx-auto text-center space-y-4">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">
                Temukan Hotel Ideal di Medan
            </h1>
            <p class="text-lg md:text-xl text-blue-100/90">
                Jelajahi penginapan terbaik di sekitar landmark terkenal Medan. Gunakan filter untuk menemukan hotel sesuai kebutuhan Anda.
            </p>

            <!-- Form Utama ke Route /search -->
            <form action="{{ url('/search') }}" method="GET" class="bg-white rounded-2xl p-4 md:p-6 shadow-xl text-slate-800 text-left mt-8 space-y-4">
                
                <!-- Input Kata Kunci Pencarian -->
                <div>
                    <label for="q" class="block text-xs font-bold text-slate-500 uppercase mb-1">Cari Hotel atau Alamat</label>
                    <input type="text" id="q" name="q" value="{{ request('q') }}" placeholder="Contoh: Cambridge, JW Marriott, atau Jalan Palang Merah..." 
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Dropdown Filter Landmark (.ttl Ontology) -->
                    <div>
                        <label for="landmark" class="block text-xs font-bold text-slate-500 uppercase mb-1">Dekat Landmark Medan</label>
                        <select id="landmark" name="landmark" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">-- Pilih Semua Landmark --</option>
                            <option value="Istana Maimun">🏛️ Istana Maimun</option>
                            <option value="Tjong A Fie Mansion">🏛️ Tjong A Fie Mansion</option>
                            <option value="Masjid Raya Al Mashun">🕌 Masjid Raya Al Mashun</option>
                            <option value="Lapangan Merdeka">🌳 Lapangan Merdeka</option>
                            <option value="Graha Maria Annai Velangkanni">⛪ Graha Maria Annai Velangkanni</option>
                        </select>
                    </div>

                    <!-- Dropdown Minimal Bintang (roomify:starRating) -->
                    <div>
                        <label for="rating" class="block text-xs font-bold text-slate-500 uppercase mb-1">Kelas Hotel (Bintang)</label>
                        <select id="rating" name="rating" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">Semua Bintang</option>
                            <option value="5">⭐⭐⭐⭐⭐ (Bintang 5)</option>
                            <option value="4">⭐⭐⭐⭐ (Bintang 4 atau lebih)</option>
                            <option value="3">⭐⭐⭐ (Bintang 3 atau lebih)</option>
                        </select>
                    </div>
                </div>

                <!-- Checkbox Fasilitas Populer (roomify:hasFacility) -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Fasilitas Diinginkan</label>
                    <div class="flex flex-wrap gap-3">
                        <label class="flex items-center gap-2 bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-lg cursor-pointer text-sm font-medium hover:bg-blue-50 transition">
                            <input type="checkbox" name="facilities[]" value="WiFi" class="rounded text-blue-600 focus:ring-blue-500">
                            📶 WiFi
                        </label>
                        <label class="flex items-center gap-2 bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-lg cursor-pointer text-sm font-medium hover:bg-blue-50 transition">
                            <input type="checkbox" name="facilities[]" value="Kolam Renang" class="rounded text-blue-600 focus:ring-blue-500">
                            🏊 Kolam Renang
                        </label>
                        <label class="flex items-center gap-2 bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-lg cursor-pointer text-sm font-medium hover:bg-blue-50 transition">
                            <input type="checkbox" name="facilities[]" value="AC" class="rounded text-blue-600 focus:ring-blue-500">
                            ❄️ AC
                        </label>
                        <label class="flex items-center gap-2 bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-lg cursor-pointer text-sm font-medium hover:bg-blue-50 transition">
                            <input type="checkbox" name="facilities[]" value="Sarapan" class="rounded text-blue-600 focus:ring-blue-500">
                            🍳 Sarapan
                        </label>
                    </div>
                </div>

                <!-- Tombol Submit Cari -->
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg transition flex items-center justify-center gap-2 text-base">
                    🔍 Cari Hotel Sekarang
                </button>
            </form>
        </div>
    </section>

    <!-- Section Landmark Khas Medan (Visual Card Search) -->
    <section id="landmark-section" class="max-w-6xl mx-auto px-4 py-16">
        <div class="text-center mb-10">
            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900">Jelajahi Hotel Menurut Landmark</h2>
            <p class="text-slate-500 text-sm mt-1">Pilih destinasi populer di Medan untuk menemukan penginapan terdekat</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Landmark Card 1: Istana Maimun -->
            <a href="{{ url('/search?landmark=Istana+Maimun') }}" class="group bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md hover:border-blue-300 transition">
                <div class="text-3xl mb-3">🏛️</div>
                <h3 property="rdfs:label" class="font-bold text-lg text-slate-900 group-hover:text-blue-600 transition">Istana Maimun</h3>
                <p class="text-xs text-slate-500 mt-1">Medan Maimun, Kota Medan</p>
                <span class="inline-block text-xs font-semibold text-blue-600 mt-3">Lihat Hotel Terdekat &rarr;</span>
            </a>

            <!-- Landmark Card 2: Tjong A Fie Mansion -->
            <a href="{{ url('/search?landmark=Tjong+A+Fie+Mansion') }}" class="group bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md hover:border-blue-300 transition">
                <div class="text-3xl mb-3">🏛️</div>
                <h3 property="rdfs:label" class="font-bold text-lg text-slate-900 group-hover:text-blue-600 transition">Tjong A Fie Mansion</h3>
                <p class="text-xs text-slate-500 mt-1">Kesawan, Kota Medan</p>
                <span class="inline-block text-xs font-semibold text-blue-600 mt-3">Lihat Hotel Terdekat &rarr;</span>
            </a>

            <!-- Landmark Card 3: Masjid Raya Al Mashun -->
            <a href="{{ url('/search?landmark=Masjid+Raya+Al+Mashun') }}" class="group bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md hover:border-blue-300 transition">
                <div class="text-3xl mb-3">🕌</div>
                <h3 property="rdfs:label" class="font-bold text-lg text-slate-900 group-hover:text-blue-600 transition">Masjid Raya Al Mashun</h3>
                <p class="text-xs text-slate-500 mt-1">Kota Matsum, Kota Medan</p>
                <span class="inline-block text-xs font-semibold text-blue-600 mt-3">Lihat Hotel Terdekat &rarr;</span>
            </a>

            <!-- Landmark Card 4: Lapangan Merdeka -->
            <a href="{{ url('/search?landmark=Lapangan+Merdeka') }}" class="group bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md hover:border-blue-300 transition">
                <div class="text-3xl mb-3">🌳</div>
                <h3 property="rdfs:label" class="font-bold text-lg text-slate-900 group-hover:text-blue-600 transition">Lapangan Merdeka</h3>
                <p class="text-xs text-slate-500 mt-1">Pusat Kota, Medan Barat</p>
                <span class="inline-block text-xs font-semibold text-blue-600 mt-3">Lihat Hotel Terdekat &rarr;</span>
            </a>

            <!-- Landmark Card 5: Graha Maria Annai Velangkanni -->
            <a href="{{ url('/search?landmark=Graha+Maria+Annai+Velangkanni') }}" class="group bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md hover:border-blue-300 transition sm:col-span-2 lg:col-span-1">
                <div class="text-3xl mb-3">⛪</div>
                <h3 property="rdfs:label" class="font-bold text-lg text-slate-900 group-hover:text-blue-600 transition">Graha Maria Annai Velangkanni</h3>
                <p class="text-xs text-slate-500 mt-1">Tuntungan, Kota Medan</p>
                <span class="inline-block text-xs font-semibold text-blue-600 mt-3">Lihat Hotel Terdekat &rarr;</span>
            </a>

        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-8 text-center text-xs text-slate-500">
        <p>© 2026 Roomify – Knowledge Graph Hotel Ontology Medan.</p>
    </footer>

</body>
</html>