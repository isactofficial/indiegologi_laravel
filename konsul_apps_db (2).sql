-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 04, 2025 at 04:49 AM
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
-- Database: `konsul_apps_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `status` enum('Draft','Published') NOT NULL DEFAULT 'Draft',
  `views` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `user_id`, `title`, `slug`, `author`, `description`, `thumbnail`, `status`, `views`, `created_at`, `updated_at`, `published_at`) VALUES
(1, 1, '1321', '1321', '132', '132', 'thumbnails/1753266058_lOewgVlXFK.png', 'Published', 60, '2025-07-18 13:49:06', '2025-07-23 03:20:58', '2025-07-18 22:13:12'),
(2, 1, '33', '33', '33', '33', 'thumbnails/1752904914_1twLXeWq02.png', 'Draft', 0, '2025-07-18 23:01:54', '2025-07-18 23:01:54', NULL),
(3, 1, '312', '312', '132', '132', 'thumbnails/1753266031_pQg8b5VWej.png', 'Draft', 0, '2025-07-18 23:16:56', '2025-07-23 03:20:31', NULL),
(4, 1, '2133122312313', '213312', '132', '312', 'thumbnails/1754170981.png', 'Draft', 0, '2025-07-23 07:19:12', '2025-08-02 14:43:02', NULL),
(8, 1, '132wqwsasa', '132wqwsasa', 'sqwqe', 'sasdqw', 'thumbnails/1754171214.png', 'Draft', 0, '2025-08-02 14:46:55', '2025-08-02 14:46:55', NULL),
(9, 1, '321', '321', 'sasa', 'qweqwe', 'thumbnails/1754171476.png', 'Draft', 0, '2025-08-02 14:51:16', '2025-08-02 14:51:16', NULL),
(10, 1, 'sasa', 'sasa', 'awdqeweq', 'qweqweq', 'thumbnails/1754171541.png', 'Draft', 0, '2025-08-02 14:52:22', '2025-08-02 14:52:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booking_service`
--

CREATE TABLE `booking_service` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `total_price_at_booking` decimal(10,2) DEFAULT NULL,
  `discount_amount_at_booking` decimal(10,2) DEFAULT NULL,
  `final_price_at_booking` decimal(10,2) DEFAULT NULL,
  `booked_date` date DEFAULT NULL,
  `booked_time` time DEFAULT NULL,
  `hours_booked` int(11) NOT NULL DEFAULT 1,
  `session_type` enum('online','offline') NOT NULL DEFAULT 'online',
  `offline_address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `referral_code_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_service`
--

INSERT INTO `booking_service` (`id`, `booking_id`, `service_id`, `total_price_at_booking`, `discount_amount_at_booking`, `final_price_at_booking`, `booked_date`, `booked_time`, `hours_booked`, `session_type`, `offline_address`, `created_at`, `updated_at`, `referral_code_id`) VALUES
(8, 14, 3, 600003.00, 0.00, 600003.00, '2025-08-07', '12:39:00', 0, 'online', NULL, '2025-08-03 10:43:35', '2025-08-03 10:43:35', NULL),
(11, 16, 3, 21888527.00, 4377705.40, 17510821.60, '2025-08-23', '13:51:00', 4, 'online', NULL, '2025-08-03 11:52:36', '2025-08-03 14:26:39', 2),
(12, 16, 4, 60000.00, 12000.00, 48000.00, '2025-08-04', '01:54:00', 0, 'online', NULL, '2025-08-03 11:52:36', '2025-08-03 14:26:39', 2),
(13, 6, 3, 5922134.00, 0.00, 5922134.00, '2025-08-15', '16:07:00', 1, 'online', NULL, '2025-08-03 14:05:18', '2025-08-03 14:05:43', NULL),
(14, 10, 3, 600003.00, 0.00, 600003.00, '2025-08-07', '16:06:00', 0, 'online', NULL, '2025-08-03 14:06:37', '2025-08-03 14:08:18', NULL),
(16, 16, 2, 40999.00, 8199.80, 32799.20, '2025-08-06', '04:26:00', 3, 'online', NULL, '2025-08-03 14:26:17', '2025-08-03 14:26:39', 2);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `article_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `consultation_bookings`
--

