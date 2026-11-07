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

        $hotel = $this->fuseki->select("$p
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

        if (! $hotel) abort(404, 'Hotel tidak ditemukan di triplestore');
        $h = $hotel[0];

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

        $landmark = ($h['lat'] && $h['lon'])
            ? $this->wikidata->landmarksNear((float) $h['lat'], (float) $h['lon'], 3)
            : [];

        return view('hotel.show', [
            'id'        => $id,
            'hotel'     => $h,
            'kamar'     => $kamar,
            'fasilitas' => array_column($fasilitas, 'namaFasilitas'),
            'landmark'  => $landmark,
        ]);
    }
}
