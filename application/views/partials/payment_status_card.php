<?php 
    // Ambil data dari variabel yang dikirim
    $payment_status = $registration->payment->status_pembayaran;
?>

<?php if($payment_status == 'menunggu'): ?>
    <div class="alert alert-warning mb-0">
        <p class="mb-1"><strong>Status Anda:</strong> Menunggu Pembayaran.</p>
        <a href="<?= site_url('user/payment/index/' . $registration->registration_id); ?>" class="btn btn-sm btn-primary mb-2">Lakukan Pembayaran Sekarang</a>
        
        <a href="<?= site_url('user/dashboard/cancel_registration/' . $registration->registration_id); ?>" 
           class="btn btn-sm btn-outline-danger" 
           onclick="return confirm('Anda yakin ingin membatalkan pendaftaran untuk peran ini?')">
            Batalkan Pendaftaran
        </a>
    </div>
<?php elseif($payment_status == 'validasi'): ?>
    <div class="alert alert-info mb-0">
        <p class="mb-0"><strong>Status Anda:</strong> Pembayaran Anda sedang divalidasi oleh panitia. Mohon tunggu.</p>
    </div>
<?php elseif($payment_status == 'ditolak'): ?>
    <div class="alert alert-danger mb-0">
        <p class="mb-1"><strong>Status Anda:</strong> Pembayaran ditolak. Mohon periksa kembali bukti transfer Anda.</p>
        <a href="<?= site_url('user/payment/index/' . $registration->registration_id); ?>" class="btn btn-sm btn-danger mb-2">Upload Ulang Bukti Bayar</a>
        
        <a href="<?= site_url('user/dashboard/cancel_registration/' . $registration->registration_id); ?>" 
           class="btn btn-sm btn-outline-danger" 
           onclick="return confirm('Anda yakin ingin membatalkan pendaftaran untuk peran ini?')">
            Batalkan Pendaftaran
        </a>
    </div>
<?php elseif($payment_status == 'lunas'): ?>
    <div class="alert alert-success mb-0">
        <div class="row align-items-center">
            <div class="col-md-3 text-center">
                <img src="<?= base_url($registration->qr_code_path); ?>" alt="QR Code Kehadiran" class="img-fluid rounded">
            </div>
            <div class="col-md-9">
                <h5 class="alert-heading">Pembayaran Berhasil!</h5>
                <p>Gunakan QR Code di samping ini untuk check-in kehadiran.</p>
                
                <!-- Tampilkan tombol Manajemen Artikel HANYA untuk presenter -->
                <?php if($registration->peran_event == 'presenter'): ?>
                    <a href="<?= site_url('user/article/index/' . $registration->registration_id); ?>" class="btn btn-sm btn-info">Lanjutkan ke Manajemen Artikel</a>
                <?php endif; ?>
                
                <?php if($registration->peran_event == 'peserta'): ?>
                    <?php if ($registration->status_kehadiran == 1): ?>
                        <hr>
                        <h5>Sertifikat Anda</h5>
                        <?php if ($active_event->sertifikat_aktif == 1): ?>
                            <p>Silakan unduh sertifikat Anda di bawah ini:</p>
                            <a href="<?= $registration->sertifikat_path ?>" class="btn btn-sm btn-info"><i class="fas fa-download me-1"></i> Unduh Sertifikat Peserta</a>
                        <?php else: ?>
                            <p class="text-muted">Sertifikat akan tersedia untuk diunduh setelah panitia mengaktifkan akses.</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-muted mt-3">Sertifikat akan dapat diakses setelah Anda melakukan check-in kehadiran pada saat acara.</p>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php if($registration->peran_event == 'presenter'): ?>
                    <?php if ($registration->status_kehadiran == 1): ?>
                        <hr>
                        <h5>Sertifikat Anda</h5>
                        <?php if ($active_event->sertifikat_aktif == 1): ?>
                            <p>Silakan unduh sertifikat Anda di bawah ini:</p>
                            <a href="<?= $registration->sertifikat_presenter_path ?>" class="btn btn-sm btn-info"><i class="fas fa-download me-1"></i> Unduh Sertifikat Peserta</a>
                        <?php else: ?>
                            <p class="text-muted">Sertifikat akan tersedia untuk diunduh setelah panitia mengaktifkan akses.</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-muted mt-3">Sertifikat akan dapat diakses setelah Anda melakukan check-in kehadiran pada saat acara.</p>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Tampilkan status sertifikat -->
            </div>
        </div>
    </div>
<?php endif; ?>