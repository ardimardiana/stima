<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?> - CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        /* (Style untuk stepper tetap sama) */
        .stepper-wrapper { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .stepper-item { position: relative; display: flex; flex-direction: column; align-items: center; flex: 1; }
        .stepper-item::before { position: absolute; content: ""; border-bottom: 2px solid #ccc; width: 100%; top: 20px; left: -50%; z-index: 2; }
        .stepper-item:first-child::before { content: none; }
        .stepper-item.completed .step-counter { background-color: #198754; color: white; }
        .stepper-item.completed::after { position: absolute; content: ""; border-bottom: 2px solid #198754; width: 100%; top: 20px; left: 50%; z-index: 3; }
        .step-counter { position: relative; z-index: 5; display: flex; justify-content: center; align-items: center; width: 40px; height: 40px; border-radius: 50%; background: #ccc; margin-bottom: 6px; color: white;}
        .step-name { font-size: 14px; font-weight: 600; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand" href="<?= site_url('user/dashboard'); ?>">Dasbor Peserta</a>
        <ul class="navbar-nav ms-auto"><li class="nav-item"><a class="nav-link" href="<?= site_url('auth/logout'); ?>">Logout</a></li></ul>
      </div>
    </nav>

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Manajemen Artikel</h2>
            <a href="<?= site_url('user/dashboard'); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali ke Dasbor</a>
        </div>

        <?php 
            $status = $paper ? $paper->status_artikel : 'belum_submit';
            $step1_class = ($status != 'belum_submit') ? 'completed' : '';
            $step2_class = ($status == 'in_review' || $status == 'revision' || $status == 'revision_submitted' || $status == 'rejected' || $status == 'final_submitted') ? 'completed' : '';
            $step3_class = ($status == 'accepted' ) ? 'completed' : '';
        ?>

        <div class="stepper-wrapper">
             <div class="stepper-item <?= $step1_class; ?>">
                <div class="step-counter">1</div>
                <div class="step-name">Submit Awal</div>
            </div>
            <div class="stepper-item <?= $step2_class; ?>">
                <div class="step-counter">2</div>
                <div class="step-name">Proses Review</div>
            </div>
            <div class="stepper-item <?= $step3_class; ?>">
                <div class="step-counter">3</div>
                <div class="step-name">Hasil & Finalisasi</div>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-8">
                <?php if($status == 'belum_submit'): ?>
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white"><h5 class="mb-0"><i class="fas fa-upload me-2"></i>Langkah 1: Submit Naskah Awal</h5></div>
                        <div class="card-body p-4">
                             <p class="text-muted">Silakan lengkapi detail artikel dan unggah naskah lengkap Anda dalam format **DOCX** (maks. 5MB).</p>
                             <?= form_open_multipart('user/article/submit_initial/' . $registration_id); ?>
                                 <div class="mb-3"><label class="form-label fw-bold">Pilih Topik</label><select name="topic_id" class="form-select" required><option value="">-- Pilih Topik Makalah --</option><?php foreach($topics as $topic): ?><option value="<?= $topic->topic_id; ?>"><?= htmlspecialchars($topic->nama_topik, ENT_QUOTES, 'UTF-8'); ?></option><?php endforeach; ?></select></div>
                                 <div class="mb-3"><label class="form-label fw-bold">Judul Makalah</label><input type="text" name="judul" class="form-control" required></div>
                                 <div class="mb-3"><label class="form-label fw-bold">Abstrak</label><textarea name="abstrak" class="form-control" rows="5" required></textarea></div>
                                 <div class="mb-3"><label class="form-label fw-bold">Kata Kunci</label><input type="text" name="kata_kunci" class="form-control" placeholder="Contoh: AI, machine learning, data" required></div>
                                 <hr class="my-4">
                                 <h5 class="mb-3">Informasi Penulis</h5>
                                 <div class="card bg-light border p-3 mb-3"><h6>Penulis Utama (Corresponding & Presenting Author)</h6><div class="row g-2"><div class="col-md-6"><input type="text" name="main_author_firstname" class="form-control" placeholder="Nama Depan" required></div><div class="col-md-6"><input type="text" name="main_author_lastname" class="form-control" placeholder="Nama Belakang"></div><div class="col-12"><input type="email" name="main_author_email" class="form-control" placeholder="Email" required></div><div class="col-12"><input type="text" name="main_author_affiliation" class="form-control" placeholder="Afiliasi/Institusi" required></div></div></div>
                                 <div id="additional-authors-list"></div>
                                 <button type="button" id="add-author-btn" class="btn btn-outline-secondary btn-sm mb-4"><i class="fas fa-plus me-1"></i> Tambah Penulis Lain</button>
                                 <hr class="my-4">
                                 <div class="mb-3"><label class="form-label fw-bold">Unggah Naskah Lengkap (HANYA .docx)</label><input type="file" name="file_path_initial" class="form-control" required accept=".docx"></div>
                                 <button type="submit" class="btn btn-primary w-100 py-2"><i class="fas fa-paper-plane me-2"></i>Submit Artikel</button>
                             <?= form_close(); ?>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white"><h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Ringkasan Artikel Anda</h5></div>
                        <div class="card-body p-4">
                            <div class="mb-3"><h6 class="text-muted">Judul</h6><h4><?= htmlspecialchars($paper->judul, ENT_QUOTES, 'UTF-8'); ?></h4></div>
                            <hr>
                            <div class="mb-3"><h6 class="text-muted">Abstrak</h6><p><?= nl2br(htmlspecialchars($paper->abstrak, ENT_QUOTES, 'UTF-8')); ?></p></div>
                            <hr>
                            <div class="mb-3"><h6 class="text-muted">Penulis</h6>
                                <ul class="list-group">
                                    <?php foreach($authors as $author): ?>
                                    <li class="list-group-item">
                                        <strong><?= htmlspecialchars($author->nama_depan . ' ' . $author->nama_belakang, ENT_QUOTES, 'UTF-8'); ?></strong>
                                        <?php if($author->is_corresponding_author): ?><span class="badge bg-success">Corresponding & Presenting Author</span><?php endif; ?>
                                        <br><small class="text-muted"><?= htmlspecialchars($author->afiliasi, ENT_QUOTES, 'UTF-8'); ?></small>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <hr>
                            <div><h6 class="text-muted">Dokumen Terkirim</h6>
                                <?php if($paper->file_path_initial): ?>
                                    <a href="<?= base_url($paper->file_path_initial) ?>" class="btn btn-secondary" target="_blank"><i class="fas fa-download me-2"></i> Unduh Naskah Awal/ Revisi</a>
                                <?php endif; ?>
                                <?php if($paper->file_path_final): ?>
                                    <a href="<?= base_url($paper->file_path_final) ?>" class="btn btn-secondary" target="_blank"><i class="fas fa-download me-2"></i> Naskah Final</a>
                                <?php endif; ?>
                                <?php if($paper->slide_path): ?>
                                    <a href="<?= base_url($paper->slide_path) ?>" class="btn btn-secondary" target="_blank"><i class="fas fa-download me-2"></i> Slide PPT</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <aside class="col-lg-4">
                <?php if (!empty($schedule)): ?>
                <div class="card shadow-sm mb-4 text-white bg-success">
                    <div class="card-header"><h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Jadwal Presentasi Anda</h5></div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li>
                                <strong><i class="fas fa-clock fa-fw me-2"></i>Waktu:</strong><br>
                                <?= date('d F Y', strtotime($schedule->waktu_mulai)); ?>, Pukul <?= date('H:i', strtotime($schedule->waktu_mulai)); ?> - <?= date('H:i', strtotime($schedule->waktu_selesai)); ?> WIB
                            </li>
                            <hr class="my-2 bg-white">
                            <li>
                                <strong><i class="fas fa-door-open fa-fw me-2"></i>Ruangan:</strong><br>
                                <?= htmlspecialchars($schedule->nama_ruang, ENT_QUOTES, 'UTF-8'); ?>
                            </li>
                            <?php if(!empty($schedule->lokasi)): ?>
                            <li>
                                <strong><i class="fas fa-map-marker-alt fa-fw me-2"></i>Lokasi:</strong><br>
                                <?= htmlspecialchars($schedule->lokasi, ENT_QUOTES, 'UTF-8'); ?>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Status & Aksi</h5></div>
                    <div class="card-body">
                         <?php if($status == 'submitted' || $status == 'in_review' || $status == 'revision_submitted'): ?>
                             <div class="alert alert-info mb-0"><h5 class="alert-heading">Menunggu Review</h5><p class="mb-0 small">Artikel Anda sedang dalam proses peninjauan.</p></div>
                             <?php if($status == 'submitted'): ?>
                         <hr>
                         <p class="small text-muted">Merasa ada kesalahan pada judul, file, atau data penulis? Anda dapat menghapus submisi dan mengunggah ulang.</p>
                         <a href="<?= site_url('user/article/delete_submission/' . $registration_id . '/' . $paper->paper_id) ?>" 
                            class="btn btn-sm btn-outline-danger w-100" 
                            onclick="return confirm('Anda yakin ingin menghapus submisi ini? Anda harus mengulangi proses dari awal.')">
                            <i class="fas fa-trash me-1"></i> Hapus dan Ulangi Submisi
                         </a>
                         <?php endif; ?>
                         <?php elseif($status == 'revision'): ?>
                             <div class="alert alert-warning mb-0"><h5 class="alert-heading">Dibutuhkan Revisi</h5><p class="mb-2 small">Reviewer telah memberikan masukan. Silakan unggah naskah revisi Anda.</p>
                                 <?= form_open_multipart('user/article/submit_revision/' . $registration_id . '/' . $paper->paper_id); ?>
                                     <div class="mb-2"><input type="file" name="file_revision" class="form-control" required accept=".pdf,.doc,.docx"></div>
                                     <button type="submit" class="btn btn-warning w-100"><i class="fas fa-upload me-2"></i>Kirim Revisi</button>
                                 <?= form_close(); ?>
                             </div>
                         <?php elseif($status == 'accepted'): ?>
                             <div class="alert alert-success mb-0"><h5 class="alert-heading">Artikel Diterima!</h5><p class="mb-2 small">Selamat! Silakan unggah naskah final dan slide presentasi.</p>
                                 <a href="<?= site_url('user/article/generate_loa/' . $registration_id . '/' . $paper->paper_id); ?>" class="btn btn-outline-success" target="_blank"><i class="fas fa-download me-2"></i>Unduh Letter of Acceptance (LoA)</a>
                                 <?= form_open_multipart('user/article/submit_final/' . $registration_id . '/' . $paper->paper_id); ?>
                                     <div class="mb-2"><label class="form-label small">Naskah Final</label><input type="file" name="file_path_final" class="form-control form-control-sm" required></div>
                                     <div class="mb-2"><label class="form-label small">Slide Presentasi</label><input type="file" name="slide_path" class="form-control form-control-sm" required></div>
                                     <button type="submit" class="btn btn-success w-100"><i class="fas fa-flag-checkered me-2"></i>Kirim Final</button>
                                 <?= form_close(); ?>
                             </div>
                        <?php elseif($status == 'final_submitted'): ?>
                             <div class="alert alert-success mb-0"><h5 class="alert-heading">Artikel Diterima!</h5><p class="mb-2 small">Selamat! Silakan unggah naskah final dan slide presentasi.</p>
                                 <a href="<?= site_url('user/article/generate_loa/' . $registration_id . '/' . $paper->paper_id); ?>" class="btn btn-outline-success" target="_blank"><i class="fas fa-download me-2"></i>Unduh Letter of Acceptance (LoA)</a>
                             </div>
                         <?php elseif($status == 'rejected'): ?>
                             <div class="alert alert-danger mb-0"><h5 class="alert-heading">Ditolak</h5><p class="mb-0 small">Artikel Anda belum dapat diterima pada kesempatan ini.</p></div>
                         <?php else: // (status 'belum_submit') ?>
                             <div class="alert alert-secondary mb-0"><p class="mb-0 small">Status akan muncul di sini setelah Anda submit artikel.</p></div>
                         <?php endif; ?>
                    </div>
                </div>
                
                <?php if($paper): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="fas fa-comments me-2"></i>Komunikasi dengan Panitia</h5></div>
                    <div class="card-body">
                        <div class="mb-3" style="height: 200px; overflow-y: auto; border: 1px solid #eee; padding: 10px;">
                            <?php if(!empty($chats)): ?>
                                <?php foreach($chats as $chat): ?>
                                    <?php if($chat->sent_by_admin): ?>
                                        <div class="text-start mb-2">
                                            <small class="fw-bold text-primary">Panitia:</small><br>
                                            <span class="d-inline-block p-2 rounded bg-light"><?= nl2br(htmlspecialchars($chat->message)); ?></span>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-end mb-2">
                                            <small class="fw-bold">Anda:</small><br>
                                            <span class="d-inline-block p-2 rounded bg-primary text-white"><?= nl2br(htmlspecialchars($chat->message)); ?></span>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-center text-muted small">Belum ada percakapan.</p>
                            <?php endif; ?>
                        </div>
                        
                        <?= form_open('user/article/post_chat_message/' . $registration_id . '/' . $paper->paper_id); ?>
                            <div class="input-group">
                                <textarea name="message" class="form-control" rows="2" placeholder="Ketik pesan..." required></textarea>
                                <button class="btn btn-primary" type="submit"><i class="fas fa-paper-plane"></i></button>
                            </div>
                        <?= form_close(); ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(!empty($reviews)): ?>
                <div class="card shadow-sm">
                    <div class="card-header"><h5 class="mb-0"><i class="fas fa-comment-dots me-2"></i>Masukan dari Reviewer</h5></div>
                    <ul class="list-group list-group-flush">
                        <?php foreach($reviews as $review): ?>
                            <li class="list-group-item">
                                <p class="small text-muted mb-0 fst-italic">"<?= nl2br(htmlspecialchars($review->saran_perbaikan, ENT_QUOTES, 'UTF-8')); ?>"</p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
            </aside>
        </div>
        </div>

    <div id="author-template" style="display: none;">
        <div class="card border p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6>Penulis Tambahan</h6>
                <button type="button" class="btn-close remove-author-btn" aria-label="Close"></button>
            </div>
            <div class="row g-2">
                <div class="col-md-6"><input type="text" name="author_firstname[]" class="form-control" placeholder="Nama Depan"></div>
                <div class="col-md-6"><input type="text" name="author_lastname[]" class="form-control" placeholder="Nama Belakang"></div>
                <div class="col-12"><input type="email" name="author_email[]" class="form-control" placeholder="Email"></div>
                <div class="col-12"><input type="text" name="author_affiliation[]" class="form-control" placeholder="Afiliasi/Institusi"></div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#add-author-btn').click(function() { $('#additional-authors-list').append($('#author-template').html()); });
        $('#additional-authors-list').on('click', '.remove-author-btn', function() { $(this).closest('.card').remove(); });
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>