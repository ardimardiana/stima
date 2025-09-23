<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">Manajemen Pengguna</li>
    </ol>
    
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Daftar Pengguna Terdaftar
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Afiliasi</th>
                            <th>Peran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach($users as $user): ?>
                        <tr>
                            <td><?= $user->user_id; ?></td>
                            <td><?= htmlspecialchars($user->nama_depan . ' ' . $user->nama_belakang, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= htmlspecialchars($user->afiliasi, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <span class="badge <?= $user->peran_sistem == 'admin' ? 'bg-success' : 'bg-secondary'; ?>">
                                    <?= ucfirst($user->peran_sistem); ?>
                                </span>
                            </td>
                            <td>
                                 <a href="<?= site_url('admin/users/register_to_event/' . $user->user_id) ?>" class="btn btn-sm btn-info mb-2" title="Daftarkan ke Event">
                                    <i class="fas fa-calendar-plus"></i>
                                </a>
                                <a href="<?= site_url('admin/users/edit/' . $user->user_id) ?>" class="btn btn-sm btn-warning mb-2">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="<?= site_url('admin/users/delete/' . $user->user_id) ?>" class="btn btn-sm btn-danger mb-2" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>