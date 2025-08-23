<?php
class Event_manager_model extends CI_Model {

    public function get_event_stats($event_id) {
        $stats = [];

        // Hitung total pendaftar (peserta + presenter)
        $this->db->where('event_id', $event_id);
        $stats['total_participants'] = $this->db->count_all_results('tbl_event_registrations');

        // Hitung total pembayaran menunggu validasi
        $this->db->from('tbl_payments p');
        $this->db->join('tbl_event_registrations er', 'p.registration_id = er.registration_id');
        $this->db->where('er.event_id', $event_id);
        $this->db->where('p.status_pembayaran', 'validasi');
        $stats['payments_to_validate'] = $this->db->count_all_results();
        
        // Hitung total artikel yang masuk
        $this->db->from('tbl_papers pa');
        $this->db->join('tbl_event_registrations er', 'pa.registration_id = er.registration_id');
        $this->db->where('er.event_id', $event_id);
        $stats['total_papers'] = $this->db->count_all_results();

        return (object)$stats;
    }
}