-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 12, 2026 at 11:28 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_gaskeun1`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint UNSIGNED NOT NULL,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint UNSIGNED DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2026-08-11 08:38:55', '2026-08-11 08:38:55'),
(2, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2026-08-11 08:47:18', '2026-08-11 08:47:18'),
(3, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 2, '[]', NULL, '2026-08-11 08:47:46', '2026-08-11 08:47:46'),
(4, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 2, '[]', NULL, '2026-08-11 08:57:13', '2026-08-11 08:57:13'),
(5, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 6, '[]', NULL, '2026-08-11 09:09:17', '2026-08-11 09:09:17'),
(6, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 6, '[]', NULL, '2026-08-11 09:09:44', '2026-08-11 09:09:44'),
(7, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2026-08-11 09:47:15', '2026-08-11 09:47:15'),
(8, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2026-08-11 09:47:38', '2026-08-11 09:47:38'),
(9, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 4, '[]', NULL, '2026-08-11 09:51:29', '2026-08-11 09:51:29'),
(10, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 4, '[]', NULL, '2026-08-11 09:51:51', '2026-08-11 09:51:51'),
(11, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2026-08-11 20:38:46', '2026-08-11 20:38:46'),
(12, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2026-08-11 20:40:05', '2026-08-11 20:40:05'),
(13, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 7, '[]', NULL, '2026-08-11 20:40:25', '2026-08-11 20:40:25'),
(14, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 7, '[]', NULL, '2026-08-11 20:45:03', '2026-08-11 20:45:03'),
(15, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 7, '[]', NULL, '2026-08-11 20:45:18', '2026-08-11 20:45:18'),
(16, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 7, '[]', NULL, '2026-08-11 20:46:28', '2026-08-11 20:46:28'),
(17, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2026-08-11 20:46:46', '2026-08-11 20:46:46'),
(18, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2026-08-11 20:47:14', '2026-08-11 20:47:14'),
(19, 'default', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 2, '[]', NULL, '2026-08-11 20:47:25', '2026-08-11 20:47:25'),
(20, 'default', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 2, '[]', NULL, '2026-08-11 20:47:55', '2026-08-11 20:47:55');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `desas`
--

CREATE TABLE `desas` (
  `id` bigint UNSIGNED NOT NULL,
  `kecamatan_id` bigint UNSIGNED NOT NULL,
  `nama_desa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kecamatans`
--

CREATE TABLE `kecamatans` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_kecamatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keluhans`
--

CREATE TABLE `keluhans` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `latitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_bukti` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isi_keluhan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_keluhan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `tindak_lanjut` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `keluhans`
--

INSERT INTO `keluhans` (`id`, `user_id`, `latitude`, `longitude`, `foto_bukti`, `isi_keluhan`, `status_keluhan`, `tindak_lanjut`, `created_at`, `updated_at`) VALUES
(1, 7, '-7.206266800560131', '107.89613416760096', 'keluhan/jCw04ubpEfFRhHDHkQ029d8IUyO9ctrSUMG0SQnx.png', 'ada penimbunan', 'pending', NULL, '2026-08-11 20:46:05', '2026-08-11 20:46:05');

-- --------------------------------------------------------

--
-- Table structure for table `kks`
--

