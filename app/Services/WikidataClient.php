<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WikidataClient
{
    protected string $wikidata = 'https://query.wikidata.org/sparql';
    protected string $dbpedia  = 'https://dbpedia.org/sparql';

    protected array $cadangan = [
        ['nama' => 'Istana Maimun', 'lat' => '3.5793', 'lon' => '98.6820'],
        ['nama' => 'Masjid Raya Al Mashun', 'lat' => '3.5786', 'lon' => '98.6812'],
        ['nama' => 'Gedung Tjong A Fie', 'lat' => '3.5920', 'lon' => '98.6755'],
        ['nama' => 'Lapangan Merdeka Medan', 'lat' => '3.5928', 'lon' => '98.6748'],
        ['nama' => 'Stasiun Medan', 'lat' => '3.5932', 'lon' => '98.6723'],
    ];

    public function landmarksNear(float $lat, float $lon, int $radiusKm = 3): array
    {
        $d = $radiusKm * 0.01;
        $box = [$lat - $d, $lat + $d, $lon - $d, $lon + $d];

        $data = $this->parse($this->raw($this->wikidata, $this->qWikidata($box)));
        if ($data) return $this->src($data, 'wikidata');

        $data = $this->parse($this->raw($this->dbpedia, $this->qDbpedia($box)));
        if ($data) return $this->src($data, 'dbpedia');

        return $this->src($this->cadangan, 'cadangan');
    }

    public function queryRingan(): array
    {
        $w = 'PREFIX wd: <http://www.wikidata.org/entity/>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX wdt: <http://www.wikidata.org/prop/direct/>
PREFIX geof: <http://www.opengis.net/def/function/geosparql/>
SELECT ?nama ?lat ?lon WHERE {
  wd:Q7411 rdfs:label ?nama ; wdt:P625 ?coord .
  FILTER(LANG(?nama) = "id")
  BIND(geof:latitude(?coord) AS ?lat)
  BIND(geof:longitude(?coord) AS ?lon)
}';
        $data = $this->parse($this->raw($this->wikidata, $w));
        if ($data) return $this->src($data, 'wikidata');

        $d = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX geo: <http://www.w3.org/2003/01/geo/wgs84_pos#>
SELECT ?nama ?lat ?lon WHERE {
  <http://dbpedia.org/resource/Medan> rdfs:label ?nama ; geo:lat ?lat ; geo:long ?lon .
  FILTER(LANG(?nama) = "en")
} LIMIT 5';
        $data = $this->parse($this->raw($this->dbpedia, $d));
        if ($data) return $this->src($data, 'dbpedia');

        return $this->src($this->cadangan, 'cadangan');
    }

    public function debug(float $lat, float $lon, int $radiusKm = 3)
    {
        $d = $radiusKm * 0.01;
        return $this->raw($this->wikidata, $this->qWikidata([$lat - $d, $lat + $d, $lon - $d, $lon + $d]));
    }

    protected function qWikidata(array $b): string
    {
        [$minLat, $maxLat, $minLon, $maxLon] = $b;
        return "PREFIX wd: <http://www.wikidata.org/entity/>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX wdt: <http://www.wikidata.org/prop/direct/>
PREFIX geof: <http://www.opengis.net/def/function/geosparql/>
SELECT DISTINCT ?nama ?lat ?lon WHERE {
  VALUES ?tipe { wd:Q16560 wd:Q32815 wd:Q33506 wd:Q16970 wd:Q22698 wd:Q174782 wd:Q55488 wd:Q11315 }
  ?place wdt:P31/wdt:P279* ?tipe ;
         wdt:P625 ?coord ;
         rdfs:label ?nama .
  FILTER(LANG(?nama) = 'id')
  BIND(geof:latitude(?coord) AS ?lat)
  BIND(geof:longitude(?coord) AS ?lon)
  FILTER(?lat > {$minLat} && ?lat < {$maxLat} && ?lon > {$minLon} && ?lon < {$maxLon})
}
LIMIT 15";
    }

    protected function qDbpedia(array $b): string
    {
        [$minLat, $maxLat, $minLon, $maxLon] = $b;
        return "PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX geo: <http://www.w3.org/2003/01/geo/wgs84_pos#>
SELECT DISTINCT ?nama ?lat ?lon WHERE {
  ?place rdfs:label ?nama ;
         geo:lat ?lat ;
         geo:long ?lon .
  FILTER(?lat > {$minLat} && ?lat < {$maxLat} && ?lon > {$minLon} && ?lon < {$maxLon})
  FILTER(LANG(?nama) = 'en')
}
LIMIT 15";
    }

    protected function parse($res): array
    {
        if (! $res || $res->failed()) return [];

        return collect($res->json()['results']['bindings'] ?? [])
            ->map(fn ($r) => [
                'nama' => $r['nama']['value'] ?? null,
                'lat'  => $r['lat']['value'] ?? null,
                'lon'  => $r['lon']['value'] ?? null,
            ])
            ->filter(fn ($d) => $d['nama'])
            ->values()
            ->all();
    }

    protected function src(array $rows, string $sumber): array
    {
        return array_map(fn ($r) => $r + ['sumber' => $sumber], $rows);
    }

    protected function raw(string $endpoint, string $sparql)
    {
        try {
            return Http::withHeaders([
                'Accept'     => 'application/sparql-results+json',
                'User-Agent' => 'Roomify-Tubes/1.0 (proyek kuliah)',
            ])->timeout(20)->get($endpoint, ['query' => $sparql, 'format' => 'json']);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
