<?php
class Email_queue_model extends CI_Model {
    public function add_batch_to_queue($data) {
        if (empty($data)) {
            return false;
        }
        return $this->db->insert_batch('tbl_email_queue', $data);
    }
    
    public function get_pending_emails($limit) {
        $this->db->where('status!="sent"');
        $this->db->limit($limit);
        return $this->db->get('tbl_email_queue')->result();
    }
    
    public function update_queue_status($queue_id, $status) {
        $data = [
            'status' => $status,
            'sent_at' => date('Y-m-d H:i:s')
        ];
        $this->db->where('queue_id', $queue_id);
        return $this->db->update('tbl_email_queue', $data);
    }
}