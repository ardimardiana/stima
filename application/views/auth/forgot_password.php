<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> .container { max-width: 450px; margin-top: 10vh; } </style>
</head>
<body>
    <div class="container">
        <div class="card shadow-sm p-4">
            <h3 class="text-center mb-3">Lupa Password</h3>
            <p class="text-muted text-center mb-4">Masukkan email Anda untuk menerima link reset password.</p>
            
            <?php if($this->session->flashdata('success')): ?>
                <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
             <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
            <?php endif; ?>

            <?= form_open('auth/forgot_password'); ?>
                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" class="form-control" name="email" required>
                    <?= form_error('email', '<small class="text-danger">', '</small>'); ?>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Kirim Link Reset</button>
                </div>
            <?= form_close(); ?>
            <div class="text-center mt-3">
                <a href="<?= site_url('auth/login'); ?>" class="text-decoration-none">Kembali ke Login</a>
            </div>
        </div>
    </div>
</body>
</html>