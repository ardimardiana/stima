<?php
class Auth_model extends CI_Model {

    public function get_user_by_email($email) {
        return $this->db->get_where('tbl_users', ['email' => $email])->row();
    }

    public function register_user($data) {
        return $this->db->insert('tbl_users', $data);
    }
    
    public function store_reset_token($email, $token) {
        // Hapus token lama jika ada untuk email yang sama
        $this->db->delete('tbl_password_resets', ['email' => $email]);
        // Simpan token baru
        $data = ['email' => $email, 'token' => $token];
        return $this->db->insert('tbl_password_resets', $data);
    }

    public function get_reset_token_data($token) {
        $query = $this->db->get_where('tbl_password_resets', ['token' => $token], 1);
        return $query->row();
    }

    public function update_password($email, $new_password) {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $this->db->where('email', $email);
        return $this->db->update('tbl_users', ['password' => $hashed_password]);
    }
    
    public function delete_reset_token($token) {
        return $this->db->delete('tbl_password_resets', ['token' => $token]);
    }
}