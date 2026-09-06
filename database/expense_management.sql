-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 07, 2026 at 08:00 AM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `expense_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Super Admin', 'admin@gmail.com', '$2y$10$cck3otuCS.VdEG8o9Bv01OArZRu/Zw4KfHY1iLuB4ltKl9SphkzFq', '2026-03-02 06:00:48');

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

DROP TABLE IF EXISTS `admin_logs`;
CREATE TABLE IF NOT EXISTS `admin_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `admin_id` int DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_logs`
--

INSERT INTO `admin_logs` (`id`, `admin_id`, `action`, `created_at`) VALUES
(1, 1, 'Changed user status (User ID: 1)', '2026-03-02 08:13:53'),
(2, 1, 'Changed user status (User ID: 1)', '2026-03-02 08:14:15'),
(3, 1, 'Admin logged in', '2026-03-06 05:01:06'),
(4, 1, 'Admin logged in', '2026-03-06 06:22:45'),
(5, 1, 'Admin logged in', '2026-03-06 07:52:01'),
(6, 1, 'Changed user status (User ID: 1)', '2026-03-06 07:52:23'),
(7, 1, 'Changed user status (User ID: 1)', '2026-03-06 07:52:36'),
(8, 1, 'Changed user status (User ID: 1)', '2026-03-06 07:52:49'),
(9, 1, 'Changed user status (User ID: 1)', '2026-03-06 07:52:50'),
(10, 1, 'Admin logged in', '2026-03-06 12:36:55'),
(11, 1, 'Admin logged in', '2026-03-06 18:34:12'),
(12, 1, 'Admin logged in', '2026-03-07 04:59:17'),
(13, 1, 'Admin logged in', '2026-03-07 05:20:43'),
(14, 1, 'Admin logged in', '2026-03-07 06:42:18'),
(15, 1, 'Admin logged in', '2026-03-07 07:52:22');

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

DROP TABLE IF EXISTS `budgets`;
CREATE TABLE IF NOT EXISTS `budgets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `month` int DEFAULT NULL,
  `year` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `budgets`
--

INSERT INTO `budgets` (`id`, `user_id`, `amount`, `month`, `year`) VALUES
(1, 1, 20000.00, 2, 2026),
(2, 1, 15000.00, 3, 2026);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `category_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` enum('admin','user') COLLATE utf8mb4_unicode_ci DEFAULT 'admin',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `user_id`, `category_name`, `created_at`, `created_by`) VALUES
(1, NULL, 'Housing', '2026-02-25 07:41:53', 'admin'),
(2, NULL, 'Transportation', '2026-02-25 07:41:53', 'admin'),
(3, NULL, 'Food', '2026-02-25 07:41:53', 'admin'),
(4, NULL, 'Utilities', '2026-02-25 07:41:53', 'admin'),
(5, NULL, 'Clothing', '2026-02-25 07:41:53', 'admin'),
(6, NULL, 'Medical/Healthcare', '2026-02-25 07:41:53', 'admin'),
(7, NULL, 'Insurance', '2026-02-25 07:41:53', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
CREATE TABLE IF NOT EXISTS `expenses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `expense_date` date DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `user_id`, `category_id`, `amount`, `expense_date`, `description`, `created_at`) VALUES
(1, 1, 3, 500.00, '2026-02-25', 'mcdonalds', '2026-02-25 07:44:02'),
(3, 1, 2, 300.00, '2026-02-26', 'Mall to Home Cab', '2026-02-26 08:10:59'),
(4, 1, 5, 1500.00, '2026-02-26', 'Zudio', '2026-02-28 04:40:08'),
(5, 1, 6, 900.00, '2026-02-25', 'LIC premium', '2026-02-28 04:40:41'),
(6, 1, 1, 8000.00, '2025-11-03', 'House Rent', '2026-02-28 04:51:42'),
(7, 1, 3, 3000.00, '2025-11-10', 'Groceries', '2026-02-28 04:51:42'),
(8, 1, 2, 1500.00, '2025-11-15', 'Fuel', '2026-02-28 04:51:42'),
(9, 1, 4, 2000.00, '2025-11-22', 'Electricity Bill', '2026-02-28 04:51:42'),
(10, 1, 1, 8000.00, '2025-12-03', 'House Rent', '2026-02-28 04:51:42'),
(11, 1, 3, 3500.00, '2025-12-12', 'Groceries', '2026-02-28 04:51:42'),
(12, 1, 5, 2500.00, '2025-12-18', 'Winter Shopping', '2026-02-28 04:51:42'),
(13, 1, 6, 1200.00, '2025-12-28', 'Doctor Visit', '2026-02-28 04:51:42'),
(14, 1, 1, 8500.00, '2026-01-03', 'House Rent', '2026-02-28 04:51:42'),
(15, 1, 3, 3200.00, '2026-01-10', 'Groceries', '2026-02-28 04:51:42'),
(16, 1, 2, 1800.00, '2026-01-14', 'Fuel', '2026-02-28 04:51:42'),
(17, 1, 7, 2200.00, '2026-01-20', 'Insurance Payment', '2026-02-28 04:51:42'),
(18, 1, 1, 8500.00, '2026-02-03', 'House Rent', '2026-02-28 04:51:42'),
(19, 1, 3, 3000.00, '2026-02-09', 'Groceries', '2026-02-28 04:51:42'),
(20, 1, 2, 1700.00, '2026-02-14', 'Fuel', '2026-02-28 04:51:42'),
(21, 1, 4, 2100.00, '2026-02-20', 'Electricity Bill', '2026-02-28 04:51:42'),
(22, 1, 1, 10000.00, '2026-03-06', 'Rent', '2026-03-06 04:58:12'),
(23, 1, 1, 10000.00, '2026-03-07', 'Rent', '2026-03-07 04:58:21'),
(24, 1, 3, 1000.00, '2026-03-06', '', '2026-03-07 05:13:38');

-- --------------------------------------------------------

--
-- Table structure for table `income`
--

DROP TABLE IF EXISTS `income`;
CREATE TABLE IF NOT EXISTS `income` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `source` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `income_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `income`
--

INSERT INTO `income` (`id`, `user_id`, `source`, `amount`, `income_date`, `created_at`) VALUES
(1, 1, 'UPI', 100.00, '2026-02-26', '2026-02-26 05:28:10'),
(13, 1, 'Salary', 22000.00, '2026-03-05', '2026-03-06 04:54:13'),
(5, 1, 'Salary', 25000.00, '2025-11-05', '2026-02-28 04:50:37'),
(6, 1, 'Freelance', 8000.00, '2025-11-20', '2026-02-28 04:50:37'),
(7, 1, 'Salary', 25000.00, '2025-12-05', '2026-02-28 04:50:37'),
(8, 1, 'Bonus', 10000.00, '2025-12-25', '2026-02-28 04:50:37'),
(9, 1, 'Salary', 26000.00, '2026-01-05', '2026-02-28 04:50:37'),
(10, 1, 'Freelance', 6000.00, '2026-01-18', '2026-02-28 04:50:37'),
(11, 1, 'Salary', 26000.00, '2026-02-05', '2026-02-28 04:50:37'),
(12, 1, 'Investment Return', 4000.00, '2026-02-15', '2026-02-28 04:50:37');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `admin_reply` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','replied') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `replied_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `subject`, `message`, `admin_reply`, `status`, `created_at`, `replied_at`) VALUES
(1, 1, 'Recurring issue ', 'recurring transactions are not working automatically\r\n', 'we\'ll work soon ', 'replied', '2026-03-06 07:18:26', '2026-03-06 07:19:06');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `type`, `is_read`, `created_at`) VALUES
(1, 1, 'Admin replied to your support message', NULL, 1, '2026-03-06 07:19:06'),
(2, 1, 'You have used 90% of your monthly budget.', 'budget', 1, '2026-03-07 04:58:21');

