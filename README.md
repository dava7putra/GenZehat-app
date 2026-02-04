# GenZehat - Calisthenics Workout Tracker

**GenZehat** adalah aplikasi berbasis web yang dirancang untuk membantu pengguna memantau progres latihan Calisthenics (olahraga beban tubuh) secara terstruktur, disiplin, dan terukur.

---

## 1. Deskripsi Project
Aplikasi ini dibangun menggunakan Framework **Laravel 11** dengan arsitektur **MVC (Model-View-Controller)**. GenZehat menyelesaikan masalah pengguna yang ingin berolahraga namun tidak memiliki akses ke gym atau alat berat.

**Keunggulan Utama:**
* **Tanpa Alat:** Fokus pada gerakan *Bodyweight* (Push Up, Squat, Plank, dll).
* **Sistem Level:** Tersedia 3 tingkatan (Pemula, Menengah, Profesional).
* **Tracking Real-time:** Pengguna dapat menkamui hari latihan (Selesai/Terlewat).
* **Visualisasi Data:** Grafik interaktif untuk memantau konsistensi mingguan.
* **Efisiensi Data:** Menggunakan teknik denormalisasi JSON pada database untuk performa tinggi.

---

## 2. User Story
Berikut adalah narasi kebutuhan pengguna dalam aplikasi ini:

* **Sebagai Pengguna Baru**, saya ingin mendaftar akun dan memilih tingkat kesulitan latihan agar sesuai dengan kemampuan fisik saya.
* **Sebagai Pengguna**, saya ingin melihat jadwal latihan harian yang sudah disusun otomatis agar saya tidak bingung harus melakukan gerakan apa.
* **Sebagai Pengguna**, saya ingin melihat instruksi detail setiap gerakan (Set & Reps) agar tidak salah melakukan teknik.
* **Sebagai Pengguna**, saya ingin menkamui status latihan ("Selesai" atau "Terlewat") untuk mencatat kedisiplinan saya.
* **Sebagai Pengguna**, saya ingin melihat grafik perkembangan saya setiap minggu untuk evaluasi diri.

---

## 3. SRS (Software Requirements Specification)

### Feature List (Daftar Fitur)
1.  **Authentication System:**
    * Login, Register, dan Logout aman menggunakan Laravel Auth & Session.
    * Proteksi Route menggunakan Middleware.
2.  **Dashboard Latihan:**
    * Tampilan jadwal 7 hari (Senin - Minggu).
    * Indikator status visual (Hijau = Selesai, Merah = Terlewat).
    * Penanda "HARI INI" otomatis berdasarkan tanggal sistem.
3.  **Manajemen Level:**
    * User dapat mengganti level (Pemula/Menengah/Pro) kapan saja.
    * Data jadwal otomatis berubah sesuai level yang dipilih.
4.  **Workout Detail (Modal):**
    * Popup informasi berisi nama gerakan, jumlah repetisi, dan deskripsi cara melakukan gerakan.
5.  **Progress Tracking:**
    * **Bar Chart:** Statistik mingguan (Jumlah Selesai vs Terlewat).
    * **Line Chart:** Grafik garis riwayat progres antar minggu.
    * **Archive System:** Fitur "Simpan & Reset" untuk menyimpan data minggu ini ke history dan memulai minggu baru.

---

## 4. UML (Unified Modeling Language)

> *Catatan: Gambar diagram di bawah ini merepresentasikan alur sistem.*

### A. Use Case Diagram
Menggambarkan interaksi User dengan Sistem:
* User -> Login/Register
* User -> Mengelola Jadwal (Update Status)
* User -> Mengganti Level
* User -> Melihat Laporan (Grafik/History)

![Use Case Diagram](docs/usecase.png)
*(Simpan gambar Use Case kamu di folder `docs` lalu ganti nama filenya di sini)*

### B. Activity Diagram
Alur aktivitas utama (Menyimpan Progress):
1.  User Login -> Masuk Dashboard.
2.  Sistem menampilkan jadwal.
3.  User klik tombol "Selesai" pada hari tertentu.
4.  Sistem mengirim data via AJAX (Fetch) ke Controller.
5.  Controller menyimpan data JSON ke Database.
6.  Tampilan web diperbarui tanpa reload (Asynchronous).

![Activity Diagram](docs/activity.png)
*(Simpan gambar Activity Diagram kamu di sini)*

### C. Sequence Diagram
Alur teknis pengiriman data:
1.  **View (JS):** `saveProgress()` dipanggil -> Mengirim request POST.
2.  **Route:** `/save-progress` meneruskan ke Controller.
3.  **Controller:** `FitnessController@saveProgress` menerima JSON.
4.  **Model:** `User.php` melakukan casting JSON ke Array.
5.  **Database:** Menyimpan ke tabel `users` kolom `progress_data`.

![Sequence Diagram](docs/sequence.png)
*(Simpan gambar Sequence Diagram kamu di sini)*

---

## 5. Mock-Up (Tampilan Aplikasi)

### Halaman Dashboard & Jadwal
Tampilan utama dimana user melihat jadwal latihan mingguan.
![Dashboard](docs/mockup_dashboard.png)

### Detail Instruksi Latihan
Tampilan Modal saat user mengklik salah satu hari.
![Detail Modal](docs/mockup_detail.png)

### Grafik & Statistik
Visualisasi data menggunakan Chart.js.
![Grafik](docs/mockup_chart.png)

---

## 6. Instalasi & Menjalankan Project

Jika ingin menjalankan project ini di komputer lokal (Localhost):

1.  **Clone Repository:**
    ```bash
    git clone [https://github.com/username-kamu/GenZehat.git](https://github.com/username-kamu/GenZehat.git)
    cd GenZehat
    ```

2.  **Install Dependensi PHP:**
    ```bash
    composer install
    ```

3.  **Setup Environment:**
    * Copy file `.env.example` menjadi `.env`.
    * Atur konfigurasi database (DB_DATABASE, DB_USERNAME, dll).

4.  **Generate Key & Migrasi Database:**
    ```bash
    php artisan key:generate
    php artisan migrate
    ```

5.  **Jalankan Server:**
    ```bash
    php artisan serve
    ```
    Buka browser dan akses: `http://localhost:8000`

---

**Dibuat oleh:** [Nama Kamu] - [NIM/Identitas]
