-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Pát 22. lis 2019, 21:50
-- Verze serveru: 10.3.16-MariaDB
-- Verze PHP: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `spacesabres`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `admins`
--

CREATE TABLE `admins` (
  `adminKey` varchar(30) NOT NULL,
  `Username` varchar(256) NOT NULL,
  `Password` varchar(256) NOT NULL,
  `Email` varchar(256) NOT NULL,
  `sessionID` varchar(256) NOT NULL,
  `sessionIDExpire` int(11) NOT NULL,
  `IP` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktura tabulky `blockedusers`
--

CREATE TABLE `blockedusers` (
  `userID` int(11) NOT NULL,
  `blockedUserID` int(11) NOT NULL,
  `blockedUserNickname` varchar(256) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktura tabulky `croncontrol`
--

CREATE TABLE `croncontrol` (
  `execute` int(11) NOT NULL DEFAULT 0,
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Struktura tabulky `dailybonus`
--

CREATE TABLE `dailybonus` (
  `itemID1` int(2) NOT NULL DEFAULT 0,
  `item1Ammount` int(2) NOT NULL DEFAULT 0,
  `day` int(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `dailybonus`
--

INSERT INTO `dailybonus` (`itemID1`, `item1Ammount`, `day`) VALUES
(1, 10, 1),
(18, 100, 2),
(19, 10, 3),
(22, 10, 4),
(26, 10, 5),
(16, 250000, 6),
(17, 50000, 7),
(18, 250, 8),
(3, 25, 9),
(14, 1, 10),
(1, 50, 11),
(18, 500, 12),
(20, 10, 13),
(23, 10, 14),
(25, 10, 15),
(16, 750000, 16),
(17, 150000, 17),
(18, 1000, 18),
(2, 50, 19),
(11, 1, 20),
(4, 20, 21),
(5, 20, 22),
(16, 1500000, 23),
(6, 10, 24);

-- --------------------------------------------------------

--
-- Struktura tabulky `gamelogs`
--

CREATE TABLE `gamelogs` (
  `eventType` int(11) NOT NULL DEFAULT 0,
  `error` text NOT NULL,
  `timeWhen` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `description` text NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktura tabulky `loginlogs`
--

CREATE TABLE `loginlogs` (
  `userID` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `loginIP` varchar(45) NOT NULL,
  `loginIPproxy` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Struktura tabulky `news`
--

CREATE TABLE `news` (
  `newsID` int(11) NOT NULL,
  `news_desc` text NOT NULL,
  `news_title` text NOT NULL,
  `news_img` varchar(50) NOT NULL,
  `news_time` int(11) NOT NULL,
  `idManual` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Vypisuji data pro tabulku `news`
--

INSERT INTO `news` (`newsID`, `news_desc`, `news_title`, `news_img`, `news_time`, `idManual`) VALUES
(0, 'Spacesabres are finally in open beta! Help us make spacesabres experience as smooth as possible by reporting bugs , or giving us suggestions on our forum. Any help will be rewarded!', 'Open Beta', '0001', 0, 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `notactiveusers`
--

CREATE TABLE `notactiveusers` (
  `userID` int(11) NOT NULL,
  `Username` varchar(16) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` varchar(256) NOT NULL,
  `regDate` int(11) NOT NULL,
  `IPreal` varchar(45) NOT NULL,
  `IPproxy` varchar(45) NOT NULL,
  `idActivate` longtext NOT NULL,
  `idExpire` int(11) NOT NULL DEFAULT 0,
  `coordsX` int(3) NOT NULL DEFAULT 0,
  `coordsY` int(3) NOT NULL DEFAULT 0,
  `mapLocation` int(3) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;


--
-- Struktura tabulky `profileimg`
--

CREATE TABLE `profileimg` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Vypisuji data pro tabulku `profileimg`
--

INSERT INTO `profileimg` (`id`, `userid`, `status`) VALUES
(1, 27613408, 1),
(2, 84216073, 1),
(3, 95617823, 1),
(4, 79120856, 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `pwdreset`
--

CREATE TABLE `pwdreset` (
  `pwdResetId` int(11) NOT NULL,
  `pwdResetEmail` text NOT NULL,
  `pwdResetSelector` text NOT NULL,
  `pwdResetToken` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pwdResetExpires` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Struktura tabulky `quests`
--

CREATE TABLE `quests` (
  `questID` int(11) NOT NULL,
  `objectives` text NOT NULL DEFAULT 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}',
  `rewards` text NOT NULL DEFAULT 'a:13:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;}',
  `timer` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Vypisuji data pro tabulku `quests`
--

INSERT INTO `quests` (`questID`, `objectives`, `rewards`, `timer`) VALUES
(1, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:5;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:1;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}', 'a:13:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:25;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;}', 0),
(2, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:1;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:1;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}', 'a:13:{i:0;i:200000;i:1;i:20000;i:2;i:0;i:3;i:0;i:4;i:10;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;}', 0),
(3, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:1;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}', 'a:13:{i:0;i:0;i:1;i:0;i:2;i:500;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;}', 0),
(4, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:1;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}}', 'a:13:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:1;i:10;i:1;i:11;i:1;i:12;i:1;}', 0),
(5, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:1;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}}', 'a:13:{i:0;i:300000;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;}', 0),
(6, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:1;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}}', 'a:13:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:50;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;}', 0),
(7, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:2;i:23;i:0;}}', 'a:13:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:75;i:4;i:50;i:5;i:25;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;}', 0),
(8, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:10;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}}', 'a:13:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:15;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;}', 0),
(9, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:5;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:10;i:23;i:0;}}', 'a:13:{i:0;i:2500000;i:1;i:1000000;i:2;i:2000;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;}', 0),
(10, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:350;i:4;i:150;i:5;i:75;i:6;i:35;i:7;i:25;i:8;i:2;i:9;i:10;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:20;i:23;i:0;}}', 'a:13:{i:0;i:7000000;i:1;i:3000000;i:2;i:10000;i:3;i:350;i:4;i:150;i:5;i:75;i:6;i:35;i:7;i:25;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;}', 72),
(11, 'a:24:{i:0;i:5000000;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:1;i:19;i:0;i:20;i:1;i:21;i:0;i:22;i:20;i:23;i:0;}}', 'a:13:{i:0;i:0;i:1;i:1000000;i:2;i:1000;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:2;}', 0),
(12, 'a:24:{i:0;i:7000000;i:1;i:3500000;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}}', 'a:13:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:500;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:2;}', 0),
(13, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:35;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}}', 'a:13:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:500;i:5;i:0;i:6;i:100;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;}', 0),
(14, 'a:24:{i:0;i:25000000;i:1;i:12500000;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:250;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}}', 'a:13:{i:0;i:0;i:1;i:0;i:2;i:20000;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;}', 0),
(15, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:25;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}}', 'a:13:{i:0;i:8000000;i:1;i:0;i:2;i:20000;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;}', 0),
(16, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:1500;i:4;i:1000;i:5;i:750;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}}', 'a:13:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:4;i:10;i:4;i:11;i:4;i:12;i:4;}', 0),
(17, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:250;i:7;i:250;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}}', 'a:13:{i:0;i:0;i:1;i:0;i:2;i:20000;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;}', 0),
(18, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:50;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}}', 'a:13:{i:0;i:0;i:1;i:0;i:2;i:20000;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:0;i:0;i:11;i:0;i:12;i:0;}', 0),
(19, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:72;}', 'a:13:{i:0;i:0;i:1;i:0;i:2;i:20000;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;}', 0),
(20, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:50;i:10;i:150;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}', 'a:13:{i:0;i:100000000;i:1;i:50000000;i:2;i:150000;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;}', 168);

-- --------------------------------------------------------

--
-- Struktura tabulky `research`
--

