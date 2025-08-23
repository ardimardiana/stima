<?php
class Review_model extends CI_Model {

    // Mengambil semua data yang dibutuhkan untuk halaman review
    public function get_review_by_token($token) {
        $this->db->select('r.*, p.judul, p.abstrak, p.kata_kunci');
        $this->db->from('tbl_reviews r');
        $this->db->join('tbl_papers p', 'r.paper_id = p.paper_id');
        $this->db->where('r.reviewer_token', $token);
        return $this->db->get()->row();
    }
    
    // Mencatat waktu saat token pertama kali diakses
    public function record_first_access($token) {
        $data = [
            'token_first_accessed_at' => date('Y-m-d H:i:s'),
            'status_review' => 'opened' // Ubah status menjadi 'dibuka'
        ];
        $this->db->where('reviewer_token', $token);
        $this->db->update('tbl_reviews', $data);
    }
    
    // Menyimpan hasil isian form review
    public function submit_review_form($token, $data) {
        $this->db->where('reviewer_token', $token);
        return $this->db->update('tbl_reviews', $data);
    }
}