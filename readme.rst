# Conference Management System (CMS) Kampus

Sebuah sistem manajemen konferensi berbasis web yang dirancang menggunakan CodeIgniter 3 untuk memfasilitasi seluruh alur kerja akademik, mulai dari pendaftaran peserta dan submisi artikel, proses review *double-blind*, hingga penjadwalan presentasi dan penerbitan sertifikat.

![Gemini](https://upload.wikimedia.org/wikipedia/commons/thumb/8/8a/Google_Gemini_logo.svg/330px-Google_Gemini_logo.svg.png)
Kode ini dibantu oleh Google Gemini pada proses Pembuatannya.
---

## ✨ Fitur Utama

Sistem ini dibagi menjadi empat bagian utama dengan alur kerja yang terintegrasi untuk setiap peran.

### 👨‍💼 Panel Admin
Panel admin adalah pusat kendali untuk mengelola seluruh aspek konferensi.

* **Manajemen Multi-Event**: Kemampuan untuk membuat, mengelola, mengaktifkan, dan mengarsipkan beberapa event konferensi dari tahun ke tahun.
* **Dasbor Pengelolaan Event**: Setiap event memiliki dasbor khususnya sendiri untuk mengelola:
    * **Banner**: Mengunggah beberapa gambar banner untuk ditampilkan sebagai *carousel* di halaman depan.
    * **Topik**: Menambah/menghapus topik keilmuan yang relevan untuk submisi artikel.
    * **Ruangan**: Mendefinisikan ruangan (fisik atau virtual) yang akan digunakan untuk sesi presentasi.
    * **Pengumuman**: Membuat pengumuman yang otomatis dikirim ke seluruh peserta melalui sistem antrian email.
* **Manajemen Pengguna**: Melihat, mengedit, dan menghapus data pengguna.
* **Validasi Pembayaran**: Antarmuka untuk melihat bukti bayar yang diunggah peserta, dengan aksi **Setujui** atau **Tolak** yang akan mengirim notifikasi email otomatis.
* **Manajemen Artikel & Review**:
    * Melihat semua artikel yang masuk.
    * Menugaskan reviewer dengan mengunggah naskah anonim.
    * Mengirim undangan review (dan mengirim ulang pengingat) melalui email dengan tautan unik.
    * Melihat hasil review yang masuk dari semua reviewer dalam satu tampilan.
    * Mengambil keputusan final (**Accepted, Revision, Rejected**) dengan notifikasi email otomatis ke presenter.
* **Manajemen Jadwal Interaktif**: Antarmuka **drag-and-drop** untuk menyusun jadwal. Admin dapat membuat blok sesi dan menempatkan beberapa artikel ke dalam satu sesi.
* **Check-in & Sertifikat**:
    * Halaman **Scanner QR Code** untuk validasi kehadiran peserta.
    * Pengaturan untuk mengaktifkan/menonaktifkan akses unduh sertifikat.
    * Fitur **Export/Import CSV** untuk mengelola tautan sertifikat eksternal secara massal.
* **Komunikasi**: Fitur chat dua arah dengan presenter untuk setiap artikel.
* **Laporan & Ekspor**:
    * Melihat daftar lengkap peserta dengan status pembayaran dan kehadiran.
    * Laporan konsolidasi untuk melihat ringkasan pendaftar (peserta saja, presenter saja, atau keduanya).
    * Fungsi **Export ke Excel** untuk laporan peserta dan artikel.

### 🧑‍🏫 Panel Peserta & Presenter
Dasbor personal yang dirancang untuk memandu pengguna melalui setiap tahapan partisipasi.

* **Pendaftaran Multi-Peran**: Satu pengguna dapat mendaftar sebagai **Peserta** dan/atau **Presenter** dalam satu event, dengan alur pembayaran yang terpisah.
* **Alur Pembayaran**: Mengunggah bukti bayar dan menerima notifikasi email otomatis saat divalidasi.
* **Manajemen Artikel (Presenter)**:
    * Antarmuka berbasis *stepper* yang memandu proses submisi.
    * Form submisi awal yang lengkap (judul, abstrak, topik, penulis, upload file '.docx').
    * Kemampuan untuk **menghapus dan submit ulang** jika artikel belum diproses.
    * Melihat masukan dari reviewer dan mengunggah file revisi.
    * Mengunggah naskah final dan slide presentasi setelah diterima.
    * Mengunduh **Letter of Acceptance (LoA)** yang digenerate otomatis.
* **Jadwal Personal**: Menampilkan informasi jadwal (waktu, ruangan, lokasi) yang spesifik untuk presentasi mereka.
* **Komunikasi**: Fitur chat untuk berdiskusi dengan panitia.
* **QR Code & Sertifikat**: Mengakses QR Code unik untuk check-in dan mengunduh sertifikat setelah acara (jika hadir dan akses dibuka oleh admin).
* **Manajemen Profil**: Fasilitas untuk mengedit data diri dan mengunggah foto profil yang akan tampil di halaman "Speakers".

### 🕵️‍♂️ Alur Reviewer
Dirancang agar semudah mungkin tanpa perlu login.

* **Akses Berbasis Token**: Reviewer mengakses halaman review melalui tautan unik dan aman yang dikirim via email.
* **Formulir Review Lengkap**: Mencakup semua kriteria penilaian standar akademik.
* **Mekanisme Kedaluwarsa**: Tautan review aktif selama 7 hari sejak pertama kali diakses.

### 🌐 Halaman Publik
Tampilan depan yang informatif dan menarik bagi pengunjung.

* **Halaman Event Dinamis**: Menampilkan banner *carousel*, konten utama, tanggal penting, dan pengumuman sesuai dengan event yang sedang aktif.
* **Arsip Event**: Pengunjung dapat melihat informasi dari event-event tahun sebelumnya melalui menu riwayat.
* **Halaman Jadwal & Pembicara**: Halaman khusus untuk melihat grid jadwal acara dan galeri para pembicara yang akan tampil.
* **Countdown Timer**: Hitung mundur interaktif menuju hari pelaksanaan acara.

---

## 🛠️ Teknologi yang Digunakan

* **Backend**: PHP 7.4+, CodeIgniter 3
* **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript (jQuery & Vanilla JS)
* **Database**: MySQL
* **Library Pihak Ketiga**:
    * **SortableJS**: Untuk fungsionalitas drag-and-drop pada penjadwalan.
    * **PhpSpreadsheet**: Untuk generate file laporan .xlsx (Excel).
    * **endroid/qr-code**: Untuk generate QR Code kehadiran.
    * **Summernote**: Editor WYSIWYG untuk konten event.
    * **DataTables.net**: Untuk tabel interaktif dengan filter dan paginasi.
* **Server**: Web server dengan dukungan PHP & MySQL (misal: Apache), Cron Jobs.

---

## 🚀 Instalasi

1.  Clone repositori ini: 'git clone https://github.com/ardimardiana/stima.git'
2.  Jalankan 'composer install' untuk menginstal semua dependensi PHP.
3.  Impor file '.sql' yang tersedia ke dalam database MySQL Anda.
4.  Konfigurasi koneksi database di 'application/config/database.php'.
5.  Sesuaikan 'base_url' di 'application/config/config.php'.
6.  Pastikan file '.htaccess' di root direktori sudah dikonfigurasi dengan benar untuk menghilangkan 'index.php' dari URL.
7.  Konfigurasi pengaturan SMTP untuk pengiriman email di 'application/config/email.php'.
8.  Atur **Cron Job** di server Anda untuk menjalankan skrip antrian email:
    '''bash
    */15 * * * * /usr/bin/php /path/ke/proyek/anda/index.php cli/cron send_emails
    '''

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

---

## 📞 Kontak

Dibuat oleh - [Ardi Mardiana] - [aim@unma.ac.id]

Project Link: [https://github.com/ardimardiana/stima]