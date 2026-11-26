#!/usr/bin/env bash
# Verifikasi T0 — Docker PostgreSQL 16 + koneksi Laravel Roomify
# Jalankan dari root repository: bash docs/msbd/verifikasi/T00.sh

set -euo pipefail

# GUARD: migrate:fresh menghapus semua tabel. Tolak jalan kalau .env belum pgsql.
if ! grep -q '^DB_CONNECTION=pgsql' .env; then
  echo "STOP: .env masih $(grep '^DB_CONNECTION' .env). Set ke pgsql + nyalakan Docker dulu, atau backup SQLite."
  exit 1
fi


echo "=== 1. Status container Docker Compose ==="
docker compose ps
# HARUS: service postgres (roomify-postgres) berstatus Up (healthy) dengan port 0.0.0.0:5433->5432/tcp (atau :::5433->5432/tcp)

echo "=== 2. Cek versi PostgreSQL 16 di container ==="
docker compose exec -T postgres psql -U roomify -d roomify -c "SELECT version();"
# HARUS: mengembalikan 1 baris berisi "PostgreSQL 16..."

echo "=== 3. Cek koneksi & migrasi Laravel ke PostgreSQL ==="
php -d extension=pdo_pgsql -d extension=pgsql artisan migrate:fresh --seed --no-interaction
# HARUS: seluruh migrasi (users, cache, jobs, bookings, reviews) berjalan sukses tanpa error dan DatabaseSeeder selesai

echo "=== 4. Cek daftar tabel di schema public PostgreSQL ==="
docker compose exec -T postgres psql -U roomify -d roomify -c "\dt"
# HARUS: tampil tabel users, password_reset_tokens, sessions, cache, cache_locks, jobs, job_batches, failed_jobs, bookings, reviews, migrations

echo "=== 5. Jalankan test suite Laravel ==="
php artisan test
# HARUS: seluruh test hijau (53+ passed, 0 failed)
