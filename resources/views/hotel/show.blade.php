<!doctype html>
<html lang="id" prefix="schema: https://schema.org/">
<head>
    <meta charset="utf-8">
    <title>{{ $hotel['nama'] }} — Roomify</title>

    {{-- Open Graph Protocol (syarat PDF) --}}
    <meta property="og:title" content="{{ $hotel['nama'] }}">
    <meta property="og:description" content="{{ $hotel['deskripsi'] ?? $hotel['alamat'] }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ url($hotel['foto'] ?? '/images/placeholder.svg') }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="bg-slate-100">
<div class="max-w-5xl mx-auto p-6" typeof="schema:Hotel" about="https://roomify.example/ontology#{{ $id }}">

    <h1 class="text-3xl font-bold" property="schema:name">{{ $hotel['nama'] }}</h1>
    <p class="text-slate-600" property="schema:address">{{ $hotel['alamat'] }}</p>
    <p class="text-sm mt-1">
        Bintang <span property="schema:starRating">{{ $hotel['bintang'] ?? '-' }}</span> ·
        Rating <span property="schema:ratingValue">{{ $hotel['rating'] ?? '-' }}</span>
        (<span property="schema:reviewCount">{{ $hotel['jumlahReview'] ?? 0 }}</span> ulasan)
    </p>
    <p class="text-red-600 font-bold text-xl mt-2">
        Mulai Rp {{ number_format((int) $hotel['harga'], 0, ',', '.') }}
    </p>

    <img src="{{ $hotel['foto'] ?? '/images/placeholder.svg' }}"
         class="rounded w-full h-72 object-cover my-4" property="schema:image">

    <h2 class="font-semibold mt-4">Fasilitas</h2>
    <ul class="flex flex-wrap gap-2 mt-2">
        @forelse ($fasilitas as $f)
            <li class="bg-white border rounded px-3 py-1 text-sm">{{ $f }}</li>
        @empty
            <li class="text-slate-500 text-sm">-</li>
        @endforelse
    </ul>

    <h2 class="font-semibold mt-6">Tipe Kamar</h2>
    <table class="w-full bg-white rounded overflow-hidden mt-2 text-sm">
        <tr class="text-left bg-slate-200">
            <th class="p-2">Tipe</th><th class="p-2">Harga/malam</th><th class="p-2">Kapasitas</th><th class="p-2"></th>
        </tr>
        @foreach ($kamar as $k)
            <tr class="border-t">
                <td class="p-2">{{ $k['tipe'] }}</td>
                <td class="p-2">Rp {{ number_format((int) $k['harga'], 0, ',', '.') }}</td>
                <td class="p-2">{{ $k['kapasitas'] }} orang</td>
                <td class="p-2"><span class="text-slate-400">(booking menyusul)</span></td>
            </tr>
        @endforeach
    </table>

    <h2 class="font-semibold mt-6">Lokasi</h2>
    <div id="peta" class="h-64 rounded mt-2"></div>

    <h2 class="font-semibold mt-6">Tempat menarik di sekitar</h2>
    <ul class="mt-2 space-y-1 text-sm">
        @forelse ($landmark as $l)
            <li>📍 {{ $l['nama'] }} <span class="text-slate-400">(sumber: {{ $l['sumber'] }})</span></li>
        @empty
            <li class="text-slate-500">-</li>
        @endforelse
    </ul>
</div>

<script>
    const lat = {{ $hotel['lat'] ?? 3.5952 }};
    const lon = {{ $hotel['lon'] ?? 98.6722 }};
    const peta = L.map('peta').setView([lat, lon], 15);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(peta);
    L.marker([lat, lon]).addTo(peta).bindPopup(@json($hotel['nama'])).openPopup();
</script>
</body>
</html>
