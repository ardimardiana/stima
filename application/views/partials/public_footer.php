<footer class="bg-dark text-white text-center p-4 mt-5">
        <div class="container">
            <p class="mb-0">Copyright &copy; <?=$_ENV['SITE_NAME']?> <?= date('Y'); ?>. All Rights Reserved.</p>
        </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Tetapkan tanggal akhir dari PHP
            // Format 'YYYY-MM-DD HH:MM:SS' aman untuk kebanyakan browser
            const countDownDate = new Date("<?= $event->tgl_mulai_acara; ?>").getTime();
        
            // 2. Perbarui hitungan mundur setiap 1 detik
            const x = setInterval(function() {
        
                // 3. Dapatkan tanggal dan waktu hari ini
                const now = new Date().getTime();
        
                // 4. Hitung selisih antara sekarang dan tanggal akhir
                const distance = countDownDate - now;
        
                // 5. Kalkulasi untuk hari, jam, menit, dan detik
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
                // 6. Tampilkan hasilnya di elemen HTML
                document.getElementById("days").innerText = days;
                document.getElementById("hours").innerText = hours;
                document.getElementById("minutes").innerText = minutes;
                document.getElementById("seconds").innerText = seconds;
        
                // 7. Jika hitungan mundur selesai
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("countdown-timer").classList.add('d-none'); // Sembunyikan timer
                    document.getElementById("countdown-finished").classList.remove('d-none'); // Tampilkan pesan selesai
                }
            }, 1000);
        });
        </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>