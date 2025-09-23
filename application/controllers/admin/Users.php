<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Pastikan controller ini mewarisi Admin_Controller, bukan CI_Controller
class Users extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }
    
    // Tambahkan fungsi ini di dalam class Users
    public function process_manual_registration() {
        $user_id = $this->input->post('user_id');
        $event_id = $this->input->post('event_id');
        $peran = $this->input->post('peran_event');
        $status_pembayaran = $this->input->post('status_pembayaran');
    
        // Cek duplikasi
        if ($this->User_model->check_existing_registration($user_id, $event_id, $peran) > 0) {
            $this->session->set_flashdata('error', 'Pengguna ini sudah terdaftar di event tersebut dengan peran yang sama.');
            redirect('admin/users');
            return;
        }
    
        $this->db->trans_start();
        // 1. Insert ke tbl_event_registrations
        $reg_data = ['user_id' => $user_id, 'event_id' => $event_id, 'peran_event' => $peran];
        $this->db->insert('tbl_event_registrations', $reg_data);
        $registration_id = $this->db->insert_id();
    
        // 2. Insert ke tbl_payments
        $payment_data = [
            'registration_id' => $registration_id,
            'nomor_invoice' => 'INV-MANUAL-' . $event_id . '-' . $registration_id,
            'status_pembayaran' => $status_pembayaran
        ];
        // Jika admin set 'lunas', langsung isi data validasi
        if ($status_pembayaran == 'lunas') {
            $payment_data['validator_id'] = $this->session->userdata('user_id');
            $payment_data['tgl_validasi'] = date('Y-m-d H:i:s');
        }
        $this->db->insert('tbl_payments', $payment_data);
        $this->db->trans_complete();
    
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Gagal mendaftarkan pengguna.');
        } else {
            $this->session->set_flashdata('success', 'Pengguna berhasil didaftarkan ke event.');
        }
        redirect('admin/users');
    }
    
    // Tambahkan fungsi ini di dalam class Users
    public function register_to_event($user_id) {
        $data['title'] = 'Daftarkan Pengguna ke Event';
        $this->load->model('Event_model');
        
        $data['user_to_register'] = $this->User_model->get_user_by_id($user_id);
        $data['events'] = $this->Event_model->get_all_events();
    
        $this->load->view('admin/_partials/_header', $data);
        $this->load->view('admin/users/register_event', $data);
        $this->load->view('admin/_partials/_footer');
    }

    // Menampilkan daftar semua pengguna
    public function index() {
        $data['users'] = $this->User_model->get_all_users();
        $data['title'] = 'Manajemen Pengguna';

        // Memuat semua bagian template
        $this->load->view('admin/_partials/_header', $data);
        $this->load->view('admin/users/index', $data); // Ini adalah konten spesifik halaman
        $this->load->view('admin/_partials/_footer');
    }

    // Mengedit pengguna
    public function edit($user_id) {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nama_depan', 'Nama Depan', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('peran_sistem', 'Peran Sistem', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['user'] = $this->User_model->get_user_by_id($user_id);
            $data['title'] = 'Edit Pengguna';
            $this->load->view('admin/_partials/_header', $data);
            $this->load->view('admin/users/edit', $data); // Load view form edit
            $this->load->view('admin/_partials/_footer');
        } else {
            $data_update = [
                'nama_depan'    => $this->input->post('nama_depan'),
                'nama_belakang' => $this->input->post('nama_belakang'),
                'email'         => $this->input->post('email'),
                'afiliasi'      => $this->input->post('afiliasi'),
                'negara'        => $this->input->post('negara'),
                'peran_sistem'  => $this->input->post('peran_sistem')
            ];

            $this->User_model->update_user($user_id, $data_update);
            $this->session->set_flashdata('success', 'Data pengguna berhasil diperbarui.');
            redirect('admin/users');
        }
    }

    // Menghapus pengguna
    public function delete($user_id) {
        // Tambahkan validasi keamanan di sini, misal cek token CSRF
        $this->User_model->delete_user($user_id);
        $this->session->set_flashdata('success', 'Pengguna berhasil dihapus.');
        redirect('admin/users');
    }
}