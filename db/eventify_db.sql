-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 04, 2026 at 04:54 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eventify_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `organizer_id` int(11) DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `date_event` date DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL,
  `price` varchar(50) DEFAULT NULL,
  `reg_link` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `poster` varchar(255) DEFAULT NULL,
  `cp_name` varchar(100) DEFAULT NULL,
  `cp_wa` varchar(30) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `organizer_id`, `title`, `category`, `date_event`, `location`, `price`, `reg_link`, `description`, `poster`, `cp_name`, `cp_wa`, `instagram`, `status`, `created_at`) VALUES
(3, NULL, 'REVOIST 5.0', 'Teknologi', '2025-11-09', 'Kaze Headquarter, Karawang', '25000', 'https://example.com', 'Event teknologi dan AI untuk mahasiswa', 'event3.jpg', 'Himpunan Mahasiswa Sistem Informasi', '08123456789', '', 'approved', '2025-12-27 04:00:13'),
(9, 2, 'Seminar Workshop Education Fair 2025', 'Pendidikan', '2025-12-27', 'Aula Husni Hamid', '0', 'https://www.instagram.com/', 'bla bla bla', '1766845971_694fee138ea2c.jpg', 'Himpunan Mahasiswa Sistem Informasi', '085281632064', 'https://www.instagram.com/edufair.himsika/', 'approved', '2025-12-27 14:32:51'),
(10, 2, 'Playoff 2', 'Olahraga', '2025-12-31', 'Lapangan Olahraga Karawang', '10000', 'https://www.instagram.com/', 'bla bla bla', '1766895009_6950ada1cfc89.jpeg', 'Himpunan Mahasiswa Sistem Informasi', '085281632064', 'https://www.instagram.com/edufair.himsika/', 'approved', '2025-12-28 04:10:09'),
(11, 2, 'Pentas Seni Revoist 5.0', 'Seni', '2026-01-08', 'Zoom Meeting', '0', 'https://www.instagram.com/', 'bla bla bla', '1766895077_6950ade540511.jpeg', 'Himpunan Mahasiswa Sistem Informasi', '085281632064', 'https://www.instagram.com/edufair.himsika/', 'approved', '2025-12-28 04:11:17'),
(12, 2, 'Talkshow & Exhibition Silogy Expo 2025 - Education Fair', 'Pendidikan', '2025-12-31', 'Technomart Karawang', '0', 'https://www.instagram.com/', 'asdasd', '1766895180_6950ae4cbbbac.jpeg', 'Himpunan Mahasiswa Sistem Informasi', '085281632064', 'https://www.instagram.com/edufair.himsika/', 'approved', '2025-12-28 04:13:00'),
(14, 3, 'Information System Gathering', 'Pendidikan', '2026-02-16', 'Agro Kartika Karawang', '90000', 'https://www.instagram.com/', '[INFORMATION SYSTEM GATHERING 2025 🍃]\r\n\r\nHalo InforSys! 🤖👋🏻\r\n\r\nJangan lupa, besok adalah hari yang sudah kita nanti-nantikan, yaitu acara Information System Gathering 2025 dengan tema \"Building Sense of Solidarity and Warmth While Embracing Responsibility\". Yuk, persiapkan diri kalian untuk mengikuti kegiatan ini dari awal hingga akhir🤩\r\n\r\n📅 Tanggal: Sabtu & Minggu, 11-12 Oktober 2025\r\n⏰ Waktu: Pukul 06.30 WIB s.d. Selesai\r\n🏫 Tempat: Agro Kartika Karawang\r\n\r\nJangan sampai terlambat ya. Siapkan semangat dan antusiasme kalian untuk belajar dan berkolaborasi.\r\n\r\nMari bersama-sama kita kembangkan potensi untuk menciptakan prestasi✨\r\n\r\nSampai jumpa besok🔥\r\n\r\n#ISGATH2025\r\n#HIMSIKA2025\r\n#KabinetNestoria \r\n#NestoriaJourney\r\n#KembangkanPotensiCiptakanPrestasi  \r\n#FasilkomUnsika', '1766931090_69513a92165a0.jpeg', 'Himpunan Mahasiswa Sistem Informasi', '085281632064', 'https://www.instagram.com/edufair.himsika/', 'approved', '2025-12-28 14:11:30'),
(15, 3, 'Opening Ceremony Study Club Himsika', 'Pendidikan', '2026-01-06', 'Zoom Meeting', '0', 'https://www.instagram.com/', '[OPENING CEREMONY STUDY CLUB 2025]\r\n\r\nAssalamu\'alaikum Wr. Wb.\r\n\r\nSelamat pagi, Inforsys! 🌟\r\n\r\nDengan bangga, kami mengundang teman-teman untuk hadir dalam Opening Ceremony Study Club  2025 yang akan segera dilaksanakan. Acara ini merupakan bagian dari rangkaian kegiatan yang dirancang untuk mendukung pengembangan keterampilan dan pengetahuan di bidang Web Development dan Data Analyst. \r\n\r\n📅 Tanggal: Selasa, 19 Agustus 2025 \r\n🕧 Waktu: 19.30 WIB hingga selesai  \r\n📍 Tempat: Zoom Meeting : https://s.id/OpeningCeremony-StudyClub2025\r\n\r\nJangan sampai ketinggalan! Pastikan kamu hadir memeriahkan Acara Study Club ini ya!🔥\r\n\r\nTerimakasih atas perhatiannya 😊✨  \r\nWassalamu\'alaikum Wr. Wb.', '1766931192_69513af8b0391.jpeg', 'Himpunan Mahasiswa Sistem Informasi', '085281632064', 'https://www.instagram.com/edufair.himsika/', 'approved', '2025-12-28 14:13:12'),
(16, 3, 'Make Connection', 'Volunteer', '2025-12-31', 'UIN SGD Bandung', '35000', 'https://www.instagram.com/', '[PELAKSANAAN MAKE CONNECTION 2025]\r\n\r\n✨ Tema kegiatan:\r\n\"Kolaborasi antar himpunan mahasiswa untuk meningkatkan value organisasi dan mempererat kekeluargaan dengan cara membangun koneksi\"\r\n\r\n🗓 Tanggal: Sabtu, 5  Juli 2025 \r\n🕐 Waktu: 06.15 - selesai\r\n📍 Lokasi : UIN Sunan Gunung Djati Bandung \r\n📌 Titik kumpul: Gerbang Mahkota 2\r\n👔 Dresscode: PDH HIMSIKA\r\n\r\n💡 Ini bukan sekedar kunjungan biasa-ini adalah kesempatan emas untuk:\r\n✅ Bertukar ide & inovasi\r\n✅ Membangun jaringan kolaborasi antar organisasi\r\n✅ Meyerap pengalaman baru yang bisa kita terapkan di himpunan sendiri.\r\n\r\nUntuk info lebih lanjut, hubungi:\r\n📞Rheza : ‪081286529115\r\n\r\nMari kita hadir sebagai delegasi aktif, antusias, dan siap membawa semangat !!\r\n\r\n#HIMSIKA2025\r\n#MakeConnection2025\r\n#KabinetNestoria\r\n#NestoriaJourney\r\n#KembangkanPotensiCiptakanPrestasi\r\n#FasilkomUnsika', '1766931290_69513b5a21ada.jpeg', 'Himpunan Mahasiswa Sistem Informasi', '085281632064', 'https://www.instagram.com/edufair.himsika/', 'approved', '2025-12-28 14:14:50'),
(17, 3, 'Soundvision Competition', 'Kompetisi', '2026-01-10', 'Zoom Meeting', '10000', 'https://www.instagram.com/', '‼️[EXTENDED SOUNDVISION COMPETITION REVOIST 5.0]‼️\r\n\r\nSaatnya tunjukkan bakat dan kreativitasmu terbaikmu lewat dentingan alat musik di Band Music Competition atau ciptakan karya visual inspiratif di Video Creative Competition.  🎸✨\r\n\r\n📅 Timeline:\r\n- Pendaftaran: 7–28 Oktober 2025\r\n- Pengumpulan Karya: 7-31 Oktober 2025\r\n- Seleksi Karya: 20-3 November 2025\r\n- Pengumuman Pemenang: 4 November 2025\r\n\r\n📞 Contact Person:\r\nNataya – 0895373647840\r\nRama – 088224007084\r\n\r\nInfo lebih lanjut dan pendaftaran:\r\n👉 https://linktr.ee/PerlombaanOnline_Revoist5.0\r\n\r\n#REVOIST5.0\r\n#HIMSIKA2025\r\n#KabinetNestoria\r\n#TurnUpTheRevolution', '1766931368_69513ba80aa81.jpeg', 'Himpunan Mahasiswa Sistem Informasi', '085281632064', 'https://www.instagram.com/edufair.himsika/', 'approved', '2025-12-28 14:16:08');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `is_active`, `created_at`) VALUES
(1, 'Apa itu Eventify?', 'Eventify adalah platform untuk menemukan dan mengikuti event kampus dengan mudah. Kamu bisa jelajahi event, lihat detailnya, simpan event favorit, dan daftar langsung melalui link pendaftaran yang disediakan.', 1, '2025-12-28 14:22:08'),
(2, 'Bagaimana cara mendaftarkan event di Eventify?', 'Kamu bisa klik tombol “Daftarkan Event” lalu isi data event seperti judul, deskripsi, tanggal, lokasi, dan poster. Setelah dikirim, event akan masuk status menunggu persetujuan admin. Jika disetujui, event akan tampil di halaman Jelajahi.', 1, '2025-12-28 14:22:30'),
(3, 'Kenapa event saya belum tampil di Jelajahi?', 'Karena setiap event yang diajukan akan melalui proses review oleh admin terlebih dahulu. Jika event kamu masih pending, berarti sedang diperiksa. Jika event ditolak, kamu akan melihat keterangan statusnya di menu Event Saya.', 1, '2025-12-28 14:22:50');

