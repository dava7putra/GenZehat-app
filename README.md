# 🌟 GenZehat - Calisthenics Workout Tracker (Fullstack Project)

![Laravel](https://img.shields.io/badge/Web_Backend-Laravel_10-FF2D20?logo=laravel&logoColor=white)
![Android](https://img.shields.io/badge/Mobile_App-Android_Java-3DDC84?logo=android&logoColor=white)
![MySQL](https://img.shields.io/badge/Database-MySQL-4479A1?logo=mysql&logoColor=white)

Selamat datang di repositori resmi **GenZehat**! 
GenZehat adalah sebuah platform ekosistem ganda (*Web* dan *Mobile*) yang dirancang khusus untuk membantu Anda melacak, mencatat, dan mengelola jadwal latihan *Calisthenics* (kalistenik) secara disiplin dan terstruktur.

Proyek ini terdiri dari dua bagian utama yang saling terhubung secara *real-time*:
1. **GenZehat Web:** Berfungsi sebagai *dashboard* utama dan *server* penyedia API (dibangun dengan Laravel).
2. **GenZehat Mobile:** Aplikasi pendamping di *smartphone* Android agar Anda bisa melihat jadwal dan riwayat latihan dari mana saja.

---

## ✨ Fitur Utama
* **Satu Akun untuk Semua:** Cukup buat akun di Web, dan gunakan akun yang sama untuk *login* di aplikasi Android.
* **Sinkronisasi Real-time:** Data riwayat latihan yang Anda selesaikan di Web akan langsung muncul di HP Anda.
* **Sistem Keamanan Token:** Aplikasi mobile dilengkapi fitur *Auto-Clean Token* dan *Secure Logout* untuk menjaga keamanan data Anda.
* **Antarmuka Responsif:** Nyaman dibuka di layar komputer (Web) maupun digenggam di tangan (Android).

---

## 🚀 Panduan Instalasi & Uji Coba (Untuk Pengguna Umum)

Ingin mencoba menjalankan GenZehat di laptop dan HP Anda sendiri? Sangat bisa! Ikuti langkah-langkah mudah di bawah ini.

### TAHAP 1: Menjalankan Server Web (Wajib)
Aplikasi Android tidak akan bisa berjalan jika *server* Web ini belum dinyalakan.

**Persiapan Alat:**
Pastikan laptop Anda sudah terinstal **XAMPP** (atau Laragon), **PHP**, dan **Composer**.

**Langkah-langkah:**
1. Hidupkan modul **Apache** dan **MySQL** di aplikasi XAMPP Anda.
2. Buat database baru di `localhost/phpmyadmin` dengan nama: **`genzehat`**
3. Buka Terminal/CMD, lalu unduh proyek ini:
   ```bash
   git clone [https://github.com/USERNAME_GITHUB_ANDA/NAMA_REPO_ANDA.git](https://github.com/USERNAME_GITHUB_ANDA/NAMA_REPO_ANDA.git)
   ```
   *(Catatan: Ganti URL di atas dengan link repositori ini).*
4. Masuk ke folder web-nya: `cd NAMA_REPO_ANDA/genzehat-web`
5. Instal komponen yang dibutuhkan:
   ```bash
   composer install
   ```
6. Salin file pengaturan lingkungan:
   ```bash
   cp .env.example .env
   ```
   *(Buka file `.env` yang baru muncul, pastikan `DB_DATABASE=genzehat`).*
7. Kunci aplikasi dan buat kerangka database:
   ```bash
   php artisan key:generate
   php artisan migrate
   ```
8. **Jalankan Server (Penting):** Agar HP Anda bisa terhubung ke laptop, jalankan perintah ini:
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```
   *Biarkan CMD ini tetap terbuka!* Sekarang Anda bisa membuka webnya di browser laptop dengan mengetik: `http://localhost:8000`

---

### TAHAP 2: Menjalankan Aplikasi Mobile Android
Setelah server menyala, sekarang saatnya memasang aplikasinya di HP/Emulator Anda!

**Cara Instan (Via APK):**
1. Buka menu **Releases** di halaman GitHub ini, atau cari file bernama **`GenZehat.apk`**.
2. Unduh file APK tersebut.
3. Kirim ke HP Android Anda dan lakukan Instalasi (Izinkan "Install from Unknown Sources").
4. Buka aplikasi, lalu coba *Login* menggunakan akun yang sudah Anda daftarkan di Web.

> **⚠️ CATATAN JARINGAN (PENTING):**
> Agar aplikasi Android bisa terhubung ke laptop Anda, pastikan HP dan Laptop Anda **terhubung ke jaringan WiFi yang sama**. Jika file APK gagal terhubung (karena perbedaan IP Address lokal), Anda harus melakukan *Build* ulang secara mandiri menggunakan Android Studio (Panduan lengkapnya ada di dalam folder Android).

---

## 📂 Struktur Repositori
Repositori ini dibagi menjadi dua folder utama untuk memudahkan pengembangan:
* 📁 **`/genzehat-web`** : Berisi *source code* Laravel untuk tampilan *website*, database, dan sistem *Backend/API*.
* 📁 **`/genzehat-android`** : Berisi *source code* Java/XML untuk aplikasi *Mobile* Android.

*(Setiap folder memiliki `README.md` spesifiknya masing-masing untuk panduan teknis lanjutan).*

---
**Developed with ☕ by [Dava Anugrah Putra]**