CREATE TABLE `research` (
  `dmgPriceCreds` int(11) NOT NULL,
  `hpPriceCreds` int(11) NOT NULL,
  `shdPriceCreds` int(11) NOT NULL,
  `speedPriceCreds` int(11) NOT NULL,
  `subspacePriceCreds` int(11) NOT NULL,
  `dmgPriceHyperid` int(11) NOT NULL,
  `hpPriceHyperid` int(11) NOT NULL,
  `shdPriceHyperid` int(11) NOT NULL,
  `speedPriceHyperid` int(11) NOT NULL,
  `subspacePriceHyperid` int(11) NOT NULL,
  `researchTime` int(11) NOT NULL,
  `level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Vypisuji data pro tabulku `research`
--

INSERT INTO `research` (`dmgPriceCreds`, `hpPriceCreds`, `shdPriceCreds`, `speedPriceCreds`, `subspacePriceCreds`, `dmgPriceHyperid`, `hpPriceHyperid`, `shdPriceHyperid`, `speedPriceHyperid`, `subspacePriceHyperid`, `researchTime`, `level`) VALUES
(100000, 75000, 75000, 50000, 70000, 1000, 2500, 500, 500, 750, 10, 1),
(150000, 100000, 100000, 75000, 0, 2500, 5000, 1500, 1500, 0, 10, 2),
(225000, 150000, 150000, 125000, 0, 7500, 12500, 5000, 5000, 0, 10, 3),
(150000, 150000, 150000, 150000, 0, 13000, 13000, 13000, 13000, 0, 5, 4),
(0, 0, 0, 0, 0, 10000, 5000, 19000, 13000, 0, 5, 5),
(120000, 50000, 70000, 100000, 0, 10000, 30000, 22000, 15000, 0, 5, 6),
(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 7),
(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 8),
(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 9),
(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 10),
(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 11),
(500, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 12),
(500, 0, 0, 0, 0, 0, 0, 0, 0, 0, 60, 13),
(5000, 0, 0, 0, 0, 10000, 0, 0, 0, 0, 0, 14),
(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 15),
(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 16),
(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 17),
(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 18),
(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 19),
(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20);

-- --------------------------------------------------------

--
-- Struktura tabulky `round2`
--

CREATE TABLE `round2` (
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktura tabulky `round3`
--

CREATE TABLE `round3` (
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktura tabulky `shop`
--

CREATE TABLE `shop` (
  `shipID` int(11) NOT NULL,
  `CostCreds` int(11) NOT NULL,
  `CostHyperid` int(11) NOT NULL,
  `CostNatium` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Vypisuji data pro tabulku `shop`
--

INSERT INTO `shop` (`shipID`, `CostCreds`, `CostHyperid`, `CostNatium`) VALUES
(0, 0, 0, 0),
(1, 25000, 0, 0),
(2, 40000, 0, 0),
(3, 50000, 5000, 0),
(4, 125000, 25000, 0),
(5, 150000, 27000, 0),
(6, 500000, 60000, 1000),
(7, 100000, 0, 0),
(8, 150000, 0, 0),
(9, 150000, 0, 100),
(10, 100000, 0, 0),
(19, 1, 0, 0),
(20, 1, 0, 0),
(21, 1, 0, 0),
(22, 1, 0, 0),
(23, 1, 0, 0),
(24, 1, 0, 0),
(25, 1, 0, 0),
(26, 1, 0, 0),
(27, 1, 0, 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `systems1`
--

CREATE TABLE `systems1` (
  `npcType` varchar(20) NOT NULL,
  `coordsX` int(3) NOT NULL,
  `coordsY` int(3) NOT NULL,
  `fleetPoints` int(11) NOT NULL DEFAULT 0,
  `fleetNumbers` text NOT NULL,
  `fleet` blob NOT NULL,
  `map` int(3) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `destructiontime` int(11) NOT NULL DEFAULT 0,
  `formationHeavy` varchar(11) NOT NULL DEFAULT 'Column',
  `formationLight` varchar(11) NOT NULL DEFAULT 'Arrow',
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `systems1`
--

INSERT INTO `systems1` (`npcType`, `coordsX`, `coordsY`, `fleetPoints`, `fleetNumbers`, `fleet`, `map`, `status`, `destructiontime`, `formationHeavy`, `formationLight`, `id`) VALUES
('Pirate', 238, 96, 0, 'a:6:{i:0;d:19;i:1;d:6;i:2;d:3;i:3;i:0;i:4;i:0;i:5;i:0;}', 0x1f8b080000000000020ab5d4310ec3200c85e1bb7080cacf81849ad3446a876c95c85255b97b4b51872ac9e6b74404d037f96736cdf65a4cca6c6aaf6ad1c2fa7cdc43697bd59285bace6b0d9ff3d82f2e96a57d51fa9fb6b5c8776f2837930bcab6b5737752fdc9c19f8cfe64f227477f72f227b33f79258c3a231f423f200404424120240442432044044245206484a38e706642e20ed5b47fe0858182812a031d186864a04735e929aaf987a63e49cdd5ffa94a1d1e59f04481b73783c2a0a142090000, 1, 1, 0, 'Column', 'Arrow', 807),
('Pirate', 143, 84, 0, 'a:6:{i:0;d:19;i:1;d:6;i:2;d:3;i:3;i:0;i:4;i:0;i:5;i:0;}', 0x1f8b080000000000020ab5d4310ec3200c85e1bb7080cacf81849ad3446a876c95c85255b97b4b51872ac9e6b74404d037f96736cdf65a4cca6c6aaf6ad1c2fa7cdc43697bd59285bace6b0d9ff3d82f2e96a57d51fa9fb6b5c8776f2837930bcab6b5737752fdc9c19f8cfe64f227477f72f227b33f79258c3a231f423f200404424120240442432044044245206484a38e706642e20ed5b47fe0858182812a031d186864a04735e929aaf987a63e49cdd5ffa94a1d1e59f04481b73783c2a0a142090000, 1, 1, 0, 'Column', 'Arrow', 809),
('Chief Pirate', 320, 46, 0, 'a:6:{i:0;d:92;i:1;d:30;i:2;d:13;i:3;i:0;i:4;i:0;i:5;i:0;}', 0x1f8b080000000000020ab5da3b6e83401440d1bdb08088f79dc7b01a4b49e12e126e22cb7b8f89932232eee636889f6e35470c03a72e16fd7aeef37aeadaaf5bf73e5dbe3e3fa6753fb7f5e8d376395db6e97edd1f379e7bcdfb56d6c791eefbf3fc73ced6f73ebfc97abbedd78727757cd2c6277d7c32c627737cb28d4fd6f8e4020c75820fe0470040020812809000860440248022011809e04801474a3c8700470a3852c091028e1470a48023051c29e0c800470638326242073832c091018e0c70648023031c19e0c801470e3872c091136f468023071c39e0c801470e3872c051008e02701480a3001c05b1c400380ac051008e02701480a3041c25e028014709384ac051126b7580a3041c25e02801470d70d400470d70d400470d70d400478d58f4061c35c051031c15e0a8004705382ac051018e0a705480a322be1e018e0a70b4008e16c0d172e4485e3565f6a7a8c673d488a813d120a249441b112d22ba00d1fbd845aa825415a91a5275a41a4835916a43aa8554115b82d812c49620b604b125882d416c09624b105b82d812c49622b614b175f81384beac6afd55e331b1dcc3fa7f9219bf65c3ca8e95032b27566e58b9b0f242950f7f9a185316ac8c1934cca031066fdf24de0444cc2c0000, 1, 1, 0, 'Column', 'Arrow', 810),
('Chief Pirate', 296, 62, 0, 'a:6:{i:0;d:92;i:1;d:30;i:2;d:13;i:3;i:0;i:4;i:0;i:5;i:0;}', 0x1f8b080000000000020ab5da3b6e83401440d1bdb08088f79dc7b01a4b49e12e126e22cb7b8f89932232eee636889f6e35470c03a72e16fd7aeef37aeadaaf5bf73e5dbe3e3fa6753fb7f5e8d376395db6e97edd1f379e7bcdfb56d6c791eefbf3fc73ced6f73ebfc97abbedd78727757cd2c6277d7c32c627737cb28d4fd6f8e4020c75820fe0470040020812809000860440248022011809e04801474a3c8700470a3852c091028e1470a48023051c29e0c800470638326242073832c091018e0c70648023031c19e0c801470e3872c091136f468023071c39e0c801470e3872c051008e02701480a3001c05b1c400380ac051008e02701480a3041c25e028014709384ac051126b7580a3041c25e02801470d70d400470d70d400470d70d400478d58f4061c35c051031c15e0a8004705382ac051018e0a705480a322be1e018e0a70b4008e16c0d172e4485e3565f6a7a8c673d488a813d120a249441b112d22ba00d1fbd845aa825415a91a5275a41a4835916a43aa8554115b82d812c49620b604b125882d416c09624b105b82d812c49622b614b175f81384beac6afd55e331b1dcc3fa7f9219bf65c3ca8e95032b27566e58b9b0f242950f7f9a185316ac8c1934cca031066fdf24de0444cc2c0000, 1, 1, 0, 'Column', 'Arrow', 811),
('Chief Pirate', 482, 41, 0, 'a:6:{i:0;d:92;i:1;d:30;i:2;d:13;i:3;i:0;i:4;i:0;i:5;i:0;}', 0x1f8b080000000000020ab5da3b6e83401440d1bdb08088f79dc7b01a4b49e12e126e22cb7b8f89932232eee636889f6e35470c03a72e16fd7aeef37aeadaaf5bf73e5dbe3e3fa6753fb7f5e8d376395db6e97edd1f379e7bcdfb56d6c791eefbf3fc73ced6f73ebfc97abbedd78727757cd2c6277d7c32c627737cb28d4fd6f8e4020c75820fe0470040020812809000860440248022011809e04801474a3c8700470a3852c091028e1470a48023051c29e0c800470638326242073832c091018e0c70648023031c19e0c801470e3872c091136f468023071c39e0c801470e3872c051008e02701480a3001c05b1c400380ac051008e02701480a3041c25e028014709384ac051126b7580a3041c25e02801470d70d400470d70d400470d70d400478d58f4061c35c051031c15e0a8004705382ac051018e0a705480a322be1e018e0a70b4008e16c0d172e4485e3565f6a7a8c673d488a813d120a249441b112d22ba00d1fbd845aa825415a91a5275a41a4835916a43aa8554115b82d812c49620b604b125882d416c09624b105b82d812c49622b614b175f81384beac6afd55e331b1dcc3fa7f9219bf65c3ca8e95032b27566e58b9b0f242950f7f9a185316ac8c1934cca031066fdf24de0444cc2c0000, 1, 1, 0, 'Column', 'Arrow', 812),
('Master Pirate', 368, 102, 0, 'a:6:{i:0;d:184;i:1;d:59;i:2;d:25;i:3;i:0;i:4;i:0;i:5;i:0;}', 0x1f8b080000000000020ab5db4d4e54411885e1bdb00053a7fe6ed5edd590e8c099094c8c61efdaa203633bf23e13020d39239e00f77b793eeb5ce7b7cf67b93d9ff5fcf672f6f3e9f5eb974f4fb7fb6b2fe7389f5e5e9f5f5f9e7e7cbebf7fe1e77395fbdbdcde3faaf7f74bf9f95abb7d3ccb87dcdedeee9fbf7cb25e3fd9ae9fecd74f8eeb27e7f593c7f593ebfac90dbed5051fe027005080a000420186021005280a6014e0a8024755fc1c028e2a705481a30a1c55e0a802471538aac051038e1a70d4c42f74c051038e1a70d480a3061c35e0a801471d38eac051078ebaf8cb0838eac051078e3a70d481a30e1c0de068004703381ac0d1108f1880a3011c0de068004703389ac0d1048e26703481a3091c4df1ac0e389ac0d1048e2670740047077074004707707400470770748887dec0d1011c1dc0d1028e1670b480a3051c2de06801470b385ae27a041c2de06803471b38dac0d1068e3670b481a30d1c6de0688b332cb9c38a436c1197d8224eb145dc628b38c616718d2de21c5bc43db688836c11a24cda204491b881d40d246f207d03091c48e1401207d13844440ea9a41612a244e710113a44940e11a94344eb10113b44d40e11b94344ef9046023c214a240f11cd4344f410513d44640f11dd4344f810513e44a40fe9a46915a244fd10913f44f40f1101444401119140443410111144440591413271214a8410112544440a11d14244c410113544440e11d14344041199e43f2f8428d144444411115544441611d145448411116544441a11d146e420ffcc2444893c22a28f880824220a89884422a291c8c34822ff1a4de97fadd6f1607590d549560fb2bac8ea16ab0f8389ff5f0d59ad64b59155626b135b9bd8dac4d626b6b6b0554b21ab21ab95ac36b2dac9ea20ab93ac1e64759155622bc45688ad105b21b6426c85d80ab115622bc45688ad4a6c5562ab125b95d8aac45625b62ab15589ad4a6c5562ab115b8dd86ac45623b61ab1d588ad466c3562ab115b8dd8eac45627b63ab1f530b7a8ff5cadebf7ea787f3c781fae7f3e2a1cbf963b5b1e6c79b2e5832d2fb6bcd5f2c322e39ae5b0e5ca9699c1c10c0e6670308383191ccce0600627333899c1c90c4e6670328393199ccce03406dfbe039b6424914e590000, 1, 1, 0, 'Column', 'Arrow', 813),
('Master Pirate', 574, 13, 0, 'a:6:{i:0;d:184;i:1;d:59;i:2;d:25;i:3;i:0;i:4;i:0;i:5;i:0;}', 0x1f8b080000000000020ab5db4d4e54411885e1bdb00053a7fe6ed5edd590e8c099094c8c61efdaa203633bf23e13020d39239e00f77b793eeb5ce7b7cf67b93d9ff5fcf672f6f3e9f5eb974f4fb7fb6b2fe7389f5e5e9f5f5f9e7e7cbebf7fe1e77395fbdbdcde3faaf7f74bf9f95abb7d3ccb87dcdedeee9fbf7cb25e3fd9ae9fecd74f8eeb27e7f593c7f593ebfac90dbed5051fe027005080a000420186021005280a6014e0a8024755fc1c028e2a705481a30a1c55e0a802471538aac051038e1a70d4c42f74c051038e1a70d480a3061c35e0a801471d38eac051078ebaf8cb0838eac051078e3a70d481a30e1c0de068004703381ac0d1108f1880a3011c0de068004703389ac0d1048e26703481a3091c4df1ac0e389ac0d1048e2670740047077074004707707400470770748887dec0d1011c1dc0d1028e1670b480a3051c2de06801470b385ae27a041c2de06803471b38dac0d1068e3670b481a30d1c6de0688b332cb9c38a436c1197d8224eb145dc628b38c616718d2de21c5bc43db688836c11a24cda204491b881d40d246f207d03091c48e1401207d13844440ea9a41612a244e710113a44940e11a94344eb10113b44d40e11b94344ef9046023c214a240f11cd4344f410513d44640f11dd4344f810513e44a40fe9a46915a244fd10913f44f40f1101444401119140443410111144440591413271214a8410112544440a11d14244c410113544440e11d14344041199e43f2f8428d144444411115544441611d145448411116544441a11d146e420ffcc2444893c22a28f880824220a89884422a291c8c34822ff1a4de97fadd6f1607590d549560fb2bac8ea16ab0f8389ff5f0d59ad64b59155626b135b9bd8dac4d626b6b6b0554b21ab21ab95ac36b2dac9ea20ab93ac1e64759155622bc45688ad105b21b6426c85d80ab115622bc45688ad4a6c5562ab125b95d8aac45625b62ab15589ad4a6c5562ab115b8dd86ac45623b61ab1d588ad466c3562ab115b8dd8eac45627b63ab1f530b7a8ff5cadebf7ea787f3c781fae7f3e2a1cbf963b5b1e6c79b2e5832d2fb6bcd5f2c322e39ae5b0e5ca9699c1c10c0e6670308383191ccce0600627333899c1c90c4e6670328393199ccce03406dfbe039b6424914e590000, 1, 1, 0, 'Column', 'Arrow', 814),
('Master Pirate', 581, 139, 0, 'a:6:{i:0;d:184;i:1;d:59;i:2;d:25;i:3;i:0;i:4;i:0;i:5;i:0;}', 0x1f8b080000000000020ab5db4d4e54411885e1bdb00053a7fe6ed5edd590e8c099094c8c61efdaa203633bf23e13020d39239e00f77b793eeb5ce7b7cf67b93d9ff5fcf672f6f3e9f5eb974f4fb7fb6b2fe7389f5e5e9f5f5f9e7e7cbebf7fe1e77395fbdbdcde3faaf7f74bf9f95abb7d3ccb87dcdedeee9fbf7cb25e3fd9ae9fecd74f8eeb27e7f593c7f593ebfac90dbed5051fe027005080a000420186021005280a6014e0a8024755fc1c028e2a705481a30a1c55e0a802471538aac051038e1a70d4c42f74c051038e1a70d480a3061c35e0a801471d38eac051078ebaf8cb0838eac051078e3a70d481a30e1c0de068004703381ac0d1108f1880a3011c0de068004703389ac0d1048e26703481a3091c4df1ac0e389ac0d1048e2670740047077074004707707400470770748887dec0d1011c1dc0d1028e1670b480a3051c2de06801470b385ae27a041c2de06803471b38dac0d1068e3670b481a30d1c6de0688b332cb9c38a436c1197d8224eb145dc628b38c616718d2de21c5bc43db688836c11a24cda204491b881d40d246f207d03091c48e1401207d13844440ea9a41612a244e710113a44940e11a94344eb10113b44d40e11b94344ef9046023c214a240f11cd4344f410513d44640f11dd4344f810513e44a40fe9a46915a244fd10913f44f40f1101444401119140443410111144440591413271214a8410112544440a11d14244c410113544440e11d14344041199e43f2f8428d144444411115544441611d145448411116544441a11d146e420ffcc2444893c22a28f880824220a89884422a291c8c34822ff1a4de97fadd6f1607590d549560fb2bac8ea16ab0f8389ff5f0d59ad64b59155626b135b9bd8dac4d626b6b6b0554b21ab21ab95ac36b2dac9ea20ab93ac1e64759155622bc45688ad105b21b6426c85d80ab115622bc45688ad4a6c5562ab125b95d8aac45625b62ab15589ad4a6c5562ab115b8dd86ac45623b61ab1d588ad466c3562ab115b8dd8eac45627b63ab1f530b7a8ff5cadebf7ea787f3c781fae7f3e2a1cbf963b5b1e6c79b2e5832d2fb6bcd5f2c322e39ae5b0e5ca9699c1c10c0e6670308383191ccce0600627333899c1c90c4e6670328393199ccce03406dfbe039b6424914e590000, 1, 1, 0, 'Column', 'Arrow', 815),
('Xamon scout', 145, 97, 0, 'a:6:{i:0;d:28;i:1;d:9;i:2;d:4;i:3;d:2;i:4;d:2;i:5;d:1;}', 0x1f8b080000000000020ab5d54d6e83301086e1bb708068e6f3989ff16990da457695601345be7beb5292a098aefc6d1018eb5918bf7876ebfd7e7549b3c3ef8b9b77ebedebb34b656cf1e8ddb2ceebd2fdbcb76de2d54729574ddb13cabdc8ef58481f2e174d3997f7cd49b427437bd2da93b13dd9b72787f6e4d89e9c085b9d910fa11f2504a48482949090121a5242444aa848091929a123103a02e31c227404424720740442472074044247a875a467a68abda188efe8444083305065a060a081811a038d0cb467a0b5a0708a62dcd1b885545c1ca38a1b3cb2e089049bb0e05a61e10c0e78fcb5823ce5fe20cb9f0c9a5c6bcdce647bfe69c3cb6a0c870db7cb46936bd5c57f0e875d1e5ebf20e2be1c655ecef91bacc4e84f4e0f0000, 1, 1, 0, 'Column', 'Arrow', 816),
('Xamon Ensign', 307, 360, 0, 'a:6:{i:0;d:55;i:1;d:18;i:2;d:8;i:3;d:4;i:4;d:3;i:5;d:1;}', 0x1f8b080000000000020ab5d84b6e83301440d1bdb080ca7e1fdb98d5446a079955824915b1f7965292a04047be13c44f77001c6cb8d4d2d7dbb586e152a5dec66ab59bbe3e3fba61d93756afdd385da6b1fb396eeb89d75ac2b28cc3ba25cb7a08bffb7478afe12d0ef3bc1c6f9e94f6496d9fb4f6496f9f4ced93b97db2b44ff6c0a34ef001fc4400500404458050040c4500510414458051041c09e04888710870248023011c09e0480047023812c091008e1470a480232526748023051c29e04801470a3852c091028e0c70648023031c19f165043832c091018e0c70648023031c39e0c801470e3872c09113bf188e1cc5b3660cf612157f8d26229a896821a23d104d818846222a445489a8115142542244254254224425425426446542542644e52351721a95b2457d1d4596aeec47145fc346859d0a272a9ca970a1c23d142e810a1f89d3b3b0ca7d5aa4e1514ebb72f82b0b5656ac6c58f9489f9d95ed31fdd4a73b98772fa2ad9cb072c6ca4700fd9fb9f856cecfcfb3f876a197f3e679fe06ca4dcfa1991d0000, 1, 1, 0, 'Column', 'Arrow', 817),
('Xamon Lieutenant', 568, 324, 0, 'a:6:{i:0;d:83;i:1;d:27;i:2;d:12;i:3;d:6;i:4;d:4;i:5;d:2;}', 0x1f8b080000000000020ab5da496ec2301840e1bb7080caffe429a7416a17ec2ac1a642dcbda4cc22e9ca6f832089de26f9886d79dbc5bc1f773d4ddbaefdb8efde37879fefafcd341fdbf7e89bfd617bd86fcee7af17ee7a4df3a74c975f3a7f4fe9ef984d9f3d7dc8743acde78727757cd2c6277d7c32c627f3f864199face3930d78d4093e801f010009204800420218120091008a0460248023051c29f11e021c29e04801470a3852c091028e1470a48023031c19e0c888011de0c80047063832c091018e0c70648023071c39e0c801474ecc8c00470e3872c091038e1c70e480a3001c05e028004701380a628901701480a3001c05e02800471970940147197094014719709489b53ac051061c65c051061c15c051011c15c051011c15c051011c1562d11b705400470570540147157054014775c991ac3525f95b54e33dea4434886826a285885622da80684b445488a8125142542344354254234435425423443542d4f98f1ba90a5255a46a48d5916a20d58c540b52ad4815b1b5b8eb4157ab5a6fd5b88ca6e6b0be8eace25a16acac58d9b0b263e5c0ca192b17ac5cb172a3ca8a1954cce0e2ee095b2b9bdea763961ee9fc924eb7b47169e7d2c1a533972e5c7a49a2afa5fd3161b7a787afbcbcb3eee986a517775d0c4a0b975ed218ffac8ddcd2e519bac6ed3eced75dc246844fbf82c149d9b32c0000, 1, 1, 0, 'Column', 'Arrow', 818),
('Xamon Commander', 320, 12, 0, 'a:6:{i:0;d:110;i:1;d:35;i:2;d:15;i:3;d:8;i:4;d:5;i:5;d:2;}', 0x1f8b080000000000020ab5da4b6ec2301440d1bdb080ca7e1fffb21aa476c0ac52985488bd97340d1fd5e9c877822041779403899f8f2d666f97530bd3b149bbcccddae1fcf5f97198966373f37698cfc7f37cb89db7f58ba756c2f21aa7f5932cef43f839a6d37b0b6f71ba5e97f3c393323ea9e393363ee9e393697c328f4f96f1c90a5cea041fc04f0400454050040845c0500410454051041845c091008e84f81f021c09e0480047023812c091008e0470248023051c29e048891b3ac091028e1470a48023051c29e0480147063832c091018e8c7832021c19e0c80047063832c091018e1c70e48023071c39e0c8892506c091038e1c70e48023071c25c051021c25c051021c25c05122d6ea0047097094004709709401471970940147197094014719709489456fc051061c65c051011c15c051011c15c051011c15c051011c15627a04382a80a30a38aa80a30a38aa80a30a38aa80a30a38aa80a34a8c6191392c31880dc4243610a3d840cc6203318c0dc4343610e3d840cc630331900d84a8eed686b817bd5d2c7faae29d6a44aa825415a91a5275a49a906a46aa05a956a22a882d416c09624b105b82d812c49620b604b125882d416c29624b115b8ad852c49622b614b1a5882d456c29624b115b86d832c49621b60cb16588adee0609d9ad4ad9aabe3e0e2c61797d34f0df72c2ca192b17ac5ca97277fbc49872c4ca8295152b1b56c60c3a66d031838e1974cc6077eb85ee9555eeab2b1a1ee9f4920e5b3a7269e1d2caa58d4b3b974e5c3a73e99e45db4bdb63d5509fc8e497fb8e7bba62e9ee068e41e9c8a5854bf734fa3f6bbf5b3a3ffff2896f97c8f2bd356c44f8fa0d40694727793a0000, 1, 1, 0, 'Column', 'Arrow', 819),
('Xamon Captain', 451, 48, 0, 'a:6:{i:0;d:165;i:1;d:53;i:2;d:23;i:3;d:12;i:4;d:8;i:5;d:3;}', 0x1f8b080000000000020ab5da494e1b511486d1bd7801d1bbaf7fe5d52025036691cc2442ec3d718869947226a9334160d037e2d0d4fd1fb6dcebf6fcb8a5f3c396b7e7cb56b7d3d38fefdf4ee7eb6b97ad6da7cbd3c3d3e5f4ebf37fbef0719be9fa36ceaf1fe5ebfb29fd7ead9cbf6ee94b9c5f5eae9f3f3c998f4f96e393f5f8643b3ed98f4f8ee393f3f8e402dfea820ff01300500041010805301400510045011805709481a32c7e0f01471938cac051068e32709481a30c1c65e0a8004705382ae20f3ae0a8004705382ac051018e0a705480a30a1c55e0a8024755fc67041c55e0a802471538aac051058e1a70d480a3061c35e0a889470cc051038e1a70d480a3061c75e0a803471d38eac051078eba7856071c75e0a803471d381ac0d1008e06703480a3011c0de0688887dec0d1008e06703481a3091c4de068024713389ac0d1048ea6b81e014713385ac0d1028e1670b480a3051c2de06801470b385ae20c4beeb0e2109bc4253689536c12b7d8248eb1495c639338c726718f4de2209b8428336d10a2c8b881ac1bc8bc81ec1bc8c0812c1cc8c4416c1c428c1c2293b5901025760e21860e21960e21a60e21b60e21c60e21d60e21e60e21f60e51c8004f88129387109b8710a38710ab8710b38710bb8710c38710cb8710d387a864d32a4489f54388f94388fd43880144880544880944880d4488114488154434321317a2c41022c41222c41422c41622c41822c41a22c41c22c41e22c42022761711712f1aa9fe55cd6da7da497590ea24d525aabbdb88ffaf06a966522da45a4995d81ac4d620b606b13588ad496c4d626b125b93d89ac4d624b626b13589ad496c4d626b115b8bd85ac4d622b616b1b588ad456c2d626b115b4bd8ca29916a906a26d542aa95541ba976521da43a4995d80a622b88ad20b682d80a622b88ad20b682d8da1d5ae4bbd53c6fd5f6fa68e81ace9f1f13b53fe5a5cabb938b63cac1ca99950b2b57566eacdc5979b032339899c1c20c1666b0308385192ccc6061060b335898c1c20c1666b03283bbdb8e72af5cf2dbe5a8a4f774ff944eb77476e9e2d2d5a59b4b77971e2e3d5d7ab1f4ee22e4a0b4d3d89cc6dd8d48bd97aeefd7e7f2e1c7d3f8f43fcb5bbaba7473e9eed2c3a5a74b2f96de5d931c94ded3d8fe31aab8a5c7c75fbbb9ddcc5cbfee359c55b888f0cb4f007651a76e580000, 1, 1, 0, 'Column', 'Arrow', 820),
('Xamon Admiral', 375, 147, 0, 'a:6:{i:0;d:193;i:1;d:62;i:2;d:27;i:3;d:14;i:4;d:9;i:5;d:4;}', 0x1f8b080000000000020ab5db4d4edc580085d1bdb08096effbb76b3548c920b39660d28ad87b524d9304c564d23e130455a53be24095dfe7c7a36efbf1f5cbb1dd1e8f727c7d3adaf1f0fccfdf9f1f6ef7c79e8e7e3c3c3d3f3e3f3d7c7fbebdbef0cbb1b6fbd7dc5e7f2af7efb7eddfc7eaedd3b1fd95dbcbcbfdf9cb27cbf593f5fac976fd64bf7e725c3f39af9f5cd74feee0575df0017e020005080a20146028005180a20046018e0a7054c4ff21e0a8004705382ac051018e0a705480a3021c55e0a8024755bca1038e2a705481a30a1c55e0a8024715386ac051038e1a70d4c42723e0a801470d386ac051038e1a70d481a30e1c75e0a803475d5c62008e3a70d481a30e1c75e068004703381ac0d1008e067034c4b53ae068004703381ac0d1048e26703481a3091c4de0680247535cf4068e26703481a3051c2de06801470b385ac0d1028e1670b4c4e91170b480a31d38da81a31d38da81a31d38da81a31d38da81a35d1cc39273587110bb8993d84d1cc56ee22c761387b19b388dddc471ec26ce63377120bb0951266d10a248dc40ea06923790be81040ea470208983681c22228714520b0951a27388081d224a8788d421a27588881d226a8788dc21a2774825019e1025928788e621227a88a81e22b28788ee21227c88281f22d28734d2b40a51a27e88c81f22fa87880022a280884820221a88880822a282482799b8102542888812222285886821226288881a22228788e8212282880c72e78510259a88882822a28a88c82222ba88883022a28c88482322da884c7233931025f288883e222290882824221289884622229288a8242232892c727fa010254a89885422a295888825725a4be4a3d16cedb7d5d24f561b59ed647590d549561759ddc16a394d27feff6ac86a21ab95ac36b2dac9ea20ab93ac2eb24a6c85d80ab115622bc45688ad105b21b6426c85d80ab15588ad426c1562ab105b85d82ac45621b60ab15588ad426c5562ab125b95d8aac45625b62ab15589ad4a6c5562ab125b8dd86ac45623b61ab1d588ad466c3562ab115b8dd86ac45627b63ab1d589ad4e6c7562ebb4b8281fae96f5b6da5f2f90de87cbfb8ba5fdbfe5c196275b5e6c7957cba729c635cb61cb852d57b6dcd832333898c1c10c0e6670308393199ccce0640627333899c1c90c4e6670328393199ccce062061733781a7ed48f966bf9714e5db79fd3e3ddf4f6365ddd7473d3dd4d0f373dddf472d33b9b3e0d452e9a8e9b761a77a771771a77a7f1b424691f4db79f854efde58fea7cf789f6c7f474d3cb4def6aba9ed625174dc74d17375ddd7473d3671afb1f9ab6b7e9f9ebfb90d2df38de5ff73a3cd4f054c34b0cbf7c03d4ffa2f492670000, 1, 1, 0, 'Column', 'Arrow', 821),
('Xamon Fleet Admiral', 259, 56, 0, 'a:6:{i:0;d:220;i:1;d:70;i:2;d:30;i:3;d:15;i:4;d:10;i:5;d:4;}', 0x1f8b080000000000020ab5dc4d4e1b590085d1bdb080d6bbefbfcaab414a0f328b049356c4debbdd6e92a03619f94c10d8d61d71c0d4fb8ae7b3f5e3fcfef52c97e7b39edf5fce7e3ebdfef5edcfa7cbf5b197739c4f2fafcfaf2f4fff3cdf6f2ffc7aee72fd98cbedab7afdbc947f1f6b972f67f92397b7b7ebf30f9fac8f9f6c8f9fec8f9f1c8f9f9c8f9f5c8f9fdc8f9f3cc0b7bae003fc04000a10144028c05000a20045018c021c55e0a88adf43c051058e2a705481a30a1c55e0a8024715386ac051038e9a7843071c35e0a801470d386ac051038e1a70d481a30e1c75e0a88bbf8c80a30e1c75e0a803471d38eac0d1008e06703480a3011c0d718901381ac0d1008e06703480a3091c4de068024713389ac0d114d7ea80a3091c4de06802470b385ac0d1028e1670b480a3051c2d71d11b385ac0d1028e3670b481a30d1c6de06803471b38dac0d116a747c0d1068e0ee0e8008e0ee0e8008e0ee0e8008e0ee0e8008e0e710c4bce61c5416c1127b1451cc51671165bc4616c11a7b1451cc716711e5bc4816c11a24cda204491b881d40d246f207d03091c48e1401207d13844440ea9a41612a244e710113a44940e11a94344eb10113b44d40e11b94344ef9046023c214a240f11cd4344f410513d44640f11dd4344f810513e44a40fe9a46915a244fd10913f44f40f1101444401119140443410111144440591413271214a8410112544440a11d14244c410113544440e11d14344041199e4ce0b214a34111151444415119145447411116144441911914644b41159e46626214ae411117d444420115148442412118d444424115149446412d9e4fe40214a941211a944442b11114b44d41211b944442f11114c44141339c82db7e49e5b71d3ad6826aa6826aa6826aa6826aa6826aa6826aa6826aa6826aa68266ac86dec42946826aa6826aa6826aa6826aa6826aa6826aa68262af9bf10779b897c369ad2ffb75ac79dd590d54a561b59ed647590d549561759dd64f510ab8dd86ac45623b61ab1d588ad466c3562ab115b8dd86ac45627b63ab1d589ad4e6c7562ab135b9dd8eac45627b63ab13588ad416c0d626b105b83d81ac4d620b606b13588ad416c4d626b125b93d89ac4d624b626b13589ad496c4d626b125b8bd85ac4d622b616b1b588ad456c2d626b115b8bd85ac4d626b636b1b589ad4d6c6d626b135b9bd8dac4d626b636b175b7bca89fae5e2dde56c7ed82fe75b87ebcb83ffe5b0e5bae6cb9b1e5ce96075b9e6c79b1e5cd960fb4dc4a61cb61cb952d37b6dcd9f260cb932d2fb6bcd93233186630cc6098c130836106c30c86190c33186630cce0dd1ea47db6dcea8fcaa4959fd3f3c374799f8e9bae6ebab9e9eea6879b9e6e7ab9e9eda60f36dd9cc6e63436a7b1398dcd69bc9b96f4cfa6fbcf74affdf2f37a7db874f0637abae9e5a6b79b3ed8f4dde6e441d371d3d54d3737ddddf43d8de3371dedfbf4faf5dd531defd2afafbb0d4f35bcd4f016c36f7f03b549aa94fd740000, 1, 1, 0, 'Column', 'Arrow', 822);

-- --------------------------------------------------------

--
-- Struktura tabulky `systems2`
--

CREATE TABLE `systems2` (
  `npcType` varchar(20) NOT NULL,
  `coordsX` int(3) NOT NULL,
  `coordsY` int(3) NOT NULL,
  `fleetPoints` int(11) NOT NULL DEFAULT 0,
  `map` int(3) NOT NULL,
  `hornet` int(11) NOT NULL,
  `spacefire` int(11) NOT NULL,
  `starhawk` int(11) NOT NULL,
  `peacemaker` int(11) NOT NULL,
  `centurion` int(11) NOT NULL,
  `nathalis` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `destructiontime` int(11) NOT NULL DEFAULT 0,
  `formationHeavy` varchar(11) NOT NULL DEFAULT 'Column',
  `formationLight` varchar(11) NOT NULL DEFAULT 'Arrow',
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabulky `systems3`
--

CREATE TABLE `systems3` (
  `npcType` varchar(20) NOT NULL,
  `coordsX` int(3) NOT NULL,
  `coordsY` int(3) NOT NULL,
  `fleetPoints` int(11) NOT NULL DEFAULT 0,
  `map` int(3) NOT NULL,
  `hornet` int(11) NOT NULL,
  `spacefire` int(11) NOT NULL,
  `starhawk` int(11) NOT NULL,
  `peacemaker` int(11) NOT NULL,
  `centurion` int(11) NOT NULL,
  `nathalis` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `destructiontime` int(11) NOT NULL DEFAULT 0,
  `formationHeavy` varchar(11) NOT NULL DEFAULT 'Column',
  `formationLight` varchar(11) NOT NULL DEFAULT 'Arrow',
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabulky `systems4`
--

CREATE TABLE `systems4` (
  `npcType` varchar(20) NOT NULL,
  `coordsX` int(3) NOT NULL,
  `coordsY` int(3) NOT NULL,
  `fleetPoints` int(11) NOT NULL DEFAULT 0,
  `map` int(3) NOT NULL,
  `hornet` int(11) NOT NULL,
  `spacefire` int(11) NOT NULL,
  `starhawk` int(11) NOT NULL,
  `peacemaker` int(11) NOT NULL,
  `centurion` int(11) NOT NULL,
  `nathalis` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `destructiontime` int(11) NOT NULL DEFAULT 0,
  `formationHeavy` varchar(11) NOT NULL DEFAULT 'Column',
  `formationLight` varchar(11) NOT NULL DEFAULT 'Arrow',
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabulky `systems5`
--

CREATE TABLE `systems5` (
  `npcType` varchar(20) NOT NULL,
  `coordsX` int(3) NOT NULL,
  `coordsY` int(3) NOT NULL,
  `fleetPoints` int(11) NOT NULL DEFAULT 0,
  `map` int(3) NOT NULL,
  `hornet` int(11) NOT NULL,
  `spacefire` int(11) NOT NULL,
  `starhawk` int(11) NOT NULL,
  `peacemaker` int(11) NOT NULL,
  `centurion` int(11) NOT NULL,
  `nathalis` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `destructiontime` int(11) NOT NULL DEFAULT 0,
  `formationHeavy` varchar(11) NOT NULL DEFAULT 'Column',
  `formationLight` varchar(11) NOT NULL DEFAULT 'Arrow',
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabulky `systems6`
--

CREATE TABLE `systems6` (
  `npcType` varchar(20) NOT NULL,
  `coordsX` int(3) NOT NULL,
  `coordsY` int(3) NOT NULL,
  `fleetPoints` int(11) NOT NULL DEFAULT 0,
  `map` int(3) NOT NULL,
  `hornet` int(11) NOT NULL,
  `spacefire` int(11) NOT NULL,
  `starhawk` int(11) NOT NULL,
  `peacemaker` int(11) NOT NULL,
  `centurion` int(11) NOT NULL,
  `nathalis` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `destructiontime` int(11) NOT NULL DEFAULT 0,
  `formationHeavy` varchar(11) NOT NULL DEFAULT 'Column',
  `formationLight` varchar(11) NOT NULL DEFAULT 'Arrow',
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabulky `systems7`
--

CREATE TABLE `systems7` (
  `npcType` varchar(20) NOT NULL,
  `coordsX` int(3) NOT NULL,
  `coordsY` int(3) NOT NULL,
  `fleetPoints` int(11) NOT NULL DEFAULT 0,
  `map` int(3) NOT NULL,
  `hornet` int(11) NOT NULL,
  `spacefire` int(11) NOT NULL,
  `starhawk` int(11) NOT NULL,
  `peacemaker` int(11) NOT NULL,
  `centurion` int(11) NOT NULL,
  `nathalis` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `destructiontime` int(11) NOT NULL DEFAULT 0,
  `formationHeavy` varchar(11) NOT NULL DEFAULT 'Column',
  `formationLight` varchar(11) NOT NULL DEFAULT 'Arrow',
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabulky `systems8`
--

CREATE TABLE `systems8` (
  `npcType` varchar(20) NOT NULL,
  `coordsX` int(3) NOT NULL,
  `coordsY` int(3) NOT NULL,
  `fleetPoints` int(11) NOT NULL DEFAULT 0,
  `map` int(3) NOT NULL,
  `hornet` int(11) NOT NULL,
  `spacefire` int(11) NOT NULL,
  `starhawk` int(11) NOT NULL,
  `peacemaker` int(11) NOT NULL,
  `centurion` int(11) NOT NULL,
  `nathalis` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `destructiontime` int(11) NOT NULL DEFAULT 0,
  `formationHeavy` varchar(11) NOT NULL DEFAULT 'Column',
  `formationLight` varchar(11) NOT NULL DEFAULT 'Arrow',
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabulky `systems9`
--

CREATE TABLE `systems9` (
  `npcType` varchar(20) NOT NULL,
  `coordsX` int(3) NOT NULL,
  `coordsY` int(3) NOT NULL,
  `fleetPoints` int(11) NOT NULL DEFAULT 0,
  `map` int(3) NOT NULL,
  `hornet` int(11) NOT NULL,
  `spacefire` int(11) NOT NULL,
  `starhawk` int(11) NOT NULL,
  `peacemaker` int(11) NOT NULL,
  `centurion` int(11) NOT NULL,
  `nathalis` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `destructiontime` int(11) NOT NULL DEFAULT 0,
  `formationHeavy` varchar(11) NOT NULL DEFAULT 'Column',
  `formationLight` varchar(11) NOT NULL DEFAULT 'Arrow',
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabulky `systems10`
--

CREATE TABLE `systems10` (
  `npcType` varchar(20) NOT NULL,
  `coordsX` int(3) NOT NULL,
  `coordsY` int(3) NOT NULL,
  `fleetPoints` int(11) NOT NULL DEFAULT 0,
  `map` int(3) NOT NULL,
  `hornet` int(11) NOT NULL,
  `spacefire` int(11) NOT NULL,
  `starhawk` int(11) NOT NULL,
  `peacemaker` int(11) NOT NULL,
  `centurion` int(11) NOT NULL,
  `nathalis` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `destructiontime` int(11) NOT NULL DEFAULT 0,
  `formationHeavy` varchar(11) NOT NULL DEFAULT 'Column',
  `formationLight` varchar(11) NOT NULL DEFAULT 'Arrow',
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabulky `userbase`
--

CREATE TABLE `userbase` (
  `userID` int(11) NOT NULL,
  `inventoryMod1` int(11) NOT NULL DEFAULT 0,
  `inventoryMod2` int(11) DEFAULT 0,
  `inventoryMod3` int(11) DEFAULT 0,
  `inventoryMod4` int(11) DEFAULT 0,
  `coreStatus` int(1) DEFAULT 1,
  `coreHealth` int(11) NOT NULL DEFAULT 1000000,
  `coreShields` int(11) NOT NULL DEFAULT 500000,
  `slot1` int(11) DEFAULT 0,
  `slot2` int(11) DEFAULT 0,
  `slot3` int(11) DEFAULT 0,
  `slot4` int(11) DEFAULT 0,
  `slot5` int(11) DEFAULT 0,
  `slot6` int(11) DEFAULT 0,
  `slot7` int(11) DEFAULT 0,
  `slot8` int(11) DEFAULT 0,
  `slot9` int(11) DEFAULT 0,
  `slot10` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Struktura tabulky `userclans`
--

CREATE TABLE `userclans` (
  `clanID` varchar(16) NOT NULL,
  `createDate` int(11) NOT NULL,
  `clanName` varchar(30) NOT NULL,
  `clanTag` varchar(5) NOT NULL,
  `clanLeader` varchar(50) NOT NULL,
  `clanContact` varchar(100) NOT NULL DEFAULT 'None',
  `clanDetail` varchar(250) NOT NULL DEFAULT 'No alliance description has been added yet.',
  `clanLog` text NOT NULL DEFAULT 'a:0:{}',
  `clanApps` text NOT NULL DEFAULT 'a:0:{}',
  `clanAppDesc` text NOT NULL DEFAULT 'a:0:{}',
  `clanDiplo` text NOT NULL DEFAULT 'a:0:{}',
  `clanDiploReq` text NOT NULL DEFAULT 'a:0:{}',
  `clanDiploPending` text NOT NULL DEFAULT 'a:0:{}',
  `clanImgID` varchar(11) NOT NULL DEFAULT '0',
  `clanImgStatus` int(2) NOT NULL DEFAULT 0,
  `clanMembers` text NOT NULL DEFAULT 'a:0:{}',
  `clanMembersPerms` text NOT NULL,
  `totalMembers` int(11) NOT NULL DEFAULT 1,
  `totalPoints` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Struktura tabulky `userfleet`
--

CREATE TABLE `userfleet` (
  `id` int(11) NOT NULL,
  `userID` int(8) NOT NULL,
  `admin` int(11) NOT NULL DEFAULT 0,
  `rank` int(11) DEFAULT 1,
  `leaderboardPos` int(11) DEFAULT 0,
  `pageCoordsX` int(11) DEFAULT 0,
  `pageCoordsY` int(11) DEFAULT 0,
  `mapLocation` int(11) DEFAULT 0,
  `formationHeavy` varchar(10) NOT NULL DEFAULT 'Line',
  `formationLight` varchar(10) NOT NULL DEFAULT 'Phalanx',
  `dock1` blob NOT NULL,
  `dock2` blob NOT NULL,
  `dock3` blob NOT NULL,
  `dock4` blob NOT NULL,
  `dock5` blob NOT NULL,
  `dock6` blob NOT NULL,
  `dock7` blob NOT NULL,
  `dock8` blob NOT NULL,
  `dock9` blob NOT NULL,
  `dock10` blob NOT NULL,
  `temporaryDock` blob NOT NULL,
  `dockAmount` int(11) NOT NULL DEFAULT 1,
  `equipment` text NOT NULL DEFAULT 'a:4:{s:6:"lasers";a:3:{i:19;i:0;i:20;i:0;i:21;i:0;}s:7:"rockets";a:1:{i:26;i:0;}s:7:"shields";a:1:{i:25;i:0;}s:10:"hyperspace";a:3:{i:22;i:0;i:23;i:0;i:24;i:0;}}',
  `dockIncrement` int(11) NOT NULL DEFAULT 0,
  `templates` blob NOT NULL,
  `fuel` int(11) DEFAULT 0,
  `destroyedPoints` int(11) DEFAULT 0,
  `fleetPoints` int(11) DEFAULT 0,
  `destroyedShips` int(11) DEFAULT 0,
  `destroyedHornets` int(11) DEFAULT 0,
  `destroyedSpacefires` int(11) DEFAULT 0,
  `destroyedStarhawks` int(11) DEFAULT 0,
  `destroyedPeacemakers` int(11) DEFAULT 0,
  `destroyedCenturions` int(11) DEFAULT 0,
  `destroyedNathalis` int(11) DEFAULT 0,
  `battlesWon` int(11) DEFAULT 0,
  `battlesLost` int(11) DEFAULT 0,
  `battlesDraw` int(11) DEFAULT 0,
  `battlesTotal` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Struktura tabulky `usermovement`
--

CREATE TABLE `usermovement` (
  `userID` int(11) NOT NULL DEFAULT 0,
  `targetUserID` int(11) NOT NULL,
  `attackedUserX` int(11) NOT NULL DEFAULT 0,
  `attackedUserY` int(11) NOT NULL DEFAULT 0,
  `targetMapLocation` int(11) NOT NULL DEFAULT 0,
  `fleet` blob NOT NULL,
  `fleetNumbers` text NOT NULL,
  `travelTime` int(11) NOT NULL DEFAULT 0,
  `travelWay` tinyint(1) NOT NULL DEFAULT 0,
  `returnWay` tinyint(1) NOT NULL DEFAULT 0,
  `setAttack` int(11) NOT NULL DEFAULT 0,
  `missionType` int(11) NOT NULL DEFAULT 1,
  `type` varchar(20) NOT NULL,
  `lobbyID` varchar(20) NOT NULL DEFAULT '0',
  `rounds` int(11) NOT NULL DEFAULT 1,
  `report1` text NOT NULL,
  `rewardsAttacker` text NOT NULL DEFAULT 'a:3:{i:0;i:0;i:1;i:0;i:2;i:0;}',
  `rewardsDefender` text NOT NULL DEFAULT 'a:3:{i:0;i:0;i:1;i:0;i:2;i:0;}',
  `report2` text NOT NULL DEFAULT 'empty',
  `report3` text NOT NULL DEFAULT 'empty',
  `defenseHours` int(11) NOT NULL DEFAULT 0,
  `reward` int(11) NOT NULL DEFAULT 0,
  `hudPresentAttacker` int(11) NOT NULL DEFAULT 0,
  `hudPresentTarget` int(11) NOT NULL,
  `formationHeavy` varchar(10) NOT NULL DEFAULT 'Line',
  `formationLight` varchar(10) NOT NULL DEFAULT 'Phalanx',
  `formationCD` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Struktura tabulky `usermsg`
--

CREATE TABLE `usermsg` (
  `id` int(11) NOT NULL,
  `toUserID` int(11) NOT NULL DEFAULT 0,
  `fromUserID` varchar(11) NOT NULL DEFAULT '0',
  `sentTime` int(11) NOT NULL DEFAULT 0,
  `subject` varchar(256) NOT NULL,
  `msg` blob NOT NULL,
  `viewed` int(11) NOT NULL DEFAULT 0,
  `replymsg` mediumblob DEFAULT NULL,
  `token` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Struktura tabulky `userquests`
--

CREATE TABLE `userquests` (
  `userID` int(8) NOT NULL,
  `currentQuest` int(11) DEFAULT 0,
  `questsCompleted` int(11) DEFAULT 0,
  `questAccomplished` int(11) DEFAULT 0,
  `userObjectives` text NOT NULL DEFAULT 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}',
  `timeLeft` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Vypisuji data pro tabulku `userquests`
--

INSERT INTO `userquests` (`userID`, `currentQuest`, `questsCompleted`, `questAccomplished`, `userObjectives`, `timeLeft`) VALUES
(18056943, 0, 0, 0, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:259200;}', 0),
(27613408, 0, 2, 0, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}', 0),
(52146370, 0, 0, 0, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}', 0),
(78145239, 0, 0, 0, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}', 0),
(79120856, 0, 0, 0, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}', 0),
(79168025, 0, 0, 0, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}', 0),
(84216073, 0, 0, 0, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}', 0),
(95617823, 0, 0, 0, 'a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}', 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `userresearch`
--

CREATE TABLE `userresearch` (
  `userID` int(8) NOT NULL,
  `researchDmg` int(11) NOT NULL DEFAULT 0,
  `researchHp` int(11) NOT NULL DEFAULT 0,
  `researchShd` int(11) NOT NULL DEFAULT 0,
  `researchSpeed` int(11) NOT NULL DEFAULT 0,
  `researchSubspace` int(11) NOT NULL DEFAULT 0,
  `researchTime` int(11) NOT NULL DEFAULT 0,
  `currentResearch` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Struktura tabulky `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `userID` int(11) NOT NULL DEFAULT 0,
  `sessionID` varchar(25) NOT NULL DEFAULT current_timestamp(),
  `Username` tinytext NOT NULL,
  `ingameNick` text NOT NULL,
  `Email` tinytext NOT NULL,
  `Password` longtext NOT NULL,
  `newsletter` tinyint(1) NOT NULL DEFAULT 0,
  `regDate` int(11) NOT NULL DEFAULT 0,
  `registerIPreal` varchar(45) NOT NULL DEFAULT 'noIP',
  `registerIPproxy` varchar(45) NOT NULL DEFAULT 'noIP',
  `playTime` int(11) NOT NULL,
  `lastUpdate` int(11) NOT NULL DEFAULT 0,
  `credits` int(11) NOT NULL DEFAULT 500000,
  `hyperid` int(11) NOT NULL DEFAULT 50000,
  `natium` int(11) NOT NULL DEFAULT 100,
  `userclan` text NOT NULL DEFAULT 'none',
  `clanJoinDate` int(11) NOT NULL,
  `nickChangeTime` int(18) NOT NULL DEFAULT 0,
  `defenseCooldown` int(11) NOT NULL DEFAULT 0,
  `loginBonusDay` int(2) NOT NULL DEFAULT 1,
  `claimed` int(1) NOT NULL,
  `blockedUsers` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`adminKey`);