-- --------------------------------------------------------

--
-- Table structure for table `recurring_transactions`
--

DROP TABLE IF EXISTS `recurring_transactions`;
CREATE TABLE IF NOT EXISTS `recurring_transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `type` enum('income','expense') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `category_id` int DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `frequency` enum('monthly','weekly','yearly') COLLATE utf8mb4_unicode_ci NOT NULL,
  `next_run` date NOT NULL,
  `status` enum('active','paused') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `goal_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `recurring_transactions`
--

INSERT INTO `recurring_transactions` (`id`, `user_id`, `type`, `amount`, `category_id`, `description`, `frequency`, `next_run`, `status`, `created_at`, `goal_id`) VALUES
(1, 1, 'income', 22000.00, 0, 'salary', 'monthly', '2026-04-05', 'active', '2026-03-03 05:44:07', NULL),
(2, 1, 'expense', 10000.00, 1, 'Rent', 'monthly', '2026-04-07', 'active', '2026-03-06 07:48:22', 0);

-- --------------------------------------------------------

--
-- Table structure for table `savings`
--

DROP TABLE IF EXISTS `savings`;
CREATE TABLE IF NOT EXISTS `savings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `saved_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `savings_goals`
--

DROP TABLE IF EXISTS `savings_goals`;
CREATE TABLE IF NOT EXISTS `savings_goals` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `goal_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_amount` decimal(10,2) NOT NULL,
  `saved_amount` decimal(10,2) DEFAULT '0.00',
  `target_date` date DEFAULT NULL,
  `status` enum('active','completed') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `savings_goals`
--

INSERT INTO `savings_goals` (`id`, `user_id`, `goal_name`, `target_amount`, `saved_amount`, `target_date`, `status`, `created_at`) VALUES
(1, 1, 'Buy Laptop', 50000.00, 5000.00, '2026-04-10', 'active', '2026-03-03 06:10:14'),
(2, 1, 'Get Gold Ring', 45000.00, 46000.00, '2026-03-10', 'completed', '2026-03-05 16:25:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','blocked') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `birthdate`, `email`, `mobile`, `password`, `created_at`, `status`) VALUES
(1, 'Riya Mistry', '2005-12-27', 'riya@test.com', '7567646719', '$2y$10$OATkrqC0f2o.FgFMvbLQ4OS8beeS7UCMBXHP.oMGkMJLhWOirMDdq', '2026-02-26 04:44:20', 'active');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
