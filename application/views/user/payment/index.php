<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-light">

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i> <?= $this->session->flashdata('error'); ?>
                    <button type="text" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="card-title mb-0"><i class="fas fa-wallet mr-2"></i> Konfirmasi Kategori & Pembayaran</h5>
                </div>
                <div class="card-body p-4">
                    
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Nama Event</small>
                            <strong><?= $event->nama_event; ?></strong>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Jenis Kepesertaan Anda</small>
                            <span class="badge badge-info text-uppercase px-3 py-2" style="font-size: 0.9rem;">
                                <i class="fas fa-user-tag mr-1"></i> <?= $participant->peran_event; // 'Pemakalah' atau 'Peserta' ?>
                            </span>
                        </div>
                    </div>

                    <hr>

                    <?= form_open('user/payment/generate_invoice'); ?>
                        
                        <input type="hidden" name="tipe_kepesertaan" value="<?= $participant->peran_event; ?>">
                        <input type="hidden" name="registration_id" value="<?= $participant->registration_id; ?>">

                        <div class="form-group mb-4">
                            <label class="font-weight-bold d-block mb-3">Pilih Kategori Asal Instansi / Pendidikan:</label>
                            
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <div class="custom-control custom-radio card-radio-select p-3 border rounded">
                                        <input type="radio" id="harga_umum" name="tipe_harga" value="Umum" class="custom-control-input" checked required>
                                        <label class="custom-control-label w-100 pl-2 cursor-pointer" for="harga_umum">
                                            <span class="d-block font-weight-bold text-dark">Kategori Umum</span>
                                            <span class="text-muted small">Peserta dari Instansi Luar / Masyarakat Umum</span>
                                            <h4 class="mt-2 text-primary font-weight-bold">
                                                Rp <?= number_format(($participant->peran_event == 'presenter') ? $event->fee_pemakalah_umum : $event->fee_peserta_umum, 0, ',', '.'); ?>
                                            </h4>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-6 mb-3">
                                    <div class="custom-control custom-radio card-radio-select p-3 border rounded">
                                        <input type="radio" id="harga_unma" name="tipe_harga" value="UNMA" class="custom-control-input" required>
                                        <label class="custom-control-label w-100 pl-2 cursor-pointer" for="harga_unma">
                                            <span class="d-block font-weight-bold text-dark">Kategori UNMA (Mahasiswa)</span>
                                            <span class="text-muted small">Civitas Akademika Universitas Majalengka / Mahasiswa</span>
                                            <h4 class="mt-2 text-success font-weight-bold">
                                                Rp <?= number_format(($participant->peran_event == 'presenter') ? $event->fee_pemakalah_unma : $event->fee_peserta_mahasiswa, 0, ',', '.'); ?>
                                            </h4>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning border-warning bg-light-warning p-3 mb-4" style="border-left: 4px solid #ffc107;">
                            <h6 class="text-warning-dark font-weight-bold mb-1"><i class="fas fa-exclamation-triangle mr-1"></i> Penting Penting!</h6>
                            <p class="small text-muted mb-0 font-italic">
                                Pilihan kategori di atas bersifat mandiri (*Self-Declare*). Pastikan Anda memilih sesuai dengan profil asli Anda. Jika invoice sudah dibuat dan dibayarkan namun terdapat kesalahan pemilihan kategori, **dana yang telah masuk tidak dapat dikembalikan atau di-refund**.
                            </p>
                        </div>

                        <div class="custom-control custom-checkbox mb-4">
                            <input type="checkbox" class="custom-control-input" id="agreementCheck" name="agreement" value="1" required>
                            <label class="custom-control-label small text-dark font-weight-bold cursor-pointer" for="agreementCheck">
                                Saya menyetujui ketentuan di atas dan menyatakan bahwa data kategori yang saya pilih adalah benar dan sah.
                            </label>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="<?= base_url('user/dashboard'); ?>" class="btn btn-light border text-muted">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
                            </a>
                            <button type="submit" class="btn btn-primary px-4 font-weight-bold">
                                Lanjut Pembuatan Invoice <i class="fas fa-chevron-right ml-1"></i>
                            </button>
                        </div>

                    <?= form_close(); ?>

                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .card-radio-select {
        transition: all 0.2s ease-in-out;
        background-color: #ffffff;
    }
    .card-radio-select:hover {
        border-color: #007bff !important;
        background-color: #f8f9fa;
    }
    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #007bff;
        border-color: #007bff;
    }
    .cursor-pointer {
        cursor: pointer;
    }
    .bg-light-warning {
        background-color: #fffdf5;
    }
    .text-warning-dark {
        color: #856404;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>