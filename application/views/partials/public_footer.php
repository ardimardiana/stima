<footer class="text-white text-center p-4 mt-auto w-100" style="background-color: var(--c-dark);">
        <div class="container">
            <p class="mb-0 opacity-75 fw-light" style="font-size: 0.95rem;">
                Copyright &copy; <?= date('Y'); ?> <strong><?=$_ENV['SITE_NAME']?></strong>. All Rights Reserved.
            </p>
        </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const countdownElem = document.getElementById("countdown-timer");
            const finishedElem = document.getElementById("countdown-finished");
            
            if(!countdownElem) return;
            
            // 1. Tetapkan tanggal akhir dari PHP
            const countDownDate = new Date("<?= $event->tgl_mulai_acara; ?>").getTime();
        
            // 2. Perbarui hitungan mundur setiap 1 detik
            const x = setInterval(function() {
        
                const now = new Date().getTime();
                const distance = countDownDate - now;
        
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
                // Update DOM jika elemen ada
                const dElem = document.getElementById("days");
                if (dElem) {
                    dElem.innerText = days < 10 ? '0' + days : days;
                    document.getElementById("hours").innerText = hours < 10 ? '0' + hours : hours;
                    document.getElementById("minutes").innerText = minutes < 10 ? '0' + minutes : minutes;
                    document.getElementById("seconds").innerText = seconds < 10 ? '0' + seconds : seconds;
                }
        
                if (distance < 0) {
                    clearInterval(x);
                    countdownElem.classList.add('d-none');
                    if (finishedElem) finishedElem.classList.remove('d-none');
                }
            }, 1000);
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>