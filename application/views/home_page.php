<?php $this->load->view('partials/public_header'); ?>
<?php $this->load->view('partials/public_navbar'); ?>

    <div class="container mt-4 mb-5 flex-grow-1">
    
    <?php if (!$is_active_event): ?>
        <div class="alert text-center border-0 rounded-3 shadow-sm" style="background-color: var(--c-light); color: var(--c-dark);" role="alert">
            <i class="fas fa-archive me-2"></i>
            <strong>Arsip Event:</strong> Anda sedang melihat halaman untuk event yang telah berlalu.
        </div>
    <?php endif; ?>
    
        <?php if (!empty($banners)): ?>
            <div id="eventCarousel" class="carousel slide shadow-sm rounded-4 mb-4 overflow-hidden" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <?php foreach($banners as $index => $banner): ?>
                    <button type="button" data-bs-target="#eventCarousel" data-bs-slide-to="<?= $index; ?>" class="<?= $index == 0 ? 'active' : ''; ?>"></button>
                    <?php endforeach; ?>
                </div>
                <div class="carousel-inner">
                    <?php foreach($banners as $index => $banner): ?>
                    <div class="carousel-item <?= $index == 0 ? 'active' : ''; ?>">
                        <img src="<?= base_url($banner->image_path); ?>" class="d-block w-100" alt="Banner Event" style="object-fit: cover; max-height: 450px;">
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
    
        <header class="hero-section text-center position-relative">
            <h1 class="display-5 fw-bold"><?= htmlspecialchars($event->nama_event, ENT_QUOTES, 'UTF-8'); ?></h1>
            <p class="lead mt-3 mb-0" style="color: var(--c-light);"><?= htmlspecialchars($event->tagline, ENT_QUOTES, 'UTF-8'); ?></p>
            
            <div id="countdown-container" class="mt-5">
                <h6 class="mb-3 text-uppercase fw-semibold" style="letter-spacing: 1.5px; color: var(--c-light);">Acara akan dimulai dalam</h6>
                <div id="countdown-timer" class="d-flex justify-content-center align-items-center gap-2 gap-md-4 fs-3">
                    <div>
                        <div id="days" class="countdown-number">00</div>
                        <div class="countdown-label">Hari</div>
                    </div>
                    <div class="d-none d-sm-block fs-2 pb-4">:</div>
                    <div>
                        <div id="hours" class="countdown-number">00</div>
                        <div class="countdown-label">Jam</div>
                    </div>
                    <div class="d-none d-sm-block fs-2 pb-4">:</div>
                    <div>
                        <div id="minutes" class="countdown-number">00</div>
                        <div class="countdown-label">Menit</div>
                    </div>
                    <div class="d-none d-sm-block fs-2 pb-4">:</div>
                    <div>
                        <div id="seconds" class="countdown-number">00</div>
                        <div class="countdown-label">Detik</div>
                    </div>
                </div>
                <div id="countdown-finished" class="alert mt-4 d-none fw-bold shadow-sm" style="background-color: var(--c-sky); color: white; border: none;">
                    <h4 class="mb-0"><i class="fas fa-flag-checkered me-2"></i> Acara Telah Dimulai!</h4>
                </div>
            </div>
        </header>
        
        <div class="row g-4">
            <main class="col-12 col-lg-8">
                <div class="card h-100">
                    <div class="card-body p-4 p-md-5">
                        <h4 class="mb-4 text-primary-custom fw-bold"><i class="fas fa-info-circle me-2"></i> Info & Pembayaran</h4>
                        <hr style="border-color: var(--c-light); opacity: 1;">
                        <div class="content-wrapper text-muted lh-lg">
                            <?= $event->info_pembayaran; ?>
                        </div>
                    </div>
                </div>
            </main>

            <aside class="col-12 col-lg-4 d-flex flex-column gap-4">
                <div class="card">
                    <div class="card-header card-header-custom p-3">
                        <h5 class="mb-0 fs-6 text-uppercase"><i class="fas fa-calendar-alt me-2"></i> Tanggal Penting</h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php if($event->tgl_batas_daftar): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <span class="text-muted">Batas Akhir Pendaftaran</span>
                                <strong style="color: var(--c-dark);"><?= date('d F Y', strtotime($event->tgl_batas_daftar)); ?></strong>
                            </li>
                        <?php endif; ?>
                        <?php if($event->tgl_batas_submit): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <span class="text-muted">Batas Akhir Submisi</span>
                                <strong style="color: var(--c-dark);"><?= date('d F Y', strtotime($event->tgl_batas_submit)); ?></strong>
                            </li>
                        <?php endif; ?>
                        <?php if($event->tgl_pengumuman): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <span class="text-muted">Pengumuman Penerimaan</span>
                                <strong style="color: var(--c-dark);"><?= date('d F Y', strtotime($event->tgl_pengumuman)); ?></strong>
                            </li>
                        <?php endif; ?>
                        <?php if($event->tgl_mulai_acara): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <span class="text-muted">Pelaksanaan Acara</span>
                                <strong style="color: var(--c-dark);"><?= date('d', strtotime($event->tgl_mulai_acara)) . '-' . date('d F Y', strtotime($event->tgl_selesai_acara)); ?></strong>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="card">
                    <div class="card-header card-header-custom p-3">
                        <h5 class="mb-0 fs-6 text-uppercase"><i class="fas fa-bullhorn me-2"></i> Pengumuman</h5>
                    </div>
                    <div class="card-body p-4">
                        <?php if (!empty($announcements)): ?>
                            <?php foreach($announcements as $item): ?>
                                <div class="mb-4 last-mb-none">
                                    <h6 class="card-title fw-bold" style="color: var(--c-dark);"><?= htmlspecialchars($item->judul, ENT_QUOTES, 'UTF-8'); ?></h6>
                                    <p class="card-text small mb-2" style="color: var(--c-primary);">
                                        <i class="far fa-clock me-1"></i> <?= date('d F Y', strtotime($item->tgl_publish)); ?>
                                    </p>
                                    <p class="card-text text-muted"><?= nl2br(htmlspecialchars($item->isi, ENT_QUOTES, 'UTF-8')); ?></p>
                                </div>
                                <?php if(next($announcements)): ?><hr style="border-color: var(--c-light); opacity: 1;"><?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-muted py-3">
                                <i class="far fa-bell-slash fs-1 mb-3" style="color: var(--c-light);"></i>
                                <p class="mb-0">Belum ada pengumuman baru saat ini.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </aside>
        </div>
    </div>
<?php $this->load->view('partials/public_footer'); ?>