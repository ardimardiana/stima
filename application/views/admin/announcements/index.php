<div class="container-fluid px-4">
    <h1 class="mt-4">Pengumuman untuk: <?= htmlspecialchars($event->nama_event, ENT_QUOTES, 'UTF-8'); ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('admin/events') ?>">Manajemen Event</a></li>
        <li class="breadcrumb-item active">Pengumuman</li>
    </ol>
    
    <?php $this->load->view('admin/event_manager/_nav'); ?>
    
    <a href="<?= site_url('admin/announcements/create/' . $event->event_id) ?>" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Buat Pengumuman Baru</a>
    
    <?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $this->session->flashdata('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-bullhorn me-1"></i> Daftar Pengumuman</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Tanggal Publish</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($announcements as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item->judul, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= date('d F Y H:i', strtotime($item->tgl_publish)); ?></td>
                            <td>
                                <a href="<?= site_url('admin/announcements/edit/' . $item->pengumuman_id) ?>" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="<?= site_url('admin/announcements/delete/' . $item->pengumuman_id) ?>" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus pengumuman ini?');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>