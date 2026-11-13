<?php

namespace App\Http\Controllers;

use App\Services\FusekiClient;
use App\Services\SparqlBuilder;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    const SYNONYMS = [
        'kolam' => 'fac-kolam-renang', 'pool' => 'fac-kolam-renang',
        'sarapan' => 'fac-sarapan', 'breakfast' => 'fac-sarapan',
        'gym' => 'fac-gym', 'fitness' => 'fac-gym',
        'parkir' => 'fac-parkir', 'wifi' => 'fac-wifi',
    ];

    public function __construct(protected FusekiClient $fuseki) {}

    public function index(Request $request)
    {
        $q = (string) $request->input('q', '');
        $query = $q;
        $sort = (string) $request->input('sort', 'relevansi');

        $b = new SparqlBuilder();
        $b->sort($sort);

        // Smart Natural Language & Significant Terms extraction
        $extracted = $this->extractTermsAndFilters($q);
        foreach ($extracted['facilities'] as $facSlug) {
            $b->facility($facSlug);
        }
        if (!empty($extracted['landmark']) && !$request->filled('landmark')) {
            $request->merge(['landmark' => $extracted['landmark']]);
        }
        if (!empty($extracted['terms'])) {
            $b->terms($extracted['terms']);
        } elseif ($q !== '') {
            $b->keyword($q);
        }

        if ($request->filled('max_price'))  $b->maxPrice((int) $request->input('max_price'));
        if ($request->filled('min_rating')) $b->minRating((float) $request->input('min_rating'));
        if ($request->filled('star'))       $b->minStar((int) $request->input('star'));
        if ($request->filled('rating'))     $b->minStar((int) $request->input('rating'));
        if ($request->filled('landmark'))   $b->landmark((string) $request->input('landmark'));
        foreach ((array) $request->input('facilities', []) as $f) {
            $b->facility('fac-' . \Illuminate\Support\Str::slug($f));
        }

        // Retrieve up to 200 hotels (entire Medan database of 187 hotels)
        $rows = $this->fuseki->select($b->build(200));

        $p = 'PREFIX roomify: <https://roomify.example/ontology#>';
        $fac = $this->grouped("$p SELECT ?id (GROUP_CONCAT(DISTINCT ?n; separator='|') AS ?v) WHERE {
            ?h roomify:hasFacility ?f . ?f roomify:name ?n .
            BIND(STRAFTER(STR(?h), '#') AS ?id) } GROUP BY ?id");
        $lm = $this->grouped("$p SELECT ?id (GROUP_CONCAT(DISTINCT ?n; separator='|') AS ?v) WHERE {
            ?h roomify:nearLandmark ?l . ?l roomify:name ?n .
            BIND(STRAFTER(STR(?h), '#') AS ?id) } GROUP BY ?id");

        $userLat = $request->filled('user_lat') ? (float)$request->input('user_lat') : null;
        $userLng = $request->filled('user_lng') ? (float)$request->input('user_lng') : null;

        $hotels = array_map(function ($r) use ($fac, $lm, $userLat, $userLng) {
            $lat = $r['lat'] ?? null;
            $lon = $r['lon'] ?? null;
            $distanceKm = null;
            $distFormatted = null;

            if ($userLat && $userLng && $lat && $lon) {
                $distanceKm = $this->haversineKm($userLat, $userLng, (float)$lat, (float)$lon);
                $distFormatted = $distanceKm < 1 
                    ? round($distanceKm * 1000) . ' m' 
                    : number_format($distanceKm, 1, ',', '.') . ' km';
            }

            return [
                'id'         => $r['id'],
                'nama'       => $r['nama'],
                'alamat'     => $r['alamat'],
                'bintang'    => $r['star'] ?? null,
                'starRating' => $r['star'] ?? null,
                'harga'      => (int) $r['harga'],
                'startPrice' => (int) $r['harga'],
                'rating'     => $r['rating'] ?? null,
                'foto'       => $r['foto'] ?? '/images/placeholder.svg',
                'latitude'   => $lat,
                'longitude'  => $lon,
                'fasilitas'  => $fac[$r['id']] ?? [],
                'landmarks'  => $lm[$r['id']] ?? [],
                'jarak_km'   => $distanceKm,
                'jarak'      => $distFormatted,
            ];
        }, $rows);

        // If user requested sorting by nearest distance
        if ($sort === 'terdekat' && $userLat && $userLng) {
            usort($hotels, function ($a, $b) {
                if ($a['jarak_km'] === null) return 1;
                if ($b['jarak_km'] === null) return -1;
                return $a['jarak_km'] <=> $b['jarak_km'];
            });
        }

        $selectedLandmark = (string) $request->input('landmark', '');
        $landmarkInfo = null;
        if ($selectedLandmark) {
            foreach (FusekiClient::LANDMARKS as $lmItem) {
                if (str_contains(strtolower($lmItem['name']), strtolower($selectedLandmark)) || str_contains(strtolower($selectedLandmark), strtolower($lmItem['name']))) {
                    $landmarkInfo = $lmItem;
                    break;
                }
            }
        }

        return view('search.index', compact('hotels', 'q', 'query', 'selectedLandmark', 'landmarkInfo', 'userLat', 'userLng', 'sort'));
    }

    protected function extractTermsAndFilters(string $q): array
    {
        $clean = mb_strtolower(trim($q));
        if ($clean === '') {
            return ['terms' => [], 'facilities' => [], 'landmark' => null];
        }

        $facilities = [];
        $landmark = null;

        // Check for landmark in query
        foreach (FusekiClient::LANDMARKS as $lm) {
            if (str_contains($clean, strtolower($lm['name']))) {
                $landmark = $lm['name'];
                $clean = str_replace(strtolower($lm['name']), '', $clean);
                break;
            }
        }

        $stopWords = [
            'hotel', 'penginapan', 'villa', 'resort', 'homestay', 'kost', 'lodging',
            'yang', 'itu', 'ini', 'di', 'jalan', 'jl', 'jln', 'daerah', 'kawasan',
            'sekitar', 'dekat', 'ada', 'mau', 'cari', 'carikan', 'rekomendasi',
            'mana', 'nih', 'dong', 'ke', 'kota', 'medan', 'pada', 'untuk', 'dengan',
            'bagus', 'murah', 'mewah', 'paling', 'tolong'
        ];

        $tokens = preg_split('/[\s,]+/', $clean, -1, PREG_SPLIT_NO_EMPTY);
        $terms = [];

        foreach ($tokens as $token) {
            $w = trim($token, ".,!?'\"()[]");
            if ($w === '') continue;

            if (isset(self::SYNONYMS[$w])) {
                $facilities[] = self::SYNONYMS[$w];
                continue;
            }

            if (!in_array($w, $stopWords)) {
                $terms[] = $w;
            }
        }

        return [
            'terms'      => $terms,
            'facilities' => array_unique($facilities),
            'landmark'   => $landmark,
        ];
    }

    protected function haversineKm(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    public function suggest(Request $request)
    {
        $q = mb_strtolower(trim((string) $request->query('q', '')));
        if (mb_strlen($q) < 3) return response()->json([]);

        $rows = $this->fuseki->select(
            'PREFIX roomify: <https://roomify.example/ontology#>
             SELECT ?nama WHERE { ?h a roomify:Hotel ; roomify:name ?nama }'
        );

        $out = [];
        foreach ($rows as $r) {
            if (str_contains(mb_strtolower($r['nama']), $q)) $out[] = $r['nama'];
            if (count($out) >= 5) break;
        }
        return response()->json($out);
    }

    protected function grouped(string $sparql): array
    {
        $out = [];
        foreach ($this->fuseki->select($sparql) as $r) {
            $out[$r['id']] = array_filter(explode('|', $r['v'] ?? ''));
        }
        return $out;
    }
}