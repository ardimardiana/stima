<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Ruangan: <?= htmlspecialchars($event->nama_event, ENT_QUOTES, 'UTF-8'); ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/event_manager/index/'.$event->event_id) ?>">Dasbor Event</a></li>
        <li class="breadcrumb-item active">Manajemen Ruangan</li>
    </ol>
    
    <?php $this->load->view('admin/event_manager/_nav'); ?>
    
    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header"><h5 class="mb-0"><i class="fas fa-plus me-2"></i>Tambah Ruangan Baru</h5></div>
                <div class="card-body">
                    <?= form_open('admin/rooms/index/' . $event->event_id); ?>
                        <div class="mb-3">
                            <label for="nama_ruang" class="form-label">Nama Ruang</label>
                            <input type="text" name="nama_ruang" class="form-control" placeholder="Contoh: Ruang Seminar A" required>
                        </div>
                        <div class="mb-3">
                            <label for="lokasi" class="form-label">Lokasi / Tautan</label>
                            <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Gedung B Lt. 2 atau Link Zoom">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Ruangan</button>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header"><h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Ruangan Tersedia</h5></div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php foreach($rooms as $room): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?= htmlspecialchars($room->nama_ruang, ENT_QUOTES, 'UTF-8'); ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($room->lokasi, ENT_QUOTES, 'UTF-8'); ?></small>
                                </div>
                                <div>
                                    <a href="<?= site_url('admin/rooms/edit/' . $room->room_id); ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <a href="<?= site_url('admin/rooms/delete/' . $room->room_id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus ruangan ini?');"><i class="fas fa-trash"></i></a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>