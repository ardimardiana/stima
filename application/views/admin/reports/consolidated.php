<div class="container-fluid px-4">
    <h1 class="mt-4">Laporan Konsolidasi: <?= htmlspecialchars($event->nama_event, ENT_QUOTES, 'UTF-8'); ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/events') ?>">Manajemen Event</a></li>
        <li class="breadcrumb-item active">Laporan Konsolidasi</li>
    </ol>

    <?php $this->load->view('admin/event_manager/_nav'); ?>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-chart-pie me-1"></i>
            Kesimpulan Pendaftar
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h4 class="card-title"><?= $summary['peserta_saja']; ?></h4>
                            <p class="card-text">Hanya sebagai Peserta</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h4 class="card-title"><?= $summary['presenter_saja']; ?></h4>
                            <p class="card-text">Hanya sebagai Presenter</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h4 class="card-title"><?= $summary['keduanya']; ?></h4>
                            <p class="card-text">Peserta & Presenter</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-friends me-1"></i>
            Ringkasan Pendaftar Unik
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th>Nama Lengkap</th>
                            <th>Afiliasi</th>
                            <th>Sebagai Peserta</th>
                            <th>Sebagai Presenter</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($consolidated_participants as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['nama_lengkap']); ?> (<?= $user['user_id']; ?>)</td>
                            <td><?= htmlspecialchars($user['afiliasi']); ?></td>
                            <td class="text-center">
                                <?php if (isset($user['roles']['peserta'])): ?>
                                    <?php 
                                        $is_lunas = ($user['roles']['peserta'] == 'lunas');
                                        $badge_class = $is_lunas ? 'bg-success' : 'bg-danger';
                                    ?>
                                    <span class="badge <?= $badge_class; ?>">Ya</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Tidak</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if (isset($user['roles']['presenter'])): ?>
                                    <?php 
                                        $is_lunas = ($user['roles']['presenter'] == 'lunas');
                                        $badge_class = $is_lunas ? 'bg-success' : 'bg-danger';
                                    ?>
                                    <span class="badge <?= $badge_class; ?>">Ya</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Tidak</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</div>