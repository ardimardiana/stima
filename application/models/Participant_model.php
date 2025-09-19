<?php
class Participant_model extends CI_Model {
    
    /**
     * Mengambil daftar email & nama unik dari semua pendaftar di sebuah event.
     * @param int $event_id
     * @return array
     */
    public function get_unique_participants_for_mailing($event_id) {
        // -- PERBAIKAN DI SINI --
        // Tulis "DISTINCT" langsung di dalam string select, tanpa dipisahkan koma
        $this->db->select('DISTINCT u.email, u.nama_depan', FALSE);
        
        $this->db->from('tbl_users u');
        $this->db->join('tbl_event_registrations er', 'u.user_id = er.user_id');
        $this->db->where('er.event_id', $event_id);
        return $this->db->get()->result();
    }
    
    public function get_attendees_for_export($event_id) {
        $this->db->select(
            'er.registration_id, u.nama_depan, u.nama_belakang, u.email, er.peran_event'
        );
        $this->db->from('tbl_event_registrations er');
        $this->db->join('tbl_users u', 'er.user_id = u.user_id');
        $this->db->where('er.event_id', $event_id);
        $this->db->where('er.status_kehadiran', 1); // <-- Kunci utamanya di sini
        $this->db->order_by('u.nama_depan', 'ASC');
    
        return $this->db->get()->result_array(); // Ambil sebagai array untuk CSV
    }
    
    public function get_all_participants_for_report($event_id) {
    $this->db->select(
            'u.nama_depan, u.nama_belakang, u.email, u.afiliasi,' .
            'er.peran_event, er.status_kehadiran, ' .
            'p.status_pembayaran'
        );
        $this->db->from('tbl_event_registrations er');
        $this->db->join('tbl_users u', 'er.user_id = u.user_id');
        $this->db->join('tbl_payments p', 'er.registration_id = p.registration_id');
        $this->db->where('er.event_id', $event_id);
        $this->db->order_by('u.nama_depan', 'ASC');
    
        return $this->db->get()->result();
    }

    public function get_participants_by_event($event_id, $filter_status = NULL) {
        $this->db->select('u.nama_depan, u.nama_belakang, u.email, er.peran_event, p.payment_id, p.status_pembayaran, p.tgl_unggah');
        $this->db->from('tbl_event_registrations er');
        $this->db->join('tbl_users u', 'er.user_id = u.user_id');
        $this->db->join('tbl_payments p', 'er.registration_id = p.registration_id');
        $this->db->where('er.event_id', $event_id);

        if ($filter_status) {
            $this->db->where('p.status_pembayaran', $filter_status);
        }

        $this->db->order_by('p.tgl_unggah', 'ASC'); // Tampilkan yang upload duluan di atas
        return $this->db->get()->result();
    }
    
    public function get_payment_detail($payment_id) {
        $this->db->select('u.nama_depan, u.nama_belakang, u.email, er.peran_event, p.*, e.event_id, e.nama_event');
        $this->db->from('tbl_payments p');
        $this->db->join('tbl_event_registrations er', 'p.registration_id = er.registration_id');
        $this->db->join('tbl_users u', 'er.user_id = u.user_id');
        $this->db->join('tbl_events e', 'er.event_id = e.event_id');
        $this->db->where('p.payment_id', $payment_id);
        return $this->db->get()->row();
    }
}