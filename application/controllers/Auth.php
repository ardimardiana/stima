<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('captcha');
        $this->load->model('Auth_model'); // Model untuk urusan user
    }
    
    function generate_password($pwd)
    {
        echo password_hash($pwd, PASSWORD_BCRYPT);
    }

    // Fungsi untuk membuat CAPTCHA
    private function _create_captcha() {
        $config = array(
            'img_path'      => './captcha/', // Folder untuk menyimpan gambar captcha
            'img_url'       => base_url('captcha/'),
            'font_path'     => FCPATH . 'system/fonts/texb.ttf',
            'img_width'     => '150',
            'img_height'    => 40,
            'expiration'    => 7200, // 2 jam
            'word_length'   => 4,
            'font_size'     => 18,
            'pool'          => '0123456789',
            'colors'        => array(
                'background' => array(255, 255, 255),
                'border' => array(204, 204, 204),
                'text' => array(0, 0, 0),
                'grid' => array(255, 182, 182)
            )
        );
        $captcha = create_captcha($config);
        
        // Simpan kode captcha di session
        $this->session->set_userdata('captcha_code', $captcha['word']);
        
        return $captcha['image'];
    }

    // Halaman Login
    public function login() {
        // Validasi form (tetap sama)
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('captcha', 'Captcha', 'required|callback_check_captcha');
    
        if ($this->form_validation->run() == FALSE) {
            $data['captcha_image'] = $this->_create_captcha();
            $this->load->view('auth/login', $data);
        } else {
            $email = $this->input->post('email');
            $password = $this->input->post('password');
    
            $user = $this->Auth_model->get_user_by_email($email);
    
            if ($user && password_verify($password, $user->password)) {
                // --- PERUBAHAN DIMULAI DI SINI ---
    
                // 1. Simpan peran pengguna ke dalam session
                $session_data = [
                    'user_id'   => $user->user_id,
                    'email'     => $user->email,
                    'nama'      => $user->nama_depan,
                    'peran'     => $user->peran_sistem, // <-- TAMBAHKAN INI
                    'logged_in' => TRUE
                ];
                $this->session->set_userdata($session_data);
    
                // 2. Arahkan berdasarkan peran
                if ($user->peran_sistem == 'admin') {
                    redirect('admin/dashboard'); // Arahkan admin ke dashboard admin
                } else {
                    redirect('user/dashboard'); // Arahkan user biasa ke dashboard peserta
                }
                // --- PERUBAHAN SELESAI ---
    
            } else {
                // Jika login gagal (tetap sama)
                $this->session->set_flashdata('error', 'Email atau Password salah!');
                redirect('auth/login');
            }
        }
    }

    // Callback function untuk validasi captcha
    public function check_captcha($str) {
        if ($str == $this->session->userdata('captcha_code')) {
            return TRUE;
        } else {
            $this->form_validation->set_message('check_captcha', 'Kode Captcha salah.');
            return FALSE;
        }
    }

    // Halaman dan Proses Registrasi
    public function register() {
    // Validasi form pendaftaran
    $this->form_validation->set_rules('nama_depan', 'Nama Depan', 'required|trim');
    $this->form_validation->set_rules('nama_belakang', 'Nama Belakang', 'trim');
    $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[tbl_users.email]');
    $this->form_validation->set_rules('afiliasi', 'Afiliasi/Institusi', 'required|trim');
    $this->form_validation->set_rules('negara', 'Negara', 'required|trim');
    $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
    $this->form_validation->set_rules('passconf', 'Konfirmasi Password', 'required|matches[password]');

    if ($this->form_validation->run() == FALSE) {
        $this->load->view('auth/register');
    } else {
        // Data untuk dimasukkan ke database
        $data = [
            'nama_depan'    => $this->input->post('nama_depan'),
            'nama_belakang' => $this->input->post('nama_belakang'),
            'email'         => $this->input->post('email'),
            'afiliasi'      => $this->input->post('afiliasi'),
            'negara'        => $this->input->post('negara'),
            'password'      => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
            'peran_sistem'  => 'user'
        ];

        $insert = $this->Auth_model->register_user($data);
        if ($insert) {
            // Kirim email konfirmasi
            $this->_send_registration_email($data['email'], $data['nama_depan']);

            $this->session->set_flashdata('success', 'Registrasi berhasil! Silakan periksa email Anda dan login.');
            redirect('auth/login');
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan. Gagal mendaftarkan akun.');
            redirect('auth/register');
        }
    }
}
    
    // Fungsi untuk mengirim email
    private function _send_registration_email($email, $nama) {
        $config = Array(
			'mailtype'  => 'html', 
			'charset'   => 'iso-8859-1'
		);
		$this->load->library('email', $config);

        $this->email->from('no-reply@stima.unma.ac.id', 'Panitia STIMA UNMA');
        $this->email->to($email);
        $this->email->subject('Pendaftaran Akun Berhasil');
        $this->email->message("Halo {$nama},<br><br>Terima kasih telah mendaftar di sistem manajemen konferensi kami. Akun Anda telah berhasil dibuat.<br><br>Silakan login untuk melanjutkan.<br><br>Hormat kami,<br>Panitia");

        $this->email->send();
    }
    
    // Fungsi Lupa Password (placeholder)
    public function forgot_password() {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
    
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/forgot_password');
        } else {
            $email = $this->input->post('email');
            $user = $this->Auth_model->get_user_by_email($email);
    
            if ($user) {
                // Buat token unik
                $token = bin2hex(random_bytes(32));
                $this->Auth_model->store_reset_token($email, $token);
    
                // Kirim email dengan link reset
                $this->_send_reset_password_email($email, $token);
            }
            
            // Selalu tampilkan pesan sukses untuk keamanan
            // (agar penyerang tidak tahu email mana yang terdaftar)
            $this->session->set_flashdata('success', 'Jika email Anda terdaftar, kami telah mengirimkan link untuk mereset password Anda.');
            redirect('auth/forgot_password');
        }
    }
    
    // Halaman untuk form reset password baru
    public function reset_password($token) {
        $token_data = $this->Auth_model->get_reset_token_data($token);
    
        // Cek token valid atau tidak (ada & tidak lebih dari 1 jam)
        if ($token_data && (time() - strtotime($token_data->created_at)) < 3600) {
            $data['token'] = $token;
            $this->load->view('auth/reset_password', $data);
        } else {
            $this->session->set_flashdata('error', 'Token reset password tidak valid atau telah kedaluwarsa.');
            redirect('auth/forgot_password');
        }
    }
    
    // Proses update password baru
    public function update_password() {
        $this->form_validation->set_rules('password', 'Password Baru', 'required|min_length[6]');
        $this->form_validation->set_rules('passconf', 'Konfirmasi Password', 'required|matches[password]');
        $this->form_validation->set_rules('token', 'Token', 'required');
    
        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, kembalikan ke form dengan error
            $this->reset_password($this->input->post('token'));
        } else {
            $token = $this->input->post('token');
            $new_password = $this->input->post('password');
            $token_data = $this->Auth_model->get_reset_token_data($token);
    
            if ($token_data && (time() - strtotime($token_data->created_at)) < 3600) {
                $this->Auth_model->update_password($token_data->email, $new_password);
                $this->Auth_model->delete_reset_token($token);
    
                $this->session->set_flashdata('success', 'Password Anda telah berhasil diubah. Silakan login.');
                redirect('auth/login');
            } else {
                $this->session->set_flashdata('error', 'Token reset password tidak valid atau telah kedaluwarsa.');
                redirect('auth/forgot_password');
            }
        }
    }
    
    // Fungsi internal untuk kirim email reset password
    private function _send_reset_password_email($email, $token) {
        $config = Array(
			'mailtype'  => 'html', 
			'charset'   => 'iso-8859-1'
		);
		$this->load->library('email', $config);
        
        $reset_link = site_url('auth/reset_password/' . $token);
    
        $this->email->from('no-reply@stima.unma.ac.id', 'Panitia STIMA UNMA');
        $this->email->to($email);
        $this->email->subject('Reset Password Akun Anda');
        $message = "Anda menerima email ini karena ada permintaan untuk mereset password akun Anda.<br><br>";
        $message .= "Silakan klik link di bawah ini untuk mereset password Anda:<br>";
        $message .= "<a href='{$reset_link}'>{$reset_link}</a><br><br>";
        $message .= "Link ini hanya akan aktif selama 1 jam.<br><br>";
        $message .= "Jika Anda tidak merasa melakukan permintaan ini, abaikan saja email ini.<br><br>";
        $message .= "Hormat kami,<br>Panitia";
        $this->email->message($message);
    
        $this->email->send();
    }
    
    public function logout()
    {
        // Hapus semua data dari session
        $this->session->sess_destroy();
        
        // Set pesan flash untuk ditampilkan di halaman login
        // (Pesan ini tidak akan berfungsi jika ditaruh setelah sess_destroy, jadi kita bisa hilangkan atau gunakan trik lain jika perlu)
        // Untuk simpelnya, kita langsung redirect.
        
        redirect('auth/login');
    }
}