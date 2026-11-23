<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPARQL Playground & Ontologi Web Semantik – Roomify Medan</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        .font-mono { font-family: 'JetBrains Mono', monospace; }
    </style>

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
    <header class="sticky top-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black text-lg shadow-sm">R</span>
                    <span class="font-extrabold text-xl tracking-tight text-slate-900 dark:text-white">Roomify</span>
                </a>
                <nav class="hidden md:flex items-center gap-5 text-sm font-semibold text-slate-600 dark:text-slate-300">
                    <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Beranda</a>
                    <a href="{{ route('search') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Cari Hotel</a>
                    <a href="{{ route('home') }}#landmarks" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Landmark Medan</a>
                    <a href="{{ url('/semantik') }}" class="text-blue-600 dark:text-blue-400">Web Semantik</a>
                </nav>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="toggleTheme()" type="button" class="p-2 rounded-xl text-slate-500 hover:text-slate-700 dark:text-slate-300 dark:hover:text-white bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 transition" aria-label="Toggle theme">
                    <i class="fa-solid fa-moon hidden dark:inline text-amber-300"></i>
                    <i class="fa-solid fa-sun inline dark:hidden text-amber-500"></i>
                </button>
                <a href="{{ route('search') }}" class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i> Cari Hotel
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">

        <!-- Hero Header -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 sm:p-10 shadow-sm">
            <div class="max-w-3xl">
                <span class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-2 block">Tugas Besar Web Semantik & Triplestore</span>
                <h1 class="text-2xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight mb-4 leading-tight">
                    Arsitektur Web Semantik, Triplestore & SPARQL Playground
                </h1>
                <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    Roomify mengintegrasikan data perhotelan Kota Medan ke dalam basis pengetahuan RDF (Resource Description Framework) menggunakan Apache Jena Fuseki sebagai triplestore. Ontologi dirancang berdasarkan standar Schema.org dan W3C OWL/RDFS untuk mendukung pencarian semantik, inferensi spasial landmark, dan federasi data eksternal.
                </p>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="#playground" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm">
                        <i class="fa-solid fa-play text-xs"></i> Buka SPARQL Playground
                    </a>
                    <a href="#ontology" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                        <i class="fa-solid fa-diagram-project text-xs text-blue-500"></i> Struktur Ontologi
                    </a>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('semantik.download', ['file' => 'roomify.ttl']) }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-blue-400 transition">
                            <i class="fa-solid fa-download text-xs text-emerald-500"></i> roomify.ttl
                        </a>
                        <a href="{{ route('semantik.download', ['file' => 'landmarks.ttl']) }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-blue-400 transition">
                            <i class="fa-solid fa-download text-xs text-emerald-500"></i> landmarks.ttl
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Statistics Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 shadow-sm">
                <span class="text-[11px] font-semibold text-slate-400 block mb-1">Total Triples</span>
                <span class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">{{ number_format($totalTriples, 0, ',', '.') }}</span>
                <span class="text-[10px] text-slate-500 block mt-1">Dataset Fuseki</span>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 shadow-sm">
                <span class="text-[11px] font-semibold text-slate-400 block mb-1">Total Hotel</span>
                <span class="text-xl sm:text-2xl font-black text-blue-600 dark:text-blue-400">{{ $sourceStats['total'] }}</span>
                <span class="text-[10px] text-slate-500 block mt-1">187 GMaps + 125 OSM</span>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 shadow-sm">
                <span class="text-[11px] font-semibold text-slate-400 block mb-1">Landmark Medan</span>
                <span class="text-xl sm:text-2xl font-black text-amber-500">24</span>
                <span class="text-[10px] text-slate-500 block mt-1">Wisata, Budaya, Ikon</span>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 shadow-sm">
                <span class="text-[11px] font-semibold text-slate-400 block mb-1">Kelas Ontologi</span>
                <span class="text-xl sm:text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ count($classes) }}</span>
                <span class="text-[10px] text-slate-500 block mt-1">owl:Class terdaftar</span>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 shadow-sm">
                <span class="text-[11px] font-semibold text-slate-400 block mb-1">Object Property</span>
                <span class="text-xl sm:text-2xl font-black text-indigo-600 dark:text-indigo-400">{{ count($objectProperties) }}</span>
                <span class="text-[10px] text-slate-500 block mt-1">Relasi semantik antarentitas</span>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 shadow-sm">
                <span class="text-[11px] font-semibold text-slate-400 block mb-1">Datatype Property</span>
                <span class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">{{ count($datatypeProperties) }}</span>
                <span class="text-[10px] text-slate-500 block mt-1">Literal & skalar tipe data</span>
            </div>
        </div>

        <!-- Section: 4 Interactive SPARQL Panels -->
        <div id="playground" class="space-y-6">
            <div>
                <span class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider block mb-1">Interactive Feature Panels</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">
                    4 Demonstrasi Query SPARQL Utama
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Uji langsung klausa FILTER, REGEX, ASK, dan OPTIONAL langsung pada basis data semantik Roomify.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- PANEL 1: FILTER -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                <i class="fa-solid fa-filter text-[10px]"></i> 1. FILTER Clause
                            </span>
                            <span class="text-[11px] text-slate-400">Multi-kriteria Harga & Rating</span>
                        </div>
                        <h3 class="font-extrabold text-base text-slate-900 dark:text-white mb-2">Penyaringan Numerik Berjenjang</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4 leading-relaxed">
                            Mendemonstrasikan seleksi hotel menggunakan ekspresi boolean <code class="bg-slate-100 dark:bg-slate-800 text-blue-600 px-1 py-0.5 rounded font-mono">FILTER(?harga &lt;= X &amp;&amp; ?rating &gt;= Y)</code>.
                        </p>

                        <!-- Controls -->
                        <div class="space-y-4 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-2xl mb-4 border border-slate-100 dark:border-slate-800">
                            <div>
                                <div class="flex justify-between text-xs font-semibold mb-1">
                                    <span>Maksimum Tarif:</span>
                                    <span id="filter-price-val" class="text-blue-600 font-bold">Rp 500.000</span>
                                </div>
                                <input type="range" id="filter-price-input" min="200000" max="1500000" step="50000" value="500000"
                                       class="w-full accent-blue-600" oninput="updateFilterQuery()">
                            </div>
                            <div>
                                <div class="flex justify-between text-xs font-semibold mb-1">
                                    <span>Minimum Rating:</span>
                                    <span id="filter-rating-val" class="text-emerald-600 font-bold">4.0 ★</span>
                                </div>
                                <input type="range" id="filter-rating-input" min="3.0" max="5.0" step="0.1" value="4.0"
                                       class="w-full accent-emerald-600" oninput="updateFilterQuery()">
                            </div>
                        </div>

                        <!-- Code Preview -->
                        <div class="relative bg-slate-900 text-slate-200 rounded-2xl p-4 font-mono text-xs overflow-x-auto mb-4 border border-slate-800">
                            <pre id="filter-query-code">PREFIX roomify: &lt;https://roomify.example/ontology#&gt;
