<div class="container-fluid px-4">
    <h1 class="mt-4">Validasi Pembayaran: <?= $event->nama_event; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/events') ?>">Manajemen Event</a></li>
        <li class="breadcrumb-item active">Validasi Peserta</li>
    </ol>
    
    <?php $this->load->view('admin/event_manager/_nav'); ?>

    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link <?= $status_filter == 'validasi' ? 'active' : '' ?>" href="<?= site_url('admin/participants/index/'.$event->event_id.'/validasi') ?>">Menunggu Validasi</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $status_filter == 'lunas' ? 'active' : '' ?>" href="<?= site_url('admin/participants/index/'.$event->event_id.'/lunas') ?>">Lunas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $status_filter == 'ditolak' ? 'active' : '' ?>" href="<?= site_url('admin/participants/index/'.$event->event_id.'/ditolak') ?>">Ditolak</a>
        </li>
    </ul>

    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nama Peserta</th>
                            <th>Email</th>
                            <th>Peran</th>
                            <th>Tgl Upload</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($participants as $p): ?>
                        <tr>
                            <td><?= $p->nama_depan . ' ' . $p->nama_belakang; ?></td>
                            <td><?= $p->email; ?></td>
                            <td><span class="badge bg-info"><?= ucfirst($p->peran_event); ?></span></td>
                            <td><?= date('d M Y H:i', strtotime($p->tgl_unggah)); ?></td>
                            <td><span class="badge bg-warning"><?= ucfirst($p->status_pembayaran); ?></span></td>
                            <td>
                                <a href="<?= site_url('admin/participants/detail/' . $p->payment_id) ?>" class="btn btn-sm btn-primary">Lihat Detail</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>