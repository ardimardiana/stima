<?php
class Banner_model extends CI_Model {

    public function get_by_event($event_id) {
        $this->db->where('event_id', $event_id);
        $this->db->order_by('sort_order', 'ASC');
        return $this->db->get('tbl_event_banners')->result();
    }

    public function insert($data) {
        return $this->db->insert('tbl_event_banners', $data);
    }

    public function get_by_id($banner_id) {
        return $this->db->get_where('tbl_event_banners', ['banner_id' => $banner_id])->row();
    }
    
    public function delete($banner_id) {
        return $this->db->delete('tbl_event_banners', ['banner_id' => $banner_id]);
    }
}