<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/users') ?>">Manajemen Pengguna</a></li>
        <li class="breadcrumb-item active">Daftarkan ke Event</li>
    </ol>
    
    <div class="card shadow-sm">
        <div class="card-header">
            Form Pendaftaran Manual untuk: <strong><?= $user_to_register->nama_depan . ' ' . $user_to_register->nama_belakang; ?></strong>
        </div>
        <div class="card-body">
            <?= form_open('admin/users/process_manual_registration'); ?>
                <input type="hidden" name="user_id" value="<?= $user_to_register->user_id; ?>">
                
                <div class="mb-3">
                    <label for="event_id" class="form-label">Pilih Event</label>
                    <select name="event_id" class="form-select" required>
                        <option value="">-- Pilih Event --</option>
                        <?php foreach($events as $event): ?>
                            <option value="<?= $event->event_id; ?>"><?= $event->nama_event; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="peran_event" class="form-label">Daftarkan Sebagai</label>
                    <select name="peran_event" class="form-select" required>
                        <option value="peserta">Peserta</option>
                        <option value="presenter">Presenter</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="status_pembayaran" class="form-label">Status Awal Pembayaran</label>
                    <select name="status_pembayaran" class="form-select" required>
                        <option value="menunggu">Menunggu Pembayaran</option>
                        <option value="lunas">Lunas (misal: bayar tunai)</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Daftarkan Pengguna</button>
                <a href="<?= site_url('admin/users'); ?>" class="btn btn-secondary">Batal</a>
            <?= form_close(); ?>
        </div>
    </div>
</div>