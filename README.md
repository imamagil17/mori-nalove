# Flood-Vision (Sistem Mitigasi Banjir Cerdas)

<p align="center">
  <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" /></a>
  <a href="https://fastapi.tiangolo.com"><img src="https://img.shields.io/badge/FastAPI-009688?style=for-the-badge&logo=fastapi&logoColor=white" alt="FastAPI" /></a>
  <a href="https://python.org"><img src="https://img.shields.io/badge/Python-3776AB?style=for-the-badge&logo=python&logoColor=white" alt="Python" /></a>
  <a href="https://tailwindcss.com"><img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS" /></a>
  <a href="https://core.telegram.org/bots/api"><img src="https://img.shields.io/badge/Telegram_Bot_API-26A69A?style=for-the-badge&logo=telegram&logoColor=white" alt="Telegram API" /></a>
</p>

---

## 🛰️ 1. Header & High-Tech Introduction

**Flood-Vision** adalah platform mitigasi bencana banjir cerdas hibrida berkinerja tinggi yang dirancang khusus untuk memantau, mendeteksi, dan mengumumkan status level air sungai secara *real-time*. Platform ini beroperasi dengan presisi tingkat tinggi untuk meminimalkan risiko bencana banjir di zona rawan dan siaga tinggi, seperti **Sungai Gumbasa**, **Sungai Lariang**, dan **Sungai Ngatabaru**.

Sistem ini mengadopsi arsitektur *decoupled hybrid architecture* yang memisahkan beban kerja pemrosesan aplikasi menjadi tiga pilar utama:
1. **State Management & Front-End Dashboard Engine (Laravel PHP):** Mengelola autentikasi pengguna, dasbor interaktif, kontrol administratif, penyimpanan basis data relasional, dan otorisasi keamanan tingkat tinggi.
2. **AI Inference & Deep Learning Microservice (Python FastAPI):** Node independen berlatensi rendah khusus untuk inferensi model visi komputer mendeteksi ketinggian permukaan air sungai dari kamera pengawas.
3. **Automated Emergency Broadcasting Pipeline (Telegram Bot API):** Mengirimkan peringatan bencana instan secara langsung ke perangkat warga dan tim penolong di lapangan.

### 🧠 Deep Learning Core: YOLOv26 Nano Integration
Jantung kecerdasan buatan dari Flood-Vision bertumpu pada arsitektur jaringan saraf konvolusional **YOLOv26 Nano** yang telah dispesialisasikan melalui *custom-training* menggunakan bobot model teroptimasi (`.pt`). Model ini dilatih secara khusus untuk mengekstrak fitur garis batas air, mendeteksi laju fluktuasi ketinggian air sungai secara piksel-demi-piksel, serta menghitung status luapan secara langsung dari umpan *video stream* resolusi tinggi dengan kebutuhan komputasi minimal (*ultra-low edge footprint*).

---

### A. Citizen Portal (User Dashboard)
Gerbang interaksi bagi masyarakat sipil di kawasan rawan bencana untuk memantau keselamatan lingkungan mereka secara mandiri:
*   **Real-time Flood Tier Indicators:** Representasi visual tingkat kerawanan air secara instan berdasarkan klasifikasi terstandarisasi BPBD: **Aman** (Hijau), **Waspada** (Kuning), **Siaga** (Oranye), dan **Bahaya** (Merah).
*   **Automated Chart.js Historical Charts:** Visualisasi grafik garis interaktif yang memetakan histori fluktuasi level air secara langsung dari basis data dengan kemampuan pembaruan berkala.
*   **Secure Citizen-Reporting Form:** Form pelaporan mandiri terenkripsi untuk mengunggah bukti genangan air lokal secara instan, diproteksi penuh oleh Laravel Token CSRF untuk menghindari *cross-site request forgery*.
*   **Integrated NLP AI Chatbot Assistant:** Asisten percakapan cerdas bertenaga LLM yang terintegrasi secara asinkron untuk memberikan panduan mitigasi darurat secara interaktif kapan saja.
*   **Evacuation Guide Modal:** Modal informasi berbasis *glassmorphism* yang menyediakan rute evakuasi terdekat dan daftar perlengkapan tas siaga bencana.

