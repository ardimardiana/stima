<?php
class Chat_model extends CI_Model {

    public function get_messages($paper_id) {
        $this->db->where('paper_id', $paper_id);
        $this->db->order_by('timestamp', 'ASC');
        return $this->db->get('tbl_paper_chats')->result();
    }

    public function post_message($data) {
        return $this->db->insert('tbl_paper_chats', $data);
    }
}