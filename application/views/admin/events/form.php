<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('admin/events') ?>">Manajemen Event</a></li>
        <li class="breadcrumb-item active"><?= $title; ?></li>
    </ol>
    
    <?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $this->session->flashdata('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    
    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-edit me-1"></i> Form Event</div>
        <div class="card-body">
            <?php 
                $is_edit = isset($event);
                $form_url = $is_edit ? site_url('admin/events/edit/' . $event->event_id) : site_url('admin/events/create');
                echo form_open_multipart($form_url);
            ?>
                <div class="mb-3">
                    <label for="nama_event" class="form-label">Nama Event</label>
                    <input type="text" name="nama_event" class="form-control" value="<?= set_value('nama_event', $is_edit ? $event->nama_event : ''); ?>">
                    <?= form_error('nama_event', '<small class="text-danger">', '</small>'); ?>
                </div>
                <div class="mb-3">
                    <label for="tagline" class="form-label">Tagline / Deskripsi Singkat</label>
                    <input type="text" name="tagline" class="form-control" value="<?= set_value('tagline', $is_edit ? $event->tagline : ''); ?>">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tahun" class="form-label">Tahun</label>
                        <input type="number" name="tahun" class="form-control" value="<?= set_value('tahun', $is_edit ? $event->tahun : date('Y')); ?>">
                        <?= form_error('tahun', '<small class="text-danger">', '</small>'); ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="slug_url" class="form-label">Slug URL</label>
                        <input type="text" name="slug_url" class="form-control" value="<?= set_value('slug_url', $is_edit ? $event->slug_url : ''); ?>" placeholder="contoh: 2025">
                        <?= form_error('slug_url', '<small class="text-danger">', '</small>'); ?>
                    </div>
                    <div class="mb-3">
                        <label for="tgl_batas_daftar" class="form-label">Batas Akhir Pendaftaran</label>
                        <input type="date" name="tgl_batas_daftar" class="form-control" value="<?= set_value('tgl_batas_daftar', $is_edit ? $event->tgl_batas_daftar : ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="tgl_batas_submit" class="form-label">Batas Akhir Submit</label>
                        <input type="date" name="tgl_batas_submit" class="form-control" value="<?= set_value('tgl_batas_submit', $is_edit ? $event->tgl_batas_submit : ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="tgl_pengumuman" class="form-label">Pengumuman</label>
                        <input type="date" name="tgl_pengumuman" class="form-control" value="<?= set_value('tgl_pengumuman', $is_edit ? $event->tgl_pengumuman : ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="tgl_mulai_acara" class="form-label">Mulai Acara</label>
                        <input type="date" name="tgl_mulai_acara" class="form-control" value="<?= set_value('tgl_mulai_acara', $is_edit ? $event->tgl_mulai_acara : ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="tgl_selesai_acara" class="form-label">Selesai Acara</label>
                        <input type="date" name="tgl_selesai_acara" class="form-control" value="<?= set_value('tgl_selesai_acara', $is_edit ? $event->tgl_selesai_acara : ''); ?>">
                    </div>
                </div>
                 <div class="mb-3">
                    <label for="info_pembayaran" class="form-label">Konten Halaman Utama Event / Info Pembayaran</label>
                    <textarea id="summernote" name="info_pembayaran" class="form-control"><?= set_value('info_pembayaran', $is_edit ? $event->info_pembayaran : ''); ?></textarea>
                </div>
                <hr>
                <h5 class="mb-3">Pengaturan Letter of Acceptance (LoA)</h5>
                <div class="mb-3">
                    <label for="header_loa" class="form-label">Header / Kop Surat LoA (Gambar)</label>
                    <?php 
                    if($event->header_loa_path)
                        echo '<img src="'.$event->header_loa_path.'">';
                    ?>
                    <input type="file" name="header_loa" class="form-control">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah header yang sudah ada.</small>
                </div>
                <div class="mb-3">
                    <label for="ketua_panitia" class="form-label">Nama Ketua Panitia (untuk TTD)</label>
                    <input type="text" name="ketua_panitia" class="form-control" value="<?= set_value('ketua_panitia', $is_edit ? $event->ketua_panitia : ''); ?>">
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= site_url('admin/events') ?>" class="btn btn-secondary">Batal</a>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 300, // Atur tinggi editor
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>