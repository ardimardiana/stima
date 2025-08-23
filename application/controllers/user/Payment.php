<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends User_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Payment_model');
    }

    public function index($registration_id) {
        // Cek keamanan, pastikan registration_id ini milik user yang login
        // (Logika ini bisa ditambahkan nanti untuk hardening)

        $data['title'] = 'Detail Pembayaran';
        $data['payment'] = $this->Payment_model->get_payment_by_registration($registration_id);
        $data['event_info'] = $this->Payment_model->get_event_info_by_registration($registration_id);
        
        $this->load->view('user/payment/index', $data);
    }

    public function do_upload($payment_id) {
        $config['upload_path']   = './uploads/bukti_bayar/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
        $config['max_size']      = 2048; // 2MB
        $config['encrypt_name']  = TRUE;

        // Buat folder jika belum ada
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('bukti_bayar')) {
            $this->session->set_flashdata('error', $this->upload->display_errors());
        } else {
            $upload_data = $this->upload->data();
            $file_path = 'uploads/bukti_bayar/' . $upload_data['file_name'];
            
            $this->Payment_model->update_payment_proof($payment_id, $file_path);
            $this->session->set_flashdata('success', 'Bukti bayar berhasil diunggah. Mohon tunggu validasi dari panitia.');
        }
        redirect('user/dashboard');
    }
}