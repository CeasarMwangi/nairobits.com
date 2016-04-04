-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2016 at 04:57 PM
-- Server version: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `nairobits`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE IF NOT EXISTS `assets` (
  `asset_id` int(11) NOT NULL,
  `nairobitsbarcode` varchar(32) NOT NULL,
  `serialnumber` varchar(32) NOT NULL,
  `category_id` int(11) NOT NULL,
  `currentcenter_id` int(11) NOT NULL,
  `specifications` text NOT NULL,
  `status` enum('active','deleted') NOT NULL DEFAULT 'active',
  `created_timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_timestamp` datetime DEFAULT NULL,
  `additional_information` text,
  `condition` enum('good','faulty','disposed','transfered') NOT NULL DEFAULT 'good'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`asset_id`, `nairobitsbarcode`, `serialnumber`, `category_id`, `currentcenter_id`, `specifications`, `status`, `created_timestamp`, `updated_timestamp`, `additional_information`, `condition`) VALUES
(2, '222222', '111111', 1, 1, '2 gb ram', 'active', '2016-04-02 05:42:26', NULL, NULL, 'good');

-- --------------------------------------------------------

--
-- Table structure for table `centers`
--

CREATE TABLE IF NOT EXISTS `centers` (
  `center_id` int(11) NOT NULL,
  `center_name` varchar(32) NOT NULL,
  `location` varchar(32) NOT NULL,
  `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_datetime` datetime DEFAULT NULL,
  `capacity` int(11) NOT NULL,
  `center_status` enum('active','deleted') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `centers`
--

INSERT INTO `centers` (`center_id`, `center_name`, `location`, `created_datetime`, `updated_datetime`, `capacity`, `center_status`) VALUES
(1, 'CFK', 'kibra', '2016-04-01 14:54:48', '2016-04-01 16:32:13', 50, 'active'),
(2, 'Mukuru Ruben Center', 'Mukuru', '2016-04-01 14:54:48', '2016-04-01 15:10:40', 15, 'active'),
(3, 'Main Center', 'South B', '2016-04-01 14:23:11', NULL, 50, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `itemcategorys`
--

CREATE TABLE IF NOT EXISTS `itemcategorys` (
  `itemcategory_id` int(11) NOT NULL,
  `category_name` varchar(32) NOT NULL,
  `description` text NOT NULL,
  `type` enum('hardware','software') NOT NULL DEFAULT 'hardware',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `itemcategorys`
--

INSERT INTO `itemcategorys` (`itemcategory_id`, `category_name`, `description`, `type`, `created_at`, `updated_at`) VALUES
(2, 'keyboard', 'computer keyboard', 'hardware', '2016-04-01 16:11:39', NULL),
(4, 'fun', 'cooler fun', 'hardware', '2016-04-01 15:47:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(64) NOT NULL,
  `password` varchar(32) NOT NULL,
  `username` varchar(32) NOT NULL,
  `user_category` enum('admin','others') NOT NULL DEFAULT 'others',
  `user_status` enum('active','deleted') NOT NULL DEFAULT 'active',
  `phone_number` varchar(32) NOT NULL,
  `created_timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_timestamp` datetime DEFAULT NULL,
  `email_address` varchar(64) NOT NULL,
  `user_thumbnail` varchar(64) NOT NULL,
  `current_center_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `password`, `username`, `user_category`, `user_status`, `phone_number`, `created_timestamp`, `updated_timestamp`, `email_address`, `user_thumbnail`, `current_center_id`) VALUES
(1, 'Mwangi Kanja', '123456', 'kimani', 'others', 'active', '0728268432', '2016-03-28 00:00:00', '2016-04-01 16:26:47', 'ceasar@nairobits.com', '/nairobits.com/img/users/user1.jpg', 1),
(2, 'davie', '12345678', 'kanja', 'others', 'active', '1234567890', '0000-00-00 00:00:00', '2016-04-01 13:22:41', 'kanja@gmail.com', '/nairobits.com/img/users/user2.jpg', 2),
(4, 'emily', '12345678', 'kanjam', 'admin', 'active', '1234567890', '0000-00-00 00:00:00', NULL, 'kanja1@gmail.com', '/nairobits.com/img/users/user3.jpg', 2),
(5, 'chris', '12345678', 'kanjamk', 'admin', 'active', '1234567890', '0000-00-00 00:00:00', NULL, 'kanja61@gmail.com', '/nairobits.com/img/users/user4.jpg', 1),
(6, 'eli', '12345678', 'kanjamkt', 'admin', 'active', '1234567890', '0000-00-00 00:00:00', NULL, 'kanja61t@gmail.com', '/nairobits.com/img/users/user5.jpg', 3),
(7, 'jane', '12345678', 'kanjamktest', 'admin', 'active', '1234567890', '2016-03-30 12:24:55', NULL, 'kanja61test@gmail.com', '/nairobits.com/img/users/user6.jpg', 3),
(8, 'irungu', '12345678', 'kanjamktestt', 'admin', 'active', '1234567890', '2016-03-30 12:25:27', NULL, 'kanja61testt@gmail.com', '/nairobits.com/img/users/user7.jpg', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`asset_id`), ADD UNIQUE KEY `serialnumber` (`serialnumber`), ADD UNIQUE KEY `nairobitsbarcode` (`nairobitsbarcode`);

--
-- Indexes for table `centers`
--
ALTER TABLE `centers`
  ADD PRIMARY KEY (`center_id`), ADD UNIQUE KEY `name` (`center_name`);

--
-- Indexes for table `itemcategorys`
--
ALTER TABLE `itemcategorys`
  ADD PRIMARY KEY (`itemcategory_id`), ADD UNIQUE KEY `category_name` (`category_name`), ADD UNIQUE KEY `category_name_2` (`category_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`), ADD UNIQUE KEY `email_address` (`email_address`), ADD UNIQUE KEY `username` (`username`), ADD UNIQUE KEY `user_thumbnail` (`user_thumbnail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `centers`
--
ALTER TABLE `centers`
  MODIFY `center_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `itemcategorys`
--
ALTER TABLE `itemcategorys`
  MODIFY `itemcategory_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
