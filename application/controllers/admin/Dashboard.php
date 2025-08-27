<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Dashboard_model');
    }

    public function index() {
        $data['title'] = 'Dashboard';
        
        // Ambil data statistik dari model
        $data['total_users'] = $this->Dashboard_model->count_total_users();
        $data['total_events'] = $this->Dashboard_model->count_total_events();
        $data['total_participants'] = $this->Dashboard_model->count_participants_active_event();
        $data['total_papers'] = $this->Dashboard_model->count_papers_active_event();

        // Tampilkan halaman menggunakan template
        $this->load->view('admin/_partials/_header', $data);
        $this->load->view('admin/dashboard/index', $data); // Konten dashboard
        $this->load->view('admin/_partials/_footer');
    }
    
    function atur($arah)
    {
        $this->load->model('Event_model');
        $event = $this->Event_model->get_active_event();
        if (!$event) {
            // Jika tidak ada event aktif, mungkin tampilkan halaman "coming soon" atau arahkan ke event terakhir
            echo "Saat ini tidak ada event yang sedang aktif.";
            return;
        }
        redirect(site_url('admin/'.$arah.'/index/'.$event->event_id));
    }
}