# SIPBANGDES

Perencanaan pembangunan desa membutuhkan proses yang tertib, transparan, dan dapat dipertanggungjawabkan, khususnya dalam penyusunan Rencana Anggaran Biaya (RAB). Selama ini, proses penyusunan RAB masih banyak dilakukan secara manual (Excel), rawan kesalahan perhitungan, sulit direvisi, dan tidak terintegrasi dengan proses persetujuan berjenjang.

## Tech Stack

Proyek SIPBANGDES dibangun menggunakan teknologi berikut:

-   **Laravel 12**: Framework PHP untuk backend development.
-   **React 19**: Library JavaScript untuk membangun antarmuka pengguna yang dinamis.
-   **Inertia.js**: Menghubungkan Laravel dan React untuk pengalaman pengembangan modern tanpa API penuh.
-   **MySQL**: Database relasional untuk menyimpan data pengguna, kursus, dan lainnya.
-   **Breeze**: Paket autentikasi Laravel untuk fitur login, register, verifikasi email, lupa kata sandi, dan ubah kata sandi.
-   **Spatie**: Paket Laravel untuk manajemen peran (role) dan izin (permission).
-   **Cloudinary**: Layanan penyimpanan cloud untuk mengelola aset media seperti gambar dan video.

## Langkah-Langkah Fork Repository

Untuk berkontribusi pada proyek ini, Anda dapat melakukan fork repository terlebih dahulu. Berikut adalah langkah-langkahnya:

1. **Kunjungi Repository di GitHub**
    - Buka halaman repository sipbangdes di GitHub: `https://github.com/irsyamokta/sipbangdes`.
2. **Fork Repository**
    - Klik tombol **Fork** di pojok kanan atas halaman repository.
    - Pilih akun atau organisasi tempat Anda ingin menyimpan fork.
3. **Clone Forked Repository**
    - Salin URL fork Anda dan clone ke lokal:
        ```bash
        git clone <URL_FORK_ANDA>
        cd sipbangdes
        ```
4. **Tambahkan Upstream Repository**
    - Tambahkan repository asli sebagai upstream untuk tetap mendapatkan pembaruan:
        ```bash
        git remote add upstream <URL_REPOSITORY_ASLI>
        ```
5. **Buat Branch untuk Perubahan**
    - Buat branch baru untuk perubahan Anda:
        ```bash
        git checkout -b <NAMA_BRANCH_ANDA>
        ```
6. **Lakukan Perubahan dan Commit**
    - Lakukan perubahan pada kode, kemudian commit:
        ```bash
        git add .
        git commit -m "Deskripsi perubahan Anda"
        ```
7. **Push ke Fork Anda**
    - Push perubahan ke fork Anda di GitHub:
        ```bash
        git push origin <NAMA_BRANCH_ANDA>
        ```
8. **Buat Pull Request**
    - Buka fork Anda di GitHub, lalu klik **Compare & pull request**.
    - Isi deskripsi pull request dan kirim untuk ditinjau.

## Langkah-Langkah Instalasi

Berikut adalah panduan untuk mengatur dan menjalankan proyek SIPBANGDES di lingkungan lokal Anda:

### Prasyarat

-   PHP >= 8.3
-   Composer
-   Node.js >= 20.x
-   Local Web Server (XAMPP/Laragon)
-   Akun Cloudinary (untuk penyimpanan media)
-   Akun Google AI Studio (untuk integrasi model AI Gemini)
-   Git

### Langkah Instalasi

1.  **Clone Repository**

    ```bash
    git clone <URL_FORK_ANDA>
    cd sipbangdes
    ```

2.  **Instal Dependensi PHP**

    ```bash
    composer install
    ```

3.  **Instal Dependensi JavaScript**

    ```bash
    npm install
    ```

4.  **Konfigurasi Environment**

    -   Salin file `.env.example` menjadi `.env`:
        ```bash
        cp .env.example .env
        ```
    -   Sesuaikan konfigurasi database dan Cloudinary di file `.env`:
        ```env
        CLOUDINARY_KEY=
        CLOUDINARY_SECRET=
        CLOUDINARY_CLOUD_NAME=
        CLOUDINARY_URL=
        CLOUDINARY_UPLOAD_PRESET=
        CLOUDINARY_NOTIFICATION_URL=

        GEMINI_API_KEY=
        GEMINI_API_URL=

        ```

5.  **Generate Application Key**

    ```bash
    php artisan key:generate
    ```

6.  **Migrasi Database dan Seed Data**

    ```bash
    php artisan migrate --seed
    ```

7.  **Kompilasi Aset Frontend**

    ```bash
    npm run dev
    ```

8.  **Jalankan Server Lokal**

    ```bash
    php artisan serve --host=localhost
    ```

9.  **Akses Aplikasi**
    - Buka browser dan kunjungi `http://localhost:8000`.

### Catatan Tambahan

-   Pastikan Anda memiliki koneksi internet untuk mengakses layanan Cloudinary.
-   Jika Anda ingin menggunakan data seeder untuk pengujian, pastikan untuk memeriksa file seeder di direktori `database/seeders`.
-   Untuk pengembangan lebih lanjut, pastikan untuk menjalankan `npm run dev` setiap kali ada perubahan pada file React.

## File yang Tidak Boleh Di-Push ke GitHub

Untuk menjaga keamanan dan integritas proyek, beberapa file tidak boleh di-push ke repository GitHub. Pastikan file-file berikut sudah ditambahkan ke `.gitignore`:

-   **File `.env`**: Berisi informasi sensitif seperti kunci API Cloudinary, kredensial database, dan kunci aplikasi Laravel.
-   **Direktori `vendor/`**: Berisi dependensi Composer yang dapat diinstal ulang dengan `composer install`.
-   **Direktori `node_modules/`**: Berisi dependensi Node.js yang dapat diinstal ulang dengan `npm install`.
-   **File Cache dan Log**:
    -   `storage/logs/*`: File log aplikasi.
    -   `storage/framework/cache/*`: File cache Laravel.
    -   `storage/framework/sessions/*`: File sesi pengguna.
    -   `storage/framework/views/*`: File tampilan yang di-cache.
-   **File Konfigurasi Sensitif**:
    -   File apa pun yang berisi kredensial atau informasi sensitif lainnya, seperti file konfigurasi khusus untuk lingkungan lokal.

Pastikan untuk memeriksa `.gitignore` sebelum melakukan commit untuk memastikan file-file di atas tidak di-push.

## Kontribusi

Jika Anda ingin berkontribusi pada proyek ini, silakan buat _pull request_ dengan perubahan yang diusulkan. Pastikan untuk mengikuti pedoman kode yang ada dan menjalankan pengujian sebelum mengirimkan perubahan.

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).
