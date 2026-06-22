# Product Requirements Document (PRD) - Project CBN

**Target Framework:** Laravel 11 (Modernization from Yii2)
**Infrastructure:** Dockerized Environment (Laravel Sail/Docker Compose)
**Developer Persona:** AI-Driven Development (Gemini 3 Flash as Senior Engineer)

---

## 1. Project Vision

Mentransformasi sistem ERP Logistik **Citra Buana Nusantara (CBN)** dari arsitektur *legacy* Yii2 menjadi aplikasi berbasis Laravel yang modern, responsif, dan mudah di-maintain. Fokus utama adalah efisiensi operasional, keamanan data transaksi logistik berskala besar (313+ MB), dan skalabilitas infrastruktur menggunakan Docker.

---

## 2. Technical Architecture & Stack

### A. Backend Core
- **Engine:** PHP 8.3+ with Laravel 11.
- **Database:** MySQL 8.0 (Existing Legacy Database `u7942055_cbn`). **NO DESTRUCTIVE MIGRATIONS.**
- **Architecture:** MVC dengan pendekatan *Service Pattern* dan *Repository Pattern* untuk memisahkan query database legacy dari logika Controller.
- **State Management:** Redis (untuk caching tarif ongkir, data tracking, dan session).

### B. Frontend Implementation
- **Public Portal:** Blade Template Engine + Tailwind CSS (Fokus pada SEO & load speed).
- **Dashboard Admin:** FilamentPHP v3 (Untuk pembuatan Admin Panel ERP logistik yang cepat dan dinamis).
- **Interactive Components:** Livewire (untuk fitur real-time tracking (Cek Resi) tanpa page reload).

### C. DevOps & Environment
- **Containerization:** Docker dengan `docker-compose.yml` (Laravel Sail untuk local dev).
- **File Storage:** Local/Private disk untuk dokumen sensitif (Proof of Delivery, KTP Agen) dengan akses berbasis URL RBAC.

---

## 3. Core Modules & Feature Requirements

### 3.1 Database Integration (Legacy Mapping)
- **Task:** Menghubungkan Laravel ke database eksisting tanpa mengubah skema tabel asli.
- **Requirement:**
  - Mapping Eloquent Model ke tabel lama (contoh: `ShipItems`, `ShipStatus`, `Agencies`, `Geography`) menggunakan `protected $table`.
  - Penanganan relasi tabel Yii2 menggunakan Foreign Key di Eloquent.
  - Wajib menggunakan index, `chunk()`, atau `cursor()` saat query tabel log pengiriman agar memory tidak bocor.

### 3.2 Logistic Logic Migration
- **Tracking System & AWB:** Porting logika pencarian Resi/AWB dari `TrackingController.php`. Wajib mencegah SQL Injection.
- **Pricing Engine:** Menulis ulang logika ongkos kirim berdasarkan koordinat wilayah (Provinsi hingga Desa) dan berat/dimensi volumetrik.
- **Shipment Lifecycle & POD:** Implementasi alur (Draft → Picked Up → Transit → Delivered). Menyediakan endpoint aman untuk upload *Proof of Delivery* (POD) / Foto Penerima.

### 3.3 Dashboard Admin & Fleet Management
- **RBAC (Role-Based Access Control):** Pemisahan hak akses menggunakan `spatie/laravel-permission` (Super Admin, Administrator, Webmaster, Courier, Agent).
- **Manifest & Transit:** Manajemen manifes pengiriman dan pergerakan armada antar-gudang (Warehouse).
- **Agency Dashboard:** Panel khusus mitra/agen untuk cek komisi dan resi tercetak.

### 3.4 API Layer & Automation
- **Courier API Ready:** Struktur controller harus mendukung pengembalian response JSON (API) untuk kesiapan integrasi Mobile App Kurir di masa depan.
- **Notification Engine:** Migrasi sistem IMAP/SMTP untuk membaca inbox. Notifikasi status resi via Email/WhatsApp menggunakan template terpusat.

---

## 4. AI Execution Protocol (Strict Rules)

