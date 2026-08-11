# 🧺 Sofia Laundry Management System

Sistem manajemen laundry berbasis web yang dibangun dengan **Laravel 12** dan **AdminLTE 3**. Mendukung pengelolaan transaksi, booking, stok barang, laporan, serta notifikasi otomatis via **WhatsApp Gateway**.

---

## 📋 Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Teknologi Stack](#teknologi-stack)
- [Struktur Proyek](#struktur-proyek)
- [Database & Relasi](#database--relasi)
- [API Endpoints](#api-endpoints)
- [Web Routes (Admin)](#web-routes-admin)
- [Controllers](#controllers)
- [Models](#models)
- [Services](#services)
- [Role & Akses](#role--akses)
- [Instalasi & Setup](#instalasi--setup)
- [Konfigurasi Environment](#konfigurasi-environment)
- [Alur Bisnis](#alur-bisnis)
- [Catatan Pengembangan](#catatan-pengembangan)

---

## Fitur Utama

| Modul | Deskripsi |
|-------|-----------|
| **Manajemen Pelanggan** | CRUD data pelanggan, riwayat transaksi |
| **Manajemen Layanan** | CRUD layanan laundry dengan harga per kg |
| **Manajemen Transaksi** | Pencatatan cucian masuk/keluar, tracking status |
| **Manajemen Booking** | Booking jadwal dengan opsi antar-jemput |
| **Pengembalian Cucian** | Tracking pengambilan cucian + notifikasi WA |
| **Stok Barang** | Manajemen stok pewangi & bahan, alert minimum |
| **Laporan** | Laporan transaksi & pelanggan, export PDF |
| **Dashboard** | Statistik real-time (pendapatan, status cucian) |
| **WhatsApp Notifikasi** | Notifikasi otomatis ke pelanggan via WA Gateway |
| **Autentikasi** | Login web (session) + API token (Sanctum) |

---

## Teknologi Stack

| Layer | Teknologi |
|-------|-----------|
| **Backend** | Laravel 12.0, PHP 8.2+ |
| **Database** | MySQL |
| **API Auth** | Laravel Sanctum 4.0 |
| **Frontend** | AdminLTE 3.15, Blade Templates |
| **Notifikasi** | WhatsApp Gateway API |
| **Testing** | PHPUnit 11.5.3 |
| **Dev Tools** | Laravel Sail, Vite, Laravel Pint |

---

## Struktur Proyek

```
app/
├── Http/
│   └── Controllers/
│       ├── AuthController.php          # Autentikasi API
│       ├── DashboardController.php     # Statistik dashboard (API)
│       ├── PelangganController.php     # CRUD pelanggan
│       ├── LayananController.php       # CRUD layanan
│       ├── UserController.php          # CRUD user
│       ├── TransaksiController.php     # CRUD transaksi + stok
│       ├── PengembalianController.php  # CRUD pengembalian + WA
│       ├── StokBarangController.php    # CRUD stok + alert
│       ├── BookingController.php       # CRUD booking + auto transaksi
│       ├── LaporanTransaksiController.php
│       ├── LaporanPelangganController.php
│       ├── WhatsAppController.php      # WA Gateway
│       └── Web/
│           ├── AuthWebController.php   # Login/logout web
│           ├── DashboardController.php # Dashboard web
│           ├── PelangganWebController.php
│           └── UserWebController.php
├── Models/
│   ├── User.php
│   ├── Pelanggan.php
│   ├── Layanan.php
│   ├── Transaksi.php
│   ├── DetailTransaksi.php
│   ├── Pembayaran.php
│   ├── Pengembalian.php
│   ├── StokBarang.php
│   ├── Booking.php
│   ├── LaporanStok.php
│   └── LaporanTransaksi.php
├── Services/
│   └── WhatsAppService.php
└── Providers/
    └── AppServiceProvider.php

database/
├── migrations/         # 15 file migrasi
├── seeders/
└── factories/

resources/views/
├── admin/
│   ├── dashboard.blade.php
│   ├── pelanggans/
│   ├── layanans/
│   ├── transaksis/
│   ├── pengembalians/
│   ├── stok_barangs/
│   ├── users/
│   ├── bookings/
│   └── laporan/
│       └── pdf/
├── auth/
└── layouts/

routes/
├── api.php     # REST API routes
├── web.php     # Web admin routes
└── console.php
```

---

## Database & Relasi

### Skema Tabel

#### `users`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id_user | bigint PK | Primary key |
| nama_user | string | Nama lengkap |
| username | string | Username login |
| password | string | Bcrypt hash |
| role | enum | `admin`, `pimpinan` |
| ip_address | string | IP terakhir login |
| user_agent | string | Browser info |

#### `pelanggans`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id_pelanggan | bigint PK | Primary key |
| nama_pelanggan | string(100) | Nama pelanggan |
| no_hp | string(15) | Nomor HP (untuk WA) |
| alamat | text | Alamat lengkap |
| created_at | timestamp | Tanggal daftar |

#### `layanans`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id_layanan | bigint PK | Primary key |
| nama_layanan | string | Nama layanan |
| harga_per_kg | decimal(10,2) | Harga per kilogram |
| keterangan | text | Deskripsi layanan |

#### `transaksis`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id_transaksi | bigint PK | Primary key |
| id_pelanggan | FK | Relasi pelanggan |
| id_user | FK | Kasir/operator |
| id_pewangi | FK nullable | Stok pewangi dipilih |
| id_booking | FK nullable | Dari booking |
| tanggal_masuk | datetime | Cucian masuk |
| tanggal_selesai | datetime nullable | Cucian selesai |
| total_berat | decimal(5,2) | Total berat (kg) |
| total_harga | decimal(12,2) | Total biaya |
| status | enum | `proses`, `selesai`, `diambil` |

#### `detail_transaksis`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id_detail | bigint PK | Primary key |
| id_transaksi | FK | Relasi transaksi |
| id_layanan | FK | Layanan digunakan |
| berat | decimal | Berat untuk layanan ini |
| subtotal | decimal | Subtotal harga |

#### `pembayarans`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id_pembayaran | bigint PK | Primary key |
| id_transaksi | FK | Relasi transaksi |
| tanggal_bayar | datetime | Tanggal pembayaran |
| metode_bayar | enum | `cash`, `transfer` |
| jumlah_bayar | decimal | Jumlah dibayar |
| status_bayar | enum | `lunas`, `belum` |

#### `pengembalians`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id_pengembalian | bigint PK | Primary key |
| id_transaksi | FK | Relasi transaksi |
| tanggal_pengembalian | datetime | Tanggal dikembalikan |
| status_pengembalian | enum | `siap_diambil`, `sudah_diambil` |
| catatan | text nullable | Catatan tambahan |
| notifikasi_terkirim | boolean | Status kirim WA |
| tanggal_notifikasi | datetime nullable | Waktu notif dikirim |

#### `stok_barangs`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id_barang | bigint PK | Primary key |
| nama_barang | string | Nama barang/pewangi |
| satuan | string | Satuan (botol, liter, dll) |
| stok | integer | Jumlah stok saat ini |
| minimum_stok | integer | Batas minimum stok |

#### `bookings`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id_booking | bigint PK | Primary key |
| id_pelanggan | FK | Relasi pelanggan |
| id_layanan | FK | Layanan dipesan |
| tanggal_booking | date | Tanggal booking |
| waktu_booking | time nullable | Jam booking |
| estimasi_berat | decimal nullable | Estimasi berat (kg) |
| catatan | text nullable | Catatan pelanggan |
| tipe_antar_jemput | enum | `none`, `pickup`, `delivery`, `both` |
| alamat_jemput | text nullable | Alamat penjemputan |
| alamat_antar | text nullable | Alamat pengantaran |
| biaya_antar_jemput | decimal nullable | Biaya antar-jemput |
| status | enum | `pending`, `confirmed`, `cancelled`, `completed` |

### Diagram Relasi

```
User ──────────────────────────────────────────── (1:M) ──→ Transaksi
Pelanggan ─────────────────────────────────────── (1:M) ──→ Transaksi
Pelanggan ─────────────────────────────────────── (1:M) ──→ Booking
Layanan ────────────────────────────────────────── (1:M) ──→ DetailTransaksi
Layanan ────────────────────────────────────────── (1:M) ──→ Booking
Transaksi ──────────────────────────────────────── (1:M) ──→ DetailTransaksi
Transaksi ──────────────────────────────────────── (1:1) ──→ Pembayaran
Transaksi ──────────────────────────────────────── (1:1) ──→ Pengembalian
Transaksi ──────────────────────────────────────── (M:1) ──→ StokBarang (pewangi)
Transaksi ──────────────────────────────────────── (M:1) ──→ Booking
Booking ────────────────────────────────────────── (1:1) ──→ Transaksi
StokBarang ─────────────────────────────────────── (1:M) ──→ LaporanStok
```

---

## API Endpoints

Base URL: `/api`

### Autentikasi

| Method | Endpoint | Deskripsi | Auth |
|--------|----------|-----------|------|
| POST | `/register` | Daftar user baru | - |
| POST | `/login` | Login, return token | - |
| POST | `/logout` | Logout | ✅ Sanctum |
| GET | `/user` | Info user aktif | ✅ Sanctum |
| GET | `/data-pengguna` | Detail data pengguna | ✅ Sanctum |
| POST | `/update-user` | Update profil user | ✅ Sanctum |

### Master Data

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET/POST | `/pelanggans` | List / Tambah pelanggan |
| GET/PUT/DELETE | `/pelanggans/{id}` | Detail / Update / Hapus |
| GET/POST | `/layanans` | List / Tambah layanan |
| GET/PUT/DELETE | `/layanans/{id}` | Detail / Update / Hapus |
| GET/POST | `/users` | List / Tambah user |
| GET/PUT/DELETE | `/users/{id}` | Detail / Update / Hapus |
| GET/POST | `/stok-barangs` | List / Tambah stok |
| GET/PUT/DELETE | `/stok-barangs/{id}` | Detail / Update / Hapus |

### Transaksi & Pengembalian

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET/POST | `/transaksis` | List / Buat transaksi |
| GET/PUT/DELETE | `/transaksis/{id}` | Detail / Update / Hapus |
| GET/POST | `/pengembalians` | List / Buat pengembalian |
| GET/PUT/DELETE | `/pengembalians/{id}` | Detail / Update / Hapus |
| POST | `/pengembalians/{id}/resend` | Kirim ulang notif WA |

### Booking

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET/POST | `/bookings` | List / Buat booking |
| GET/PUT/DELETE | `/bookings/{id}` | Detail / Update / Hapus |

### Laporan

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/laporan/transaksi` | Data laporan transaksi |
| GET | `/laporan/transaksi/pdf` | Export PDF transaksi |
| GET | `/laporan/pelanggan` | Data laporan pelanggan |
| GET | `/laporan/pelanggan/pdf` | Export PDF pelanggan |

### WhatsApp & Dashboard

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/wa/status` | Cek status WA Gateway |
| POST | `/wa/send-message` | Kirim pesan WA manual |
| GET | `/dashboard` | Statistik dashboard |

---

## Web Routes (Admin)

Base URL: `/`

### Publik
| Route | Deskripsi |
|-------|-----------|
| `GET /login` | Halaman login |
| `POST /login` | Proses login |
| `POST /logout` | Logout |

### Admin (Memerlukan Login)

| Route | Deskripsi |
|-------|-----------|
| `GET /dashboard` | Dashboard utama |
| `GET /admin/pelanggans` | Manajemen pelanggan |
| `GET /admin/users` | Manajemen user |
| `GET /admin/layanans` | Manajemen layanan |
| `GET /admin/transaksis` | Manajemen transaksi |
| `GET /admin/stok-barangs` | Manajemen stok barang |
| `GET /admin/pengembalians` | Manajemen pengembalian |
| `GET /admin/bookings` | Manajemen booking |
| `GET /admin/laporan/transaksi` | Laporan transaksi |
| `GET /admin/laporan/pelanggan` | Laporan pelanggan |

---

## Controllers

### `AuthController`
Menangani autentikasi API (register, login, logout, profil user).

### `DashboardController`
Mengembalikan statistik real-time: total pelanggan, pelanggan baru 7 hari, pendapatan hari ini, jumlah cucian per status.

### `PelangganController`
CRUD pelanggan. Method `indexView()` untuk tampilan web, `index()` untuk API JSON.

### `LayananController`
CRUD layanan laundry beserta harga per kg.

### `UserController`
CRUD user sistem (admin/pimpinan).

### `TransaksiController`
CRUD transaksi dengan fitur:
- Validasi stok pewangi sebelum transaksi dibuat
- Otomatis kurangi stok pewangi saat transaksi dibuat
- Kembalikan stok jika transaksi dihapus atau pewangi diganti
- Semua operasi dalam database transaction

### `PengembalianController`
CRUD pengembalian cucian dengan fitur:
- Hanya transaksi berstatus `selesai` yang bisa dibuat pengembalian
- Kirim notifikasi WhatsApp otomatis saat pengembalian dibuat
- Method `resendNotification()` untuk kirim ulang notif WA

### `StokBarangController`
CRUD stok barang dengan endpoint tambahan `lowStock` untuk barang di bawah minimum stok.

### `BookingController`
CRUD booking dengan fitur:
- Opsi antar-jemput (pickup, delivery, both, none)
- Saat status booking diubah ke `confirmed`, transaksi otomatis dibuat
- Harga dihitung dari `estimasi_berat × harga_per_kg`

### `LaporanTransaksiController` & `LaporanPelangganController`
Laporan dengan filter tanggal dan export PDF.

### `WhatsAppController`
Proxy ke WhatsApp Gateway untuk cek status dan kirim pesan manual.

---

## Models

| Model | Tabel | Primary Key | Timestamps |
|-------|-------|-------------|------------|
| `User` | users | id_user | ✅ |
| `Pelanggan` | pelanggans | id_pelanggan | created_at only |
| `Layanan` | layanans | id_layanan | ✅ |
| `Transaksi` | transaksis | id_transaksi | ✅ |
| `DetailTransaksi` | detail_transaksis | id_detail | ✅ |
| `Pembayaran` | pembayarans | id_pembayaran | ✅ |
| `Pengembalian` | pengembalians | id_pengembalian | ✅ |
| `StokBarang` | stok_barangs | id_barang | ✅ |
| `Booking` | bookings | id_booking | ✅ |
| `LaporanStok` | laporan_stoks | id_laporan_stok | ✅ |
| `LaporanTransaksi` | laporan_transaksis | id_laporan | ✅ |

### Accessor Khusus

**`Pengembalian::getTotalBayarAttribute()`**
Menghitung total bayar termasuk biaya antar-jemput jika transaksi berasal dari booking.

**`StokBarang::isLowStock()`**
Mengecek apakah stok barang sudah di bawah atau sama dengan minimum stok.

---

## Services

### `WhatsAppService`

Lokasi: `app/Services/WhatsAppService.php`

Mengelola pengiriman notifikasi WhatsApp ke pelanggan.

**Method:**

| Method | Parameter | Deskripsi |
|--------|-----------|-----------|
| `sendNotification($phone, $message)` | string, string | Kirim pesan WA ke nomor tertentu |
| `sendPengembalianNotification($pengembalian)` | Pengembalian | Kirim notif cucian siap diambil |
| `getPengembalianTemplate($nama, $transaksi)` | string, Transaksi | Generate template pesan |

**Format Nomor Telepon:**
- Input: `08xxx`, `628xxx`, atau `62xxx`
- Output: selalu diformat ke `628xxx`
- Validasi regex: `/^62[0-9]{9,13}$/`

**Template Pesan Pengembalian:**
```
🧺 LAUNDRY SIAP DIAMBIL 🧺

Halo [Nama Pelanggan],

Laundry Anda sudah selesai dan siap untuk diambil!

📋 Detail:
• Tanggal Selesai: DD/MM/YYYY
• Total Berat: X.XX Kg
• Total Harga: Rp X.XXX.XXX
• Pewangi: [nama pewangi] (jika ada)

Terima kasih telah menggunakan layanan kami! 🙏
```

---

## Role & Akses

| Role | Akses |
|------|-------|
| **admin** | Full access semua fitur |
| **pimpinan** | Akses dashboard & laporan (terbatas) |

**Middleware:**
- Web routes: `auth` (session-based)
- API routes: `auth:sanctum` (token-based)

---

## Instalasi & Setup

### Prasyarat
- PHP 8.2+
- Composer
- MySQL
- Node.js & NPM

### Langkah Instalasi

```bash
# 1. Clone repository
git clone <repo-url>
cd sofia-laundry

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Konfigurasi database di .env
# DB_DATABASE=dbsofialaundry
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Jalankan migrasi
php artisan migrate

# 6. (Opsional) Jalankan seeder
php artisan db:seed

# 7. Build assets
npm run build

# 8. Jalankan server
php artisan serve
```

Atau gunakan script setup otomatis:
```bash
composer run setup
```

### Menjalankan Development Server

```bash
composer run dev
```

Perintah ini menjalankan secara bersamaan:
- `php artisan serve` — Laravel server
- `php artisan queue:listen` — Queue worker
- `php artisan pail` — Log viewer
- `npm run dev` — Vite dev server

---

## Konfigurasi Environment

File `.env` yang perlu dikonfigurasi:

```env
# Aplikasi
APP_NAME="Sofia Laundry"
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dbsofialaundry
DB_USERNAME=root
DB_PASSWORD=

# WhatsApp Gateway
WHATSAPP_API_URL=http://localhost:3000/send-message
WHATSAPP_API_KEY=

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

---

## Alur Bisnis

### Alur Transaksi Normal

```
1. Pelanggan datang
2. Admin buat Transaksi baru
   └── Pilih pelanggan, layanan, berat, pewangi (opsional)
   └── Stok pewangi otomatis berkurang
3. Status: proses
4. Cucian selesai → Admin update status ke "selesai"
5. Admin buat Pengembalian
   └── Notifikasi WA otomatis dikirim ke pelanggan
6. Pelanggan ambil cucian → Update status ke "sudah_diambil"
7. Update status transaksi ke "diambil"
```

### Alur Booking

```
1. Pelanggan booking via aplikasi/admin
   └── Pilih layanan, tanggal, waktu, opsi antar-jemput
   └── Status: pending
2. Admin konfirmasi booking
   └── Status: confirmed
   └── Transaksi otomatis dibuat dari data booking
   └── Harga = estimasi_berat × harga_per_kg
3. Proses berlanjut seperti alur transaksi normal
```

### Alur Notifikasi WhatsApp

```
1. Pengembalian dibuat dengan kirim_notifikasi = true
2. WhatsAppService::sendPengembalianNotification() dipanggil
3. Nomor HP pelanggan diformat ke format internasional (62xxx)
4. Pesan template digenerate dengan detail transaksi
5. HTTP POST ke WhatsApp Gateway (localhost:3000)
6. Jika berhasil: notifikasi_terkirim = true, tanggal_notifikasi = now()
7. Jika gagal: bisa resend manual via POST /pengembalians/{id}/resend
```

---

## Catatan Pengembangan

> ⚠️ **Status:** Development / Staging — belum production-ready

1. **Public API Routes** — Saat ini semua route API bersifat publik (tidak memerlukan autentikasi). Untuk production, aktifkan kembali middleware `auth:sanctum` di `routes/api.php`.

2. **WhatsApp Gateway** — Memerlukan service terpisah yang berjalan di `localhost:3000`. Pastikan service WA Gateway aktif sebelum menggunakan fitur notifikasi.

3. **Default User ID** — Beberapa controller menggunakan `auth()->id() ?? 1` sebagai fallback. Pastikan autentikasi aktif di production.

4. **PDF Export** — Menggunakan DomPDF. Pastikan package terinstall jika fitur cetak PDF digunakan.

5. **Custom Primary Keys** — Semua tabel menggunakan custom primary key (id_user, id_pelanggan, dll), bukan `id` default Laravel.

6. **Cascade Delete** — Foreign key dikonfigurasi dengan `onDelete('cascade')`, hapus data induk akan menghapus data turunan.

7. **Deployment URL** — `https://noncommunistical-curably-alisa.ngrok-free.dev/` (ngrok tunnel, bersifat sementara).
 