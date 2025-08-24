<?php $this->load->view('partials/public_header'); ?>
<?php $this->load->view('partials/public_navbar'); ?>

    <div class="container mt-4">
    
    <?php if (!$is_active_event): ?>
        <div class="alert alert-warning text-center" role="alert">
            <i class="fas fa-archive me-2"></i>
            <strong>Arsip Event:</strong> Anda sedang melihat halaman untuk event yang telah berlalu.
        </div>
    <?php endif; ?>
    
        <header class="hero-section text-center">
            <h1><?= htmlspecialchars($event->nama_event, ENT_QUOTES, 'UTF-8'); ?></h1>
            <p class="lead"><?= htmlspecialchars($event->tagline, ENT_QUOTES, 'UTF-8'); ?></p>
        </header>

        <div class="row">
            <main class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <?= $event->info_pembayaran; ?>
                    </div>
                </div>
            </main>

            <aside class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Tanggal Penting</h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php if($event->tgl_batas_daftar): ?>
                            <li class="list-group-item">Batas Akhir Pendaftaran: <strong><?= date('d F Y', strtotime($event->tgl_batas_daftar)); ?></strong></li>
                        <?php endif; ?>
                        <?php if($event->tgl_batas_submit): ?>
                            <li class="list-group-item">Batas Akhir Submisi: <strong><?= date('d F Y', strtotime($event->tgl_batas_submit)); ?></strong></li>
                        <?php endif; ?>
                        <?php if($event->tgl_pengumuman): ?>
                            <li class="list-group-item">Pengumuman Penerimaan: <strong><?= date('d F Y', strtotime($event->tgl_pengumuman)); ?></strong></li>
                        <?php endif; ?>
                        <?php if($event->tgl_mulai_acara): ?>
                            <li class="list-group-item">Pelaksanaan Acara: <strong><?= date('d', strtotime($event->tgl_mulai_acara)) . '-' . date('d F Y', strtotime($event->tgl_selesai_acara)); ?></strong></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="card shadow-sm mt-4">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0"><i class="fas fa-bullhorn me-2"></i>Pengumuman</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($announcements)): ?>
                            <?php foreach($announcements as $item): ?>
                                <div class="mb-3">
                                    <h6 class="card-title"><?= htmlspecialchars($item->judul, ENT_QUOTES, 'UTF-8'); ?></h6>
                                    <p class="card-text small text-muted">
                                        Diposting pada: <?= date('d F Y', strtotime($item->tgl_publish)); ?>
                                    </p>
                                    <p class="card-text"><?= nl2br(htmlspecialchars($item->isi, ENT_QUOTES, 'UTF-8')); ?></p>
                                </div>
                                <?php if(next($announcements)): ?><hr><?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Belum ada pengumuman baru saat ini.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </aside>
        </div>
    </div>
<?php $this->load->view('partials/public_footer'); ?>