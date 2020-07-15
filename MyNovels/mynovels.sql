-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 11, 2020 at 06:52 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mynovels`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `admin_f_name` varchar(100) NOT NULL,
  `admin_l_name` varchar(100) NOT NULL,
  `admin_u_name` varchar(100) NOT NULL,
  `admin_pass` varchar(100) NOT NULL,
  `admin_all` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `admin_f_name`, `admin_l_name`, `admin_u_name`, `admin_pass`, `admin_all`) VALUES
(1, 'احمد', 'حسام', 'admin123', 'f865b53623b121fd34ee5426c792e5c33af8c227', 1),
(4, 'محمود', 'ربيع', 'محمود ربيع', '394624e13eccae9009e24796651e53af558fcbbc', 0),
(5, 'مازن', 'ربيع', 'مازن ربيع', '81f00f9e62de4fd04ef1ce05b7847b417e697b34', 0),
(7, 'ربيع', 'محمد', 'ربيع محمد', '023a790d7fef1e2c6487622b2b41abf2e5a53825', 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `novel_id` int(11) NOT NULL,
  `comment_user` varchar(255) NOT NULL,
  `comment` varchar(1000) NOT NULL,
  `comment_date` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` bigint(20) NOT NULL,
  `novel_id` int(11) NOT NULL,
  `u_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `novels`
--

CREATE TABLE `novels` (
  `id` int(11) NOT NULL,
  `novel_title` varchar(255) NOT NULL,
  `novel_author` varchar(255) NOT NULL,
  `novel_image` varchar(255) NOT NULL,
  `novel_contant` longtext NOT NULL,
  `novel_likes` int(11) NOT NULL,
  `novle_Published` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `novels`
--

INSERT INTO `novels` (`id`, `novel_title`, `novel_author`, `novel_image`, `novel_contant`, `novel_likes`, `novle_Published`) VALUES
(13, 'شسيسشي', 'مؤمن ربيع1', 'main_image.jpg', 'شسيشسي', 0, '2020-03-10'),
(15, 'cxcxc', 'moamen1234', '5e68a6f1d5d88.jpg', 'xcxcxcxcxxcxcxcxcxcxcxcxc', 0, '2020-03-11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `f_name` char(100) NOT NULL,
  `l_name` char(100) NOT NULL,
  `u_name` char(100) NOT NULL,
  `password` char(100) NOT NULL,
  `add_date` char(100) NOT NULL,
  `gender` char(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` char(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `f_name`, `l_name`, `u_name`, `password`, `add_date`, `gender`, `image`, `status`) VALUES
(21, 'مؤمن', 'ربيع', 'مؤمن ربيع', '23b48a8dc70dce739439a4a7539fcc3cbba215ef', '2020-03-09', 'ذكر', '5e67e0bd1d465.jpg', '1'),
(22, 'بسملة', 'السيد', 'بسملة السيد', '323be1babc803e3bcf64b3e4c2d2ec5364f63004', '2020-03-09', 'انثى', '5e66a13530f01.jpg', '1'),
(23, 'moamen', 'rabe', 'moamenrabe', '601f1889667efaebb33b8c12572835da3f027f78', '2020-03-10', 'ذكر', 'user.png', '1'),
(24, 'مؤمن', 'ربيع', 'مؤمن ربيع1', '601f1889667efaebb33b8c12572835da3f027f78', '2020-03-10', 'ذكر', 'user.png', '1'),
(25, 'احمد على', 'ربيع', 'moameasd123', '85136c79cbf9fe36bb9d05d0639c70c265c18d37', '2020-03-10', 'ذكر', 'user.png', '1'),
(26, 'مؤمن', 'ربيع', 'moamen1234', '79344d7e124837e7c3ca6151111bd7a324b0ba92', '2020-03-11', 'ذكر', 'user.png', '1'),
(27, 'مازن', 'ربيع', 'مازن ربيع', '601f1889667efaebb33b8c12572835da3f027f78', '2020-03-11', 'ذكر', 'user.png', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `admin_u_name` (`admin_u_name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `novels`
--
ALTER TABLE `novels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_name` (`u_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `novels`
--
ALTER TABLE `novels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