CREATE TABLE `consultation_bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_name` varchar(255) DEFAULT NULL,
  `contact_preference` enum('chat_only','chat_and_call') NOT NULL DEFAULT 'chat_only',
  `referral_code_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_type` enum('online','offline') NOT NULL,
  `offline_address` text DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `final_price` decimal(10,2) NOT NULL,
  `payment_type` enum('dp','full_payment') NOT NULL DEFAULT 'full_payment',
  `session_status` enum('menunggu pembayaran','terdaftar','ongoing','selesai','dibatalkan') NOT NULL DEFAULT 'menunggu pembayaran',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `consultation_bookings`
--

INSERT INTO `consultation_bookings` (`id`, `user_id`, `receiver_name`, `contact_preference`, `referral_code_id`, `invoice_id`, `session_type`, `offline_address`, `discount_amount`, `final_price`, `payment_type`, `session_status`, `created_at`, `updated_at`, `service_id`) VALUES
(6, 1, 'sasa', 'chat_only', NULL, 7, 'online', NULL, 0.00, 6522137.00, 'full_payment', 'menunggu pembayaran', '2025-07-25 10:28:32', '2025-08-03 14:05:43', NULL),
(10, 1, 'sasa', 'chat_only', NULL, 12, 'online', NULL, 0.00, 1200006.00, 'dp', 'menunggu pembayaran', '2025-08-03 10:36:25', '2025-08-03 14:08:18', NULL),
(14, 1, 'sasa', 'chat_only', NULL, 16, 'online', NULL, 0.00, 600003.00, 'dp', 'menunggu pembayaran', '2025-08-03 10:43:34', '2025-08-03 10:43:34', NULL),
(16, 1, 'haikal', 'chat_only', NULL, 18, 'online', NULL, 4397905.20, 17591620.80, 'full_payment', 'terdaftar', '2025-08-03 11:52:36', '2025-08-03 14:26:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `consultation_services`
--

CREATE TABLE `consultation_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `hourly_price` decimal(10,2) DEFAULT NULL,
  `short_description` varchar(255) NOT NULL,
  `status` enum('draft','published','special') NOT NULL DEFAULT 'draft',
  `product_description` text NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `consultation_services`
--

INSERT INTO `consultation_services` (`id`, `title`, `price`, `hourly_price`, `short_description`, `status`, `product_description`, `thumbnail`, `created_at`, `updated_at`) VALUES
(2, 'sasa', 40000.00, 333.00, 'asdqeeqweqw', 'published', 'eqweqwdsad', 'service-thumbnails/1754207404.png', '2025-07-23 07:20:15', '2025-08-03 14:23:05'),
(3, '132', 600003.00, 5322131.00, '1aasdad', 'published', 'eqweqweqweqwddas', 'service-thumbnails/1754207423.png', '2025-08-03 00:50:23', '2025-08-03 08:18:17'),
(4, 'Personal Counseling Add', 60000.00, 0.00, '132', 'special', '123', 'service-thumbnails/1754208861.png', '2025-08-03 01:14:22', '2025-08-03 10:25:55');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) NOT NULL,
  `invoice_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `due_date` timestamp NULL DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `payment_type` enum('dp','full_payment') NOT NULL,
  `payment_status` enum('paid','unpaid','pending','dibatalkan') NOT NULL DEFAULT 'unpaid',
  `session_type` enum('online','offline') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `user_id`, `invoice_no`, `invoice_date`, `due_date`, `total_amount`, `paid_amount`, `discount_amount`, `payment_type`, `payment_status`, `session_type`, `created_at`, `updated_at`) VALUES
