<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= site_url('user/dashboard'); ?>">Dasbor Peserta</a>
            <ul class="navbar-nav ms-auto"><li class="nav-item"><a class="nav-link" href="<?= site_url('auth/logout'); ?>">Logout</a></li></ul>
        </div>
    </nav>
    
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="mb-4">Edit Profil Saya</h2>

                <?php if($this->session->flashdata('success')): ?><div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div><?php endif; ?>
                <?php if($this->session->flashdata('error')): ?><div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div><?php endif; ?>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <?= form_open_multipart('user/profile/update'); ?>
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <img src="<?= base_url($user->photo_path ?: 'assets/img/default_avatar.png'); ?>" class="img-thumbnail rounded-circle mb-3" alt="Foto Profil" style="width: 150px; height: 150px; object-fit: cover;">
                                    <div class="mb-3">
                                        <label for="photo" class="form-label small">Ubah Foto Profil</label>
                                        <input class="form-control form-control-sm" type="file" name="photo">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nama_depan" class="form-label">Nama Depan</label>
                                            <input type="text" name="nama_depan" class="form-control" value="<?= $user->nama_depan; ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="nama_belakang" class="form-label">Nama Belakang</label>
                                            <input type="text" name="nama_belakang" class="form-control" value="<?= $user->nama_belakang; ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" value="<?= $user->email; ?>" disabled readonly>
                                        <small class="text-muted">Email tidak dapat diubah.</small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="afiliasi" class="form-label">Afiliasi / Institusi</label>
                                        <input type="text" name="afiliasi" class="form-control" value="<?= $user->afiliasi; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="negara" class="form-label">Negara</label>
                                        <input type="text" name="negara" class="form-control" value="<?= $user->negara; ?>" required>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="text-end">
                                <a href="<?= site_url('user/dashboard'); ?>" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        <?= form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>