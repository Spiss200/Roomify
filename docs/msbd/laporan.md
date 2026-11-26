# Laporan Proyek Roomify × MSBD — Upgrade Transaksi ke PostgreSQL 16

Dokumen ini memetakan materi perkuliahan MSBD ke implementasi nyata pada aplikasi **Roomify** (arsitektur multi-database: **PostgreSQL 16** untuk OLTP/transaksi + **Apache Jena Fuseki** untuk katalog semantik hotel & landmark Medan).

---

## Ringkasan Progres & Hasil yang Diharapkan per Task

### T0 — Docker PostgreSQL + Koneksi Laravel
- **Materi / Fokus:** Infrastruktur RDBMS terisolasi (Docker Compose), konfigurasi koneksi PostgreSQL 16 di port host `5433`, migrasi awal transaksi dari SQLite ke PostgreSQL, serta menjaga katalog semantik Fuseki tetap berjalan normal.
- **Implementasi:**
  - `compose.yaml`: service `postgres:16` (`roomify-postgres`), database `roomify`, user `roomify`, port mapping `5433:5432` (menghindari bentrok port `5432`), named volume `roomify_pgdata`, dan healthcheck `pg_isready`.
  - `.env` & `.env.example`: konfigurasi `DB_CONNECTION=pgsql`, `DB_HOST=127.0.0.1`, `DB_PORT=5433`, `DB_DATABASE=roomify`, `DB_USERNAME=roomify` (tanpa rahasia di `.env.example`).
- **File Verifikasi:** `docs/msbd/verifikasi/T00.sh`
- **Hasil yang Diharapkan:**
  1. `docker compose ps` menampilkan `roomify-postgres` dalam kondisi `Up (healthy)` pada port host `5433`.
  2. `psql -U roomify -d roomify -c "SELECT version();"` menampilkan versi `PostgreSQL 16.x`.
  3. `php artisan migrate:fresh --seed` berhasil membuat seluruh tabel transaksi (`users`, `bookings`, `sessions`, `cache`, `jobs`, `reviews`) di PostgreSQL tanpa error.
  4. `php artisan test` tetap hijau (`53+ passed`), dan endpoint `/search` tetap merespons normal (200 OK).
