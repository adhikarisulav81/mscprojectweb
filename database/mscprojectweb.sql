-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2026 at 07:00 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mscprojectweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminlogin_tb`
--

CREATE TABLE `adminlogin_tb` (
  `id` int(11) NOT NULL,
  `name` varchar(60) COLLATE utf8_bin NOT NULL,
  `email` varchar(60) COLLATE utf8_bin NOT NULL,
  `password` varchar(32) COLLATE utf8_bin NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `adminlogin_tb`
--

INSERT INTO `adminlogin_tb` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Admin', 'admin@gmail.com', 'e64b78fc3bc91bcbc7dc232ba8ec59e0', '2021-05-10 03:23:17');

-- --------------------------------------------------------

--
-- Table structure for table `assignwork_tb`
--

CREATE TABLE `assignwork_tb` (
  `rno` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `request_info` text COLLATE utf8_bin NOT NULL,
  `request_desc` text COLLATE utf8_bin NOT NULL,
  `requester_name` varchar(60) COLLATE utf8_bin NOT NULL,
  `requester_add1` text COLLATE utf8_bin NOT NULL,
  `requester_add2` text COLLATE utf8_bin NOT NULL,
  `requester_city` varchar(60) COLLATE utf8_bin NOT NULL,
  `requester_state` varchar(60) COLLATE utf8_bin NOT NULL,
  `requester_zip` int(11) NOT NULL,
  `requester_email` varchar(60) COLLATE utf8_bin NOT NULL,
  `requester_mobile` bigint(11) NOT NULL,
  `assign_tech` varchar(60) COLLATE utf8_bin NOT NULL,
  `assign_date` date NOT NULL,
  `status` varchar(100) COLLATE utf8_bin NOT NULL,
  `deliveryDate` date DEFAULT NULL,
  `image` longblob DEFAULT NULL,
  `priority` varchar(100) COLLATE utf8_bin DEFAULT 'Normal',
  `rating` int(11) DEFAULT 0,
  `service_price` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `categories_tb`
--

CREATE TABLE `categories_tb` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) COLLATE utf8_bin NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `categories_tb`
--

INSERT INTO `categories_tb` (`category_id`, `category_name`, `created_at`) VALUES
(1, 'Mobile Phones', '2026-03-09 17:41:26'),
(2, 'Tablets', '2026-03-09 17:41:26'),
(3, 'Laptops', '2026-03-09 17:41:26');

-- --------------------------------------------------------

--
-- Table structure for table `models_tb`
--

CREATE TABLE `models_tb` (
  `model_id` int(11) NOT NULL,
  `sub_id` int(11) NOT NULL,
  `model_name` varchar(100) COLLATE utf8_bin NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `models_tb`
--

INSERT INTO `models_tb` (`model_id`, `sub_id`, `model_name`, `created_at`) VALUES
(1, 1, 'Galaxy S21', '2026-03-09 17:41:26'),
(2, 1, 'Galaxy A52', '2026-03-09 17:41:26'),
(3, 2, 'iPhone 13', '2026-03-09 17:41:26'),
(4, 2, 'iPhone 12', '2026-03-09 17:41:26');

-- --------------------------------------------------------

--
-- Table structure for table `otp_verification_tb`
--

CREATE TABLE `otp_verification_tb` (
  `id` int(11) NOT NULL,
  `email` varchar(60) COLLATE utf8_bin NOT NULL,
  `name` varchar(60) COLLATE utf8_bin NOT NULL,
  `password_hash` varchar(32) COLLATE utf8_bin NOT NULL,
  `otp` varchar(6) COLLATE utf8_bin NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tb`
--

