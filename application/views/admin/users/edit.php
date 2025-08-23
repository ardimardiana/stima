<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('admin/users') ?>">Manajemen Pengguna</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Form Edit Pengguna
        </div>
        <div class="card-body">
            <?= form_open('admin/users/edit/' . $user->user_id); ?>
                <div class="mb-3">
                    <label for="nama_depan" class="form-label">Nama Depan</label>
                    <input type="text" name="nama_depan" class="form-control" value="<?= set_value('nama_depan', $user->nama_depan); ?>">
                    <?= form_error('nama_depan', '<small class="text-danger">', '</small>'); ?>
                </div>
                 <div class="mb-3">
                    <label for="nama_belakang" class="form-label">Nama Belakang</label>
                    <input type="text" name="nama_belakang" class="form-control" value="<?= set_value('nama_belakang', $user->nama_belakang); ?>">
                </div>
                 <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= set_value('email', $user->email); ?>">
                     <?= form_error('email', '<small class="text-danger">', '</small>'); ?>
                </div>
                <div class="mb-3">
                    <label for="afiliasi" class="form-label">Afiliasi</label>
                    <input type="text" name="afiliasi" class="form-control" value="<?= set_value('afiliasi', $user->afiliasi); ?>">
                </div>
                <div class="mb-3">
                    <label for="negara" class="form-label">Negara</label>
                    <input type="text" name="negara" class="form-control" value="<?= set_value('negara', $user->negara); ?>">
                </div>
                <div class="mb-3">
                    <label for="peran_sistem" class="form-label">Peran Sistem</label>
                    <select name="peran_sistem" class="form-select">
                        <option value="user" <?= $user->peran_sistem == 'user' ? 'selected' : '' ?>>User</option>
                        <option value="admin" <?= $user->peran_sistem == 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="<?= site_url('admin/users'); ?>" class="btn btn-secondary">Batal</a>
            <?= form_close(); ?>
        </div>
    </div>
</div>