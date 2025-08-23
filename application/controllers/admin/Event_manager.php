<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_manager extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Event_model');
        // Kita akan buat model ini di langkah selanjutnya
        $this->load->model('Event_manager_model'); 
    }

    // Halaman utama saat admin mengklik "Kelola"
    public function index($event_id) {
        $data['title'] = 'Dasbor Pengelolaan Event';
        $data['event'] = $this->Event_model->get_event_by_id($event_id);
        
        if (!$data['event']) {
            show_404();
        }

        // Ambil statistik spesifik untuk event ini
        $data['stats'] = $this->Event_manager_model->get_event_stats($event_id);

        $this->load->view('admin/_partials/_header', $data);
        $this->load->view('admin/event_manager/index', $data);
        $this->load->view('admin/_partials/_footer');
    }
    
    public function toggle_certificates($event_id) {
        // Ambil status saat ini
        $event = $this->Event_model->get_event_by_id($event_id);
        // Tentukan status baru (kebalikannya)
        $new_status = ($event->sertifikat_aktif == 1) ? 0 : 1;
        
        // Update ke database
        $this->db->where('event_id', $event_id);
        $this->db->update('tbl_events', ['sertifikat_aktif' => $new_status]);
        
        $message = ($new_status == 1) ? 'Akses download sertifikat telah DIAKTIFKAN.' : 'Akses download sertifikat telah DINONAKTIFKAN.';
        $this->session->set_flashdata('success', $message);
        redirect('admin/event_manager/index/' . $event_id);
    }
}