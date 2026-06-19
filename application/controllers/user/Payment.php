<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends MY_Controller { // Atau CI_Controller sesuai base controller Anda

    public function __construct() {
        parent::__construct();
        $this->load->model('Event_model');
        $this->load->model('Participant_model'); // Sesuaikan dengan model pendaftaran Anda
        
        // Pastikan user sudah login
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
    }
    
    function jador()
    {
        $payload = json_decode('{"event":"payment.received","data":{"id":"589b8f95-690c-43f3-8c98-80f66b5d34b5","transactionId":"589b8f95-690c-43f3-8c98-80f66b5d34b5","status":"SUCCESS","transactionStatus":"created","createdAt":"2026-06-19T16:10:49.918Z","updatedAt":"2026-06-19T16:11:09.413Z","merchantId":"90e811f8-416d-447d-9014-1e094714074f","merchantName":"Ardi Mardiana ","merchantEmail":"ardimardiana@gmail.com","customerId":"63526eb6-6e71-4ed8-a37f-06513ad94386","customerName":"IT UNMA","customerEmail":"it@unma.ac.id","customerMobile":"000000000000","amount":10000,"paymentLinkAmount":10000,"isAdminFeeBorneByCustomer":null,"isChannelFeeBorneByCustomer":null,"productId":"dcc3c084-704f-46cb-85a1-c55185565cf1","productName":"INVOICE","productDescription":"Biaya Pemakalah UNMA - SEMINAR TEKNOLOGI MAJALENGKA (STIMA) 10 Tahun 2026","productType":"invoice","pixelFbp":null,"pixelFbc":null,"qty":1,"couponUsed":null,"paymentMethod":"QRIS","nettAmount":10000,"extraData":{"noCustomer":"454","idProd":"453"}}}');
        var_dump($payload);
    }
    
    /**
     * Tampilan awal pilihan jenis biaya (Umum / UNMA) + Checkbox Persetujuan Self-Declare
     */
    public function index($registration_id=null) {
        
        $user_id = $this->session->userdata('user_id');

        // 1. Ambil data event yang sedang aktif beserta nominal biayanya
        $event = $this->Event_model->get_active_event(); 
        if (!$event) {
            $this->session->set_flashdata('error', 'Tidak ada event aktif saat ini.');
            redirect('user/dashboard');
        }

        // 2. Ambil data partisipasi/pendaftaran aktif user untuk tahu jenis kepesertaan (Pemakalah / Peserta)
        // Ambil pendaftaran terakhir yang status pembayarannya belum lunas
        $participant = $this->db->order_by('registration_id', 'DESC')
                                ->get_where('tbl_event_registrations', [
                                    'registration_id'=>$registration_id,
                                    'user_id' => $user_id, 
                                    'event_id' => $event->event_id
                                ])->row();
        //var_dump($user_id); exit;
        if (!$participant) {
            $this->session->set_flashdata('error', 'Anda belum memilih jenis kepesertaan. Silakan daftar melalui dashboard.');
            redirect('user/dashboard');
        }

        // Jika user sudah memiliki invoice Mayar yang aktif/lunas, langsung redirect atau beri info
        $existing_payment = $this->db->get_where('tbl_payments', [
            'registration_id' => $participant->registration_id,
            'mayar_status !=' => 'canceled'
        ])->row();

        if ($existing_payment && $existing_payment->mayar_status == 'lunas') {
            $this->session->set_flashdata('success', 'Pembayaran Anda telah diverifikasi dan lunas.');
            redirect('user/dashboard');
        }

        // 3. Kirim data ke View
        $data['title']       = "Pilih Tipe Pembayaran";
        $data['event']       = $event;
        $data['participant'] = $participant; // Berisi jenis kepesertaan (Pemakalah/Peserta)
        $data['payment']     = $existing_payment;

        // Load view pendaftaran pembayaran
        $this->load->view('user/payment/index', $data);
    }

    // Fungsi submit form untuk generate invoice via Mayar
    // Fungsi submit form untuk generate invoice via Mayar
    public function generate_invoice() {
        // Tangkap registration_id dari form
        $registration_id  = $this->input->post('registration_id'); 
        $tipe_kepesertaan = $this->input->post('tipe_kepesertaan'); // 'Pemakalah' atau 'Peserta'
        $tipe_harga       = $this->input->post('tipe_harga');       // 'Umum' atau 'UNMA'
        $is_agreed        = $this->input->post('agreement');        // Checkbox self-declare

        if (!$is_agreed) {
            $this->session->set_flashdata('error', 'Anda harus menyetujui ketentuan peserta/mahasiswa.');
            redirect('user/payment/index/'.$registration_id);
        }

        $user_id = $this->session->userdata('user_id');
        $user    = $this->db->get_where('tbl_users', ['user_id' => $user_id])->row();
        $event   = $this->Event_model->get_active_event();

        // 1. Tentukan Harga dari 4 Kategori (tbl_events)
        $amount = 0;
        $desc   = '';
        if ($tipe_kepesertaan == 'presenter' && $tipe_harga == 'Umum') {
            $amount = $event->fee_pemakalah_umum;
            $desc   = "Biaya Pemakalah Umum - " . $event->nama_event;
        } elseif ($tipe_kepesertaan == 'presenter' && $tipe_harga == 'UNMA') {
            $amount = $event->fee_pemakalah_unma;
            $desc   = "Biaya Pemakalah UNMA - " . $event->nama_event;
        } elseif ($tipe_kepesertaan == 'peserta' && $tipe_harga == 'Umum') {
            $amount = $event->fee_peserta_umum;
            $desc   = "Biaya Peserta Umum - " . $event->nama_event;
        } elseif ($tipe_kepesertaan == 'peserta' && $tipe_harga == 'UNMA') {
            $amount = $event->fee_peserta_mahasiswa;
            $desc   = "Biaya Peserta Mahasiswa - " . $event->nama_event;
        }

        // ... (Kode pengecekan harga dan set $amount sebelumnya) ...

        // 1. Ambil data payment_id yang sudah dibuat saat user mendaftar di dashboard
        $payment_exist = $this->db->get_where('tbl_payments', ['registration_id' => $registration_id])->row();
        
        if (!$payment_exist) {
            $this->session->set_flashdata('error', 'Data pendaftaran tidak ditemukan.');
            redirect('user/payment/index/'.$registration_id);
        }

        $payment_id = $payment_exist->payment_id; // Ambil ID yang benar dari database

        // 2. Buat nomor invoice lokal untuk tampilan visual
        $nomor_invoice = 'INV-' . date('Ymd') . '-' . strtoupper(substr(md5(time()), 0, 5));

        // 3. Update tabel payment dengan data invoice lokal
        $data_payment = [
            'nomor_invoice' => $nomor_invoice,
            'tgl_unggah'    => date('Y-m-d H:i:s'),
            'amount' => $amount 
        ];
        $this->db->where('payment_id', $payment_id);
        $this->db->update('tbl_payments', $data_payment);
        
        // 4. Request ke Mayar API
        $payload = [
            "name"        => $user->nama_depan.' '.$user->nama_belakang,
            "email"       => $user->email,
            "mobile"      => $user->phone ?? "000000000000",
            "redirectUrl" => base_url("user/dashboard"),
            "description" => $desc,
            "expiredAt"   => date("Y-m-d\TH:i:s.000\Z", strtotime('+1 days')),
            "items" => [[
                "quantity"    => 1,
                "rate"        => (int)$amount,
                "description" => $desc
            ]],
            "extraData" => [
                "noCustomer" => (string)$registration_id,
                "idProd"     => (string)$payment_id // Kunci utama untuk ditangkap Webhook Mayar nanti!
            ]
        ];
        
        // ... (Proses CURL tetap sama seperti kode Anda) ...
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $_ENV['mayar_url'] . '/invoice/create');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $_ENV['mayar_api'],
            'Content-Type: application/json'
        ]);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $res = json_decode($response);

        if ($http_code == 200 && isset($res->data->id)) {
            // 5. Update Database Lokal dengan Data Mayar
            $this->db->where('payment_id', $payment_id);
            $this->db->update('tbl_payments', [
                'mayar_status'  => 'requested',
                'mayar_invoice_id' => $res->data->id, // ID asli dari Mayar (bisa dipakai untuk identifikasi/cek API manual)
                'mayar_link'       => $res->data->link // Link murni untuk dialihkan ke halaman bayar
            ]);

            // Redirect ke controller invoice lokal yang nanti akan forward ke mayar_link
            redirect('user/invoice/index/' . $res->data->id);
        } else {
            // Jika gagal mendapatkan link dari Mayar, biarkan data payment (jangan dihapus)
            // karena user masih terdaftar di event, tapi arahkan untuk mencoba klik bayar lagi.
            $this->session->set_flashdata('error', 'Gagal memproses tagihan dari server pembayaran. Silakan coba beberapa saat lagi.');
            redirect('user/payment/index/'.$registration_id);
        }
    }
}