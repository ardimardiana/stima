<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Conference Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .login-container { max-width: 400px; margin-top: 10vh; }
        .captcha-img { border-radius: .25rem; border: 1px solid #dee2e6; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card shadow-sm p-4 mx-auto login-container">
            <h3 class="text-center mb-4">LOGIN SISTEM</h3>
            
            <?php if($this->session->flashdata('success')): ?>
                <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
            <?php endif; ?>

            <?= form_open('auth/login'); ?>
                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="<?= set_value('email'); ?>" required>
                    <?= form_error('email', '<small class="text-danger">', '</small>'); ?>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                    <?= form_error('password', '<small class="text-danger">', '</small>'); ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Captcha</label>
                    <div class="d-flex align-items-center">
                        <span class="me-3 captcha-img"><?= $captcha_image; ?></span>
                        <input type="text" class="form-control" name="captcha" placeholder="Masukkan 4 digit" required>
                    </div>
                    <?= form_error('captcha', '<small class="text-danger">', '</small>'); ?>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            <?= form_close(); ?>

            <hr>
            <div class="text-center">
                <a href="<?= site_url('auth/forgot_password'); ?>" class="text-decoration-none">Lupa Password?</a>
                <br>
                Belum punya akun? <a href="<?= site_url('auth/register'); ?>" class="text-decoration-none">Daftar di sini</a>
            </div>
        </div>
    </div>
</body>
</html>