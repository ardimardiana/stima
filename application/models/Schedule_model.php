<?php
class Schedule_model extends CI_Model {
    
    /**
     * Mengambil dan menstrukturkan data jadwal lengkap untuk ditampilkan ke publik.
     * @param int $event_id
     * @return array Data jadwal yang sudah terstruktur
     */
    public function get_public_schedule($event_id) {
        // Ambil semua data yang dibutuhkan dalam satu query
        $this->db->select('
            DATE(s.waktu_mulai) as schedule_day,
            TIME(s.waktu_mulai) as start_time,
            TIME(s.waktu_selesai) as end_time,
            s.nama_sesi, s.room_id,
            s.paper_id,
            p.judul as paper_title,
            GROUP_CONCAT(CONCAT(a.nama_depan, " ", a.nama_belakang) SEPARATOR ", ") as authors
        ');
        $this->db->from('tbl_schedules s');
        $this->db->join('tbl_papers p', 's.paper_id = p.paper_id', 'left');
        $this->db->join('tbl_authors a', 'p.paper_id = a.paper_id', 'left');
        $this->db->where('s.event_id', $event_id);
        $this->db->group_by('s.schedule_id');
        $this->db->order_by('s.waktu_mulai ASC, s.room_id ASC');
        $flat_schedule = $this->db->get()->result();
    
        // Proses data mentah menjadi struktur grid [hari][waktu][ruangan]
        $structured_schedule = [];
        foreach ($flat_schedule as $item) {
            $time_key = $item->start_time;
            $structured_schedule[$item->schedule_day][$time_key][$item->room_id] = $item;
        }
        
        return $structured_schedule;
    }

    public function get_unscheduled_papers($event_id) {
        // Subquery untuk mengambil semua paper_id yang SUDAH dijadwalkan
        $subquery = $this->db->select('paper_id')
                             ->from('tbl_schedules')
                             ->where('event_id', $event_id)
                             ->where('paper_id IS NOT NULL') // Ini tetap penting
                             ->get_compiled_select();
    
        // Query utama
        $this->db->select('p.paper_id, p.judul');
        $this->db->from('tbl_papers p');
        $this->db->join('tbl_event_registrations er', 'p.registration_id = er.registration_id');
        $this->db->where('er.event_id', $event_id);
        $this->db->where_in('p.status_artikel', ['accepted', 'final_submitted']);
        
        // Cukup langsung gunakan subquery. 
        // CodeIgniter akan menangani kasus di mana subquery mengembalikan hasil kosong.
        // Blok "if (!empty(...))" yang sebelumnya ada, kita hapus karena menyebabkan error.
        $this->db->where("p.paper_id NOT IN ($subquery)", NULL, FALSE);
    
        return $this->db->get()->result();
    }

    // Mengambil semua jadwal untuk sebuah event, di-grup berdasarkan ruangan
    public function get_schedule_by_event($event_id) {
        $this->db->select('s.*, p.judul as paper_title');
        $this->db->from('tbl_schedules s');
        $this->db->join('tbl_papers p', 's.paper_id = p.paper_id', 'left');
        $this->db->where('s.event_id', $event_id);
        $this->db->order_by('s.waktu_mulai', 'ASC');
        $query = $this->db->get();
        
        $schedule_by_room = [];
        foreach ($query->result() as $row) {
            $schedule_by_room[$row->room_id][] = $row;
        }
        return $schedule_by_room;
    }

    // Fungsi untuk menyimpan sesi baru
    public function insert_session($data) {
        return $this->db->insert('tbl_schedules', $data);
    }
    
    // Fungsi untuk meng-assign paper ke sebuah sesi (via drag-drop)
    public function assign_paper_to_session($paper_id, $schedule_id) {
        $this->db->where('schedule_id', $schedule_id);
        return $this->db->update('tbl_schedules', ['paper_id' => $paper_id]);
    }
}