(3, 1, 'INV-CUPZPDHJ-20250725', '2025-07-25 02:39:44', '2025-07-26 02:39:44', 40123.00, 0.00, NULL, 'full_payment', 'unpaid', 'online', '2025-07-25 02:39:44', '2025-07-25 02:39:44'),
(4, 1, 'INV-DSCDNHL2-20250725', '2025-07-25 02:40:13', '2025-07-26 02:40:13', 40123.00, 0.00, NULL, 'full_payment', 'unpaid', 'online', '2025-07-25 02:40:13', '2025-07-25 02:40:13'),
(7, 1, 'INV-EYRFFSI6-20250725', '2025-08-03 21:05:43', '2025-07-26 10:28:32', 6522137.00, 6522137.00, 0.00, 'full_payment', 'pending', 'online', '2025-07-25 10:28:32', '2025-08-03 14:05:43'),
(11, 1, 'INV-1754242337', '2025-08-03 10:32:17', '2025-08-10 10:32:17', 4785707.20, 2392853.60, 1196426.80, 'dp', 'pending', 'online', '2025-08-03 10:32:17', '2025-08-03 10:32:17'),
(12, 1, 'INV-1754242585', '2025-08-03 21:08:18', '2025-08-10 10:36:25', 1200006.00, 600003.00, 0.00, 'dp', 'pending', 'online', '2025-08-03 10:36:25', '2025-08-03 14:08:18'),
(16, 1, 'INV-1754243014', '2025-08-03 10:43:34', '2025-08-10 10:43:34', 600003.00, 300001.50, 0.00, 'dp', 'pending', 'online', '2025-08-03 10:43:34', '2025-08-03 10:43:34'),
(18, 1, 'INV-1754247156', '2025-08-03 21:26:39', '2025-08-10 11:52:36', 17591620.80, 17591620.80, 4397905.20, 'full_payment', 'pending', 'online', '2025-08-03 11:52:36', '2025-08-03 14:26:39');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(4, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(5, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(6, '2016_06_01_000004_create_oauth_clients_table', 1),
(7, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(8, '2019_08_19_000000_create_failed_jobs_table', 1),
(9, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(10, '2025_07_18_130108_create_articles_table', 1),
(11, '2025_07_18_130117_create_subheadings_table', 1),
(12, '2025_07_18_130126_create_paragraphs_table', 1),
(13, '2025_07_18_130135_create_comments_table', 1),
(14, '2025_07_18_130148_create_consultation_services_table', 1),
(15, '2025_07_18_130244_create_referral_codes_table', 1),
(16, '2025_07_18_130254_create_invoices_table', 1),
(17, '2025_07_18_130302_create_consultation_bookings_table', 1),
(18, '2025_07_18_130311_create_sketches_table', 1),
(19, '2025_07_18_130319_create_user_profiles_table', 1),
(20, '2025_07_23_110613_add_thumbnail_to_consultation_services_table', 2),
(23, '2025_07_25_090331_create_booking_service_table', 3),
(24, '2025_07_25_154226_add_date_time_to_booking_service_table', 4),
(25, '2025_07_25_163856_add_session_type_address_to_booking_service_table', 5),
(26, '2025_07_27_153520_add_receiver_name_to_consultation_bookings_table', 6),
(27, '2025_08_03_073913_add_hourly_price_to_consultation_services_table', 7),
(28, '2025_08_03_080612_add_status_to_consultation_services_table', 8),
(29, '2025_08_03_080944_update_status_enum_on_consultation_services_table', 9),
(30, '2025_08_03_143148_add_hours_booked_to_booking_service_table', 10),
(31, '2025_08_03_143216_add_discount_amount_to_invoices_table', 11),
(32, '2025_08_03_152238_update_booking_service_table_for_multi_service', 12),
(33, '2025_08_03_163151_add_enums_to_booking_tables', 13),
(34, '2025_08_03_170926_add_price_fields_to_booking_service_table', 14),
(35, '2025_08_03_173716_remove_price_at_booking_from_booking_service_table', 15);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('0270f71ae19f0aa3ca175c33dee66581fda8268ee61b49e09156acbaf2f93428e3ce22c12ca11416', 1, 1, NULL, '[\"*\"]', 0, '2025-07-18 12:31:47', '2025-07-18 12:31:47', '2025-07-18 20:31:47'),
('e142457f17f97bf33056d57e174908ce5a9be7e12404bc87876cde17aa90250d82deaee02436471e', 1, 1, NULL, '[\"*\"]', 0, '2025-07-18 12:29:19', '2025-07-18 12:29:19', '2025-07-18 20:29:19');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `redirect` text NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Password Grant Client', '47eKVds7XZDP8fz88MpkhoqNII5gcggLOPTTIbtt', 'users', 'http://localhost', 0, 1, 0, '2025-07-18 12:23:57', '2025-07-18 12:23:57'),
(2, NULL, 'Laravel Personal Access Client', '0UMmnoWzzOnag4ta0PyjILI4M0DFWMkv5WhvB7uG', NULL, 'http://localhost', 1, 0, 0, '2025-07-18 12:30:50', '2025-07-18 12:30:50'),
(3, NULL, 'Laravel Password Grant Client', 'JMka8hIfaLv7d8CG1acPqTjGSS3GxPqlCHPSu72T', 'users', 'http://localhost', 0, 1, 0, '2025-07-18 12:30:50', '2025-07-18 12:30:50');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-07-18 11:47:34', '2025-07-18 11:47:34'),
(2, 3, '2025-07-18 11:48:28', '2025-07-18 11:48:28'),
(3, 2, '2025-07-18 12:30:50', '2025-07-18 12:30:50');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_refresh_tokens`
--

INSERT INTO `oauth_refresh_tokens` (`id`, `access_token_id`, `revoked`, `expires_at`) VALUES
('861a784bc8bf995c875eb14e60ef44bd7dc13f3d9c40be1d7c0b2ce2d933d2e11f7dc56283f5b38d', '0270f71ae19f0aa3ca175c33dee66581fda8268ee61b49e09156acbaf2f93428e3ce22c12ca11416', 0, '2025-07-25 19:31:47'),
('e3266aa405b1f1eda429e9147454ff97904acd0f80d86ea4b3378bf554b5e2ffc2b3bbeccc44ea10', 'e142457f17f97bf33056d57e174908ce5a9be7e12404bc87876cde17aa90250d82deaee02436471e', 0, '2025-07-25 19:29:19');

-- --------------------------------------------------------

--
-- Table structure for table `paragraphs`
--

CREATE TABLE `paragraphs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subheading_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `order_number` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `paragraphs`
--

INSERT INTO `paragraphs` (`id`, `subheading_id`, `content`, `order_number`, `created_at`, `updated_at`) VALUES
(3, 3, '3', 1, '2025-07-23 03:20:31', '2025-07-23 03:20:31'),
(4, 4, '132', 1, '2025-07-23 03:20:58', '2025-07-23 03:20:58'),
(5, 5, '132', 1, '2025-07-23 07:19:12', '2025-07-23 07:19:12'),
(6, 6, '312', 1, '2025-07-23 07:19:12', '2025-07-23 07:19:12'),
(11, 10, 'asas', 1, '2025-08-02 14:46:55', '2025-08-02 14:46:55'),
(12, 11, 'qewqew', 1, '2025-08-02 14:51:16', '2025-08-02 14:51:16'),
(13, 12, 'qewqewqwe', 1, '2025-08-02 14:52:22', '2025-08-02 14:52:22');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(10, 'App\\Models\\User', 1, 'auth_token', 'a4f09ee15a0362fc819407be0fe5a66df5a2eac0818cfba4add78ef7bbad9d97', '[\"*\"]', '2025-07-27 12:13:11', NULL, '2025-07-27 08:32:04', '2025-07-27 12:13:11');

-- --------------------------------------------------------

--
-- Table structure for table `referral_codes`
--

CREATE TABLE `referral_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `discount_percentage` decimal(5,2) NOT NULL,
  `valid_from` timestamp NULL DEFAULT NULL,
  `valid_until` timestamp NULL DEFAULT NULL,
  `max_uses` int(11) DEFAULT NULL,
  `current_uses` int(11) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `referral_codes`
--

INSERT INTO `referral_codes` (`id`, `code`, `discount_percentage`, `valid_from`, `valid_until`, `max_uses`, `current_uses`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'YOLNXDBVGB', 32.00, '2025-07-09 17:00:00', '2025-07-16 17:00:00', 10, 0, 1, '2025-07-23 03:40:43', '2025-07-23 03:40:43'),
(2, 'HAIKAL', 20.00, '2025-07-22 17:00:00', '2025-08-29 17:00:00', 323312, 14, 1, '2025-07-23 03:44:23', '2025-08-03 12:55:20'),
(3, '32', 33.00, '2025-06-30 17:00:00', '2025-07-25 17:00:00', 33, 0, 1, '2025-07-23 07:18:25', '2025-07-23 08:04:44'),
(4, 'FNDRB', 3.00, '2025-08-04 17:00:00', '2025-08-29 17:00:00', 12313, 0, 1, '2025-08-03 00:36:39', '2025-08-03 00:36:39');

-- --------------------------------------------------------

--
-- Table structure for table `sketches`
--

CREATE TABLE `sketches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `status` enum('Draft','Published') NOT NULL DEFAULT 'Draft',
  `views` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sketches`
--

INSERT INTO `sketches` (`id`, `user_id`, `title`, `slug`, `author`, `thumbnail`, `status`, `views`, `content`, `created_at`, `updated_at`) VALUES
(1, 1, '3334', '333', '33', 'thumbnails/1753265522_DCHSTqmVoe.png', 'Published', 0, '132124', '2025-07-18 23:20:34', '2025-07-23 03:12:03'),
(2, 1, 'sasa', 'sasa', '123', 'thumbnails/1753280322_JzBTeQXwZp.png', 'Draft', 0, '132', '2025-07-23 07:18:42', '2025-07-23 07:18:42'),
(3, 1, 'sadasdsa', 'sadasd', 'Arsya Rivaldo', 'sketches/1754205429.png', 'Draft', 0, 'saeq41431eqewqeq', '2025-08-02 15:09:04', '2025-08-03 00:17:11');

-- --------------------------------------------------------

--
-- Table structure for table `subheadings`
--

CREATE TABLE `subheadings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `article_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `order_number` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subheadings`
--

INSERT INTO `subheadings` (`id`, `article_id`, `title`, `order_number`, `created_at`, `updated_at`) VALUES
(3, 3, '3', 1, '2025-07-23 03:20:31', '2025-07-23 03:20:31'),
(4, 1, '123', 1, '2025-07-23 03:20:58', '2025-07-23 03:20:58'),
(5, 4, '132', 1, '2025-07-23 07:19:12', '2025-07-23 07:19:12'),
(6, 4, '312', 2, '2025-07-23 07:19:12', '2025-07-23 07:19:12'),
(10, 8, 'qewqe', 1, '2025-08-02 14:46:55', '2025-08-02 14:46:55'),
(11, 9, 'qeweq', 1, '2025-08-02 14:51:16', '2025-08-02 14:51:16'),
(12, 10, 'qewqwe', 1, '2025-08-02 14:52:22', '2025-08-02 14:52:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','author','reader') NOT NULL DEFAULT 'reader',
  `google_id` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `google_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Utama', 'sasa1234@gmail.com', NULL, '$2y$10$V95ty/xV3.omaCqeEBNTHO4STu30Fr.q.nA2bWc5GVKmXX0NouAK6', 'admin', NULL, NULL, '2025-07-18 11:43:51', '2025-07-18 11:43:51');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `social_media` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visits`
--

CREATE TABLE `visits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `url` text NOT NULL,
  `visited_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `visits`
--

INSERT INTO `visits` (`id`, `ip_address`, `user_agent`, `url`, `visited_at`) VALUES
(1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000', '2025-08-02 12:28:35'),
(2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/_ignition/health-check', '2025-08-02 12:28:37'),
(3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', '2025-08-02 12:28:46'),
(4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', '2025-08-02 12:35:19'),
(5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/dashboard', '2025-08-02 12:35:25'),
(6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/_ignition/health-check', '2025-08-02 12:35:26'),
(7, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', '2025-08-02 13:51:28'),
(8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/dashboard', '2025-08-02 13:51:29'),
(9, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/_ignition/health-check', '2025-08-02 13:51:32'),
(10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/.well-known/appspecific/com.chrome.devtools.json', '2025-08-02 14:07:25'),
(11, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000', '2025-08-02 14:11:14'),
(12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/_ignition/health-check', '2025-08-02 14:11:16'),
(13, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', '2025-08-02 14:11:22'),
(14, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/dashboard', '2025-08-02 14:11:28'),
(15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000', '2025-08-02 14:18:47'),
(16, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/_ignition/health-check', '2025-08-02 14:18:48'),
(17, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000', '2025-08-03 00:15:14'),
(18, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/_ignition/health-check', '2025-08-03 00:15:19'),
(19, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', '2025-08-03 00:15:45'),
(20, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/dashboard', '2025-08-03 00:15:53'),
(21, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000', '2025-08-03 06:00:58'),
(22, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', '2025-08-03 06:01:12'),
(23, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/dashboard', '2025-08-03 06:01:22'),
(24, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', '2025-08-03 08:16:29'),
(25, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/dashboard', '2025-08-03 08:16:36'),
(26, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/_ignition/health-check', '2025-08-03 09:57:00'),
(27, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/_ignition/health-check', '2025-08-03 10:39:20'),
(28, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/.well-known/appspecific/com.chrome.devtools.json', '2025-08-03 11:00:19'),
(29, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/.well-known/appspecific/com.chrome.devtools.json', '2025-08-03 11:07:01'),
(30, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/.well-known/appspecific/com.chrome.devtools.json', '2025-08-03 11:13:14'),
(31, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/.well-known/appspecific/com.chrome.devtools.json', '2025-08-03 11:20:35'),
(32, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/.well-known/appspecific/com.chrome.devtools.json', '2025-08-03 11:27:14'),
(33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/.well-known/appspecific/com.chrome.devtools.json', '2025-08-03 11:55:23'),
(34, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/.well-known/appspecific/com.chrome.devtools.json', '2025-08-03 12:04:52'),
(35, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/.well-known/appspecific/com.chrome.devtools.json', '2025-08-03 12:11:04'),
(36, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/logout', '2025-08-03 14:27:47'),
(37, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', '2025-08-03 14:27:47'),
(38, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000', '2025-08-03 14:27:51'),
(39, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000', '2025-08-03 19:39:31'),
(40, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', '2025-08-03 19:39:39'),
(41, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/dashboard', '2025-08-03 19:39:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `articles_slug_unique` (`slug`),
  ADD KEY `articles_user_id_foreign` (`user_id`);

--
-- Indexes for table `booking_service`
--
ALTER TABLE `booking_service`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_service_booking_id_service_id_unique` (`booking_id`,`service_id`),
  ADD KEY `booking_service_service_id_foreign` (`service_id`),
  ADD KEY `booking_service_referral_code_id_foreign` (`referral_code_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_article_id_foreign` (`article_id`),
  ADD KEY `comments_user_id_foreign` (`user_id`);

--
-- Indexes for table `consultation_bookings`
--
ALTER TABLE `consultation_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `consultation_bookings_user_id_foreign` (`user_id`),
  ADD KEY `consultation_bookings_referral_code_id_foreign` (`referral_code_id`),
  ADD KEY `consultation_bookings_invoice_id_foreign` (`invoice_id`);

--
-- Indexes for table `consultation_services`
--
ALTER TABLE `consultation_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_no_unique` (`invoice_no`),
  ADD KEY `invoices_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `paragraphs`
--
ALTER TABLE `paragraphs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paragraphs_subheading_id_foreign` (`subheading_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `referral_codes`
--
ALTER TABLE `referral_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `referral_codes_code_unique` (`code`),
  ADD KEY `referral_codes_created_by_foreign` (`created_by`);

--
-- Indexes for table `sketches`
--
ALTER TABLE `sketches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sketches_slug_unique` (`slug`),
  ADD KEY `sketches_user_id_foreign` (`user_id`);

--
-- Indexes for table `subheadings`
--
ALTER TABLE `subheadings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subheadings_article_id_foreign` (`article_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_google_id_unique` (`google_id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_profiles_user_id_unique` (`user_id`);

--
-- Indexes for table `visits`
--
ALTER TABLE `visits`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `booking_service`
--
ALTER TABLE `booking_service`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `consultation_bookings`
--
ALTER TABLE `consultation_bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `consultation_services`
--
ALTER TABLE `consultation_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `paragraphs`
--
ALTER TABLE `paragraphs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `referral_codes`
--
ALTER TABLE `referral_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sketches`
--
ALTER TABLE `sketches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `subheadings`
--
ALTER TABLE `subheadings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visits`
--
ALTER TABLE `visits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_service`
--
ALTER TABLE `booking_service`
  ADD CONSTRAINT `booking_service_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `consultation_bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_service_referral_code_id_foreign` FOREIGN KEY (`referral_code_id`) REFERENCES `referral_codes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `booking_service_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `consultation_services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `consultation_bookings`
--
ALTER TABLE `consultation_bookings`
  ADD CONSTRAINT `consultation_bookings_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `consultation_bookings_referral_code_id_foreign` FOREIGN KEY (`referral_code_id`) REFERENCES `referral_codes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `consultation_bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `paragraphs`
--
ALTER TABLE `paragraphs`
  ADD CONSTRAINT `paragraphs_subheading_id_foreign` FOREIGN KEY (`subheading_id`) REFERENCES `subheadings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `referral_codes`
--
ALTER TABLE `referral_codes`
  ADD CONSTRAINT `referral_codes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sketches`
--
ALTER TABLE `sketches`
  ADD CONSTRAINT `sketches_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subheadings`
--
ALTER TABLE `subheadings`
  ADD CONSTRAINT `subheadings_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
