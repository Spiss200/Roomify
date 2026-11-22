<?php

namespace App\Http\Controllers;

use App\Services\FusekiClient;
use Illuminate\Http\Request;

class LandmarkController extends Controller
{
    public function __construct(protected FusekiClient $fuseki) {}

    public function show(string $id)
    {
        $p = 'PREFIX roomify: <https://roomify.example/ontology#>';

        // 1. Fetch landmark metadata
        $rows = $this->fuseki->select("$p
            SELECT ?id ?nama ?lat ?lon ?kat ?alamat ?foto ?rating ?ulasan WHERE {
              roomify:$id a roomify:Landmark ;
                          roomify:name ?nama ;
                          roomify:latitude ?lat ;
                          roomify:longitude ?lon ;
                          roomify:category ?kat .
              OPTIONAL { roomify:$id roomify:address ?alamat }
              OPTIONAL { roomify:$id roomify:photoUrl ?foto }
              OPTIONAL { roomify:$id roomify:rating ?rating }
              OPTIONAL { roomify:$id roomify:reviewCount ?ulasan }
              BIND(STRAFTER(STR(roomify:$id), '#') AS ?id)
            } LIMIT 1");

        // Fallback to static list if not found
        $landmark = null;
        if (!empty($rows)) {
            $landmark = $rows[0];
        } else {
            foreach (FusekiClient::LANDMARKS as $lm) {
                if ($lm['id'] === $id) {
                    $landmark = [
                        'id'     => $lm['id'],
                        'nama'   => $lm['name'],
                        'lat'    => (string)$lm['lat'],
                        'lon'    => (string)$lm['lon'],
                        'kat'    => $lm['category'] ?? 'Landmark',
                        'alamat' => $lm['address'] ?? 'Kota Medan, Sumatera Utara',
                        'foto'   => $lm['image'] ?? '/images/placeholder.svg',
                        'rating' => isset($lm['rating']) ? (string)$lm['rating'] : '4.5',
                        'ulasan' => isset($lm['reviews']) ? (string)$lm['reviews'] : '100',
                    ];
                    break;
                }
            }
        }

        if (!$landmark) {
            abort(404, 'Landmark tidak ditemukan');
        }

        $lmLat = (float)($landmark['lat'] ?? 3.5952);
        $lmLon = (float)($landmark['lon'] ?? 98.6722);

        // 2. Fetch candidate hotels from triplestore
        $hotelRows = $this->fuseki->select("$p
            SELECT ?id ?nama ?alamat ?harga ?rating ?star ?foto ?lat ?lon ?dataSource WHERE {
              ?h a roomify:Hotel ;
                 roomify:name ?nama ;
                 roomify:address ?alamat .
              OPTIONAL { ?h roomify:dataSource ?dataSource }
              OPTIONAL { ?h roomify:startPrice ?harga }
              OPTIONAL { ?h roomify:rating ?rating }
              OPTIONAL { ?h roomify:starRating ?star }
              OPTIONAL { ?h roomify:latitude ?lat }
              OPTIONAL { ?h roomify:longitude ?lon }
              OPTIONAL { ?h roomify:hasPhoto ?p . ?p roomify:photoUrl ?foto }
              BIND(STRAFTER(STR(?h), '#') AS ?id)
            } LIMIT 350");

        $hotels = [];
        foreach ($hotelRows as $r) {
            $lat = !empty($r['lat']) ? (float)$r['lat'] : null;
            $lon = !empty($r['lon']) ? (float)$r['lon'] : null;
            $distKm = ($lat && $lon) ? $this->haversineKm($lmLat, $lmLon, $lat, $lon) : null;
            $distFormatted = $distKm !== null
                ? ($distKm < 1 ? round($distKm * 1000) . ' m' : number_format($distKm, 1, ',', '.') . ' km')
                : null;

            $rawPrice = (!empty($r['harga']) && (int)$r['harga'] > 0) ? (int)$r['harga'] : null;

            $hotels[] = [
                'id'         => $r['id'],
                'nama'       => $r['nama'],
                'alamat'     => $r['alamat'],
                'bintang'    => $r['star'] ?? null,
                'starRating' => $r['star'] ?? null,
                'harga'      => $rawPrice,
                'startPrice' => $rawPrice,
                'rating'     => $r['rating'] ?? null,
                'foto'       => (!empty($r['foto']) && $r['foto'] !== '') ? $r['foto'] : '/images/placeholder.svg',
                'latitude'   => $lat,
                'longitude'  => $lon,
                'jarak_km'   => $distKm,
                'jarak'      => $distFormatted,
                'dataSource' => $r['dataSource'] ?? (!empty($rawPrice) ? 'google_maps' : 'osm'),
            ];
        }

        // Sort by distance to this landmark
        usort($hotels, function ($a, $b) {
            if ($a['jarak_km'] === null) return 1;
            if ($b['jarak_km'] === null) return -1;
            return $a['jarak_km'] <=> $b['jarak_km'];
        });

        $nearestHotels = array_slice($hotels, 0, 12);
        $allLandmarks = FusekiClient::LANDMARKS;

        return view('landmark.show', [
            'landmark'      => $landmark,
            'nearestHotels' => $nearestHotels,
            'allLandmarks'  => $allLandmarks,
        ]);
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
}
