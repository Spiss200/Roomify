<?php

namespace App\Http\Controllers;

use App\Services\FusekiClient;
use App\Services\WikidataClient;

class HotelController extends Controller
{
    public function __construct(
        protected FusekiClient $fuseki,
        protected WikidataClient $wikidata,
    ) {}

    public function show(string $id)
    {
        $p = 'PREFIX roomify: <https://roomify.example/ontology#>';

        $rows = $this->fuseki->select("$p
            SELECT ?nama ?alamat ?deskripsi ?bintang ?rating ?jumlahReview ?harga ?lat ?lon ?foto WHERE {
              roomify:$id a roomify:Hotel ;
                roomify:name ?nama ;
                roomify:address ?alamat ;
                roomify:startPrice ?harga .
              OPTIONAL { roomify:$id roomify:description ?deskripsi }
              OPTIONAL { roomify:$id roomify:starRating ?bintang }
              OPTIONAL { roomify:$id roomify:rating ?rating }
              OPTIONAL { roomify:$id roomify:reviewCount ?jumlahReview }
              OPTIONAL { roomify:$id roomify:latitude ?lat }
              OPTIONAL { roomify:$id roomify:longitude ?lon }
              OPTIONAL { roomify:$id roomify:hasPhoto ?ph . ?ph roomify:photoUrl ?foto }
            } LIMIT 1");

        if (! $rows) abort(404, 'Hotel tidak ditemukan di triplestore');
        $h = $rows[0];

        $kamar = $this->fuseki->select("$p
            SELECT ?tipe ?harga ?kapasitas WHERE {
              roomify:$id roomify:hasRoom ?r .
              ?r roomify:roomType ?tipe ;
                 roomify:pricePerNight ?harga ;
                 roomify:maxGuests ?kapasitas .
            } ORDER BY ASC(?harga)");

        $fasilitas = $this->fuseki->select("$p
            SELECT ?namaFasilitas WHERE {
              roomify:$id roomify:hasFacility ?f .
              ?f roomify:name ?namaFasilitas .
            }");

        $lat = $h['lat'] ?? null;
        $lon = $h['lon'] ?? null;

        // Optimalisasi: Gunakan perhitungan lokal berkecepatan tinggi dari 13 Landmark Medan
        // Menggantikan pemanggilan lambat eksternal Wikidata/DBpedia (memotong waktu muat dari 5-10s ke <20ms)
        $nearbyLandmarks = ($lat && $lon)
            ? $this->getNearbyLandmarks((float) $lat, (float) $lon, 5)
            : [];

        $hotel = [
            'nama'             => $h['nama'],
            'alamat'           => $h['alamat'],
            'deskripsi'        => $h['deskripsi'] ?? null,
            'starRating'       => $h['bintang'] ?? null,
            'startPrice'       => (int) $h['harga'],
            'rating'           => $h['rating'] ?? null,
            'foto'             => $h['foto'] ?? '/images/placeholder.svg',
            'galeri'           => $this->galeri($h['foto'] ?? null),
            'latitude'         => $lat ? (float)$lat : 3.5952,
            'longitude'        => $lon ? (float)$lon : 98.6722,
            'fasilitas'        => array_column($fasilitas, 'namaFasilitas'),
            'kamar'            => $kamar,
            'landmarks'        => array_column($nearbyLandmarks, 'nama'),
            'nearby_landmarks' => $nearbyLandmarks,
        ];

        return view('hotel.show', [
            'id'    => $id,
            'hotel' => $hotel,
        ]);
    }

    protected function getNearbyLandmarks(float $lat, float $lon, int $limit = 5): array
    {
        $landmarks = FusekiClient::LANDMARKS;
        $results = [];

        foreach ($landmarks as $lm) {
            $d = $this->haversineKm($lat, $lon, (float)$lm['lat'], (float)$lm['lon']);
            $distStr = $d < 1 
                ? round($d * 1000) . ' m' 
                : number_format($d, 1, ',', '.') . ' km';
            
            $results[] = [
                'nama'     => $lm['name'],
                'jarak'    => $distStr,
                'jarak_km' => $d,
                'lat'      => $lm['lat'],
                'lon'      => $lm['lon'],
            ];
        }

        usort($results, fn($a, $b) => $a['jarak_km'] <=> $b['jarak_km']);
        return array_slice($results, 0, $limit);
    }

    protected function haversineKm(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $r = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        return $r * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    protected function galeri(?string $foto): array
    {
        if (! $foto || ! preg_match('#^/images/hotels/([^/]+)/#', $foto, $m)) {
            return $foto ? [$foto] : [];
        }
        $out = [];
        foreach (glob(public_path("images/hotels/{$m[1]}/*.jpg")) as $f) {
            $out[] = "/images/hotels/{$m[1]}/" . basename($f);
        }
        natsort($out);
        return array_values($out) ?: [$foto];
    }
}
