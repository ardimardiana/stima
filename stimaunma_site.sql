-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 24, 2025 at 03:47 AM
-- Server version: 8.0.43
-- PHP Version: 8.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stimaunma_site`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_authors`
--

CREATE TABLE `tbl_authors` (
  `author_id` int NOT NULL,
  `paper_id` int NOT NULL,
  `nama_depan` varchar(100) NOT NULL,
  `nama_belakang` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `afiliasi` varchar(255) NOT NULL,
  `negara` varchar(100) NOT NULL,
  `is_corresponding_author` tinyint(1) NOT NULL DEFAULT '0',
  `is_presenting_author` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_events`
--

CREATE TABLE `tbl_events` (
  `event_id` int NOT NULL,
  `nama_event` varchar(255) NOT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `tahun` year NOT NULL,
  `slug_url` varchar(50) NOT NULL,
  `info_pembayaran` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `status` enum('draft','aktif','arsip') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tgl_batas_daftar` date DEFAULT NULL,
  `tgl_batas_submit` date DEFAULT NULL,
  `tgl_pengumuman` date DEFAULT NULL,
  `tgl_mulai_acara` date DEFAULT NULL,
  `tgl_selesai_acara` date DEFAULT NULL,
  `header_loa_path` varchar(255) DEFAULT NULL COMMENT 'Path ke gambar header/kop surat LoA',
  `ketua_panitia` varchar(255) DEFAULT NULL COMMENT 'Nama ketua panitia untuk TTD',
  `sertifikat_aktif` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=Nonaktif, 1=Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_event_registrations`
--

CREATE TABLE `tbl_event_registrations` (
  `registration_id` int NOT NULL,
  `user_id` int NOT NULL,
  `event_id` int NOT NULL,
  `peran_event` enum('peserta','presenter') NOT NULL,
  `tanggal_registrasi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `kode_kehadiran_qr` varchar(100) DEFAULT NULL,
  `status_kehadiran` tinyint(1) NOT NULL DEFAULT '0',
  `sertifikat_path` varchar(255) DEFAULT NULL,
  `sertifikat_presenter_path` varchar(255) DEFAULT NULL,
  `qr_code_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_papers`
--

CREATE TABLE `tbl_papers` (
  `paper_id` int NOT NULL,
  `registration_id` int NOT NULL,
  `topic_id` int NOT NULL,
  `judul` varchar(500) NOT NULL,
  `abstrak` text NOT NULL,
  `kata_kunci` varchar(255) NOT NULL,
  `file_path_initial` varchar(255) DEFAULT NULL,
  `status_artikel` enum('submitted','in_review','revision','revision_submitted','accepted','final_submitted','rejected') DEFAULT 'submitted',
  `file_path_final` varchar(255) DEFAULT NULL,
  `slide_path` varchar(255) DEFAULT NULL,
  `loa_path` varchar(255) DEFAULT NULL,
  `tgl_submit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_paper_chats`
--

CREATE TABLE `tbl_paper_chats` (
  `chat_id` int NOT NULL,
  `paper_id` int NOT NULL,
  `user_id` int NOT NULL COMMENT 'ID Pengirim',
  `sent_by_admin` tinyint(1) NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_password_resets`
--

CREATE TABLE `tbl_password_resets` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payments`
--

CREATE TABLE `tbl_payments` (
  `payment_id` int NOT NULL,
  `registration_id` int NOT NULL,
  `nomor_invoice` varchar(50) NOT NULL,
  `bukti_bayar_path` varchar(255) DEFAULT NULL,
  `status_pembayaran` enum('menunggu','lunas','ditolak','dibatalkan','validasi') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'validasi',
  `tgl_unggah` datetime DEFAULT NULL,
  `tgl_validasi` datetime DEFAULT NULL,
  `validator_id` int DEFAULT NULL,
  `catatan_admin` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pengumuman`
--

CREATE TABLE `tbl_pengumuman` (
  `pengumuman_id` int NOT NULL,
  `event_id` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text,
  `tgl_publish` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reviews`
--

CREATE TABLE `tbl_reviews` (
  `review_id` int NOT NULL,
  `paper_id` int NOT NULL,
  `reviewer_name` varchar(255) DEFAULT NULL,
  `reviewer_email` varchar(255) DEFAULT NULL,
  `reviewer_token` varchar(100) NOT NULL,
  `token_expires_at` datetime NOT NULL,
  `token_first_accessed_at` datetime DEFAULT NULL COMMENT 'Mencatat kapan token pertama kali dibuka',
  `status_review` enum('pending','opened','submitted') NOT NULL DEFAULT 'pending',
  `file_for_reviewer_path` varchar(255) DEFAULT NULL,
  `relevansi` tinyint(1) DEFAULT NULL,
  `kualitas_konten` tinyint(1) DEFAULT NULL,
  `orisinalitas` tinyint(1) DEFAULT NULL,
  `gaya_penulisan` tinyint(1) DEFAULT NULL,
  `rekomendasi` enum('accept','weak_accept','weak_reject','reject') DEFAULT NULL,
  `saran_perbaikan` text,
  `rekomendasi_best_paper` tinyint(1) DEFAULT '0',
  `catatan_untuk_panitia` text,
  `reviewed_file_path` varchar(255) DEFAULT NULL,
  `tgl_submit_review` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rooms`
--

CREATE TABLE `tbl_rooms` (
  `room_id` int NOT NULL,
  `event_id` int NOT NULL,
  `nama_ruang` varchar(255) NOT NULL,
  `lokasi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_schedules`
--

CREATE TABLE `tbl_schedules` (
  `schedule_id` int NOT NULL,
  `event_id` int NOT NULL,
  `room_id` int NOT NULL,
  `paper_id` int DEFAULT NULL,
  `nama_sesi` varchar(255) NOT NULL,
  `waktu_mulai` datetime NOT NULL,
  `waktu_selesai` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_topics`
--

CREATE TABLE `tbl_topics` (
  `topic_id` int NOT NULL,
  `event_id` int NOT NULL,
  `nama_topik` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int NOT NULL,
  `nama_depan` varchar(100) NOT NULL,
  `nama_belakang` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `afiliasi` varchar(255) DEFAULT NULL,
  `negara` varchar(100) DEFAULT NULL,
  `peran_sistem` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `nama_depan`, `nama_belakang`, `email`, `password`, `afiliasi`, `negara`, `peran_sistem`, `created_at`, `updated_at`) VALUES
(1, 'ARDI', 'MARDIANA', 'aim@unma.ac.id', '$2y$10$8KXM7JRukz2BjumfxHXRHuXEs5THUXWrQj5O7LG2N.6jresUJfMxe', 'UNIVERSITAS MAJALENGKA', 'INDONESIA', 'admin', '2025-08-22 07:15:48', '2025-08-22 15:33:41'),
(2, 'Mawar', 'Melati', 'ardimardiana@gmail.com', '$2y$10$xY.pp2Lm0KRxp08dWkDFYeli0ZzIzIqBGilTGlLlxxGriw692SjFC', 'STIMIK LIKMI BANDUNG', 'Indonesia', 'user', '2025-08-22 15:46:14', '2025-08-23 13:24:35'),
(3, 'Infotech', 'Journal', 'journalinfotech@gmail.com', '$2y$10$0eyBglRIx5j0VOrROiSRWeSHNBa2GlLrL8vXmpHeQtviMFbgbX1WW', 'Universitas Majalengka', 'Indonesia', 'user', '2025-08-22 16:34:08', '2025-08-22 16:34:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_authors`
--
ALTER TABLE `tbl_authors`
  ADD PRIMARY KEY (`author_id`),
  ADD KEY `paper_id` (`paper_id`);

--
-- Indexes for table `tbl_events`
--
ALTER TABLE `tbl_events`
  ADD PRIMARY KEY (`event_id`),
  ADD UNIQUE KEY `slug_url` (`slug_url`);

--
-- Indexes for table `tbl_event_registrations`
--
ALTER TABLE `tbl_event_registrations`
  ADD PRIMARY KEY (`registration_id`),
  ADD UNIQUE KEY `user_event_unique` (`user_id`,`event_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `tbl_papers`
--
ALTER TABLE `tbl_papers`
  ADD PRIMARY KEY (`paper_id`),
  ADD KEY `registration_id` (`registration_id`),
  ADD KEY `topic_id` (`topic_id`);

--
-- Indexes for table `tbl_paper_chats`
--
ALTER TABLE `tbl_paper_chats`
  ADD PRIMARY KEY (`chat_id`),
  ADD KEY `paper_id` (`paper_id`);

--
-- Indexes for table `tbl_password_resets`
--
ALTER TABLE `tbl_password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_index` (`email`);

--
-- Indexes for table `tbl_payments`
--
ALTER TABLE `tbl_payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD UNIQUE KEY `registration_id` (`registration_id`),
  ADD UNIQUE KEY `nomor_invoice` (`nomor_invoice`),
  ADD KEY `validator_id` (`validator_id`);

--
-- Indexes for table `tbl_pengumuman`
--
ALTER TABLE `tbl_pengumuman`
  ADD PRIMARY KEY (`pengumuman_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `tbl_reviews`
--
ALTER TABLE `tbl_reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD UNIQUE KEY `reviewer_token` (`reviewer_token`),
  ADD KEY `paper_id` (`paper_id`);

--
-- Indexes for table `tbl_rooms`
--
ALTER TABLE `tbl_rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `tbl_schedules`
--
ALTER TABLE `tbl_schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `paper_id` (`paper_id`);

--
-- Indexes for table `tbl_topics`
--
ALTER TABLE `tbl_topics`
  ADD PRIMARY KEY (`topic_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_authors`
--
ALTER TABLE `tbl_authors`
  MODIFY `author_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_events`
--
ALTER TABLE `tbl_events`
  MODIFY `event_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_event_registrations`
--
ALTER TABLE `tbl_event_registrations`
  MODIFY `registration_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_papers`
--
ALTER TABLE `tbl_papers`
  MODIFY `paper_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_paper_chats`
--
ALTER TABLE `tbl_paper_chats`
  MODIFY `chat_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_password_resets`
--
ALTER TABLE `tbl_password_resets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_payments`
--
ALTER TABLE `tbl_payments`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_pengumuman`
--
ALTER TABLE `tbl_pengumuman`
  MODIFY `pengumuman_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_reviews`
--
ALTER TABLE `tbl_reviews`
  MODIFY `review_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_rooms`
--
ALTER TABLE `tbl_rooms`
  MODIFY `room_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_schedules`
--
ALTER TABLE `tbl_schedules`
  MODIFY `schedule_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_topics`
--
ALTER TABLE `tbl_topics`
  MODIFY `topic_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_authors`
--
ALTER TABLE `tbl_authors`
  ADD CONSTRAINT `fk_author_paper` FOREIGN KEY (`paper_id`) REFERENCES `tbl_papers` (`paper_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_event_registrations`
--
ALTER TABLE `tbl_event_registrations`
  ADD CONSTRAINT `fk_reg_event` FOREIGN KEY (`event_id`) REFERENCES `tbl_events` (`event_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reg_user` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_papers`
--
ALTER TABLE `tbl_papers`
  ADD CONSTRAINT `fk_paper_reg` FOREIGN KEY (`registration_id`) REFERENCES `tbl_event_registrations` (`registration_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_paper_topic` FOREIGN KEY (`topic_id`) REFERENCES `tbl_topics` (`topic_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_paper_chats`
--
ALTER TABLE `tbl_paper_chats`
  ADD CONSTRAINT `fk_chat_paper` FOREIGN KEY (`paper_id`) REFERENCES `tbl_papers` (`paper_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_payments`
--
ALTER TABLE `tbl_payments`
  ADD CONSTRAINT `fk_payment_reg` FOREIGN KEY (`registration_id`) REFERENCES `tbl_event_registrations` (`registration_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_payment_validator` FOREIGN KEY (`validator_id`) REFERENCES `tbl_users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `tbl_pengumuman`
--
ALTER TABLE `tbl_pengumuman`
  ADD CONSTRAINT `fk_pengumuman_event` FOREIGN KEY (`event_id`) REFERENCES `tbl_events` (`event_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_reviews`
--
ALTER TABLE `tbl_reviews`
  ADD CONSTRAINT `fk_review_paper` FOREIGN KEY (`paper_id`) REFERENCES `tbl_papers` (`paper_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_rooms`
--
ALTER TABLE `tbl_rooms`
  ADD CONSTRAINT `fk_room_event` FOREIGN KEY (`event_id`) REFERENCES `tbl_events` (`event_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_schedules`
--
ALTER TABLE `tbl_schedules`
  ADD CONSTRAINT `fk_schedule_event` FOREIGN KEY (`event_id`) REFERENCES `tbl_events` (`event_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_schedule_paper` FOREIGN KEY (`paper_id`) REFERENCES `tbl_papers` (`paper_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_schedule_room` FOREIGN KEY (`room_id`) REFERENCES `tbl_rooms` (`room_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_topics`
--
ALTER TABLE `tbl_topics`
  ADD CONSTRAINT `fk_topic_event` FOREIGN KEY (`event_id`) REFERENCES `tbl_events` (`event_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
