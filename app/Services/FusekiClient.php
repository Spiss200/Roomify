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
                    $bindings = $res->json()['results']['bindings'] ?? [];
                    if (!empty($bindings)) {
                        return collect($bindings)
                            ->map(function ($row) {
                                $clean = [];
                                foreach ($row as $key => $cell) {
                                    $clean[$key] = $cell['value'] ?? null;
                                }
                                return $clean;
                            })
                            ->all();
                    }
                } else {
                    static::$fusekiOnline = false;
                }
            } catch (\Throwable $e) {
                static::$fusekiOnline = false;
            }
        }

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

        // Keyword filter
        if (preg_match('/CONTAINS\(LCASE\(\?nama\),\s*\'([^\']+)\'\)/i', $sparql, $km)) {
            $kw = strtolower($km[1]);
            $filtered = array_filter($filtered, function ($h) use ($kw) {
                return str_contains(strtolower($h['name'] ?? ''), $kw)
                    || str_contains(strtolower($h['address'] ?? ''), $kw)
                    || str_contains(strtolower($h['description'] ?? ''), $kw);
            });
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

        // Sort by start_price ASC
        usort($filtered, function ($a, $b) {
            return ($a['start_price'] ?? 0) <=> ($b['start_price'] ?? 0);
        });

        // Limit
        $limit = 50;
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
