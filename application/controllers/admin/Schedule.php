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
        $data['schedules'] = $this->Schedule_model->get_schedule_by_event($event_id);

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
            'paper_id' => NULL // Awalnya kosong
        ];
        $this->Schedule_model->insert_session($data);
        $this->session->set_flashdata('success', 'Sesi baru berhasil dibuat.');
        redirect('admin/schedule/index/' . $event_id);
    }
    
    // Aksi untuk memproses drag-and-drop (dipanggil via AJAX)
    public function assign_paper() {
        $paper_id = $this->input->post('paper_id');
        $schedule_id = $this->input->post('schedule_id');
        
        $success = $this->Schedule_model->assign_paper_to_session($paper_id, $schedule_id);
        
        header('Content-Type: application/json');
        if ($success) {
            echo json_encode(['status' => 'success', 'message' => 'Artikel berhasil dijadwalkan.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan jadwal.']);
        }
    }
}