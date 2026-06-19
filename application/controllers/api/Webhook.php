<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Panggil namespace QR Code sesuai dengan library yang Anda gunakan
// (Sesuaikan dengan yang ada di controller admin/Payments)
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class Webhook extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        // Load database atau model lain jika diperlukan
        $this->load->database();
    }

    public function mayar() {
        
        // ==========================================
        // 0. VERIFIKASI WEBHOOK TOKEN
        // ==========================================
        
        // Ambil token dari request header
        $requestToken = $this->input->get_request_header('X-Callback-Token', TRUE);
        
        // Ambil expected token dari Environment Variable
        $expectedToken = isset($_ENV['mayar_token']) ? $_ENV['mayar_token'] : null;
    
        // Cek apakah token di ENV sudah disetting
        if (!$expectedToken) {
            log_message('error', '[Mayar Webhook] mayar_token not configured in ENV');
            http_response_code(500);
            echo json_encode(["error" => "Webhook configuration error"]);
            return;
        }
    
        // Cek apakah header X-Callback-Token ada
        if (!$requestToken) {
            log_message('error', '[Mayar Webhook] X-Callback-Token header missing');
            http_response_code(401);
            echo json_encode(["error" => "Unauthorized: Token missing"]);
            return;
        }
    
        // Cocokkan token dari header dengan yang ada di ENV
        if ($requestToken !== $expectedToken) {
            log_message('error', '[Mayar Webhook] Token mismatch');
            http_response_code(401);
            echo json_encode(["error" => "Unauthorized: Invalid token"]);
            return;
        }
        
        // ==========================================
        // 1. PROSES PAYLOAD JIKA VERIFIKASI BERHASIL
        // ==========================================
        $jsonPayload = file_get_contents('php://input');
        
        // Simpan Webhook
        file_put_contents(APPPATH . 'logs/webhook_test.txt', file_get_contents('php://input') . PHP_EOL, FILE_APPEND);
        
        // Gunakan true agar di-decode menjadi Array Asosiatif (lebih aman dan stabil di PHP)
        $payload = json_decode($jsonPayload, true); 
        
        // Abaikan jika payload kosong atau bukan array
        if (!$payload || !is_array($payload)) {
            http_response_code(400);
            echo json_encode(["status" => "Invalid Payload"]);
            return;
        }
    
        // 2. Tangkap event payment.received
        if (isset($payload['event']) && $payload['event'] === 'payment.received') {
            
            // Ambil data extraData dengan aman
            $extraData = isset($payload['data']['extraData']) ? $payload['data']['extraData'] : null;
            
            // Coba ambil idProd (sesuai payload kamu: "453")
            $payment_id_lokal = isset($extraData['idProd']) ? $extraData['idProd'] : null;
    
            if ($payment_id_lokal) {
                
                // Ambil data payment dari database
                $payment = $this->db->get_where('tbl_payments', ['payment_id' => $payment_id_lokal])->row();
    
                // Pastikan data payment ditemukan
                if ($payment) {
                    
                    // Mencegah double proses jika statusnya sudah lunas / verified
                    if ($payment->status_pembayaran !== 'verified') {
                        
                        // Update tbl_payments menjadi lunas
                        $this->db->where('payment_id', $payment_id_lokal);
                        $update = $this->db->update('tbl_payments', [
                            'mayar_status'      => 'lunas',
                            'status_pembayaran' => 'verified',
                            'tgl_validasi'      => date('Y-m-d H:i:s'),
                            'validator_id'      => 394 
                        ]);
    
                        if ($update) {
                            // Generate QR Code untuk Peserta jika update berhasil
                            $this->_generate_qr_code($payment->registration_id);
                            
                            http_response_code(200);
                            echo json_encode(["status" => "Success", "message" => "Payment verified and QR generated"]);
                            return;
                        } else {
                            // Jika query update gagal karena masalah database
                            http_response_code(200);
                            echo json_encode(["status" => "Error", "message" => "Database update failed ".$payment_id_lokal]);
                            return;
                        }
    
                    } else {
                        // Jika sudah pernah divalidasi sebelumnya
                        http_response_code(200);
                        echo json_encode(["status" => "Skipped", "message" => "Payment already verified"]);
                        return;
                    }
    
                } else {
                    // ID ada di payload tapi TIDAK ditemukan di database tbl_payments
                    http_response_code(200);
                    echo json_encode(["status" => "Not Found", "message" => "payment_id $payment_id_lokal not found in DB"]);
                    return;
                }
            } else {
                http_response_code(200);
                echo json_encode(["status" => "Bad Request", "message" => "idProd missing from extraData"]);
                return;
            }
            
        } else {
            // Event selain payment.received diabaikan tapi tetap beri respon 200 agar Mayar tidak mengirim ulang
            http_response_code(200);
            echo json_encode(["status" => "Ignored", "event" => isset($payload['event']) ? $payload['event'] : 'unknown']);
        }
    }

    // Fungsi Generate QR Code dipindahkan ke sini
    private function _generate_qr_code($registration_id) {
        // Data unik untuk QR Code
        $registration_data = $this->db->get_where('tbl_event_registrations', ['registration_id' => $registration_id])->row();
        
        if (!$registration_data) return false;

        $qr_data = $registration_data->kode_kehadiran_qr; // Gunakan kode unik yang sudah ada

        if (empty($qr_data)) {
            // Jika kode belum ada, buat yang baru
            $qr_data = 'REG-' . $registration_id . '-' . uniqid();
            $this->db->where('registration_id', $registration_id)
                     ->update('tbl_event_registrations', ['kode_kehadiran_qr' => $qr_data]);
        }

        // Buat objek QR Code
        $qrCode = QrCode::create($qr_data);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Simpan file QR Code
        $path = 'uploads/qrcodes/';
        if (!is_dir($path)) { mkdir($path, 0777, TRUE); }
        $filename = $registration_id . '.png';
        $result->saveToFile($path . $filename);

        // Simpan path ke database
        $this->db->where('registration_id', $registration_id);
        $this->db->update('tbl_event_registrations', ['qr_code_path' => $path . $filename]);
    }
}