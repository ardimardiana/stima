<div class="container-fluid px-4">
    <h1 class="mt-4">Daftar Artikel untuk Dimoderasi</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/event_manager/index/'.$event->event_id) ?>">Dasbor Event</a></li>
        <li class="breadcrumb-item active">Moderator</li>
    </ol>
    <?php $this->load->view('admin/event_manager/_nav'); ?>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i> Artikel pada Sesi Anda
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered" id="datatablesSimple">
                <thead>
                    <tr>
                        <th>Waktu & Ruang</th>
                        <th>Sesi</th>
                        <th>Judul Artikel</th>
                        <th>Penulis</th>
                        <th>Topik</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($papers)): ?>
                        <?php foreach ($papers as $paper): ?>
                        <tr>
                            <td>
                                <strong><?= date('H:i', strtotime($paper->waktu_mulai)); ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($paper->nama_ruang); ?></small>
                            </td>
                            <td><strong><?= htmlspecialchars($paper->nama_sesi); ?></strong></td>
                            <td><?= htmlspecialchars($paper->judul); ?></td>
                            <td><?= ucfirst($paper->author_firstname) . ' ' . ucfirst($paper->author_lastname); ?></td>
                            <td><?= htmlspecialchars($paper->nama_topik); ?></td>
                            <td>
                                <span class="badge bg-primary"><?= ucfirst(str_replace('_', ' ', $paper->status_artikel)); ?></span>
                            </td>
                            <td>
                                <a href="<?= site_url('admin/articles/detail/'.$paper->paper_id); ?>" class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i> Detail</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada kelas paralel yang ditugaskan kepada Anda pada kegiatan event ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>