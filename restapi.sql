-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 17, 2018 at 10:11 AM
-- Server version: 10.1.8-MariaDB
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restapi`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `active_status` enum('Y','N') NOT NULL DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `email`, `password`, `created_on`, `updated_on`, `active_status`) VALUES
(86, 'Arla', '', 'chaitanya.a@inspiredge.in', 'e10adc3949ba59abbe56e057f20f883e', '2017-12-29 15:15:41', NULL, 'Y'),
(87, 'Mohammad', '', 'aamir.m@inspiredge.in', '05800d7d3e18276aac40a9d4a2d6156b', '2017-12-30 21:20:56', '2017-12-30 21:21:48', 'Y'),
(88, 'Sai Vivek ', '', 'vivek.y@inspiredge.in', 'e10adc3949ba59abbe56e057f20f883e', '2017-12-30 21:21:27', NULL, 'Y'),
(89, 'Santoshi', '', 'santoshi.p@inspiredge.in', 'e10adc3949ba59abbe56e057f20f883e', '2017-12-30 21:22:18', NULL, 'Y'),
(90, 'Varun ', '', 'varun.b@inspiredge.in', 'c049171d06ca1eaff859d53f18b8a05d', '2017-12-30 21:22:46', NULL, 'Y'),
(91, 'Raghuvani ', '', 'Raghuvani.k@inspiredge.in', 'e10adc3949ba59abbe56e057f20f883e', '2017-12-30 21:23:16', NULL, 'Y'),
(92, 'Tusshar ', '', 'Tusshar.p@inspiredge.in', 'e10adc3949ba59abbe56e057f20f883e', '2017-12-30 21:23:44', NULL, 'Y'),
(93, 'Yeruva', '', 'ananth.y@inspiredge.in', 'e10adc3949ba59abbe56e057f20f883e', '2017-12-30 21:24:15', NULL, 'Y'),
(94, 'Umme ', '', 'farva.u@inspiredge.in', 'e10adc3949ba59abbe56e057f20f883e', '2017-12-30 21:24:43', NULL, 'Y'),
(95, 'Ramya ', '', 'ramya.d@inspiredge.in', 'e10adc3949ba59abbe56e057f20f883e', '2017-12-30 21:25:08', NULL, 'Y'),
(96, 'Katipamu ', '', 'sarveshwar.k@inspiredge.in', 'e10adc3949ba59abbe56e057f20f883e', '2017-12-30 21:25:42', NULL, 'Y'),
(97, 'Roopesh ', '', 'roopesh.t@inspiredge.in', '753e91286bebce0ddd63dc0bb65bb7b5', '2017-12-30 21:26:07', NULL, 'Y'),
(98, 'Ravi', '7207203000', 'ravi.u@inspiredge.in', 'e10adc3949ba59abbe56e057f20f883e', '2018-01-03 22:50:31', NULL, 'Y'),
(99, 'Quality', '', 'qa@inspiredge.in', 'e10adc3949ba59abbe56e057f20f883e', '2018-01-04 21:57:12', NULL, 'Y'),
(100, 'Reporting ', '', 'reporting@inspiredge.in', 'e10adc3949ba59abbe56e057f20f883e', '2018-01-04 22:00:25', NULL, 'Y'),
(101, 'ketan', '123456', 'kkc1909@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '2018-01-17 08:33:59', NULL, 'Y'),
(102, 'ketan', '123456', 'kkc1909@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '2018-01-17 08:34:12', NULL, 'Y'),
(103, 'ketan', '123456', 'kkc1909@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '2018-01-17 08:34:24', NULL, 'Y');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
