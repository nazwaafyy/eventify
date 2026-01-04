# Eventify

Eventify adalah website event kampus berbasis PHP & MySQL yang memudahkan pengguna untuk menemukan, melihat detail, dan menyimpan event. Penyelenggara juga dapat mendaftarkan event agar tampil di platform.

## Fitur
- Beranda event + rekomendasi
- Jelajahi event (search + filter kategori)
- Detail event (daftar, bagikan, simpan)
- Form pendaftaran event (upload poster + upload MoU)
- Halaman FAQ
- Admin page untuk kelola event (jika digunakan)

## Tech Stack
- PHP Native
- MySQL (phpMyAdmin)
- HTML, CSS, JavaScript

## Cara Menjalankan (Local)
1. Pindahkan folder project ke `htdocs`
2. Jalankan Apache dan MySQL di XAMPP
3. Import database ke phpMyAdmin
4. Sesuaikan koneksi database di `config/koneksi.php`
5. Buka:
   `http://localhost/eventify_project/beranda.php`

## Demo Account
User:
- Email: nazwa@gmail.com
- Password: 123456

## Database
Database utama: `eventify_db`  
Tabel utama: `events`, `users`, `saved_events`

## Author
Nazwa Febriyanti

