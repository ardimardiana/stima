<?php
class Payment_model extends CI_Model {

    // Mengambil data pembayaran berdasarkan registration_id
    public function get_payment_by_registration($reg_id) {
        $this->db->select('p.*, er.peran_event');
        $this->db->from('tbl_payments p');
        $this->db->join('tbl_event_registrations er', 'p.registration_id = er.registration_id');
        $this->db->where('p.registration_id', $reg_id);
        return $this->db->get()->row();
    }
    
    // Mengambil data event untuk info pembayaran
    public function get_event_info_by_registration($reg_id) {
        $this->db->select('e.info_pembayaran');
        $this->db->from('tbl_events e');
        $this->db->join('tbl_event_registrations er', 'e.event_id = er.event_id');
        $this->db->where('er.registration_id', $reg_id);
        return $this->db->get()->row();
    }

    // Update path bukti bayar dan status
    public function update_payment_proof($payment_id, $file_path) {
        $data = [
            'bukti_bayar_path' => $file_path,
            'status_pembayaran' => 'validasi', // Status berubah menjadi 'menunggu validasi'
            'tgl_unggah' => date('Y-m-d H:i:s')
        ];
        $this->db->where('payment_id', $payment_id);
        return $this->db->update('tbl_payments', $data);
    }
    
    public function set_payment_status($payment_id, $status, $admin_id)
    {
        // Data yang akan diupdate di tabel tbl_payments
        $data = [
            'status_pembayaran' => $status,
            'validator_id'      => $admin_id,
            'tgl_validasi'      => date('Y-m-d H:i:s')
        ];

        // Lakukan update berdasarkan payment_id
        $this->db->where('payment_id', $payment_id);
        return $this->db->update('tbl_payments', $data);
    }
}