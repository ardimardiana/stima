<?php
class Schedule_model extends CI_Model {
    
    /**
     * Mengambil dan menstrukturkan data jadwal lengkap, dikelompokkan per ruangan.
     * @param int $event_id
     * @return array Data jadwal yang sudah terstruktur
     */
    public function get_public_schedule_by_room($event_id) {
        // 1. Ambil semua sesi untuk event ini, gabungkan dengan data ruangan
        $this->db->select('s.*, r.nama_ruang');
        $this->db->from('tbl_sessions s');
        $this->db->join('tbl_rooms r', 's.room_id = r.room_id');
        $this->db->where('s.event_id', $event_id);
        $this->db->order_by('r.nama_ruang ASC, s.waktu_mulai ASC');
        $sessions = $this->db->get()->result();
    
        if (empty($sessions)) {
            return [];
        }
    
        // 2. Ambil semua paper yang terjadwal dalam satu query
        $session_ids = array_column($sessions, 'session_id');
        $this->db->select('sp.session_id, sp.paper_id, p.judul, GROUP_CONCAT(CONCAT(a.nama_depan, " ", a.nama_belakang) SEPARATOR ", ") as authors, c.nama_topik');
        $this->db->from('tbl_scheduled_papers sp');
        $this->db->join('tbl_papers p', 'sp.paper_id = p.paper_id');
        $this->db->join('tbl_topics c', 'c.topic_id = p.topic_id');
        $this->db->join('tbl_authors a', 'p.paper_id = a.paper_id');
        $this->db->where_in('sp.session_id', $session_ids);
        $this->db->group_by('sp.scheduled_paper_id');
        $this->db->order_by('sp.sort_order', 'ASC');
        $papers_raw = $this->db->get()->result();
    
        // Petakan paper ke session_id agar mudah diakses
        $papers_by_session = [];
        foreach ($papers_raw as $paper) {
            $papers_by_session[$paper->session_id][] = $paper;
        }
    
        // 3. Gabungkan dan kelompokkan berdasarkan hari dan ruangan
        $schedule_by_day_room = [];
        foreach ($sessions as $session) {
            $day = date('Y-m-d', strtotime($session->waktu_mulai));
            // Masukkan paper yang sesuai ke dalam objek sesi
            $session->papers = isset($papers_by_session[$session->session_id]) ? $papers_by_session[$session->session_id] : [];
            // Kelompokkan
            $schedule_by_day_room[$day][$session->nama_ruang][] = $session;
        }
        
        return $schedule_by_day_room;
    }
    
    public function delete_session($session_id) {
        return $this->db->delete('tbl_sessions', ['session_id' => $session_id]);
    }
    
    public function remove_paper_from_schedule($paper_id) {
        return $this->db->delete('tbl_scheduled_papers', ['paper_id' => $paper_id]);
    }

    public function get_unscheduled_papers($event_id) {
        $subquery = $this->db->select('paper_id')->from('tbl_scheduled_papers sp')
                             ->join('tbl_sessions s', 'sp.session_id = s.session_id')
                             ->where('s.event_id', $event_id)
                             ->get_compiled_select();
    
        $this->db->select('
            p.paper_id, 
            p.judul, 
            a.nama_depan as author_firstname, 
            a.nama_belakang as author_lastname, 
            a.afiliasi as author_affiliation,
            c.nama_topik
        ');
        
        $this->db->from('tbl_papers p');
        $this->db->join('tbl_topics c', 'c.topic_id = p.topic_id');
        $this->db->join('tbl_event_registrations er', 'p.registration_id = er.registration_id');
        $this->db->join('tbl_authors a', 'p.paper_id = a.paper_id');
        
        $this->db->where('er.event_id', $event_id);
        $this->db->where_in('p.status_artikel', ['accepted', 'final_submitted']);
        $this->db->where('a.is_corresponding_author', 1);
     
        if (!empty($this->db->query($subquery)->result())) {
            $this->db->where("p.paper_id NOT IN ($subquery)", NULL, FALSE);
        }
        return $this->db->get()->result();
    }

    // UPDATE: Ambil semua sesi beserta data relasi nama moderatornya
    public function get_sessions_with_papers($event_id) {
        $this->db->select('s.*, u.nama_depan as mod_depan, u.nama_belakang as mod_belakang');
        $this->db->from('tbl_sessions s');
        $this->db->join('tbl_users u', 's.moderator_id = u.user_id', 'left');
        $this->db->where('s.event_id', $event_id);
        $this->db->order_by('s.waktu_mulai', 'ASC');
        $sessions = $this->db->get()->result();

        foreach ($sessions as $session) {
            $this->db->select('sp.paper_id, p.judul')
                     ->from('tbl_scheduled_papers sp')
                     ->join('tbl_papers p', 'sp.paper_id = p.paper_id')
                     ->where('sp.session_id', $session->session_id)
                     ->order_by('sp.sort_order', 'ASC');
            $session->papers = $this->db->get()->result();
        }
        return $sessions;
    }

    public function insert_session($data) {
        return $this->db->insert('tbl_sessions', $data);
    }
    
    public function assign_paper_to_session($paper_id, $session_id) {
        $exists = $this->db->get_where('tbl_scheduled_papers', ['paper_id' => $paper_id])->num_rows() > 0;
        if($exists) return false;

        $this->db->delete('tbl_scheduled_papers', ['paper_id' => $paper_id]);

        $data = ['session_id' => $session_id, 'paper_id' => $paper_id];
        return $this->db->insert('tbl_scheduled_papers', $data);
    }

    // UPDATE: Query pencarian paper berdasarkan moderator_id
    public function get_papers_by_moderator($user_id, $event_id) {
        $this->db->select('p.*, s.nama_sesi, s.waktu_mulai, r.nama_ruang, a.nama_depan as author_firstname, a.nama_belakang as author_lastname, c.nama_topik');
        $this->db->from('tbl_papers p');
        $this->db->join('tbl_scheduled_papers sp', 'p.paper_id = sp.paper_id');
        $this->db->join('tbl_sessions s', 'sp.session_id = s.session_id');
        $this->db->join('tbl_rooms r', 's.room_id = r.room_id');
        $this->db->join('tbl_event_registrations er', 'p.registration_id = er.registration_id');
        $this->db->join('tbl_topics c', 'p.topic_id = c.topic_id');
        $this->db->join('tbl_authors a', 'p.paper_id = a.paper_id');
        
        $this->db->where('er.event_id', $event_id);
        $this->db->where('s.moderator_id', $user_id); 
        $this->db->where('a.is_corresponding_author', 1);
        $this->db->order_by('s.waktu_mulai', 'ASC');
        
        return $this->db->get()->result();
    }
}