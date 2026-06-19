<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends CI_Controller {
    
    public function index($mayar_invoice_id) {
        $payment = $this->db->get_where('tbl_payments', ['mayar_invoice_id' => $mayar_invoice_id])->row();
        
        if ($payment && $payment->mayar_link) {
            // Langsung arahkan (redirect) pengguna ke halaman Mayar
            redirect($payment->mayar_link);
        } else {
            show_404();
        }
    }
}