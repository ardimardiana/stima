<?php $this->load->view('partials/public_header'); ?>
<?php $this->load->view('partials/public_navbar'); ?>

    <div class="container mt-4">
    
    <?php if (!$is_active_event): ?>
        <div class="alert alert-warning text-center" role="alert">
            <i class="fas fa-archive me-2"></i>
            <strong>Arsip Event:</strong> Anda sedang melihat halaman untuk event yang telah berlalu.
        </div>
    <?php endif; ?>
    
        <?php if (!empty($banners)): ?>
            <div id="eventCarousel" class="carousel slide shadow-sm rounded mb-4" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <?php foreach($banners as $index => $banner): ?>
                    <button type="button" data-bs-target="#eventCarousel" data-bs-slide-to="<?= $index; ?>" class="<?= $index == 0 ? 'active' : ''; ?>"></button>
                    <?php endforeach; ?>
                </div>
                <div class="carousel-inner rounded">
                    <?php foreach($banners as $index => $banner): ?>
                    <div class="carousel-item <?= $index == 0 ? 'active' : ''; ?>">
                        <img src="<?= base_url($banner->image_path); ?>" class="d-block w-100" alt="Banner Event">
                    </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#eventCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span><span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#eventCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span><span class="visually-hidden">Next</span>
                </button>
            </div>
        <?php endif; ?>
    
        <header class="hero-section text-center">
            <h1><?= htmlspecialchars($event->nama_event, ENT_QUOTES, 'UTF-8'); ?></h1>
            <p class="lead"><?= htmlspecialchars($event->tagline, ENT_QUOTES, 'UTF-8'); ?></p>
            
        <div id="countdown-container" class="mt-4">
        <h5 class="mb-3">Acara akan dimulai dalam:</h5>
        <div id="countdown-timer" class="d-flex justify-content-center gap-3 fs-2">
            <div>
                <span id="days" class="countdown-number">0</span>
                <div class="countdown-label">Hari</div>
            </div>
            <div>:</div>
            <div>
                <span id="hours" class="countdown-number">0</span>
                <div class="countdown-label">Jam</div>
            </div>
            <div>:</div>
            <div>
                <span id="minutes" class="countdown-number">0</span>
                <div class="countdown-label">Menit</div>
            </div>
            <div>:</div>
            <div>
                <span id="seconds" class="countdown-number">0</span>
                <div class="countdown-label">Detik</div>
            </div>
        </div>
        <div id="countdown-finished" class="alert alert-success mt-4 d-none">
            <h4>Acara Telah Dimulai!</h4>
        </div>
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