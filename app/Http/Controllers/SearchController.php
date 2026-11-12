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

        $b = new SparqlBuilder();
        $keep = [];
        foreach (preg_split('/\s+/', strtolower($q)) as $w) {
            if (isset(self::SYNONYMS[$w])) $b->facility(self::SYNONYMS[$w]);
            elseif ($w !== '') $keep[] = $w;
        }
        $b->keyword(implode(' ', $keep));

        if ($request->filled('max_price'))  $b->maxPrice((int) $request->input('max_price'));
        if ($request->filled('min_rating')) $b->minRating((float) $request->input('min_rating'));
        if ($request->filled('star'))       $b->minStar((int) $request->input('star'));
        if ($request->filled('rating'))     $b->minStar((int) $request->input('rating'));
        if ($request->filled('landmark'))   $b->landmark((string) $request->input('landmark'));
        foreach ((array) $request->input('facilities', []) as $f) {
            $b->facility('fac-' . \Illuminate\Support\Str::slug($f));
        }

        $rows = $this->fuseki->select($b->build(50));

        $p = 'PREFIX roomify: <https://roomify.example/ontology#>';
        $fac = $this->grouped("$p SELECT ?id (GROUP_CONCAT(DISTINCT ?n; separator='|') AS ?v) WHERE {
            ?h roomify:hasFacility ?f . ?f roomify:name ?n .
            BIND(STRAFTER(STR(?h), '#') AS ?id) } GROUP BY ?id");
        $lm = $this->grouped("$p SELECT ?id (GROUP_CONCAT(DISTINCT ?n; separator='|') AS ?v) WHERE {
            ?h roomify:nearLandmark ?l . ?l roomify:name ?n .
            BIND(STRAFTER(STR(?h), '#') AS ?id) } GROUP BY ?id");

        $hotels = array_map(fn ($r) => [
            'id'         => $r['id'],
            'nama'       => $r['nama'],
            'alamat'     => $r['alamat'],
            'bintang'    => $r['star'] ?? null,
            'starRating' => $r['star'] ?? null,
            'harga'      => (int) $r['harga'],
            'startPrice' => (int) $r['harga'],
            'rating'     => $r['rating'] ?? null,
            'foto'       => $r['foto'] ?? '/images/placeholder.svg',
            'latitude'   => $r['lat'] ?? null,
            'longitude'  => $r['lon'] ?? null,
            'fasilitas'  => $fac[$r['id']] ?? [],
            'landmarks'  => $lm[$r['id']] ?? [],
        ], $rows);

        $selectedLandmark = (string) $request->input('landmark', '');
        $landmarkInfo = null;
        if ($selectedLandmark) {
            foreach (FusekiClient::LANDMARKS as $lm) {
                if (str_contains(strtolower($lm['name']), strtolower($selectedLandmark)) || str_contains(strtolower($selectedLandmark), strtolower($lm['name']))) {
                    $landmarkInfo = $lm;
                    break;
                }
            }
        }

        return view('search.index', compact('hotels', 'q', 'query', 'selectedLandmark', 'landmarkInfo'));
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