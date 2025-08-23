<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Review extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Review_model'); // Kita akan buat model ini
    }

    // Fungsi ini dipanggil saat reviewer mengklik link dari email
    public function start($token) {
        $review_data = $this->Review_model->get_review_by_token($token);

        // Keamanan 1: Cek apakah token ada
        if (!$review_data) {
            show_error('Tautan review tidak valid atau telah kedaluwarsa.', 404, 'Token Tidak Ditemukan');
            return;
        }
        
        // Keamanan 2: Cek apakah review sudah disubmit
        if ($review_data->status_review == 'submitted') {
            show_error('Terima kasih, Anda sudah pernah mengirimkan hasil review untuk naskah ini.', 403, 'Review Telah Disubmit');
            return;
        }

        // Keamanan 3: Cek masa aktif token (7 hari sejak akses pertama)
        if ($review_data->token_first_accessed_at !== NULL) {
            $first_access_time = strtotime($review_data->token_first_accessed_at);
            if (time() > $first_access_time + (7 * 24 * 60 * 60)) { // 7 hari dalam detik
                show_error('Masa aktif tautan review ini telah berakhir (7 hari setelah akses pertama).', 403, 'Tautan Kedaluwarsa');
                return;
            }
        } else {
            // Jika ini akses pertama, catat waktunya!
            $this->Review_model->record_first_access($token);
        }

        $data['title'] = 'Formulir Peninjauan Naskah';
        $data['review'] = $review_data;

        $this->load->view('review/form', $data);
    }

    // Fungsi untuk memproses form review yang di-submit
    public function submit($token) {
        // Lakukan validasi data form di sini (bisa ditambahkan nanti)

        $review_data = $this->Review_model->get_review_by_token($token);
        if (!$review_data || $review_data->status_review == 'submitted') {
            show_error('Aksi tidak diizinkan.', 403, 'Akses Ditolak');
            return;
        }
        
        $data_to_update = [
            'relevansi' => $this->input->post('relevansi'),
            'kualitas_konten' => $this->input->post('kualitas_konten'),
            'orisinalitas' => $this->input->post('orisinalitas'),
            'gaya_penulisan' => $this->input->post('gaya_penulisan'),
            'rekomendasi' => $this->input->post('rekomendasi'),
            'saran_perbaikan' => $this->input->post('saran_perbaikan'),
            'rekomendasi_best_paper' => $this->input->post('rekomendasi_best_paper'),
            'catatan_untuk_panitia' => $this->input->post('catatan_untuk_panitia'),
            'status_review' => 'submitted',
            'tgl_submit_review' => date('Y-m-d H:i:s')
        ];

        if ($this->Review_model->submit_review_form($token, $data_to_update)) {
            if ($this->input->post('send_copy_to_self')) {
                $this->_send_reviewer_copy($review_data, $this->input->post());
            }
            $this->load->view('review/thank_you'); // Tampilkan halaman terima kasih
        } else {
            show_error('Terjadi kesalahan saat menyimpan data review Anda.', 500, 'Kesalahan Server');
        }
    }
    
    // Tambahkan fungsi private baru ini di dalam controller Review.php
    private function _send_reviewer_copy($review_data, $form_input) {
        $config = Array(
			'mailtype'  => 'html', 
			'charset'   => 'iso-8859-1'
		);
		$this->load->library('email', $config);
    
        $this->email->from('no-reply@stima.unma.ac.id', 'Sistem Konferensi (Salinan Review)');
        $this->email->to($review_data->reviewer_email);
        $this->email->subject('Salinan Hasil Review Anda untuk Naskah: ' . $review_data->judul);
    
        // Buat isi email dari data form yang disubmit
        $message  = "Berikut adalah salinan dari review yang baru saja Anda kirimkan.<br><br>";
        $message .= "--------------------<br>";
        $message .= "<strong>Judul Naskah:</strong> {$review_data->judul}<br><br>";
        $message .= "<strong>Rekomendasi Anda:</strong> " . ucfirst(str_replace('_', ' ', $form_input['rekomendasi'])) . "<br><br>";
        $message .= "<strong>Saran Perbaikan untuk Penulis:</strong><br>" . nl2br(htmlspecialchars($form_input['saran_perbaikan'])) . "<br><br>";
        $message .= "<strong>Catatan untuk Panitia:</strong><br>" . nl2br(htmlspecialchars($form_input['catatan_untuk_panitia'])) . "<br><br>";
        $message .= "--------------------<br><br>";
        $message .= "Terima kasih atas kontribusi Anda.";
    
        $this->email->message($message);
        $this->email->send();
    }
}