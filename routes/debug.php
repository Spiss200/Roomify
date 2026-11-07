<?php

use Illuminate\Support\Facades\Route;
use App\Services\FusekiClient;
use App\Services\WikidataClient;

// Route debugging — hapus sebelum production
Route::get('/tes-fuseki', function (FusekiClient $fuseki) {
    $rows = $fuseki->select('PREFIX roomify: <https://roomify.example/ontology#>
        SELECT ?nama ?harga ?rating WHERE {
            ?h a roomify:Hotel ; roomify:name ?nama ;
               roomify:startPrice ?harga ; roomify:rating ?rating .
        } ORDER BY ?harga LIMIT 5');
    return response()->json([
        'pesan' => count($rows) ? 'Fuseki TERHUBUNG!' : 'Fuseki tidak merespons',
        'jumlah' => count($rows),
        'data' => $rows,
    ]);
});

Route::get('/tes-wikidata', function (WikidataClient $w) {
    $data = $w->landmarksNear(3.5952, 98.6722, 3);
    if (count($data) > 0) return response()->json($data);
    $res = $w->debug(3.5952, 98.6722, 3);
    return response()->json([
        'status' => $res->status(),
        'error' => substr($res->body(), 0, 500),
    ]);
});