--
-- Klíče pro tabulku `blockedusers`
--
ALTER TABLE `blockedusers`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `croncontrol`
--
ALTER TABLE `croncontrol`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `dailybonus`
--
ALTER TABLE `dailybonus`
  ADD PRIMARY KEY (`day`);

--
-- Klíče pro tabulku `gamelogs`
--
ALTER TABLE `gamelogs`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `loginlogs`
--
ALTER TABLE `loginlogs`
  ADD PRIMARY KEY (`userID`);

--
-- Klíče pro tabulku `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`newsID`);

--
-- Klíče pro tabulku `notactiveusers`
--
ALTER TABLE `notactiveusers`
  ADD PRIMARY KEY (`userID`);

--
-- Klíče pro tabulku `profileimg`
--
ALTER TABLE `profileimg`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `pwdreset`
--
ALTER TABLE `pwdreset`
  ADD PRIMARY KEY (`pwdResetId`);

--
-- Klíče pro tabulku `quests`
--
ALTER TABLE `quests`
  ADD PRIMARY KEY (`questID`),
  ADD KEY `questID` (`questID`);

--
-- Klíče pro tabulku `research`
--
ALTER TABLE `research`
  ADD PRIMARY KEY (`level`);

--
-- Klíče pro tabulku `round2`
--
ALTER TABLE `round2`
  ADD PRIMARY KEY (`userID`);

--
-- Klíče pro tabulku `round3`
--
ALTER TABLE `round3`
  ADD PRIMARY KEY (`userID`);

--
-- Klíče pro tabulku `shop`
--
ALTER TABLE `shop`
  ADD UNIQUE KEY `shipID` (`shipID`);

--
-- Klíče pro tabulku `systems1`
--
ALTER TABLE `systems1`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `userbase`
--
ALTER TABLE `userbase`
  ADD PRIMARY KEY (`userID`);

--
-- Klíče pro tabulku `userclans`
--
ALTER TABLE `userclans`
  ADD PRIMARY KEY (`clanID`);

--
-- Klíče pro tabulku `userfleet`
--
ALTER TABLE `userfleet`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `usermovement`
--
ALTER TABLE `usermovement`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `usermsg`
--
ALTER TABLE `usermsg`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `userquests`
--
ALTER TABLE `userquests`
  ADD PRIMARY KEY (`userID`);

--
-- Klíče pro tabulku `userresearch`
--
ALTER TABLE `userresearch`
  ADD PRIMARY KEY (`userID`);

--
-- Klíče pro tabulku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `blockedusers`
--
ALTER TABLE `blockedusers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `dailybonus`
--
ALTER TABLE `dailybonus`
  MODIFY `day` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pro tabulku `gamelogs`
--
ALTER TABLE `gamelogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
-- AUTO_INCREMENT pro tabulku `profileimg`
--
ALTER TABLE `profileimg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pro tabulku `pwdreset`
--
ALTER TABLE `pwdreset`
  MODIFY `pwdResetId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pro tabulku `systems1`
--
ALTER TABLE `systems1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=823;

--
-- AUTO_INCREMENT pro tabulku `userfleet`
--
ALTER TABLE `userfleet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pro tabulku `usermovement`
--
ALTER TABLE `usermovement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222;

--
-- AUTO_INCREMENT pro tabulku `usermsg`
--
ALTER TABLE `usermsg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=930;

--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
