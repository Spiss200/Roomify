# Roomify: Semantic Hotel Search Engine Medan

Tugas besar mata kuliah **Web Semantik** dan **Manajemen Sistem Basis Data**.

Roomify adalah website pencarian hotel di Kota Medan. Data hotel hasil scraping diubah menjadi **RDF**, disimpan di **Apache Jena Fuseki**, lalu dicari lewat **SPARQL** dari aplikasi **Laravel**. Data transaksi (user dan booking) disimpan di **SQLite**.

## Fitur

**Pencarian (SPARQL ke Fuseki)**
- Kata kunci bebas pada nama, alamat, dan deskripsi. Kalimat diurai menjadi kata kunci, fasilitas, dan landmark, contoh: "hotel dekat Istana Maimun kolam"
- Sinonim fasilitas: kolam/pool, sarapan/breakfast, gym/fitness, parkir, wifi
- Filter sidebar: bintang (minimal N bintang), harga minimum dan maksimum, dekat landmark, fasilitas
- Urutan: harga naik/turun, rating, nama, terdekat dari lokasi pengguna
- Autocomplete nama hotel (`/suggest`)
- Peta hasil pencarian (Google Maps)

**Detail hotel**
- Deskripsi, fasilitas, tipe kamar dan harga, galeri foto, peta
- 5 landmark terdekat dengan jarak (dihitung lokal dari 13 landmark Medan, rumus haversine)
- Metadata semantik: RDFa, Open Graph, dan tampilan JSON-LD

**Transaksi**
- Register, login, dashboard pengguna (Laravel Breeze)
- Booking dan pembayaran (**simulasi**, tanpa payment gateway), riwayat di dashboard

**Chatbot**
- Chatbot **berbasis aturan (rule-based)**: jadwal check-in, syarat KTP, metode bayar, hotel dekat landmark, hotel murah, hotel bintang 5. Chatbot membaca `hotels_complete.json`, bukan Fuseki.

## Tech Stack

| Bagian | Teknologi |
|---|---|
| Backend | Laravel 13, PHP 8.3+ |
| Triplestore | Apache Jena Fuseki 5.1.0 (SPARQL) |
| Database aplikasi | SQLite |
| Frontend | Blade, Tailwind CSS |
| Peta | Google Maps JavaScript API |
| Scraping dan konversi | Python |
| Ontologi | OWL/RDF (Turtle), dipetakan ke schema.org |

## Arsitektur dan Alur Data

```
Scraping (Google Maps, Traveloka, OSM) -> CSV
   -> cleaning (Python) -> hotels_complete.json
   -> build_rdf.py -> semantic/rdf/roomify.ttl
   -> Fuseki (dataset "Roomify")
   -> Laravel: SearchController -> SparqlBuilder -> FusekiClient
   -> Halaman web
```

Data saat ini: **187 hotel**, **11.384 triple**. Sebaran bintang: 1 bintang (22), 2 (76), 3 (68), 4 (17), 5 (4).

### Fallback Fuseki

`FusekiClient` memakai `semantic/scrape/hotels_complete.json` sebagai cadangan **hanya jika Fuseki tidak bisa dihubungi**. Hasil SPARQL yang kosong tidak memicu cadangan.

Untuk demo Web Semantik, matikan cadangan di `.env`:
```
FUSEKI_FALLBACK=false
```
Dengan nilai ini, jika Fuseki mati maka pencarian kosong, sehingga terbukti seluruh pencarian berjalan lewat SPARQL.

## Ontologi

Namespace: `https://roomify.example/ontology#`

- **Class:** Hotel, Room, Facility, City, Review, Photo, Landmark (turunan class schema.org)
- **Relasi:** hasRoom, hasFacility, hasFeature, locatedInCity, hasReview, hasPhoto, nearLandmark (masing-masing punya inverse)
- **Properti data:** name, address, description, starRating, rating, reviewCount, startPrice, pricePerNight, roomType, maxGuests, latitude, longitude, photoUrl, dataSource

Lima contoh query SPARQL (hotel murah berkolam renang, hotel di jalan tertentu, kamar satu hotel, statistik fasilitas, hotel dekat landmark) ada di `semantic/rdf/queries/demo_queries.sparql`.

## Cara Menjalankan (Windows)

