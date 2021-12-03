-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Dec 03, 2021 at 05:52 AM
-- Server version: 5.7.30
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `JAM`
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
  `progress` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jars`
--

INSERT INTO `jars` (`id`, `user_id`, `company`, `date`, `notes`, `link`, `progress`) VALUES
(7, 0, 'Google', '2021-12-06', 'Internship at Google', 'https://careers.google.com/students/', 3),
(8, 0, 'Amazon', '2021-12-14', 'Job opportunity at AWS', 'https://www.amazon.jobs/en/teams/internships-for-students', 2),
(9, 26, 'Google', '2021-12-07', 'Google internship opportunity\r\n- Finished first round interview', 'https://careers.google.com/students/', 2),
(10, 26, 'Amazon', '2021-12-22', 'Amazon full time position', 'https://www.amazon.jobs/en/teams/internships-for-students', 3);

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
  `dob` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `fname`, `lname`, `type`, `filepath`, `dob`) VALUES
(0, 'username', '$2y$10$Md2KwwqOJ1YhXuzDqqBWQeLy0BQKRtMC5cI3B5drpubStTZs0YL7G', 'john', 'smith', 0, 'uploads/26.png', '2021-11-16'),
(26, 'jsmith', '$2y$10$FK7cDkrX4Dk5cqf3VSsZ6.76huphIeFP1qUdpOxE8FiIXIs3RJJZ6', 'John', 'Smith', 0, 'uploads/26.jpeg', '2021-12-23');

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
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jars`
--
ALTER TABLE `jars`
  ADD CONSTRAINT `jars_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
