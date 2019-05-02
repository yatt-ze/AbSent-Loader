-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 02, 2019 at 08:43 AM
-- Server version: 5.7.19-log
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absent`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hwid` varchar(100) NOT NULL,
  `buildName` varchar(100) NOT NULL,
  `buildType` varchar(100) NOT NULL,
  `buildVersion` varchar(100) NOT NULL,
  `cpuArchitecture` varchar(100) NOT NULL,
  `cpuCores` varchar(100) NOT NULL,
  `computerName` varchar(100) NOT NULL,
  `cpu` varchar(100) NOT NULL,
  `gpu` varchar(100) NOT NULL,
  `installPath` varchar(100) NOT NULL,
  `operatingSystem` varchar(100) NOT NULL,
  `privilege` varchar(100) NOT NULL,
  `ram` varchar(100) NOT NULL,
  `userName` varchar(100) NOT NULL,
  `vram` varchar(100) NOT NULL,
  `ipAddr` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `foundPrograms` text NOT NULL,
  `installDate` varchar(100) NOT NULL,
  `lastKnock` varchar(100) NOT NULL,
  `currentTask` varchar(100) NOT NULL,
  `mark` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `c_tasks`
--

DROP TABLE IF EXISTS `c_tasks`;
CREATE TABLE IF NOT EXISTS `c_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `taskID` varchar(100) NOT NULL,
  `hwid` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `f_tasks`
--

DROP TABLE IF EXISTS `f_tasks`;
CREATE TABLE IF NOT EXISTS `f_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `taskID` varchar(100) NOT NULL,
  `hwid` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `knock` int(11) NOT NULL,
  `dead` int(11) NOT NULL,
  `gate_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`knock`, `dead`, `gate_status`) VALUES
(5, 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `taskId` varchar(100) DEFAULT NULL,
  `task` varchar(100) DEFAULT NULL,
  `parameters` varchar(100) DEFAULT NULL,
  `filters` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `date` int(11) DEFAULT NULL,
  `total` int(11) NOT NULL,
  `compleated` int(11) DEFAULT NULL,
  `failed` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `permissions` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `status`, `permissions`) VALUES
(1, 'admin', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 1, 'admin');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
