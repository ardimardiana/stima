<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Topics extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Topic_model');
        $this->load->model('Event_model');
    }

    // Menampilkan daftar topik & form tambah
    public function index($event_id) {
        $this->form_validation->set_rules('nama_topik', 'Nama Topik', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Pengaturan Topik Makalah';
            $data['event'] = $this->Event_model->get_event_by_id($event_id);
            $data['topics'] = $this->Topic_model->get_by_event($event_id);
            
            $this->load->view('admin/_partials/_header', $data);
            $this->load->view('admin/topics/index', $data);
            $this->load->view('admin/_partials/_footer');
        } else {
            $data = [
                'event_id' => $event_id,
                'nama_topik' => $this->input->post('nama_topik')
            ];
            $this->Topic_model->insert($data);
            $this->session->set_flashdata('success', 'Topik baru berhasil ditambahkan.');
            redirect('admin/topics/index/' . $event_id);
        }
    }

    public function delete($topic_id) {
        $topic = $this->Topic_model->get_by_id($topic_id);
        if ($topic) {
            $this->Topic_model->delete($topic_id);
            $this->session->set_flashdata('success', 'Topik berhasil dihapus.');
            redirect('admin/topics/index/' . $topic->event_id);
        } else {
            show_404();
        }
    }
}