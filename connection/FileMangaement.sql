-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 04, 2025 at 03:21 PM
-- Server version: 8.0.41-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `File_Management`
--

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `attempt_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`email`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `expires_at`) VALUES
('zan@gmail.com', '5a82c6b122ee615f04db89d1b6eb7363a227ed3715f5375ee9eb826e9127566c', '2025-05-04 16:17:59');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
CREATE TABLE IF NOT EXISTS `Users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `verified` tinyint DEFAULT '0',
  `verify_token` varchar(255) DEFAULT NULL,
  `secret` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`id`, `username`, `email`, `password`, `verified`, `verify_token`, `secret`) VALUES
(26, 'cvnn', 'cvnn@gmail.com', '$2y$10$ikaR5/tk4QUnFoh8okD6IeJO53aYLPA5p/31QLRnvKr3LJvhvA3Ru', 1, NULL, 'GAEBDPGW7IDAVQLD4YTZXSLVVUSWS673Y4GRN7RIB5ET2ARBPMCFTS53YO7SYQ6HUEEMWKYFYTCZWKXLHMZOEMUVXTFUTOGLSUOWWNY'),
(35, 'aung htet', 'akh@gmail.com', '$2y$10$rTpgfyKk02PqodtjxBqnmOCh1oZt1qhvrtYMfO7b77qsLp0FnJI2e', 1, NULL, 'RCQRNL7PABEMR4EX3VBYJCZBYXFAOOZVZAK7XQOGTMXD3PAV3UCOMX6JJGW6LP7LRSADHJUAOAF52OOB6O5D5TJ6U4W7D4GYSRIGDHA'),
(36, 'man', 'man@gmail.com', '$2y$10$2sz1t1ReEFtX4j7bvMqSMeB1MRM3GjSmo8GijmW8anKTVRLesPs6a', 0, 'c318e7f7b75eab35e53e17bc28ea7178', NULL),
(37, 'kan', 'kan@gmail.com', '$2y$10$9bAvGaMVyd3mAH4Qlu3Qj.yWjWM4ccM3AFADoWcU7aMGjWgrWAPR.', 0, '8a82dfb67158515007c6c893bd19dff5', NULL),
(38, 'dan zo', 'dan@gmail.com', '$2y$10$kdFGPSIrTL8RSWDEbn2GGetep612fM9gkNJq9LG9MT8O3g0jEpHKy', 1, NULL, 'NOU3NUWYSITIAGGHZGH2ZMGGNBOMA3WFNIZY5NJSXG7H2P53Z5NGAP2GUMH5ILFMWSQUHSJQSAOVQ4TE23ZQRDQVEZY5TJ6RIE2YZIA'),
(39, 'zan', 'zan@gmail.com', '$2y$10$SrS9lCrU.mG/TiYDylZm0eecCuNgU6I5iYjZbVG.vmP.yvD22zA6u', 1, NULL, NULL),
(40, 'sansan', 'san@gmail.com', '$2y$10$3YdgWK3H0qAL2MsZY78N4evLa6noYD0VroCnqwOEdTeNP5M71wGl2', 1, NULL, NULL),
(41, 'wai yan moe myint', 'waiyan@gmail.com', '$2y$10$LNAesQbvpn/S7nIo6egtIuaHEQFl2eOnkcDnpbZf3SeJLO4uDe09O', 1, NULL, NULL),
(42, 'hah', 'hah@gmail.com', '$2y$10$dUywm7VGd4ISipyIN8R.nOMU1CQvYD5zrVOPcxgG863lkeC7aJcW6', 1, NULL, NULL),
(43, 'akm', 'akm@gmail.com', '$2y$10$BaWDFRjdio2p7wuInrSZAO6p6Azp3sdmXCLlbJkr3YtDvlZin3uH6', 1, NULL, NULL),
(44, 'kkmt', 'kkmt@gmail.com', '$2y$10$WQhPgqUTlfzdcYCeERMVYuIzTxHHPko51D/w4AnLId3y6b3Zk9YyC', 1, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;