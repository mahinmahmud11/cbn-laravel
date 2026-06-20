# Panduan Penggunaan Sistem ERP CBN Logistics

Dokumen ini disusun sebagai panduan operasional standar bagi seluruh pengguna sistem ERP CBN Logistics, mencakup alur kerja dari penerimaan paket hingga pengantaran akhir.

---

## 1. Pendahuluan & Hak Akses

Sistem ini menggunakan pengamanan berbasis peran (Role-Based Access Control) yang memungkinkan setiap staf bekerja sesuai dengan wewenang yang diberikan.

### Memahami Peran Pengguna (Roles)
*   **Super Admin:** Mengelola seluruh sistem, mengatur peran pengguna, dan memantau laporan finansial global.
*   **Agen Mitra:** Melakukan penginputan resi baru dan memantau perolehan komisi agen.
*   **Admin Gudang:** Memproses pergerakan paket masuk/keluar menggunakan perangkat pemindai laser.
*   **Kurir Lapangan:** Memperbarui status pengiriman dan mengunggah bukti foto (POD) melalui perangkat mobile.
*   **Customer Service:** Membantu pelanggan melakukan pengecekan status dan melakukan pembaruan riwayat secara manual jika diperlukan.

### Melakukan Login & Navigasi
1.  Mengakses alamat URL panel administrasi (contoh: `/admin/login`).
2.  Memasukkan alamat email dan kata sandi yang telah didaftarkan oleh administrator.
3.  Menjelajahi menu yang tersedia pada bilah samping (sidebar) sesuai dengan hak akses yang Anda miliki.

---

## 2. Panduan Agen Mitra (Drop Point)

### Membuat Resi Baru
1.  Membuka menu **Manajemen Resi** dan mengeklik tombol **Tambah Resi**.
2.  Mengisi Identitas Pengirim dan Penerima secara lengkap.
3.  Memilih **Kecamatan Asal** dan **Kecamatan Tujuan**.
4.  Memasukkan **Berat (Kg)** dan **Dimensi (P x L x T)** paket.
    *   *Sistem akan menghitung harga secara otomatis berdasarkan berat yang paling besar antara berat aktual dan berat volumetrik.*
5.  Menyimpan data untuk mencetak nomor resi yang dihasilkan secara otomatis.

### Memantau Komisi Agen
1.  Membuka menu **Agent Commission**.
2.  Melihat daftar komisi yang berhasil dikumpulkan dari setiap pengiriman yang Anda proses.
3.  Memantau status pencairan komisi (Pending/Paid).

---

## 3. Panduan Operasional Gudang & Kurir (Scan Barcode)

Halaman **Scan Barcode** didesain untuk kecepatan tinggi dengan dua metode pemindaian:

### Melakukan Scan Fisik (Admin Gudang)
1.  Membuka menu **Scan Barcode**.
2.  Menempatkan kursor pada kolom input nomor resi.
3.  Memindai barcode paket menggunakan pemindai laser USB.
    *   *Sistem akan langsung memproses paket ke status TRANSIT tanpa perlu mengeklik tombol apa pun (Zero-click).*

### Melakukan Scan Mobile (Kurir Lapangan)
1.  Membuka halaman **Scan Barcode** melalui browser smartphone (wajib menggunakan HTTPS).
2.  Memilih status pengiriman yang sesuai (RECEIVED, TRANSIT, OUT FOR DELIVERY, atau DELIVERED).
3.  Mengeklik tombol **Buka Kamera Scanner**.
4.  Mengarahkan kamera HP ke barcode paket hingga terbaca oleh sistem.

### Mengambil Bukti Pengiriman (POD)
1.  Memastikan status yang dipilih adalah **DELIVERED**.
2.  Mengeklik tombol **Pilih File** yang muncul di bawah dropdown status.
3.  Memotret paket yang telah diterima atau rumah pelanggan sebagai bukti pengiriman yang sah.
4.  Menyelesaikan proses pemindaian agar foto tersimpan secara permanen dalam riwayat pelacakan.

---

## 4. Panduan Customer Service

### Melakukan Pembaruan Manual
1.  Membuka menu **Tracking History** jika terdapat agen yang mengalami kendala teknis atau koneksi internet.
2.  Mengeklik tombol **Tambah Riwayat**.
3.  Memasukkan Nomor Resi paket yang bersangkutan.
4.  Mengatur status, lokasi, dan deskripsi kejadian secara manual.
5.  Menyimpan data agar pelanggan dapat melihat pembaruan tersebut secara real-time.

---

## 5. Panduan Portal Pelanggan

### Melacak Paket (Track & Trace)
Pelanggan tidak perlu melakukan login untuk melacak paket mereka:
1.  Mengakses halaman depan (Landing Page) website CBN Logistics.
2.  Memasukkan nomor resi pada kolom **Lacak Paket**.
3.  Mengeklik tombol **Lacak Sekarang**.
4.  Melihat identitas paket dan garis waktu (Timeline) pergerakan paket dari lokasi asal hingga tujuan akhir secara transparan.

---

*Catatan: Segala kendala teknis harap segera dilaporkan kepada tim Administrator Sistem.*
