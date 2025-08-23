<div class="container-fluid px-4">
    <h1 class="mt-4">Detail Pembayaran</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">...</li>
        <li class="breadcrumb-item active">Detail</li>
    </ol>

    <div class="card">
        <div class="card-header">
            Validasi untuk: <strong><?= $payment->nama_depan . ' ' . $payment->nama_belakang; ?></strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Data Peserta</h5>
                    <table class="table">
                        <tr><th>No. Invoice</th><td><?= $payment->nomor_invoice; ?></td></tr>
                        <tr><th>Nama</th><td><?= $payment->nama_depan . ' ' . $payment->nama_belakang; ?></td></tr>
                        <tr><th>Email</th><td><?= $payment->email; ?></td></tr>
                        <tr><th>Peran</th><td><span class="badge bg-info"><?= ucfirst($payment->peran_event); ?></span></td></tr>
                        <tr><th>Status</th><td><span class="badge bg-warning"><?= ucfirst($payment->status_pembayaran); ?></span></td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Bukti Pembayaran</h5>
                    <?php if (empty($payment->bukti_bayar_path)): ?>
                        <div class="alert alert-secondary">Peserta belum mengunggah bukti bayar.</div>
                    <?php else: ?>
                        <a href="<?= base_url($payment->bukti_bayar_path) ?>" target="_blank">
                            <img src="<?= base_url($payment->bukti_bayar_path) ?>" class="img-fluid rounded border" alt="Bukti Bayar">
                        </a>
                        <small class="d-block text-center mt-2">Klik gambar untuk memperbesar</small>
                    <?php endif; ?>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <h5>Aksi Validasi</h5>
                <?= form_open('admin/participants/process_validation'); ?>
                    <input type="hidden" name="payment_id" value="<?= $payment->payment_id; ?>">
                    <button type="submit" name="action" value="lunas" class="btn btn-success"><i class="fas fa-check"></i> Setujui Pembayaran</button>
                    <button type="submit" name="action" value="ditolak" class="btn btn-danger"><i class="fas fa-times"></i> Tolak Pembayaran</button>
                    <a href="<?= site_url('admin/participants/index/' . $payment->event_id); ?>" class="btn btn-secondary">Kembali</a>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>