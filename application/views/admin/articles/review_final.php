<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Artikel: <?= htmlspecialchars($event->nama_event, ENT_QUOTES, 'UTF-8'); ?></h1>
    <ol class="breadcrumb mb-4">
        </ol>
    
    <?php $this->load->view('admin/event_manager/_nav'); ?>
    
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link <?= empty($status_filter) ? 'active' : '' ?>" href="<?= site_url('admin/articles/index/'.$event->event_id) ?>">Semua</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $status_filter == 'submitted' ? 'active' : '' ?>" href="<?= site_url('admin/articles/index/'.$event->event_id.'/submitted') ?>">Submit</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $status_filter == 'in_review' ? 'active' : '' ?>" href="<?= site_url('admin/articles/review/'.$event->event_id.'/in_review') ?>">Proses Review</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $status_filter == 'revision' ? 'active' : '' ?>" href="<?= site_url('admin/articles/index/'.$event->event_id.'/revision') ?>">Proses Revisi</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $status_filter == 'revision_submitted' ? 'active' : '' ?>" href="<?= site_url('admin/articles/index/'.$event->event_id.'/revision_submitted') ?>">Revisi Masuk</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $status_filter == 'accepted' ? 'active' : '' ?>" href="<?= site_url('admin/articles/index/'.$event->event_id.'/accepted') ?>">Accepted</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $status_filter == 'final_submitted' ? 'active' : '' ?>" href="<?= site_url('admin/articles/index/'.$event->event_id.'/final_submitted') ?>">Camera Ready</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $status_filter == 'review_final' ? 'active' : '' ?>" href="<?= site_url('admin/articles/review_final/'.$event->event_id.'/review_final') ?>">Rekap Review</a>
        </li>
    </ul>

    <div class="card shadow-sm">
        <div class="card-header"><i class="fas fa-file-alt me-2"></i>Daftar Artikel <?=ucfirst($status_filter)?> : <?=count($articles)?></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Judul Artikel</th>
                            <th>Penulis Utama</th>
                            <th>Reviewer</th>
                            <th>Status</th>
                            <th>relevansi</th>
                            <th>kualitas_konten</th>
                            <th>orisinalitas</th>
                            <th>gaya_penulisan</th>
                            <th>rekomendasi</th>
                            <th>rekomendasi_best_paper</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($articles as $article): ?>
                        <tr>
                            <td><?= htmlspecialchars(substr($article->judul,0,20)); ?></td>
                            <td><?= htmlspecialchars($article->nama_depan . ' ' . $article->nama_belakang); ?></td>
                            <td><?= htmlspecialchars($article->reviewer_name); ?></td>
                            <td><?= htmlspecialchars($article->status_review); ?></td>
                            <td><?= htmlspecialchars($article->relevansi); ?></td>
                            <td><?= htmlspecialchars($article->kualitas_konten); ?></td>
                            <td><?= htmlspecialchars($article->orisinalitas); ?></td>
                            <td><?= htmlspecialchars($article->gaya_penulisan); ?></td>
                            <td><?= htmlspecialchars($article->rekomendasi); ?></td>
                            <td><?= htmlspecialchars($article->rekomendasi_best_paper); ?></td>
                            <td>
                                <a href="<?= site_url('admin/articles/detail/' . $article->paper_id) ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-search me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>