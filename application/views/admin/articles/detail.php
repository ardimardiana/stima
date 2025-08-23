<div class="container-fluid px-4">
    <h1 class="mt-4">Detail Artikel</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/event_manager/index/'.$event->event_id) ?>">Dasbor Event</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('admin/articles/index/'.$event->event_id) ?>">Manajemen Artikel</a></li>
        <li class="breadcrumb-item active"><?= htmlspecialchars($paper->judul); ?></li>
    </ol>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white"><h5 class="mb-0">Ringkasan Artikel</h5></div>
                <div class="card-body">
                    <?php 
                        $status = $paper->status_artikel;
                        $badge_class = 'bg-secondary';
                        $status_text = 'Unknown';
            
                        switch ($status) {
                            case 'submitted':
                                $badge_class = 'bg-info text-dark';
                                $status_text = 'Submitted';
                                break;
                            case 'in_review':
                                $badge_class = 'bg-primary';
                                $status_text = 'In Review';
                                break;
                            case 'revision':
                                $badge_class = 'bg-warning text-dark';
                                $status_text = 'Revision Needed';
                                break;
                            case 'revision_submitted':
                                $badge_class = 'bg-warning text-dark';
                                $status_text = 'Revision Submitted';
                                break;
                            case 'accepted':
                                $badge_class = 'bg-success';
                                $status_text = 'Accepted';
                                break;
                            case 'final_submitted':
                                $badge_class = 'bg-success';
                                $status_text = 'Camera Ready';
                                break;
                            case 'rejected':
                                $badge_class = 'bg-danger';
                                $status_text = 'Rejected';
                                break;
                        }
                    ?>
                    
                    <h4><?= htmlspecialchars($paper->judul); ?></h4>
                    <h5><span class="badge <?= $badge_class; ?>"><?= $status_text; ?></span></h5>
                    <span class="badge bg-secondary"><?= htmlspecialchars($paper->nama_topik); ?></span>
                    <hr>
                    <h6>Abstrak</h6>
                    <p><?= nl2br(htmlspecialchars($paper->abstrak)); ?></p>
                    <hr>
                    <h6>Penulis</h6>
                    <ul class="list-group list-group-flush">
                        <?php foreach($authors as $author): ?>
                        <li class="list-group-item"><?= htmlspecialchars($author->nama_depan . ' ' . $author->nama_belakang); ?> (<?= htmlspecialchars($author->afiliasi); ?>)</li>
                        <?php endforeach; ?>
                    </ul>
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
            <?php if(($status == 'rejected' || $status != 'final_submitted')): ?>
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white"><h5 class="mb-0">Keputusan Final</h5></div>
                <div class="card-body text-center">
                    <p>Ubah status artikel ini berdasarkan hasil review. Peserta akan menerima notifikasi email otomatis.</p>
                    
                    <?= form_open('admin/articles/update_status/' . $paper->paper_id); ?>
                        <button type="submit" name="status" value="accepted" class="btn btn-success" 
                                onclick="return confirm('Anda yakin ingin MENYETUJUI artikel ini?')">
                            <i class="fas fa-check"></i> Setujui (Accept)
                        </button>
                        
                        <button type="submit" name="status" value="revision" class="btn btn-warning"
                                onclick="return confirm('Anda yakin ingin MEMINTA REVISI untuk artikel ini?')">
                            <i class="fas fa-edit"></i> Minta Revisi
                        </button>
                        
                        <button type="submit" name="status" value="rejected" class="btn btn-danger"
                                onclick="return confirm('Anda yakin ingin MENOLAK artikel ini?')">
                            <i class="fas fa-times"></i> Tolak (Reject)
                        </button>
                    <?= form_close(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        
        <aside class="col-lg-4">
            <?php if(($status == 'rejected' || $status != 'final_submitted')): ?>
            <div class="card shadow-sm mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Tugaskan Reviewer</h5></div>
                <div class="card-body">
                    <?= form_open_multipart('admin/articles/assign_reviewer/' . $paper->paper_id); ?>
                        <div class="mb-2">
                            <label class="form-label">Nama Reviewer</label>
                            <input type="text" name="reviewer_name" class="form-control" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Email Reviewer</label>
                            <input type="email" name="reviewer_email" class="form-control" placeholder="Email Aktif" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Unggah File Anonim (.docx/.pdf)</label>
                            <input type="file" name="anonymized_file" class="form-control" required>
                            <small class="text-muted">File ini yang akan dilihat oleh reviewer (tanpa identitas penulis).</small>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-paper-plane me-2"></i>Kirim Undangan Review</button>
                    <?= form_close(); ?>
                </div>
            </div>
            <?php endif; ?>
            <div class="card shadow-sm mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fas fa-users-cog me-2"></i>Reviewer Ditugaskan</h5></div>
                <ul class="list-group list-group-flush">
                    <?php if(empty($reviews)): ?>
                        <li class="list-group-item">Belum ada reviewer yang ditugaskan.</li>
                    <?php else: ?>
                        <?php foreach($reviews as $review): ?>
                            <li class="list-group-item">
                                <strong><?= htmlspecialchars($review->reviewer_name); ?></strong>
                                <br><small class="text-muted"><?= $review->reviewer_email; ?></small>
                                <span class="badge bg-info float-end"><?= ucfirst($review->status_review); ?></span>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
            
             <div class="card shadow-sm mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Hasil Review</h5></div>
                <div class="card-body">
                    <?php 
                        // Cek apakah ada review yang sudah disubmit
                        $submitted_reviews = array_filter($reviews, function($r) {
                            return $r->status_review == 'submitted';
                        });
                    ?>
        
                    <?php if(empty($submitted_reviews)): ?>
                        <p class="text-center text-muted">Belum ada hasil review yang masuk.</p>
                    <?php else: ?>
                        <div class="accordion" id="reviewAccordion">
                            <?php foreach($reviews as $index => $review): ?>
                                <?php if($review->status_review == 'submitted'): ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading-<?= $index; ?>">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?= $index; ?>" aria-expanded="false" aria-controls="collapse-<?= $index; ?>">
                                                <strong>Review dari: <?= htmlspecialchars($review->reviewer_name); ?></strong>
                                            </button>
                                        </h2>
                                        <div id="collapse-<?= $index; ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?= $index; ?>" data-bs-parent="#reviewAccordion">
                                            <div class="accordion-body">
                                                <ul class="list-group list-group-flush">
                                                    <?php 
                                                        // Mapping untuk menerjemahkan nilai angka ke teks
                                                        $relevansi_map = [1=>'Tidak Relevan', 2=>'Kurang', 3=>'Cukup', 4=>'Sangat Relevan'];
                                                        $konten_map = [1=>'Tidak Diterima', 2=>'Rendah', 3=>'Sedang', 4=>'Tinggi'];
                                                        $orisinal_map = [1=>'Sudah Dikerjakan', 2=>'Pengembangan Minor', 3=>'Ada Aspek Orisinal', 4=>'Sangat Orisinal'];
                                                        $penulisan_map = [1=>'Sangat Sulit Dipahami', 2=>'Perlu Perbaikan', 3=>'Cukup Jelas', 4=>'Sangat Jelas'];
                                                    ?>
                                                    <li class="list-group-item d-flex justify-content-between"><span>Relevansi:</span> <span class="fw-bold"><?= $relevansi_map[$review->relevansi] ?? '-'; ?></span></li>
                                                    <li class="list-group-item d-flex justify-content-between"><span>Kualitas Konten:</span> <span class="fw-bold"><?= $konten_map[$review->kualitas_konten] ?? '-'; ?></span></li>
                                                    <li class="list-group-item d-flex justify-content-between"><span>Orisinalitas:</span> <span class="fw-bold"><?= $orisinal_map[$review->orisinalitas] ?? '-'; ?></span></li>
                                                    <li class="list-group-item d-flex justify-content-between"><span>Gaya Penulisan:</span> <span class="fw-bold"><?= $penulisan_map[$review->gaya_penulisan] ?? '-'; ?></span></li>
                                                    <li class="list-group-item d-flex justify-content-between"><span>Rekomendasi Final:</span> <span class="badge bg-info text-dark"><?= ucfirst(str_replace('_', ' ', $review->rekomendasi)); ?></span></li>
                                                    <li class="list-group-item d-flex justify-content-between"><span>Rekomendasi Best Paper:</span> <span class="fw-bold"><?= ($review->rekomendasi_best_paper == 1) ? 'Ya' : 'Tidak'; ?></span></li>
                                                </ul>
                                                <hr>
                                                <strong>Saran Perbaikan (untuk Penulis):</strong>
                                                <blockquote class="blockquote-footer mt-1 bg-light p-2 rounded"><?= nl2br(htmlspecialchars($review->saran_perbaikan)); ?></blockquote>
                                                
                                                <strong>Catatan (untuk Panitia):</strong>
                                                <blockquote class="blockquote-footer mt-1 bg-warning p-2 rounded"><?= nl2br(htmlspecialchars($review->catatan_untuk_panitia)); ?></blockquote>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="fas fa-comments me-2"></i>Komunikasi dengan Presenter</h5></div>
                <div class="card-body">
                    <div class="mb-3" style="height: 250px; overflow-y: auto; border: 1px solid #eee; padding: 10px; display: flex; flex-direction: column-reverse;">
                        <div class="d-flex flex-column">
                        <?php if(!empty($chats)): ?>
                            <?php foreach($chats as $chat): ?>
                                <?php if($chat->sent_by_admin): ?>
                                    <div class="align-self-start mb-2">
                                        <small class="fw-bold text-primary">Anda (Panitia):</small><br>
                                        <span class="d-inline-block p-2 rounded bg-primary text-white"><?= nl2br(htmlspecialchars($chat->message)); ?></span>
                                    </div>
                                <?php else: ?>
                                    <div class="align-self-end text-end mb-2">
                                        <small class="fw-bold">Presenter:</small><br>
                                        <span class="d-inline-block p-2 rounded bg-light"><?= nl2br(htmlspecialchars($chat->message)); ?></span>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-center text-muted small">Belum ada percakapan.</p>
                        <?php endif; ?>
                        </div>
                    </div>
                    
                    <?= form_open('admin/articles/post_admin_chat/' . $paper->paper_id); ?>
                        <div class="input-group">
                            <textarea name="message" class="form-control" rows="2" placeholder="Ketik balasan..." required></textarea>
                            <button class="btn btn-primary" type="submit" title="Kirim Pesan"><i class="fas fa-paper-plane"></i></button>
                        </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </aside>
    </div>
</div>