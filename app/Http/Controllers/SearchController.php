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
        $q = $request->query('q', '');
        $b = new SparqlBuilder();
        $b->keyword($this->extractSynonyms($q, $b));

        if ($request->filled('max_price'))  $b->maxPrice((int) $request->query('max_price'));
        if ($request->filled('min_rating')) $b->minRating((float) $request->query('min_rating'));
        if ($request->filled('star'))       $b->minStar((int) $request->query('star'));

        $hotels = $this->fuseki->select($b->build());
        $suggestions = (count($hotels) === 0 && $q !== '') ? $this->fuzzy($q) : [];

        return view('search.index', compact('hotels', 'q', 'suggestions'));
    }

    /** Kata sinonim fasilitas dipisah jadi filter, sisanya jadi kata kunci bebas */
    protected function extractSynonyms(string $q, SparqlBuilder $b): string
    {
        $keep = [];
        foreach (preg_split('/\s+/', strtolower($q)) as $w) {
            if (isset(self::SYNONYMS[$w])) $b->facility(self::SYNONYMS[$w]);
            elseif ($w !== '') $keep[] = $w;
        }
        return implode(' ', $keep);
    }

    /** Endpoint autocomplete buat searchbar */
    public function suggest(Request $request)
    {
        $q = strtolower(trim($request->query('q', '')));
        if (mb_strlen($q) < 3) return response()->json([]);
        return response()->json($this->matchNames($q, 5));
    }

    protected function fuzzy(string $q): array
    {
        return $this->matchNames(strtolower($q), 5);
    }

    protected function matchNames(string $q, int $limit): array
    {
        $rows = $this->fuseki->select(
            'PREFIX roomify: <https://roomify.example/ontology#>
             SELECT ?nama WHERE { ?h a roomify:Hotel ; roomify:name ?nama } LIMIT 300'
        );
        $out = [];
        foreach ($rows as $r) {
            $name = $r['nama'] ?? '';
            $low = mb_strtolower($name);
            $hit = str_contains($low, $q);
            if (!$hit) {
                foreach (preg_split('/\s+/', $low) as $word) {
                    if (mb_strlen($word) >= 4 && levenshtein($q, $word) <= 2) { $hit = true; break; }
                }
            }
            if ($hit) $out[] = $name;
            if (count($out) >= $limit) break;
        }
        return $out;
    }
}
