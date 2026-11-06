<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Roomify - Cari Hotel Medan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen">
<div class="max-w-5xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-4">Roomify</h1>

    <form method="GET" action="/search" class="flex gap-2 mb-4">
        <input name="q" value="{{ $q }}" placeholder="cth: sakura / jalan yamin / kolam"
               class="flex-1 border rounded px-3 py-2">
        <input name="max_price" type="number" placeholder="Maks Rp" class="w-36 border rounded px-3 py-2">
        <button class="bg-red-600 text-white px-4 py-2 rounded">Cari</button>
    </form>

    @if($suggestions)
        <div class="bg-yellow-100 border border-yellow-300 rounded p-3 mb-4 text-sm">
            Mungkin yang kamu maksud:
            @foreach($suggestions as $s)
                <a class="underline text-blue-700" href="/search?q={{ urlencode($s) }}">{{ $s }}</a>@if(!$loop->last), @endif
            @endforeach
        </div>
    @endif

    <p class="text-sm text-slate-600 mb-3">{{ count($hotels) }} hotel ditemukan</p>

    <div class="grid md:grid-cols-2 gap-4">
        @foreach($hotels as $h)
            <div class="bg-white rounded shadow p-4 flex gap-4">
                <img src="{{ $h['foto'] ?? '/images/placeholder.svg' }}" class="w-28 h-24 object-cover rounded">
                <div>
                    <h2 class="font-semibold">{{ $h['nama'] }}</h2>
                    <p class="text-sm text-slate-500">{{ $h['alamat'] }}</p>
                    <p class="text-sm">Rating {{ $h['rating'] ?? '-' }} | Bintang {{ $h['star'] ?? '-' }}</p>
                    <p class="text-red-600 font-bold">Rp {{ number_format((int) $h['harga'], 0, ',', '.') }}</p>
                    {{-- link detail aktif setelah S4 --}}
                    <span class="text-xs text-slate-400">id: {{ $h['id'] }}</span>
                </div>
            </div>
        @endforeach
    </div>
</div>
</body>
</html>
