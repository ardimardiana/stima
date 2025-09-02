<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white"><h3 class="mb-0">Formulir Peninjauan Naskah Ilmiah</h3></div>
            <div class="card-body p-4">
                <div class="mb-4 p-3 border rounded">
                    <h4><?= htmlspecialchars($review->judul, ENT_QUOTES, 'UTF-8'); ?></h4><hr>
                    <p><strong>Abstrak:</strong> <?= nl2br(htmlspecialchars($review->abstrak, ENT_QUOTES, 'UTF-8')); ?></p>
                    <p><strong>Kata Kunci:</strong> <span class="badge bg-secondary"><?= str_replace(',', '</span> <span class="badge bg-secondary">', htmlspecialchars($review->kata_kunci, ENT_QUOTES, 'UTF-8')); ?></span></p>
                    <a href="<?= base_url($review->file_for_reviewer_path); ?>" class="btn btn-primary" target="_blank"><i class="fas fa-download me-2"></i> Unduh Naskah Lengkap untuk Direview</a>
                </div>

                <?= form_open_multipart('review/submit/' . $review->reviewer_token); ?>
                    <div class="mb-4"><label class="form-label fw-bold">1. Relevansi</label>
                        <div class="form-check"><input type="radio" name="relevansi" value="1" class="form-check-input" required> <label class="form-check-label">Tidak Relevan</label></div>
                        <div class="form-check"><input type="radio" name="relevansi" value="2" class="form-check-input"> <label class="form-check-label">Kurang Relevan</label></div>
                        <div class="form-check"><input type="radio" name="relevansi" value="3" class="form-check-input"> <label class="form-check-label">Cukup Relevan</label></div>
                        <div class="form-check"><input type="radio" name="relevansi" value="4" class="form-check-input"> <label class="form-check-label">Sangat Relevan</label></div>
                    </div>

                    <div class="mb-4"><label class="form-label fw-bold">2. Kualitas Konten Teknis</label>
                        <div class="form-check"><input type="radio" name="kualitas_konten" value="1" class="form-check-input" required> <label class="form-check-label">Tidak dapat diterima</label></div>
                        <div class="form-check"><input type="radio" name="kualitas_konten" value="2" class="form-check-input"> <label class="form-check-label">Rendah</label></div>
                        <div class="form-check"><input type="radio" name="kualitas_konten" value="3" class="form-check-input"> <label class="form-check-label">Sedang</label></div>
                        <div class="form-check"><input type="radio" name="kualitas_konten" value="4" class="form-check-input"> <label class="form-check-label">Tinggi</label></div>
                    </div>

                    <div class="mb-4"><label class="form-label fw-bold">3. Orisinalitas</label>
                        <div class="form-check"><input type="radio" name="orisinalitas" value="1" class="form-check-input" required> <label class="form-check-label">Sudah pernah dikerjakan</label></div>
                        <div class="form-check"><input type="radio" name="orisinalitas" value="2" class="form-check-input"> <label class="form-check-label">Pengembangan minor</label></div>
                        <div class="form-check"><input type="radio" name="orisinalitas" value="3" class="form-check-input"> <label class="form-check-label">Memiliki aspek orisinal</label></div>
                        <div class="form-check"><input type="radio" name="orisinalitas" value="4" class="form-check-input"> <label class="form-check-label">Sangat orisinal</label></div>
                    </div>

                    <div class="mb-4"><label class="form-label fw-bold">4. Gaya Penulisan dan Presentasi</label>
                        <div class="form-check"><input type="radio" name="gaya_penulisan" value="1" class="form-check-input" required> <label class="form-check-label">Sangat sulit dipahami</label></div>
                        <div class="form-check"><input type="radio" name="gaya_penulisan" value="2" class="form-check-input"> <label class="form-check-label">Bahasa perlu perbaikan</label></div>
                        <div class="form-check"><input type="radio" name="gaya_penulisan" value="3" class="form-check-input"> <label class="form-check-label">Cukup jelas</label></div>
                        <div class="form-check"><input type="radio" name="gaya_penulisan" value="4" class="form-check-input"> <label class="form-check-label">Sangat jelas</label></div>
                    </div>

                    <div class="mb-4"><label class="form-label fw-bold">5. Rekomendasi</label>
                        <select name="rekomendasi" class="form-select" required>
                            <option value="">-- Pilih Rekomendasi --</option>
                            <option value="accept">Diterima (Accept)</option>
                            <option value="weak_accept">Diterima dengan catatan (Weak Accept)</option>
                            <option value="weak_reject">Ditolak dengan catatan (Weak Reject)</option>
                            <option value="reject">Ditolak (Reject)</option>
                        </select>
                    </div>

                    <div class="mb-4"><label for="saran_perbaikan" class="form-label fw-bold">6. Saran Perbaikan untuk Penulis (Wajib diisi)</label><textarea name="saran_perbaikan" class="form-control" rows="5" required></textarea></div>
                    <div class="mb-4"><label class="form-label fw-bold">7. Rekomendasi Best Paper Award</label>
                        <div class="form-check"><input type="radio" name="rekomendasi_best_paper" value="0" class="form-check-input" checked> <label class="form-check-label">Tidak</label></div>
                        <div class="form-check"><input type="radio" name="rekomendasi_best_paper" value="1" class="form-check-input"> <label class="form-check-label">Ya</label></div>
                    </div>
                    <div class="mb-4"><label for="catatan_untuk_panitia" class="form-label fw-bold">8. Catatan untuk Panitia (Tidak akan terlihat oleh penulis)</label><textarea name="catatan_untuk_panitia" class="form-control" rows="3"></textarea></div>
                    <div class="mb-4">
                            <label class="form-label">Unggah File (.docx/.pdf)</label>
                            <input type="file" name="anonymized_file" class="form-control" required>
                    </div>
                    <hr>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="send_copy_to_self" value="1" id="send_copy_to_self">
                        <label class="form-check-label" for="send_copy_to_self">Kirimkan salinan hasil review ini ke email saya.</label>
                    </div>

                    <div class="d-grid"><button type="submit" class="btn btn-success btn-lg">Kirim Hasil Review</button></div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</body>
</html>