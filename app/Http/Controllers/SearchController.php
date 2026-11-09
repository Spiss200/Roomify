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
            'fasilitas'  => $fac[$r['id']] ?? [],
            'landmarks'  => $lm[$r['id']] ?? [],
        ], $rows);

        return view('search.index', compact('hotels', 'q', 'query'));
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