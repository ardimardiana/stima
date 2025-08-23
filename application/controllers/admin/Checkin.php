<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkin extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Event_model');
    }

    // Menampilkan halaman scanner
    public function index($event_id) {
        $data['title'] = 'QR Code Scanner Kehadiran';
        $data['event'] = $this->Event_model->get_event_by_id($event_id);
        
        $this->load->view('admin/_partials/_header', $data);
        $this->load->view('admin/checkin/index', $data);
        $this->load->view('admin/_partials/_footer');
    }

    // Memproses data QR yang discan
    public function process() {
        $qr_code = $this->input->post('qr_code');
        $event_id = $this->input->post('event_id');

        // Cari pendaftaran berdasarkan kode QR dan event_id
        $this->db->select('er.registration_id, er.status_kehadiran, u.nama_depan, u.nama_belakang, u.email');
        $this->db->from('tbl_event_registrations er');
        $this->db->join('tbl_users u', 'er.user_id = u.user_id');
        $this->db->where('er.kode_kehadiran_qr', $qr_code);
        $this->db->where('er.event_id', $event_id);
        $registration = $this->db->get()->row();

        if ($registration) {
            if ($registration->status_kehadiran == 1) {
                // Jika sudah pernah check-in (tidak ada perubahan)
                $response = ['status' => 'warning', 'message' => 'Peserta ini SUDAH check-in sebelumnya.', 'name' => $registration->nama_depan];
            } else {
                // Jika belum, update statusnya
                $this->db->where('registration_id', $registration->registration_id);
                $this->db->update('tbl_event_registrations', ['status_kehadiran' => 1]);
    
                // -- PERUBAHAN DI SINI: Panggil fungsi kirim email --
                $event_data = $this->Event_model->get_event_by_id($event_id);
                $this->_send_checkin_confirmation_email($registration, $event_data);
                
                $response = ['status' => 'success', 'message' => 'Check-in BERHASIL! Email konfirmasi telah dikirim.', 'name' => $registration->nama_depan];
            }
        } else {
            // Jika QR code tidak ditemukan/tidak valid (tidak ada perubahan)
            $response = ['status' => 'error', 'message' => 'QR Code tidak valid atau bukan untuk event ini.', 'name' => 'Tidak Dikenal'];
        }
    
        // Kembalikan response dalam format JSON (tidak ada perubahan)
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    private function _send_checkin_confirmation_email($participant_data, $event_data)
    {
        $config = Array(
			'mailtype'  => 'html', 
			'charset'   => 'iso-8859-1'
		);
		$this->load->library('email', $config);
    
        $recipient_email = $participant_data->email;
        $recipient_name = $participant_data->nama_depan;
        $event_name = $event_data->nama_event;
    
        $this->email->from('no-reply@stima.unma.ac.id', 'Panitia ' . $event_name);
        $this->email->to($recipient_email);
        $this->email->subject('Konfirmasi Kehadiran di ' . $event_name);
    
        // Isi Pesan Email
        $message = "Halo {$recipient_name},<br><br>";
        $message .= "Kami mengonfirmasi bahwa Anda telah berhasil melakukan check-in kehadiran untuk acara <strong>{$event_name}</strong>.<br>";
        $message .= "Waktu check-in Anda tercatat pada: " . date('d F Y, H:i:s') . " WIB.<br><br>";
        $message .= "Terima kasih atas partisipasi Anda.<br><br>";
        $message .= "Hormat kami,<br>Panitia Penyelenggara";
    
        $this->email->message($message);
    
        // Kirim email
        $this->email->send();
    }
}