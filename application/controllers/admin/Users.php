<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Pastikan controller ini mewarisi Admin_Controller, bukan CI_Controller
class Users extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }

    // Menampilkan daftar semua pengguna
    public function index() {
        $data['users'] = $this->User_model->get_all_users();
        $data['title'] = 'Manajemen Pengguna';

        // Memuat semua bagian template
        $this->load->view('admin/_partials/_header', $data);
        $this->load->view('admin/users/index', $data); // Ini adalah konten spesifik halaman
        $this->load->view('admin/_partials/_footer');
    }

    // Mengedit pengguna
    public function edit($user_id) {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nama_depan', 'Nama Depan', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('peran_sistem', 'Peran Sistem', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['user'] = $this->User_model->get_user_by_id($user_id);
            $data['title'] = 'Edit Pengguna';
            $this->load->view('admin/_partials/_header', $data);
            $this->load->view('admin/users/edit', $data); // Load view form edit
            $this->load->view('admin/_partials/_footer');
        } else {
            $data_update = [
                'nama_depan'    => $this->input->post('nama_depan'),
                'nama_belakang' => $this->input->post('nama_belakang'),
                'email'         => $this->input->post('email'),
                'afiliasi'      => $this->input->post('afiliasi'),
                'negara'        => $this->input->post('negara'),
                'peran_sistem'  => $this->input->post('peran_sistem')
            ];

            $this->User_model->update_user($user_id, $data_update);
            $this->session->set_flashdata('success', 'Data pengguna berhasil diperbarui.');
            redirect('admin/users');
        }
    }

    // Menghapus pengguna
    public function delete($user_id) {
        // Tambahkan validasi keamanan di sini, misal cek token CSRF
        $this->User_model->delete_user($user_id);
        $this->session->set_flashdata('success', 'Pengguna berhasil dihapus.');
        redirect('admin/users');
    }
}