-- --------------------------------------------------------

--
-- Table structure for table `featured_events`
--

CREATE TABLE `featured_events` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `position` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `featured_events`
--

INSERT INTO `featured_events` (`id`, `event_id`, `position`, `created_at`) VALUES
(2, 12, 2, '2025-12-28 14:17:53'),
(3, 16, 3, '2025-12-28 14:18:08'),
(4, 11, 3, '2025-12-31 17:02:24');

-- --------------------------------------------------------

--
-- Table structure for table `popular_events`
--

CREATE TABLE `popular_events` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `position` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `popular_events`
--

INSERT INTO `popular_events` (`id`, `event_id`, `position`, `created_at`) VALUES
(4, 3, 1, '2025-12-31 17:01:41'),
(5, 15, 2, '2025-12-31 17:01:49'),
(6, 9, 3, '2025-12-31 17:02:16');

-- --------------------------------------------------------

--
-- Table structure for table `saved_events`
--

CREATE TABLE `saved_events` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `role`, `message`, `photo`, `is_active`, `created_at`) VALUES
(1, 'Banu Azizi', 'Staff Departemen IT', 'Eventify membantu banget buat mahasiswa yang suka ikut event. Informasi eventnya lengkap, dan proses pencarian event jadi lebih cepat dan praktis.', '1766931569_69513c718a63b.png', 1, '2025-12-28 14:19:29'),
(2, 'Jihan Ayudia', 'CEO PT. Pelita Nusa', 'Saya suka karena tampilan Eventify simpel tapi modern. Admin juga cepat menyetujui event, jadi event yang kami buat bisa langsung dipromosikan dan dijangkau banyak mahasiswa.', '1766931621_69513ca5e0d3b.jpg', 1, '2025-12-28 14:20:21'),
(3, 'Nandito Adi Fauzan', 'GM. Sastra', 'Eventify bikin daftar event jadi lebih terstruktur. Poster event tampil menarik, link pendaftaran jelas, dan saya jadi nggak takut ketinggalan event kampus lagi.', '1766931697_69513cf1e71a6.jpg', 1, '2025-12-28 14:21:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('organizer','admin') NOT NULL DEFAULT 'organizer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `phone`, `password`, `role`, `created_at`) VALUES
(1, 'Admin Eventify', 'admin@eventify.com', '', '$2y$10$ed3JFLOwnen2Ygq.ISd5gua82bZD2tARdYe3Sk.t4Qj20fc90fJUi', 'admin', '2025-12-27 04:32:16'),
(2, 'Nazwa Chandra Febriyanti', 'nazwa@gmail.com', '08123456789', '$2y$10$6pqhX/fK0RwfzwVfXZlS/ehoB8fMud2.Q2hgxL18IMKanJMeF0c8q', 'organizer', '2025-12-27 05:14:52'),
(3, 'Alya Rahma Khamila', 'alya@gmail.com', '08123456789', '$2y$10$UlNPx/YrQxLE3zozR6p/1OHeH0MS5pLhXPxL7DQ0.boUYHP20gZDK', 'organizer', '2025-12-27 05:20:53'),
(5, 'Aziz Wibowo', 'wowo@gmail.com', '08123456789', '$2y$10$5Iq5CO9etryp5U8Yvu28peRHr31aqLsyrLYKDJzx6A6isyxXvRpF2', 'organizer', '2025-12-27 09:55:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organizer_id` (`organizer_id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `featured_events`
--
ALTER TABLE `featured_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `popular_events`
--
ALTER TABLE `popular_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `saved_events`
--
ALTER TABLE `saved_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `featured_events`
--
ALTER TABLE `featured_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `popular_events`
--
ALTER TABLE `popular_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `saved_events`
--
ALTER TABLE `saved_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`organizer_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `saved_events`
--
ALTER TABLE `saved_events`
  ADD CONSTRAINT `saved_events_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `saved_events_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
