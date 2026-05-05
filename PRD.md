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
*   **Engine:** PHP 8.3+ with Laravel 11.
*   **Database:** MySQL 8.0 (Existing Legacy Database `u7942055_cbn`). **NO DESTRUCTIVE MIGRATIONS.**
*   **Architecture:** MVC dengan pendekatan *Service Pattern* dan *Repository Pattern* untuk memisahkan query database legacy dari logika Controller.
*   **State Management:** Redis (untuk caching tarif ongkir, data tracking, dan session).

### B. Frontend Implementation
*   **Public Portal:** Blade Template Engine + Tailwind CSS (Fokus pada SEO & load speed).
*   **Dashboard Admin:** FilamentPHP v3 (Untuk pembuatan Admin Panel ERP logistik yang cepat dan dinamis).
*   **Interactive Components:** Livewire (untuk fitur real-time tracking (Cek Resi) tanpa page reload).

### C. DevOps & Environment
*   **Containerization:** Docker dengan `docker-compose.yml` (Laravel Sail untuk local dev).
*   **File Storage:** Local/Private disk untuk dokumen sensitif (Proof of Delivery, KTP Agen) dengan akses berbasis URL RBAC.

---

## 3. Core Modules & Feature Requirements

### 3.1 Database Integration (Legacy Mapping)
*   **Task:** Menghubungkan Laravel ke database eksisting tanpa mengubah skema tabel asli.
*   **Requirement:** 
    *   Mapping Eloquent Model ke tabel lama (contoh: `ShipItems`, `ShipStatus`, `Agencies`, `Geography`) menggunakan `protected $table`.
    *   Penanganan relasi tabel Yii2 menggunakan Foreign Key di Eloquent.
    *   Wajib menggunakan index, `chunk()`, atau `cursor()` saat query tabel log pengiriman agar memory tidak bocor.

### 3.2 Logistic Logic Migration
*   **Tracking System & AWB:** Porting logika pencarian Resi/AWB dari `TrackingController.php`. Wajib mencegah SQL Injection.
*   **Pricing Engine:** Menulis ulang logika ongkos kirim berdasarkan koordinat wilayah (Provinsi hingga Desa) dan berat/dimensi volumetrik.
*   **Shipment Lifecycle & POD:** Implementasi alur (Draft -> Picked Up -> Transit -> Delivered). Menyediakan endpoint aman untuk upload *Proof of Delivery* (POD) / Foto Penerima.

### 3.3 Dashboard Admin & Fleet Management
*   **RBAC (Role-Based Access Control):** Pemisahan hak akses menggunakan `spatie/laravel-permission` (Super Admin, Finance, Admin Cabang, Kurir).
*   **Manifest & Transit:** Manajemen manifes pengiriman dan pergerakan armada antar-gudang (Warehouse).
*   **Agency Dashboard:** Panel khusus mitra/agen untuk cek komisi dan resi tercetak.

### 3.4 API Layer & Automation
*   **Courier API Ready:** Struktur controller harus mendukung pengembalian response JSON (API) untuk kesiapan integrasi Mobile App Kurir di masa depan.
*   **Notification Engine:** Migrasi sistem IMAP/SMTP untuk membaca inbox. Notifikasi status resi via Email/WhatsApp menggunakan template terpusat.

---

## 4. AI Execution Protocol (Strict Rules)
1.  **Read Before Write:** AI wajib membaca `.antigravityrules` dan file source code Yii2 lama sebelum menulis logic Laravel.
2.  **Legacy Data Protection:** Dilarang keras menggunakan `migrate:fresh`.
3.  **Strict Typing:** Wajib menggunakan *return type declarations* (contoh: `: JsonResponse`, `: BelongsTo`).
4.  **Security First:** Validasi menggunakan *Form Requests*, proteksi CSRF, dan amankan upload file (No public disk for PODs).

---

## 5. Development Phases
1.  **Phase 1 (Infra):** Setup Docker (Sail), PHP 8.3, Laravel 11, dan konfigurasi `.env` untuk legacy DB.
2.  **Phase 2 (Data Bridge):** Pembuatan Eloquent Models khusus untuk tabel utama legacy (`ShipItems`, `ShipStatus`, `Geography`).
3.  **Phase 3 (Admin ERP):** Instalasi FilamentPHP dan setup RBAC Spatie.
4.  **Phase 4 (Logistic Core):** Porting Pricing Engine dan fitur Track & Trace menggunakan Livewire.
5.  **Phase 5 (Refine):** Refactoring, optimasi query N+1, dan security audit.

---

## 6. Advanced Logistics Modules (Roadmap)

### 6.1 Sistem POD (Proof of Delivery) & Tracking Status
*   **Status Workflow**: Implementasi alur status pengiriman (Transit, Arrived, Out for Delivery, Delivered).
*   **Evidence Collection**: Fitur unggah foto bukti pengiriman (POD) dan tanda tangan digital penerima saat status menjadi 'Delivered'.
*   **Security**: Foto disimpan di *private storage* dan hanya bisa diakses oleh user berwenang.

### 6.2 Manifest & Konsolidasi (Grouping)
*   **Manifest Creation**: Pengelompokan banyak resi ke dalam satu Nomor Manifest untuk pengiriman antar cabang/Hub.
*   **Transit Monitoring**: Melacak pergerakan manifest besar secara kolektif untuk efisiensi operasional armada.

### 6.3 Manajemen Pickup (Penjemputan)
*   **Request Form**: Form permintaan jemput paket oleh pelanggan korporat atau individu.
*   **Dispatcher Panel**: Penugasan kurir terdekat untuk melakukan penjemputan berdasarkan lokasi permintaan.

### 6.4 Keuangan & Invoicing
*   **Financial Reporting**: Laporan omzet real-time per agen dan sistem saldo/deposit agen.
*   **Auto-Invoicing**: Pembuatan invoice otomatis untuk pelanggan korporat (B2B) dengan siklus penagihan mingguan/bulanan.

### 6.5 Manajemen Armada & Driver
*   **Fleet Database**: Pendataan kendaraan (Plat nomor, masa berlaku STNK/KIR, kapasitas angkut).
*   **Driver Assignment**: Penugasan driver ke manifest atau rute pengiriman tertentu.

### 6.6 Customer CRM (Address Book)
*   **Frequent Customer DB**: Database pengirim dan penerima tetap untuk mempercepat proses input resi (Auto-complete data pelanggan).

---

## 7. Development Phases (Updated)
1.  **Phase 1 (Infra):** Setup Docker (Sail), PHP 8.3, Laravel 11, dan konfigurasi `.env`.
2.  **Phase 2 (Data Bridge):** Pembuatan Eloquent Models khusus untuk tabel utama legacy.
3.  **Phase 3 (Core ERP):** Instalasi FilamentPHP, setup RBAC, dan fitur Cetak Resi (Thermal/Bulk).
4.  **Phase 4 (Logistic Advanced):** Implementasi Sistem POD, Manajemen Manifest, dan Pickup Request.
5.  **Phase 5 (Finance & CRM):** Invoicing otomatis dan integrasi CRM Address Book.
6.  **Phase 6 (Refine):** Refactoring, optimasi query N+1, dan security audit.