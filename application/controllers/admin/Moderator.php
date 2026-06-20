<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Moderator extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Schedule_model');
        $this->load->model('Event_model');
    }

    public function index($event_id) {
        $data['title'] = 'Moderasi Artikel Sesi';
        $data['event'] = $this->Event_model->get_event_by_id($event_id);
        
        $user_id = $this->session->userdata('user_id'); 
        $data['papers'] = $this->Schedule_model->get_papers_by_moderator($user_id, $event_id);

        $this->load->view('admin/_partials/_header', $data);
        $this->load->view('admin/moderator/index', $data);
        $this->load->view('admin/_partials/_footer');
    }
}