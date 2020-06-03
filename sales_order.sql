-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 03, 2020 at 02:57 PM
-- Server version: 5.7.29-0ubuntu0.18.04.1
-- PHP Version: 7.2.24-0ubuntu0.18.04.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `oc_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `sales_order`
--

CREATE TABLE `sales_order` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `contact_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `agree_term_and_condition` int(11) NOT NULL,
  `send_to_oc` int(11) NOT NULL,
  `number_of_job` int(11) NOT NULL,
  `mobile` int(11) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sales_order`
--

INSERT INTO `sales_order` (`id`, `sender_id`, `company_id`, `contact_name`, `email`, `address`, `agree_term_and_condition`, `send_to_oc`, `number_of_job`, `mobile`, `created`) VALUES
(1, 0, 1, 'Jayraj singh', 'Testing', 'Vijay nagar', 0, 0, 0, 0, '2020-06-01 00:00:50'),
(2, 1, 1, 'Jayraj singh', 'Testing', 'Vijay nagar', 1, 0, 0, 0, '2020-06-01 00:02:11'),
(3, 1, 1, 'Jayraj singh', 'Testing', 'Vijay nagar', 1, 0, 0, 0, '2020-06-01 00:02:47'),
(4, 1, 57, 'sdgfdg', 'dfdsf@gmail.com', 'dffdg', 0, 0, 0, 0, '2020-06-03 13:47:46'),
(5, 1, 57, 'sdfdsfsdfsd', 'sdfg@gmail.com', 'ddffdgfd', 0, 0, 0, 0, '2020-06-03 13:48:51'),
(6, 1, 57, 'dsfgfdgdf', 'sdf@gmail.com', 'dsfsdf', 0, 0, 0, 0, '2020-06-03 13:53:32'),
(7, 1, 57, 'dsfgfdgdf', 'sdf@gmail.com', 'dsfsdf', 0, 0, 0, 0, '2020-06-03 13:53:43'),
(8, 1, 57, 'wefwe', 'ffgg@gmail.com', 'dsfdfsd', 0, 0, 0, 0, '2020-06-03 13:54:53'),
(9, 1, 57, 'dfgdg', 'fdsfds@gmail.com', 'dffdgfd', 0, 0, 0, 0, '2020-06-03 13:55:57'),
(10, 1, 51, 'Testing', 'gg@gmail.com', 'Testing', 0, 0, 0, 0, '2020-06-03 13:58:43'),
(11, 1, 1, 'sdfsdf', 'sdfsd@gmail.com', 'dfsdf', 0, 1, 4565, 2147483647, '2020-06-03 14:01:19'),
(12, 0, 1, 'sdfsdf', 'sdfsd@gmail.com', 'dfsdf', 0, 0, 0, 2147483647, '2020-06-03 14:52:32'),
(13, 0, 1, 'sdfsdf', 'sdfsd@gmail.com', 'dfsdf', 0, 0, 0, 2147483647, '2020-06-03 14:52:44'),
(14, 0, 1, 'sdfsdf', 'sdfsd@gmail.com', 'dfsdf', 0, 0, 0, 2147483647, '2020-06-03 14:53:10'),
(15, 0, 1, 'jayraj singh', 'jpar@gmail.com', 'sdfdsfd', 0, 1, 56465, 2147483647, '2020-06-03 14:56:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sales_order`
--
ALTER TABLE `sales_order`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sales_order`
--
ALTER TABLE `sales_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
