<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="<?= site_url(); ?>"><strong><?= htmlspecialchars($event->tahun, ENT_QUOTES, 'UTF-8'); ?></strong> CMS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link <?= ($this->uri->segment(2) == '' || $this->uri->segment(2) == 'view_event') ? 'active' : '' ?>" href="<?= site_url($event->slug_url); ?>">Home</a>
        </li>
        
        <?php if (!empty($archived_events)): ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">Riwayat Event</a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <?php foreach($archived_events as $archive): ?>
              <li><a class="dropdown-item" href="<?= site_url($archive->slug_url); ?>"><?= htmlspecialchars($archive->nama_event, ENT_QUOTES, 'UTF-8'); ?></a></li>
            <?php endforeach; ?>
          </ul>
        </li>
        <?php endif; ?>
    
        <li class="nav-item">
          <a class="nav-link <?= ($this->uri->segment(2) == 'speakers') ? 'active' : '' ?>" href="<?= site_url('home/speakers'); ?>">Speakers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($this->uri->segment(2) == 'schedule') ? 'active' : '' ?>" href="<?= site_url('home/schedule'); ?>">Jadwal</a>
        </li>
        
        <?php if ($is_active_event): ?>
            <li class="nav-item ms-lg-3"><a class="btn btn-outline-light" href="<?= site_url('auth/login'); ?>">Login</a></li>
            <li class="nav-item ms-lg-2"><a class="btn btn-primary" href="<?= site_url('auth/register'); ?>">Daftar</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>