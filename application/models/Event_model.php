<?php
class Event_model extends CI_Model {
    
    /**
    * Mengambil data event berdasarkan paper_id.
     * Berguna untuk mencari tahu sebuah paper milik event yang mana.
     * @param int $paper_id
     * @return object|null
     */
    public function get_event_by_id_from_paper($paper_id) {
        $this->db->select('e.*'); // Ambil semua data dari tabel events
        $this->db->from('tbl_events e');
        $this->db->join('tbl_event_registrations er', 'e.event_id = er.event_id');
        $this->db->join('tbl_papers p', 'er.registration_id = p.registration_id');
        $this->db->where('p.paper_id', $paper_id);
        
        return $this->db->get()->row();
    }
    
    public function get_archived_events() {
        $this->db->where('status !=', 'aktif');
        $this->db->order_by('tahun', 'DESC');
        return $this->db->get('tbl_events')->result();
    }

    // FUNGSI BARU: Mengambil satu event berdasarkan slug/url-nya
    public function get_event_by_slug($slug) {
        return $this->db->get_where('tbl_events', ['slug_url' => $slug])->row();
    }

    public function get_all_events() {
        $this->db->order_by('tahun', 'DESC');
        return $this->db->get('tbl_events')->result();
    }

    public function get_event_by_id($event_id) {
        return $this->db->get_where('tbl_events', ['event_id' => $event_id])->row();
    }

    public function insert_event($data) {
        return $this->db->insert('tbl_events', $data);
    }

    public function update_event($event_id, $data) {
        $this->db->where('event_id', $event_id);
        return $this->db->update('tbl_events', $data);
    }

    public function delete_event($event_id) {
        return $this->db->delete('tbl_events', ['event_id' => $event_id]);
    }
    
    // Fungsi untuk mengaktifkan satu event dan menonaktifkan yang lain
    public function set_active_event($event_id) {
        // 1. Nonaktifkan semua event terlebih dahulu
        $this->db->update('tbl_events', ['status' => 'arsip']);
        
        // 2. Aktifkan event yang dipilih
        $this->db->where('event_id', $event_id);
        return $this->db->update('tbl_events', ['status' => 'aktif']);
    }

    // Fungsi get_active_event yang sudah ada sebelumnya
    public function get_active_event() {
        return $this->db->get_where('tbl_events', ['status' => 'aktif'], 1)->row();
    }
}