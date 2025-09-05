<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends User_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }

    // Menampilkan halaman edit profil
    public function index() {
        $user_id = $this->session->userdata('user_id');
        $data['title'] = 'Edit Profil Saya';
        $data['user'] = $this->User_model->get_user_details($user_id);
        
        // Memuat navbar dan footer sederhana untuk konsistensi
        $this->load->view('user/profile/index', $data);
    }

    // Memproses update profil
    public function update() {
        $user_id = $this->session->userdata('user_id');
        
        // 1. Siapkan data teks
        $data_update = [
            'nama_depan' => $this->input->post('nama_depan'),
            'nama_belakang' => $this->input->post('nama_belakang'),
            'afiliasi' => $this->input->post('afiliasi'),
            'negara' => $this->input->post('negara'),
        ];

        // 2. Cek dan proses upload foto profil
        if (!empty($_FILES['photo']['name'])) {
            $config['upload_path']   = './uploads/photos/';
            $config['allowed_types'] = 'jpg|png|jpeg';
            $config['max_size']      = 2048; // 2MB
            $config['encrypt_name']  = TRUE;

            if (!is_dir($config['upload_path'])) { mkdir($config['upload_path'], 0777, TRUE); }
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('photo')) {
                // Jika upload berhasil, tambahkan path file ke data
                $data_update['photo_path'] = 'uploads/photos/' . $this->upload->data('file_name');

                // Hapus foto profil lama
                $old_photo = $this->User_model->get_user_details($user_id)->photo_path;
                if (!empty($old_photo) && file_exists($old_photo)) {
                    unlink($old_photo);
                }
            } else {
                $this->session->set_flashdata('error', 'Upload Foto Gagal: ' . $this->upload->display_errors());
                redirect('user/profile');
                return;
            }
        }
        
        // 3. Lakukan update ke database
        if ($this->User_model->update_profile($user_id, $data_update)) {
            $this->session->set_flashdata('success', 'Profil berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui profil.');
        }

        redirect('user/profile');
    }
}