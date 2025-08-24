<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room_model extends CI_Model {

    public function get_by_event($event_id) {
        $this->db->where('event_id', $event_id);
        $this->db->order_by('nama_ruang', 'ASC');
        return $this->db->get('tbl_rooms')->result();
    }

    public function get_by_id($room_id) {
        return $this->db->get_where('tbl_rooms', ['room_id' => $room_id])->row();
    }

    public function insert($data) {
        return $this->db->insert('tbl_rooms', $data);
    }

    public function update($room_id, $data) {
        $this->db->where('room_id', $room_id);
        return $this->db->update('tbl_rooms', $data);
    }

    public function delete($room_id) {
        return $this->db->delete('tbl_rooms', ['room_id' => $room_id]);
    }
}