-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 19, 2026 at 07:27 AM
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
-- Database: `si_tabdep`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint UNSIGNED NOT NULL,
  `log_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `causer_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint UNSIGNED DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1, 'periode', 'Periode Periode 8 Mei 2026 berhasil di-generate (3 cabang)', 'App\\Models\\PeriodeLaporan', NULL, 1, 'App\\Models\\User', 1, '[]', NULL, '2026-05-17 23:39:09', '2026-05-17 23:39:09'),
(2, 'laporan', 'Draft Tabungan cabang 101 disimpan', 'App\\Models\\Laporan', NULL, 1, 'App\\Models\\User', 2, '{\"jenis\": \"Tabungan\", \"saldo_akhir\": 7}', NULL, '2026-05-18 00:00:11', '2026-05-18 00:00:11'),
(3, 'laporan', 'Draft Tabungan cabang 101 disimpan', 'App\\Models\\Laporan', NULL, 1, 'App\\Models\\User', 2, '{\"jenis\": \"Tabungan\", \"saldo_akhir\": 7}', NULL, '2026-05-18 00:00:15', '2026-05-18 00:00:15'),
(4, 'laporan', 'Draft Deposito cabang 101 disimpan', 'App\\Models\\Laporan', NULL, 2, 'App\\Models\\User', 2, '{\"jenis\": \"Deposito\", \"saldo_akhir\": 3}', NULL, '2026-05-18 00:00:33', '2026-05-18 00:00:33'),
(5, 'laporan', 'Draft Tabungan cabang 101 disimpan', 'App\\Models\\Laporan', NULL, 1, 'App\\Models\\User', 2, '{\"jenis\": \"Tabungan\", \"saldo_akhir\": 7}', NULL, '2026-05-18 00:00:42', '2026-05-18 00:00:42'),
(6, 'laporan', 'Laporan Tabungan cabang 101 disubmit', 'App\\Models\\Laporan', NULL, 1, 'App\\Models\\User', 2, '{\"jenis\": \"Tabungan\", \"saldo_akhir\": 7}', NULL, '2026-05-18 00:01:06', '2026-05-18 00:01:06'),
(7, 'laporan', 'Laporan Deposito cabang 101 disubmit', 'App\\Models\\Laporan', NULL, 2, 'App\\Models\\User', 2, '{\"jenis\": \"Deposito\", \"saldo_akhir\": 3}', NULL, '2026-05-18 00:01:21', '2026-05-18 00:01:21'),
(8, 'laporan', 'Laporan Tabungan cabang 102 disubmit', 'App\\Models\\Laporan', NULL, 3, 'App\\Models\\User', 3, '{\"jenis\": \"Tabungan\", \"saldo_akhir\": 14}', NULL, '2026-05-18 00:05:14', '2026-05-18 00:05:14'),
(9, 'laporan', 'Laporan Deposito cabang 102 disubmit', 'App\\Models\\Laporan', NULL, 4, 'App\\Models\\User', 3, '{\"jenis\": \"Deposito\", \"saldo_akhir\": 4}', NULL, '2026-05-18 00:05:24', '2026-05-18 00:05:24'),
(10, 'laporan', 'Laporan Tabungan cabang 101 diverifikasi akunting', 'App\\Models\\Laporan', NULL, 1, 'App\\Models\\User', 4, '{\"jenis\": \"Tabungan\", \"cabang\": \"101\", \"periode\": \"Periode 8 Mei 2026\"}', NULL, '2026-05-18 00:48:21', '2026-05-18 00:48:21'),
(11, 'laporan', 'Laporan Deposito cabang 101 diverifikasi akunting', 'App\\Models\\Laporan', NULL, 2, 'App\\Models\\User', 4, '{\"jenis\": \"Deposito\", \"cabang\": \"101\", \"periode\": \"Periode 8 Mei 2026\"}', NULL, '2026-05-18 00:48:49', '2026-05-18 00:48:49'),
(12, 'laporan', 'Revisi diminta untuk laporan Tabungan cabang 102', 'App\\Models\\Laporan', NULL, 3, 'App\\Models\\User', 4, '{\"jenis\": \"Tabungan\", \"cabang\": \"102\", \"catatan\": \"tambahan tidak sesuai dengan pengadaan\"}', NULL, '2026-05-18 00:50:26', '2026-05-18 00:50:26'),
(13, 'laporan', 'Laporan Deposito cabang 102 diverifikasi akunting', 'App\\Models\\Laporan', NULL, 4, 'App\\Models\\User', 4, '{\"jenis\": \"Deposito\", \"cabang\": \"102\", \"periode\": \"Periode 8 Mei 2026\"}', NULL, '2026-05-18 00:50:36', '2026-05-18 00:50:36'),
(14, 'laporan', 'Laporan Tabungan cabang 102 disubmit', 'App\\Models\\Laporan', NULL, 3, 'App\\Models\\User', 3, '{\"jenis\": \"Tabungan\", \"saldo_akhir\": 13}', NULL, '2026-05-18 00:52:05', '2026-05-18 00:52:05'),
(15, 'laporan', 'Laporan Tabungan cabang 102 diverifikasi akunting', 'App\\Models\\Laporan', NULL, 3, 'App\\Models\\User', 4, '{\"jenis\": \"Tabungan\", \"cabang\": \"102\", \"periode\": \"Periode 8 Mei 2026\"}', NULL, '2026-05-18 00:52:57', '2026-05-18 00:52:57'),
(16, 'laporan', 'Laporan Tabungan cabang 103 disubmit', 'App\\Models\\Laporan', NULL, 5, 'App\\Models\\User', 5, '{\"jenis\": \"Tabungan\", \"saldo_akhir\": 7}', NULL, '2026-05-18 01:03:29', '2026-05-18 01:03:29'),
(17, 'laporan', 'Laporan Deposito cabang 103 disubmit', 'App\\Models\\Laporan', NULL, 6, 'App\\Models\\User', 5, '{\"jenis\": \"Deposito\", \"saldo_akhir\": 6}', NULL, '2026-05-18 01:04:29', '2026-05-18 01:04:29'),
(18, 'laporan', 'Laporan Tabungan cabang 103 diverifikasi akunting', 'App\\Models\\Laporan', NULL, 5, 'App\\Models\\User', 4, '{\"jenis\": \"Tabungan\", \"cabang\": \"103\", \"periode\": \"Periode 8 Mei 2026\"}', NULL, '2026-05-18 01:05:37', '2026-05-18 01:05:37'),
(19, 'laporan', 'Laporan Deposito cabang 103 diverifikasi akunting', 'App\\Models\\Laporan', NULL, 6, 'App\\Models\\User', 4, '{\"jenis\": \"Deposito\", \"cabang\": \"103\", \"periode\": \"Periode 8 Mei 2026\"}', NULL, '2026-05-18 01:05:45', '2026-05-18 01:05:45'),
(20, 'periode', 'Periode Periode 8 Mei 2026 diverifikasi final oleh Kepala Operasional', 'App\\Models\\PeriodeLaporan', NULL, 1, 'App\\Models\\User', 6, '[]', NULL, '2026-05-18 01:10:48', '2026-05-18 01:10:48'),
(21, 'export', 'Export Excel periode Periode 8 Mei 2026 oleh akunting pusat', NULL, NULL, NULL, 'App\\Models\\User', 4, '{\"periode\": \"Periode 8 Mei 2026\", \"filename\": \"Laporan_Stock_Periode_2026-05-08.xlsx\"}', NULL, '2026-05-18 20:41:39', '2026-05-18 20:41:39');