1. **Read Before Write:** AI wajib membaca `.antigravityrules` dan file source code Yii2 lama sebelum menulis logic Laravel.
2. **Legacy Data Protection:** Dilarang keras menggunakan `migrate:fresh`.
3. **Strict Typing:** Wajib menggunakan *return type declarations* (contoh: `: JsonResponse`, `: BelongsTo`).
4. **Security First:** Validasi menggunakan *Form Requests*, proteksi CSRF, dan amankan upload file (No public disk for PODs).

---

## 5. Development Phases (V1 — Awal)

1. **Phase 1 (Infra):** Setup Docker (Sail), PHP 8.3, Laravel 11, dan konfigurasi `.env` untuk legacy DB.
2. **Phase 2 (Data Bridge):** Pembuatan Eloquent Models khusus untuk tabel utama legacy (`ShipItems`, `ShipStatus`, `Geography`).
3. **Phase 3 (Admin ERP):** Instalasi FilamentPHP dan setup RBAC Spatie.
4. **Phase 4 (Logistic Core):** Porting Pricing Engine dan fitur Track & Trace menggunakan Livewire.
5. **Phase 5 (Refine):** Refactoring, optimasi query N+1, dan security audit.

---

## 6. Advanced Logistics Modules (Roadmap)

### 6.1 Sistem POD (Proof of Delivery) & Tracking Status
- **Status Workflow:** Implementasi alur status pengiriman (Transit, Arrived, Out for Delivery, Delivered).
- **Evidence Collection:** Fitur unggah foto bukti pengiriman (POD) dan tanda tangan digital penerima saat status menjadi 'Delivered'.
- **Security:** Foto disimpan di *private storage* dan hanya bisa diakses oleh user berwenang.

### 6.2 Manifest & Konsolidasi (Grouping)
- **Manifest Creation:** Pengelompokan banyak resi ke dalam satu Nomor Manifest untuk pengiriman antar cabang/Hub.
- **Transit Monitoring:** Melacak pergerakan manifest besar secara kolektif untuk efisiensi operasional armada.

### 6.3 Manajemen Pickup (Penjemputan)
- **Request Form:** Form permintaan jemput paket oleh pelanggan korporat atau individu.
- **Dispatcher Panel:** Penugasan kurir terdekat untuk melakukan penjemputan berdasarkan lokasi permintaan.

### 6.4 Keuangan & Invoicing
- **Financial Reporting:** Laporan omzet real-time per agen dan sistem saldo/deposit agen.
- **Auto-Invoicing:** Pembuatan invoice otomatis untuk pelanggan korporat (B2B) dengan siklus penagihan mingguan/bulanan.

### 6.5 Manajemen Armada & Driver ✅ SELESAI Sprint 2
- **Fleet Database:** Pendataan kendaraan (Plat nomor, masa berlaku STNK/KIR, kapasitas angkut). → `ArmadaResource`
- **Driver Assignment:** Penugasan driver ke manifest atau rute pengiriman tertentu. → `SuratJalanResource`

### 6.6 Customer CRM, Master Wilayah & Data Snapshotting ✅ SELESAI Sprint 5

Modul ini adalah *Core Point of Sales* (POS) untuk mempercepat kerja kasir cabang hingga 300% menggunakan fitur *Auto-fill* cerdas, dengan tetap mempertahankan integritas data historis secara absolut.

#### 6.6.1 Inventori Field Form Resi (Sumber: Analisis Form Legacy citrabuananusantara.com)

**Bagian Informasi Utama:**

| Field | Tipe | Wajib | Nilai/Opsi | Kolom DB |
|---|---|---|---|---|
| AWB | Text (auto-generate) | ✅ | AwbNumberGeneratorService | `tracking_number` |
| Sender | Text + Auto-fill | ✅ | Lookup dari `customers` | `sender_name` |
| Receiver | Text + Auto-fill | ✅ | Lookup dari `customers` | `recipient_name` |
| Dimension | Text | ❌ | Format P x L x T | `dimension` |
| Pieces | Number | ✅ | | `pieces` |
| Kilogram | Decimal | ✅ | | `weight_kg` |
| Price | Currency | ✅ | Auto-kalkulasi dari tarif | `price` |
| Package | Select | ✅ | Regular, ONS, SDS, International Service | `package_type` |
| Categories | Tag/Text | ✅ | Bebas | `package_content` |
| Fragile | Toggle/Radio | ✅ | No / Yes | `is_fragile` |
| Notes | Textarea | ❌ | | `notes` |

