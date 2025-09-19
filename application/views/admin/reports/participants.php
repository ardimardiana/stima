<div class="container-fluid px-4">
    <h1 class="mt-4">Laporan Peserta: <?= htmlspecialchars($event->nama_event, ENT_QUOTES, 'UTF-8'); ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/events') ?>">Manajemen Event</a></li>
        <li class="breadcrumb-item active">Laporan Peserta</li>
    </ol>
    
    <?php $this->load->view('admin/event_manager/_nav'); ?>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-users me-1"></i>
            Total Pendaftar: <?= count($participants); ?> Orang
            
            <a href="<?= site_url('admin/reports/export_attendees/' . $event->event_id) ?>" class="btn btn-sm btn-info float-end ms-2"><i class="fas fa-download me-1"></i> Export Peserta Hadir (CSV)</a>

            <a href="<?= site_url('admin/reports/export_all_participants_excel/' . $event->event_id) ?>" class="btn btn-sm btn-success float-end"><i class="fas fa-file-excel me-1"></i> Export Semua (Excel)</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label for="filter-peran" class="form-label">Filter Peran</label>
                    <select id="filter-peran" class="form-select filter-select" data-column-index="4">
                        <option value="">Semua Peran</option>
                        <option value="Peserta">Peserta</option>
                        <option value="Presenter">Presenter</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filter-pembayaran" class="form-label">Filter Pembayaran</label>
                    <select id="filter-pembayaran" class="form-select filter-select" data-column-index="5">
                        <option value="">Semua Status</option>
                        <option value="Lunas">Lunas</option>
                        <option value="Ditolak">Ditolak</option>
                        <option value="Menunggu">Menunggu</option>
                        <option value="Validasi">Validasi</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filter-kehadiran" class="form-label">Filter Kehadiran</label>
                    <select id="filter-kehadiran" class="form-select filter-select" data-column-index="6">
                        <option value="">Semua Status</option>
                        <option value="Hadir">Hadir</option>
                        <option value="Belum Hadir">Belum Hadir</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table id="participantsTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Afiliasi</th>
                            <th>Peran</th>
                            <th>Status Pembayaran</th>
                            <th>Status Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach($participants as $p): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($p->nama_depan . ' ' . $p->nama_belakang, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= htmlspecialchars($p->email, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= ucfirst($p->afiliasi); ?></td>
                            <td><?= ucfirst($p->peran_event); ?></td>
                            <td>
                                <?php 
                                    $payment_status = $p->status_pembayaran;
                                    $badge_class = 'bg-secondary';
                                    if ($payment_status == 'lunas') $badge_class = 'bg-success';
                                    else if ($payment_status == 'menunggu') $badge_class = 'bg-info text-dark';
                                    else if ($payment_status == 'validasi') $badge_class = 'bg-warning text-dark';
                                    else if ($payment_status == 'ditolak') $badge_class = 'bg-danger';
                                ?>
                                <span class="badge <?= $badge_class; ?>"><?= ucfirst($payment_status); ?></span>
                            </td>
                            <td class="text-center">
                                <?php if ($p->status_kehadiran == 1): ?>
                                    <span class="badge bg-success">Hadir</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Belum Hadir</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-file-upload me-1"></i>
            Import Tautan Sertifikat dari CSV
        </div>
        <div class="card-body">
            <p>Gunakan fitur ini untuk mengunggah tautan sertifikat secara massal. Ikuti langkah berikut:</p>
            <ol>
                <li>Gunakan tombol <strong>"Export Peserta Hadir (CSV)"</strong> di atas untuk mengunduh template.</li>
                <li>Buka file CSV tersebut menggunakan spreadsheet editor (Excel, Google Sheets).</li>
                <li>Isi kolom <strong>sertifikat_path</strong> dan <strong>sertifikat_presenter_path</strong> dengan tautan yang sesuai.</li>
                <li><strong>PENTING:</strong> Jangan mengubah atau menghapus kolom <strong>registration_id</strong>.</li>
                <li>Simpan file, lalu unggah menggunakan form di bawah ini.</li>
            </ol>
    
            <?= form_open_multipart('admin/reports/import_certificates/' . $event->event_id); ?>
                <div class="input-group">
                    <input type="file" class="form-control" name="csv_file" required>
                    <button class="btn btn-primary" type="submit"><i class="fas fa-upload me-1"></i> Unggah dan Proses</button>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    // 1. Inisialisasi DataTables
    const table = $('#participantsTable').DataTable({
        // Opsi tambahan jika diperlukan, misal:
        "pageLength": 10,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.8/i18n/id.json"
        }
    });

    // 2. Tambahkan event listener untuk setiap dropdown filter
    $('.filter-select').on('change', function() {
        // Ambil index kolom dari atribut data-*
        const columnIndex = $(this).data('column-index');
        // Ambil nilai yang dipilih
        const selectedValue = $(this).val();

        // Terapkan filter ke kolom yang sesuai
        // Gunakan ^...$ untuk pencarian yang sama persis
        table.column(columnIndex).search(selectedValue ? '^' + selectedValue + '$' : '', true, false).draw();
    });
});
</script>