# MelChat - Aplikasi Chat Real-Time 

MelChat adalah aplikasi obrolan (*chatting*) berbasis web yang memungkinkan pengguna untuk bertukar pesan secara *real-time* tanpa perlu memuat ulang (*refresh*) halaman. Aplikasi ini dibangun dengan framework **Laravel 12** dan memanfaatkan teknologi **Laravel Reverb** sebagai server WebSockets.

Pembuat :
* **Nama:** Melati Nur Sabila
* **NIM:** 240180044

---

## 🌟 Fitur Utama
* **Autentikasi User:** Login dan Registrasi aman menggunakan Laravel Breeze.
* **Private Chat (Japri):** Kirim pesan secara personal (*one-on-one*) dengan pengguna lain.
* **Group Chat:** Buat grup, tambahkan banyak anggota, dan mengobrol bersama dalam satu ruang diskusi.
* **Real-Time Messaging:** Pesan masuk secara instan berkat integrasi Laravel Echo dan Reverb.
* **Status Online (Presence):** Indikator visual otomatis (🟢) untuk melihat siapa saja teman yang sedang aktif membuka halaman aplikasi.
* **Modern UI/UX:** Tampilan antarmuka yang cantik, *dark-mode*, dan dirancang responsif.

---

## 🛠️ Persyaratan Sistem
Sebelum menjalankan aplikasi, pastikan komputer Anda telah terinstal:
* PHP (versi 8.2 atau lebih baru)
* Composer
* Node.js & NPM
* Database SQLite (sudah otomatis disiapkan) atau MySQL/MariaDB.

---

## 🚀 Cara Instalasi & Menjalankan Aplikasi

Ikuti langkah-langkah mudah di bawah ini untuk menjalankan aplikasi MelChat di komputer Anda:

### 1. Kloning Repository
Buka terminal/CMD, lalu jalankan perintah ini untuk menyalin kode sumber:
```bash
git clone https://github.com/MelatiNurr/web-chat-app-melati.git
cd web-chat-app-melati
```

### 2. Instal Dependensi
Instal semua modul PHP dan JavaScript yang dibutuhkan oleh aplikasi:
```bash
composer install
npm install
```

### 3. Konfigurasi Environment (Lingkungan)
Salin file konfigurasi bawaan agar bisa diisi dengan pengaturan lokal komputer Anda:
```bash
cp .env.example .env
```
*(Catatan: Pengguna Windows dapat mengetik perintah `copy .env.example .env` di CMD).*

Lalu hasilkan kunci aplikasi (App Key):
```bash
php artisan key:generate
```

### 4. Siapkan Database
Aplikasi ini sudah diatur agar siap menggunakan database bawaan (SQLite). Jalankan migrasi untuk membuat tabel-tabel database:
```bash
php artisan migrate
```

### 5. Jalankan Aplikasi
Agar sistem real-time berjalan sempurna, Anda perlu menyalakan **3 server (mesin) sekaligus** secara bersamaan. Silakan buka **3 jendela Terminal/CMD** baru, dan jalankan satu perintah di setiap jendela:

**Terminal 1 (Menjalankan Web Server Utama):**
```bash
php artisan serve
```

**Terminal 2 (Menjalankan Server WebSockets / Real-Time):**
```bash
php artisan reverb:start
```

**Terminal 3 (Menjalankan Asset Tampilan CSS/JS):**
```bash
npm run dev
```

### 6. Buka Aplikasi di Browser
Setelah ketiga terminal di atas berjalan, buka browser kesayangan Anda dan akses alamat berikut:
👉 **[http://localhost:8000](http://localhost:8000)**

Silakan buat 2 akun berbeda (gunakan *Incognito Mode* untuk akun kedua) dan rasakan pengalaman chating *real-time*-nya! 🎉
