-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 30, 2019 at 06:02 AM
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

--
-- Truncate table before insert `clients`
--

TRUNCATE TABLE `clients`;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
