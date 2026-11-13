# AJARA – Panduan pemasangan versi pembaruan

## Isi pembaruan
- Identitas warna diperbarui ke teal `#075B55`, maroon `#8D2638`, gold `#D8B66A`, dan off-white `#F5F7F5`.
- Notifikasi sukses/status menutup otomatis setelah 4,5 detik.
- Navigasi bawah mobile untuk mahasiswa.
- Tombol Keluar pada header desktop.
- Halaman struk pengajuan publikasi yang dapat dicetak / disimpan sebagai PDF.
- Halaman pembayaran menampilkan slot QRIS AJARA.

## PENTING: backup dahulu
Ekspor database melalui phpMyAdmin > database `ajara_db` > Export. Jangan gunakan `migrate:fresh`.

## Pembaruan file
1. Ekstrak ZIP.
2. Backup folder project lama.
3. Salin isi folder `ajara_work` ke project Laravel yang sedang digunakan, izinkan overwrite file.
4. Jangan menimpa `.env` milikmu.
5. Simpan gambar QRIS resmi sebagai `public/images/ajara-qris.png` (nama dan ekstensi harus sama dengan yang dipakai halaman pembayaran).
6. Pastikan `storage` writable dan jalankan `php artisan storage:link`.

## Pembaruan database yang mempertahankan data
1. phpMyAdmin > pilih database `ajara_db` yang sudah ada.
2. Tab Import atau SQL, jalankan file `AJARA_DB_UPGRADE_AMAN.sql`.
3. Script memakai CREATE TABLE IF NOT EXISTS dan INSERT IGNORE; tidak menghapus tabel atau baris data.
4. Jangan jalankan `php artisan migrate:fresh`.

File SQL ini ditujukan untuk dump AJARA yang disertakan pengguna (MariaDB 10.11), dengan tabel `users`, `cache`, `cache_locks`, `jobs`, `password_reset_tokens`, dan `sessions` sudah ada.

## Konfigurasi Laravel
Pertahankan nilai `.env` yang sudah benar. Pastikan:
- DB_CONNECTION=mysql
- DB_DATABASE=ajara_db
- DB_USERNAME dan DB_PASSWORD sesuai XAMPP/MySQL lokal
- APP_URL=http://127.0.0.1:8000

Lalu:
```bash
php artisan optimize:clear
php artisan storage:link
php artisan serve
```

## Email konfirmasi
Agar notifikasi email dapat dikirim, konfigurasi SMTP pada `.env` (MAIL_MAILER, MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, MAIL_ENCRYPTION, MAIL_FROM_ADDRESS, MAIL_FROM_NAME). Jangan gunakan password email utama; gunakan app password bila penyedia email mensyaratkannya.

## Paket publikasi
Harga paket contoh di `app/Http/Controllers/OrganizerController.php`:
- Basic: Rp150.000
- Premium: Rp300.000
- Featured: Rp500.000

Ubah harga sesuai keputusan AJARA sebelum menerima pembayaran sungguhan.

## Status pengujian
Semua file PHP diperiksa dengan `php -l` tanpa temuan syntax error. Ini bukan pengganti uji fungsional di komputer lokal. Uji login, role, pendaftaran, upload file, pembayaran, review admin, dan chat sebelum dipublikasikan.
