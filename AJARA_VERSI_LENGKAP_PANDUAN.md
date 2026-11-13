# AJARA – Panduan versi pengembangan

## Fitur yang ditambahkan
- Role Admin, Mahasiswa, dan Penyelenggara dengan middleware pembatasan akses.
- Pendaftaran lomba internal AJARA, data peserta/tim, dan upload dokumen.
- Pengajuan lomba penyelenggara dan upload poster/flyer.
- Paket publikasi Basic Rp150.000, Premium Rp300.000, Featured Rp500.000.
- Transfer manual dengan upload bukti; Admin memverifikasi pembayaran dan konten sebelum status lomba menjadi published.
- Chat sederhana pengguna dengan Admin dan notifikasi flash otomatis menghilang.

## Instalasi lokal
1. Backup project dan database terlebih dahulu.
2. Pertahankan file `.env` lokal milikmu; jangan gunakan `.env` dari arsip.
3. Jalankan `composer install`.
4. Jalankan `php artisan optimize:clear`.
5. Jalankan `php artisan migrate` (tidak memakai migrate:fresh).
6. Jalankan `php artisan storage:link`.
7. Jalankan `php artisan db:seed` untuk akun demo dan data lomba.
8. Jalankan `php artisan serve`.

## Akun demo
- Admin: `admin@ajara.test` / `AdminAJARA123!`
- Mahasiswa: `amanda@example.com` / `password123`

Ganti password akun demo sebelum deployment.

## Harga paket
Harga contoh untuk pengembangan: Basic Rp150.000, Premium Rp300.000, Featured Rp500.000. Sesuaikan nominal pada `OrganizerController` sebelum digunakan.

## Pembayaran
Pembayaran masih transfer manual. Nomor rekening resmi belum dikonfigurasi di aplikasi; jangan menerima transfer sungguhan sebelum pengelola menetapkan rekening dan prosedur verifikasi.

## Catatan produksi
Chat masih berbasis refresh halaman, bukan realtime. Dokumen dan bukti disimpan pada disk public untuk demo; produksi sebaiknya memakai private storage dengan endpoint unduhan terotorisasi. Tambahkan verifikasi email, audit log, rate limiting, kebijakan privasi, backup, dan pengujian keamanan sebelum deployment.
