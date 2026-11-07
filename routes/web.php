<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Services\FusekiClient;

Route::get('/tes-fuseki', function (FusekiClient $fuseki) {
    $rows = $fuseki->select(
        'PREFIX roomify: <https://roomify.example/ontology#>
         SELECT ?nama ?harga ?rating WHERE {
           ?h a roomify:Hotel ;
              roomify:name ?nama ;
              roomify:startPrice ?harga ;
              roomify:rating ?rating .
         } ORDER BY ?harga LIMIT 5'
    );

    return response()->json([
        'pesan'  => count($rows) ? 'Fuseki TERHUBUNG! Otak semantik hidup.' : 'Fuseki tidak merespons / dataset kosong',
        'jumlah' => count($rows),
        'data'   => $rows,
    ]);
});

use App\Http\Controllers\SearchController;

Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/api/suggest', [SearchController::class, 'suggest']);

Route::get('/tes-wikidata', function (WikidataClient $w) {
    $data = $w->landmarksNear(3.5952, 98.6722, 3);

    if (count($data) > 0) {
        return response()->json($data);
    }

    // kalau masih kosong, tunjukkan error asli dari Wikidata
    $res = $w->debug(3.5952, 98.6722, 3);
    return response()->json([
        'status' => $res->status(),
        'error'  => substr($res->body(), 0, 400),
    ]);
});

Route::get('/tes-wikidata', function (\App\Services\WikidataClient $w) {
    $data = $w->landmarksNear(3.5952, 98.6722, 3);

    if (count($data) > 0) {
        return response()->json($data);
    }

    $res = $w->debug(3.5952, 98.6722, 3);
    return response()->json([
        'status' => $res->status(),
        'error'  => substr($res->body(), 0, 500),
    ]);
});

Route::get('/tes-wikidata-ringan', fn (\App\Services\WikidataClient $w) => response()->json($w->queryRingan()));
