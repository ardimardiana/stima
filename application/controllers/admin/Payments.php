<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Include Composer's autoloader
//require FCPATH . 'vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class Payments extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Payment_model');
        // (Load juga model Event dan User jika perlu)
    }

    // Halaman untuk memvalidasi pembayaran
    public function validate($payment_id) {
        $payment = $this->Payment_model->get_payment_by_id($payment_id); // Asumsi fungsi ini ada
        // ... (Tampilkan view dengan detail pembayaran & bukti bayar) ...
    }

    // Aksi untuk menyetujui atau menolak
    public function set_status($payment_id, $status) {
        $admin_id = $this->session->userdata('user_id');
        $payment = $this->Payment_model->get_payment_by_id($payment_id);
        
        if ($status == 'lunas') {
            // Jika disetujui, update status & generate QR Code
            $this->Payment_model->set_payment_status($payment_id, 'lunas', $admin_id);
            $this->_generate_qr_code($payment->registration_id);
            $this->session->set_flashdata('success', 'Pembayaran berhasil divalidasi.');

        } elseif ($status == 'ditolak') {
            $this->Payment_model->set_payment_status($payment_id, 'ditolak', $admin_id);
            $this->session->set_flashdata('success', 'Pembayaran telah ditolak.');
        }

        // Redirect kembali ke halaman daftar pembayaran
        redirect('admin/events/manage/' . $payment->event_id); 
    }

    private function _generate_qr_code($registration_id) {
        // Data unik untuk QR Code
        $registration_data = $this->db->get_where('tbl_event_registrations', ['registration_id' => $registration_id])->row();
        $qr_data = $registration_data->kode_kehadiran_qr; // Gunakan kode unik yang sudah ada

        if (empty($qr_data)) {
            // Jika kode belum ada, buat yang baru
            $qr_data = 'REG-' . $registration_id . '-' . uniqid();
            $this->db->where('registration_id', $registration_id)->update('tbl_event_registrations', ['kode_kehadiran_qr' => $qr_data]);
        }

        // Buat objek QR Code
        $qrCode = QrCode::create($qr_data);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Simpan file QR Code
        $path = 'uploads/qrcodes/';
        if (!is_dir($path)) { mkdir($path, 0777, TRUE); }
        $filename = $registration_id . '.png';
        $result->saveToFile($path . $filename);

        // Simpan path ke database
        $this->db->where('registration_id', $registration_id);
        $this->db->update('tbl_event_registrations', ['qr_code_path' => $path . $filename]);
    }
}