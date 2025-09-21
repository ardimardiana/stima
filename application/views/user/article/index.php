<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?> - <?=$_ENV['SITE_NAME']?></title>
    <link rel="icon" href="data:image/x-icon;base64,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" type="image/x-icon" />
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
        .stepper-item:last-child::after {
            content: none;
        }
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
            
            // --- AWAL LOGIKA STEPPER BARU ---
        
            // Step 1 selesai jika status BUKAN 'belum_submit'
            $step1_class = ($status != 'belum_submit') ? 'completed' : '';
        
            // Step 2 selesai jika proses review sudah dilewati (sudah ada keputusan final)
            $step2_completed_statuses = ['accepted', 'final_submitted', 'rejected'];
            $step2_class = in_array($status, $step2_completed_statuses) ? 'completed' : '';
        
            // Step 3 selesai jika presenter sudah mengunggah semua dokumen final
            $step3_class = ($status == 'final_submitted') ? 'completed' : '';
        
            // --- AKHIR LOGIKA STEPPER BARU ---
        
            $is_submission_open = (date('Y-m-d H:i:s') <= $event->tgl_batas_submit . ' 23:59:59');
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
                                     <div class="mb-2"><input type="file" name="file_revision" class="form-control" required accept=".docx"></div>
                                     <button type="submit" class="btn btn-warning w-100"><i class="fas fa-upload me-2"></i>Kirim Revisi</button>
                                 <?= form_close(); ?>
                             </div>
                         <?php elseif($status == 'accepted'): ?>
                             <div class="alert alert-success mb-0"><h5 class="alert-heading">Artikel Diterima!</h5><p class="mb-2 small">Selamat! Silakan unggah naskah final dan slide presentasi.</p>
                                 <a href="<?= site_url('user/article/generate_loa/' . $registration_id . '/' . $paper->paper_id); ?>" class="btn btn-outline-success" target="_blank"><i class="fas fa-download me-2"></i>Unduh Letter of Acceptance (LoA)</a>
                                 <?= form_open_multipart('user/article/submit_final/' . $registration_id . '/' . $paper->paper_id); ?>
                                     <div class="mb-2"><label class="form-label small">Naskah Final (*.docx)</label><input type="file" name="file_path_final" class="form-control form-control-sm" accept=".docx" required></div>
                                     <div class="mb-2"><label class="form-label small">Slide Presentasi (*.pdf)</label><input type="file" name="slide_path" class="form-control form-control-sm" accept=".pdf" required></div>
                                     <button type="submit" class="btn btn-success w-100"><i class="fas fa-flag-checkered me-2"></i>Kirim Final</button>
                                 <?= form_close(); ?>
                             </div>
                        <?php elseif($status == 'final_submitted'): ?>
                             <div class="alert alert-success mb-0"><h5 class="alert-heading">Anda Siap Presentasi!</h5><p class="mb-2 small">Jika dibutuhkan, anda bisa mengunggah ulah dokumen anda.</p>
                                 <a href="<?= site_url('user/article/generate_loa/' . $registration_id . '/' . $paper->paper_id); ?>" class="btn btn-outline-success" target="_blank"><i class="fas fa-download me-2"></i>Unduh Letter of Acceptance (LoA)</a>
                                 <?= form_open_multipart('user/article/submit_final/' . $registration_id . '/' . $paper->paper_id); ?>
                                     <div class="mb-2"><label class="form-label small">Naskah Final (*.docx)</label><input type="file" name="file_path_final" class="form-control form-control-sm" accept=".docx" required></div>
                                     <div class="mb-2"><label class="form-label small">Slide Presentasi (*.pdf)</label><input type="file" name="slide_path" class="form-control form-control-sm" accept=".pdf" required></div>
                                     <button type="submit" class="btn btn-success w-100"><i class="fas fa-flag-checkered me-2"></i>Kirim Ulang Final</button>
                                 <?= form_close(); ?>
                             </div>
                         <?php elseif($status == 'rejected'): ?>
                             <div class="alert alert-danger mb-0"><h5 class="alert-heading">Ditolak</h5><p class="mb-0 small">Artikel Anda belum dapat diterima pada kesempatan ini.</p></div>
                              <p class="small text-muted">Merasa ada kesalahan pada judul, file, atau data penulis? Anda dapat menghapus submisi dan mengunggah ulang.</p>
                             <a href="<?= site_url('user/article/delete_submission/' . $registration_id . '/' . $paper->paper_id) ?>" 
                                class="btn btn-sm btn-outline-danger w-100" 
                                onclick="return confirm('Anda yakin ingin menghapus submisi ini? Anda harus mengulangi proses dari awal.')">
                                <i class="fas fa-trash me-1"></i> Hapus dan Ulangi Submisi
                             </a>
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
                <div class="card shadow-sm mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="fas fa-comment-dots me-2"></i>Masukan dari Reviewer</h5></div>
                    <ul class="list-group list-group-flush">
                        <?php foreach($reviews as $review): ?>
                            <li class="list-group-item">
                                <p class="">"<?= nl2br(htmlspecialchars($review->saran_perbaikan, ENT_QUOTES, 'UTF-8')); ?>"</p>
                            </li>
                            <?php  if($review->reviewed_file_path) { ?>
                            <li class="list-group-item">
                                <p class=""><a href="<?=site_url($review->reviewed_file_path)?>" target="_blank">Dokumen Revisi</a></p>
                            </li>
                            <?php } ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Check File</h5></div>
                    <div class="card-body">
                        <div class="alert alert-danger mb-2"><p class="mb-0 small">Fitur ini dibuat untuk memastikan valid/ sukses unggah file sebelum melakukan proses unggah.</p></div>
                        <?= form_open_multipart('user/article/check_valid/'); ?>
                             <div class="mb-2"><input type="file" name="check_valid" class="form-control" required accept=".docx"></div>
                             <button type="submit" class="btn btn-success w-100"><i class="fas fa-upload me-2"></i>Check File Saya</button>
                         <?= form_close(); ?>
                    </div>
                </div>
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