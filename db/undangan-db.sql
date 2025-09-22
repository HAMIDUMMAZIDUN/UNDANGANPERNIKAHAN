-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Sep 2025 pada 10.54
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
-- Database: `undangan_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activities`
--

CREATE TABLE `activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `activities`
--

INSERT INTO `activities` (`id`, `user_id`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'telah login ke sistem.', '2025-09-04 08:33:14', '2025-09-04 08:33:14'),
(2, 2, 'telah login ke sistem.', '2025-09-04 08:33:38', '2025-09-04 08:33:38'),
(3, 1, 'telah login ke sistem.', '2025-09-06 00:23:55', '2025-09-06 00:23:55'),
(5, 1, 'telah login ke sistem.', '2025-09-06 02:21:54', '2025-09-06 02:21:54'),
(8, 1, 'telah login ke sistem.', '2025-09-06 02:23:50', '2025-09-06 02:23:50'),
(14, 1, 'telah login ke sistem.', '2025-09-06 06:22:40', '2025-09-06 06:22:40'),
(17, 1, 'telah login ke sistem.', '2025-09-06 06:45:29', '2025-09-06 06:45:29'),
(22, 1, 'telah login ke sistem.', '2025-09-06 07:00:53', '2025-09-06 07:00:53'),
(26, 2, 'telah login ke sistem.', '2025-09-06 07:15:10', '2025-09-06 07:15:10'),
(28, 1, 'telah login ke sistem.', '2025-09-06 07:30:38', '2025-09-06 07:30:38'),
(30, 1, 'telah login ke sistem.', '2025-09-06 07:35:50', '2025-09-06 07:35:50'),
(32, 1, 'telah login ke sistem.', '2025-09-06 07:43:06', '2025-09-06 07:43:06'),
(34, 1, 'telah login ke sistem.', '2025-09-06 09:05:57', '2025-09-06 09:05:57'),
(36, 1, 'telah login ke sistem.', '2025-09-06 09:07:35', '2025-09-06 09:07:35'),
(38, 1, 'telah login ke sistem.', '2025-09-06 23:02:19', '2025-09-06 23:02:19'),
(39, 1, 'telah login ke sistem.', '2025-09-06 23:42:58', '2025-09-06 23:42:58'),
(41, 1, 'telah login ke sistem.', '2025-09-06 23:44:22', '2025-09-06 23:44:22'),
(43, 1, 'telah login ke sistem.', '2025-09-07 01:08:01', '2025-09-07 01:08:01'),
(45, 1, 'telah login ke sistem.', '2025-09-08 00:42:01', '2025-09-08 00:42:01'),
(46, 1, 'telah login ke sistem.', '2025-09-08 01:14:36', '2025-09-08 01:14:36'),
(47, 23, 'telah login ke sistem.', '2025-09-08 01:21:41', '2025-09-08 01:21:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-open_for_order', 'b:1;', 2072605649);

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
-- Struktur dari tabel `client_requests`
--

CREATE TABLE `client_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `template_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `price` decimal(15,2) DEFAULT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'unpaid',
  `order_id` varchar(255) DEFAULT NULL,
  `qris_url` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `client_requests`
--

INSERT INTO `client_requests` (`id`, `user_id`, `title`, `template_id`, `status`, `price`, `payment_status`, `order_id`, `qris_url`, `created_at`, `updated_at`) VALUES
(19, 23, 'Order: Classic Gold', 1, 'approve', 1000.00, 'unpaid', NULL, NULL, '2025-09-08 01:12:23', '2025-09-08 01:21:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`content`)),
  `location` varchar(255) DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `packet` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `groom_name` varchar(255) DEFAULT NULL,
  `groom_photo` varchar(255) DEFAULT NULL,
  `groom_parents` varchar(255) DEFAULT NULL,
  `groom_instagram` varchar(255) DEFAULT NULL,
  `bride_name` varchar(255) DEFAULT NULL,
  `bride_photo` varchar(255) DEFAULT NULL,
  `bride_parents` varchar(255) DEFAULT NULL,
  `bride_instagram` varchar(255) DEFAULT NULL,
  `love_story` text DEFAULT NULL,
  `location_url` varchar(255) DEFAULT NULL,
  `akad_location` varchar(255) DEFAULT NULL,
  `akad_time` varchar(255) DEFAULT NULL,
  `akad_maps_url` varchar(255) DEFAULT NULL,
  `resepsi_location` varchar(255) DEFAULT NULL,
  `resepsi_time` varchar(255) DEFAULT NULL,
  `resepsi_maps_url` varchar(255) DEFAULT NULL,
  `template_name` varchar(255) DEFAULT 'classic-gold',
  `gift_bank_1_name` varchar(255) DEFAULT NULL,
  `gift_bank_1_account` varchar(255) DEFAULT NULL,
  `gift_bank_1_holder` varchar(255) DEFAULT NULL,
  `gift_bank_2_name` varchar(255) DEFAULT NULL,
  `gift_bank_2_account` varchar(255) DEFAULT NULL,
  `gift_bank_2_holder` varchar(255) DEFAULT NULL,
  `gift_address` text DEFAULT NULL,
  `story_1_date` date DEFAULT NULL,
  `story_1_title` varchar(255) DEFAULT NULL,
  `story_1_description` text DEFAULT NULL,
  `story_1_image` varchar(255) DEFAULT NULL,
  `story_2_date` date DEFAULT NULL,
  `story_2_title` varchar(255) DEFAULT NULL,
  `story_2_description` text DEFAULT NULL,
  `story_2_image` varchar(255) DEFAULT NULL,
  `rekening_bank` varchar(255) DEFAULT NULL,
  `rekening_atas_nama` varchar(255) DEFAULT NULL,
  `rekening_nomor` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `events`
--

INSERT INTO `events` (`id`, `user_id`, `uuid`, `name`, `slug`, `date`, `content`, `location`, `photo_url`, `phone_number`, `packet`, `description`, `groom_name`, `groom_photo`, `groom_parents`, `groom_instagram`, `bride_name`, `bride_photo`, `bride_parents`, `bride_instagram`, `love_story`, `location_url`, `akad_location`, `akad_time`, `akad_maps_url`, `resepsi_location`, `resepsi_time`, `resepsi_maps_url`, `template_name`, `gift_bank_1_name`, `gift_bank_1_account`, `gift_bank_1_holder`, `gift_bank_2_name`, `gift_bank_2_account`, `gift_bank_2_holder`, `gift_address`, `story_1_date`, `story_1_title`, `story_1_description`, `story_1_image`, `story_2_date`, `story_2_title`, `story_2_description`, `story_2_image`, `rekening_bank`, `rekening_atas_nama`, `rekening_nomor`, `created_at`, `updated_at`) VALUES
(6, 23, 'a4696c70-038a-4944-ab3f-ab9622dbecbc', 'Pernikahan Hamid & Pasangan', 'pernikahan-hamid-pasangan', '2027-01-01', NULL, 'asia afrika', 'event_photos/It6Jk7K3w0HCTZAaTU9zriMxfzKIy03IORTJqxP3.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'classic-gold', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 01:24:07', '2025-09-08 01:34:07');

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
(6, 23, 6, 'gallery_photos/eT2ay9YY1FuMOnrIhrnjWC89O0K8dIUx1tUHDNWI.jpg', '2025-09-08 01:43:21', '2025-09-08 01:43:21'),
(7, 23, 6, 'gallery_photos/oI6EbhsbxVHQLoaLIAqIKjuwwsuczEL6aaDdOoxk.jpg', '2025-09-08 01:44:54', '2025-09-08 01:44:54');

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
(4, NULL, 'c2755021-9fa8-4f1e-a9b2-4e287564db92', 23, 6, 'ASEP', 'TEMAN', 'BANDUNG', '8123456789', NULL, 1, NULL, 'Belum Hadir', '2025-09-08 01:36:53', '2025-09-08 01:36:53'),
(5, NULL, '8e9dfec0-db22-46d1-82c1-7b0f92bb0129', 23, 6, 'PANDI', 'SAHABAT', 'DAGO', '8123456790', NULL, 1, NULL, 'Belum Hadir', '2025-09-08 01:36:53', '2025-09-08 01:36:53'),
(6, NULL, 'a0d0ea58-9dc0-4e69-b5f6-e095a7949f8b', 23, 6, 'SUNARYA', 'VIP', 'CICAHEUM', '8123456791', NULL, 1, NULL, 'Belum Hadir', '2025-09-08 01:36:53', '2025-09-08 01:36:53'),
(7, NULL, '7f27c21e-da40-4bd0-a1db-e473e57808f3', 23, 6, 'INDAH', 'KADES', 'CILEUNYI', '8123456792', NULL, 1, NULL, 'Belum Hadir', '2025-09-08 01:36:53', '2025-09-08 01:36:53');

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
(8, '2025_08_23_072719_add_check_in_time_to_guests_table', 1),
(9, '2025_08_23_074238_create_rsvps_table', 1),
(10, '2025_08_23_074718_create_event_photos_table', 1),
(11, '2025_08_23_095326_add_souvenir_taken_at_to_guests_table', 1),
(12, '2025_08_23_100753_add_uuid_to_guests_table', 1),
(13, '2025_08_23_101549_create_event_gifts_table', 1),
(14, '2025_08_23_170333_add_number_of_guests_to_guests_table', 1),
(15, '2025_08_23_171340_add_unique_code_to_guests_table', 1),
(16, '2025_08_24_154903_add_guest_id_to_rsvps_table', 1),
(17, '2025_08_28_160549_create_client_requests_table', 1),
(18, '2025_09_03_012551_add_packet_to_events_table', 1),
(19, '2025_09_03_053219_create_activities_table', 1),
(20, '2025_09_06_070058_add_payment_columns_to_client_requests_table', 2),
(21, '2025_09_06_070637_create_client_requests_table', 3),
(22, '2025_09_06_072805_add_phone_to_users_table', 4),
(23, '2025_09_06_135728_add_status_to_users_table', 5),
(24, '2025_09_06_155514_add_customization_fields_to_events_table', 6);

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
('8bu3QIHBsz70baui8eZzDG7yDqP6H8db5uKK758j', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiRHUzd05QbVpBV1FWMVNJSTVaOUxUbHptaDRSNk1uTDgxNWcyRnoxMiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1757224063),
('FZOLRYIUa5WTFghTs9JJXkjueNSsHdcoBKdywVW3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNzdGTDJTZEpoSFJJckZXQUVodlY4a0h1cTg1dHQ5bWhOY01NS0dYaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTI2OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvcGF5bWVudC8xNz9leHBpcmVzPTE3NTcyNjExODEmc2lnbmF0dXJlPTc3ZGM3ZjllY2U5NTAwMzQ5MTZhM2E4YmM1YzBkMmI3YmZhNDM1ZDA2NGU2NjM0ODg4ZWUyMjQ0ZmM4MDgwODciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1757174781),
('OHLWAcKOt93zsnJMj8fSiuRCFbMOpjlLKtUtTkv1', 23, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVVhwbVRCaTNxY052SUtDRk9DTXJHQmx3YldOcFRFd3FZUUVSR0ZaOCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYW51YWwiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyMzt9', 1757321397);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `status`, `role`, `remember_token`, `created_at`, `updated_at`, `photo`) VALUES
(1, 'Admin', 'admin@example.com', NULL, NULL, '$2y$12$ctnoZNhwCgGv2xD.wqX6gugwHYMxfzUtYkVtQdaAkDDyn4RpYX1FS', 'pending', 'admin', NULL, '2025-09-04 08:31:03', '2025-09-04 08:31:03', NULL),
(2, 'User', 'user@example.com', NULL, NULL, '$2y$12$MiskAoD/mNXyywxZe/NmaOv6UFStrM2mLFbAfkxEclEx2rO/Bz7NW', 'approve', 'user', NULL, '2025-09-04 08:31:03', '2025-09-06 08:57:00', NULL),
(23, 'HAMID ABDUL AZIZ', 'hamidabdulaziz36@gmail.com', '081214019947', NULL, '$2y$12$AqFrqCYN6.JKjLCwz3d9oevWUh20e/v5tZ18x3Awnkms9/6qQKGv6', 'approve', 'user', NULL, '2025-09-08 01:12:23', '2025-09-08 01:21:21', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activities_user_id_foreign` (`user_id`);

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
-- Indeks untuk tabel `client_requests`
--
ALTER TABLE `client_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `client_requests_order_id_unique` (`order_id`),
  ADD KEY `client_requests_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `events_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `events_slug_unique` (`slug`),
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
-- AUTO_INCREMENT untuk tabel `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT untuk tabel `client_requests`
--
ALTER TABLE `client_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `event_gifts`
--
ALTER TABLE `event_gifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `event_photos`
--
ALTER TABLE `event_photos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `guests`
--
ALTER TABLE `guests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `rsvps`
--
ALTER TABLE `rsvps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `client_requests`
--
ALTER TABLE `client_requests`
  ADD CONSTRAINT `client_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
