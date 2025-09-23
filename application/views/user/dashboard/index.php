<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?> - <?=$_ENV['SITE_NAME']?></title>
    <link rel="icon" href="data:image/x-icon;base64,...">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= site_url('user/dashboard'); ?>">Dasbor Peserta</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user me-1"></i> <?= $nama_user; ?>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">
                    <li><a class="dropdown-item" href="<?= site_url('user/profile'); ?>">Edit Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?= site_url('auth/logout'); ?>">Logout</a></li>
                  </ul>
                </li>
            </ul>
        </div>
    </nav>
    
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
                <h4 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Event Aktif: <?= htmlspecialchars($active_event->nama_event, ENT_QUOTES, 'UTF-8'); ?></h4>
            </div>
            <div class="card-body p-4">
                
                <?php
                    // Variabel untuk mengecek batas waktu pendaftaran umum
                    $is_registration_open = (date('Y-m-d H:i:s') <= $active_event->tgl_batas_daftar . ' 23:59:59');
                ?>

                <div class="p-3 mb-3 border rounded">
                    <h5><i class="fas fa-user me-2"></i>Partisipasi sebagai Peserta</h5>
                    <?php if (!$registration_peserta): ?>
                        <?php if ($is_registration_open): ?>
                            <p class="text-muted">Ikuti rangkaian seminar sebagai peserta umum.</p>
                            <?= form_open('user/dashboard/register_for_event'); ?>
                                <input type="hidden" name="event_id" value="<?= $active_event->event_id; ?>">
                                <button type="submit" name="peran" value="peserta" class="btn btn-outline-primary" 
                                        onclick="return confirm('Anda yakin ingin mendaftar sebagai Peserta?')">
                                    Daftar sebagai Peserta
                                </button>
                            <?= form_close(); ?>
                        <?php  else: ?>
                            <div class="alert alert-warning mb-0">Pendaftaran untuk peserta umum telah ditutup.</div>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php $this->load->view('partials/payment_status_card', ['registration' => $registration_peserta]); ?>
                    <?php endif; ?>
                </div>

                <div class="p-3 border rounded">
                    <h5><i class="fas fa-file-powerpoint me-2"></i>Partisipasi sebagai Presenter</h5>
                    <p class="text-center"><a href="<?=$_ENV['grup_wa']?>"><img style="max-width:200px" src="https://assets.kompasiana.com/items/album/2020/12/28/wa-grouo3-5fe9c2fad541df11077fce52.png"></a></p>
                    <?php if (!$registration_presenter): ?>
                        <?php if ($is_registration_open): ?>
                            <p class="text-muted">Daftarkan diri Anda untuk mempresentasikan naskah ilmiah.</p>
                            <?= form_open('user/dashboard/register_for_event'); ?>
                                <input type="hidden" name="event_id" value="<?= $active_event->event_id; ?>">
                                <button type="submit" name="peran" value="presenter" class="btn btn-outline-success" 
                                        onclick="return confirm('Anda yakin ingin mendaftar sebagai Presenter?')">
                                    Daftar sebagai Presenter
                                </button>
                            <?= form_close(); ?>
                        <?php else: ?>
                            <div class="alert alert-warning mb-0">Pendaftaran untuk presenter telah ditutup.</div>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php $this->load->view('partials/payment_status_card', ['registration' => $registration_presenter, 'paper' => $paper_presenter, 'event' => $active_event]); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- =================================================================== -->
        <!-- ##            AKHIR PERUBAHAN STRUKTUR KONTEN                    ## -->
        <!-- =================================================================== -->

        <!-- Bagian Riwayat Event (Tetap Sama) -->
        <div class="card shadow-sm mb-4">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>