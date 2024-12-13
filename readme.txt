# Panduan Instalasi Proyek Web Poliklinik

Berikut adalah langkah-langkah untuk menginstal proyek web poliklinik.

## Prasyarat
Sebelum memulai, pastikan Anda telah menginstal perangkat lunak berikut:
- [XAMPP](https://www.apachefriends.org/index.html) atau [WAMP](http://www.wampserver.com/en/) (untuk server lokal)
- [Git](https://git-scm.com/)
- Browser web (seperti Google Chrome, Mozilla Firefox, dll.)

## Langkah-langkah Instalasi

1. **Clone Repository**
   Buka terminal atau command prompt, lalu jalankan perintah berikut untuk meng-clone repository:
   ```bash
   git clone https://github.com/ajiekusumadhany/cp-webdev-bk.git
   ```

2. **Pindahkan Folder Proyek**
   Setelah proses cloning selesai, pindahkan folder proyek ke direktori `htdocs` (untuk XAMPP) atau `www` (untuk WAMP). Contoh:
   ```bash
   mv cp-webdev-bk /path/to/xampp/htdocs/
   ```

3. **Konfigurasi Database**
   - Pastikan Apache dan MySQL di XAMPP atau WAMP sudah berjalan.
   - Buka [phpMyAdmin](http://localhost/phpmyadmin) melalui browser. 
   - Buat database baru dengan nama `db_klinik`.
   - Import file `db_klinik.sql` yang terdapat di dalam folder `database` ke database yang baru dibuat.

4. **Konfigurasi Koneksi Database**
   - Buka file `koneksi/koneksi.php` di dalam folder proyek.
   - Sesuaikan konfigurasi database (host, username, password, dan nama database) sesuai dengan pengaturan server lokal Anda. Contoh:
     ```php
     $host = 'localhost';
     $user = 'root';
     $pass = '';
     $db = 'db_klinik';
     ```

5. **Menjalankan Proyek**
   - Pastikan Apache dan MySQL di XAMPP atau WAMP sudah berjalan.
   - Buka browser dan akses proyek melalui URL: [http://localhost/cp-webdev-bk](http://localhost/cp-webdev-bk).

6. **Login ke website**
   - Gunakan kredensial berikut untuk login sebagai admin:
     - Username: `admin`
     - Password: `admin`
   - Gunakan kredensial berikut untuk login sebagai pasien:
     - Username: `inipasien`
     - Password: `semarang`
   - Gunakan kredensial berikut untuk login sebagai dokter:
     - Username: `inidokter`
     - Password: `semarang`
   Jadi untuk password pasien dan dokter itu menggunakan alamat


Selamat! Anda telah berhasil menginstal dan menjalankan proyek PHP native dari GitHub.

## Catatan
- Jika terdapat masalah bisa menghubungi [email ajiekusumadhany@gmail.com](mailto:ajiekusumadhany@gmail.com)
