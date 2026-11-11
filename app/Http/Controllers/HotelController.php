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

        $landmark = ($lat && $lon)
            ? $this->wikidata->landmarksNear((float) $lat, (float) $lon, 3)
            : [];

        $hotel = [
            'nama'       => $h['nama'],
            'alamat'     => $h['alamat'],
            'deskripsi'  => $h['deskripsi'] ?? null,
            'starRating' => $h['bintang'] ?? null,
            'startPrice' => (int) $h['harga'],
            'rating'     => $h['rating'] ?? null,
            'foto'       => $h['foto'] ?? '/images/placeholder.svg',
            'galeri'     => $this->galeri($h['foto'] ?? null),
            'latitude'   => $lat,
            'longitude'  => $lon,
            'fasilitas'  => array_column($fasilitas, 'namaFasilitas'),
            'kamar'      => $kamar,
            'landmarks'  => array_column($landmark, 'nama'),
        ];

        return view('hotel.show', [
            'id'    => $id,
            'hotel' => $hotel,
        ]);
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