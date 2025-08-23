<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Announcements extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Announcement_model');
        $this->load->model('Event_model'); // Untuk mengambil info event
    }

    // Menampilkan daftar pengumuman untuk event tertentu
    public function event($event_id) {
        $data['title'] = 'Manajemen Pengumuman';
        $data['event'] = $this->Event_model->get_event_by_id($event_id);
        $data['announcements'] = $this->Announcement_model->get_by_event($event_id);

        if (!$data['event']) {
            show_404();
        }

        $this->load->view('admin/_partials/_header', $data);
        $this->load->view('admin/announcements/index', $data);
        $this->load->view('admin/_partials/_footer');
    }

    // Menampilkan form tambah pengumuman
    public function create($event_id) {
        $this->form_validation->set_rules('judul', 'Judul', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Buat Pengumuman Baru';
            $data['event'] = $this->Event_model->get_event_by_id($event_id);
            $this->load->view('admin/_partials/_header', $data);
            $this->load->view('admin/announcements/form', $data);
            $this->load->view('admin/_partials/_footer');
        } else {
            $data = [
                'event_id' => $event_id,
                'judul' => $this->input->post('judul'),
                'isi' => $this->input->post('isi')
            ];
            $this->Announcement_model->insert($data);
            $this->session->set_flashdata('success', 'Pengumuman baru berhasil dibuat.');
            redirect('admin/announcements/event/' . $event_id);
        }
    }

    // Menampilkan form edit pengumuman
    public function edit($pengumuman_id) {
        $this->form_validation->set_rules('judul', 'Judul', 'required');
        
        $announcement = $this->Announcement_model->get_by_id($pengumuman_id);
        if (!$announcement) { show_404(); }

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Edit Pengumuman';
            $data['event'] = $this->Event_model->get_event_by_id($announcement->event_id);
            $data['announcement'] = $announcement;
            $this->load->view('admin/_partials/_header', $data);
            $this->load->view('admin/announcements/form', $data);
            $this->load->view('admin/_partials/_footer');
        } else {
            $data = [
                'judul' => $this->input->post('judul'),
                'isi' => $this->input->post('isi')
            ];
            $this->Announcement_model->update($pengumuman_id, $data);
            $this->session->set_flashdata('success', 'Pengumuman berhasil diperbarui.');
            redirect('admin/announcements/event/' . $announcement->event_id);
        }
    }

    // Menghapus pengumuman
    public function delete($pengumuman_id) {
        $announcement = $this->Announcement_model->get_by_id($pengumuman_id);
        if ($announcement) {
            $event_id = $announcement->event_id;
            $this->Announcement_model->delete($pengumuman_id);
            $this->session->set_flashdata('success', 'Pengumuman berhasil dihapus.');
            redirect('admin/announcements/event/' . $event_id);
        } else {
            show_404();
        }
    }
}