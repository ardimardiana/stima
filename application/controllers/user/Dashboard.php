<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends User_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model'); // Load model baru kita
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');

        $data['title'] = 'Dashboard Peserta';
        $data['nama_user'] = $this->session->userdata('nama');

        // Ambil data dari model
        $data['active_events'] = $this->User_model->get_active_events_with_user_status($user_id);
        $data['history_events'] = $this->User_model->get_user_event_history($user_id);

        $this->load->view('user/dashboard/index', $data);
    }

    public function register_for_event() {
        $user_id = $this->session->userdata('user_id');
        $event_id = $this->input->post('event_id');
        $peran = $this->input->post('peran');
    
        if (empty($event_id) || empty($peran) || !in_array($peran, ['peserta', 'presenter'])) {
            $this->session->set_flashdata('error', 'Terjadi kesalahan, silakan coba lagi.');
            redirect('user/dashboard');
        }
    
        // --- PERUBAHAN DIMULAI DI SINI ---
        
        // Mulai database transaction
        $this->db->trans_start();
    
        // 1. Masukkan data ke tbl_event_registrations
        $reg_data = [
            'user_id' => $user_id,
            'event_id' => $event_id,
            'peran_event' => $peran,
            'tanggal_registrasi' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('tbl_event_registrations', $reg_data);
        $registration_id = $this->db->insert_id(); // Ambil ID dari data yang baru dimasukkan
    
        // 2. Buat record pembayaran awal di tbl_payments
        $payment_data = [
            'registration_id' => $registration_id,
            'nomor_invoice' => 'INV-' . $event_id . '-' . $registration_id, // Buat nomor invoice unik
            'status_pembayaran' => 'menunggu'
        ];
        $this->db->insert('tbl_payments', $payment_data);
    
        // Selesaikan database transaction
        $this->db->trans_complete();
        
        // Cek apakah transaksi berhasil
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Gagal mendaftar di event.');
        } else {
            $this->session->set_flashdata('success', 'Anda berhasil terdaftar di event. Silakan lanjutkan ke tahap pembayaran.');
        }
    
        redirect('user/dashboard');
    }
}