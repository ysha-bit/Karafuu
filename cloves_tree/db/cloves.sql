-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 12, 2024 at 09:03 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cloves`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `status_of_trees` varchar(255) DEFAULT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `description`, `status_of_trees`, `date`) VALUES
(7, 'sidusygskjsuiscsnsnvs kjvbni kjvisuj kvbsuivkjvnkjvsvksnskvbs', 'healthy', '0000-00-00'),
(8, 'Cloves trees ware found normal.', 'normal', '2024-09-08'),
(9, 'Asipewe tena ', 'normal', '2024-09-10');

-- --------------------------------------------------------

--
-- Table structure for table `farmers`
--

CREATE TABLE `farmers` (
  `farmer_id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `zanzibar_id` varchar(50) DEFAULT NULL,
  `amount_of_miche` int(10) DEFAULT NULL,
  `shehia` varchar(50) DEFAULT NULL,
  `wilaya` varchar(50) DEFAULT NULL,
  `date` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmers`
--

INSERT INTO `farmers` (`farmer_id`, `firstname`, `lastname`, `phone`, `zanzibar_id`, `amount_of_miche`, `shehia`, `wilaya`, `date`) VALUES
(1, 'Thani', 'Thani', '062356544', '0988882', 300, 'MAZIZINI', 'MAGHARIBI B', '2023'),
(22, 'Khalid', 'Sheha', '0625879175', '999028484', 500, 'MAHONDA', 'KASKAZINI A', '2024'),
(23, 'Thani', 'Thani', '062356544', '0988882', 200, 'MAZIZINI', 'MAGHARIBI B', '2024');

-- --------------------------------------------------------

--
-- Table structure for table `historylog`
--

CREATE TABLE `historylog` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'itstaff'),
(2, 'farmer_officer'),
(3, 'admin'),
(4, 'head_of_farmers_officers');

-- --------------------------------------------------------

--
-- Table structure for table `treeprogress`
--

CREATE TABLE `treeprogress` (
  `progress_id` int(11) NOT NULL,
  `wilaya` varchar(20) DEFAULT NULL,
  `shehia` varchar(20) NOT NULL,
  `farmer` varchar(50) DEFAULT NULL,
  `number_tree` int(25) DEFAULT NULL,
  `growth_stage` varchar(50) DEFAULT NULL,
  `progress_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `treeprogress`
--

INSERT INTO `treeprogress` (`progress_id`, `wilaya`, `shehia`, `farmer`, `number_tree`, `growth_stage`, `progress_date`) VALUES
(1, 'MAGHARIBI B', 'MAZIZINI', 'thani thani', 250, '4', '2023-09-06 02:05:22'),
(6, 'KISAUNI', 'MAGHARIBI B', 'Talhata Mouse', 200, '3', '2024-08-26 09:56:51'),
(7, 'MAHONDA', 'KASKAZINI A', 'Khalid Sheha', 400, '1', '2024-09-10 12:59:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `zanzibar_id` varchar(50) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `firstname`, `lastname`, `email`, `password`, `phone`, `zanzibar_id`, `role_id`) VALUES
(12, 'Fatma', 'Hamad', 'fatma@gmai.com', '$2y$10$iQgXyY6gXoNxKXpK1hQv2uaIo69YNR.l.kR3BsfH4tb.CAxDkiEm.', '0622123750', '9995510', 1),
(13, 'Fahad', 'Amour', 'fahad@gmail.com', '$2y$10$FtHUSTQJT.nPa6kIVr3lMu2QFrVxr/k2W4ocT9llGywHq7pA0czn6', '0625851607', '2231077', 2),
(14, 'Aisha', 'Ali', 'aisha@gmail.com', '$2y$10$WLq35AtiZZDAruFzSKyIs.Yk83CeXF9aDStItUjkO2XYBlxrTJvpK', '625879123', '5567234', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `farmers`
--
ALTER TABLE `farmers`
  ADD PRIMARY KEY (`farmer_id`);

--
-- Indexes for table `historylog`
--
ALTER TABLE `historylog`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `treeprogress`
--
ALTER TABLE `treeprogress`
  ADD PRIMARY KEY (`progress_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `farmers`
--
ALTER TABLE `farmers`
  MODIFY `farmer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `historylog`
--
ALTER TABLE `historylog`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `treeprogress`
--
ALTER TABLE `treeprogress`
  MODIFY `progress_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `historylog`
--
ALTER TABLE `historylog`
  ADD CONSTRAINT `historylog_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
