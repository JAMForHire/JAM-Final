-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 30, 2021 at 04:55 AM
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
(1, 0, 'RPI', '2021-11-08', 'Job opportunity at RPI', 'https://www.rpi.edu/', 0);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jars`
--
ALTER TABLE `jars`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
