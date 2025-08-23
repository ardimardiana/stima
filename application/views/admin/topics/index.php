<div class="container-fluid px-4">
    <h1 class="mt-4">Topik Makalah: <?= htmlspecialchars($event->nama_event, ENT_QUOTES, 'UTF-8'); ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/event_manager/index/'.$event->event_id) ?>">Dasbor Event</a></li>
        <li class="breadcrumb-item active">Pengaturan Topik</li>
    </ol>
    
    <?php $this->load->view('admin/event_manager/_nav'); ?>
    
    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header"><h5 class="mb-0">Tambah Topik Baru</h5></div>
                <div class="card-body">
                    <?= form_open('admin/topics/index/' . $event->event_id); ?>
                        <div class="mb-3">
                            <label for="nama_topik" class="form-label">Nama Topik</label>
                            <input type="text" name="nama_topik" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Topik</button>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header"><h5 class="mb-0">Daftar Topik Tersedia</h5></div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php foreach($topics as $topic): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= htmlspecialchars($topic->nama_topik, ENT_QUOTES, 'UTF-8'); ?>
                                <a href="<?= site_url('admin/topics/delete/' . $topic->topic_id); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus topik ini?');"><i class="fas fa-trash"></i></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>