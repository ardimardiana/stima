<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Reports extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Participant_model');
        $this->load->model('Event_model');
    }
    
    public function export_all_participants_excel($event_id) {
        // 1. Ambil data dari model (kita gunakan fungsi yang sudah ada)
        $participants = $this->Participant_model->get_all_participants_for_report($event_id);
        $event = $this->Event_model->get_event_by_id($event_id);

        // 2. Buat objek Spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Daftar Peserta');

        // 3. Buat Header Tabel di Excel
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Lengkap');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Afilasi');
        $sheet->setCellValue('E1', 'Peran');
        $sheet->setCellValue('F1', 'Status Pembayaran');
        $sheet->setCellValue('G1', 'Status Kehadiran');

        // Styling Header (Opsional, tapi membuat lebih rapi)
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4285F4']]
        ];
        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);


        // 4. Isi data peserta ke dalam baris-baris Excel
        $rowNum = 2; // Mulai dari baris ke-2
        $no = 1;
        foreach ($participants as $p) {
            $sheet->setCellValue('A' . $rowNum, $no++);
            $sheet->setCellValue('B' . $rowNum, $p->nama_depan . ' ' . $p->nama_belakang);
            $sheet->setCellValue('C' . $rowNum, $p->email);
            $sheet->setCellValue('D' . $rowNum, ucfirst($p->afiliasi));
            $sheet->setCellValue('E' . $rowNum, ucfirst($p->peran_event));
            $sheet->setCellValue('F' . $rowNum, ucfirst($p->status_pembayaran));
            $sheet->setCellValue('G' . $rowNum, ($p->status_kehadiran == 1) ? 'Hadir' : 'Belum Hadir');
            $rowNum++;
        }

        // Auto-size kolom agar pas dengan konten
        foreach(range('A','F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // 5. Kirim file ke browser untuk di-download
        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan_semua_peserta_' . $event->slug_url . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit();
    }

    // Menampilkan halaman laporan daftar peserta
    public function index($event_id) {
        $data['title'] = 'Laporan Daftar Peserta';
        $data['event'] = $this->Event_model->get_event_by_id($event_id);
        $data['participants'] = $this->Participant_model->get_all_participants_for_report($event_id);
        
        if (!$data['event']) {
            show_404();
        }

        $this->load->view('admin/_partials/_header', $data);
        $this->load->view('admin/reports/participants', $data);
        $this->load->view('admin/_partials/_footer');
    }
    
    public function import_certificates($event_id) {
        $config['upload_path']   = './uploads/temp_csv/';
        $config['allowed_types'] = 'csv';
        $config['max_size']      = 2048;
        $config['encrypt_name']  = TRUE;
    
        if (!is_dir($config['upload_path'])) { mkdir($config['upload_path'], 0777, TRUE); }
    
        $this->load->library('upload', $config);
    
        if (!$this->upload->do_upload('csv_file')) {
            $this->session->set_flashdata('error', $this->upload->display_errors());
        } else {
            $file_data = $this->upload->data();
            $file_path = $config['upload_path'] . $file_data['file_name'];
    
            $update_batch_data = [];
            $is_header = TRUE; // Flag untuk melewati baris header
    
            // Buka file CSV untuk dibaca
            if (($handle = fopen($file_path, "r")) !== FALSE) {
                // Baca file baris per baris, dengan pemisah titik koma (;)
                while (($row = fgetcsv($handle, 1000, ";")) !== FALSE) {
                    // Lewati baris pertama (header)
                    if ($is_header) {
                        $is_header = FALSE;
                        continue;
                    }
    
                    // Pastikan baris tidak kosong
                    if (!empty($row[0])) {
                        $update_batch_data[] = [
                            'registration_id'           => $row[0], // Kolom pertama (index 0)
                            'sertifikat_path'           => !empty($row[4]) ? $row[4] : NULL, // Kolom ke-5 (index 4)
                            'sertifikat_presenter_path' => !empty($row[5]) ? $row[5] : NULL  // Kolom ke-6 (index 5)
                        ];
                    }
                }
                fclose($handle); // Tutup file
            }
            
            //var_dump($update_batch_data); exit;
    
            if (!empty($update_batch_data)) {
                // Lakukan update massal ke database
                $this->db->update_batch('tbl_event_registrations', $update_batch_data, 'registration_id');
                $this->session->set_flashdata('success', count($update_batch_data) . ' data sertifikat berhasil diimpor/diperbarui.');
            } else {
                $this->session->set_flashdata('warning', 'Tidak ada data untuk diimpor dari file CSV.');
            }
            
            unlink($file_path); // Hapus file sementara
        }
        redirect('admin/reports/participants/' . $event_id);
    }
    
    public function export_attendees($event_id) {
        $event = $this->Event_model->get_event_by_id($event_id);
        $filename = 'peserta_hadir_' . $event->slug_url . '.csv';
        
        // Set header untuk download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
    
        // Ambil data peserta yang hadir
        $attendees = $this->Participant_model->get_attendees_for_export($event_id);
    
        // Buka output stream PHP
        $output = fopen('php://output', 'w');
    
        // Tulis header CSV (kolom ini yang akan Anda isi)
        fputcsv($output, [
            'registration_id', 
            'nama_lengkap', 
            'email', 
            'peran', 
            'sertifikat_path', 
            'sertifikat_presenter_path'
        ]);
    
        // Tulis data ke CSV
        foreach ($attendees as $row) {
            $csv_row = [
                $row['registration_id'],
                $row['nama_depan'] . ' ' . $row['nama_belakang'],
                $row['email'],
                $row['peran_event'],
                '', // Kolom sertifikat_path sengaja dikosongkan
                ''  // Kolom sertifikat_presenter_path sengaja dikosongkan
            ];
            fputcsv($output, $csv_row);
        }
    
        fclose($output);
        exit();
    }
}