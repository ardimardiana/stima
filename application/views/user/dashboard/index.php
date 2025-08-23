<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark"> ... </nav>
    
    <div class="container mt-4">
        <h2 class="mb-4">Selamat Datang, <?= $nama_user; ?>!</h2>
        
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
        <?php endif; ?>
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Event Aktif</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($active_events)): ?>
                    <?php foreach($active_events as $event): ?>
                        <div class="p-3 mb-3 border rounded">
                            <h5><?= htmlspecialchars($event->nama_event, ENT_QUOTES, 'UTF-8'); ?></h5>
                            
                            <?php if ($event->is_registered): 
                            
                            // Asumsi data payment sudah di-load dari controller
                            $payment_status = $event->payment->status_pembayaran; 
                             if($payment_status == 'menunggu'): ?>
                                    <div class="alert alert-warning">
                                        <p class="mb-1"><strong>Status Anda:</strong> Menunggu Pembayaran.</p>
                                        <a href="<?= site_url('user/payment/index/' . $event->registration_id); ?>" class="btn btn-sm btn-primary">Lakukan Pembayaran Sekarang</a>
                                    </div>
                                <?php elseif($payment_status == 'validasi'): ?>
                                    <div class="alert alert-info">
                                        <p class="mb-0"><strong>Status Anda:</strong> Pembayaran Anda sedang divalidasi oleh panitia. Mohon tunggu.</p>
                                    </div>
                                <?php elseif($payment_status == 'ditolak'): ?>
                                     <div class="alert alert-danger">
                                        <p class="mb-1"><strong>Status Anda:</strong> Pembayaran ditolak. Mohon periksa kembali bukti transfer Anda.</p>
                                        <a href="<?= site_url('user/payment/index/' . $event->registration_id); ?>" class="btn btn-sm btn-danger">Upload Ulang Bukti Bayar</a>
                                    </div>
                                <?php elseif($payment_status == 'lunas'): ?>
                                    <div class="alert alert-success">
                                        <div class="row align-items-center">
                                            <div class="col-md-3 text-center">
                                                <img src="<?= base_url($event->registration->qr_code_path); ?>" alt="QR Code Kehadiran" class="img-fluid rounded">
                                            </div>
                                            <div class="col-md-9">
                                                <h5 class="alert-heading">Pembayaran Berhasil!</h5>
                                                <p>Terima kasih, pembayaran Anda telah kami konfirmasi. Gunakan QR Code di samping ini untuk melakukan check-in kehadiran pada saat acara.</p>
                                                 <?php if ($event->registration->status_kehadiran == 1): ?>
                                                    <hr>
                                                    <h5>Sertifikat Anda</h5>
                                                    <?php if ($event->sertifikat_aktif == 1): ?>
                                                        <p>Silakan unduh sertifikat Anda di bawah ini:</p>
                                                        <a href="<?= base_url($event->registration->sertifikat_path) ?>" class="btn btn-sm btn-info"><i class="fas fa-download me-1"></i> Unduh Sertifikat Peserta</a>
                                            
                                                        <?php if($event->user_role == 'presenter'): ?>
                                                            <a href="<?= base_url($event->registration->sertifikat_presenter_path) ?>" class="btn btn-sm btn-secondary"><i class="fas fa-download me-1"></i> Unduh Sertifikat Presenter</a>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <p class="text-muted">Sertifikat akan tersedia untuk diunduh setelah panitia mengaktifkan akses.</p>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <p class="text-muted mt-3">Sertifikat akan dapat diakses setelah Anda melakukan check-in kehadiran pada saat acara.</p>
                                                <?php endif; ?>
                                                <?php if($event->user_role == 'presenter'): ?>
                                                    <a href="<?= site_url('user/article/index/' . $event->registration_id); ?>" class="btn btn-sm btn-info">
                                                        Lanjutkan ke Manajemen Artikel
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                
                            <?php else: ?>
                                <p>Anda belum terdaftar pada event ini. Silakan pilih peran Anda:</p>
                                <?= form_open('user/dashboard/register_for_event'); ?>
                                    <input type="hidden" name="event_id" value="<?= $event->event_id; ?>">
                                    <div class="d-flex gap-2">
                                        <button type="submit" name="peran" value="peserta" class="btn btn-outline-primary">
                                            <i class="fas fa-user me-1"></i> Daftar sebagai Peserta
                                        </button>
                                        <button type="submit" name="peran" value="presenter" class="btn btn-primary">
                                            <i class="fas fa-file-powerpoint me-1"></i> Daftar sebagai Presenter
                                        </button>
                                    </div>
                                <?= form_close(); ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Saat ini tidak ada event yang sedang aktif.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card shadow-sm">
         <div class="card-header">
            <h4 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Partisipasi Event</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($history_events)): ?>
                <div class="list-group">
                <?php foreach($history_events as $history): ?>
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><?= htmlspecialchars($history->nama_event, ENT_QUOTES, 'UTF-8'); ?></h5>
                            <small>Peran: <?= ucfirst($history->peran_event); ?></small>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <div>
                                <?php 
                                    $badge_class = ($history->status_pembayaran == 'lunas') ? 'bg-success' : 'bg-secondary';
                                ?>
                                <span class="badge <?= $badge_class; ?>">
                                    <i class="fas fa-money-bill-wave me-1"></i>
                                    Pembayaran <?= ucfirst($history->status_pembayaran); ?>
                                </span>
                            </div>
    
                            <div>
                                <?php if($history->status_kehadiran == 1): ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-user-check me-1"></i> Hadir
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-user-times me-1"></i> Tidak Hadir
                                    </span>
                                <?php endif; ?>
                            </div>
    
                            <?php if($history->status_kehadiran == 1 && $history->status_pembayaran == 'lunas'): ?>
                                <div class="ms-md-auto"> <?php if(!empty($history->sertifikat_path)): ?>
                                        <a href="<?= $history->sertifikat_path; ?>" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-download me-1"></i> Sertifikat Peserta
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if($history->peran_event == 'presenter' && !empty($history->sertifikat_presenter_path)): ?>
                                        <a href="<?= $history->sertifikat_presenter_path; ?>" target="_blank" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-download me-1"></i> Sertifikat Presenter
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Anda belum pernah berpartisipasi dalam event sebelumnya.</p>
            <?php endif; ?>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>