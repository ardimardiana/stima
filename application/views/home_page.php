<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($event->nama_event, ENT_QUOTES, 'UTF-8'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .hero-section {
            background-color: #343a40;
            color: white;
            padding: 4rem 2rem;
            margin-bottom: 2rem;
            border-radius: 0.5rem;
        }
        .card-header-custom {
            background-color: #0d6efd;
            color: white;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
      <div class="container">
        <a class="navbar-brand" href="<?= site_url(); ?>"><strong><?= htmlspecialchars($event->tahun, ENT_QUOTES, 'UTF-8'); ?></strong> CMS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link active" href="<?= site_url($event->slug_url); ?>">Home</a>
            </li>
            
            <?php if (!empty($archived_events)): ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Riwayat Event
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <?php foreach($archived_events as $archive): ?>
                  <li><a class="dropdown-item" href="<?= site_url($archive->slug_url); ?>"><?= htmlspecialchars($archive->nama_event, ENT_QUOTES, 'UTF-8'); ?></a></li>
                <?php endforeach; ?>
              </ul>
            </li>
            <?php endif; ?>
    
            <li class="nav-item">
              <a class="nav-link" href="#">Speakers</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Jadwal</a>
            </li>
            
            <?php if ($is_active_event): ?>
                <li class="nav-item ms-lg-3">
                    <a class="btn btn-outline-light" href="<?= site_url('auth/login'); ?>">Login</a>
                </li>
                 <li class="nav-item ms-lg-2">
                    <a class="btn btn-primary" href="<?= site_url('auth/register'); ?>">Daftar</a>
                </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>

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

    <footer class="bg-dark text-white text-center p-4 mt-5">
        <div class="container">
            <p class="mb-0">Copyright &copy; Panitia Konferensi <?= date('Y'); ?>. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>