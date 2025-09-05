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
            <a class="nav-link <?= $status_filter == 'in_review' ? 'active' : '' ?>" href="<?= site_url('admin/articles/index/'.$event->event_id.'/in_review') ?>">Proses Review</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $status_filter == 'revision' ? 'active' : '' ?>" href="<?= site_url('admin/articles/index/'.$event->event_id.'/revision') ?>">Revisi</a>
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
    </ul>

    <div class="card shadow-sm">
        <div class="card-header"><i class="fas fa-file-alt me-2"></i>Daftar Artikel Masuk
            <a href="<?= site_url('admin/articles/export_articles_excel/' . $event->event_id) ?>" class="btn btn-sm btn-success float-end"><i class="fas fa-file-excel me-1"></i> Export ke Excel</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Topik</th>
                            <th>Judul Artikel</th>
                            <th>Penulis Utama</th>
                            <th>Status</th>
                            <th>Dokumen</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($articles as $article): ?>
                        <tr>
                            <td><?= htmlspecialchars($article->nama_topik, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= htmlspecialchars($article->judul); ?></td>
                            <td><?= htmlspecialchars($article->nama_depan . ' ' . $article->nama_belakang); ?></td>
                            <td><?= htmlspecialchars($article->status_artikel); ?></td>
                            <td>
                                <?php if($article->file_path_initial): ?>
                                    <a href="<?= base_url($article->file_path_initial) ?>" class="d-block mb-1" target="_blank"><small>Naskah Awal/Revisi</small></a>
                                <?php endif; ?>
                                <?php if($article->file_path_final): ?>
                                    <a href="<?= base_url($article->file_path_final) ?>" class="d-block mb-1" target="_blank"><small>Naskah Final</small></a>
                                <?php endif; ?>
                                <?php if($article->slide_path): ?>
                                    <a href="<?= base_url($article->slide_path) ?>" class="d-block" target="_blank"><small>Slide PPT</small></a>
                                <?php endif; ?>
                            </td>
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