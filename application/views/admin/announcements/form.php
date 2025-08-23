<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('admin/events') ?>">Manajemen Event</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('admin/announcements/event/' . $event->event_id) ?>">Pengumuman</a></li>
        <li class="breadcrumb-item active"><?= $title; ?></li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-edit me-1"></i> Form Pengumuman</div>
        <div class="card-body">
            <?php 
                $is_edit = isset($announcement);
                $form_url = $is_edit ? site_url('admin/announcements/edit/' . $announcement->pengumuman_id) : site_url('admin/announcements/create/' . $event->event_id);
                echo form_open($form_url);
            ?>
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Pengumuman</label>
                    <input type="text" name="judul" class="form-control" value="<?= set_value('judul', $is_edit ? $announcement->judul : ''); ?>">
                    <?= form_error('judul', '<small class="text-danger">', '</small>'); ?>
                </div>
                 <div class="mb-3">
                    <label for="isi" class="form-label">Isi Pengumuman</label>
                    <textarea name="isi" class="form-control" rows="5"><?= set_value('isi', $is_edit ? $announcement->isi : ''); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= site_url('admin/announcements/event/' . $event->event_id) ?>" class="btn btn-secondary">Batal</a>
            <?= form_close(); ?>
        </div>
    </div>
</div>