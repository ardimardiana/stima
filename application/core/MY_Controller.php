<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// SEBELUMNYA: class Admin_Controller extends CI_Controller
class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');

        // Cek apakah pengguna sudah login
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Anda harus login terlebih dahulu.');
            redirect('auth/login');
        }
    }
}

// BUAT CLASS BARU UNTUK ADMIN DI FILE YANG SAMA
class Admin_Controller extends MY_Controller {
    public function __construct() {
        parent::__construct();

        // Cek spesifik untuk peran admin
        if ($this->session->userdata('peran') !== 'admin') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki hak akses ke halaman ini.');
            redirect('user/dashboard'); 
        }
    }
}

// BUAT CLASS BARU UNTUK USER DI FILE YANG SAMA
class User_Controller extends MY_Controller {
    public function __construct() {
        parent::__construct();

        // Cek spesifik untuk peran user (jika diperlukan)
        if ($this->session->userdata('peran') !== 'user') {
             redirect('auth/login'); 
        }
    }
}