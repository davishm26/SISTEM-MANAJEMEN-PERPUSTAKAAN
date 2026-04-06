# Tugas Praktikum Implementasi API - Sistem Manajemen Perpustakaan

**Identitas:**
- Nama: [Davista Hana Maygiantama]
- NIM: [102062400074]

## Cara Menjalankan Project

### 1. Persiapan Database
- Pastikan MySQL di Laragon aktif.
- Buat database baru bernama `library_api`.

### 2. Backend (Laravel API)
- Masuk ke folder `library-api`.
- Jalankan `composer install` (jika vendor kosong).
- Sesuaikan konfigurasi database di `.env`.
- Jalankan migrasi: `php artisan migrate`.
- Jalankan server: `php artisan serve`. (Port 8000)

### 3. Frontend (CodeIgniter 4 App)
- Masuk ke folder `library-app`.
- Sesuaikan `LIBRARY_API_URL` di `.env` ke `http://localhost:8000/api`.
- Jalankan server: `php spark serve`. (Port 8080)