### B. Command Center (Admin Dashboard)
Pusat kendali operasional bagi administrator kebencanaan untuk mengambil keputusan taktis secara instan:
*   **YOLOv26 Video Processing Queue Manager:** Panel visual untuk melacak antrean aliran video CCTV sungai aktif yang diproses oleh AI backend secara berurutan.
*   **WITA Timezone-Synchronized Telegram Simulator:** Simulasi peluncuran pesan peringatan dini manual ke publik yang disinkronkan presisi dengan Waktu Indonesia Tengah (**Asia/Makassar**), menampilkan pratinjau isi pesan sebelum disebarkan.
*   **Secure Citizen Report Verification Center:** Sistem pemrosesan laporan warga dengan *background virtual form generation* yang aman untuk mencegah injeksi data saat admin melakukan validasi status laporan dari pending menjadi terverifikasi.
*   **News & Announcement CRUD Panels:** Manajemen konten berita dan pengumuman kebencanaan terpusat untuk disebarkan secara dinamis ke halaman depan publik maupun portal warga.

### C. Public Welcome Page
Halaman pendarat (*landing page*) publik bernuansa premium bergaya Bento Box Layout yang menampilkan:
*   **Real-time Status Feed:** Ringkasan tingkat air sungai terkini beserta cuaca lokal.
*   **Core Intelligence Deep Dive Section:** Pembongkaran ilmiah mengenai cara kerja **YOLOv26 Nano** dalam mengekstrak fitur spasial air sungai (*Feature Extraction*) hingga mencapai kecepatan kalkulasi milidetik (*Ultra-Fast Inference*).
*   **Public Information Board:** Menampilkan rilis berita kebencanaan resmi terbaru agar dapat diakses instan oleh publik tanpa perlu autentikasi masuk terlebih dahulu.

---

## 3. Step-by-Step Installation & Deployment Guide

Ikuti instruksi teknis di bawah ini untuk memasang dan menjalankan ekosistem Flood-Vision secara lokal di mesin pengembangan Anda:

### Langkah 1: Kloning Repositori
Jalankan perintah Git berikut untuk menyalin kode sumber ke komputer Anda:
```bash
git clone https://github.com/imamagil17/flood-vision.git
cd flood-vision
```

### Langkah 2: Pemasangan Dependensi Proyek
Pasang paket dependensi PHP backend (Laravel) dan pustaka JavaScript frontend (Vite/Node):
```bash
# Memasang dependensi Laravel
composer install

# Memasang dependensi frontend
npm install
```

### Langkah 3: Konfigurasi Environment Variables
Salin berkas konfigurasi *template* bawaan ke berkas aktif `.env`:
```bash
cp .env.example .env
```

Buka file `.env` yang baru saja dibuat, lalu konfigurasikan parameter koneksi database MySQL, kunci aplikasi, serta integrasi eksternal Telegram Anda:
```ini
APP_NAME=Flood-Vision
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

# Konfigurasi Database Utama
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=flood_vision_db
DB_USERNAME=root
DB_PASSWORD=

# Konfigurasi Integrasi Telegram Broadcast (WITA/Asia/Makassar)
TELEGRAM_BOT_TOKEN="your_telegram_bot_token_here"
TELEGRAM_CHAT_ID="your_telegram_chat_channel_id_here"

# Konfigurasi Alamat API YOLOv26 FastAPI Inference Backend
FASTAPI_AI_URL="http://127.0.0.1:8000"
```

### Langkah 4: Enkripsi Kunci & Migrasi Skema Database
Bangun struktur tabel database relasional beserta data benih awal (*seeding*):
```bash
# Membuat application key baru
php artisan key:generate

# Migrasi database dengan seeder bawaan
php artisan migrate --seed
```

> [!IMPORTANT]
> Proses migrasi akan secara otomatis menyusun skema tabel dengan batasan tipe data kolom ENUM berikut:
> *   Kolom `tingkat_genangan` pada tabel laporan: `ENUM('Rendah', 'Sedang', 'Tinggi')`
> *   Kolom `status` pada tabel verifikasi laporan: `ENUM('Pending', 'Terverifikasi')`

