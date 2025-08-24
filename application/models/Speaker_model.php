<?php
class Speaker_model extends CI_Model {

    public function get_speakers_by_event($event_id) {
        $this->db->select('p.paper_id, u.nama_depan, u.nama_belakang, u.afiliasi, u.negara, u.photo_path, p.judul, p.abstrak');
        $this->db->from('tbl_users u');
        $this->db->join('tbl_event_registrations er', 'u.user_id = er.user_id');
        $this->db->join('tbl_papers p', 'er.registration_id = p.registration_id');
        $this->db->where('er.event_id', $event_id);
        $this->db->where('er.peran_event', 'presenter');
        // Hanya tampilkan presenter yang artikelnya sudah diterima
        $this->db->where_in('p.status_artikel', ['accepted', 'final_submitted']);
        $this->db->order_by('u.nama_depan', 'ASC');

        return $this->db->get()->result();
    }
}