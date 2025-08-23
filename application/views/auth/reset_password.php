<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> .container { max-width: 450px; margin-top: 10vh; } </style>
</head>
<body>
    <div class="container">
        <div class="card shadow-sm p-4">
            <h3 class="text-center mb-4">Masukkan Password Baru</h3>

            <?= form_open('auth/update_password'); ?>
                <input type="hidden" name="token" value="<?= $token; ?>">
                <div class="mb-3">
                    <label for="password" class="form-label">Password Baru</label>
                    <input type="password" class="form-control" name="password" required>
                     <?= form_error('password', '<small class="text-danger">', '</small>'); ?>
                </div>
                 <div class="mb-3">
                    <label for="passconf" class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" name="passconf" required>
                     <?= form_error('passconf', '<small class="text-danger">', '</small>'); ?>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Ubah Password</button>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</body>
</html>