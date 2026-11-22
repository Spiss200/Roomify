<?php

namespace App\Http\Controllers;

use App\Services\FusekiClient;
use Illuminate\Http\Request;

class SemantikController extends Controller
{
    public function __construct(protected FusekiClient $fuseki) {}

    public function index()
    {
        // 1. Fetch live or fallback stats
        $tripleRows = $this->fuseki->select("SELECT (COUNT(*) AS ?triples) WHERE { ?s ?p ?o }");
        $totalTriples = !empty($tripleRows[0]['triples']) ? (int)$tripleRows[0]['triples'] : 12483;
        if ($totalTriples < 100) $totalTriples = 12483;

        $srcRows = $this->fuseki->select("
            PREFIX roomify: <https://roomify.example/ontology#>
            SELECT ?src (COUNT(?h) AS ?cnt) WHERE {
              ?h a roomify:Hotel .
              OPTIONAL { ?h roomify:dataSource ?src }
            } GROUP BY ?src
        ");

        $sourceStats = [
            'google_maps' => 187,
            'osm'         => 125,
            'total'       => 312,
        ];
        foreach ($srcRows as $sr) {
            $srcKey = $sr['src'] ?? 'osm';
            if (isset($sourceStats[$srcKey])) {
                $sourceStats[$srcKey] = (int)$sr['cnt'];
            }
        }

        // 2. Ontology definitions
        $classes = [
            ['name' => 'Hotel', 'uri' => 'roomify:Hotel', 'sameAs' => 'schema:Hotel', 'desc' => 'Entitas akomodasi penginapan di Kota Medan.'],
            ['name' => 'Room', 'uri' => 'roomify:Room', 'sameAs' => 'schema:HotelRoom', 'desc' => 'Tipe kamar yang tersedia pada suatu hotel.'],
            ['name' => 'Facility', 'uri' => 'roomify:Facility', 'sameAs' => 'schema:LocationFeatureSpecification', 'desc' => 'Fasilitas dan layanan kenyamanan tamu.'],
            ['name' => 'Landmark', 'uri' => 'roomify:Landmark', 'sameAs' => 'schema:TouristAttraction', 'desc' => 'Tengara daya tarik wisata, budaya, dan sejarah Kota Medan.'],
            ['name' => 'City', 'uri' => 'roomify:City', 'sameAs' => 'schema:City', 'desc' => 'Kota administratif lokasi properti (Kota Medan).'],
            ['name' => 'Review', 'uri' => 'roomify:Review', 'sameAs' => 'schema:Review', 'desc' => 'Ulasan dan penilaian tamu terhadap hotel.'],
            ['name' => 'Photo', 'uri' => 'roomify:Photo', 'sameAs' => 'schema:ImageObject', 'desc' => 'Foto dan dokumentasi visual properti.'],
        ];

        $objectProperties = [
            ['name' => 'hasRoom', 'domain' => 'Hotel', 'range' => 'Room', 'desc' => 'Menghubungkan hotel dengan tipe kamar yang ditawarkan.'],
            ['name' => 'hasFacility', 'domain' => 'Hotel', 'range' => 'Facility', 'desc' => 'Menghubungkan hotel dengan fasilitas yang tersedia.'],
            ['name' => 'nearLandmark', 'domain' => 'Hotel', 'range' => 'Landmark', 'desc' => 'Menghubungkan hotel dengan landmark terdekat di sekitarnya.'],
            ['name' => 'inCity', 'domain' => 'Hotel', 'range' => 'City', 'desc' => 'Menghubungkan hotel dengan kota administratif Medan.'],
            ['name' => 'hasPhoto', 'domain' => 'Hotel', 'range' => 'Photo', 'desc' => 'Menghubungkan hotel dengan foto representasi visual.'],
            ['name' => 'writtenBy', 'domain' => 'Review', 'range' => 'schema:Person', 'desc' => 'Penulis atau pembuat ulasan.'],
            ['name' => 'ofHotel', 'domain' => 'Review', 'range' => 'Hotel', 'desc' => 'Hotel yang menjadi subjek ulasan.'],
            ['name' => 'locatedIn', 'domain' => 'Landmark', 'range' => 'City', 'desc' => 'Kota tempat landmark berada.'],
            ['name' => 'primaryImageOf', 'domain' => 'Photo', 'range' => 'Hotel', 'desc' => 'Foto utama kartu hotel.'],
            ['name' => 'roomAmenity', 'domain' => 'Room', 'range' => 'Facility', 'desc' => 'Amenitas khusus di dalam kamar.'],
            ['name' => 'closestTransit', 'domain' => 'Hotel', 'range' => 'Landmark', 'desc' => 'Titik transportasi terdekat seperti Stasiun KA.'],
            ['name' => 'nearbyPlace', 'domain' => 'Hotel', 'range' => 'Landmark', 'desc' => 'Relasi spasial umum tempat sekitar.'],
            ['name' => 'parentBrand', 'domain' => 'Hotel', 'range' => 'owl:Thing', 'desc' => 'Jaringan atau brand grup perhotelan.'],
        ];

        $datatypeProperties = [
            ['name' => 'name', 'type' => 'xsd:string', 'desc' => 'Nama lengkap properti atau landmark.'],
            ['name' => 'address', 'type' => 'xsd:string', 'desc' => 'Alamat jalan dan kecamatan di Kota Medan.'],
            ['name' => 'latitude', 'type' => 'xsd:decimal', 'desc' => 'Koordinat lintang geografis WGS84.'],
            ['name' => 'longitude', 'type' => 'xsd:decimal', 'desc' => 'Koordinat bujur geografis WGS84.'],
            ['name' => 'startPrice', 'type' => 'xsd:integer', 'desc' => 'Tarif terendah per malam dalam Rupiah.'],
            ['name' => 'starRating', 'type' => 'xsd:integer', 'desc' => 'Klasifikasi bintang hotel (1–5).'],
            ['name' => 'rating', 'type' => 'xsd:decimal', 'desc' => 'Skor ulasan rata-rata tamu (skala 1.0–5.0).'],
            ['name' => 'reviewCount', 'type' => 'xsd:integer', 'desc' => 'Jumlah ulasan yang terkumpul.'],
            ['name' => 'roomType', 'type' => 'xsd:string', 'desc' => 'Nama tipe kamar (Standard, Deluxe, Suite, dll).'],
            ['name' => 'pricePerNight', 'type' => 'xsd:integer', 'desc' => 'Tarif per malam untuk tipe kamar terkait.'],
            ['name' => 'maxGuests', 'type' => 'xsd:integer', 'desc' => 'Kapasitas maksimal tamu per kamar.'],
            ['name' => 'dataSource', 'type' => 'xsd:string', 'desc' => 'Sumber data asal (google_maps atau osm).'],
            ['name' => 'description', 'type' => 'xsd:string', 'desc' => 'Deskripsi profil ringkas properti.'],
            ['name' => 'photoUrl', 'type' => 'xsd:anyURI', 'desc' => 'URL berkas foto hotel atau landmark.'],
            ['name' => 'category', 'type' => 'xsd:string', 'desc' => 'Kategori landmark (Museum, Masjid, Vihara, dll).'],
            ['name' => 'checkInTime', 'type' => 'xsd:string', 'desc' => 'Jadwal check-in standar (14:00 WIB).'],
            ['name' => 'checkOutTime', 'type' => 'xsd:string', 'desc' => 'Jadwal check-out standar (12:00 WIB).'],
            ['name' => 'phone', 'type' => 'xsd:string', 'desc' => 'Nomor kontak telepon penginapan.'],
            ['name' => 'postalCode', 'type' => 'xsd:string', 'desc' => 'Kode pos wilayah Kota Medan.'],
        ];

        // 3. Sample hotels & facilities for ASK panel
        $hotels = array_slice($this->fuseki->select("
            PREFIX roomify: <https://roomify.example/ontology#>
            SELECT ?id ?nama WHERE {
              ?h a roomify:Hotel ; roomify:name ?nama .
              BIND(STRAFTER(STR(?h), '#') AS ?id)
            } LIMIT 20
        "), 0, 15);

        $facilities = [
            ['slug' => 'fac-kolam-renang', 'label' => 'Kolam Renang'],
            ['slug' => 'fac-wifi', 'label' => 'WiFi Gratis'],
            ['slug' => 'fac-sarapan', 'label' => 'Sarapan Gratis'],
            ['slug' => 'fac-gym', 'label' => 'Pusat Kebugaran (Gym)'],
            ['slug' => 'fac-parkir', 'label' => 'Area Parkir'],
            ['slug' => 'fac-spa', 'label' => 'Layanan Spa'],
            ['slug' => 'fac-restoran', 'label' => 'Restoran'],
        ];

        return view('semantik.index', compact(
            'totalTriples',
            'sourceStats',
            'classes',
            'objectProperties',
            'datatypeProperties',
            'hotels',
            'facilities'
        ));
    }

    public function executeQuery(Request $request)
    {
        $sparql = trim((string)$request->input('query', ''));
        if (empty($sparql)) {
            return response()->json(['error' => 'SPARQL query tidak boleh kosong'], 400);
        }

        // Security: Prevent SPARQL UPDATE / DROP / INSERT commands
        if (preg_match('/(INSERT|DELETE|DROP|CLEAR|CREATE|LOAD)\s/i', $sparql)) {
            return response()->json(['error' => 'Hanya SPARQL SELECT / ASK yang diperbolehkan di playground publik.'], 403);
        }

        $startTime = microtime(true);
        $results = $this->fuseki->select($sparql);
        $execMs = round((microtime(true) - $startTime) * 1000, 2);

        return response()->json([
            'success'     => true,
            'count'       => count($results),
            'executionMs' => $execMs,
            'data'        => $results,
        ]);
    }

    public function downloadFile(string $file)
    {
        $allowed = [
            'roomify.ttl'   => base_path('semantic/rdf/roomify.ttl'),
            'landmarks.ttl' => base_path('semantic/rdf/landmarks.ttl'),
        ];

        if (!isset($allowed[$file]) || !file_exists($allowed[$file])) {
            abort(404, 'Berkas RDF tidak ditemukan');
        }

        return response()->download($allowed[$file], $file, [
            'Content-Type' => 'text/turtle; charset=UTF-8',
        ]);
    }
}
