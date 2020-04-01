-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2020 at 09:39 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

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
CREATE DEFINER=`root`@`localhost` PROCEDURE `gift_add` (IN `i_user_id` INT UNSIGNED, IN `i_gift_name` VARCHAR(30))  READS SQL DATA
    SQL SECURITY INVOKER
BEGIN
	DECLARE v_wishlist_ID INT UNSIGNED;
    DECLARE v_priority INT UNSIGNED;

    SELECT wishlist_ID INTO v_wishlist_ID FROM users where ID=i_user_id;
    
    SELECT COALESCE(MAX(priority), -1) INTO v_priority FROM gifts WHERE wishlist_ID=v_wishlist_ID;
    
    SET v_priority = v_priority + 1;
    
    
    INSERT into gifts (name, wishlist_ID, priority) VALUES (i_gift_name, v_wishlist_ID, v_priority);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `gift_claim` (IN `i_gift_id` INT UNSIGNED, IN `i_claimed_by` VARCHAR(30))  MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN
	UPDATE gifts SET claimed_by=i_claimed_by WHERE ID = i_gift_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `gift_delete` (IN `i_gift_id` INT UNSIGNED)  MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN
	DELETE FROM gifts WHERE ID=i_gift_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `gift_set_priority` (IN `i_gift_id` INT UNSIGNED, IN `i_new_priority` INT UNSIGNED)  MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN
	DECLARE v_old_priority INT UNSIGNED;
    
    SET i_new_priority = i_new_priority + 1;
    
    SELECT priority INTO v_old_priority FROM gifts WHERE ID=i_gift_id;

	UPDATE gifts SET priority=priority-1
    WHERE priority > v_old_priority AND priority <= i_new_priority;
    
    UPDATE gifts SET priority=priority+1 where priority < v_old_priority AND priority >= i_new_priority;
    
    UPDATE gifts SET priority=i_new_priority where ID=i_gift_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `guest_login` (IN `i_access_code` VARCHAR(4))  READS SQL DATA
    SQL SECURITY INVOKER
BEGIN
	SELECT ID FROM wishlists WHERE access_code=i_access_code LIMIT 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `user_email_taken` (IN `i_email_address` VARCHAR(60), OUT `o_taken` TINYINT(1))  READS SQL DATA
    SQL SECURITY INVOKER
begin
    SELECT COUNT(*) > 0 as o_taken FROM users WHERE email_address=i_email_address;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `user_login` (IN `i_email_address` VARCHAR(30), IN `i_password` VARCHAR(64), OUT `o_id` INT UNSIGNED)  READS SQL DATA
    SQL SECURITY INVOKER
begin
	select id as o_id from users WHERE email_address=i_email_address AND password=i_password LIMIT 1;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `user_register` (IN `i_name` VARCHAR(30), IN `i_email_address` VARCHAR(60), IN `i_password` VARCHAR(64), OUT `o_success` TINYINT(1))  MODIFIES SQL DATA
    SQL SECURITY INVOKER
begin
	DECLARE var_wishlist_id INT UNSIGNED;

	CALL wishlist_create(var_wishlist_id);
    
    IF var_wishlist_id THEN

    INSERT INTO users(wishlist_ID, name, email_address, password) VALUES(var_wishlist_id, i_name, i_email_address, i_password);
    SELECT ROW_COUNT() > 0 INTO o_success;
    ELSE
    	SET o_success = false;
    END IF;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `wishlist_create` (OUT `o_wishlist_id` INT UNSIGNED)  NO SQL
begin
	DECLARE temp_access_code VARCHAR(4);
    SET temp_access_code = (SELECT SUBSTRING(MD5(RAND()) FROM 1 FOR 4));

	INSERT INTO wishlists(access_code) VALUES(temp_access_code);
    SELECT LAST_INSERT_ID() INTO o_wishlist_id;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `wishlist_guest_gifts_claimed` (IN `i_wishlist_id` INT UNSIGNED)  READS SQL DATA
    SQL SECURITY INVOKER
begin
    SELECT ID, name, claimed_by FROM gifts WHERE wishlist_ID = i_wishlist_id AND claimed_by IS NOT NULL ORDER BY priority ASC;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `wishlist_guest_gifts_unclaimed` (IN `i_wishlist_id` INT UNSIGNED)  READS SQL DATA
    SQL SECURITY INVOKER
begin
    SELECT ID, name FROM gifts WHERE wishlist_ID = i_wishlist_id AND claimed_by IS NULL ORDER BY priority ASC;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `wishlist_user_data` (IN `i_id` INT UNSIGNED, OUT `o_name` VARCHAR(30), OUT `o_access_code` VARCHAR(4))  READS SQL DATA
    SQL SECURITY INVOKER
begin
    SELECT users.name as o_name, wishlists.access_code as o_access_code FROM users INNER JOIN wishlists ON wishlists.ID = users.wishlist_ID WHERE users.ID=i_id LIMIT 1;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `wishlist_user_items` (IN `i_id` INT UNSIGNED)  READS SQL DATA
    SQL SECURITY INVOKER
begin
    SELECT gifts.ID, gifts.name FROM gifts INNER JOIN users ON gifts.wishlist_ID = users.wishlist_ID WHERE users.ID = i_id ORDER BY priority ASC;
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

--
-- Dumping data for table `gifts`
--

INSERT INTO `gifts` (`ID`, `wishlist_ID`, `name`, `priority`, `claimed_by`) VALUES
(40, 20, 'kittens', 2, NULL),
(41, 20, 'moneys', 3, 'Mijn moeder'),
(42, 20, 'u', 5, NULL),
(43, 20, 'me', 6, NULL),
(44, 20, 'food', 1, NULL),
(45, 20, 'appels', 4, 'Je moeder');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(10) UNSIGNED NOT NULL,
  `wishlist_ID` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `email_address` varchar(60) NOT NULL,
  `password` varchar(64) NOT NULL COMMENT 'Size of SHA(256) encryption'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `wishlist_ID`, `name`, `email_address`, `password`) VALUES
(18, 20, 'meandu', 'amberEnJustin@love.com', '1aaaaaf984713ba7f8ab1d4f239b4984f89a0c6a1b402b9d71507ae263ee1de2');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `ID` int(10) UNSIGNED NOT NULL,
  `access_code` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`ID`, `access_code`) VALUES
(20, 'c8d0');

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
  ADD UNIQUE KEY `unique_wishlist_ID` (`wishlist_ID`) USING BTREE,
  ADD UNIQUE KEY `unique_email_address` (`email_address`) USING BTREE;

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `unique_access_code` (`access_code`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gifts`
--
ALTER TABLE `gifts`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
