<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WikidataClient
{
    protected string $endpoint = 'https://query.wikidata.org/sparql';

    public function landmarksNear(float $lat, float $lon, int $radiusKm = 3): array
    {
        $res = $this->raw($this->buildAroundQuery($lat, $lon, $radiusKm));

        if ($res->failed()) return [];

        return collect($res->json()['results']['bindings'] ?? [])
            ->map(fn($r) => [
                'nama' => $r['nama']['value'] ?? null,
                'lat'  => $r['lat']['value'] ?? null,
                'lon'  => $r['lon']['value'] ?? null,
            ])->all();
    }

    /** Buat debugging: balikin respons mentah dari Wikidata */
    public function debug(float $lat, float $lon, int $radiusKm = 3)
    {
        return $this->raw($this->buildAroundQuery($lat, $lon, $radiusKm));
    }

    protected function buildAroundQuery(float $lat, float $lon, int $radiusKm): string
    {
        return "PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX wdt: <http://www.wikidata.org/prop/direct/>
PREFIX geo: <http://www.opengis.net/ont/geosparql#>
PREFIX geof: <http://www.opengis.net/def/function/geosparql/>
PREFIX wikibase: <http://wikiba.se/ontology#>
PREFIX bd: <http://www.bigdata.com/rdf#>
SELECT ?nama ?lat ?lon WHERE {
  SERVICE wikibase:around {
    ?place wdt:P625 ?coord .
    bd:serviceParam wikibase:center \"Point($lon $lat)\"^^geo:wktLiteral .
    bd:serviceParam wikibase:radius \"$radiusKm\" .
  }
  ?place rdfs:label ?nama .
  FILTER(LANG(?nama) = \"id\")
  BIND(geof:latitude(?coord) AS ?lat)
  BIND(geof:longitude(?coord) AS ?lon)
} LIMIT 15";
    }

    protected function raw(string $sparql)
    {
        return Http::withHeaders([
            'Accept' => 'application/sparql-results+json',
            'User-Agent' => 'Roomify-Tubes/1.0 (proyek kuliah)',
        ])->timeout(30)->get($this->endpoint, ['query' => $sparql, 'format' => 'json']);
    }
}
