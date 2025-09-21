<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends User_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model'); // Load model baru kita
        $this->load->model('Event_model');
    }
    
        // Tambahkan fungsi baru ini di dalam class Dashboard
    public function cancel_registration($registration_id) {
        // Keamanan 1: Pastikan user adalah pemilik registrasi
        $user_id = $this->session->userdata('user_id');
        if (!$this->User_model->is_registration_owner($registration_id, $user_id)) {
            show_404(); 
            return;
        }
        
        // Keamanan 2: Ambil data pembayaran dan pastikan statusnya 'menunggu'
        $payment = $this->db->get_where('tbl_payments', ['registration_id' => $registration_id])->row();
    
        if ($payment && ($payment->status_pembayaran == 'menunggu' || $payment->status_pembayaran == 'ditolak')) {
            // Hapus data dari tbl_event_registrations.
            // Data di tbl_payments akan otomatis terhapus karena ON DELETE CASCADE.
            $this->db->delete('tbl_event_registrations', ['registration_id' => $registration_id]);
            $this->session->set_flashdata('success', 'Pendaftaran berhasil dibatalkan.');
        } else {
            $this->session->set_flashdata('error', 'Pendaftaran tidak dapat dibatalkan karena sudah dalam proses pembayaran atau validasi.');
        }
    
        redirect('user/dashboard');
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        $active_event = $this->Event_model->get_active_event(); // Asumsi fungsi ini ada
    
        if (!$active_event) { /* ... handle jika tidak ada event ... */ }
    
        $data['title'] = 'Dashboard Peserta';
        $data['nama_user'] = $this->session->userdata('nama');
        $data['active_event'] = $active_event;
    
        // Ambil data pendaftaran untuk setiap peran secara terpisah
        $data['registration_peserta'] = $this->User_model->get_user_registration_by_role($user_id, $active_event->event_id, 'peserta');
        $data['registration_presenter'] = $this->User_model->get_user_registration_by_role($user_id, $active_event->event_id, 'presenter');
        
        // Ambil data paper (jika ada) untuk logika konfirmasi kehadiran
        if($data['registration_presenter']){
            $this->load->model('Article_model');
            $data['paper_presenter'] = $this->Article_model->get_paper_by_registration($data['registration_presenter']->registration_id);
        } else {
            $data['paper_presenter'] = null;
        }
        
        $data['history_events'] = $this->User_model->get_user_event_history($user_id);
    
        $this->load->view('user/dashboard/index', $data);
    }
    
    // Tambahkan fungsi baru untuk konfirmasi kehadiran
    public function confirm_attendance() {
        $registration_id = $this->input->post('registration_id');
        $kehadiran = $this->input->post('kehadiran');
        
        // Keamanan: Pastikan registrasi ini milik user yang login
        $user_id = $this->session->userdata('user_id');
        if (!$this->User_model->is_registration_owner($registration_id, $user_id)) {
            show_404(); return;
        }
        
        // Update status konfirmasi
        $this->db->where('registration_id', $registration_id);
        $this->db->update('tbl_event_registrations', ['status_kehadiran' => $kehadiran]);
        
        $this->session->set_flashdata('success', 'Terima kasih telah memberikan konfirmasi kehadiran.');
        redirect('user/dashboard');
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