### Langkah 5: Menjalankan Server Aplikasi
Jalankan kompilator aset frontend dan server lokal Laravel secara bersamaan:

**Terminal 1 (Vite Asset Compiler):**
```bash
npm run dev
```

**Terminal 2 (Laravel Web Server):**
```bash
php artisan serve
```

Aplikasi web Flood-Vision kini aktif dan siap diakses melalui tautan peramban Anda di: **`http://127.0.0.1:8000`**

---

## 4. Application Operational Walkthrough

Berikut adalah skenario alur kerja operasional penggunaan sistem Flood-Vision dari sisi Warga hingga Administrator:

### Skenario A: Warga (Citizen User Flow)
1. **Autentikasi Akun:** Warga membuka situs web utama, masuk ke halaman pendaftaran/login, dan memasukkan kredensial akun warga mereka.
2. **Akses Dashboard:** Setelah masuk, warga diarahkan ke *Citizen Portal* yang menampilkan status tingkat bahaya terkini (misal: **Waspada**), lengkap dengan grafik dinamis Chart.js yang terus diperbarui.
3. **Mengajukan Laporan Kebencanaan:**
    *   Warga menavigasi ke bagian bento box **"Laporkan Genangan Air"**.
    *   Warga mengisi detail lokasi (contoh: *Sekitar Jembatan Sungai Gumbasa*), memilih klasifikasi genangan dari opsi *dropdown* (`Tinggi` / `Sedang` / `Rendah`), memasukkan deskripsi kondisi visual, lalu menekan tombol **Kirim Laporan**.
    *   Sistem memvalidasi token CSRF secara otomatis di latar belakang, memverifikasi kecocokan tipe data ENUM database, dan menyimpan data laporan dengan status default `'Pending'`.

### Skenario B: Administrator (Command Center Flow)
1. **Pemantauan Real-time & Deteksi AI:** Admin memantau live feeds dari kamera pemantau sungai di *Command Center*. Model **YOLOv26 Nano** di backend FastAPI secara konstan memproses bingkai video, melacak tinggi batas air sungai, dan memetakan data numerik kembali ke server Laravel.
2. **Verifikasi Laporan Masuk:**
    *   Admin melihat daftar laporan warga di panel **"Verifikasi Laporan Darurat"**. Laporan yang baru dikirim oleh warga akan berstatus berwarna kuning (`Pending`).
    *   Admin mengklik tombol **Verifikasi**. Sistem memicu pembuatan *background virtual form* aman yang memproses permintaan verifikasi secara asinkron.
    *   Setelah disetujui, status laporan di basis data berubah menjadi hijau (`Terverifikasi`).
3. **Simulasi & Publikasi Pesan Peringatan (Telegram Alert):**
    *   Apabila model AI mendeteksi tinggi air melampaui ambang batas kritis (contoh: naik drastis di *Sungai Ngatabaru*), Admin menuju panel **"Telegram Emergency Broadcast Panel"**.
    *   Pesan peringatan dini disusun secara otomatis oleh sistem, lengkap dengan stempel waktu real-time berzona **WITA (Asia/Makassar)**.
    *   Admin menekan tombol **"Test-Fire Payload"** untuk mensimulasikan pengiriman.
    *   Sinyal HTTP dikirim ke Telegram Bot API, dan detik itu juga, bot Flood-Vision menyiarkan pesan peringatan evakuasi massal resmi ke saluran publik Telegram.

---

## 5. Official Credits & Dedication

Aplikasi **Flood-Vision (Sistem Mitigasi Banjir Cerdas)** didekasikan sebagai bagian dari luaran proyek riset terapan inovasi teknologi mitigasi bencana alam.

*   **Pengembang Utama:** Kelompok 12
*   **Institusi:** Program Studi Teknik Informatika & Sistem Informasi, Jurusan Teknologi Informasi, Fakultas Teknik, **Universitas Tadulako**, Palu, Sulawesi Tengah.

*Dipersembahkan untuk keselamatan masyarakat dan pengelolaan risiko bencana lingkungan yang lebih cerdas, responsif, dan berbasis data di Indonesia.*
