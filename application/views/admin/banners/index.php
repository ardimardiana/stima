<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Banner: <?= $event->nama_event; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/event_manager/index/'.$event->event_id) ?>">Dasbor Event</a></li>
        <li class="breadcrumb-item active">Manajemen Banner</li>
    </ol>
    <?php $this->load->view('admin/event_manager/_nav'); ?>
    
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header"><h5 class="mb-0">Unggah Banner Baru</h5></div>
                <div class="card-body">
                    <?= form_open_multipart('admin/banners/upload/' . $event->event_id); ?>
                        <div class="mb-3">
                            <label for="banner_image" class="form-label">Pilih file gambar (maks 2MB)</label>
                            <input type="file" name="banner_image" class="form-control" required accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Unggah</button>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-sm">
                 <div class="card-header"><h5 class="mb-0">Banner Terpasang</h5></div>
                 <div class="card-body">
                    <div class="row">
                        <?php foreach($banners as $banner): ?>
                        <div class="col-md-6 mb-3">
                            <img src="<?= base_url($banner->image_path); ?>" class="img-fluid rounded mb-2">
                            <a href="<?= site_url('admin/banners/delete/' . $banner->banner_id); ?>" class="btn btn-danger btn-sm w-100" onclick="return confirm('Yakin?')">Hapus</a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</div>