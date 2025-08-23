<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Ringkasan Sistem</li>
    </ol>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <div>
                            <div class="fs-3 fw-bold"><?= $total_users; ?></div>
                            <div>Total Pengguna</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= site_url('admin/users'); ?>">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                     <div class="d-flex justify-content-between">
                        <div>
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                        <div>
                            <div class="fs-3 fw-bold"><?= $total_events; ?></div>
                            <div>Total Event</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="#">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                     <div class="d-flex justify-content-between">
                        <div>
                            <i class="fas fa-user-check fa-2x"></i>
                        </div>
                        <div>
                            <div class="fs-3 fw-bold"><?= $total_participants; ?></div>
                            <div>Pendaftar (Event Aktif)</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="#">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                     <div class="d-flex justify-content-between">
                        <div>
                            <i class="fas fa-file-alt fa-2x"></i>
                        </div>
                        <div>
                            <div class="fs-3 fw-bold"><?= $total_papers; ?></div>
                            <div>Makalah Masuk (Event Aktif)</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="#">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-chart-area me-1"></i>
            Aktivitas Pendaftaran
        </div>
        <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
    </div>
</div>