SELECT ?nama ?harga ?rating WHERE {
  ?h a roomify:Hotel ;
     roomify:name ?nama ;
     roomify:startPrice ?harga ;
     roomify:rating ?rating .
  FILTER(?harga &lt;= 500000 &amp;&amp; ?rating &gt;= 4.0)
} ORDER BY ASC(?harga) LIMIT 5</pre>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <button onclick="runPredefinedQuery('filter-query-code', 'filter-result')" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 transition flex items-center gap-1.5">
                            <i class="fa-solid fa-play text-[10px]"></i> Jalankan Query
                        </button>
                        <button onclick="copyCode('filter-query-code')" class="px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 transition">
                            <i class="fa-regular fa-copy"></i> Salin
                        </button>
                    </div>

                    <!-- Result Output -->
                    <div id="filter-result" class="mt-4 hidden text-xs"></div>
                </div>

                <!-- PANEL 2: REGEX -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                <i class="fa-solid fa-font text-[10px]"></i> 2. REGEX Pattern
                            </span>
                            <span class="text-[11px] text-slate-400">Pencocokan String Lanjutan</span>
                        </div>
                        <h3 class="font-extrabold text-base text-slate-900 dark:text-white mb-2">Pola Regular Expression di Triplestore</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4 leading-relaxed">
                            Mendemonstrasikan fungsi <code class="bg-slate-100 dark:bg-slate-800 text-amber-600 px-1 py-0.5 rounded font-mono">FILTER(REGEX(?nama, pattern, "i"))</code> untuk pencarian pola teks kompleks (awalan, akhiran, atau kata kunci).
                        </p>

                        <!-- Controls -->
                        <div class="space-y-4 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-2xl mb-4 border border-slate-100 dark:border-slate-800">
                            <div>
                                <label for="regex-pattern-input" class="text-xs font-semibold block mb-1">Pola Regex (Pattern):</label>
                                <div class="flex gap-2">
                                    <input type="text" id="regex-pattern-input" value="^Grand"
                                           class="flex-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs font-mono"
                                           oninput="updateRegexQuery()">
                                    <button onclick="setRegexPattern('^Grand')" class="px-2.5 py-1 text-[11px] rounded-lg bg-slate-200 dark:bg-slate-700 font-mono">^Grand</button>
                                    <button onclick="setRegexPattern('Resort|Suites')" class="px-2.5 py-1 text-[11px] rounded-lg bg-slate-200 dark:bg-slate-700 font-mono">Resort|Suites</button>
                                </div>
                            </div>
                        </div>

                        <!-- Code Preview -->
                        <div class="relative bg-slate-900 text-slate-200 rounded-2xl p-4 font-mono text-xs overflow-x-auto mb-4 border border-slate-800">
                            <pre id="regex-query-code">PREFIX roomify: &lt;https://roomify.example/ontology#&gt;
