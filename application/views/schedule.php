<?php $this->load->view('partials/public_header'); ?>
<?php $this->load->view('partials/public_navbar'); ?>

<style>
    .session-title { font-weight: 600; }
    .paper-item { border-left: 3px solid #0d6efd; padding-left: 15px; margin-left: 5px; margin-bottom: 10px; }
    .paper-title { font-weight: 500; }
    .paper-authors { font-size: 0.9em; color: #6c757d; }
    .break-item { background-color: #f8f9fa; font-weight: bold; text-align: center; }
</style>

<div class="container my-5">
    <div class="text-center mb-5">
        <h1>Jadwal Acara</h1>
        <p class="lead"><?= $event->nama_event; ?></p>
    </div>

    <?php if (empty($schedule_data)): ?>
        <div class="alert alert-info text-center">Jadwal lengkap akan segera dipublikasikan.</div>
    <?php else: ?>
        <?php foreach ($schedule_data as $day => $rooms): ?>
            <h2 class="mt-5 mb-3 text-center bg-light p-2 rounded">Hari: <?= date('l, d F Y', strtotime($day)); ?></h2>
            <div class="row g-4">
                <?php foreach ($rooms as $room_name => $sessions): ?>
                    <div class="col-lg-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0"><i class="fas fa-door-open me-2"></i><?= htmlspecialchars($room_name); ?></h5>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <tbody>
                                        <?php foreach ($sessions as $session): ?>
                                            <tr>
                                                <td class="text-nowrap" style="width: 25%;">
                                                    <strong><?= date('H:i', strtotime($session->waktu_mulai)); ?> - <?= date('H:i', strtotime($session->waktu_selesai)); ?></strong>
                                                </td>
                                                <td>
                                                    <div class="session-title"><?= htmlspecialchars($session->nama_sesi); ?></div>
                                                    <?php if (!empty($session->papers)): ?>
                                                        <?php foreach ($session->papers as $paper): ?>
                                                            <div class="paper-item mt-2">
                                                                <div class="paper-title">"<?= htmlspecialchars($paper->judul); ?>"</div>
                                                                <div class="paper-authors"><?= htmlspecialchars($paper->authors); ?></div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endforeach; // End loop rooms ?>
            </div>
        <?php endforeach; // End loop schedule_data ?>
    <?php endif; ?>
</div>

<?php $this->load->view('partials/public_footer'); ?>