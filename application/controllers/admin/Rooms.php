<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rooms extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Room_model');
        $this->load->model('Event_model');
    }

    // Menampilkan daftar ruangan & form tambah
    public function index($event_id) {
        $this->form_validation->set_rules('nama_ruang', 'Nama Ruang', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Manajemen Ruangan';
            $data['event'] = $this->Event_model->get_event_by_id($event_id);
            $data['rooms'] = $this->Room_model->get_by_event($event_id);
            
            $this->load->view('admin/_partials/_header', $data);
            $this->load->view('admin/rooms/index', $data);
            $this->load->view('admin/_partials/_footer');
        } else {
            $data = [
                'event_id' => $event_id,
                'nama_ruang' => $this->input->post('nama_ruang'),
                'lokasi' => $this->input->post('lokasi')
            ];
            $this->Room_model->insert($data);
            $this->session->set_flashdata('success', 'Ruangan baru berhasil ditambahkan.');
            redirect('admin/rooms/index/' . $event_id);
        }
    }

    // Menampilkan form edit
    public function edit($room_id) {
        $data['title'] = 'Edit Ruangan';
        $data['room'] = $this->Room_model->get_by_id($room_id);
        $data['event'] = $this->Event_model->get_event_by_id($data['room']->event_id);

        $this->load->view('admin/_partials/_header', $data);
        $this->load->view('admin/rooms/edit', $data);
        $this->load->view('admin/_partials/_footer');
    }

    // Memproses update
    public function update($room_id) {
        $room = $this->Room_model->get_by_id($room_id);
        $data = [
            'nama_ruang' => $this->input->post('nama_ruang'),
            'lokasi' => $this->input->post('lokasi')
        ];
        $this->Room_model->update($room_id, $data);
        $this->session->set_flashdata('success', 'Ruangan berhasil diperbarui.');
        redirect('admin/rooms/index/' . $room->event_id);
    }
    
    // Menghapus ruangan
    public function delete($room_id) {
        $room = $this->Room_model->get_by_id($room_id);
        if ($room) {
            $this->Room_model->delete($room_id);
            $this->session->set_flashdata('success', 'Ruangan berhasil dihapus.');
            redirect('admin/rooms/index/' . $room->event_id);
        } else {
            show_404();
        }
    }
}