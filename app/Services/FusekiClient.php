<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FusekiClient
{
    protected string $endpoint;
    protected ?array $dataset = null;
    protected static ?bool $fusekiOnline = null;

    const LANDMARKS = [
        ['id' => 'lm-maimun', 'name' => 'Istana Maimun', 'lat' => 3.5793, 'lon' => 98.6820],
        ['id' => 'lm-tjong', 'name' => 'Tjong A Fie Mansion', 'lat' => 3.5920, 'lon' => 98.6755],
        ['id' => 'lm-mashun', 'name' => 'Masjid Raya Al Mashun', 'lat' => 3.5786, 'lon' => 98.6812],
        ['id' => 'lm-merdeka', 'name' => 'Lapangan Merdeka', 'lat' => 3.5928, 'lon' => 98.6748],
        ['id' => 'lm-velangkanni', 'name' => 'Graha Maria Annai Velangkanni', 'lat' => 3.5536, 'lon' => 98.6229],
        ['id' => 'lm-sunplaza', 'name' => 'Sun Plaza', 'lat' => 3.5857, 'lon' => 98.6722],
        ['id' => 'lm-rahmat', 'name' => 'Museum Rahmat', 'lat' => 3.5775, 'lon' => 98.6623],
        ['id' => 'lm-maitreya', 'name' => 'Maha Vihara Maitreya', 'lat' => 3.6264, 'lon' => 98.7028],
        ['id' => 'lm-merdekawalk', 'name' => 'Merdeka Walk', 'lat' => 3.5932, 'lon' => 98.6758],
        ['id' => 'lm-menaraair', 'name' => 'Menara Air Tirtanadi', 'lat' => 3.5802, 'lon' => 98.6836],
        ['id' => 'lm-centrepoint', 'name' => 'Centre Point Mall', 'lat' => 3.5915, 'lon' => 98.6789],
        ['id' => 'lm-medanzoo', 'name' => 'Kebun Binatang Medan', 'lat' => 3.5042, 'lon' => 98.6644],
        ['id' => 'lm-shrimariamman', 'name' => 'Kuil Shri Mariamman', 'lat' => 3.5859, 'lon' => 98.6717],
    ];

    public function __construct()
    {
        $this->endpoint = env('FUSEKI_ENDPOINT', 'http://localhost:3030/Roomify/query');
    }

    public function select(string $sparql): array
    {
        if (static::$fusekiOnline !== false) {
            try {
                $res = Http::withHeaders([
                    'Accept' => 'application/sparql-results+json',
                ])->connectTimeout(0.35)->timeout(1.2)->get($this->endpoint, ['query' => $sparql]);

                if ($res->successful()) {
                    static::$fusekiOnline = true;
                    return collect($res->json()['results']['bindings'] ?? [])
                        ->map(fn ($row) => array_map(fn ($cell) => $cell['value'] ?? null, $row))
                        ->all();
                }
                static::$fusekiOnline = false;
            } catch (\Throwable $e) {
                static::$fusekiOnline = false;
            }
        }

        if (! env('FUSEKI_FALLBACK', true)) {
            return [];
        }

        \Illuminate\Support\Facades\Log::warning('Fuseki tidak tersedia, memakai fallback hotels_complete.json');
        return $this->fallback($sparql);
    }

    protected function loadDataset(): array
    {
        if ($this->dataset !== null) {
            return $this->dataset;
        }

        $path = base_path('semantic/scrape/hotels_complete.json');
        if (file_exists($path)) {
            $raw = json_decode(file_get_contents($path), true) ?: [];
            $this->dataset = $raw;
        } else {
            $this->dataset = [];
        }

        return $this->dataset;
    }

    protected function fallback(string $sparql): array
    {
        $data = $this->loadDataset();
        if (empty($data)) return [];

        // 1. Grouped facilities query
        if (str_contains($sparql, 'hasFacility ?f') && str_contains($sparql, 'GROUP_CONCAT')) {
            $out = [];
            foreach ($data as $h) {
                $facs = $h['facilities'] ?? [];
                $out[] = [
                    'id' => $h['id'],
                    'v'  => implode('|', $facs),
                ];
            }
            return $out;
        }

        // 2. Grouped landmarks query
        if (str_contains($sparql, 'nearLandmark ?l') && str_contains($sparql, 'GROUP_CONCAT')) {
            $out = [];
            foreach ($data as $h) {
                $lm = $this->closestLandmark($h['latitude'] ?? null, $h['longitude'] ?? null);
                $out[] = [
                    'id' => $h['id'],
                    'v'  => $lm['name'] ?? 'Pusat Kota Medan',
                ];
            }
            return $out;
        }

        // 3. Single hotel detail query
        if (preg_match('/roomify:(hotel-[0-9a-zA-Z_-]+)\s+a\s+roomify:Hotel/i', $sparql, $m)) {
            $targetId = $m[1];
            foreach ($data as $h) {
                if ($h['id'] === $targetId) {
                    return [[
                        'nama'         => $h['name'],
                        'alamat'       => $h['address'],
                        'deskripsi'    => $h['description'] ?? null,
                        'bintang'      => isset($h['star']) ? (string)$h['star'] : null,
                        'rating'       => isset($h['rating']) ? (string)$h['rating'] : null,
                        'jumlahReview' => isset($h['review_count']) ? (string)$h['review_count'] : null,
                        'harga'        => (string)($h['start_price'] ?? 0),
                        'lat'          => isset($h['latitude']) ? (string)$h['latitude'] : null,
                        'lon'          => isset($h['longitude']) ? (string)$h['longitude'] : null,
                        'foto'         => $h['image_url'] ?? '/images/placeholder.svg',
                    ]];
                }
            }
            return [];
        }

        // 4. Single hotel rooms query
        if (preg_match('/roomify:(hotel-[0-9a-zA-Z_-]+)\s+roomify:hasRoom/i', $sparql, $m)) {
            $targetId = $m[1];
            foreach ($data as $h) {
                if ($h['id'] === $targetId) {
                    $rooms = [];
                    foreach ($h['rooms'] ?? [] as $r) {
                        $rooms[] = [
                            'tipe'      => $r['room_type'] ?? 'Standard',
                            'harga'     => (string)($r['price'] ?? $h['start_price'] ?? 0),
                            'kapasitas' => (string)($r['max_guests'] ?? 2),
                        ];
                    }
                    return $rooms;
                }
            }
            return [];
        }

        // 5. Single hotel facilities query
        if (preg_match('/roomify:(hotel-[0-9a-zA-Z_-]+)\s+roomify:hasFacility/i', $sparql, $m)) {
            $targetId = $m[1];
            foreach ($data as $h) {
                if ($h['id'] === $targetId) {
                    $facs = [];
                    foreach ($h['facilities'] ?? [] as $f) {
                        $facs[] = ['namaFasilitas' => $f];
                    }
                    return $facs;
                }
            }
            return [];
        }

        // 6. Suggest autocomplete query
        if (str_contains($sparql, 'SELECT ?nama WHERE')) {
            $out = [];
            foreach ($data as $h) {
                $out[] = ['nama' => $h['name']];
            }
            return $out;
        }

        // 7. Search query
        $filtered = $data;

        // Keyword filter (supports single or multiple significant terms)
        if (preg_match_all('/CONTAINS\(LCASE\(\?nama\),\s*\'([^\']+)\'\)/i', $sparql, $kmAll) && !empty($kmAll[1])) {
            $terms = array_map('strtolower', $kmAll[1]);
            $filtered = array_filter($filtered, function ($h) use ($terms) {
                $haystack = strtolower(
                    ($h['name'] ?? '') . ' ' .
                    ($h['address'] ?? '') . ' ' .
                    ($h['description'] ?? '')
                );
                foreach ($terms as $t) {
                    if (!str_contains($haystack, $t)) {
                        // try without dot (e.g. hm vs h.m)
                        $tNoDot = str_replace('.', '', $t);
                        $haystackNoDot = str_replace('.', '', $haystack);
                        if (!str_contains($haystackNoDot, $tNoDot)) {
                            return false;
                        }
                    }
                }
                return true;
            });
        }

        // Min price filter
        if (preg_match('/FILTER\(\?harga\s*>=\s*([0-9]+)\)/i', $sparql, $mp)) {
            $minPrice = (int) $mp[1];
            $filtered = array_filter($filtered, fn ($h) => (int) ($h['start_price'] ?? 0) >= $minPrice);
        }

        // Max price filter
        if (preg_match('/FILTER\(\?harga\s*<=\s*([0-9]+)\)/i', $sparql, $pm)) {
            $maxPrice = (int)$pm[1];
            $filtered = array_filter($filtered, function ($h) use ($maxPrice) {
                return (int)($h['start_price'] ?? 0) <= $maxPrice;
            });
        }

        // Star filter
        if (preg_match('/FILTER\(\?star\s*>=\s*([0-9]+)\)/i', $sparql, $sm)) {
            $star = (int)$sm[1];
            $filtered = array_filter($filtered, function ($h) use ($star) {
                return (int)($h['star'] ?? 0) >= $star;
            });
        }

        // Rating filter
        if (preg_match('/FILTER\(\?rating\s*>=\s*([0-9.]+)\)/i', $sparql, $rm)) {
            $rating = (float)$rm[1];
            $filtered = array_filter($filtered, function ($h) use ($rating) {
                return (float)($h['rating'] ?? 0) >= $rating;
            });
        }

        // Landmark filter
        if (preg_match('/roomify:name\s+"([^"]+)"/i', $sparql, $lmMatch)) {
            $lmName = strtolower(trim($lmMatch[1]));
            $filtered = array_filter($filtered, function ($h) use ($lmName) {
                $cl = $this->closestLandmark($h['latitude'] ?? null, $h['longitude'] ?? null);
                return str_contains(strtolower($cl['name'] ?? ''), $lmName) || str_contains($lmName, strtolower($cl['name'] ?? ''));
            });
        }

        // Facility filter
        if (preg_match_all('/roomify:hasFacility\s+roomify:fac-([a-z0-9-]+)/i', $sparql, $fm)) {
            foreach ($fm[1] as $facSlug) {
                $cleanFac = str_replace('-', ' ', $facSlug);
                $filtered = array_filter($filtered, function ($h) use ($cleanFac) {
                    $hFacs = array_map('strtolower', $h['facilities'] ?? []);
                    foreach ($hFacs as $hf) {
                        if (str_contains($hf, $cleanFac) || str_contains($cleanFac, $hf)) return true;
                    }
                    return false;
                });
            }
        }

        // Sort according to SPARQL ORDER BY
        if (str_contains($sparql, 'ORDER BY DESC(?harga)')) {
            usort($filtered, fn ($a, $b) => ($b['start_price'] ?? 0) <=> ($a['start_price'] ?? 0));
        } elseif (str_contains($sparql, 'ORDER BY DESC(?rating)')) {
            usort($filtered, fn ($a, $b) => ($b['rating'] ?? 0) <=> ($a['rating'] ?? 0));
        } elseif (str_contains($sparql, 'ORDER BY ASC(?nama)')) {
            usort($filtered, fn ($a, $b) => strcasecmp($a['name'] ?? '', $b['name'] ?? ''));
        } else {
            usort($filtered, fn ($a, $b) => ($a['start_price'] ?? 0) <=> ($b['start_price'] ?? 0));
        }

        // Limit (default 200 to cover all 187 hotels in Medan)
        $limit = 200;
        if (preg_match('/LIMIT\s+([0-9]+)/i', $sparql, $limMatch)) {
            $limit = (int)$limMatch[1];
        }
        $filtered = array_slice($filtered, 0, $limit);

        // Format to SPARQL bindings shape
        $out = [];
        foreach ($filtered as $h) {
            $out[] = [
                'id'     => $h['id'],
                'nama'   => $h['name'],
                'alamat' => $h['address'],
                'harga'  => (string)($h['start_price'] ?? 0),
                'rating' => isset($h['rating']) ? (string)$h['rating'] : null,
                'star'   => isset($h['star']) ? (string)$h['star'] : null,
                'foto'   => $h['image_url'] ?? '/images/placeholder.svg',
                'lat'    => isset($h['latitude']) ? (string)$h['latitude'] : null,
                'lon'    => isset($h['longitude']) ? (string)$h['longitude'] : null,
            ];
        }

        return $out;
    }

    protected function closestLandmark(?float $lat, ?float $lon): array
    {
        if (!$lat || !$lon) {
            return self::LANDMARKS[0];
        }

        $best = self::LANDMARKS[0];
        $minDist = PHP_FLOAT_MAX;

        foreach (self::LANDMARKS as $lm) {
            $d = sqrt(pow($lat - $lm['lat'], 2) + pow($lon - $lm['lon'], 2));
            if ($d < $minDist) {
                $minDist = $d;
                $best = $lm;
            }
        }

        return $best;
    }
}