-- --------------------------------------------------------

--
-- Table structure for table `cabangs`
--

CREATE TABLE `cabangs` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_cabang` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_cabang` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cabangs`
--

INSERT INTO `cabangs` (`id`, `kode_cabang`, `nama_cabang`, `alamat`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '101', 'Kantor Pare', NULL, 1, '2026-05-12 02:39:57', '2026-05-12 02:39:57'),
(2, '102', 'Kantor Gurah', 'Jl . DR. Wahidin No. 11 Gurah', 1, '2026-05-12 02:39:57', '2026-05-12 02:42:58'),
(3, '103', 'Kantor Sambi', 'Jl . Raya Sambi No. 31 Ringinrejo', 1, '2026-05-12 02:42:37', '2026-05-12 18:23:46');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporans`
--

CREATE TABLE `laporans` (
  `id` bigint UNSIGNED NOT NULL,
  `id_cabang` bigint UNSIGNED NOT NULL,
  `id_periode` bigint UNSIGNED NOT NULL,
  `jenis` enum('tabungan','deposito') COLLATE utf8mb4_unicode_ci NOT NULL,
  `saldo_awal` int UNSIGNED NOT NULL DEFAULT '0',
  `tambahan_stok` int UNSIGNED NOT NULL DEFAULT '0',
  `jumlah_digunakan` int UNSIGNED NOT NULL DEFAULT '0',
  `jml_dibatalkan_rusak` int UNSIGNED NOT NULL DEFAULT '0',
  `jml_dibatalkan_hilang` int UNSIGNED NOT NULL DEFAULT '0',
  `saldo_akhir` int UNSIGNED NOT NULL DEFAULT '0',
  `status_verifikasi` enum('draft','submitted','verified_accounting','revision_requested') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `tgl_submit` datetime DEFAULT NULL,
  `tgl_verifikasi_akunting` datetime DEFAULT NULL,
  `verified_by_akunting` bigint UNSIGNED DEFAULT NULL,
  `catatan_revisi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laporans`
--

INSERT INTO `laporans` (`id`, `id_cabang`, `id_periode`, `jenis`, `saldo_awal`, `tambahan_stok`, `jumlah_digunakan`, `jml_dibatalkan_rusak`, `jml_dibatalkan_hilang`, `saldo_akhir`, `status_verifikasi`, `tgl_submit`, `tgl_verifikasi_akunting`, `verified_by_akunting`, `catatan_revisi`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'tabungan', 0, 10, 3, 0, 0, 7, 'verified_accounting', '2026-05-18 07:01:06', '2026-05-18 07:48:21', 4, NULL, '2026-05-17 23:39:09', '2026-05-18 00:48:21'),
(2, 1, 1, 'deposito', 0, 5, 2, 0, 0, 3, 'verified_accounting', '2026-05-18 07:01:21', '2026-05-18 07:48:49', 4, NULL, '2026-05-17 23:39:09', '2026-05-18 00:48:49'),
(3, 2, 1, 'tabungan', 0, 14, 1, 0, 0, 13, 'verified_accounting', '2026-05-18 07:52:05', '2026-05-18 07:52:57', 4, NULL, '2026-05-17 23:39:09', '2026-05-18 00:52:57'),
(4, 2, 1, 'deposito', 0, 6, 2, 0, 0, 4, 'verified_accounting', '2026-05-18 07:05:24', '2026-05-18 07:50:36', 4, NULL, '2026-05-17 23:39:09', '2026-05-18 00:50:36'),
(5, 3, 1, 'tabungan', 0, 10, 3, 0, 0, 7, 'verified_accounting', '2026-05-18 08:03:29', '2026-05-18 08:05:37', 4, NULL, '2026-05-17 23:39:09', '2026-05-18 01:05:37'),
(6, 3, 1, 'deposito', 0, 8, 2, 0, 0, 6, 'verified_accounting', '2026-05-18 08:04:29', '2026-05-18 08:05:45', 4, NULL, '2026-05-17 23:39:09', '2026-05-18 01:05:45');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0000_12_31_000000_create_cabangs_table', 1),
(2, '0001_01_01_000000_create_users_table', 1),
(3, '0001_01_01_000001_create_cache_table', 1),
(4, '0001_01_01_000002_create_jobs_table', 1),
(5, '2026_05_12_033624_create_activity_log_table', 1),
(6, '2026_05_12_033625_add_event_column_to_activity_log_table', 1),
(7, '2026_05_12_033626_add_batch_uuid_column_to_activity_log_table', 1),
(8, '2026_05_18_061214_create_periode_laporans_table', 2),
(9, '2026_05_18_061250_create_laporans_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `periode_laporans`
--

CREATE TABLE `periode_laporans` (
  `id` bigint UNSIGNED NOT NULL,
  `tanggal_akhir` date NOT NULL COMMENT 'Selalu hari Jumat',
  `nama_periode` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Otomatis: Periode DD MMMM YYYY',
  `status_operasional` enum('pending','verified') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `tgl_verifikasi_operasional` datetime DEFAULT NULL,
  `verified_by_operasional` bigint UNSIGNED DEFAULT NULL,
  `is_current` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Hanya satu true',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `periode_laporans`
--

INSERT INTO `periode_laporans` (`id`, `tanggal_akhir`, `nama_periode`, `status_operasional`, `tgl_verifikasi_operasional`, `verified_by_operasional`, `is_current`, `created_at`, `updated_at`) VALUES
(1, '2026-05-08', 'Periode 8 Mei 2026', 'verified', '2026-05-18 08:10:48', 6, 0, '2026-05-17 23:39:09', '2026-05-18 01:10:48');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('PPww0HpKf0VsfhCPjeakMEDjFAuWulFl1rJx1tub', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNUFjWlhYWEhKU05LU1lReERmZnNsZVdEQkNRWkZFN08xcFBvQ2MyNiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9hdWRpdCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1779175221),
('VLlxzx8I4f1bnCuxUGjneTbOpge20EAJgadejrd6', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiY3FnUUVZR0VPU09mNUZwczJyeVdxNWg3SkhCNTEyUnRUOVpEdDBCZSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9waWMvZGFzaGJvYXJkIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1779175092);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `nik` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Format: AP123456789',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_cabang` bigint UNSIGNED DEFAULT NULL,
  `role` enum('pic_cabang','akunting','kepala_operasional','super_admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pic_cabang',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nik`, `name`, `email`, `email_verified_at`, `password`, `id_cabang`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'AP111111111', 'Super Admin', NULL, NULL, '$2y$12$wvCTc/.49eqopfx5XtdWFemZ1FrI5lQM0iUXiaPiYDXQNiCewDPT2', NULL, 'super_admin', 1, NULL, '2026-05-11 21:28:59', '2026-05-11 21:28:59'),
(2, 'AP222222222', 'pic pare', 'slamet@gmail.com', NULL, '$2y$12$WWvt2sB5/02Q8Ux0hPSH9egSStbSLzHhtuc53d2dp3siVPmluY1Q.', 1, 'pic_cabang', 1, NULL, '2026-05-12 02:46:25', '2026-05-12 02:46:44'),
(3, 'AP333333333', 'pic gurah', 'slamet@gmail.com', NULL, '$2y$12$LogJb0O9lXa02VanwlJkfOOjVOvqBwmFu4XxJfYAtVIXtHziBp2Ii', 2, 'pic_cabang', 1, NULL, '2026-05-12 03:06:40', '2026-05-18 00:04:30'),
(4, 'AP121212121', 'akunting pusat', 'cakin@cutiapp.com', NULL, '$2y$12$bTdgQ0PxPiTBsuBYjGzxNOoZjEK30oTiFvVqVDTLeBxND.jBOAgQy', NULL, 'akunting', 1, NULL, '2026-05-18 00:33:02', '2026-05-18 00:33:02'),
(5, 'AP444444444', 'pic sambi', NULL, NULL, '$2y$12$/QS0EIdfA.OOoWRAvGXQCeQl2NflaSAUM.TMJ7gm.2xka2fAG4BXK', 3, 'pic_cabang', 1, NULL, '2026-05-18 01:02:34', '2026-05-18 01:02:34'),
(6, 'AP212121212', 'kabag op', 'slamet@gmail.com', NULL, '$2y$12$SAPjnUeeY6vzDKLgHvUGZuJiwm6YJ/cu8VOpRJBwa0PYIwqKMOJxm', NULL, 'kepala_operasional', 1, NULL, '2026-05-18 01:07:49', '2026-05-18 01:07:49');

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
-- Indexes for table `cabangs`
--
ALTER TABLE `cabangs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cabangs_kode_cabang_unique` (`kode_cabang`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

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
-- Indexes for table `laporans`
--
ALTER TABLE `laporans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `laporans_id_cabang_id_periode_jenis_unique` (`id_cabang`,`id_periode`,`jenis`),
  ADD KEY `laporans_id_periode_foreign` (`id_periode`),
  ADD KEY `laporans_verified_by_akunting_foreign` (`verified_by_akunting`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `periode_laporans`
--
ALTER TABLE `periode_laporans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `periode_laporans_tanggal_akhir_unique` (`tanggal_akhir`),
  ADD KEY `periode_laporans_verified_by_operasional_foreign` (`verified_by_operasional`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_nik_unique` (`nik`),
  ADD KEY `users_id_cabang_foreign` (`id_cabang`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `cabangs`
--
ALTER TABLE `cabangs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT for table `laporans`
--
ALTER TABLE `laporans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `periode_laporans`
--
ALTER TABLE `periode_laporans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `laporans`
--
ALTER TABLE `laporans`
  ADD CONSTRAINT `laporans_id_cabang_foreign` FOREIGN KEY (`id_cabang`) REFERENCES `cabangs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `laporans_id_periode_foreign` FOREIGN KEY (`id_periode`) REFERENCES `periode_laporans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `laporans_verified_by_akunting_foreign` FOREIGN KEY (`verified_by_akunting`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `periode_laporans`
--
ALTER TABLE `periode_laporans`
  ADD CONSTRAINT `periode_laporans_verified_by_operasional_foreign` FOREIGN KEY (`verified_by_operasional`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_id_cabang_foreign` FOREIGN KEY (`id_cabang`) REFERENCES `cabangs` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
