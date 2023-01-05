-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2023 at 08:05 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `a14project`
--

-- --------------------------------------------------------

--
-- Table structure for table `access`
--

CREATE TABLE `access` (
  `user_id` varchar(32) NOT NULL,
  `tcode` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `access`
--

INSERT INTO `access` (`user_id`, `tcode`) VALUES
('17010370', 'T3'),
('17010394', 'T3'),
('17010471', 'T3'),
('17010535', 'T3');

-- --------------------------------------------------------

--
-- Table structure for table `problems`
--

CREATE TABLE `problems` (
  `pcode` varchar(15) NOT NULL,
  `pname` varchar(50) NOT NULL,
  `judge_key` varchar(16) NOT NULL,
  `owner` varchar(32) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_editor` varchar(32) DEFAULT NULL,
  `last_edit` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ready` tinyint(1) NOT NULL DEFAULT 0,
  `owner_name` varchar(50) NOT NULL,
  `editor_name` varchar(50) NOT NULL,
  `c` tinyint(1) NOT NULL DEFAULT 0,
  `cpp14` tinyint(1) NOT NULL DEFAULT 0,
  `py3` tinyint(1) NOT NULL DEFAULT 0,
  `java` tinyint(1) NOT NULL DEFAULT 0,
  `c_time` float NOT NULL DEFAULT 1,
  `cpp14_time` float NOT NULL DEFAULT 1,
  `py3_time` float NOT NULL DEFAULT 5,
  `java_time` float NOT NULL DEFAULT 2,
  `c_ready` tinyint(1) NOT NULL DEFAULT 0,
  `cpp14_ready` tinyint(1) NOT NULL DEFAULT 0,
  `py3_ready` tinyint(1) NOT NULL DEFAULT 0,
  `java_ready` tinyint(1) NOT NULL DEFAULT 0,
  `flag` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `problems`
--

INSERT INTO `problems` (`pcode`, `pname`, `judge_key`, `owner`, `created`, `last_editor`, `last_edit`, `ready`, `owner_name`, `editor_name`, `c`, `cpp14`, `py3`, `java`, `c_time`, `cpp14_time`, `py3_time`, `java_time`, `c_ready`, `cpp14_ready`, `py3_ready`, `java_ready`, `flag`) VALUES
('3_DS_SORT', 'O(N logN) Sort', 'HaEpzRNenwblKnGl', 'mayureshpatle', '2021-05-26 19:04:12', 'mayureshpatle', '2021-06-20 19:30:49', 1, 'MAYURESH PATLE', 'MAYURESH PATLE', 0, 1, 0, 0, 0.5, 0.5, 5, 2, 0, 1, 0, 1, 0),
('GCD', 'Greatest Common Divisor', 'afm4ezd9PIyJ4sYF', 'mayureshpatle', '2021-05-27 06:55:41', 'mayureshpatle', '2021-06-21 21:25:18', 1, 'MAYURESH PATLE', 'MAYURESH PATLE', 1, 0, 1, 0, 0.02, 1.01, 5.01, 2.01, 1, 0, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `t1`
--

CREATE TABLE `t1` (
  `user_id` varchar(32) NOT NULL,
  `roll_no` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `score` float NOT NULL DEFAULT 0,
  `submitted` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `t3`
--

CREATE TABLE `t3` (
  `user_id` varchar(32) NOT NULL,
  `roll_no` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `score` float NOT NULL DEFAULT 0,
  `submitted` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t3`
--

INSERT INTO `t3` (`user_id`, `roll_no`, `name`, `score`, `submitted`) VALUES
('17010370', 20, 'VEDASHREE NARSAPURKAR', 0, NULL),
('17010394', 65, 'UTTKARSH BAWANKAR', 0, NULL),
('17010471', 44, 'MAYURESH', 100, '2021-06-21 21:57:56'),
('17010535', 57, 'SHUBHAM SAWATE', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE `tests` (
  `tcode` varchar(15) NOT NULL,
  `tname` varchar(30) NOT NULL,
  `pcode` varchar(15) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `reg_key` varchar(8) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tests`
--

INSERT INTO `tests` (`tcode`, `tname`, `pcode`, `status`, `reg_key`, `created`) VALUES
('T1', 'Demo', '3_DS_SORT', 1, 'zCTGv9GG', '2021-06-18 08:41:18'),
('T3', 'Demo 1', 'GCD', 1, 'hvZTHOms', '2021-06-10 22:51:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(32) NOT NULL,
  `password` varchar(512) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(40) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `access_code` text NOT NULL,
  `mail_status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `password`, `name`, `email`, `type`, `status`, `access_code`, `mail_status`) VALUES
('17010370', '$2y$10$0xDW0BPRglfSav0l2WE4vOWK6Bf0K1Z42o4Vvipa76ny0WcESlXtK', 'VEDASHREE NARSAPURKAR', '17010394@ycce.in', 0, 1, '9PgahXJVspTgsqOzMdJUourzHLJqmZtk', 0),
('17010394', '$2y$10$LZq7fI9UZCUpWZbyMgPrQuKIcvUe/EMSsbO43x6QH/QzAuhY3dHW6', 'UTTKARSH BAWANKAR', '17010394@ycce.in', 0, 1, 'e407gvkaZOejWfq32Q6YwFLCt6fJCu4n', 0),
('17010471', '$2y$10$7HsHkz3I33ZZQgHNI1iW8O5gGaR1/rfcBV1L64rIblZn6tCXsC.7m', 'MAYURESH', '17010471@ycce.in', 0, 1, 'xXcJZK3r4VLEbBqVSiBf1excHFC5Qj4Q', 1),
('17010535', '$2y$10$lZxaOPCWoz34HwSxvaljjeXLjLU.ESCK4.SuIv4BdLSGsxQ6ubypW', 'SHUBHAM SAWATE', '17010535@ycce.in', 0, 1, 'deRxAqTtz6quw9cw22b7o0TFqLVqHbQe', 1),
('mayureshpatle', '$2y$10$K9ONsaYKk3Y96q03Fapmo.FJaUXn30RDhbPteeenJM83RFWTXK6EG', 'MAYURESH PATLE', 'mayureshpatle@gmail.com', 1, 1, 'wR3UYtwX0gMUaO9XWjOr56BePkjmIqk7', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access`
--
ALTER TABLE `access`
  ADD PRIMARY KEY (`user_id`,`tcode`),
  ADD KEY `test validaiton` (`tcode`);

--
-- Indexes for table `problems`
--
ALTER TABLE `problems`
  ADD PRIMARY KEY (`pcode`),
  ADD KEY `owner validation` (`owner`),
  ADD KEY `editor validation` (`last_editor`);

--
-- Indexes for table `t1`
--
ALTER TABLE `t1`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `t3`
--
ALTER TABLE `t3`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`tcode`),
  ADD KEY `problem validation` (`pcode`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `access`
--
ALTER TABLE `access`
  ADD CONSTRAINT `test validaiton` FOREIGN KEY (`tcode`) REFERENCES `tests` (`tcode`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user validation` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `problems`
--
ALTER TABLE `problems`
  ADD CONSTRAINT `editor validation` FOREIGN KEY (`last_editor`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `owner validation` FOREIGN KEY (`owner`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `t1`
--
ALTER TABLE `t1`
  ADD CONSTRAINT `fk_T1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t3`
--
ALTER TABLE `t3`
  ADD CONSTRAINT `fk_T3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tests`
--
ALTER TABLE `tests`
  ADD CONSTRAINT `problem validation` FOREIGN KEY (`pcode`) REFERENCES `problems` (`pcode`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
