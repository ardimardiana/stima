<style>
    /* Styling untuk drag-and-drop */
    .paper-list, .session-papers { min-height: 50px; }
    .paper-item { cursor: grab; background-color: #f8f9fa; border: 1px solid #dee2e6; }
    .session-box { border: 1px dashed #ccc; padding: 10px; }
</style>

<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Jadwal: <?= $event->nama_event; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/event_manager/index/'.$event->event_id) ?>">Dasbor Event</a></li>
        <li class="breadcrumb-item active">Manajemen Jadwal</li>
    </ol>
    <?php $this->load->view('admin/event_manager/_nav'); ?>
    
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header"><h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Artikel Belum Dijadwalkan</h5></div>
                <div class="card-body">
                    <div id="unscheduled-papers" class="list-group paper-list">
                        <?php foreach($unscheduled_papers as $paper): ?>
                            <div class="list-group-item paper-item" data-paper-id="<?= $paper->paper_id; ?>">
                                <?= htmlspecialchars($paper->judul); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Grid Jadwal</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#sessionModal"><i class="fas fa-plus"></i> Buat Sesi Baru</button>
                </div>
                <div class="card-body">
                    <?php foreach($rooms as $room): ?>
                        <h6 class="mt-3">Ruangan: <?= $room->nama_ruang; ?></h6>
                        <?php if(isset($schedules[$room->room_id])): ?>
                            <?php foreach($schedules[$room->room_id] as $schedule): ?>
                                <div class="session-box mb-2">
                                    <strong><?= date('H:i', strtotime($schedule->waktu_mulai)); ?> - <?= date('H:i', strtotime($schedule->waktu_selesai)); ?>: <?= $schedule->nama_sesi; ?></strong>
                                    <div id="schedule-<?= $schedule->schedule_id; ?>" class="session-papers mt-2" data-schedule-id="<?= $schedule->schedule_id; ?>">
                                        <?php if($schedule->paper_id): ?>
                                            <div class="list-group-item paper-item" data-paper-id="<?= $schedule->paper_id; ?>"><?= $schedule->paper_title; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted small">Belum ada sesi untuk ruangan ini.</p>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="sessionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat Sesi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <?= form_open('admin/schedule/create_session/' . $event->event_id); ?>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Nama Sesi</label><input type="text" name="nama_sesi" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Ruangan</label><select name="room_id" class="form-select" required><?php foreach($rooms as $room): ?><option value="<?= $room->room_id; ?>"><?= $room->nama_ruang; ?></option><?php endforeach; ?></select></div>
                    <div class="row">
                        <div class="col"><label class="form-label">Waktu Mulai</label><input type="datetime-local" name="waktu_mulai" class="form-control" required></div>
                        <div class="col"><label class="form-label">Waktu Selesai</label><input type="datetime-local" name="waktu_selesai" class="form-control" required></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Sesi</button>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi list paper yang belum dijadwalkan
    var unscheduledList = document.getElementById('unscheduled-papers');
    new Sortable(unscheduledList, {
        group: 'shared-papers', // Grup yang sama agar bisa dipindah
        animation: 150
    });

    // Inisialisasi setiap kotak sesi di grid jadwal
    document.querySelectorAll('.session-papers').forEach(function(sessionEl) {
        new Sortable(sessionEl, {
            group: 'shared-papers',
            animation: 150,
            onAdd: function (evt) {
                // Event ini dijalankan saat paper di-drop ke kotak sesi
                var paperId = evt.item.getAttribute('data-paper-id');
                var scheduleId = evt.to.getAttribute('data-schedule-id');
                
                // Kirim data ke server via AJAX
                fetch("<?= site_url('admin/schedule/assign_paper'); ?>", {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `paper_id=${paperId}&schedule_id=${scheduleId}`
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data.message); // Tampilkan notifikasi jika perlu
                });
            }
        });
    });
});
</script>