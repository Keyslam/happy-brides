-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 27 mrt 2020 om 17:17
-- Serverversie: 10.4.11-MariaDB
-- PHP-versie: 7.4.3

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
CREATE DEFINER=`root`@`localhost` PROCEDURE `user_login` (IN `email_address` VARCHAR(30), IN `password` VARCHAR(60), OUT `result` TINYINT(1))  begin
	select count(*) > 0 as result from users where email_address = email_address AND password = password;
end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `gifts`
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
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `ID` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `email_address` varchar(60) NOT NULL,
  `password` varchar(64) NOT NULL COMMENT 'Size of SHA(256) encryption'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Stand-in structuur voor view `view_claimed_gifts`
-- (Zie onder voor de actuele view)
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
-- Stand-in structuur voor view `view_unclaimed_gifts`
-- (Zie onder voor de actuele view)
--
CREATE TABLE `view_unclaimed_gifts` (
`ID` int(10) unsigned
,`wishlist_ID` int(10) unsigned
,`name` varchar(30)
,`priority` int(10) unsigned
);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `wishlists`
--

CREATE TABLE `wishlists` (
  `ID` int(10) UNSIGNED NOT NULL,
  `user_ID` int(10) UNSIGNED NOT NULL,
  `access_code` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structuur voor de view `view_claimed_gifts`
--
DROP TABLE IF EXISTS `view_claimed_gifts`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_claimed_gifts`  AS  select `gifts`.`ID` AS `ID`,`gifts`.`wishlist_ID` AS `wishlist_ID`,`gifts`.`name` AS `name`,`gifts`.`priority` AS `priority`,`gifts`.`claimed_by` AS `claimed_by` from `gifts` where `gifts`.`claimed_by` is not null ;

-- --------------------------------------------------------

--
-- Structuur voor de view `view_unclaimed_gifts`
--
DROP TABLE IF EXISTS `view_unclaimed_gifts`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_unclaimed_gifts`  AS  select `gifts`.`ID` AS `ID`,`gifts`.`wishlist_ID` AS `wishlist_ID`,`gifts`.`name` AS `name`,`gifts`.`priority` AS `priority` from `gifts` where `gifts`.`claimed_by` is null ;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `gifts`
--
ALTER TABLE `gifts`
  ADD PRIMARY KEY (`ID`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- Indexen voor tabel `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `gifts`
--
ALTER TABLE `gifts`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
