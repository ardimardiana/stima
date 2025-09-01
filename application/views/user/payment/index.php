<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?> - <?=$_ENV['SITE_NAME']?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand" href="<?= site_url('user/dashboard'); ?>">Dasbor Peserta</a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="<?= site_url('auth/logout'); ?>">Logout</a>
            </li>
        </ul>
      </div>
    </nav>
    
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>Detail Tagihan & Pembayaran</h4>
                    </div>
                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="mb-0">Nomor Invoice: <?= htmlspecialchars($payment->nomor_invoice, ENT_QUOTES, 'UTF-8'); ?></h5>
                                <small class="text-muted">Untuk Pendaftaran sebagai: <?= ucfirst($payment->peran_event); ?></small>
                            </div>
                            <div>
                                <?php 
                                    $status = $payment->status_pembayaran;
                                    $badge_class = 'bg-secondary';
                                    if ($status == 'menunggu') $badge_class = 'bg-warning text-dark';
                                    else if ($status == 'validasi') $badge_class = 'bg-info text-dark';
                                    else if ($status == 'ditolak') $badge_class = 'bg-danger';
                                ?>
                                <span class="badge fs-6 <?= $badge_class; ?>">Status: <?= ucfirst($status); ?></span>
                            </div>
                        </div>

                        <div class="alert alert-light border">
                            <strong><i class="fas fa-info-circle me-1"></i> Instruksi Pembayaran:</strong>
                            <hr class="my-2">
<strong>Biaya Pendaftaran:</strong>
<ul>
<li>Pemakalah: Rp 350.000,-</li>
<li>Peserta Umum: Rp 150.000,-</li>
<li>Mahasiswa FT UNMA: Rp 100.000,-</li>
</ul>
<strong>Transfer Biaya ke:</strong>
<br>Bank BJB
<br>No. Rekening: 0061996737101
<br>a.n. Fakultas Teknik UNMA
                        </div>
                        
                        <hr class="my-4">

                        <div>
                            <h5 class="mb-3"><i class="fas fa-upload me-2"></i>Unggah Bukti Pembayaran Anda</h5>
                            
                            <?php if($status == 'menunggu' || $status == 'ditolak'): ?>
                                <?php if($status == 'ditolak'): ?>
                                    <div class="alert alert-danger">Pembayaran Anda sebelumnya ditolak. Silakan periksa kembali dan unggah bukti bayar yang benar.</div>
                                <?php endif; ?>
                                
                                <?= form_open_multipart('user/payment/do_upload/' . $payment->payment_id); ?>
                                    <div class="mb-3">
                                        <label for="bukti_bayar" class="form-label">Pilih file <strong>GAMBAR</strong>(JPG, PNG, PDF, maks 2MB)</label>
                                        <input class="form-control" type="file" name="bukti_bayar" id="bukti_bayar" required accept="image/*">
                                    </div>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i> Kirim Bukti Bayar</button>
                                    <a href="<?= site_url('user/dashboard'); ?>" class="btn btn-secondary">Kembali ke Dasbor</a>
                                <?= form_close(); ?>

                            <?php elseif($status == 'validasi'): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-hourglass-half me-2"></i>
                                    Bukti pembayaran Anda telah terkirim pada tanggal <strong><?= date('d F Y, H:i', strtotime($payment->tgl_unggah)); ?></strong> dan sedang menunggu validasi dari panitia.
                                </div>
                                <a href="<?= site_url('user/dashboard'); ?>" class="btn btn-secondary">Kembali ke Dasbor</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-light text-center p-3 mt-5 border-top">
        <p class="mb-0">Copyright &copy; <?=$_ENV['SITE_NAME']?> <?= date('Y'); ?></p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>