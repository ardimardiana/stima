<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Event_model');
        $this->load->model('Announcement_model');
        $this->load->helper('url');
    }

    // Fungsi ini akan menampilkan event yang statusnya 'aktif'
    public function index() {
        $event = $this->Event_model->get_active_event();
        if (!$event) {
            // Jika tidak ada event aktif, mungkin tampilkan halaman "coming soon" atau arahkan ke event terakhir
            echo "Saat ini tidak ada event yang sedang aktif. Atau Login <a href='".site_url('auth/login')."'>disini</a>";
            return;
        }
        $this->_show_event_page($event);
    }
    
    public function speakers() {
        $active_event = $this->Event_model->get_active_event();
        if (!$active_event) { show_404(); return; }
    
        $this->load->model('Speaker_model');
    
        $data['title'] = 'Daftar Pembicara';
        $data['event'] = $active_event;
        $data['is_active_event'] = ($active_event->status == 'aktif');
        $data['speakers'] = $this->Speaker_model->get_speakers_by_event($active_event->event_id);
        $data['archived_events'] = $this->Event_model->get_archived_events();
        
        $this->load->view('speakers', $data);
    }
    
    public function schedule() {
        // Ambil event yang sedang aktif
        $active_event = $this->Event_model->get_active_event();
        if (!$active_event) {
            show_404(); return;
        }
    
        $this->load->model('Schedule_model');
        $this->load->model('Event_model');
        $this->load->model('Room_model');
    
        $data['title'] = 'Jadwal Acara';
        $data['event'] = $active_event;
        $data['is_active_event'] = ($active_event->status == 'aktif');
        // Ambil daftar ruangan untuk header tabel
        $data['rooms'] = $this->Room_model->get_by_event($active_event->event_id);
        // Ambil data jadwal yang sudah terstruktur
        $data['schedule_data'] = $this->Schedule_model->get_public_schedule_by_room($active_event->event_id);
    
        $data['archived_events'] = $this->Event_model->get_archived_events();
    
        $this->load->view('schedule', $data);
    }

    // Fungsi ini akan menangani URL seperti /2025, /2026, dst.
    public function view_event($slug = '') {
        $event = $this->Event_model->get_event_by_slug($slug);
        if (!$event) {
            show_404(); // Tampilkan halaman 404 jika slug tidak ditemukan
        }
        $this->_show_event_page($event);
    }

    // Fungsi private untuk menghindari duplikasi kode
    private function _show_event_page($event_data) {
        $data['event'] = $event_data;
        $data['announcements'] = $this->Announcement_model->get_by_event($event_data->event_id);
        $data['archived_events'] = $this->Event_model->get_archived_events();
        $this->load->model('Banner_model');
        $data['banners'] = $this->Banner_model->get_by_event($event_data->event_id);
    
        // Kirim flag untuk menandakan apakah event ini aktif atau tidak
        $data['is_active_event'] = ($event_data->status == 'aktif');
        $data['title'] = 'Selamat Datang';
        $this->load->view('home_page', $data);
    }
}