<div class="container-fluid px-4">
    <h1 class="mt-4">Scanner Kehadiran: <?= $event->nama_event; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/event_manager/index/'.$event->event_id) ?>">Dasbor Event</a></li>
        <li class="breadcrumb-item active">Check-in Scanner</li>
    </ol>
    
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-body text-center">
                    <div id="qr-reader" style="width:100%;"></div>
                    <div id="qr-result" class="mt-3">
                        <div id="alert-placeholder"></div>
                        <p>Arahkan kamera ke QR Code peserta.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        // Hentikan scan sejenak untuk memproses
        html5QrcodeScanner.pause();

        let alertPlaceholder = document.getElementById('alert-placeholder');
        
        // Kirim data ke server menggunakan AJAX
        $.ajax({
            url: "<?= site_url('admin/checkin/process') ?>",
            type: "POST",
            data: {
                qr_code: decodedText,
                event_id: <?= $event->event_id; ?>
            },
            dataType: "json",
            success: function(response) {
                let alertClass = '';
                if(response.status === 'success') alertClass = 'alert-success';
                else if(response.status === 'warning') alertClass = 'alert-warning';
                else alertClass = 'alert-danger';
                
                alertPlaceholder.innerHTML = `<div class="alert ${alertClass}"><strong>${response.name}:</strong> ${response.message}</div>`;
            },
            error: function() {
                 alertPlaceholder.innerHTML = `<div class="alert alert-danger">Gagal terhubung ke server.</div>`;
            },
            complete: function() {
                // Lanjutkan scan setelah 2 detik
                setTimeout(() => {
                    alertPlaceholder.innerHTML = ''; // Hapus pesan
                    html5QrcodeScanner.resume();
                }, 2000);
            }
        });
    }

    var html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", { fps: 10, qrbox: 250 });
    html5QrcodeScanner.render(onScanSuccess);
</script>