CREATE TABLE `password_reset_tb` (
  `id` int(11) NOT NULL,
  `email` varchar(60) COLLATE utf8_bin NOT NULL,
  `token` varchar(64) COLLATE utf8_bin NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `services_tb`
--

CREATE TABLE `services_tb` (
  `service_id` int(11) NOT NULL,
  `model_id` int(11) NOT NULL,
  `service_name` varchar(150) COLLATE utf8_bin NOT NULL,
  `service_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `services_tb`
--

INSERT INTO `services_tb` (`service_id`, `model_id`, `service_name`, `service_price`, `created_at`) VALUES
(1, 1, 'Screen Replacement', '15000.00', '2026-03-09 17:41:26'),
(2, 1, 'Battery Replacement', '5000.00', '2026-03-09 17:41:26'),
(3, 2, 'Screen Replacement', '12000.00', '2026-03-09 17:41:26'),
(4, 3, 'Screen Replacement', '25000.00', '2026-03-09 17:41:26');

-- --------------------------------------------------------

--
-- Table structure for table `subcategories_tb`
--

CREATE TABLE `subcategories_tb` (
  `sub_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `sub_name` varchar(100) COLLATE utf8_bin NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `subcategories_tb`
--

INSERT INTO `subcategories_tb` (`sub_id`, `category_id`, `sub_name`, `created_at`) VALUES
(1, 1, 'Samsung', '2026-03-09 17:41:26'),
(2, 1, 'Apple', '2026-03-09 17:41:26'),
(3, 1, 'Xiaomi', '2026-03-09 17:41:26'),
(4, 2, 'iPad', '2026-03-09 17:41:26'),
(5, 3, 'Dell', '2026-03-09 17:41:26');

-- --------------------------------------------------------

--
-- Table structure for table `submitrequest_tb`
--

CREATE TABLE `submitrequest_tb` (
  `request_id` int(11) NOT NULL,
  `request_info` text COLLATE utf8_bin NOT NULL,
  `request_desc` text COLLATE utf8_bin NOT NULL,
  `requester_name` varchar(60) COLLATE utf8_bin NOT NULL,
  `requester_add1` text COLLATE utf8_bin NOT NULL,
  `requester_add2` text COLLATE utf8_bin NOT NULL,
  `requester_city` varchar(60) COLLATE utf8_bin NOT NULL,
  `requester_state` varchar(60) COLLATE utf8_bin NOT NULL,
  `requester_zip` int(11) NOT NULL,
  `requester_email` varchar(60) COLLATE utf8_bin NOT NULL,
  `requester_mobile` bigint(11) NOT NULL,
  `request_date` date NOT NULL,
  `image` longblob DEFAULT NULL,
  `priority` varchar(100) COLLATE utf8_bin DEFAULT 'Normal',
  `service_price` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `technician_tb`
--

CREATE TABLE `technician_tb` (
  `empid` int(11) NOT NULL,
  `empName` varchar(60) COLLATE utf8_bin NOT NULL,
  `empCity` varchar(60) COLLATE utf8_bin NOT NULL,
  `empMobile` bigint(11) NOT NULL,
  `empEmail` varchar(60) COLLATE utf8_bin NOT NULL,
  `password` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `empRating` decimal(3,2) DEFAULT 0.00,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `technician_tb`
--

INSERT INTO `technician_tb` (`empid`, `empName`, `empCity`, `empMobile`, `empEmail`, `password`, `created_at`, `empRating`, `is_active`) VALUES
(1, 'Tech1', 'Kathmandu', 9800000001, 'tech1@gmail.com', 'e64b78fc3bc91bcbc7dc232ba8ec59e0', '2026-03-09 17:41:26', '4.50', 1),
(2, 'Sulav Tech', 'Kathmandu', 9800000002, 'sulavtech@gmail.com', 'e64b78fc3bc91bcbc7dc232ba8ec59e0', '2026-03-09 17:41:26', '5.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `userlogin_tb`
--

CREATE TABLE `userlogin_tb` (
  `id` int(11) NOT NULL,
  `name` varchar(60) COLLATE utf8_bin NOT NULL,
  `email` varchar(60) COLLATE utf8_bin NOT NULL,
  `password` varchar(32) COLLATE utf8_bin NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `userlogin_tb`
--

INSERT INTO `userlogin_tb` (`id`, `name`, `email`, `password`, `created_at`, `is_active`) VALUES
(1, 'User', 'user@gmail.com', '6edf26f6e0badff12fca32b16db38bf2', '2021-04-18 02:50:42', 1),
(2, 'Sulav Adhikari', 'adhikarisulav1@gmail.com', '91edb1ece3be96e037cdb5e6b2a93b48', '2021-04-21 10:33:41', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminlogin_tb`
--
ALTER TABLE `adminlogin_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assignwork_tb`
--
ALTER TABLE `assignwork_tb`
  ADD PRIMARY KEY (`rno`);

--
-- Indexes for table `categories_tb`
--
ALTER TABLE `categories_tb`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `models_tb`
--
ALTER TABLE `models_tb`
  ADD PRIMARY KEY (`model_id`),
  ADD KEY `sub_id` (`sub_id`);

--
-- Indexes for table `otp_verification_tb`
--
ALTER TABLE `otp_verification_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tb`
--
ALTER TABLE `password_reset_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services_tb`
--
ALTER TABLE `services_tb`
  ADD PRIMARY KEY (`service_id`),
  ADD KEY `model_id` (`model_id`);

--
-- Indexes for table `subcategories_tb`
--
ALTER TABLE `subcategories_tb`
  ADD PRIMARY KEY (`sub_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `submitrequest_tb`
--
ALTER TABLE `submitrequest_tb`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `technician_tb`
--
ALTER TABLE `technician_tb`
  ADD PRIMARY KEY (`empid`);

--
-- Indexes for table `userlogin_tb`
--
ALTER TABLE `userlogin_tb`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminlogin_tb`
--
ALTER TABLE `adminlogin_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `assignwork_tb`
--
ALTER TABLE `assignwork_tb`
  MODIFY `rno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories_tb`
--
ALTER TABLE `categories_tb`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `models_tb`
--
ALTER TABLE `models_tb`
  MODIFY `model_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `otp_verification_tb`
--
ALTER TABLE `otp_verification_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_reset_tb`
--
ALTER TABLE `password_reset_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services_tb`
--
ALTER TABLE `services_tb`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `subcategories_tb`
--
ALTER TABLE `subcategories_tb`
  MODIFY `sub_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `submitrequest_tb`
--
ALTER TABLE `submitrequest_tb`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `technician_tb`
--
ALTER TABLE `technician_tb`
  MODIFY `empid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `userlogin_tb`
--
ALTER TABLE `userlogin_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `models_tb`
--
ALTER TABLE `models_tb`
  ADD CONSTRAINT `fk_model_subcategory` FOREIGN KEY (`sub_id`) REFERENCES `subcategories_tb` (`sub_id`);

--
-- Constraints for table `services_tb`
--
ALTER TABLE `services_tb`
  ADD CONSTRAINT `fk_service_model` FOREIGN KEY (`model_id`) REFERENCES `models_tb` (`model_id`);

--
-- Constraints for table `subcategories_tb`
--
ALTER TABLE `subcategories_tb`
  ADD CONSTRAINT `fk_subcategory_category` FOREIGN KEY (`category_id`) REFERENCES `categories_tb` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
