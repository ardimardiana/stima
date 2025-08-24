    <?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $this->session->flashdata('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link <?= ($this->uri->segment(3) == 'index') ? 'active' : '' ?>" 
           href="<?= site_url('admin/event_manager/index/' . $event->event_id) ?>">
           <i class="fas fa-tachometer-alt me-1"></i> Dasbor Event
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link <?= ($this->uri->segment(2) == 'reports') ? 'active' : '' ?>" 
           href="<?= site_url('admin/reports/participants/' . $event->event_id) ?>">
           <i class="fas fa-users me-1"></i> Daftar Peserta
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link <?= ($this->uri->segment(2) == 'participants') ? 'active' : '' ?>" 
           href="<?= site_url('admin/participants/index/' . $event->event_id) ?>">
           <i class="fas fa-check-circle me-1"></i> Validasi Pembayaran
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($this->uri->segment(2) == 'articles') ? 'active' : '' ?>" 
           href="<?= site_url('admin/articles/index/' . $event->event_id) ?>">
           <i class="fas fa-file-alt me-1"></i> Manajemen Artikel
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($this->uri->segment(2) == 'topics') ? 'active' : '' ?>" 
           href="<?= site_url('admin/topics/index/' . $event->event_id) ?>">
           <i class="fas fa-tags me-1"></i> Topik Makalah
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($this->uri->segment(2) == 'rooms') ? 'active' : '' ?>" 
           href="<?= site_url('admin/rooms/index/' . $event->event_id) ?>">
           <i class="fas fa-door-open me-1"></i> Manajemen Ruangan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($this->uri->segment(2) == 'schedule') ? 'active' : '' ?>" 
           href="<?= site_url('admin/schedule/index/' . $event->event_id) ?>">
           <i class="fas fa-calendar-alt me-1"></i> Manajemen Jadwal
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($this->uri->segment(2) == 'announcements') ? 'active' : '' ?>" 
           href="<?= site_url('admin/announcements/event/' . $event->event_id) ?>">
           <i class="fas fa-bullhorn me-1"></i> Pengumuman
        </a>
    </li>
</ul>