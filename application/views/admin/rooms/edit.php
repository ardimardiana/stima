<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Ruangan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/event_manager/index/'.$event->event_id) ?>">Dasbor Event</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('admin/rooms/index/'.$event->event_id) ?>">Manajemen Ruangan</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
    
    <div class="card shadow-sm">
        <div class="card-header"><h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Data Ruangan</h5></div>
        <div class="card-body">
            <?= form_open('admin/rooms/update/' . $room->room_id); ?>
                <div class="mb-3">
                    <label for="nama_ruang" class="form-label">Nama Ruang</label>
                    <input type="text" name="nama_ruang" class="form-control" value="<?= htmlspecialchars($room->nama_ruang, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="lokasi" class="form-label">Lokasi / Tautan</label>
                    <input type="text" name="lokasi" class="form-control" value="<?= htmlspecialchars($room->lokasi, ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <button type="submit" class="btn btn-primary">Perbarui Ruangan</button>
                <a href="<?= site_url('admin/rooms/index/' . $room->event_id); ?>" class="btn btn-secondary">Batal</a>
            <?= form_close(); ?>
        </div>
    </div>
</div>