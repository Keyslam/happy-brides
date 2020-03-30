-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2020 at 08:10 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `happy_brides`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `user_email_taken` (IN `i_email_address` VARCHAR(60), OUT `o_taken` TINYINT(1))  READS SQL DATA
    SQL SECURITY INVOKER
begin
    SELECT COUNT(*) > 0 as o_taken FROM users WHERE email_address=i_email_address;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `user_login` (IN `i_email_address` VARCHAR(30), IN `i_password` VARCHAR(60), OUT `o_id` INT)  READS SQL DATA
    SQL SECURITY INVOKER
begin
	select id as o_id from users WHERE email_address=i_email_address AND password=i_password LIMIT 1;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `user_register` (IN `i_name` VARCHAR(30), IN `i_email_address` VARCHAR(60), IN `i_password` VARCHAR(64), OUT `o_success` TINYINT(1))  MODIFIES SQL DATA
    SQL SECURITY INVOKER
begin
    INSERT INTO users(name, email_address, password) VALUES(i_name, i_email_address, i_password);
    SELECT ROW_COUNT() > 0 INTO o_success;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `wishlist_user_view` (IN `i_id` INT UNSIGNED, OUT `o_name` VARCHAR(30), OUT `o_access_code` VARCHAR(4))  READS SQL DATA
    SQL SECURITY INVOKER
begin
    SELECT users.name as o_name, wishlists.access_code as o_access_code FROM users INNER JOIN wishlists ON users.ID = wishlists.user_ID WHERE users.ID=i_id LIMIT 1;
end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `gifts`
--

CREATE TABLE `gifts` (
  `ID` int(10) UNSIGNED NOT NULL,
  `wishlist_ID` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `priority` int(10) UNSIGNED NOT NULL,
  `claimed_by` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `email_address` varchar(60) NOT NULL,
  `password` varchar(64) NOT NULL COMMENT 'Size of SHA(256) encryption'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `name`, `email_address`, `password`) VALUES
(1, 'Test User', 'test@user.com', 'test'),
(2, 'Test User 2', 'test@user2.com', 'test'),
(3, 'Test User 3', 'test@user3.com', 'testtest'),
(4, 'Test User 4', 'test@user4.com', '8be44ec9dbd77b437eb1dd6cef14e27fa0bde9d955569928e9764b346bb7d1f9'),
(5, 'Test User ', 'test@user5.com', '8be44ec9dbd77b437eb1dd6cef14e27fa0bde9d955569928e9764b346bb7d1f9'),
(6, 'User 6', 'test@user6.com', '8be44ec9dbd77b437eb1dd6cef14e27fa0bde9d955569928e9764b346bb7d1f9');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_claimed_gifts`
-- (See below for the actual view)
--
CREATE TABLE `view_claimed_gifts` (
`ID` int(10) unsigned
,`wishlist_ID` int(10) unsigned
,`name` varchar(30)
,`priority` int(10) unsigned
,`claimed_by` varchar(30)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_unclaimed_gifts`
-- (See below for the actual view)
--
CREATE TABLE `view_unclaimed_gifts` (
`ID` int(10) unsigned
,`wishlist_ID` int(10) unsigned
,`name` varchar(30)
,`priority` int(10) unsigned
);

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `ID` int(10) UNSIGNED NOT NULL,
  `user_ID` int(10) UNSIGNED NOT NULL,
  `access_code` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`ID`, `user_ID`, `access_code`) VALUES
(1, 1, 'TEST');

-- --------------------------------------------------------

--
-- Structure for view `view_claimed_gifts`
--
DROP TABLE IF EXISTS `view_claimed_gifts`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_claimed_gifts`  AS  select `gifts`.`ID` AS `ID`,`gifts`.`wishlist_ID` AS `wishlist_ID`,`gifts`.`name` AS `name`,`gifts`.`priority` AS `priority`,`gifts`.`claimed_by` AS `claimed_by` from `gifts` where `gifts`.`claimed_by` is not null ;

-- --------------------------------------------------------

--
-- Structure for view `view_unclaimed_gifts`
--
DROP TABLE IF EXISTS `view_unclaimed_gifts`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_unclaimed_gifts`  AS  select `gifts`.`ID` AS `ID`,`gifts`.`wishlist_ID` AS `wishlist_ID`,`gifts`.`name` AS `name`,`gifts`.`priority` AS `priority` from `gifts` where `gifts`.`claimed_by` is null ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gifts`
--
ALTER TABLE `gifts`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `unique_name_per_wishlist` (`wishlist_ID`,`name`),
  ADD KEY `index_wishlist_ID` (`wishlist_ID`) USING BTREE;

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `unqiue_email_address` (`email_address`) USING BTREE;

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `unique_access_code` (`access_code`) USING BTREE,
  ADD KEY `index_user_ID` (`user_ID`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gifts`
--
ALTER TABLE `gifts`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