**Bagian Alamat Pengirim (Sender's Address) — 5 tingkat hierarki:**

| Field | Tipe | Wajib | Kolom DB |
|---|---|---|---|
| Address | Textarea | ✅ | `sender_address` |
| Province | Searchable Select → cascade | ✅ | `origin_province_id` |
| City / Regency | Searchable Select → cascade | ✅ | `origin_regency_id` |
| District | Searchable Select → cascade | ✅ | `origin_district_id` |
| Village | Searchable Select → cascade | ✅ | `origin_village_id` |
| Postal Code | Text | ❌ | `origin_postal_code` |

**Bagian Alamat Penerima (Receiver's Address) — 5 tingkat hierarki:**

| Field | Tipe | Wajib | Kolom DB |
|---|---|---|---|
| Address | Textarea | ✅ | `receiver_address` |
| Province | Searchable Select → cascade | ✅ | `destination_province_id` |
| City / Regency | Searchable Select → cascade | ✅ | `destination_regency_id` |
| District | Searchable Select → cascade | ✅ | `destination_district_id` |
| Village | Searchable Select → cascade | ✅ | `destination_village_id` |
| Postal Code | Text | ❌ | `destination_postal_code` |

#### 6.6.2 Geo Hierarchy — 5 Tingkat (bukan 3)

Database geo sudah lengkap: `geo_provinces` → `geo_regencies` → `geo_districts` → `geo_villages` → `geo_postal_codes`. Model tersedia: `Province`, `Regency`, `District`, `Village` (dibuat Sprint 5).

Seluruh dropdown WAJIB di-cache Redis dengan TTL 24 jam menggunakan pola:
```php
Cache::remember('geo_{level}_{parent_id}', 86400, fn() => Model::where(...)->pluck('name', 'id'));
```
Dropdown bersifat **cascading** — memilih Province memfilter Regency, memilih Regency memfilter District, dst. Implementasi via `GeoService` (Sprint 5) dengan `->live()` + `->options(fn($get) => ...)` + `->disabled(fn($get) => !$get('parent_field'))`.

#### 6.6.3 Customer Address Book

- Tabel `customers`: `id`, `name`, `phone`, `address`, `province_id`, `regency_id`, `district_id`, `village_id`, `postal_code`, `type` (enum: `sender`|`receiver`|`both`), `company_id` (nullable FK ke `companies`), `created_by`, `timestamps`.
- Saat kasir mengetik nama pengirim/penerima di form Resi, sistem melakukan `LIKE` search ke `customers.name` dan menampilkan saran.
- Saat kontak diklik → `afterStateUpdated` Filament/Livewire mengisi semua field alamat secara otomatis.

#### 6.6.4 Data Snapshotting (CRITICAL)

- Tabel `shipments` **WAJIB** memiliki kolom snapshot sendiri — TIDAK BOLEH hanya simpan `customer_id` lalu JOIN saat cetak.
- Semua kolom snapshot sudah tersedia (Sprint 5): `sender_name`, `sender_address`, `origin_province_id`, `origin_regency_id`, `origin_village_id`, `origin_postal_code`, `receiver_address`, `destination_province_id`, `destination_regency_id`, `destination_village_id`, `destination_postal_code`, `is_fragile`, `customer_id`.
- Saat resi disimpan (`mutateFormDataBeforeCreate`): data Customer di-snapshot ke kolom-kolom ini. Perubahan data master `customers` di kemudian hari **tidak** mengubah resi yang sudah tercetak.

#### 6.6.5 Smart Background Update

- Jika kasir mengedit nomor telepon di form resi (setelah auto-fill), sistem menawarkan (via Filament Notification + Action) untuk memperbarui data master `customers` secara transparan dalam `DB::transaction`.

### 6.7 Sistem Akuntansi Terpadu (Double-Entry)
- **General Ledger (Buku Besar):** Pencatatan otomatis setiap transaksi logistik (pendapatan ongkir, piutang korporat, utang komisi agen) ke dalam Jurnal Umum untuk menghasilkan neraca keuangan (*Balance Sheet*) yang akurat.
- **Accounting Service:** Sentralisasi logika mutasi dana untuk mencegah anomali atau ketidakseimbangan pencatatan finansial.

### 6.8 Audit Trail & System Logging ✅ SELESAI Sprint 4
- **Activity Monitor:** Pelacakan komprehensif terhadap seluruh aktivitas admin dan agen. → `AuditTrailResource` (read-only, grup Sistem & Keamanan)
- **Historical Data:** Penyimpanan status data sebelum (*before*) dan sesudah (*after*) via `spatie/laravel-activitylog`. Model `Shipment` sudah menggunakan trait `LogsActivity`.

### 6.9 Web Artisan & Server Management Console ✅ SELESAI Sprint 4
- **GUI Terminal:** Panel kontrol eksekusi command artisan tanpa SSH. → `ArtisanConsolePage` (whitelist 8 command, akses super_admin saja, setiap eksekusi dicatat ke activity_log)
- **Queue Monitor:** Antarmuka pemantauan background jobs. *(Belum diimplementasi)*

### 6.10 Invoicing Korporat B2B (Company) — Dirancang, Belum Diimplementasi

**Status:** Dirancang dan disetujui 21 Juni 2026. Siap dieksekusi Sprint berikutnya.

**Konteks:** CBN melayani pelanggan korporat (perusahaan) yang melakukan pengiriman rutin dan membayar lewat penagihan berkala. Entitas ini **BERBEDA** dari `Agency` (mitra komisi). `Company` = pembeli jasa, `Agency` = mitra penjual.

**Model Data:**
- Tabel `companies`: `id`, `name`, `npwp`, `billing_address`, `pic_name`, `pic_phone`, `payment_term_days` (default 30 hari), `created_by` (FK ke tabel `user`), `timestamps`, `index(name)`
- Tabel `customers`: tambah kolom `company_id` nullable FK ke `companies`
- Tabel `invoices`: tambah kolom `company_id`, `billing_status` (enum), `due_date`, `paid_amount`

**Status Penagihan (billing_status):**
`belum_ditagih` → `sudah_ditagih` → `sebagian_dibayar` → `lunas`

**Fitur yang akan dibangun:**
- `CompanyResource` (Data Master, sort 21, akses: super_admin, administrator)
- `InvoiceResource`: filter & sort by `company_id` dan `billing_status`, default sort `due_date ASC`
- Auto-fill `due_date` dari `payment_term_days` Company saat invoice dibuat
- Widget `OutstandingInvoicesByCompany`: rekap piutang aktif per perusahaan

**Pendekatan Hybrid:** Company dibuat manual oleh admin/finance (data legal: NPWP, alamat tagih, PIC). Setelah dibuat, bisa di-link ke kontak Address Book (`customers`) yang mewakili perusahaan tersebut.

---

## 7. Development Phases (V2 — Updated)

1. **Phase 1 (Infra):** Setup Docker (Sail), PHP 8.3, Laravel 11, dan konfigurasi `.env`.
2. **Phase 2 (Data Bridge):** Pembuatan Eloquent Models khusus untuk tabel utama legacy.
3. **Phase 3 (Core ERP):** Instalasi FilamentPHP, setup RBAC, dan fitur Cetak Resi (Thermal/Bulk).
4. **Phase 4 (Tracking & Scanning):** Implementasi Arsitektur Master-Detail Tracking dan integrasi pemindaian barcode.
5. **Phase 5 (Finance & Profit Sharing):** Otomatisasi bagi hasil agen dan pelaporan finansial.
6. **Phase 6 (Logistic Advanced):** Implementasi Sistem POD, Manajemen Manifest, dan Pickup Request.
7. **Phase 7 (Refine & Audit):** Refactoring, optimasi query N+1, dan security audit.
8. **Phase 8 (Enterprise & Finance Core):** Integrasi sistem akuntansi Double-Entry, Audit Trail menyeluruh, dan Web Artisan Console.

---

## 8. Arsitektur Tracking Logistik Terpadu & Bagi Hasil Agen

### 8.1 Arsitektur Pelacakan Master-Detail

- **Entitas Utama (Shipments):** Tabel `shipments` berfungsi sebagai identitas unik ("KTP") untuk setiap resi.
  - ID Resi / AWB Number
  - Identitas Pengirim & Penerima (snapshot)
  - Kota Asal & Tujuan
  - Detail Paket (Berat, Dimensi, Isi)

- **Entitas Riwayat (Tracking Histories):** Tabel `tracking_histories` berfungsi sebagai "Buku Harian" paket.
  - Timestamp kejadian
  - Lokasi (Cabang/Agen/Hub)
  - Status Pengiriman (Manifested, Received at Warehouse, Out for Delivery, dst.)
  - Person in Charge (PIC) / User yang melakukan pemindaian

### 8.2 Alur Pemindaian Barcode (Scanning Flow)

- Pemindaian barcode oleh agen di lapangan atau petugas gudang akan memicu pembuatan baris baru di `tracking_histories`.
- **Integritas Data:** Operasi ini tidak boleh mengubah atribut utama pada tabel `shipments` (kecuali status global terakhir untuk sinkronisasi), guna menjaga integritas riwayat perjalanan paket.

### 8.3 Otomatisasi Profit-Sharing (Bagi Hasil)

- Setiap pemicu perubahan status akan divalidasi dan sistem menghitung nilai komisi agen.
- Data komisi disimpan ke tabel `agent_commissions`.
- **Keamanan Transaksi:** Perhitungan dan pencatatan komisi wajib dibungkus dalam `DB::transaction`.

### 8.4 Keputusan Final Cutover Arsitektur Resi (21 Juni 2026)

> Hasil audit menemukan sistem berada dalam kondisi arsitektur terbelah: `ship_items` (legacy, 2.380 baris data hidup, kolom `awb` varchar(5)) vs `shipments` (baru, kolom `tracking_number` varchar(255), sudah jadi basis untuk `TrackingHistory`/`AgentCommission`/`ScanBarcode`/`PublicTracking`).

**Keputusan:** `shipments` menjadi *single source of truth* untuk seluruh transaksi resi baru mulai tanggal cutover. `ship_items` **dibekukan menjadi arsip read-only** — tidak ada lagi create/edit/delete, hanya dibaca untuk keperluan histori.

**Status rekonsiliasi (semua selesai):**
- [x] Dashboard Widgets (`ShipmentTrends`, `RecentShipments`, `MonthlyRevenue`, `StatsOverview`) — **SELESAI Sprint 3**: query sudah digabung ShipItem + Shipment, dashboard tidak nol.
- [x] Duplikasi widget tracking publik — **SELESAI Sprint 3**: `TrackingWidget.php` dihapus (dead code), `PublicTracking.php` sudah punya fallback Shipment → ShipItem, field kilogram/pieces/package_type sudah ditambahkan.
- [x] `Manifest` dan `Invoice` relasi ke `Shipment` — **SELESAI Sprint 1**: `ShipmentsRelationManager` sudah ada di kedua resource.
- [x] Skema `shipments` kolom logistik — **SELESAI Sprint 5**: migration `add_geo_snapshot_and_pricing_to_shipments` sudah dijalankan, semua kolom snapshot tersedia.

**Known Behavior — AWB Number Gap (Tidak Perlu Diperbaiki, Cukup Didokumentasikan):**
`AwbNumberGeneratorService` menaikkan counter `last_sequence` SEBELUM data berhasil insert ke tabel `shipments`. Jika insert gagal setelah nomor AWB di-generate, nomor itu hilang permanen dan tidak akan dipakai ulang. Ini **bukan bug** — perilaku ini lazim di sistem penomoran dokumen finansial/logistik (mirip gap nomor invoice). Tim finance/audit perlu tahu bahwa gap nomor AWB adalah hal normal.

---

## 9. Standar Antarmuka & Arsitektur Navigasi (Filament Admin Panel)

> Section ini bersifat **mengikat** — setiap pembuatan/refactor Filament Resource WAJIB merujuk ke struktur di bawah, tidak boleh dibuat ad-hoc oleh AI Agent.

### 9.1 Prinsip Konsistensi Bahasa

- Seluruh elemen UI yang terlihat pengguna — `navigationLabel`, judul halaman, `modelLabel`, `pluralModelLabel`, label tombol aksi, breadcrumb, dan nama kolom tabel — **WAJIB Bahasa Indonesia**.
- **Dilarang** nama Class/Model Eloquent tampil mentah di UI. Setiap Resource wajib override `getModelLabel()`, `getPluralModelLabel()`, dan `getNavigationLabel()` secara eksplisit.

### 9.2 Struktur Navigasi Sidebar (Definitif)

Urutan grup mengikuti frekuensi pemakaian harian (transaksi paling sering dipakai → paling atas; administrasi sistem paling jarang disentuh non-superadmin → paling bawah).

| Sort | Navigation Group | Menu | Status | Catatan |
|---|---|---|---|---|
| - | *(tanpa grup)* | Dasbor | ✅ | |
| 1 | **Transaksi** | Daftar Resi | ✅ | `ResiResource` (model `Shipment`) — single source of truth |
| 1 | Transaksi | Arsip Resi | ✅ | `ShipItemResource` (model `ShipItem`) — read-only, sort 10 |
| 1 | Transaksi | Scan Barcode | ✅ | `ScanBarcode` Page, sort 1 |
| 1 | Transaksi | Lacak Pengiriman | ✅ | |
| 1 | Transaksi | Manajemen Manifest | ✅ | |
| 1 | Transaksi | Request Penjemputan | ✅ | |
| 1 | Transaksi | Surat Jalan | ✅ | `SuratJalanResource` (Sprint 2) |
| 2 | **Keuangan & Komisi** | Komisi Agen | ✅ | |
| 2 | Keuangan & Komisi | Penagihan (Invoicing) | ✅ | |
| 2 | Keuangan & Komisi | Manajemen Tarif | ✅ | |
| 2 | Keuangan & Komisi | Jurnal Umum & Neraca | ⬜ | Belum diimplementasi — Section 6.7 |
| 3 | **Data Master** | Manajemen Agen | ✅ | |
| 3 | Data Master | Armada | ✅ | `ArmadaResource` (Sprint 2) |
| 3 | Data Master | Pelanggan Tetap (Address Book) | ✅ | `CustomerResource` (Sprint 5) |
| 3 | Data Master | Pelanggan Korporat | ⬜ | `CompanyResource` — belum diimplementasi, Section 6.10 |
| 4 | **Sistem & Keamanan** | Manajemen User | ✅ | |
| 4 | Sistem & Keamanan | Roles | ✅ | Filament Shield, dipindahkan ke grup ini |
| 4 | Sistem & Keamanan | Audit Trail | ✅ | `AuditTrailResource` (Sprint 4) |
| 4 | Sistem & Keamanan | Artisan Console | ✅ | `ArtisanConsolePage` (Sprint 4) |
| 4 | Sistem & Keamanan | Antrean (Queue Monitor) | ⬜ | Belum diimplementasi |
| 4 | Sistem & Keamanan | Template Notifikasi | ⬜ | Belum diimplementasi — Section 3.4 |

### 9.3 Tabel Pemetaan Model → Label Filament

| Eloquent Model | Tabel | Navigation Label | Model Label | Plural Model Label | Nav Group | Status |
|---|---|---|---|---|---|---|
| `Shipment` | `shipments` | Daftar Resi | Resi | Daftar Resi | Transaksi | ✅ Aktif — `ResiResource`, satu-satunya jalur create/edit |
| `ShipItem` | `ship_items` (legacy) | Arsip Resi | Arsip Resi | Daftar Arsip Resi | Transaksi | ✅ Read-only — canCreate/canEdit/canDelete selalu `false` |
| `ShipStatus` | `ship_status` (legacy) | Lacak Pengiriman | Riwayat Status | Riwayat Status | Transaksi | ✅ Aktif (riwayat legacy) |
| `TrackingHistory` | `tracking_histories` | Lacak Pengiriman | Riwayat Status | Riwayat Status | Transaksi | ✅ Aktif (riwayat baru, pasangan `Shipment`) |
| `Agency` | `agencies` | Manajemen Agen | Agen | Daftar Agen | Data Master | ✅ Aktif |
| `Armada` | `armada` | Armada | Armada | Daftar Armada | Data Master | ✅ Aktif — Sprint 2 |
| `SuratJalan` | `surat_jalan` | Surat Jalan | Surat Jalan | Daftar Surat Jalan | Transaksi | ✅ Aktif — Sprint 2 |
| `Customer` | `customers` | Pelanggan Tetap | Pelanggan | Daftar Pelanggan | Data Master | ✅ Aktif — Sprint 5 |
| `Manifest` | `manifests` | Manajemen Manifest | Manifes | Daftar Manifes | Transaksi | ✅ Aktif — relasi ke `Shipment` via `ShipmentsRelationManager` |
| `Invoice` | `invoices` | Penagihan (Invoicing) | Invoice | Daftar Invoice | Keuangan & Komisi | ✅ Aktif — relasi ke `Shipment` via `ShipmentsRelationManager` |
| `AgentCommission` | `agent_commissions` | Komisi Agen | Komisi | Daftar Komisi | Keuangan & Komisi | ✅ Aktif |
| `Activity` *(Spatie)* | `activity_log` | Audit Trail | Audit Trail | Audit Trails | Sistem & Keamanan | ✅ Aktif — `AuditTrailResource`, read-only, Sprint 4 |
| `Company` | `companies` | Pelanggan Korporat | Perusahaan | Pelanggan Korporat | Data Master | ⬜ Belum diimplementasi — `CompanyResource`, sort 21, Section 6.10 |
| `OutstandingInvoicesByCompany` | *(widget, bukan tabel)* | *(Widget Dashboard)* | - | - | Dashboard | ⬜ Belum diimplementasi — Widget rekap piutang, Section 6.10 |

**Aturan wajib:** Setiap penambahan Resource baru wajib menambah baris baru di tabel ini sebelum kode ditulis.

### 9.4 Update Roadmap — Status Sprint

**✅ Selesai:**
- Sprint 1: cutover `ResiResource` (`Shipment`) sebagai source of truth, `ShipItemResource` jadi Arsip Resi read-only, relasi `Manifest`/`Invoice` ke `Shipment` via `ShipmentsRelationManager`
- Sprint 2: Armada & Driver — `ArmadaResource`, `SuratJalanResource` + RBAC
- Sprint 3: konsolidasi Dashboard Widgets (query gabungan ShipItem + Shipment), konsolidasi Public Tracking + hapus dead code `TrackingWidget.php`
- Sprint 4: Audit Trail viewer (`AuditTrailResource` read-only), Web Artisan Console (`ArtisanConsolePage`, whitelist 8 command, activity log tiap eksekusi), pemindahan "Roles" ke grup "Sistem & Keamanan"
- Sprint 5: Customer CRM & Address Book (`CustomerResource`), `GeoService` Redis cache, cascading dropdown 5 tingkat, Data Snapshotting kolom shipments (migration selesai)
- Desain: Invoicing Korporat B2B dirancang dan disetujui — Section 6.10

**⬜ Belum dikerjakan — Sprint 6+:**
- `CompanyResource` + update `InvoiceResource` (billing_status, due_date, company_id) + Widget `OutstandingInvoicesByCompany` — Section 6.10, sudah dirancang lengkap dengan kode siap eksekusi
- Jurnal Umum & Neraca viewer — Section 6.7
- Antrean/Queue Monitor — Section 6.9
- Template Notifikasi — Section 3.4
- Sistem POD (Proof of Delivery) — Section 6.1
- Print route RBAC hardening — proteksi middleware eksplisit untuk `/print/resi/*`
