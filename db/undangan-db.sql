-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 26 Agu 2025 pada 13.38
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `undangan-db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `groom_name` varchar(255) DEFAULT NULL,
  `groom_photo` varchar(255) DEFAULT NULL,
  `groom_parents` varchar(255) DEFAULT NULL,
  `groom_instagram` varchar(255) DEFAULT NULL,
  `bride_name` varchar(255) DEFAULT NULL,
  `bride_photo` varchar(255) DEFAULT NULL,
  `bride_parents` varchar(255) DEFAULT NULL,
  `bride_instagram` varchar(255) DEFAULT NULL,
  `quran_verse` text DEFAULT NULL,
  `love_story` text DEFAULT NULL,
  `date` date NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `akad_location` varchar(255) DEFAULT NULL,
  `akad_time` varchar(255) DEFAULT NULL,
  `akad_maps_url` varchar(255) DEFAULT NULL,
  `resepsi_location` varchar(255) DEFAULT NULL,
  `resepsi_time` varchar(255) DEFAULT NULL,
  `resepsi_maps_url` varchar(255) DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `events`
--

INSERT INTO `events` (`id`, `uuid`, `user_id`, `name`, `slug`, `groom_name`, `groom_photo`, `groom_parents`, `groom_instagram`, `bride_name`, `bride_photo`, `bride_parents`, `bride_instagram`, `quran_verse`, `love_story`, `date`, `description`, `location`, `akad_location`, `akad_time`, `akad_maps_url`, `resepsi_location`, `resepsi_time`, `resepsi_maps_url`, `photo_url`, `created_at`, `updated_at`) VALUES
(2, '07dd7f07-9195-4997-926c-be75a83f9939', 2, 'HAMID & ABDUL', '', 'HAMIDUN', 'groom_photos/YWJvtNHO9cFvqWF6yhtt6ZQHr7L8b6aC3GYpTohU.jpg', 'asep', '@hamidun', 'HIDAYAT', 'bride_photos/a90KXKUHB9rM41X7ndNN1WbbTIgACwW2NlvRrVpX.jpg', 'udin', '@hidayat', NULL, NULL, '2027-01-01', NULL, NULL, 'pameuntasan', '09:00', 'https://maps.app.goo.gl/mhHUNUZfoJppiDdL7', 'bandung', '11:00-12:00', 'https://maps.app.goo.gl/EjcSS4ureEbg28iU7', 'event_photos/I3nhxM5H4KaD0DhTO8iEH8CKSu6BKtusikQKU3Cw.jpg', '2025-08-25 17:38:00', '2025-08-26 04:32:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `event_gifts`
--

CREATE TABLE `event_gifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `sender_name` varchar(255) NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `event_photos`
--

CREATE TABLE `event_photos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `event_photos`
--

INSERT INTO `event_photos` (`id`, `user_id`, `event_id`, `path`, `created_at`, `updated_at`) VALUES
(14, 2, 2, 'gallery_photos/y8Nz4qMMKgYa74AiwEH7BtAiW97OF9pTrIKX9fU4.jpg', '2025-08-26 00:59:03', '2025-08-26 00:59:03'),
(15, 2, 2, 'gallery_photos/97ijh5yPw3rydJ901AIjpjhRflPOCMrfCWcsGUpP.jpg', '2025-08-26 04:34:00', '2025-08-26 04:34:00'),
(16, 2, 2, 'gallery_photos/3DbZKEjV04ib6j6Hd5Ni00ZuGLUsVZY1SUjFxZoI.jpg', '2025-08-26 04:36:15', '2025-08-26 04:36:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `guests`
--

CREATE TABLE `guests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unique_code` varchar(255) DEFAULT NULL,
  `uuid` char(36) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `affiliation` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `check_in_time` timestamp NULL DEFAULT NULL,
  `number_of_guests` int(11) NOT NULL DEFAULT 1,
  `souvenir_taken_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Belum Hadir',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `guests`
--

