<?php 
    // Ambil data dari variabel yang dikirim
    $payment_status = $registration->payment->status_pembayaran;
?>

<?php if($payment_status == 'menunggu'): ?>
    <div class="alert alert-warning mb-0">
        <p class="mb-1"><strong>Status Anda:</strong> Menunggu Pembayaran.</p>
        <a href="<?= site_url('user/payment/index/' . $registration->registration_id); ?>" class="btn btn-sm btn-primary">Lakukan Pembayaran Sekarang</a>
    </div>
<?php elseif($payment_status == 'validasi'): ?>
    <div class="alert alert-info mb-0">
        <p class="mb-0"><strong>Status Anda:</strong> Pembayaran Anda sedang divalidasi oleh panitia. Mohon tunggu.</p>
    </div>
<?php elseif($payment_status == 'ditolak'): ?>
    <div class="alert alert-danger mb-0">
        <p class="mb-1"><strong>Status Anda:</strong> Pembayaran ditolak. Mohon periksa kembali bukti transfer Anda.</p>
        <a href="<?= site_url('user/payment/index/' . $registration->registration_id); ?>" class="btn btn-sm btn-danger">Upload Ulang Bukti Bayar</a>
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

                <!-- Tampilkan status sertifikat -->
            </div>
        </div>
    </div>
<?php endif; ?>