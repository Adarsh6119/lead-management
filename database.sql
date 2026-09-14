-- TaxiCRM / Lead Management System - Production MySQL / MariaDB Database Dump
-- Compatible with MySQL 5.7+, MySQL 8.0+, MariaDB 10.3+

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `login_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'employee',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_login_id_unique` (`login_id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Initial Data Seed for `users`
-- --------------------------------------------------------

INSERT INTO `users` (`id`, `name`, `login_id`, `email`, `role`, `status`, `phone`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'System Admin', 'ADMIN01', 'admin@taxicrm.com', 'admin', 'active', '9876543210', 1, NULL, '$2y$12$R5hTjVkWtL09f2g2Z3y/0e5b7.e5c6a7b8c9d0e1f2g3h4i5j6k7', NULL, NOW(), NOW()),
(2, 'Vikram TeamLead', 'TL001', 'tl@taxicrm.com', 'head', 'active', '9876543212', 1, NULL, '$2y$12$O2RUlUriXZTdWNslc4JEceiXFhw8llaqaYl3ERPc/O6.DEIAfF8QO', NULL, NOW(), NOW()),
(3, 'Accounts Manager', 'ACCT01', 'accounts@taxicrm.com', 'accountant', 'active', '9876543211', 1, NULL, '$2y$12$7kP.e1f2g3h4i5j6k7l8m9n0o1p2q3r4s5t6u7v8w9x0y1z2a3b4', NULL, NOW(), NOW());

-- --------------------------------------------------------
-- Table structure for table `cab_types`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `cab_types` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seating_capacity` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `base_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `cab_types` (`id`, `name`, `seating_capacity`, `description`, `base_rate`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Hatchback (WagonR / Indica)', '4 Passengers', 'Economical compact hatchback for budget travel', 2500.00, 1, NOW(), NOW()),
(2, 'Sedan (Dzire / Etios)', '4 Passengers', 'Comfortable AC Sedan for outstation & local', 3500.00, 1, NOW(), NOW()),
(3, 'SUV (Ertiga / XL6)', '6 Passengers', 'Spacious 6-seater SUV for family trips', 4800.00, 1, NOW(), NOW()),
(4, 'Premium SUV (Innova Crysta)', '7 Passengers', 'Luxury 7-seater Innova Crysta for premium comfort', 6500.00, 1, NOW(), NOW()),
(5, 'Tempo Traveller (12 Seater)', '12 Passengers', 'Large tempo traveller for group tours', 9500.00, 1, NOW(), NOW()),
(6, 'Hire Driver Only', 'N/A', 'Professional outstation & local driver on daily basis', 1200.00, 1, NOW(), NOW());

-- --------------------------------------------------------
-- Table structure for table `leads`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `leads` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date_created` date NOT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_no` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pickup_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pickup_date` date DEFAULT NULL,
  `pickup_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `trip_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'one_way',
  `cab_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Uttar Pradesh',
  `web_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discounted_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `final_quoted_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `offer_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `quotation_sent` tinyint(1) NOT NULL DEFAULT 0,
  `ticket_generated` tinyint(1) NOT NULL DEFAULT 0,
  `app_download` tinyint(1) NOT NULL DEFAULT 0,
  `last_followup_date` date DEFAULT NULL,
  `next_followup_date` date DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'New Lead',
  `tl_note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tl_note_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tl_note_at` timestamp NULL DEFAULT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `employee_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leads_mobile_no_index` (`mobile_no`),
  KEY `leads_status_index` (`status`),
  KEY `leads_source_index` (`source`),
  KEY `leads_employee_id_index` (`employee_id`),
  KEY `leads_next_followup_date_index` (`next_followup_date`),
  CONSTRAINT `leads_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `lead_remarks`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `lead_remarks` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `lead_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `added_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lead_remarks_lead_id_index` (`lead_id`),
  KEY `lead_remarks_user_id_index` (`user_id`),
  CONSTRAINT `lead_remarks_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `bookings`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lead_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` date NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_no` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pickup_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pickup_date` date DEFAULT NULL,
  `pickup_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `trip_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'one_way',
  `cab_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reporting_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver_mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cab_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `advance_payment` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_mode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UPI',
  `source_tag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Confirmed',
  `employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `employee_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookings_booking_id_unique` (`booking_id`),
  KEY `bookings_booking_status_index` (`booking_status`),
  KEY `bookings_employee_id_index` (`employee_id`),
  CONSTRAINT `bookings_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `accountings`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `accountings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_no` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estimated_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `advance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `gst_on_advance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `igst_advance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cgst_advance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sgst_advance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pending` decimal(10,2) NOT NULL DEFAULT 0.00,
  `gst_on_pending` decimal(10,2) NOT NULL DEFAULT 0.00,
  `igst_pending` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cgst_pending` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sgst_pending` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_gst` decimal(10,2) NOT NULL DEFAULT 0.00,
  `customer_state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_reco_status` tinyint(1) NOT NULL DEFAULT 0,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Advance Paid',
  `employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `accountings_booking_id_index` (`booking_id`),
  KEY `accountings_customer_state_index` (`customer_state`),
  KEY `accountings_employee_id_index` (`employee_id`),
  CONSTRAINT `accountings_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `employee_targets`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `employee_targets` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `lead_target` int(11) NOT NULL DEFAULT 50,
  `booking_target` int(11) NOT NULL DEFAULT 10,
  `revenue_target` decimal(10,2) NOT NULL DEFAULT 100000.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_targets_employee_month_year_unique` (`employee_id`,`month`,`year`),
  CONSTRAINT `employee_targets_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `employee_meeting_notes`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `employee_meeting_notes` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `tl_id` bigint(20) UNSIGNED NOT NULL,
  `meeting_date` date NOT NULL,
  `quotation_not_sending` tinyint(1) NOT NULL DEFAULT 0,
  `images_not_sending` tinyint(1) NOT NULL DEFAULT 0,
  `followup_not_regular` tinyint(1) NOT NULL DEFAULT 0,
  `cannot_convince_customer` tinyint(1) NOT NULL DEFAULT 0,
  `not_providing_discount` tinyint(1) NOT NULL DEFAULT 0,
  `conversation_not_good` tinyint(1) NOT NULL DEFAULT 0,
  `remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_meeting_notes_employee_id_index` (`employee_id`),
  KEY `employee_meeting_notes_meeting_date_index` (`meeting_date`),
  CONSTRAINT `employee_meeting_notes_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `employee_meeting_notes_tl_id_foreign` FOREIGN KEY (`tl_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `migrations`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_01_000003_create_cab_types_table', 1),
(5, '2024_01_01_000004_create_leads_table', 1),
(6, '2024_01_01_000005_create_lead_remarks_table', 1),
(7, '2024_01_01_000006_create_bookings_table', 1),
(8, '2024_01_01_000007_create_accountings_table', 1),
(9, '2024_01_01_000008_create_employee_targets_table', 1),
(10, '2024_01_01_000009_add_tl_fields_and_meeting_notes', 1);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
