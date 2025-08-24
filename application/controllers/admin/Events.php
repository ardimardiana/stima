<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Event_model');
    }

    public function index() {
        $data['title'] = 'Manajemen Event';
        $data['events'] = $this->Event_model->get_all_events();

        $this->load->view('admin/_partials/_header', $data);
        $this->load->view('admin/events/index', $data);
        $this->load->view('admin/_partials/_footer');
    }

    public function create() {
        $this->form_validation->set_rules('nama_event', 'Nama Event', 'required');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|numeric');
        $this->form_validation->set_rules('slug_url', 'Slug URL', 'required|alpha_dash|is_unique[tbl_events.slug_url]');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Buat Event Baru';
            $this->load->view('admin/_partials/_header', $data);
            $this->load->view('admin/events/form', $data);
            $this->load->view('admin/_partials/_footer');
        } else {
            $data = [
                'ketua_panitia' => $this->input->post('ketua_panitia'),
                'nama_event' => $this->input->post('nama_event'),
                'tagline' => $this->input->post('tagline'),
                'tahun' => $this->input->post('tahun'),
                'tgl_batas_daftar' => $this->input->post('tgl_batas_daftar'),
                'tgl_batas_submit' => $this->input->post('tgl_batas_submit'),
                'tgl_pengumuman' => $this->input->post('tgl_pengumuman'),
                'tgl_mulai_acara' => $this->input->post('tgl_mulai_acara'),
                'tgl_selesai_acara' => $this->input->post('tgl_selesai_acara'),
                'slug_url' => $this->input->post('slug_url'),
                'info_pembayaran' => $this->input->post('info_pembayaran'),
                'status' => 'draft'
            ];
            
            if (!empty($_FILES['header_loa']['name'])) {
                $config['upload_path']   = './uploads/loa_headers/';
                $config['allowed_types'] = 'jpg|png|jpeg';
                $config['max_size']      = 2048; // 2MB
                $config['encrypt_name']  = TRUE;
    
                if (!is_dir($config['upload_path'])) { mkdir($config['upload_path'], 0777, TRUE); }
    
                $this->load->library('upload', $config);
    
                if ($this->upload->do_upload('header_loa')) {
                    // Jika upload berhasil, tambahkan path file ke data
                    $upload_data = $this->upload->data();
                    $data['header_loa_path'] = 'uploads/loa_headers/' . $upload_data['file_name'];
                } else {
                    // Jika upload gagal, tampilkan error dan batalkan proses
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('admin/events/edit/' . $event_id);
                    return; // Hentikan eksekusi
                }
            }
            $this->Event_model->insert_event($data);
            $this->session->set_flashdata('success', 'Event baru berhasil dibuat.');
            redirect('admin/events');
        }
    }
    
    public function edit($event_id) {
        
        $this->form_validation->set_rules('nama_event', 'Nama Event', 'required');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|numeric');
        $this->form_validation->set_rules('slug_url', 'Slug URL', 'required|alpha_dash');
        
        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Edit Event';
            $data['event'] = $this->Event_model->get_event_by_id($event_id);
            $this->load->view('admin/_partials/_header', $data);
            $this->load->view('admin/events/form', $data);
            $this->load->view('admin/_partials/_footer');
        } else {
            // 1. Siapkan data teks terlebih dahulu
            $data_update = [
                'ketua_panitia' => $this->input->post('ketua_panitia'),
                'nama_event' => $this->input->post('nama_event'),
                'tagline' => $this->input->post('tagline'),
                'tahun' => $this->input->post('tahun'),
                'tgl_batas_daftar' => $this->input->post('tgl_batas_daftar'),
                'tgl_batas_submit' => $this->input->post('tgl_batas_submit'),
                'tgl_pengumuman' => $this->input->post('tgl_pengumuman'),
                'tgl_mulai_acara' => $this->input->post('tgl_mulai_acara'),
                'tgl_selesai_acara' => $this->input->post('tgl_selesai_acara'),
                'slug_url' => $this->input->post('slug_url'),
                'info_pembayaran' => $this->input->post('info_pembayaran'),
            ];
    
            // 2. Cek apakah ada file header baru yang diunggah
            if (!empty($_FILES['header_loa']['name'])) {
                
                $config['upload_path']   = './uploads/loa_headers/';
                $config['allowed_types'] = 'jpg|png|jpeg';
                $config['max_size']      = 2048; // 2MB
                $config['encrypt_name']  = TRUE;
    
                if (!is_dir($config['upload_path'])) { mkdir($config['upload_path'], 0777, TRUE); }
    
                $this->load->library('upload', $config);
    
                if ($this->upload->do_upload('header_loa')) {
                    // Jika upload berhasil, tambahkan path file ke data
                    $upload_data = $this->upload->data();
                    $data_update['header_loa_path'] = 'uploads/loa_headers/' . $upload_data['file_name'];
    
                    // Hapus file header lama untuk menghemat ruang
                    $old_header = $this->Event_model->get_event_by_id($event_id)->header_loa_path;
                    if (!empty($old_header) && file_exists($old_header)) {
                        unlink($old_header);
                    }
                } else {
                    // Jika upload gagal, tampilkan error dan batalkan proses
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('admin/events/edit/' . $event_id);
                    return; // Hentikan eksekusi
                }
            }
            
            // 3. Lakukan update ke database
            $this->Event_model->update_event($event_id, $data_update);
            $this->session->set_flashdata('success', 'Data event berhasil diperbarui.');
            redirect('admin/events');
        }
    }

    public function delete($event_id) {
        $this->Event_model->delete_event($event_id);
        $this->session->set_flashdata('success', 'Event berhasil dihapus.');
        redirect('admin/events');
    }

    public function set_active($event_id) {
        $this->Event_model->set_active_event($event_id);
        $this->session->set_flashdata('success', 'Event berhasil diaktifkan.');
        redirect('admin/events');
    }
}