<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webhook extends CI_Controller {

    public function mayar_callback() {
        // Mayar mengirim data webhook sebagai JSON Raw
        $jsonPayload = file_get_contents('php://input');
        $payload = json_decode($jsonPayload);

        // Abaikan jika tidak valid
        if (!$payload) {
            http_response_code(400);
            return;
        }

        // Tangkap event berhasil bayar
        if (isset($payload->event) && $payload->event === 'payment.received') {
            
            // Mengambil custom field "idProd" yang kita selipkan di "extraData" saat create invoice
            // Mayar membawanya kembali ke field "productId" pada webhook payment.received
            $payment_id_lokal = isset($payload->data->productId) ? $payload->data->productId : null;

            if ($payment_id_lokal) {
                // Update tbl_payments lunas secara otomatis!
                $this->db->where('id', $payment_id_lokal);
                $this->db->update('tbl_payments', [
                    'mayar_status' => 'lunas',
                    'status'       => 'verified' // Ganti menggantikan verifikasi manual admin sebelumnya
                ]);
            }
            
            http_response_code(200);
            echo json_encode(["status" => "OK"]);
        } else {
            // Event lain (misal: invoice expired) bisa dikelola nanti jika diperlukan
            http_response_code(200);
            echo json_encode(["status" => "Ignored"]);
        }
    }
}