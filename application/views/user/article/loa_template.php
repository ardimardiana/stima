<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #e9ecef; }
        .page { background: white; width: 21cm; height: 29.7cm; display: block; margin: 2rem auto; padding: 2.5cm; box-shadow: 0 0 0.5cm rgba(0,0,0,0.5); }
        .letter-body { line-height: 1.5; }
        .signature-block { margin-top: 100px; }
        .print-button { position: fixed; bottom: 20px; right: 20px; }
        @media print {
            body { background-color: white; margin: 0; }
            .page { margin: 0; box-shadow: none; border: none; }
            .print-button { display: none; }
        }
    </style>
</head>
<body>
    <div class="page">
        <?php if (!empty($loa->header_loa_path)): ?>
            <img src="<?= base_url($loa->header_loa_path); ?>" class="img-fluid mb-4" alt="Kop Surat">
        <?php endif; ?>

        <p class="text-end">Majalengka, <?= date('d F Y'); ?></p>
        <p>
            Nomor: <?=$loa->paper_id?>/LOA/PANITIA/<?=$loa->nama_event?>/<?=$loa->tahun?><br>
            Perihal: Surat Penerimaan Naskah
        </p>

        <p class="mt-5">
            <strong>Yth. Bapak/Ibu <?= htmlspecialchars($loa->nama_depan . ' ' . $loa->nama_belakang, ENT_QUOTES, 'UTF-8'); ?></strong><br>
            di Tempat
        </p>

        <p class="mt-4 letter-body">
            Dengan hormat,<br>
            Kami mengucapkan terima kasih atas partisipasi dan submisi naskah Anda pada acara seminar ilmiah <strong><?= htmlspecialchars($loa->nama_event, ENT_QUOTES, 'UTF-8'); ?></strong>.
            <br><br>
            Setelah melalui proses peninjauan oleh tim reviewer, kami dengan gembira memberitahukan bahwa naskah Anda yang berjudul:
        </p>
        <h4 class="text-center my-4 fst-italic">"<?= htmlspecialchars($loa->judul, ENT_QUOTES, 'UTF-8'); ?>"</h4>
        <p class="letter-body">
            Telah dinyatakan <strong>DITERIMA</strong> untuk dipresentasikan dalam seminar kami.
            <br><br>
            Kami menantikan kehadiran dan presentasi Anda. Informasi lebih lanjut mengenai jadwal teknis akan kami sampaikan kemudian.
        </p>

        <div class="signature-block">
            <p>Hormat kami,<br>Panitia Penyelenggara</p>
            <br><br><br>
            <p class="fw-bold"><u><?= htmlspecialchars($loa->ketua_panitia, ENT_QUOTES, 'UTF-8'); ?></u><br>Ketua Panitia</p>
        </div>
    </div>

    <button class="btn btn-primary btn-lg print-button" onclick="window.print()">
        <i class="fas fa-print"></i> Cetak / Simpan sebagai PDF
    </button>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
</body>
</html>