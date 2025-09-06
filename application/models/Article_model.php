<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article_model extends CI_Model {
    
    public function update_loa_paper($paper_id,$loa_path) {
        $this->db->where('paper_id', $paper_id);
        $this->db->set('loa_path', $loa_path);
        return $this->db->update('tbl_papers');
    }
    
    /**
     * Mengambil data jadwal spesifik untuk sebuah paper.
     * @param int $paper_id
     * @return object|null
     */
    public function get_schedule_by_paper($paper_id) {
        $this->db->select('sess.nama_sesi, sess.waktu_mulai, sess.waktu_selesai, r.nama_ruang, r.lokasi');
        
        // Gunakan nama tabel baru: tbl_sessions
        $this->db->from('tbl_sessions sess');
        
        // Join dengan tabel ruangan
        $this->db->join('tbl_rooms r', 'sess.room_id = r.room_id');
        
        // Join dengan tabel penghubung baru: tbl_scheduled_papers
        $this->db->join('tbl_scheduled_papers sp', 'sess.session_id = sp.session_id');
        
        // Kondisi WHERE sekarang pada tabel penghubung
        $this->db->where('sp.paper_id', $paper_id);
        
        return $this->db->get()->row();
    }
    
    /**
     * Mengambil semua data gabungan untuk generate LoA.
     * @param int $paper_id
     * @return object|null
     */
    public function get_loa_data($paper_id) {
        $this->db->select('p.judul, p.paper_id, u.nama_depan, u.nama_belakang, e.*, p.loa_path');
        $this->db->from('tbl_papers p');
        $this->db->join('tbl_event_registrations er', 'p.registration_id = er.registration_id');
        $this->db->join('tbl_users u', 'er.user_id = u.user_id');
        $this->db->join('tbl_events e', 'er.event_id = e.event_id');
        $this->db->where('p.paper_id', $paper_id);
        return $this->db->get()->row();
    }
    
    /**
     * Menghapus paper dan semua penulis terkait secara transaksional.
     * @param int $paper_id
     * @param int $registration_id
     * @return bool
     */
    public function delete_paper($paper_id) {
        $this->db->trans_start();
        // Hapus dulu data anaknya (authors)
        $this->db->delete('tbl_authors', ['paper_id' => $paper_id]);
        // Baru hapus data induknya (papers)
        $this->db->delete('tbl_papers', ['paper_id' => $paper_id]);
        $this->db->trans_complete();
    
        return $this->db->trans_status();
    }
    
    public function get_authors_by_paper($paper_id) {
        $this->db->where('paper_id', $paper_id);
        return $this->db->get('tbl_authors')->result();
    }

    /**
     * Mengambil data paper berdasarkan registration_id.
     * Ini adalah fungsi utama untuk 'memanggil' data artikel milik presenter.
     * @param int $registration_id
     * @return object|null
     */
    public function get_paper_by_registration($registration_id) {
        $this->db->where('registration_id', $registration_id);
        return $this->db->get('tbl_papers')->row();
    }

    /**
     * Mengambil semua data review untuk sebuah paper.
     * @param int $paper_id
     * @return array
     */
    public function get_reviews_by_paper($paper_id) {
        $this->db->where('paper_id', $paper_id);
        $this->db->where('status_review', 'submitted'); // Hanya tampilkan review yang sudah disubmit
        return $this->db->get('tbl_reviews')->result();
    }

    /**
     * Menyimpan data submit naskah awal beserta semua penulisnya
     * menggunakan database transaction untuk memastikan integritas data.
     * @param array $paper_data Data untuk tbl_papers
     * @param array $authors_data Array berisi data penulis untuk tbl_authors
     * @return bool
     */
    public function submit_initial_paper_with_authors($paper_data, $authors_data) {
        // Mulai transaction
        $this->db->trans_start();
    
        // 1. Insert data utama paper
        $this->db->insert('tbl_papers', $paper_data);
        $paper_id = $this->db->insert_id();
    
        // 2. Insert semua data penulis (jika ada)
        if (!empty($authors_data)) {
            // Tambahkan paper_id ke setiap baris data penulis
            foreach ($authors_data as &$author) { // Perhatikan tanda & (pass by reference)
                $author['paper_id'] = $paper_id;
            }
            // Insert semua penulis sekaligus (batch insert)
            $this->db->insert_batch('tbl_authors', $authors_data);
        }
        
        // Selesaikan transaction
        $this->db->trans_complete();
    
        // Kembalikan status sukses atau gagal dari transaction
        return $this->db->trans_status();
    }
    
    /**
     * Mengupdate file setelah presenter mengirimkan revisi.
     * @param int $paper_id
     * @param string $file_path Path file revisi yang baru
     * @return bool
     */
    // Modifikasi fungsi submit_revision
    public function submit_revision($paper_id, $file_path) {
        $data = [
            // Status diubah menjadi 'revision_submitted' sebagai penanda untuk admin
            'status_artikel' => 'revision_submitted', 
            'file_path_initial' => $file_path, // Kita timpa file awal dengan revisi
            'tgl_submit' => date('Y-m-d H:i:s')
        ];
        $this->db->where('paper_id', $paper_id);
        return $this->db->update('tbl_papers', $data);
    }
    
    // Modifikasi fungsi submit_final_version
    public function submit_final_version($paper_id, $data) {
        // Tambahkan update status
        $data['status_artikel'] = 'final_submitted';
        
        $this->db->where('paper_id', $paper_id);
        return $this->db->update('tbl_papers', $data);
    }
    
    // Nanti kita bisa tambahkan fungsi untuk mengelola data penulis (authors) di sini
}