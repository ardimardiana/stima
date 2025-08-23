<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Topic_model extends CI_Model {

    public function get_by_event($event_id) {
        $this->db->where('event_id', $event_id);
        $this->db->order_by('nama_topik', 'ASC');
        return $this->db->get('tbl_topics')->result();
    }

    public function get_by_id($topic_id) {
        return $this->db->get_where('tbl_topics', ['topic_id' => $topic_id])->row();
    }

    public function insert($data) {
        return $this->db->insert('tbl_topics', $data);
    }

    public function update($topic_id, $data) {
        $this->db->where('topic_id', $topic_id);
        return $this->db->update('tbl_topics', $data);
    }

    public function delete($topic_id) {
        return $this->db->delete('tbl_topics', ['topic_id' => $topic_id]);
    }
}