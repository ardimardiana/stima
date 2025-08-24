<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Sertakan autoloader Composer untuk library QR Code
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class Participants extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Participant_model');
        $this->load->model('Event_model');
        $this->load->model('Payment_model');
    }

    // Menampilkan daftar peserta untuk divalidasi
    public function index($event_id, $status = 'validasi') {
        $data['title'] = 'Validasi Pembayaran';
        $data['event'] = $this->Event_model->get_event_by_id($event_id);
        $data['participants'] = $this->Participant_model->get_participants_by_event($event_id, $status);
        $data['status_filter'] = $status; // Untuk menandai tab aktif

        $this->load->view('admin/_partials/_header', $data);
        $this->load->view('admin/participants/index', $data);
        $this->load->view('admin/_partials/_footer');
    }
    
    // Menampilkan halaman detail untuk validasi
    public function detail($payment_id) {
        $data['title'] = 'Detail Validasi Pembayaran';
        $data['payment'] = $this->Participant_model->get_payment_detail($payment_id);
        
        $this->load->view('admin/_partials/_header', $data);
        $this->load->view('admin/participants/detail', $data);
        $this->load->view('admin/_partials/_footer');
    }

    // Memproses Aksi Validasi (Setuju/Tolak)
    public function process_validation() {
        $payment_id = $this->input->post('payment_id');
        $action = $this->input->post('action'); // 'lunas' atau 'ditolak'
        $admin_id = $this->session->userdata('user_id');
    
        $payment = $this->Participant_model->get_payment_detail($payment_id);
        
        if ($action == 'lunas') {
            $this->Payment_model->set_payment_status($payment_id, 'lunas', $admin_id);
            $this->_generate_qr_code($payment->registration_id);
    
            // -- PANGGIL FUNGSI EMAIL --
            $this->_send_payment_notification_email($payment, 'lunas');
    
            $this->session->set_flashdata('success', 'Pembayaran berhasil divalidasi. Notifikasi telah dikirim ke peserta.');
    
        } else { // 'ditolak'
            $this->Payment_model->set_payment_status($payment_id, 'ditolak', $admin_id);
            
            // -- PANGGIL FUNGSI EMAIL --
            $this->_send_payment_notification_email($payment, 'ditolak');
    
            $this->session->set_flashdata('warning', 'Pembayaran telah ditolak. Notifikasi telah dikirim ke peserta.');
        }
    
        redirect('admin/participants/index/' . $payment->event_id);
    }
    
    private function _send_payment_notification_email($payment_data, $status) {
        $config = Array(
			'mailtype'  => 'html', 
			'charset'   => 'iso-8859-1'
		);
		$this->load->library('email', $config);
    
        $recipient_email = $payment_data->email;
        $recipient_name = $payment_data->nama_depan;
        $event_name = $payment_data->nama_event;
        $invoice_number = $payment_data->nomor_invoice;
    
        $subject = '';
        $message = "Halo {$recipient_name},<br><br>";
        $message .= "Kami memberitahukan status terbaru untuk pembayaran Anda (Invoice: <strong>{$invoice_number}</strong>) untuk acara <strong>{$event_name}</strong>.<br><br>";
    
        // Tentukan isi email berdasarkan status
        if ($status == 'lunas') {
            $subject = 'Pembayaran Anda Telah Dikonfirmasi';
            $message .= "<div style='padding:15px; border-left: 5px solid #198754; background-color:#e9f7ef;'>";
            $message .= "<strong>Status: LUNAS</strong><br>";
            $message .= "Terima kasih, pembayaran Anda telah kami verifikasi. QR Code untuk kehadiran kini telah tersedia di dasbor Anda.";
            $message .= "</div>";
        } else { // 'ditolak'
            $subject = 'Informasi Mengenai Pembayaran Anda';
            $message .= "<div style='padding:15px; border-left: 5px solid #dc3545; background-color:#fbe9ea;'>";
            $message .= "<strong>Status: DITOLAK</strong><br>";
            $message .= "Mohon maaf, pembayaran Anda tidak dapat kami verifikasi saat ini. Kemungkinan penyebabnya adalah bukti transfer tidak jelas, jumlah tidak sesuai, atau lainnya.<br>";
            $message .= "Silakan login kembali ke dasbor Anda untuk mengunggah ulang bukti pembayaran yang benar.";
            $message .= "</div>";
        }
        
        $dashboard_link = site_url('user/dashboard');
        $message .= "<br>Silakan akses dasbor Anda melalui tautan berikut:<br>";
        $message .= "<a href='{$dashboard_link}'>{$dashboard_link}</a><br><br>";
        $message .= "Hormat kami,<br>Panitia Penyelenggara";
    
        $this->email->from('no-reply@stima.unma.ac.id', 'Panitia ' . $event_name);
        $this->email->to($recipient_email);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->send();
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