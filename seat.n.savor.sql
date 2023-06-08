-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2023 at 06:06 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `seat.n.savor`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` tinyint(5) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `avatar`, `name`, `email`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', 'avatar.png', 'ADMIN', 'admin@email.com', '09123456789', '2023-06-07 07:13:52', '2023-06-07 11:23:50');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(255) UNSIGNED NOT NULL,
  `customer_id` int(255) UNSIGNED NOT NULL,
  `restaurant_id` int(255) UNSIGNED NOT NULL,
  `table_id` int(255) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `party_size` smallint(100) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL,
  `cancellation_reason` varchar(255) DEFAULT NULL,
  `is_shown` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(255) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `username`, `password`, `avatar`, `name`, `email`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'customer01', 'customer01', 'avatar.png', 'CUSTOMER 01', 'customer1@email.com', '09133678462', 'Iriga City, Camarines Sur', '2023-06-07 07:15:39', '2023-06-07 11:20:23'),
(2, 'customer02', 'customer02', 'avatar.png', 'CUSTOMER 02', 'customer2@email.com', '09133648462', 'Nabua, Camarines Sur', '2023-06-07 07:17:28', '2023-06-07 11:20:26'),
(3, 'customer03', 'customer03', 'avatar03.jpg', 'CUSTOMER 03', 'customer3@email.com', '09133648463', 'Sta. Cruz, Iriga City, Camarines Sur', '2023-06-07 07:17:28', '2023-06-07 11:20:31');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(255) UNSIGNED NOT NULL,
  `recipient_id` int(255) UNSIGNED NOT NULL,
  `sender_id` int(255) UNSIGNED NOT NULL,
  `message` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `id` int(255) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`id`, `name`, `address`, `image`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'Chowking - Iriga', 'New, Public Market, City of Iriga, 4431 Camarines Sur', 'chowking.jpg', '0917 628 2476', '2023-06-07 07:20:20', '2023-06-08 08:18:59'),
(2, 'Desny Grill Bar Resto & Cafe', 'Lourdes Hospital Rd, City of Iriga, 4431 Camarines Sur', 'desny.jpg', '(054) 456 1512', '2023-06-07 18:22:37', '2023-06-08 08:19:05'),
(3, 'Yhanyhan Frappe Restaurant', 'Buhi, Camarines Sur', 'yhanyhan.jpg', '0917 555 4867', '2023-06-07 18:22:37', '2023-06-08 08:19:30'),
(4, 'Pomodoro - Iriga', 'Nabua - Iriga Rd, City of Iriga, Camarines Sur', 'pomodoro.jpg', '09214452234', '2023-06-07 18:25:06', '2023-06-08 08:21:58'),
(5, 'Above Sea Level - Iriga', 'City of Iriga, 4431 Camarines Sur', 'above-sea-level.jpg', '0917 107 3738', '2023-06-07 18:25:58', '2023-06-08 08:35:03'),
(6, 'Bigg\'s Diner - Iriga', 'Highway 1, Trinidad Bldg, San Roque, City of Iriga, 4431 Camarines Sur', 'biggs.jpg', '(054) 299 1111', '2023-06-07 18:27:20', '2023-06-08 08:35:08'),
(7, 'MCM Restaurant', 'Ortega Street, City of Iriga, Camarines Sur', 'mcm.jpg', '(054) 299 2303', '2023-06-07 18:30:15', '2023-06-08 08:35:16'),
(8, 'Highway Grill', 'Zone 7 Nabua - Iriga Rd, Nabua, 4434 Camarines Sur', 'highway-grill.jpg', '(054) 871 5811', '2023-06-07 18:30:15', '2023-06-08 08:41:38'),
(9, 'Chef Romeo\'s Kitchen', 'Iriga - Baao Rd, Baao, Camarines Sur', 'chef-romeos.jpg', '0915 200 1161', '2023-06-07 18:35:59', '2023-06-08 08:41:46'),
(10, 'Graziela\'s Resto & Events Hall', 'Baao, Camarines Sur', 'graziela\'s.jpg', '0927 464 6206', '2023-06-07 18:37:01', '2023-06-08 08:44:49');

-- --------------------------------------------------------

--
-- Table structure for table `restaurateurs`
--

CREATE TABLE `restaurateurs` (
  `id` int(255) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `restaurant_id` int(255) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restaurateurs`
--

INSERT INTO `restaurateurs` (`id`, `username`, `password`, `avatar`, `name`, `email`, `phone`, `address`, `restaurant_id`, `created_at`, `updated_at`) VALUES
(1, 'chowkingAdmin', 'chowkingAdmin', 'avatar.png', 'ROBERT KUAN', 'robertkuan@chowking.com', '092345678765', 'Makati, Philippines', 1, '2023-06-07 10:22:41', '2023-06-07 11:24:01');

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id` int(255) UNSIGNED NOT NULL,
  `number` int(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `capacity` int(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`id`, `number`, `image`, `capacity`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'table01.jpg', 5, 'This table can seat 5 people/s.', '2023-06-07 10:25:02', '2023-06-08 08:06:02'),
(2, 2, 'table02.jpg', 6, 'This table can seat 6 people/s.', '2023-06-08 08:02:12', '2023-06-08 08:06:14'),
(3, 3, 'table03.jpg', 7, 'This table can seat 7 people/s.', '2023-06-08 08:02:12', '2023-06-08 08:06:20'),
(4, 4, 'table04.jpg', 8, 'This table can seat 8 people/s.', '2023-06-08 08:04:09', '2023-06-08 08:06:27'),
(5, 5, 'table05.jpg', 1, 'This table can seat 1 people/s.', '2023-06-08 08:04:09', '2023-06-08 08:06:32'),
(6, 6, 'table06.jpg', 9, 'This table can seat 9 people/s.', '2023-06-08 08:13:00', '2023-06-08 08:13:00'),
(7, 7, 'table07.jpg', 2, 'This table can seat 2 people/s.', '2023-06-08 08:13:38', '2023-06-08 08:13:38'),
(8, 8, 'table08.jpg', 4, 'This table can seat 4 people/s.', '2023-06-08 08:14:27', '2023-06-08 08:14:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `table_id` (`table_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipient_id` (`recipient_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restaurateurs`
--
ALTER TABLE `restaurateurs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` tinyint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(255) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(255) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(255) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `id` int(255) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `restaurateurs`
--
ALTER TABLE `restaurateurs`
  MODIFY `id` int(255) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id` int(255) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`),
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`recipient_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `restaurateurs` (`id`);

--
-- Constraints for table `restaurateurs`
--
ALTER TABLE `restaurateurs`
  ADD CONSTRAINT `restaurateurs_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
