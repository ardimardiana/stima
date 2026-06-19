<?php
class Cron extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Hanya izinkan eksekusi dari command line
        if (!$this->input->is_cli_request()) {
            exit('Akses Ditolak.');
        }
        $this->load->model('Email_queue_model');
        //$this->load->library('email');
    }

    // Fungsi yang akan dipanggil cron job
    public function send_emails($limit = 20) {
        $pending_emails = $this->Email_queue_model->get_pending_emails((int)$limit);
        //var_dump($pending_emails); exit;
        echo "Mencoba mengirim " . count($pending_emails) . " email...\n";
        
        foreach ($pending_emails as $mail) {
                $config = array(
                'mailtype'  => 'html', 
                'charset'   => 'utf-8', // Boleh juga menggunakan 'utf-8'
                'wordwrap'  => TRUE,
                'crlf'      => "\r\n", 
                'newline'   => "\r\n"
            );
        
            // 2. Load library email
            $this->load->library('email');
        
            // 3. Wajib gunakan initialize agar config benar-benar diterapkan
            $this->email->initialize($config);
            $this->email->clear(); // Bersihkan email dari iterasi sebelumnya
            $this->email->from('no-reply@stima.unma.ac.id', 'Panitia');
            $this->email->to($mail->recipient_email);
            $this->email->subject($mail->subject);
            $this->email->message($mail->message);

            if ($this->email->send()) {
                $this->Email_queue_model->update_queue_status($mail->queue_id, 'sent');
                echo "Email ke {$mail->recipient_email} terkirim.\n";
            } else {
                $this->Email_queue_model->update_queue_status($mail->queue_id, 'failed');
                echo "Email ke {$mail->recipient_email} GAGAL.\n";
                echo "DEBUG: " . $this->email->print_debugger(array('headers')) . "\n";
            }
        }
        echo "Cron job selesai: " . date('Y-m-d H:i:s') . "\n";
    }
}