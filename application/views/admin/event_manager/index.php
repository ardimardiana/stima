<div class="container-fluid px-4">
    <h1 class="mt-4">Kelola Event: <?= htmlspecialchars($event->nama_event, ENT_QUOTES, 'UTF-8'); ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/events') ?>">Manajemen Event</a></li>
        <li class="breadcrumb-item active">Dasbor</li>
    </ol>
    
    <?php $this->load->view('admin/event_manager/_nav'); ?>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-certificate me-1"></i>
                    Pengaturan Sertifikat
                </div>
                <div class="card-body">
                    <p>Status saat ini: 
                        <?php if($event->sertifikat_aktif == 1): ?>
                            <strong class="text-success">AKTIF</strong> (Peserta yang hadir dapat mengunduh sertifikat).
                        <?php else: ?>
                            <strong class="text-danger">NONAKTIF</strong> (Peserta belum dapat mengunduh sertifikat).
                        <?php endif; ?>
                    </p>
                    <a href="<?= site_url('admin/event_manager/toggle_certificates/' . $event->event_id) ?>" 
                       class="btn <?= $event->sertifikat_aktif == 1 ? 'btn-danger' : 'btn-success' ?>"
                       onclick="return confirm('Anda yakin ingin mengubah status akses sertifikat?')">
                       <?= $event->sertifikat_aktif == 1 ? 'Nonaktifkan Sekarang' : 'Aktifkan Sekarang' ?>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="fs-3 fw-bold"><?= $stats->total_participants; ?></div>
                    <div>Total Pendaftar</div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card bg-warning text-dark mb-4">
                <div class="card-body">
                    <div class="fs-3 fw-bold"><?= $stats->payments_to_validate; ?></div>
                    <div>Pembayaran Perlu Validasi</div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="fs-3 fw-bold"><?= $stats->total_papers; ?></div>
                    <div>Total Artikel Masuk</div>
                </div>
            </div>
        </div>
    </div>
</div>