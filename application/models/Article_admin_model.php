<?php
class Article_admin_model extends CI_Model {
    
    /**
     * Mengambil detail presenter (nama, email, registration_id) berdasarkan paper_id.
     * @param int $paper_id
     * @return object|null
     */
    public function get_presenter_details_by_paper($paper_id) {
        $this->db->select('u.nama_depan, u.email, er.registration_id');
        $this->db->from('tbl_users u');
        $this->db->join('tbl_event_registrations er', 'u.user_id = er.user_id');
        $this->db->join('tbl_papers p', 'er.registration_id = p.registration_id');
        $this->db->where('p.paper_id', $paper_id);
        return $this->db->get()->row();
    }
    
    /**
     * Menugaskan reviewer baru ke sebuah artikel.
     * @param array $review_data Data untuk tbl_reviews
     * @param int $paper_id ID paper yang direview
     * @return array|false Mengembalikan data review jika sukses, false jika gagal.
     */
    public function assign_reviewer($review_data, $paper_id) {
        // 1. Buat token unik yang aman
        $review_data['reviewer_token'] = bin2hex(random_bytes(32));
        
        $this->db->trans_start();
    
        // 2. Insert data penugasan review baru
        $this->db->insert('tbl_reviews', $review_data);
        
        // 3. Ubah status artikel menjadi 'in_review'
        $this->update_article_status($paper_id, 'in_review');
    
        $this->db->trans_complete();
    
        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            // Kembalikan data yang baru di-insert (termasuk token) untuk dikirim via email
            return $review_data; 
        }
    }
    
    public function update_article_status($paper_id, $status) {
        $this->db->where('paper_id', $paper_id);
        return $this->db->update('tbl_papers', ['status_artikel' => $status]);
    }
    
    public function get_article_details($paper_id) {
        // Ambil data paper utama
        $this->db->select('p.*, t.nama_topik');
        $this->db->from('tbl_papers p');
        $this->db->join('tbl_topics t', 'p.topic_id = t.topic_id');
        $this->db->where('p.paper_id', $paper_id);
        $details['paper'] = $this->db->get()->row();
    
        if (!$details['paper']) return null;
    
        // Ambil data penulis
        $this->db->where('paper_id', $paper_id);
        $details['authors'] = $this->db->get('tbl_authors')->result();
        
        // Ambil data review
        $this->db->where('paper_id', $paper_id);
        $details['reviews'] = $this->db->get('tbl_reviews')->result();
    
        return $details;
    }
    
    public function get_articles_for_export($event_id) {
        $this->db->select('p.judul, p.status_artikel, u.nama_depan, u.nama_belakang, t.nama_topik');
        $this->db->from('tbl_papers p');
        $this->db->join('tbl_event_registrations er', 'p.registration_id = er.registration_id');
        $this->db->join('tbl_users u', 'er.user_id = u.user_id');
        $this->db->join('tbl_topics t', 'p.topic_id = t.topic_id');
        $this->db->where('er.event_id', $event_id);
        return $this->db->get()->result_array();
    }
    
    public function get_articles_by_event($event_id, $status_filter = NULL) {
        $this->db->select('p.paper_id, p.judul, p.status_artikel, p.file_path_initial, p.file_path_final, p.slide_path, u.nama_depan, u.nama_belakang, t.nama_topik');
        $this->db->from('tbl_papers p');
        $this->db->join('tbl_event_registrations er', 'p.registration_id = er.registration_id');
        $this->db->join('tbl_users u', 'er.user_id = u.user_id');
        $this->db->join('tbl_topics t', 'p.topic_id = t.topic_id');
        $this->db->where('er.event_id', $event_id);
        
        if($status_filter) {
            $this->db->where('p.status_artikel', $status_filter);
        }
        
        $this->db->order_by('p.tgl_submit', 'DESC');
        return $this->db->get()->result();
    }

}