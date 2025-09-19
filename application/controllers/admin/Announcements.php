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
             // 1. Simpan pengumuman seperti biasa
            $announcement_data = [
                'event_id' => $event_id,
                'judul' => $this->input->post('judul'),
                'isi' => $this->input->post('isi')
            ];
            $this->Announcement_model->insert($announcement_data);
    
            // -- PROSES PENGISIAN ANTRIAN EMAIL --
            // 2. Ambil daftar email unik
            $this->load->model('Participant_model');
            $recipients = $this->Participant_model->get_unique_participants_for_mailing($event_id);
    
            if (!empty($recipients)) {
                $this->load->model('Email_queue_model');
                $event_data = $this->Event_model->get_event_by_id($event_id);
                $email_batch = [];
    
                // 3. Siapkan data email untuk setiap penerima
                foreach ($recipients as $recipient) {
                    $email_batch[] = [
                        'recipient_email' => $recipient->email,
                        'subject' => '[PENGUMUMAN] ' . $announcement_data['judul'],
                        'message' => "Halo {$recipient->nama_depan},<br><br>Ada pengumuman baru dari panitia {$event_data->nama_event}:<br><br><div style='padding:15px; border:1px solid #ddd; background-color:#f9f9f9;'><strong>{$announcement_data['judul']}</strong><br>{$announcement_data['isi']}</div><br><br>Terima kasih.",
                        'status' => 'pending',
                        'event_id' => $event_id
                    ];
                }
                // 4. Masukkan semua data ke antrian sekaligus
                $this->Email_queue_model->add_batch_to_queue($email_batch);
            }
            // -- AKHIR PROSES --
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