SELECT ?id ?nama ?alamat WHERE {
  ?h a roomify:Hotel ;
     roomify:name ?nama ;
     roomify:address ?alamat .
  BIND(STRAFTER(STR(?h), "#") AS ?id)
  FILTER(REGEX(?nama, "^Grand", "i"))
} LIMIT 5</pre>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <button onclick="runPredefinedQuery('regex-query-code', 'regex-result')" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 transition flex items-center gap-1.5">
                            <i class="fa-solid fa-play text-[10px]"></i> Jalankan Query
                        </button>
                        <button onclick="copyCode('regex-query-code')" class="px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 transition">
                            <i class="fa-regular fa-copy"></i> Salin
                        </button>
                    </div>

                    <!-- Result Output -->
                    <div id="regex-result" class="mt-4 hidden text-xs"></div>
                </div>

                <!-- PANEL 3: ASK -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                <i class="fa-solid fa-circle-question text-[10px]"></i> 3. ASK Query
                            </span>
                            <span class="text-[11px] text-slate-400">Verifikasi Fakta Boolean</span>
                        </div>
                        <h3 class="font-extrabold text-base text-slate-900 dark:text-white mb-2">Evaluasi Kebenaran Relasi Semantik</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4 leading-relaxed">
                            SPARQL <code class="bg-slate-100 dark:bg-slate-800 text-emerald-600 px-1 py-0.5 rounded font-mono">ASK</code> memeriksa apakah graf triple tertentu ada dalam dataset, menghasilkan nilai boolean sederhana <code class="font-bold">true</code> atau <code class="font-bold">false</code> tanpa mengembalikan daftar bindings.
                        </p>

                        <!-- Controls -->
                        <div class="space-y-3 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-2xl mb-4 border border-slate-100 dark:border-slate-800">
                            <div>
                                <label for="ask-hotel-select" class="text-xs font-semibold block mb-1">Pilih Hotel Subjek:</label>
                                <select id="ask-hotel-select" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs" onchange="updateAskQuery()">
                                    @foreach($hotels as $h)
                                        <option value="{{ $h['id'] }}">{{ $h['nama'] }} ({{ $h['id'] }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="ask-facility-select" class="text-xs font-semibold block mb-1">Pilih Fasilitas Objek:</label>
                                <select id="ask-facility-select" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs" onchange="updateAskQuery()">
                                    @foreach($facilities as $f)
                                        <option value="{{ $f['slug'] }}">{{ $f['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Code Preview -->
                        <div class="relative bg-slate-900 text-slate-200 rounded-2xl p-4 font-mono text-xs overflow-x-auto mb-4 border border-slate-800">
                            <pre id="ask-query-code">PREFIX roomify: &lt;https://roomify.example/ontology#&gt;
ASK {
  roomify:{{ $hotels[0]['id'] ?? 'hotel-001' }} roomify:hasFacility roomify:fac-kolam-renang .
}</pre>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <button onclick="runAskQuery()" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 transition flex items-center gap-1.5">
                            <i class="fa-solid fa-play text-[10px]"></i> Verifikasi ASK
                        </button>
                        <button onclick="copyCode('ask-query-code')" class="px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 transition">
                            <i class="fa-regular fa-copy"></i> Salin
                        </button>
                    </div>

                    <!-- Result Output -->
                    <div id="ask-result" class="mt-4 hidden p-3 rounded-xl border text-xs"></div>
                </div>

                <!-- PANEL 4: OPTIONAL -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                                <i class="fa-solid fa-code-merge text-[10px]"></i> 4. OPTIONAL Semantics
                            </span>
                            <span class="text-[11px] text-slate-400">Left Outer Join</span>
                        </div>
                        <h3 class="font-extrabold text-base text-slate-900 dark:text-white mb-2">Pola Data Tidak Lengkap (OSM vs Google)</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4 leading-relaxed">
                            Mendemonstrasikan mengapa <code class="bg-slate-100 dark:bg-slate-800 text-indigo-600 px-1 py-0.5 rounded font-mono">OPTIONAL { ?h roomify:startPrice ?harga }</code> sangat krusial di Roomify. 125 hotel dari OpenStreetMap tidak memiliki harga. Tanpa OPTIONAL, 125 hotel tersebut akan tereliminasi (hanya tersisa 187).
                        </p>

                        <!-- Controls -->
                        <div class="space-y-4 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-2xl mb-4 border border-slate-100 dark:border-slate-800">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold">Mode Query SPARQL:</span>
                                <div class="flex rounded-xl bg-slate-200 dark:bg-slate-700 p-1">
                                    <button id="opt-btn-with" onclick="setOptionalMode(true)" class="px-3 py-1 rounded-lg text-xs font-bold bg-white dark:bg-slate-900 shadow-sm transition">Dengan OPTIONAL (312 Hotel)</button>
                                    <button id="opt-btn-without" onclick="setOptionalMode(false)" class="px-3 py-1 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-800 dark:hover:text-white transition">Tanpa OPTIONAL (187 Hotel)</button>
                                </div>
                            </div>
                        </div>

                        <!-- Code Preview -->
                        <div class="relative bg-slate-900 text-slate-200 rounded-2xl p-4 font-mono text-xs overflow-x-auto mb-4 border border-slate-800">
                            <pre id="optional-query-code">PREFIX roomify: &lt;https://roomify.example/ontology#&gt;
SELECT ?id ?nama ?harga WHERE {
  ?h a roomify:Hotel ;
     roomify:name ?nama .
  OPTIONAL { ?h roomify:startPrice ?harga }
  BIND(STRAFTER(STR(?h), "#") AS ?id)
} LIMIT 400</pre>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <button onclick="runPredefinedQuery('optional-query-code', 'optional-result')" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 transition flex items-center gap-1.5">
                            <i class="fa-solid fa-play text-[10px]"></i> Hitung Baris Hasil
                        </button>
                        <button onclick="copyCode('optional-query-code')" class="px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 transition">
                            <i class="fa-regular fa-copy"></i> Salin
                        </button>
                    </div>

                    <!-- Result Output -->
                    <div id="optional-result" class="mt-4 hidden text-xs"></div>
                </div>

            </div>
        </div>

        <!-- Section: Custom SPARQL Console -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 shadow-sm">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4">
                <div>
                    <h2 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-terminal text-blue-500"></i>
                        Konsol Eksekusi SPARQL Bebas
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Tulis atau coba query SPARQL kustom untuk mengeksplorasi triplestore Kota Medan secara langsung.</p>
                </div>
                <!-- Presets -->
                <div class="flex flex-wrap items-center gap-1.5">
                    <span class="text-xs text-slate-400 mr-1">Preset:</span>
                    <button onclick="loadPreset(1)" class="px-2.5 py-1 text-xs rounded-lg border border-slate-200 dark:border-slate-700 hover:border-blue-400 transition">Hotel Bintang 5</button>
                    <button onclick="loadPreset(2)" class="px-2.5 py-1 text-xs rounded-lg border border-slate-200 dark:border-slate-700 hover:border-blue-400 transition">Dekat Istana Maimun</button>
                    <button onclick="loadPreset(3)" class="px-2.5 py-1 text-xs rounded-lg border border-slate-200 dark:border-slate-700 hover:border-blue-400 transition">24 Landmark & Kategori</button>
                </div>
            </div>

            <div class="space-y-3">
                <textarea id="custom-sparql-input" rows="8"
                          class="w-full bg-slate-900 text-slate-100 font-mono text-xs p-4 rounded-2xl border border-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 leading-relaxed">PREFIX roomify: <https://roomify.example/ontology#>
SELECT ?nama ?harga ?bintang ?alamat WHERE {
  ?h a roomify:Hotel ;
     roomify:name ?nama ;
     roomify:address ?alamat .
  OPTIONAL { ?h roomify:startPrice ?harga }
  OPTIONAL { ?h roomify:starRating ?bintang }
  FILTER(BOUND(?bintang) && ?bintang >= 4)
} ORDER BY DESC(?bintang) LIMIT 10</textarea>

                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <button onclick="executeCustomQuery()" id="btn-run-custom"
                                class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition flex items-center gap-2">
                            <i class="fa-solid fa-play text-xs"></i> Eksekusi SPARQL
                        </button>
                        <button onclick="clearCustomOutput()" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 transition">
                            Bersihkan
                        </button>
                    </div>
                    <span id="custom-status" class="text-xs text-slate-400"></span>
                </div>

                <!-- Custom Query Result Container -->
                <div id="custom-result-container" class="hidden mt-4 overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800">
                    <table id="custom-result-table" class="w-full text-xs text-left">
                        <thead class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold border-b border-slate-200 dark:border-slate-700">
                            <!-- Headers inserted via JS -->
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <!-- Rows inserted via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section: Ontology Browser -->
        <div id="ontology" class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 shadow-sm space-y-6">
            <div>
                <span class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider block mb-1">Formal Specification</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">
                    Penjelajah Struktur Ontologi (Namespace: <code class="text-blue-600 dark:text-blue-400 font-mono text-base">roomify:</code>)
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Rincian 7 Kelas, 13 Object Property, dan 19 Datatype Property yang mendefinisikan semantik Roomify.</p>
            </div>

            <!-- Ontology Tabs -->
            <div class="flex items-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-3">
                <button onclick="switchOntologyTab('classes')" id="tab-classes" class="px-4 py-2 rounded-xl text-xs font-bold bg-blue-600 text-white transition">Kelas (7)</button>
                <button onclick="switchOntologyTab('obj-props')" id="tab-obj-props" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">Object Properties (13)</button>
                <button onclick="switchOntologyTab('data-props')" id="tab-data-props" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">Datatype Properties (19)</button>
            </div>

            <!-- Tab 1: Classes -->
            <div id="content-classes" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($classes as $c)
                <div class="p-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40">
                    <div class="flex items-center justify-between mb-1.5">
                        <strong class="text-sm font-bold text-slate-900 dark:text-white">{{ $c['name'] }}</strong>
                        <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-950 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-900">{{ $c['uri'] }}</span>
                    </div>
                    <div class="text-xs text-slate-400 font-mono mb-2">sameAs: {{ $c['sameAs'] }}</div>
                    <p class="text-xs text-slate-600 dark:text-slate-300">{{ $c['desc'] }}</p>
                </div>
                @endforeach
            </div>

            <!-- Tab 2: Object Properties -->
            <div id="content-obj-props" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($objectProperties as $op)
                <div class="p-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40">
                    <div class="flex items-center justify-between mb-1">
                        <strong class="text-sm font-bold text-indigo-600 dark:text-indigo-400 font-mono">roomify:{{ $op['name'] }}</strong>
                        <span class="text-[10px] px-2 py-0.5 rounded bg-indigo-50 dark:bg-indigo-950 text-indigo-600 border border-indigo-200 dark:border-indigo-900">owl:ObjectProperty</span>
                    </div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-3 mb-2 font-mono">
                        <span><strong>Domain:</strong> {{ $op['domain'] }}</span>
                        <span>&rarr;</span>
                        <span><strong>Range:</strong> {{ $op['range'] }}</span>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-300">{{ $op['desc'] }}</p>
                </div>
                @endforeach
            </div>

            <!-- Tab 3: Datatype Properties -->
            <div id="content-data-props" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($datatypeProperties as $dp)
                <div class="p-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40">
                    <div class="flex items-center justify-between mb-1">
                        <strong class="text-xs font-bold text-slate-900 dark:text-white font-mono">roomify:{{ $dp['name'] }}</strong>
                        <span class="text-[10px] font-mono text-emerald-600 dark:text-emerald-400">{{ $dp['type'] }}</span>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-300">{{ $dp['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="mt-16 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 py-8 text-center text-xs text-slate-500">
        <p>© 2026 Roomify – Direktori & Pemesanan Hotel Berbasis Web Semantik Kota Medan.</p>
    </footer>

    <!-- JavaScript for Live Queries -->
    <script>
        function updateFilterQuery() {
            const p = document.getElementById('filter-price-input').value;
            const r = document.getElementById('filter-rating-input').value;
            document.getElementById('filter-price-val').innerText = 'Rp ' + parseInt(p).toLocaleString('id-ID');
            document.getElementById('filter-rating-val').innerText = parseFloat(r).toFixed(1) + ' ★';

            const query = `PREFIX roomify: <https://roomify.example/ontology#>
SELECT ?nama ?harga ?rating WHERE {
  ?h a roomify:Hotel ;
     roomify:name ?nama ;
     roomify:startPrice ?harga ;
     roomify:rating ?rating .
  FILTER(?harga <= ${p} && ?rating >= ${parseFloat(r).toFixed(1)})
} ORDER BY ASC(?harga) LIMIT 5`;

            document.getElementById('filter-query-code').innerText = query;
        }

        function setRegexPattern(pattern) {
            document.getElementById('regex-pattern-input').value = pattern;
            updateRegexQuery();
        }

        function updateRegexQuery() {
            const pattern = document.getElementById('regex-pattern-input').value.trim();
            const query = `PREFIX roomify: <https://roomify.example/ontology#>
SELECT ?id ?nama ?alamat WHERE {
  ?h a roomify:Hotel ;
     roomify:name ?nama ;
     roomify:address ?alamat .
  BIND(STRAFTER(STR(?h), "#") AS ?id)
  FILTER(REGEX(?nama, "${pattern}", "i"))
} LIMIT 5`;
            document.getElementById('regex-query-code').innerText = query;
        }

        function updateAskQuery() {
            const hotelId = document.getElementById('ask-hotel-select').value;
            const facSlug = document.getElementById('ask-facility-select').value;
            const query = `PREFIX roomify: <https://roomify.example/ontology#>
ASK {
  roomify:${hotelId} roomify:hasFacility roomify:${facSlug} .
}`;
            document.getElementById('ask-query-code').innerText = query;
        }

        let optionalMode = true;
        function setOptionalMode(isOptional) {
            optionalMode = isOptional;
            const btnWith = document.getElementById('opt-btn-with');
            const btnWithout = document.getElementById('opt-btn-without');

            if (isOptional) {
                btnWith.className = 'px-3 py-1 rounded-lg text-xs font-bold bg-white dark:bg-slate-900 shadow-sm transition';
                btnWithout.className = 'px-3 py-1 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-800 dark:hover:text-white transition';
                document.getElementById('optional-query-code').innerText = `PREFIX roomify: <https://roomify.example/ontology#>
SELECT ?id ?nama ?harga WHERE {
  ?h a roomify:Hotel ;
     roomify:name ?nama .
  OPTIONAL { ?h roomify:startPrice ?harga }
  BIND(STRAFTER(STR(?h), "#") AS ?id)
} LIMIT 400`;
            } else {
                btnWithout.className = 'px-3 py-1 rounded-lg text-xs font-bold bg-white dark:bg-slate-900 shadow-sm transition';
                btnWith.className = 'px-3 py-1 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-800 dark:hover:text-white transition';
                document.getElementById('optional-query-code').innerText = `PREFIX roomify: <https://roomify.example/ontology#>
SELECT ?id ?nama ?harga WHERE {
  ?h a roomify:Hotel ;
     roomify:name ?nama ;
     roomify:startPrice ?harga .
  BIND(STRAFTER(STR(?h), "#") AS ?id)
} LIMIT 400`;
            }
        }

        async function runPredefinedQuery(codeId, resultId) {
            const query = document.getElementById(codeId).innerText;
            const resEl = document.getElementById(resultId);
            resEl.classList.remove('hidden');
            resEl.innerHTML = '<span class="text-slate-400"><i class="fa-solid fa-spinner fa-spin mr-1"></i> Menjalankan query ke triplestore...</span>';

            try {
                const response = await fetch('/api/semantik/query', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ query })
                });
                const res = await response.json();

                if (!res.success) {
                    resEl.innerHTML = `<span class="text-rose-500 font-bold">${res.error || 'Terjadi kesalahan eksekusi'}</span>`;
                    return;
                }

                if (!res.data || res.data.length === 0) {
                    resEl.innerHTML = `<span class="text-slate-400">Tidak ada baris yang cocok (0 baris, ${res.executionMs} ms).</span>`;
                    return;
                }

                let html = `<div class="p-3 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
                    <div class="flex justify-between items-center mb-2 font-semibold text-slate-600 dark:text-slate-300">
                        <span>Hasil: ${res.count} baris</span>
                        <span class="text-emerald-600 font-mono">${res.executionMs} ms</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead><tr class="border-b border-slate-200 dark:border-slate-700">`;
                const cols = Object.keys(res.data[0]);
                cols.forEach(c => html += `<th class="py-1 px-2 font-bold">${c}</th>`);
                html += `</tr></thead><tbody>`;
                res.data.slice(0, 5).forEach(row => {
                    html += `<tr class="border-b border-slate-100 dark:border-slate-800/50">`;
                    cols.forEach(c => html += `<td class="py-1 px-2 font-mono text-[11px]">${row[c] !== null ? row[c] : '<span class="text-slate-400">unbound</span>'}</td>`);
                    html += `</tr>`;
                });
                html += `</tbody></table></div></div>`;
                resEl.innerHTML = html;
            } catch (err) {
                resEl.innerHTML = `<span class="text-rose-500">Error jaringan / endpoint tidak merespons.</span>`;
            }
        }

        async function runAskQuery() {
            const query = document.getElementById('ask-query-code').innerText;
            const resEl = document.getElementById('ask-result');
            resEl.classList.remove('hidden');
            resEl.innerHTML = '<span class="text-slate-400"><i class="fa-solid fa-spinner fa-spin mr-1"></i> Mengevaluasi graf triple...</span>';

            try {
                const response = await fetch('/api/semantik/query', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ query })
                });
                const res = await response.json();

                // If fallback or fuseki returns array/boolean
                let isTrue = false;
                if (res.data && res.data.length > 0) {
                    isTrue = true;
                } else if (res.boolean === true) {
                    isTrue = true;
                }

                if (isTrue) {
                    resEl.className = 'mt-4 p-3 rounded-xl border border-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-200 text-xs flex items-center justify-between';
                    resEl.innerHTML = `<span><i class="fa-solid fa-circle-check text-emerald-600 mr-1.5"></i> <strong>HASIL: TRUE</strong> &mdash; Triple valid dan terverifikasi ada dalam dataset Roomify.</span><span class="font-mono text-[10px] text-emerald-700">${res.executionMs} ms</span>`;
                } else {
                    resEl.className = 'mt-4 p-3 rounded-xl border border-amber-300 bg-amber-50 dark:bg-amber-950/60 text-amber-800 dark:text-amber-200 text-xs flex items-center justify-between';
                    resEl.innerHTML = `<span><i class="fa-solid fa-circle-xmark text-amber-600 mr-1.5"></i> <strong>HASIL: FALSE</strong> &mdash; Hotel ini tidak memiliki fasilitas yang dipilih pada basis data graf.</span><span class="font-mono text-[10px] text-amber-700">${res.executionMs} ms</span>`;
                }
            } catch (err) {
                resEl.innerHTML = `<span class="text-rose-500">Gagal mengevaluasi ASK.</span>`;
            }
        }

        async function executeCustomQuery() {
            const query = document.getElementById('custom-sparql-input').value.trim();
            const statusEl = document.getElementById('custom-status');
            const container = document.getElementById('custom-result-container');
            const table = document.getElementById('custom-result-table');

            if (!query) return;

            statusEl.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Menjalankan query...';

            try {
                const response = await fetch('/api/semantik/query', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ query })
                });
                const res = await response.json();

                if (!res.success) {
                    statusEl.innerHTML = `<span class="text-rose-500 font-bold">${res.error}</span>`;
                    container.classList.add('hidden');
                    return;
                }

                statusEl.innerHTML = `<span class="text-emerald-600 font-bold">${res.count} baris</span> dalam <span class="font-mono">${res.executionMs} ms</span>`;

                if (!res.data || res.data.length === 0) {
                    container.classList.add('hidden');
                    return;
                }

                container.classList.remove('hidden');
                const thead = table.querySelector('thead');
                const tbody = table.querySelector('tbody');

                const cols = Object.keys(res.data[0]);
                thead.innerHTML = '<tr>' + cols.map(c => `<th class="py-2.5 px-3 uppercase tracking-wider text-[11px]">${c}</th>`).join('') + '</tr>';

                tbody.innerHTML = res.data.slice(0, 25).map(row => {
                    return '<tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">' +
                           cols.map(c => `<td class="py-2 px-3 font-mono text-[11px]">${row[c] !== null ? row[c] : '<span class="text-slate-400 italic">null</span>'}</td>`).join('') +
                           '</tr>';
                }).join('');

            } catch (e) {
                statusEl.innerHTML = '<span class="text-rose-500">Error menghubungi endpoint triplestore.</span>';
            }
        }

        function clearCustomOutput() {
            document.getElementById('custom-result-container').classList.add('hidden');
            document.getElementById('custom-status').innerText = '';
        }

        function loadPreset(num) {
            const input = document.getElementById('custom-sparql-input');
            if (num === 1) {
                input.value = `PREFIX roomify: <https://roomify.example/ontology#>
SELECT ?nama ?harga ?rating ?alamat WHERE {
  ?h a roomify:Hotel ;
     roomify:name ?nama ;
     roomify:starRating 5 ;
     roomify:address ?alamat .
  OPTIONAL { ?h roomify:startPrice ?harga }
  OPTIONAL { ?h roomify:rating ?rating }
} ORDER BY ASC(?harga)`;
            } else if (num === 2) {
                input.value = `PREFIX roomify: <https://roomify.example/ontology#>
SELECT ?nama ?harga ?bintang WHERE {
  ?h a roomify:Hotel ;
     roomify:name ?nama ;
     roomify:nearLandmark roomify:lm-maimun .
  OPTIONAL { ?h roomify:startPrice ?harga }
  OPTIONAL { ?h roomify:starRating ?bintang }
} LIMIT 10`;
            } else if (num === 3) {
                input.value = `PREFIX roomify: <https://roomify.example/ontology#>
SELECT ?nama ?kat ?rating ?ulasan WHERE {
  ?lm a roomify:Landmark ;
      roomify:name ?nama ;
      roomify:category ?kat .
  OPTIONAL { ?lm roomify:rating ?rating }
  OPTIONAL { ?lm roomify:reviewCount ?ulasan }
} ORDER BY DESC(?ulasan)`;
            }
            executeCustomQuery();
        }

        function copyCode(elementId) {
            const text = document.getElementById(elementId).innerText;
            navigator.clipboard.writeText(text).then(() => {
                alert('SPARQL Query berhasil disalin ke clipboard!');
            });
        }

        function switchOntologyTab(tab) {
            const tabs = ['classes', 'obj-props', 'data-props'];
            tabs.forEach(t => {
                const btn = document.getElementById('tab-' + t);
                const content = document.getElementById('content-' + t);
                if (t === tab) {
                    btn.className = 'px-4 py-2 rounded-xl text-xs font-bold bg-blue-600 text-white transition';
                    content.classList.remove('hidden');
                } else {
                    btn.className = 'px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition';
                    content.classList.add('hidden');
                }
            });
        }
    </script>
</body>
</html>
