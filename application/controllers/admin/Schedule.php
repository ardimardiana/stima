<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Schedule_model');
        $this->load->model('Event_model');
        $this->load->model('Room_model');
    }

    // Menampilkan halaman utama penjadwalan
    public function index($event_id) {
        $data['title'] = 'Manajemen Jadwal';
        $data['event'] = $this->Event_model->get_event_by_id($event_id);
        $data['rooms'] = $this->Room_model->get_by_event($event_id);
        $data['unscheduled_papers'] = $this->Schedule_model->get_unscheduled_papers($event_id);
        $sessions_raw = $this->Schedule_model->get_sessions_with_papers($event_id);
        $schedules_by_room = [];
        foreach($sessions_raw as $session) {
            $schedules_by_room[$session->room_id][] = $session;
        }
        $data['schedules'] = $schedules_by_room;

        $this->load->view('admin/_partials/_header', $data);
        $this->load->view('admin/schedule/index', $data);
        $this->load->view('admin/_partials/_footer');
    }

    // Aksi untuk membuat sesi baru (dari modal)
    public function create_session($event_id) {
        $data = [
            'event_id' => $event_id,
            'room_id' => $this->input->post('room_id'),
            'nama_sesi' => $this->input->post('nama_sesi'),
            'waktu_mulai' => $this->input->post('waktu_mulai'),
            'waktu_selesai' => $this->input->post('waktu_selesai'),
        ];
        $this->Schedule_model->insert_session($data);
        $this->session->set_flashdata('success', 'Sesi baru berhasil dibuat.');
        redirect('admin/schedule/index/' . $event_id);
    }
    
    // Aksi untuk memproses drag-and-drop (dipanggil via AJAX)
    public function assign_paper() {
        $paper_id = $this->input->post('paper_id');
        $session_id = $this->input->post('session_id'); // Ganti dari schedule_id
        
        $success = $this->Schedule_model->assign_paper_to_session($paper_id, $session_id);
        
        header('Content-Type: application/json');
        if ($success) {
            echo json_encode(['status' => 'success', 'message' => 'Artikel berhasil dijadwalkan.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan jadwal.']);
        }
    }
    
    // Aksi untuk menghapus/melepas paper dari sesi (dipanggil via AJAX)
    public function remove_paper() {
        $paper_id = $this->input->post('paper_id');
        
        $success = $this->Schedule_model->remove_paper_from_schedule($paper_id);
        
        header('Content-Type: application/json');
        if ($success) {
            echo json_encode(['status' => 'success', 'message' => 'Artikel berhasil dilepas dari jadwal.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal melepas artikel.']);
        }
    }
    
    // Tambahkan fungsi baru ini di dalam class Schedule
    public function delete_session($session_id) {
        $session = $this->db->get_where('tbl_sessions', ['session_id' => $session_id])->row(); // Ambil data untuk redirect
        if ($session) {
            $this->Schedule_model->delete_session($session_id);
            $this->session->set_flashdata('success', 'Sesi berhasil dihapus.');
            redirect('admin/schedule/index/' . $session->event_id);
        } else {
            show_404();
        }
    }
}