# Roomify: Semantic Hotel Search Engine Medan

Tugas besar mata kuliah **Web Semantik** dan **Manajemen Sistem Basis Data**.

Roomify adalah website pencarian hotel di Kota Medan. Data hotel hasil scraping diubah menjadi **RDF**, disimpan di **Apache Jena Fuseki**, lalu dicari lewat **SPARQL** dari aplikasi **Laravel**. Data transaksi (user, booking, ulasan) disimpan di **SQLite**.

## Fitur

**Pencarian (SPARQL ke Fuseki)**
- Kata kunci bebas pada nama, alamat, dan deskripsi. Kalimat diurai menjadi kata kunci, fasilitas, dan landmark, contoh: "hotel dekat Istana Maimun kolam"
- Sinonim fasilitas: kolam/pool, sarapan/breakfast, gym/fitness, parkir, wifi
- Filter sidebar: bintang (minimal N bintang), harga minimum dan maksimum, dekat landmark, fasilitas
- Urutan: harga naik/turun, rating, nama, terdekat dari lokasi pengguna (jika lokasi terdeteksi)
- Autocomplete nama hotel (`/suggest`)
- Peta hasil pencarian (Google Maps)

**Detail hotel**
- Deskripsi, fasilitas, tipe kamar dan harga, galeri foto, peta
- 5 landmark terdekat dengan jarak (dihitung lokal dari 13 landmark Medan, rumus haversine)
- Metadata semantik: RDFa, Open Graph, dan tampilan JSON-LD

**Akun dan transaksi**
- Register, login, profil (termasuk foto profil), dashboard pengguna (Laravel Breeze)
- Booking dan pembayaran (**simulasi**, tanpa payment gateway), riwayat di dashboard
- Tampilan terang/gelap dan jam real-time di halaman

**Panel admin (`/admin`)**
- Statistik: jumlah pengguna, booking, pendapatan, booking lunas dan pending
- Kelola pengguna: ubah peran admin/member, hapus akun
- Kelola booking: cari, filter status, ubah status (`paid`, `pending`, `cancelled`), hapus
- Hanya akun dengan `is_admin = true` yang bisa masuk (selain itu mendapat 403)

**Chatbot**
- Chatbot **berbasis aturan (rule-based)**, bukan model AI: jadwal check-in, syarat KTP, metode bayar, hotel dekat landmark atau lokasi populer, hotel murah, hotel bintang 5, serta singkatan dan bahasa gaul lokal. Chatbot membaca `hotels_complete.json`, bukan Fuseki.

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

### Bagian yang memakai SPARQL dan yang tidak

| Fitur | Sumber data |
|---|---|
| Pencarian dan filter | Fuseki (SPARQL) |
| Detail hotel (info, kamar, fasilitas) | Fuseki (SPARQL) |
| Autocomplete `/suggest` | Fuseki (SPARQL) |
| Form booking | Fuseki (SPARQL) |
| Chatbot | `hotels_complete.json` |
| Daftar hotel di panel admin | `hotels_complete.json` |
| Landmark terdekat di detail | Perhitungan lokal (13 landmark) |

### Fallback Fuseki

`FusekiClient` memakai `semantic/scrape/hotels_complete.json` sebagai cadangan **hanya jika Fuseki tidak bisa dihubungi**. Hasil SPARQL yang kosong tidak memicu cadangan.

Untuk demo Web Semantik, matikan cadangan di `.env`:
```
FUSEKI_FALLBACK=false
```
Dengan nilai ini, jika Fuseki mati maka pencarian kosong, sehingga terbukti pencarian berjalan lewat SPARQL.

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
GOOGLE_MAPS_KEY=
```
`GOOGLE_MAPS_KEY` bersifat opsional. Jika kosong, aplikasi memakai key bawaan di `config/services.php`.

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

### 5. Membuat akun admin
Daftar akun lewat `/register`, lalu angkat menjadi admin:
```bash
php artisan tinker --execute="App\Models\User::where('email','EMAIL_ANDA')->update(['is_admin'=>true]);"
```
Login, lalu buka `/admin`. Admin berikutnya dapat diangkat dari panel admin.

### 6. Tes otomatis
```bash
php artisan test
```
Terdapat 46 tes: autentikasi, alur booking dan pembayaran, filter bintang dan harga, `/suggest`, chatbot, serta akses panel admin. Tes berjalan tanpa Fuseki (memakai data cadangan).

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
| `/profile` | Profil pengguna (perlu login) |
| `/admin` | Panel admin (khusus admin) |

## Struktur Folder

```
app/Http/Controllers   Search, Hotel, Booking, Payment, Chatbot, Admin
app/Services           FusekiClient, SparqlBuilder, WikidataClient
resources/views        Tampilan Blade
semantic/scrape        Skrip scraping, cleaning, build_rdf.py, data JSON dan CSV
semantic/rdf           roomify.ttl dan demo_queries.sparql
public/images          Foto hotel dan landmark
tests/Feature          Tes otomatis
```

## Keterbatasan

- **Ulasan:** ulasan di RDF dibuat otomatis oleh `build_rdf.py`, bukan ulasan asli. Tabel `reviews` sudah ada di SQLite, tetapi belum dipakai, dan bagian ulasan di halaman detail masih contoh statis. Tombol hapus ulasan di panel admin belum menghapus data.
- **Chatbot dan panel admin** tidak memakai SPARQL, keduanya membaca `hotels_complete.json`.
- **Urutan acak:** `/search` tanpa filter dan tanpa lokasi menampilkan hotel dalam urutan acak setiap dibuka.
- **Kualitas data:** sebagian alamat hotel muncul berulang, dan harga tipe kamar tampak dihitung dari harga dasar. Data dasar (nama, koordinat, rating, harga mulai) berasal dari scraping, sedangkan kelengkapan lainnya perlu diverifikasi.
- **Landmark:** RDF menyimpan satu landmark terdekat per hotel (jarak lurus), sedangkan halaman detail menampilkan lima landmark terdekat (haversine). `WikidataClient` masih ada di kode tetapi tidak dipakai lagi oleh halaman detail.
- **Pembayaran** hanya simulasi, status langsung `paid`.
- **Keamanan:** nomor KTP tersimpan tanpa enkripsi di SQLite dan dapat dicari dari panel admin. API key Google Maps tertulis sebagai nilai bawaan di `config/services.php`.

## Tim

| NIM | Nama | Peran |
|---|---|---|
| 251402082 | Bintang Pamungkas | Leader, ontologi dan RDF, Fuseki, backend, admin, chatbot |
| 251402092 | Muhammad Azkha Amorie | Backend (integrasi search, filter, detail, suggest), tes, dokumentasi |
| 251402008 | Sina Mahdi | Frontend |
| 251502129 | Mar'ie Rizqullah | Frontend |
| 251402138 | Randi Abdiansyah | Backend |
| 251402059 | Agi Aginta | Backend |