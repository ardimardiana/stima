<nav class="navbar navbar-expand-lg navbar-custom sticky-top shadow-sm">
  <div class="container py-1">
    <a class="navbar-brand d-flex align-items-center gap-2" href="<?= site_url(); ?>">
        <strong><?= htmlspecialchars($event->tahun, ENT_QUOTES, 'UTF-8'); ?></strong> 
        <span><?=$_ENV['SITE_NAME']?></span>
    </a>
    <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item">
          <a class="nav-link <?= ($this->uri->segment(2) == '' || $this->uri->segment(2) == 'view_event') ? 'active' : '' ?>" href="<?= site_url($event->slug_url); ?>">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="[https://icon-stem.unma.ac.id/](https://icon-stem.unma.ac.id/)" target="_blank">ICon-STEM</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?=$_ENV['template']?>" target="_blank">Template Artikel</a>
        </li>
        <?php if (!empty($archived_events)): ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">Riwayat Event</a>
          <ul class="dropdown-menu shadow-sm border-0 rounded-3 mt-2" aria-labelledby="navbarDropdown">
            <?php foreach($archived_events as $archive): ?>
              <li><a class="dropdown-item py-2" href="<?= site_url($archive->slug_url); ?>"><?= htmlspecialchars($archive->nama_event, ENT_QUOTES, 'UTF-8'); ?></a></li>
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
        <li class="nav-item">
            <a class="nav-link" href="<?= site_url('home/manual'); ?>" target="_blank">Panduan</a>
        </li>
        <?php if ($is_active_event): ?>
            <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                <a class="btn btn-outline-light w-100 px-4 rounded-pill" href="<?= site_url('auth/login'); ?>">Login</a>
            </li>
            <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                <a class="btn btn-sky w-100 px-4 rounded-pill shadow-sm" href="<?= site_url('auth/register'); ?>">Daftar</a>
            </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>