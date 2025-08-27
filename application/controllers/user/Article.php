<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article extends User_Controller {

    public function __construct() {
        parent::__construct();
        // Nanti kita akan buat model ini
        $this->load->model('Article_model'); 
        $this->load->model('User_model'); 
        $this->load->model('Chat_model');
    }
    
    public function generate_loa($registration_id, $paper_id) {
        // Keamanan 1: Pastikan user adalah pemilik
        $user_id = $this->session->userdata('user_id');
        if (!$this->User_model->is_registration_owner($registration_id, $user_id)) {
            show_404(); return;
        }
    
        $paper = $this->Article_model->get_paper_by_registration($registration_id);
    
        // Keamanan 2: Pastikan status artikel sudah 'accepted'
        // --- PERBAIKAN LOGIKA DI SINI ---
        // Cek apakah status artikel TIDAK ada di dalam array yang diizinkan
        if (!$paper || !in_array($paper->status_artikel, ['accepted', 'final_submitted'])) {
            show_error('Letter of Acceptance tidak tersedia untuk artikel ini.', 403, 'Akses Ditolak');
            return;
        }
    
        // Ambil semua data yang dibutuhkan untuk LoA
        $data['loa'] = $this->Article_model->get_loa_data($paper_id);
        $data['title'] = 'Letter of Acceptance';
    
        $this->load->view('user/article/loa_template', $data);
    }
    
    // Fungsi untuk memproses unggahan revisi
    public function submit_revision($registration_id, $paper_id) {
        // Keamanan
        $user_id = $this->session->userdata('user_id');
        if (!$this->User_model->is_registration_owner($registration_id, $user_id)) { show_404(); return; }
    
        // Konfigurasi Upload
        $config['upload_path']   = './uploads/papers_revised/';
        $config['allowed_types'] = 'docx';
        $config['max_size']      = 5120;
        $config['encrypt_name']  = TRUE;
    
        if (!is_dir($config['upload_path'])) { mkdir($config['upload_path'], 0777, TRUE); }
        $this->load->library('upload', $config);
    
        if ($this->upload->do_upload('file_revision')) {
            $file_path = 'uploads/papers_revised/' . $this->upload->data('file_name');
            
            // Panggil model untuk update path dan STATUS
            $this->Article_model->submit_revision($paper_id, $file_path);
            $this->session->set_flashdata('success', 'Naskah revisi berhasil diunggah. Panitia akan meninjau kembali.');
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors());
        }
        redirect('user/article/index/' . $registration_id);
    }
    
    public function submit_final($registration_id, $paper_id) {
        // Keamanan: Pastikan user adalah pemilik artikel
        $user_id = $this->session->userdata('user_id');
        if (!$this->User_model->is_registration_owner($registration_id, $user_id)) {
            show_404();
            return;
        }
    
        $this->load->library('upload');
        $data_to_update = []; // Siapkan array kosong untuk menampung path file
        $errors = '';
    
        // --- 1. PROSES UPLOAD NASKAH FINAL ---
        // Cek apakah file naskah final diisi di form
        if (!empty($_FILES['file_path_final']['name'])) {
            $config_paper['upload_path']   = './uploads/papers_final/';
            $config_paper['allowed_types'] = 'docx|pdf';
            $config_paper['max_size']      = 5120; // 5MB
            $config_paper['encrypt_name']  = TRUE;
    
            if (!is_dir($config_paper['upload_path'])) { mkdir($config_paper['upload_path'], 0777, TRUE); }
            
            $this->upload->initialize($config_paper); // Inisialisasi untuk file pertama
    
            if ($this->upload->do_upload('file_path_final')) {
                // Jika berhasil, simpan path-nya
                $data_to_update['file_path_final'] = 'uploads/papers_final/' . $this->upload->data('file_name');
            } else {
                // Jika gagal, kumpulkan pesan error
                $errors .= $this->upload->display_errors('<p>', '</p>');
            }
        }
    
        // --- 2. PROSES UPLOAD SLIDE PRESENTASI ---
        // Cek apakah file slide diisi di form
        if (!empty($_FILES['slide_path']['name'])) {
            $config_slide['upload_path']   = './uploads/slides/';
            $config_slide['allowed_types'] = 'pptx|ppt';
            $config_slide['max_size']      = 10240; // 10MB
            $config_slide['encrypt_name']  = TRUE;
            
            if (!is_dir($config_slide['upload_path'])) { mkdir($config_slide['upload_path'], 0777, TRUE); }
    
            // Re-inisialisasi library upload untuk file kedua
            $this->upload->initialize($config_slide); 
    
            if ($this->upload->do_upload('slide_path')) {
                // Jika berhasil, simpan path-nya
                $data_to_update['slide_path'] = 'uploads/slides/' . $this->upload->data('file_name');
            } else {
                // Jika gagal, kumpulkan pesan error
                $errors .= $this->upload->display_errors('<p>', '</p>');
            }
        }
    
        // --- 3. UPDATE DATABASE & REDIRECT ---
        if (!empty($errors)) {
            // Jika ada error dari salah satu atau kedua upload, tampilkan pesan
            $this->session->set_flashdata('error', $errors);
        } else {
            // Jika tidak ada error sama sekali
            if (!empty($data_to_update)) {
                // Panggil model hanya jika ada file yang berhasil di-upload
                $this->Article_model->submit_final_version($paper_id, $data_to_update);
                $this->session->set_flashdata('success', 'Naskah final dan/atau slide berhasil diunggah. Terima kasih.');
            } else {
                // Jika pengguna menekan submit tanpa memilih file
                $this->session->set_flashdata('warning', 'Anda tidak memilih file untuk diunggah.');
            }
        }
        
        redirect('user/article/index/' . $registration_id);
    }
    
    public function delete_submission($registration_id, $paper_id) {
        // Keamanan 1: Pastikan registrasi ini milik user yang login
        $user_id = $this->session->userdata('user_id');
        if (!$this->User_model->is_registration_owner($registration_id, $user_id)) {
            show_404();
            return;
        }
    
        // Ambil data paper untuk cek status
        $paper = $this->Article_model->get_paper_by_registration($registration_id);
    
        // Keamanan 2: Pastikan paper ada dan statusnya 'submitted'
        if ($paper && $paper->paper_id == $paper_id && $paper->status_artikel == 'submitted') {
            
            // 1. Simpan path file sebelum record dihapus dari database
            $file_path_to_delete = $paper->file_path_initial;
    
            // 2. Hapus record dari database terlebih dahulu
            if ($this->Article_model->delete_paper($paper_id)) {
                // 3. JIKA record database berhasil dihapus, HAPUS FILE FISIK
                if (!empty($file_path_to_delete) && file_exists($file_path_to_delete)) {
                    unlink($file_path_to_delete);
                }
                
                $this->session->set_flashdata('success', 'Submisi artikel telah berhasil dihapus. Anda dapat melakukan submit ulang.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menghapus submisi artikel dari database.');
            }
            
        } else {
            $this->session->set_flashdata('error', 'Artikel tidak dapat dihapus karena sudah dalam proses review.');
        }
    
        redirect('user/article/index/' . $registration_id);
    }

    public function index($registration_id) {
        // --- PENAMBAHAN BAGIAN KEAMANAN DIMULAI DI SINI ---
        $user_id = $this->session->userdata('user_id');
    
        // Panggil model untuk verifikasi kepemilikan
        if (!$this->User_model->is_registration_owner($registration_id, $user_id)) {
            // Jika BUKAN miliknya, tampilkan halaman error 404.
            // Jangan beri tahu bahwa halaman itu ada tapi terlarang.
            show_404();
            return; // Hentikan eksekusi
        }
        // --- BAGIAN KEAMANAN SELESAI ---
    
        // Kode di bawah ini hanya akan berjalan jika pengecekan berhasil
        $data['title'] = 'Manajemen Artikel';
        $data['registration_id'] = $registration_id;
        
        $data['paper'] = $this->Article_model->get_paper_by_registration($registration_id);
        
        $registration_data = $this->db->get_where('tbl_event_registrations', ['registration_id' => $registration_id])->row();
        $this->load->model('Topic_model');
        $data['topics'] = $this->Topic_model->get_by_event($registration_data->event_id);
        $this->load->model('Event_model');
        $data['event'] = $this->Event_model->get_event_by_id($registration_data->event_id);
    
        
        $data['reviews'] = [];
        $data['authors'] = [];
        $data['chats'] = [];
        $data['schedule'] = null;
        
        if ($data['paper']) {
            $data['chats'] = $this->Chat_model->get_messages($data['paper']->paper_id);
            $data['reviews'] = $this->Article_model->get_reviews_by_paper($data['paper']->paper_id);
            $data['authors'] = $this->Article_model->get_authors_by_paper($data['paper']->paper_id);
            $data['schedule'] = $this->Article_model->get_schedule_by_paper($data['paper']->paper_id);
        }
    
        $this->load->view('user/article/index', $data);
    }
    
    public function post_chat_message($registration_id, $paper_id) {
        // Keamanan: Pastikan user ini adalah pemilik paper
        $user_id = $this->session->userdata('user_id');
        if (!$this->User_model->is_registration_owner($registration_id, $user_id)) {
            show_404(); return;
        }
    
        $message = $this->input->post('message');
        if (!empty(trim($message))) {
            $data = [
                'paper_id' => $paper_id,
                'user_id' => $user_id,
                'sent_by_admin' => 0, // 0 untuk pesan dari peserta
                'message' => $message
            ];
            $this->Chat_model->post_message($data);
        }
        redirect('user/article/index/' . $registration_id);
    }
    
    public function submit_initial($registration_id) {
        // Keamanan: Pastikan ini miliknya
        $user_id = $this->session->userdata('user_id');
        if (!$this->User_model->is_registration_owner($registration_id, $user_id)) {
            show_404();
            return;
        }
        
        $registration_data = $this->db->get_where('tbl_event_registrations', ['registration_id' => $registration_id])->row();
        $this->load->model('Event_model');
        $event = $this->Event_model->get_event_by_id($registration_data->event_id);
    
        if (date('Y-m-d H:i:s') > $event->tgl_batas_submit . ' 23:59:59') {
            $this->session->set_flashdata('error', 'Batas waktu untuk submit artikel telah berakhir.');
            redirect('user/article/index/' . $registration_id);
            return;
        }
    
        // Validasi Form
        $this->form_validation->set_rules('judul', 'Judul Makalah', 'required|trim');
        $this->form_validation->set_rules('abstrak', 'Abstrak', 'required|trim');
        
        // Konfigurasi Upload File
        $config['upload_path']   = './uploads/papers/';
        $config['allowed_types'] = 'docx'; // <-- Kunci: Hanya izinkan docx
        $config['max_size']      = 5120; // 5MB
        $config['encrypt_name']  = TRUE;
    
        if (!is_dir($config['upload_path'])) { mkdir($config['upload_path'], 0777, TRUE); }
        $this->load->library('upload', $config);
    
        if ($this->form_validation->run() == FALSE || !$this->upload->do_upload('file_path_initial')) {
            // Jika validasi atau upload gagal
            $errors = validation_errors() . ' ' . $this->upload->display_errors();
            $this->session->set_flashdata('error', $errors);
        } else {
            // Jika berhasil, siapkan data untuk model
            $upload_data = $this->upload->data();
            
            // Data untuk tbl_papers
            $paper_data = [
                'registration_id'   => $registration_id,
                'topic_id'          => $this->input->post('topic_id'),
                'judul'             => $this->input->post('judul'),
                'abstrak'           => $this->input->post('abstrak'),
                'kata_kunci'        => $this->input->post('kata_kunci'),
                'file_path_initial' => 'uploads/papers/' . $upload_data['file_name'],
                'status_artikel'    => 'submitted'
            ];
            
            // Data untuk tbl_authors
            $authors_data = [];
            // 1. Tambahkan penulis utama
            $authors_data[] = [
                'nama_depan' => $this->input->post('main_author_firstname'),
                'nama_belakang' => $this->input->post('main_author_lastname'),
                'email' => $this->input->post('main_author_email'),
                'afiliasi' => $this->input->post('main_author_affiliation'),
                'negara' => 'Indonesia', // Ganti jika ada input negara
                'is_corresponding_author' => 1,
                'is_presenting_author' => 1
            ];
    
            // 2. Tambahkan penulis lainnya (jika ada)
            $other_firstnames = $this->input->post('author_firstname');
            if (!empty($other_firstnames)) {
                for ($i=0; $i < count($other_firstnames); $i++) {
                    if(!empty($other_firstnames[$i])) { // Hanya proses jika nama depan diisi
                        $authors_data[] = [
                            'nama_depan' => $this->input->post('author_firstname')[$i],
                            'nama_belakang' => $this->input->post('author_lastname')[$i],
                            'email' => $this->input->post('author_email')[$i],
                            'afiliasi' => $this->input->post('author_affiliation')[$i],
                            'negara' => 'Indonesia',
                            'is_corresponding_author' => 0,
                            'is_presenting_author' => 0
                        ];
                    }
                }
            }
            
            // Panggil model untuk menyimpan semuanya
            if ($this->Article_model->submit_initial_paper_with_authors($paper_data, $authors_data)) {
                $this->session->set_flashdata('success', 'Artikel Anda berhasil disubmit dan sedang menunggu proses review.');
            } else {
                $this->session->set_flashdata('error', 'Terjadi kesalahan pada database saat menyimpan data.');
            }
        }
        
        redirect('user/article/index/' . $registration_id);
    }
}