<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Articles extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Article_admin_model');
        $this->load->model('Event_model');
        $this->load->model('Chat_model');
    }
    
    // Fungsi untuk memproses aksi dari tombol keputusan final
    public function update_status($paper_id) {
        $status = $this->input->post('status');
    
        // Validasi input status
        if (!in_array($status, ['accepted', 'revision', 'rejected'])) {
            $this->session->set_flashdata('error', 'Aksi tidak valid.');
            redirect('admin/articles/detail/' . $paper_id);
        }
    
        // Panggil model untuk update status di database
        if ($this->Article_admin_model->update_article_status($paper_id, $status)) {
            // Jika update berhasil, kirim notifikasi email ke presenter
            $this->_send_decision_notification_email($paper_id, $status);
            $this->session->set_flashdata('success', 'Status artikel berhasil diubah menjadi: ' . ucfirst($status));
        } else {
            $this->session->set_flashdata('error', 'Gagal mengubah status artikel.');
        }
    
        redirect('admin/articles/detail/' . $paper_id);
    }
    
    // Fungsi private untuk mengirim email notifikasi keputusan
    private function _send_decision_notification_email($paper_id, $status) {
        $config = Array(
			'mailtype'  => 'html', 
			'charset'   => 'iso-8859-1'
		);
		$this->load->library('email', $config);
		
        $presenter = $this->Article_admin_model->get_presenter_details_by_paper($paper_id);
        $event = $this->Event_model->get_event_by_id_from_paper($paper_id);
        $paper = $this->db->get_where('tbl_papers', ['paper_id' => $paper_id])->row();
    
        if (!$presenter || !$event || !$paper) return; // Hentikan jika data tidak lengkap
    
        $article_link = site_url('user/article/index/' . $presenter->registration_id);
        $subject = '';
        $message = "Halo {$presenter->nama_depan},<br><br>";
        $message .= "Panitia telah selesai meninjau artikel Anda yang berjudul \"<strong>{$paper->judul}</strong>\".<br><br>";
    
        // Tentukan subjek dan isi email berdasarkan status
        switch ($status) {
            case 'accepted':
                $subject = 'Selamat! Artikel Anda Diterima';
                $message .= "Dengan senang hati kami memberitahukan bahwa artikel Anda telah <strong>DITERIMA</strong> untuk dipresentasikan.<br>";
                $message .= "Langkah selanjutnya adalah mengunggah naskah final dan slide presentasi Anda. Silakan login ke dasbor Anda untuk melanjutkan.";
                break;
            case 'revision':
                $subject = 'Pemberitahuan Revisi untuk Artikel Anda';
                $message .= "Artikel Anda membutuhkan <strong>REVISI</strong> berdasarkan masukan dari reviewer.<br>";
                $message .= "Silakan login ke dasbor Anda untuk melihat detail masukan dan mengunggah naskah perbaikan.";
                break;
            case 'rejected':
                $subject = 'Pemberitahuan Hasil Review Artikel Anda';
                $message .= "Setelah melalui proses review yang saksama, dengan berat hati kami informasikan bahwa artikel Anda belum dapat diterima pada kesempatan ini.<br>";
                $message .= "Kami sangat menghargai usaha dan kontribusi Anda.";
                break;
        }
    
        $message .= "<br><br>Anda dapat mengakses halaman manajemen artikel melalui tautan berikut:<br>";
        $message .= "<a href='{$article_link}'>{$article_link}</a><br><br>";
        $message .= "Hormat kami,<br>Panitia Penyelenggara";
    
        $this->email->from('no-reply@stima.unma.ac.id', 'Panitia ' . $event->nama_event);
        $this->email->to($presenter->email);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->send();
    }

    public function assign_reviewer($paper_id) {
        // Validasi form
        $this->form_validation->set_rules('reviewer_name', 'Nama Reviewer', 'required');
        $this->form_validation->set_rules('reviewer_email', 'Email Reviewer', 'required|valid_email');
    
        // Konfigurasi Upload File Anonim
        $config['upload_path']   = './uploads/review_files/';
        $config['allowed_types'] = 'docx|pdf';
        $config['max_size']      = 5120; // 5MB
        $config['encrypt_name']  = TRUE;
    
        if (!is_dir($config['upload_path'])) { mkdir($config['upload_path'], 0777, TRUE); }
        $this->load->library('upload', $config);
    
        if ($this->form_validation->run() == FALSE || !$this->upload->do_upload('anonymized_file')) {
            $errors = validation_errors() . ' ' . $this->upload->display_errors('<p>', '</p>');
            $this->session->set_flashdata('error', $errors);
        } else {
            $upload_data = $this->upload->data();
            
            $review_data = [
                'paper_id' => $paper_id,
                'reviewer_name' => $this->input->post('reviewer_name'),
                'reviewer_email' => $this->input->post('reviewer_email'),
                'file_for_reviewer_path' => 'uploads/review_files/' . $upload_data['file_name'],
                'status_review' => 'pending' // Status awal
            ];
    
            // Panggil model untuk menyimpan data dan mengubah status artikel
            $new_review = $this->Article_admin_model->assign_reviewer($review_data, $paper_id);
    
            if ($new_review) {
                // Jika berhasil, kirim email undangan
                $this->_send_review_invitation($new_review);
                $this->session->set_flashdata('success', 'Reviewer berhasil ditugaskan dan email undangan telah dikirim.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menugaskan reviewer.');
            }
        }
        redirect('admin/articles/detail/' . $paper_id);
    }
    
    // Fungsi private untuk mengirim email
    private function _send_review_invitation($review_data) {
        $config = Array(
			'mailtype'  => 'html', 
			'charset'   => 'iso-8859-1'
		);
		$this->load->library('email', $config);
        
        $event = $this->Event_model->get_event_by_id_from_paper($review_data['paper_id']);
        
        // Buat link review unik
        $review_link = site_url('review/start/' . $review_data['reviewer_token']);
    
        $this->email->from('no-reply@stima.unma.ac.id', 'Panitia ' . $event->nama_event);
        $this->email->to($review_data['reviewer_email']);
        $this->email->subject('Undangan untuk Mereview Naskah Ilmiah');
    
        $message  = "Dengan hormat Bapak/Ibu {$review_data['reviewer_name']},<br><br>";
        $message .= "Kami mengundang Anda untuk berpartisipasi sebagai reviewer dalam acara <strong>{$event->nama_event}</strong>.<br><br>";
        $message .= "Silakan akses tautan di bawah ini untuk melihat naskah dan mengisi formulir review:<br>";
        $message .= "<a href='{$review_link}'>{$review_link}</a><br><br>";
        $message .= "Tautan ini akan aktif selama <strong>7 hari</strong> sejak pertama kali Anda membukanya.<br><br>";
        $message .= "Atas perhatian dan kerja sama Anda, kami ucapkan terima kasih.<br><br>";
        $message .= "Hormat kami,<br>Panitia Penyelenggara";
    
        $this->email->message($message);
        $this->email->send();
    }
    
    // Fungsi untuk memproses form chat dari admin
    public function post_admin_chat($paper_id) {
        $message = trim($this->input->post('message'));
    
        if (!empty($message)) {
            $data = [
                'paper_id'      => $paper_id,
                'user_id'       => $this->session->userdata('user_id'), // ID Admin
                'sent_by_admin' => 1, // Penanda pesan dari admin
                'message'       => $message
            ];
    
            if ($this->Chat_model->post_message($data)) {
                // Jika pesan berhasil disimpan, kirim notifikasi email
                $this->_send_chat_notification_email($paper_id);
                $this->session->set_flashdata('success', 'Pesan berhasil dikirim.');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengirim pesan.');
            }
        }
        redirect('admin/articles/detail/' . $paper_id);
    }
    
    // Fungsi private untuk mengirim notifikasi email ke presenter
    private function _send_chat_notification_email($paper_id) {
        $config = Array(
			'mailtype'  => 'html', 
			'charset'   => 'iso-8859-1'
		);
		$this->load->library('email', $config);
		
        $presenter = $this->Article_admin_model->get_presenter_details_by_paper($paper_id);
        $event = $this->Event_model->get_event_by_id_from_paper($paper_id);
    
        if (!$presenter || !$event) {
            return; // Hentikan jika data tidak ditemukan
        }
    
        $article_link = site_url('user/article/index/' . $presenter->registration_id);
    
        $this->email->from('no-reply@stima.unma.ac.id', 'Panitia ' . $event->nama_event);
        $this->email->to($presenter->email);
        $this->email->subject('Pesan Baru dari Panitia Terkait Artikel Anda');
    
        $message  = "Halo {$presenter->nama_depan},<br><br>";
        $message .= "Anda menerima pesan baru dari panitia terkait submisi artikel Anda untuk acara <strong>{$event->nama_event}</strong>.<br><br>";
        $message .= "Silakan login ke dasbor Anda atau klik tautan di bawah ini untuk melihat dan membalas pesan:<br>";
        $message .= "<a href='{$article_link}'>{$article_link}</a><br><br>";
        $message .= "Terima kasih.<br><br>";
        $message .= "Hormat kami,<br>Panitia Penyelenggara";
    
        $this->email->message($message);
        $this->email->send();
    }
    
    public function detail($paper_id) {
        $details = $this->Article_admin_model->get_article_details($paper_id);
        
        if (!$details) {
            show_404();
        }
        
        $data['chats'] = [];
        
        $data['chats'] = $this->Chat_model->get_messages($paper_id);
    
        $data['title'] = 'Detail Artikel';
        $data['event'] = $this->Event_model->get_event_by_id_from_paper($paper_id); // Anda perlu buat fungsi ini
        $data['paper'] = $details['paper'];
        $data['authors'] = $details['authors'];
        $data['reviews'] = $details['reviews'];
    
        $this->load->view('admin/_partials/_header', $data);
        $this->load->view('admin/articles/detail', $data);
        $this->load->view('admin/_partials/_footer');
    }

    public function index($event_id, $status_filter = NULL) {
        $data['title'] = 'Manajemen Artikel';
        $data['event'] = $this->Event_model->get_event_by_id($event_id);
        $data['articles'] = $this->Article_admin_model->get_articles_by_event($event_id, $status_filter);
        $data['status_filter'] = $status_filter;
    
        $this->load->view('admin/_partials/_header', $data);
        $this->load->view('admin/articles/index', $data);
        $this->load->view('admin/_partials/_footer');
    }
    
    public function review($event_id, $status_filter = NULL) {
        $data['title'] = 'Manajemen Artikel';
        $data['event'] = $this->Event_model->get_event_by_id($event_id);
        $data['articles'] = $this->Article_admin_model->get_articles_by_review($event_id, $status_filter);
        $data['status_filter'] = $status_filter;
    
        $this->load->view('admin/_partials/_header', $data);
        $this->load->view('admin/articles/review', $data);
        $this->load->view('admin/_partials/_footer');
    }
    
    public function review_final($event_id, $status_filter = NULL) {
        $data['title'] = 'Manajemen Artikel';
        $data['event'] = $this->Event_model->get_event_by_id($event_id);
        $data['articles'] = $this->Article_admin_model->get_articles_by_review($event_id);
        $data['status_filter'] = $status_filter;
    
        $this->load->view('admin/_partials/_header', $data);
        $this->load->view('admin/articles/review_final', $data);
        $this->load->view('admin/_partials/_footer');
    }
    
    public function export_articles_excel($event_id) {
        $articles = $this->Article_admin_model->get_articles_for_export($event_id);
        $event = $this->Event_model->get_event_by_id($event_id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Daftar Artikel');

        // Header
        $sheet->setCellValue('A1', 'Judul Artikel');
        $sheet->setCellValue('B1', 'Penulis Utama');
        $sheet->setCellValue('C1', 'Topik');
        $sheet->setCellValue('D1', 'Status');
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        // Data
        $rowNum = 2;
        foreach ($articles as $article) {
            $sheet->setCellValue('A' . $rowNum, $article['judul']);
            $sheet->setCellValue('B' . $rowNum, $article['nama_depan'] . ' ' . $article['nama_belakang']);
            $sheet->setCellValue('C' . $rowNum, $article['nama_topik']);
            $sheet->setCellValue('D' . $rowNum, ucfirst($article['status_artikel']));
            $rowNum++;
        }

        foreach(range('A','D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Kirim file
        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan_artikel_' . $event->slug_url . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $writer->save('php://output');
        exit();
    }
}