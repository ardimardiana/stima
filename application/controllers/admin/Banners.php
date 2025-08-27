<?php
class Banners extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Banner_model');
        $this->load->model('Event_model');
    }

    // Halaman utama manajemen banner
    public function index($event_id) {
        $data['title'] = 'Manajemen Banner';
        $data['event'] = $this->Event_model->get_event_by_id($event_id);
        $data['banners'] = $this->Banner_model->get_by_event($event_id);

        $this->load->view('admin/_partials/_header', $data);
        $this->load->view('admin/banners/index', $data);
        $this->load->view('admin/_partials/_footer');
    }

    // Proses upload banner baru
    public function upload($event_id) {
        $config['upload_path']   = './uploads/banners/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['max_size']      = 2048;
        $config['encrypt_name']  = TRUE;

        if (!is_dir($config['upload_path'])) { mkdir($config['upload_path'], 0777, TRUE); }
        $this->load->library('upload', $config);

        if ($this->upload->do_upload('banner_image')) {
            $data = [
                'event_id' => $event_id,
                'image_path' => 'uploads/banners/' . $this->upload->data('file_name')
            ];
            $this->Banner_model->insert($data);
            $this->session->set_flashdata('success', 'Banner baru berhasil diunggah.');
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors());
        }
        redirect('admin/banners/index/' . $event_id);
    }

    // Proses hapus banner
    public function delete($banner_id) {
        $banner = $this->Banner_model->get_by_id($banner_id);
        if ($banner) {
            // Hapus file fisik
            if (file_exists($banner->image_path)) {
                unlink($banner->image_path);
            }
            // Hapus record database
            $this->Banner_model->delete($banner_id);
            $this->session->set_flashdata('success', 'Banner berhasil dihapus.');
            redirect('admin/banners/index/' . $banner->event_id);
        } else {
            show_404();
        }
    }
}