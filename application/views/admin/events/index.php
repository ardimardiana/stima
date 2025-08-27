<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">Manajemen Event</li>
    </ol>
    
    <a href="<?= site_url('admin/events/create') ?>" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Buat Event Baru</a>
    
    <?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $this->session->flashdata('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-table me-1"></i> Daftar Event</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nama Event</th>
                            <th>Tahun</th>
                            <th>Status</th>
                            <th>URL</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($events as $event): ?>
                        <tr>
                            <td><a href="<?= site_url('admin/event_manager/index/' . $event->event_id) ?>" class="btn btn-sm btn-primary" title="Kelola Event"><?= htmlspecialchars($event->nama_event, ENT_QUOTES, 'UTF-8'); ?></a></td>
                            <td><?= $event->tahun; ?></td>
                            <td>
                                <?php if($event->status == 'aktif'): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php elseif($event->status == 'draft'): ?>
                                    <span class="badge bg-secondary">Draft</span>
                                <?php else: ?>
                                    <span class="badge bg-dark">Arsip</span>
                                <?php endif; ?>
                            </td>
                            <td>/<?= $event->slug_url; ?></td>
                            <td>
                                <a href="<?= site_url('admin/event_manager/index/' . $event->event_id) ?>" class="btn btn-sm btn-primary" title="Kelola Event"><i class="fas fa-cog"></i> Kelola</a>
                                
                                <a href="<?= site_url('admin/events/edit/' . $event->event_id) ?>" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="#" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus event ini?');"><i class="fas fa-trash"></i></a>
                                
                                <?php if($event->status != 'aktif'): ?>
                                <a href="<?= site_url('admin/events/set_active/' . $event->event_id) ?>" class="btn btn-sm btn-info" title="Jadikan Aktif"><i class="fas fa-check"></i> Aktifkan</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>