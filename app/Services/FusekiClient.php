<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FusekiClient
{
    protected string $endpoint;

    public function __construct()
    {
        $this->endpoint = env('FUSEKI_ENDPOINT', 'http://localhost:3030/Roomify/query');
    }

    public function select(string $sparql): array
    {
        $res = Http::withHeaders([
            'Accept' => 'application/sparql-results+json',
        ])->timeout(30)->get($this->endpoint, ['query' => $sparql]);

        if ($res->failed()) {
            return [];
        }

        return collect($res->json()['results']['bindings'] ?? [])
            ->map(function ($row) {
                $clean = [];
                foreach ($row as $key => $cell) {
                    $clean[$key] = $cell['value'] ?? null;
                }
                return $clean;
            })
            ->all();
    }
}
