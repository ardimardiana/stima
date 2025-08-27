<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading"><?=$_ENV['SITE_NAME']?></div>
                <a class="nav-link" href="<?= site_url('admin/dashboard'); ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <div class="sb-sidenav-menu-heading">Event Aktif</div>
                <a class="nav-link" href="<?= site_url('admin/dashboard/atur/event_manager'); ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-gear"></i></div>
                    Kelola
                </a>
                <a class="nav-link" href="<?= site_url('admin/dashboard/atur/reports'); ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Pendaftar
                </a>
                <a class="nav-link" href="<?= site_url('admin/dashboard/atur/participants'); ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-check-circle"></i></div>
                    Validasi Pembayaran
                </a>
                <a class="nav-link" href="<?= site_url('admin/dashboard/atur/articles'); ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
                    Artikel
                </a>
                <a class="nav-link" href="<?= site_url('admin/dashboard/atur/checkin'); ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-qrcode"></i></div>
                    Checkin
                </a>
                <hr>
                <div class="sb-sidenav-menu-heading">Manajemen Situs</div>
                <a class="nav-link" href="<?= site_url('admin/users'); ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Pengguna
                </a>
                <a class="nav-link" href="<?= site_url('admin/events'); ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-calendar-alt"></i></div>
                    Event
                </a>
                
                <!--<a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                    Submisi
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="#">Review Artikel</a>
                        <a class="nav-link" href="#">Jadwal Presentasi</a>
                    </nav>
                </div>
                -->
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <?= $this->session->userdata('nama'); ?>
        </div>
    </nav>
</div>