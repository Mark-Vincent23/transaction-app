-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 15, 2025 at 05:22 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `transaction_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `status_types`
--

CREATE TABLE `status_types` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status_types`
--

INSERT INTO `status_types` (`id`, `name`) VALUES
(0, 'SUCCESS'),
(1, 'FAILED');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `productID` varchar(10) NOT NULL,
  `productName` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `customerName` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `transactionDate` datetime NOT NULL,
  `createBy` varchar(100) NOT NULL,
  `createOn` datetime NOT NULL,
  `modifiedBy` varchar(50) DEFAULT NULL,
  `modifiedDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `productID`, `productName`, `amount`, `customerName`, `status`, `transactionDate`, `createBy`, `createOn`, `modifiedBy`, `modifiedDate`) VALUES
(2, '2323', 'asas', 3242.00, 'Mark Vincent', 0, '2025-01-15 02:25:27', 'system', '2025-01-15 02:20:54', NULL, '2025-01-15 04:00:00'),
(1372, '10001', 'Test 1', 1000.00, 'abc', 0, '2022-07-10 11:14:52', 'abc', '2022-07-10 11:14:52', NULL, '2025-01-15 04:00:00'),
(1373, '10002', 'Test 2', 2000.00, 'abc', 0, '2022-07-11 13:14:52', 'abc', '2022-07-10 13:14:52', NULL, '2025-01-15 04:00:00'),
(1374, '10001', 'Test 1', 1000.00, 'abc', 0, '2022-08-10 12:14:52', 'abc', '2022-07-10 12:14:52', NULL, '2025-01-15 04:00:00'),
(1375, '10002', 'Test 2', 1000.00, 'abc', 1, '2022-08-10 13:10:52', 'abc', '2022-07-10 13:10:52', NULL, '2025-01-15 04:00:00'),
(1376, '10001', 'Test 1', 1000.00, 'abc', 0, '2022-08-10 13:11:52', 'abc', '2022-07-10 13:11:52', NULL, '2025-01-15 04:00:00'),
(1377, '10002', 'Test 2', 2000.00, 'abc', 0, '2022-08-12 13:14:52', 'abc', '2022-07-10 13:14:52', NULL, '2025-01-15 04:00:00'),
(1378, '10001', 'Test 1', 1000.00, 'abc', 0, '2022-08-12 14:11:52', 'abc', '2022-07-10 14:11:52', NULL, '2025-01-15 04:00:00'),
(1379, '10002', 'Test 2', 1000.00, 'abc', 1, '2022-09-13 11:14:52', 'abc', '2022-07-10 11:14:52', NULL, '2025-01-15 04:00:00'),
(1380, '10001', 'Test 1', 1000.00, 'abc', 0, '2022-09-13 13:14:52', 'abc', '2022-07-10 13:14:52', NULL, '2025-01-15 04:00:00'),
(1381, '10002', 'Test 2', 2000.00, 'abc', 0, '2022-09-14 09:11:52', 'abc', '2022-07-10 09:11:52', NULL, '2025-01-15 04:00:00'),
(1382, '10001', 'Test 1', 1000.00, 'abc', 0, '2022-09-14 10:14:52', 'abc', '2022-07-10 10:14:52', NULL, '2025-01-15 04:00:00'),
(1383, '10002', 'Test 2', 1000.00, 'abc', 1, '2022-08-15 13:14:52', 'abc', '2022-07-10 13:14:52', NULL, '2025-01-15 04:00:00'),
(1384, '90', 'add test', 3242.00, 'test add', 0, '2025-01-15 10:22:00', 'system', '0000-00-00 00:00:00', NULL, '2025-01-15 04:00:00'),
(1386, '55', '55', 55.00, '55', 1, '2025-01-14 20:55:00', 'system', '0000-00-00 00:00:00', 'system', '2025-01-15 04:00:25'),
(1387, '777', '777', 777.00, '777', 0, '2025-01-15 15:00:00', 'system', '0000-00-00 00:00:00', NULL, '2025-01-15 04:00:40'),
(1388, '888', 'testtt 88', 889.00, 'cust 888', 0, '2024-02-15 04:08:00', 'system', '0000-00-00 00:00:00', 'system', '2025-01-15 04:08:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `status_types`
--
ALTER TABLE `status_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1389;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
