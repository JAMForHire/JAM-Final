-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2021 at 09:06 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jam`
--

-- --------------------------------------------------------

--
-- Table structure for table `jars`
--

CREATE TABLE `jars` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `notes` text NOT NULL,
  `link` text NOT NULL,
  `progress` int(1) NOT NULL,
  `archived` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jars`
--

INSERT INTO `jars` (`id`, `user_id`, `company`, `date`, `notes`, `link`, `progress`, `archived`) VALUES
(8, 0, 'Amazon', '2021-12-14', 'Job opportunity at AWS', 'https://www.amazon.jobs/en/teams/internships-for-students', 2, 0),
(9, 26, 'Google', '2021-12-09', 'Google internship opportunity\r\n- Finished first round interview', 'https://careers.google.com/students/', 2, 0),
(10, 26, 'Amazon', '2021-12-22', 'Amazon full time position', 'https://www.amazon.jobs/en/teams/internships-for-students', 3, 0),
(15, 26, 'Amazon', '2021-12-22', 'Amazon full time position', 'https://www.amazon.jobs/en/teams/internships-for-students', 3, 1),
(20, 26, 'Amazon', '2021-12-22', 'Amazon full time position', 'https://www.amazon.jobs/en/teams/internships-for-students', 3, 1),
(21, 26, 'Amazon', '2021-12-22', 'Amazon full time position', 'https://www.amazon.jobs/en/teams/internships-for-students', 3, 0),
(22, 0, 'Amazon', '2021-12-14', 'Job opportunity at AWS', 'https://www.amazon.jobs/en/teams/internships-for-students', 3, 0),
(23, 26, 'test jar 4', '2021-12-15', 'a', 'a', 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint(20) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fname` varchar(255) DEFAULT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `type` int(1) NOT NULL,
  `filepath` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `resume` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `fname`, `lname`, `type`, `filepath`, `dob`, `resume`) VALUES
(0, 'username', '$2y$10$Md2KwwqOJ1YhXuzDqqBWQeLy0BQKRtMC5cI3B5drpubStTZs0YL7G', 'john', 'smith', 0, 'uploads/26.png', '2021-11-16', 'Resumes/0.docx'),
(26, 'jsmith', '$2y$10$FK7cDkrX4Dk5cqf3VSsZ6.76huphIeFP1qUdpOxE8FiIXIs3RJJZ6', 'John', 'Smith', 0, 'uploads/26.png', '2021-12-23', 'Resumes/26.pdf'),
(27, 'h', '$2y$10$54epIpEh5avjh2GADvZqpeXKvBF4tXDBms3wSCRKB07InTF6Oysma', 'h', 'h', 0, 'uploads/27.jpg', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jars`
--
ALTER TABLE `jars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jars`
--
ALTER TABLE `jars`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jars`
--
ALTER TABLE `jars`
  ADD CONSTRAINT `jars_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