### Prasyarat
PHP 8.3+ (ekstensi `pdo_sqlite`, `sqlite3`, `mbstring`, `openssl`, `curl`, `fileinfo` aktif di `php.ini`), Composer, Node.js, Java 17+, Apache Jena Fuseki 5.1.0.

### 1. Instal aplikasi
```bash
git clone https://github.com/Spiss200/Roomify.git
cd Roomify
copy .env.example .env
composer install
npm install
php artisan key:generate
type nul > database\database.sqlite
php artisan migrate
```

### 2. Isi `.env`
```
FUSEKI_ENDPOINT=http://localhost:3030/Roomify/query
FUSEKI_FALLBACK=true
GOOGLE_MAPS_KEY=isi_dengan_api_key_google_maps
```
`GOOGLE_MAPS_KEY` wajib diisi, jika kosong peta tidak tampil. Mintalah key kepada leader tim.

### 3. Jalankan Fuseki
```bash
cd apache-jena-fuseki-5.1.0
.\fuseki-server.bat
```
1. Buka http://localhost:3030
2. **Manage datasets > New dataset**, nama `Roomify`, tipe **Persistent (TDB2)**
3. Tab **add data**, unggah `semantic/rdf/roomify.ttl`
4. Tab **query**, jalankan `SELECT (COUNT(*) AS ?n) WHERE { ?s ?p ?o }`. Hasil yang benar: **11384**

Jika memperbarui `roomify.ttl`, kosongkan data lama lebih dulu dengan `CLEAR DEFAULT` di tab query, lalu unggah ulang.

### 4. Jalankan web
```bash
php artisan serve
```
Buka http://localhost:8000. Fuseki harus tetap menyala.

### 5. Tes otomatis
```bash
php artisan test
```
Terdapat 40 tes: autentikasi, alur booking dan pembayaran, filter bintang dan harga, `/suggest`, dan chatbot. Tes berjalan tanpa Fuseki (memakai data cadangan).

## Rute

| Rute | Fungsi |
|---|---|
| `/` | Beranda |
| `/search` | Pencarian dan filter |
| `/suggest?q=` | Autocomplete (JSON) |
| `/api/ai-chat` | Chatbot (POST) |
| `/hotel/{id}` | Detail hotel |
| `/hotel/{id}/book` | Form booking |
| `/payment` | Pembayaran (simulasi) |
| `/dashboard` | Dashboard pengguna (perlu login) |

## Struktur Folder

```
app/Http/Controllers   Search, Hotel, Booking, Payment, Chatbot
app/Services           FusekiClient, SparqlBuilder, WikidataClient
resources/views        Tampilan Blade
semantic/scrape        Skrip scraping, cleaning, build_rdf.py, data JSON dan CSV
semantic/rdf           roomify.ttl dan demo_queries.sparql
public/images          Foto hotel dan landmark
tests/Feature          Tes otomatis
```

## Keterbatasan

- Ulasan di RDF dibuat otomatis oleh `build_rdf.py`, bukan ulasan asli.
- Sebagian alamat hotel yang sama muncul berulang, dan harga tipe kamar tampak dihitung dari harga dasar. Data dasar (nama, koordinat, rating, harga mulai) berasal dari scraping, sedangkan kelengkapan lainnya perlu diverifikasi.
- RDF menyimpan satu landmark terdekat per hotel (jarak lurus), sedangkan halaman detail menampilkan lima landmark terdekat (haversine).
- `WikidataClient` masih ada di kode, tetapi halaman detail tidak lagi memakainya.
- Pembayaran hanya simulasi, status langsung `paid`. Nomor KTP tersimpan tanpa enkripsi di SQLite.
- Chatbot tidak memakai SPARQL, dan kata kunci "kolam renang" hanya dicocokkan pada nama, alamat, dan deskripsi.

## Tim

| NIM | Nama | Peran |
|---|---|---|
| 251402082 | Bintang Pamungkas | Leader, ontologi dan RDF, Fuseki, backend |
| 251402092 | Muhammad Azkha Amorie | Backend, tes, dokumentasi |
| 251402008 | Sina Mahdi | Frontend |
| 251502129 | Mar'ie Rizqullah | Frontend |
| 251402138 | Randi Abdiansyah | Backend |
| 251402059 | Agi Aginta | Backend |