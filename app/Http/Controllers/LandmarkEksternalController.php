<?php

namespace App\Http\Controllers;

use App\Services\FusekiClient;
use Illuminate\Support\Facades\Http;

class LandmarkEksternalController extends Controller
{
    public function __construct(protected FusekiClient $fuseki) {}

    public function show(string $id)
    {
        $id = preg_replace('/[^a-zA-Z0-9\-_]/', '', $id);
        $p  = 'PREFIX roomify: <https://roomify.example/ontology#>';

        $rows = $this->fuseki->select("$p
            SELECT ?nama ?lat ?lon WHERE {
              roomify:$id roomify:name ?nama ;
                          roomify:latitude ?lat ;
                          roomify:longitude ?lon .
            } LIMIT 1");

        $h    = $rows[0] ?? null;
        $lat  = (float) ($h['lat'] ?? 3.5952);
        $lon  = (float) ($h['lon'] ?? 98.6722);
        $nama = $h['nama'] ?? 'Kota Medan';

        $items  = $this->dbpedia($lat, $lon);
        $sumber = 'dbpedia';

        if (!$items) {
            $items  = $this->lokal();
            $sumber = 'local-fuseki';
        }

        foreach ($items as &$it) {
            $it['jarak_km'] = round($this->hav($lat, $lon, (float) $it['lat'], (float) $it['lon']), 2);
        }
        unset($it);

        usort($items, fn($a, $b) => $a['jarak_km'] <=> $b['jarak_km']);

        return response()->json([
            'hotel'    => $nama,
            'sumber'   => $sumber,
            'endpoint' => $sumber === 'dbpedia'
                ? 'https://dbpedia.org/sparql'
                : 'http://localhost:3030/Roomify/query',
            'items'    => array_slice($items, 0, 6),
        ]);
    }

    protected function dbpedia(float $lat, float $lon): array
    {
        $d = 0.045;
        $q = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX geo: <http://www.w3.org/2003/01/geo/wgs84_pos#>
PREFIX dbo: <http://dbpedia.org/ontology/>
SELECT DISTINCT ?nama ?lat ?lon WHERE {
  ?place geo:lat ?lat ; geo:long ?lon ; rdfs:label ?nama .
  { ?place a dbo:TouristAttraction } UNION { ?place a dbo:Museum }
  UNION { ?place a dbo:HistoricBuilding } UNION { ?place a dbo:ReligiousBuilding }
  UNION { ?place a dbo:Park } UNION { ?place a dbo:Building }
  FILTER(?lat > ' . ($lat - $d) . ' && ?lat < ' . ($lat + $d) .
        ' && ?lon > ' . ($lon - $d) . ' && ?lon < ' . ($lon + $d) . ')
  FILTER(LANG(?nama) = "id" || LANG(?nama) = "en")
} LIMIT 15';

        try {
            $res = Http::withHeaders([
                    'Accept'     => 'application/sparql-results+json',
                    'User-Agent' => 'Roomify-Tubes/1.0 (proyek kuliah web semantik)',
                ])
                ->connectTimeout(3)->timeout(6)
                ->get('https://dbpedia.org/sparql', ['query' => $q, 'format' => 'json']);

            if ($res->failed()) return [];

            return array_map(fn($r) => [
                'nama' => $r['nama']['value'] ?? '',
                'lat'  => $r['lat']['value'] ?? 0,
                'lon'  => $r['lon']['value'] ?? 0,
            ], $res->json()['results']['bindings'] ?? []);
        } catch (\Throwable $e) {
            return [];
        }
    }

    protected function lokal(): array
    {
        $p = 'PREFIX roomify: <https://roomify.example/ontology#>';
        return array_map(fn($r) => [
            'nama' => $r['nama'], 'lat' => $r['lat'], 'lon' => $r['lon'],
        ], $this->fuseki->select("$p
            SELECT ?nama ?lat ?lon WHERE {
              ?lm a roomify:Landmark ; roomify:name ?nama ;
                  roomify:latitude ?lat ; roomify:longitude ?lon .
            }"));
    }

    protected function hav(float $a, float $b, float $c, float $d): float
    {
        $R = 6371; $p = M_PI / 180;
        $x = sin(($c - $a) * $p / 2) ** 2
           + cos($a * $p) * cos($c * $p) * sin(($d - $b) * $p / 2) ** 2;
        return $R * 2 * atan2(sqrt($x), sqrt(1 - $x));
    }
}
