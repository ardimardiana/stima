<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .register-container { max-width: 600px; margin-top: 5vh; margin-bottom: 5vh; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card shadow-sm p-4 mx-auto register-container">
            <h3 class="text-center mb-4">PENDAFTARAN AKUN BARU</h3>

            <?= form_open('auth/register'); ?>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama_depan" class="form-label">Nama Depan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_depan" value="<?= set_value('nama_depan'); ?>">
                        <?= form_error('nama_depan', '<small class="text-danger">', '</small>'); ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nama_belakang" class="form-label">Nama Belakang</label>
                        <input type="text" class="form-control" name="nama_belakang" value="<?= set_value('nama_belakang'); ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="email" value="<?= set_value('email'); ?>">
                    <?= form_error('email', '<small class="text-danger">', '</small>'); ?>
                </div>
                <div class="mb-3">
                    <label for="afiliasi" class="form-label">Afiliasi / Institusi <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="afiliasi" value="<?= set_value('afiliasi'); ?>">
                    <?= form_error('afiliasi', '<small class="text-danger">', '</small>'); ?>
                </div>
                 <div class="mb-3">
                    <label for="negara" class="form-label">Negara <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="negara" value="<?= set_value('negara'); ?>">
                    <?= form_error('negara', '<small class="text-danger">', '</small>'); ?>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password">
                        <?= form_error('password', '<small class="text-danger">', '</small>'); ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="passconf" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="passconf">
                        <?= form_error('passconf', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Daftar</button>
                </div>
            <?= form_close(); ?>
            <div class="text-center mt-3">
                Sudah punya akun? <a href="<?= site_url('auth/login'); ?>" class="text-decoration-none">Login di sini</a>
            </div>
        </div>
    </div>
</body>
</html>