<?php
class Dashboard_model extends CI_Model {

    public function count_total_users() {
        return $this->db->count_all('tbl_users');
    }

    public function count_total_events() {
        return $this->db->count_all('tbl_events');
    }
    
    // Fungsi untuk menghitung pendaftar di event yang sedang aktif
    public function count_participants_active_event() {
        $this->db->select('event_id');
        $active_event = $this->db->get_where('tbl_events', ['status' => 'aktif'], 1)->row();

        if ($active_event) {
            $this->db->where('event_id', $active_event->event_id);
            return $this->db->count_all_results('tbl_event_registrations');
        }
        return 0;
    }

    // Fungsi untuk menghitung paper yang disubmit di event yang sedang aktif
    public function count_papers_active_event() {
        $this->db->select('e.event_id');
        $this->db->from('tbl_events e');
        $this->db->where('e.status', 'aktif');
        $active_event = $this->db->get(null, 1)->row();

        if ($active_event) {
            $this->db->from('tbl_papers p');
            $this->db->join('tbl_event_registrations er', 'p.registration_id = er.registration_id');
            $this->db->where('er.event_id', $active_event->event_id);
            return $this->db->count_all_results();
        }
        return 0;
    }
}