INSERT INTO `guests` (`id`, `unique_code`, `uuid`, `user_id`, `event_id`, `name`, `affiliation`, `address`, `phone_number`, `check_in_time`, `number_of_guests`, `souvenir_taken_at`, `status`, `created_at`, `updated_at`) VALUES
(24, NULL, '5318c581-8e1a-4717-a7e1-298034774253', 2, 2, 'HAMID ABDUL AZIZ', 'teman', NULL, NULL, '2025-08-25 19:29:12', 2, NULL, 'Belum Hadir', '2025-08-25 19:01:09', '2025-08-25 19:29:12'),
(25, NULL, '556a2ce5-3dc6-4358-9b21-edeaa9445ea3', 2, 2, 'HIDAYAT', 'kades', NULL, NULL, '2025-08-25 19:35:14', 2, NULL, 'Belum Hadir', '2025-08-25 19:34:55', '2025-08-25 19:35:14'),
(26, NULL, 'd8346e82-a204-45ef-92d2-d626146ff928', 2, 2, 'Samsudin & Asep', 'bupati', NULL, NULL, '2025-08-25 19:36:00', 3, NULL, 'Belum Hadir', '2025-08-25 19:36:00', '2025-08-25 19:36:00'),
(27, NULL, '91ffc3f6-db13-4649-8442-1df51ba380a8', 2, 2, 'Admin', '-', NULL, NULL, '2025-08-25 19:53:57', 1, NULL, 'Belum Hadir', '2025-08-25 19:53:57', '2025-08-25 19:53:57'),
(28, NULL, 'cb00c9cf-f4be-4505-817d-dc703d38522b', 2, 2, 'budi & asep', '-', NULL, NULL, '2025-08-25 19:54:04', 1, NULL, 'Belum Hadir', '2025-08-25 19:54:04', '2025-08-25 19:54:04'),
(29, NULL, 'df00d755-d5a9-4fa1-93ea-8a18281cc4d1', 2, 2, 'Samsudin & Asep', '-', NULL, NULL, '2025-08-25 19:59:43', 1, NULL, 'Belum Hadir', '2025-08-25 19:59:43', '2025-08-25 19:59:43'),
(30, NULL, 'fd74fdcc-6663-45a1-8168-a2fcd2b6d120', 2, 2, 'asep', '-', NULL, NULL, '2025-08-25 20:07:34', 1, NULL, 'Belum Hadir', '2025-08-25 20:07:34', '2025-08-25 20:07:34'),
(31, NULL, '26d81f6c-7392-4077-b315-7bc03b288e71', 2, 2, 'ade', '', NULL, NULL, '2025-08-25 20:11:30', 1, NULL, 'Belum Hadir', '2025-08-25 20:11:30', '2025-08-25 20:11:30'),
(32, NULL, '25894437-4e9e-4030-890b-0a142a6a2e2b', 2, 2, 'HIDAYAT SPDI MPD', 'bupati', NULL, NULL, '2025-08-25 20:12:39', 3, NULL, 'Belum Hadir', '2025-08-25 20:12:31', '2025-08-25 20:12:39'),
(33, NULL, '0a492f92-e410-4610-a438-962c4712411b', 2, 2, 'MAS MATLA\'UL ANWAR PAMEUNTASAN', 'bupati', NULL, NULL, NULL, 1, NULL, 'Belum Hadir', '2025-08-25 20:13:13', '2025-08-25 20:13:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_08_20_125919_add_photo_to_users_table', 1),
(5, '2025_08_20_132136_create_events_table', 1),
(6, '2025_08_20_133341_add_email_to_users_table', 1),
(7, '2025_08_21_075629_create_guests_table', 1),
(8, '2025_08_23_072719_add_check_in_time_to_guests_table', 2),
(9, '2025_08_23_074238_create_rsvps_table', 3),
(10, '2025_08_23_074718_create_event_photos_table', 4),
(11, '2025_08_23_080108_add_uuid_to_events_table', 5),
(12, '2025_08_23_095326_add_souvenir_taken_at_to_guests_table', 6),
(13, '2025_08_23_100753_add_uuid_to_guests_table', 7),
(14, '2025_08_23_101549_create_event_gifts_table', 8),
(15, '2025_08_23_103746_add_couple_details_to_events_table', 9),
(16, '2025_08_23_170333_add_number_of_guests_to_guests_table', 9),
(17, '2025_08_23_171340_add_unique_code_to_guests_table', 10),
(18, '2025_08_24_153056_add_akad_resepsi_details_to_events_table', 11),
(19, '2025_08_24_154903_add_guest_id_to_rsvps_table', 12),
(20, '2025_08_26_063913_add_slug_to_events_table', 13);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rsvps`
--

CREATE TABLE `rsvps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `status` enum('hadir','tidak_hadir','ragu') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `guest_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Q2lsXDasmTK5W7E06Ub5ClWESCMwGJ31fKzJtlIe', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMzdCbnFKSXlOaVlzUWhxd0x6RzQxTkNTSnhYeFBvMGJOWWlWaGRjcSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zb3V2ZW5pciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1756208218);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `photo`) VALUES
(2, 'Admin', 'admin@example', NULL, '$2y$12$infj3kyshFN.R/svAWTrFu3XubRSNrxdM8y3MbpW6HdSw/LsSSAYq', NULL, '2025-08-25 17:31:23', '2025-08-25 17:39:38', 'poto-profile/user-2.jpg');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `events_slug_unique` (`slug`),
  ADD UNIQUE KEY `events_uuid_unique` (`uuid`),
  ADD KEY `events_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `event_gifts`
--
ALTER TABLE `event_gifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_gifts_user_id_foreign` (`user_id`),
  ADD KEY `event_gifts_event_id_foreign` (`event_id`);

--
-- Indeks untuk tabel `event_photos`
--
ALTER TABLE `event_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_photos_user_id_foreign` (`user_id`),
  ADD KEY `event_photos_event_id_foreign` (`event_id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `guests_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `guests_unique_code_unique` (`unique_code`),
  ADD KEY `guests_user_id_foreign` (`user_id`),
  ADD KEY `guests_event_id_foreign` (`event_id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `rsvps`
--
ALTER TABLE `rsvps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rsvps_user_id_foreign` (`user_id`),
  ADD KEY `rsvps_event_id_foreign` (`event_id`),
  ADD KEY `rsvps_guest_id_foreign` (`guest_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_name_unique` (`name`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `event_gifts`
--
ALTER TABLE `event_gifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `event_photos`
--
ALTER TABLE `event_photos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `guests`
--
ALTER TABLE `guests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `rsvps`
--
ALTER TABLE `rsvps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `event_gifts`
--
ALTER TABLE `event_gifts`
  ADD CONSTRAINT `event_gifts_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_gifts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `event_photos`
--
ALTER TABLE `event_photos`
  ADD CONSTRAINT `event_photos_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_photos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `guests`
--
ALTER TABLE `guests`
  ADD CONSTRAINT `guests_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `guests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rsvps`
--
ALTER TABLE `rsvps`
  ADD CONSTRAINT `rsvps_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rsvps_guest_id_foreign` FOREIGN KEY (`guest_id`) REFERENCES `guests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rsvps_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
