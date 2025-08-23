<?php
class Announcement_model extends CI_Model {
    
    public function get_for_active_event() {
        // 1. Cari dulu event_id dari event yang aktif
        $this->db->select('event_id');
        $active_event = $this->db->get_where('tbl_events', ['status' => 'aktif'], 1)->row();

        if ($active_event) {
            // 2. Jika ada event aktif, panggil fungsi get_by_event dengan id tersebut
            return $this->get_by_event($active_event->event_id);
        }

        // 3. Jika tidak ada event aktif, kembalikan array kosong
        return [];
    }

    public function get_by_event($event_id) {
        $this->db->where('event_id', $event_id);
        $this->db->order_by('tgl_publish', 'DESC');
        return $this->db->get('tbl_pengumuman')->result();
    }

    public function get_by_id($pengumuman_id) {
        return $this->db->get_where('tbl_pengumuman', ['pengumuman_id' => $pengumuman_id])->row();
    }

    public function insert($data) {
        return $this->db->insert('tbl_pengumuman', $data);
    }

    public function update($pengumuman_id, $data) {
        $this->db->where('pengumuman_id', $pengumuman_id);
        return $this->db->update('tbl_pengumuman', $data);
    }

    public function delete($pengumuman_id) {
        return $this->db->delete('tbl_pengumuman', ['pengumuman_id' => $pengumuman_id]);
    }
}