CREATE TABLE `kks` (
  `id` bigint UNSIGNED NOT NULL,
  `desa_id` bigint UNSIGNED NOT NULL,
  `nomor_kk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_lengkap` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `koreksi_pengirimans`
--

CREATE TABLE `koreksi_pengirimans` (
  `id` bigint UNSIGNED NOT NULL,
  `transaksi_pengiriman_id` bigint UNSIGNED NOT NULL,
  `jumlah_seharusnya` int NOT NULL,
  `keterangan_koreksi` text COLLATE utf8mb4_unicode_ci,
  `status_koreksi` enum('Menunggu','Disetujui','Ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_08_05_103151_create_permission_tables', 1),
(5, '2026_08_05_103203_create_activity_log_table', 1),
(6, '2026_08_05_103204_add_event_column_to_activity_log_table', 1),
(7, '2026_08_05_103205_add_batch_uuid_column_to_activity_log_table', 1),
(8, '2026_08_05_104747_create_kecamatans_table', 1),
(9, '2026_08_05_104748_create_desas_table', 1),
(10, '2026_08_05_104748_create_kks_table', 1),
(11, '2026_08_05_104748_create_penduduks_table', 1),
(12, '2026_08_05_104749_create_nelayans_table', 1),
(13, '2026_08_05_104749_create_petanis_table', 1),
(14, '2026_08_05_104750_create_rumah_tangga_sasarans_table', 1),
(15, '2026_08_05_104750_create_umkms_table', 1),
(16, '2026_08_05_105534_create_profil_agens_table', 1),
(17, '2026_08_05_105534_create_stok_pangkalans_table', 1),
(18, '2026_08_05_105535_create_transaksi_pengirimen_table', 1),
(19, '2026_08_05_105536_create_transaksi_penyalurans_table', 1),
(20, '2026_08_05_105537_create_koreksi_pengirimans_table', 1),
(21, '2026_08_06_041726_create_keluhans_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 4),
(5, 'App\\Models\\User', 5),
(6, 'App\\Models\\User', 6),
(7, 'App\\Models\\User', 7);

-- --------------------------------------------------------

--
-- Table structure for table `nelayans`
--

CREATE TABLE `nelayans` (
  `id` bigint UNSIGNED NOT NULL,
  `penduduk_id` bigint UNSIGNED NOT NULL,
  `jenis_kapal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alat_tangkap` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penduduks`
--

CREATE TABLE `penduduks` (
  `id` bigint UNSIGNED NOT NULL,
  `kk_id` bigint UNSIGNED NOT NULL,
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `pekerjaan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `petanis`
--

CREATE TABLE `petanis` (
  `id` bigint UNSIGNED NOT NULL,
  `penduduk_id` bigint UNSIGNED NOT NULL,
  `luas_lahan_m2` int DEFAULT NULL,
  `jenis_komoditas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profil_agens`
--

CREATE TABLE `profil_agens` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama_agen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_registrasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `kontak` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', '2026-08-11 07:47:53', '2026-08-11 07:47:53'),
(2, 'Disperindag', 'web', '2026-08-11 07:47:53', '2026-08-11 07:47:53'),
(3, 'Pangkalan LPG', 'web', '2026-08-11 07:47:54', '2026-08-11 07:47:54'),
(4, 'Agen LPG', 'web', '2026-08-11 07:47:54', '2026-08-11 07:47:54'),
(5, 'Pimpinan Daerah', 'web', '2026-08-11 07:47:54', '2026-08-11 07:47:54'),
(6, 'Hiswana Migas', 'web', '2026-08-11 07:47:55', '2026-08-11 07:47:55'),
(7, 'Publik', 'web', '2026-08-11 07:47:55', '2026-08-11 07:47:55');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rumah_tangga_sasarans`
--

CREATE TABLE `rumah_tangga_sasarans` (
  `id` bigint UNSIGNED NOT NULL,
  `kk_id` bigint UNSIGNED NOT NULL,
  `kriteria_bantuan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_penerima` enum('Layak','Tidak Layak','Menerima') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Layak',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('7F4xoxNjt8QRS5B3OKvwJ6nNzn2DVYFi05L5HlBf', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiekNHN2Z1T3NPNTFaQWRUa1RlSVcwUGpabHN3YjczTVN1dkFIVUU0YiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1786467111),
('CCdBZhUhk1UzSDR5CMVMxobVrKnrGsEUlEM7LPh2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYThmU24wUEFWTWdFRW82c0FqSVU5VjQxTUtUWEZpOTU3a25oSmNKbCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9fQ==', 1786506499);

-- --------------------------------------------------------

--
-- Table structure for table `stok_pangkalans`
--

CREATE TABLE `stok_pangkalans` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `jumlah_tabung` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_pengirimans`
--

CREATE TABLE `transaksi_pengirimans` (
  `id` bigint UNSIGNED NOT NULL,
  `agen_id` bigint UNSIGNED NOT NULL,
  `pangkalan_id` bigint UNSIGNED NOT NULL,
  `jumlah_tabung` int NOT NULL,
  `tanggal_pengiriman` date NOT NULL,
  `foto_bukti` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Menunggu','Diterima','Dikoreksi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_penyalurans`
--

CREATE TABLE `transaksi_penyalurans` (
  `id` bigint UNSIGNED NOT NULL,
  `pangkalan_id` bigint UNSIGNED NOT NULL,
  `kategori_konsumen` enum('Rumah Tangga','UMKM','Nelayan','Petani') COLLATE utf8mb4_unicode_ci NOT NULL,
  `penduduk_id` bigint UNSIGNED NOT NULL,
  `jumlah_tabung` int NOT NULL,
  `tanggal_penyaluran` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `umkms`
--

CREATE TABLE `umkms` (
  `id` bigint UNSIGNED NOT NULL,
  `penduduk_id` bigint UNSIGNED NOT NULL,
  `nama_usaha` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bidang_usaha` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'superadmin', NULL, NULL, '$2y$12$8pdVtqWBHRFwQH.9/5PHAuBkk/wlVmkRA5sj8Mh/EX/XAF2f1/XbK', NULL, '2026-08-11 07:47:53', '2026-08-11 07:47:53'),
(2, 'Disperindag', 'disperindag', NULL, NULL, '$2y$12$XVVkCW5mL51hyvvUePyIR.0rwBnX4RuNlanR9JumPOHseMXqs0.xC', NULL, '2026-08-11 07:47:54', '2026-08-11 07:47:54'),
(3, 'Pangkalan LPG', 'pangkalanlpg', NULL, NULL, '$2y$12$tXTSu2fISEBM0BqqUcTrSOChoKSIo4LF8hSo2FryTKLAiULHyXJiG', NULL, '2026-08-11 07:47:54', '2026-08-11 07:47:54'),
(4, 'Agen LPG', 'agenlpg', NULL, NULL, '$2y$12$MGixEbLtxqpLtsn1Jn9v6uGfcY5FZ03LoEvl7w.SRSbkXfJ.iraaa', NULL, '2026-08-11 07:47:54', '2026-08-11 07:47:54'),
(5, 'Pimpinan Daerah', 'pimpinandaerah', NULL, NULL, '$2y$12$pYEDjCfBljBgsOhWPf.lnef5ThWLaEWNYs63d2Ov8I691VKtg3Zfq', NULL, '2026-08-11 07:47:55', '2026-08-11 07:47:55'),
(6, 'Hiswana Migas', 'hiswanamigas', NULL, NULL, '$2y$12$Ak8Z.o.H4xJidjR/ClDs/.k1LyDk2FH6bDUd7G8H7deQynjU0J8um', NULL, '2026-08-11 07:47:55', '2026-08-11 07:47:55'),
(7, 'Publik', 'publik', NULL, NULL, '$2y$12$i42RNHo.MOYG7KTAnGuxPOgw5NdqMG4ETXAPAAyRBQVslhvm4M3Xu', NULL, '2026-08-11 07:47:55', '2026-08-11 07:47:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject` (`subject_type`,`subject_id`),
  ADD KEY `causer` (`causer_type`,`causer_id`),
  ADD KEY `activity_log_log_name_index` (`log_name`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `desas`
--
ALTER TABLE `desas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `desas_kecamatan_id_foreign` (`kecamatan_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kecamatans`
--
ALTER TABLE `kecamatans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keluhans`
--
ALTER TABLE `keluhans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `keluhans_user_id_foreign` (`user_id`);

--
-- Indexes for table `kks`
--
ALTER TABLE `kks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kks_nomor_kk_unique` (`nomor_kk`),
  ADD KEY `kks_desa_id_foreign` (`desa_id`);

--
-- Indexes for table `koreksi_pengirimans`
--
ALTER TABLE `koreksi_pengirimans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `koreksi_pengirimans_transaksi_pengiriman_id_foreign` (`transaksi_pengiriman_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `nelayans`
--
ALTER TABLE `nelayans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nelayans_penduduk_id_foreign` (`penduduk_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `penduduks`
--
ALTER TABLE `penduduks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `penduduks_nik_unique` (`nik`),
  ADD KEY `penduduks_kk_id_foreign` (`kk_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `petanis`
--
ALTER TABLE `petanis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `petanis_penduduk_id_foreign` (`penduduk_id`);

--
-- Indexes for table `profil_agens`
--
ALTER TABLE `profil_agens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profil_agens_user_id_foreign` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `rumah_tangga_sasarans`
--
ALTER TABLE `rumah_tangga_sasarans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rumah_tangga_sasarans_kk_id_foreign` (`kk_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stok_pangkalans`
--
ALTER TABLE `stok_pangkalans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stok_pangkalans_user_id_foreign` (`user_id`);

--
-- Indexes for table `transaksi_pengirimans`
--
ALTER TABLE `transaksi_pengirimans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_pengirimans_agen_id_foreign` (`agen_id`),
  ADD KEY `transaksi_pengirimans_pangkalan_id_foreign` (`pangkalan_id`);

--
-- Indexes for table `transaksi_penyalurans`
--
ALTER TABLE `transaksi_penyalurans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_penyalurans_pangkalan_id_foreign` (`pangkalan_id`),
  ADD KEY `transaksi_penyalurans_penduduk_id_foreign` (`penduduk_id`);

--
-- Indexes for table `umkms`
--
ALTER TABLE `umkms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `umkms_penduduk_id_foreign` (`penduduk_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `desas`
--
ALTER TABLE `desas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kecamatans`
--
ALTER TABLE `kecamatans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keluhans`
--
ALTER TABLE `keluhans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kks`
--
ALTER TABLE `kks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `koreksi_pengirimans`
--
ALTER TABLE `koreksi_pengirimans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `nelayans`
--
ALTER TABLE `nelayans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penduduks`
--
ALTER TABLE `penduduks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `petanis`
--
ALTER TABLE `petanis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profil_agens`
--
ALTER TABLE `profil_agens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `rumah_tangga_sasarans`
--
ALTER TABLE `rumah_tangga_sasarans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stok_pangkalans`
--
ALTER TABLE `stok_pangkalans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi_pengirimans`
--
ALTER TABLE `transaksi_pengirimans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi_penyalurans`
--
ALTER TABLE `transaksi_penyalurans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `umkms`
--
ALTER TABLE `umkms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `desas`
--
ALTER TABLE `desas`
  ADD CONSTRAINT `desas_kecamatan_id_foreign` FOREIGN KEY (`kecamatan_id`) REFERENCES `kecamatans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `keluhans`
--
ALTER TABLE `keluhans`
  ADD CONSTRAINT `keluhans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `kks`
--
ALTER TABLE `kks`
  ADD CONSTRAINT `kks_desa_id_foreign` FOREIGN KEY (`desa_id`) REFERENCES `desas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `koreksi_pengirimans`
--
ALTER TABLE `koreksi_pengirimans`
  ADD CONSTRAINT `koreksi_pengirimans_transaksi_pengiriman_id_foreign` FOREIGN KEY (`transaksi_pengiriman_id`) REFERENCES `transaksi_pengirimans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nelayans`
--
ALTER TABLE `nelayans`
  ADD CONSTRAINT `nelayans_penduduk_id_foreign` FOREIGN KEY (`penduduk_id`) REFERENCES `penduduks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penduduks`
--
ALTER TABLE `penduduks`
  ADD CONSTRAINT `penduduks_kk_id_foreign` FOREIGN KEY (`kk_id`) REFERENCES `kks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `petanis`
--
ALTER TABLE `petanis`
  ADD CONSTRAINT `petanis_penduduk_id_foreign` FOREIGN KEY (`penduduk_id`) REFERENCES `penduduks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `profil_agens`
--
ALTER TABLE `profil_agens`
  ADD CONSTRAINT `profil_agens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rumah_tangga_sasarans`
--
ALTER TABLE `rumah_tangga_sasarans`
  ADD CONSTRAINT `rumah_tangga_sasarans_kk_id_foreign` FOREIGN KEY (`kk_id`) REFERENCES `kks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stok_pangkalans`
--
ALTER TABLE `stok_pangkalans`
  ADD CONSTRAINT `stok_pangkalans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaksi_pengirimans`
--
ALTER TABLE `transaksi_pengirimans`
  ADD CONSTRAINT `transaksi_pengirimans_agen_id_foreign` FOREIGN KEY (`agen_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_pengirimans_pangkalan_id_foreign` FOREIGN KEY (`pangkalan_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaksi_penyalurans`
--
ALTER TABLE `transaksi_penyalurans`
  ADD CONSTRAINT `transaksi_penyalurans_pangkalan_id_foreign` FOREIGN KEY (`pangkalan_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_penyalurans_penduduk_id_foreign` FOREIGN KEY (`penduduk_id`) REFERENCES `penduduks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `umkms`
--
ALTER TABLE `umkms`
  ADD CONSTRAINT `umkms_penduduk_id_foreign` FOREIGN KEY (`penduduk_id`) REFERENCES `penduduks` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
