<?php $this->load->view('partials/public_header'); ?>
<?php $this->load->view('partials/public_navbar'); ?>
    <div class="container my-5">
        <div class="text-center mb-5">
            <h1>Jadwal Acara</h1>
            <p class="lead"><?= $event->nama_event; ?></p>
        </div>

        <?php if (empty($schedule_data)): ?>
            <div class="alert alert-info text-center">Jadwal lengkap akan segera dipublikasikan.</div>
        <?php else: ?>
            <?php foreach ($schedule_data as $day => $time_slots): ?>
                <h3 class="mt-5">Hari: <?= date('l, d F Y', strtotime($day)); ?></h3>
                <div class="table-responsive">
                    <table class="table table-bordered schedule-table">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 15%;">Waktu</th>
                                <?php foreach ($rooms as $room): ?>
                                    <th><?= htmlspecialchars($room->nama_ruang); ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($time_slots as $time => $room_sessions): ?>
                                <tr>
                                    <td><strong><?= date('H:i', strtotime($time)); ?></strong></td>
                                    <?php foreach ($rooms as $room): ?>
                                        <td>
                                            <?php if (isset($room_sessions[$room->room_id])): 
                                                $session = $room_sessions[$room->room_id];
                                            ?>
                                                <?php if ($session->paper_id): // Sesi presentasi ?>
                                                    <div class="session-item">
                                                        <strong><?= htmlspecialchars($session->paper_title); ?></strong><br>
                                                        <small class="text-muted"><?= htmlspecialchars($session->authors); ?></small>
                                                    </div>
                                                <?php else: // Sesi umum seperti coffee break ?>
                                                    <div class="break-item">
                                                        <?= htmlspecialchars($session->nama_sesi); ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; // End if isset ?>
                                        </td>
                                    <?php endforeach; // End loop rooms ?>
                                </tr>
                            <?php endforeach; // End loop time_slots ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; // End loop schedule_data ?>
        <?php endif; ?>
    </div>

<?php $this->load->view('partials/public_footer'); ?>