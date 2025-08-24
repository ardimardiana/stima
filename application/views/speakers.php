<?php $this->load->view('partials/public_header'); ?>
<?php $this->load->view('partials/public_navbar'); ?>

<div class="container my-5">
    <div class="text-center mb-5">
        <h1>Daftar Pembicara</h1>
        <p class="lead">Temui para ahli yang akan berbagi wawasan di <?= $event->nama_event; ?></p>
    </div>

    <div class="row">
        <?php if (empty($speakers)): ?>
            <div class="col"><div class="alert alert-info text-center">Daftar pembicara akan segera diumumkan.</div></div>
        <?php else: ?>
        <?php foreach($speakers as $speaker): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 text-center shadow-sm">
                        <div class="card-body">
                            <img src="<?= base_url($speaker->photo_path ?: 'assets/img/default_avatar.png'); ?>" 
                                 class="rounded-circle mb-3" alt="Foto <?= $speaker->nama_depan; ?>" 
                                 style="width: 120px; height: 120px; object-fit: cover;">
                            <h5 class="card-title"><?= htmlspecialchars($speaker->nama_depan . ' ' . $speaker->nama_belakang); ?></h5>
                            <p class="text-muted mb-2"><?= htmlspecialchars($speaker->afiliasi); ?></p>
                            <hr>
                            <h6 class="card-subtitle my-3 text-primary">Akan Mempresentasikan:</h6>
                            <p class="card-text">"<?= htmlspecialchars($speaker->judul); ?>"</p>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" 
                                    data-bs-target="#abstractModal"
                                    data-title="<?= htmlspecialchars($speaker->judul, ENT_QUOTES); ?>"
                                    data-abstract="<?= htmlspecialchars($speaker->abstrak, ENT_QUOTES); ?>">
                                Lihat Abstrak
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="abstractModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="abstractModalLabel">Abstrak</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="abstractModalBody">
                    ...
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script untuk mengisi modal dengan data yang benar saat tombol diklik
        const abstractModal = document.getElementById('abstractModal');
        abstractModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const title = button.getAttribute('data-title');
            const abstract = button.getAttribute('data-abstract');

            const modalTitle = abstractModal.querySelector('.modal-title');
            const modalBody = abstractModal.querySelector('.modal-body');

            modalTitle.textContent = title;
            modalBody.textContent = abstract;
        });
    </script>

<?php $this->load->view('partials/public_footer'); ?>