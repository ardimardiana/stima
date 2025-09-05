<?php
class User_model extends CI_Model {
    
    public function get_user_details($user_id) {
        return $this->db->get_where('tbl_users', ['user_id' => $user_id])->row();
    }
    
    public function update_profile($user_id, $data) {
        $this->db->where('user_id', $user_id);
        return $this->db->update('tbl_users', $data);
    }
    
    // Ganti fungsi lama get_active_events_... dengan fungsi yang lebih spesifik ini
    public function get_user_registration_by_role($user_id, $event_id, $role) {
        $this->db->where('user_id', $user_id);
        $this->db->where('event_id', $event_id);
        $this->db->where('peran_event', $role);
        $registration = $this->db->get('tbl_event_registrations')->row();
    
        // Jika ada, langsung ambil data pembayarannya juga
        if ($registration) {
            $registration->payment = $this->db->get_where('tbl_payments', ['registration_id' => $registration->registration_id])->row();
        }
        
        return $registration;
    }
    
    /**
     * Memverifikasi apakah sebuah registration_id dimiliki oleh user_id tertentu.
     * Ini adalah kunci untuk mencegah akses ilegal ke data orang lain.
     * @param int $registration_id ID pendaftaran yang dicek
     * @param int $user_id ID user yang sedang login
     * @return bool
     */
    public function is_registration_owner($registration_id, $user_id)
    {
        $this->db->where('registration_id', $registration_id);
        $this->db->where('user_id', $user_id);
        $count = $this->db->count_all_results('tbl_event_registrations');
    
        return ($count > 0); // Akan return TRUE jika ada 1 baris (miliknya), FALSE jika 0 (bukan miliknya)
    }
    
    public function get_active_events_with_user_status($user_id) {
        $this->db->where('status', 'aktif');
        $this->db->order_by('tahun', 'ASC');
        $active_events = $this->db->get('tbl_events')->result();
    
        foreach ($active_events as $event) {
            $this->db->where('user_id', $user_id);
            $this->db->where('event_id', $event->event_id);
            $registration = $this->db->get('tbl_event_registrations')->row();
    
            if ($registration) {
                $event->is_registered = TRUE;
                $event->user_role = $registration->peran_event;
                $event->registration_id = $registration->registration_id;
    
                // --- PERUBAHAN DIMULAI DI SINI ---
                // Ambil data pembayaran yang terkait
                $payment = $this->db->get_where('tbl_payments', ['registration_id' => $registration->registration_id])->row();
                
                // Sertakan objek payment ke dalam objek event
                $event->payment = $payment;
                
                // Sertakan juga path QR code untuk nanti
                $event->registration = $registration; 
                // --- PERUBAHAN SELESAI ---
    
            } else {
                $event->is_registered = FALSE;
                $event->user_role = NULL;
                $event->registration_id = NULL;
                $event->payment = NULL; // Pastikan properti ini ada meskipun kosong
                $event->registration = NULL;
            }
        }
        return $active_events;
    }

    // Mengambil riwayat event yang pernah diikuti user
   public function get_user_event_history($user_id) {
        $this->db->select(
            'e.nama_event, e.tahun, ' .
            'er.peran_event, er.status_kehadiran, er.sertifikat_path, er.sertifikat_presenter_path, ' .
            'p.status_pembayaran'
        );
        $this->db->from('tbl_event_registrations er');
        $this->db->join('tbl_events e', 'er.event_id = e.event_id');
        $this->db->join('tbl_payments p', 'er.registration_id = p.registration_id', 'left'); // Gunakan LEFT JOIN untuk jaga-jaga
        $this->db->where('er.user_id', $user_id);
        $this->db->where('e.status !=', 'aktif');
        $this->db->order_by('e.tahun', 'DESC');
        
        return $this->db->get()->result();
    }
    
    // Mendaftarkan user ke sebuah event
    public function register_to_event($data) {
        return $this->db->insert('tbl_event_registrations', $data);
    }

    public function get_all_users() {
        $query = $this->db->get('tbl_users');
        return $query->result();
    }

    public function get_user_by_id($user_id) {
        $query = $this->db->get_where('tbl_users', ['user_id' => $user_id]);
        return $query->row();
    }
    
    public function update_user($user_id, $data) {
        $this->db->where('user_id', $user_id);
        return $this->db->update('tbl_users', $data);
    }
    
    public function delete_user($user_id) {
        // Hati-hati! Ini akan menghapus permanen.
        // Pertimbangkan untuk menambahkan kolom 'is_active' untuk soft delete.
        $this->db->where('user_id', $user_id);
        return $this->db->delete('tbl_users');
    }
}