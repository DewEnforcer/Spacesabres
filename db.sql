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

--
-- Vypisuji data pro tabulku `admins`
--

INSERT INTO `admins` (`adminKey`, `Username`, `Password`, `Email`, `sessionID`, `sessionIDExpire`, `IP`) VALUES
(';y8}m+@hJQ2nbYanq:!#w]`]MHd8Rc', 'DewEnforcer', '$2y$10$OmMAKR5SgJ0VNlFyvTgsUuhiC3rbKObUkhS9.BXpCevoBXAOHDXu2', 'pat.mad@seznam.cz', '2ord1kqp9630', 1563233529, '');

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
-- Vypisuji data pro tabulku `croncontrol`
--

INSERT INTO `croncontrol` (`execute`, `id`) VALUES
(0, 1);

-- --------------------------------------------------------

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
-- Vypisuji data pro tabulku `loginlogs`
--

INSERT INTO `loginlogs` (`userID`, `time`, `loginIP`, `loginIPproxy`) VALUES
(18056943, 1570186639, '::1', 'No proxy IP detected.'),
(27613408, 1569335641, '::1<br>', ''),
(79120856, 1570023766, '::1', 'No proxy IP detected.');

-- --------------------------------------------------------

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
-- Vypisuji data pro tabulku `notactiveusers`
--

INSERT INTO `notactiveusers` (`userID`, `Username`, `Email`, `Password`, `regDate`, `IPreal`, `IPproxy`, `idActivate`, `idExpire`, `coordsX`, `coordsY`, `mapLocation`) VALUES
(30214576, 'Libec', 'axbox@seznam.cz', '$2y$10$B/xKp8Knv3nsg10v4XX3ZuwYBixw2qSXxEj2PUcp0TtYYYQjta8Ii', 1563896864, '', '', 'cb2ecc44e86de62122e7b84564f3faa9', 1563983264, 84, 92, 1),
(24610739, 'PlanetTest10', 'piranha0863@gmail.com', '$2y$10$k3p8yrNSym6aUg7Mq7zFM.PO4EJkwn64QZqCJfw6pScHoAmB4NUQ6', 1569338987, '::1', 'No proxy IP detected.', '656e3db18ed873963ced9ebfc968d1f0', 1569425387, 679, 166, 73);

-- --------------------------------------------------------

--
-- Struktura tabulky `player_information`
--

CREATE TABLE `player_information` (
  `playerID` int(255) NOT NULL,
  `userID` int(255) NOT NULL,
  `TokenId` bigint(20) NOT NULL DEFAULT 4,
  `shipId` smallint(11) NOT NULL DEFAULT 10,
  `factionId` smallint(1) NOT NULL DEFAULT 0,
  `mapId` smallint(30) NOT NULL DEFAULT 1,
  `x` varchar(5) NOT NULL DEFAULT '6800',
  `y` varchar(5) NOT NULL DEFAULT '10600',
  `settings` text NOT NULL,
  `rank` int(11) NOT NULL DEFAULT 1 COMMENT '1: Basic Space Pilot',
  `premium` int(1) NOT NULL DEFAULT 1 COMMENT '0: No, Premium | 1: Yes, Premium',
  `level` int(11) NOT NULL DEFAULT 1,
  `clanID` int(11) NOT NULL DEFAULT 0,
  `uridium` varchar(255) CHARACTER SET utf16 NOT NULL DEFAULT '1000000',
  `credit` varchar(255) CHARACTER SET utf16 NOT NULL DEFAULT '1000000',
  `ldrones` int(255) NOT NULL DEFAULT 1,
  `Resulutions` int(255) NOT NULL DEFAULT 1,
  `design` int(255) NOT NULL DEFAULT 0,
  `exp` bigint(255) NOT NULL DEFAULT 0,
  `honor` bigint(255) NOT NULL DEFAULT 0,
  `RexLevel` int(11) NOT NULL DEFAULT 1,
  `RexExperience` int(11) NOT NULL DEFAULT 0,
  `RexBaseExperience` int(11) NOT NULL DEFAULT 25000,
  `c1speed` int(11) NOT NULL DEFAULT 15,
  `c2speed` int(11) NOT NULL DEFAULT 5,
  `c1lasers` int(11) NOT NULL DEFAULT 10,
  `c2lasers` int(11) DEFAULT 5,
  `c1shieldd` int(11) NOT NULL DEFAULT 6,
  `c2shieldd` int(11) NOT NULL DEFAULT 11,
  `c1shield` int(11) NOT NULL DEFAULT 0,
  `c2shield` int(11) NOT NULL DEFAULT 10,
  `designID` int(32) NOT NULL DEFAULT 1,
  `key2` int(32) NOT NULL DEFAULT 5,
  `apis` int(32) NOT NULL DEFAULT 0,
  `zeus` int(32) NOT NULL DEFAULT 0,
  `datelastonline` date DEFAULT NULL,
  `chatban` int(11) NOT NULL DEFAULT 0,
  `gameban` int(11) NOT NULL DEFAULT 0,
  `chatmod` int(11) NOT NULL DEFAULT 0,
  `wave1` int(11) NOT NULL DEFAULT 1,
  `wave2` int(11) NOT NULL DEFAULT 1,
  `wave3` int(11) NOT NULL DEFAULT 1,
  `wave4` int(11) NOT NULL DEFAULT 1,
  `life1` int(11) NOT NULL DEFAULT 0,
  `life2` int(11) NOT NULL DEFAULT 0,
  `life3` int(11) NOT NULL DEFAULT 0,
  `life4` int(11) NOT NULL DEFAULT 0,
  `ring1` int(11) NOT NULL DEFAULT 0,
  `ring2` int(11) NOT NULL DEFAULT 0,
  `ring3` int(11) NOT NULL DEFAULT 0,
  `ring4` int(11) NOT NULL DEFAULT 0,
  `connected` int(11) NOT NULL DEFAULT 0,
  `IP` text NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `show_config` int(11) NOT NULL DEFAULT 1,
  `PtitleID` int(11) NOT NULL,
  `Profilstat` int(11) NOT NULL,
  `blockuser` int(11) NOT NULL,
  `clan_function` varchar(40) NOT NULL DEFAULT '0|0|0|0|' COMMENT '1 = Accepter/Refuser; 2 = Kick; 3 = Diplomatie; 4 = Modifier clan'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

--
-- Vypisuji data pro tabulku `player_information`
--

INSERT INTO `player_information` (`playerID`, `userID`, `TokenId`, `shipId`, `factionId`, `mapId`, `x`, `y`, `settings`, `rank`, `premium`, `level`, `clanID`, `uridium`, `credit`, `ldrones`, `Resulutions`, `design`, `exp`, `honor`, `RexLevel`, `RexExperience`, `RexBaseExperience`, `c1speed`, `c2speed`, `c1lasers`, `c2lasers`, `c1shieldd`, `c2shieldd`, `c1shield`, `c2shield`, `designID`, `key2`, `apis`, `zeus`, `datelastonline`, `chatban`, `gameban`, `chatmod`, `wave1`, `wave2`, `wave3`, `wave4`, `life1`, `life2`, `life3`, `life4`, `ring1`, `ring2`, `ring3`, `ring4`, `connected`, `IP`, `width`, `height`, `show_config`, `PtitleID`, `Profilstat`, `blockuser`, `clan_function`) VALUES
(1, 27613408, 4, 10, 0, 1, '6800', '10600', '', 1, 1, 1, 0, '1000000', '1000000', 1, 1, 0, 0, 0, 1, 0, 25000, 15, 5, 10, 5, 6, 11, 0, 10, 1, 5, 0, 0, NULL, 0, 0, 0, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, '::1', 1920, 966, 1, 0, 0, 0, '0|0|0|0|');

-- --------------------------------------------------------

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
-- Vypisuji data pro tabulku `pwdreset`
--

INSERT INTO `pwdreset` (`pwdResetId`, `pwdResetEmail`, `pwdResetSelector`, `pwdResetToken`, `pwdResetExpires`) VALUES
(20, 'pat.mad@seznam.cz', '98acc71f4efe4d99', '$2y$10$HUq.czOrqPgp.G5slya5TO/2H/lFksNfiHdMFoovy7/qS2t8PTTIS', '1567508936');

-- --------------------------------------------------------

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
-- Vypisuji data pro tabulku `userbase`
--

INSERT INTO `userbase` (`userID`, `inventoryMod1`, `inventoryMod2`, `inventoryMod3`, `inventoryMod4`, `coreStatus`, `coreHealth`, `coreShields`, `slot1`, `slot2`, `slot3`, `slot4`, `slot5`, `slot6`, `slot7`, `slot8`, `slot9`, `slot10`) VALUES
(18056943, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(27613408, 31, 95, 2, 10, 1, 6000000, 3000000, 1, 2, 3, 0, 0, 0, 4, 1, 1, 1),
(52146370, 0, 0, 0, 0, 1, 1000000, 500000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(78145239, 0, 0, 0, 0, 1, 1000000, 500000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(79120856, 10, 3, 1, 10, 1, 1000000, 500000, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2),
(79168025, 10, 0, 0, 0, 1, 1000000, 500000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(84216073, 0, 0, 0, 0, 1, 1000000, 500000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(95617823, 5, 3, 1, 1, 1, 1000000, 500000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

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
-- Vypisuji data pro tabulku `userclans`
--

INSERT INTO `userclans` (`clanID`, `createDate`, `clanName`, `clanTag`, `clanLeader`, `clanContact`, `clanDetail`, `clanLog`, `clanApps`, `clanAppDesc`, `clanDiplo`, `clanDiploReq`, `clanDiploPending`, `clanImgID`, `clanImgStatus`, `clanMembers`, `clanMembersPerms`, `totalMembers`, `totalPoints`) VALUES
('7610952348', 1570187475, 'Bruhmomentum', 'BRUH', 'Dewtest', 'None', 'We report every bruh moment that is found in this game', 'a:0:{}', 'a:0:{}', 'a:0:{}', 'a:0:{}', 'a:0:{}', 'a:0:{}', '0', 0, 'a:1:{i:0;s:8:\"79120856\";}', 'a:1:{i:0;a:9:{i:0;i:1;i:1;i:1;i:2;i:1;i:3;i:1;i:4;i:1;i:5;i:1;i:6;i:1;i:7;i:1;i:8;i:5;}}', 1, 0),
('8037546129', 1568740647, 'Dewbois', 'DEWY', 'DewEnforcerTest', 'None', 'We are the defenders of all our friends and shall defeat all those who oppose us!', 'a:4:{i:0;s:99:\"<div>Commander PlanetTest1 has been kicked from the alliance by DewEnforcerTest. (04.10.2019)</div>\";i:1;s:70:\"<div>Commander PlanetTest1 has joined the alliance. (04.10.2019)</div>\";i:2;s:54:\"<div>Dewtest has left the alliance. (02.10.2019)</div>\";i:3;s:66:\"<div>Commander Dewtest has joined the alliance. (22.09.2019)</div>\";}', 'a:0:{}', 'a:0:{}', 'a:0:{}', 'a:0:{}', 'a:0:{}', '0', 0, 'a:1:{i:0;s:8:\"27613408\";}', 'a:1:{i:0;a:9:{i:0;i:1;i:1;i:1;i:2;i:1;i:3;i:1;i:4;i:1;i:5;i:1;i:6;i:1;i:7;i:1;i:8;i:5;}}', 50, 135613);

-- --------------------------------------------------------

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
-- Vypisuji data pro tabulku `userfleet`
--

INSERT INTO `userfleet` (`id`, `userID`, `admin`, `rank`, `leaderboardPos`, `pageCoordsX`, `pageCoordsY`, `mapLocation`, `formationHeavy`, `formationLight`, `dock1`, `dock2`, `dock3`, `dock4`, `dock5`, `dock6`, `dock7`, `dock8`, `dock9`, `dock10`, `temporaryDock`, `dockAmount`, `equipment`, `dockIncrement`, `templates`, `fuel`, `destroyedPoints`, `fleetPoints`, `destroyedShips`, `destroyedHornets`, `destroyedSpacefires`, `destroyedStarhawks`, `destroyedPeacemakers`, `destroyedCenturions`, `destroyedNathalis`, `battlesWon`, `battlesLost`, `battlesDraw`, `battlesTotal`) VALUES
(1, 18056943, 0, 8, 6, 165, 46, 1, 'Line', 'Phalanx', 0x613a313a7b693a303b613a363a7b733a363a22686f726e6574223b613a303a7b7d733a393a22737061636566697265223b613a303a7b7d733a383a22737461726861776b223b613a303a7b7d733a31303a2270656163656d616b6572223b613a303a7b7d733a393a2263656e747572696f6e223b613a303a7b7d733a383a226e617468616c6973223b613a303a7b7d7d7d, '', '', '', '', '', '', '', '', '', '', 1, 'a:4:{s:6:\"lasers\";a:3:{s:3:\"low\";i:0;s:6:\"medium\";i:0;s:4:\"beam\";i:0;}s:7:\"rockets\";i:0;s:6:\"energy\";i:0;s:10:\"hyperspace\";a:3:{s:3:\"low\";i:0;s:6:\"medium\";i:0;s:4:\"high\";i:0;}}', 0, '', 10000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 27613408, 0, 11, 1, 66, 405, 1, 'Wedge', 'Pincer', 0x1f8b080000000000000ae5dd4b8e6cc77945e1b9dc011819f1bf224aa3a12502222cdb82483704417377f0b26102f21ac16a142a4fd679d56eaddef7c3d7fa7c3e5ffff8e9ebf3871fbee6eb1f3f7ff5d7b7fffa9ffffcf71ffff6ed0f3f7fe5d7b739b37efdf8beffcb0f3ffff8b79fbfbd33f3b74b7efeda5fdff6fbf34f5febf707fbf707f17f07fffcf93de3dbdffefb8ffff1e32fdfeff31efddb773ffff9a71ffff2a7efdfaddfdfbbbe5fb43e5fdffefcf7bfbe87fff5873ffef82f27edef27d5bbcb2f3ffcf2fbf77bafd59fefbfde4f7dbe7fdc7ff8d3d7da9fdf0ee21d7cfe2d7ebdfcfdabbfbc47fcf6fafffcf5927f1de4a7afb7c6168eb1698c108e1134460ac7481aa38463148dd1c2319ac618e11843631ce11887c6b8c2312e8c713ec231d6ff97a1dfd758c635a8418fb1411745e83146e8a20a3dc60a5d94a1c798a18b3af4183b7451881e63882e2ad1632cd145297a8c29baa845afb14537b5e835b6e8a616bdc616ddd4a2d7d8a29b5af41a5b74538b5e638b6e6ad16b6cd14d2d7a8d2dbaa945afb14537b5e835b6e88616bd1f638b06b4e8fd185b34a045efc7d8a2012d7a3fc6160d68d1fb31b668408bde8fb145035af47e8c2d1ad0a2f7636cd18016bd1f638b06b4e8fd185b34a84597b145935a74195b34a94597b145935a74195b34a94597b145935a74195b34a94597b145935a74195b34a94597b145935a74195b34a945b7b1458b5a741b5bb4a845b7b1458b5a741b5bb4a845b7b1458b5a741b5bb4a845b7b1458b5a741b5bb4a845b7b1458b5a741b5bb4a845c3d8a24d2d1ac6166d6ad130b668538b86b1459b5a348c2ddad4a2616cd1a6160d638b36b568185bb4a945c3d8a24d2d1ac6166d6ad134b6e8508ba6b145875a348d2d3ad4a2696cd1a1164d638b0eb5681a5b74a845d3d8a2432d9ac6161d6ad134b6e8508ba6b145875ab48c2d7aa845cbd8a2875ab48c2d7aa845cbd8a2875ab48c2d7aa845cbd8a2875ab48c2d7aa845cbd8a2875ab48c2d7aa845cbd8a2875ab48d2d7aa945dbd8a2975ab48d2d7aa945dbd8a2975ab48d2d7aa945dbd8a2975ab48d2d7aa945dbd8a2975ab48d2d7aa945dbd8a2975a748c2dba3e14a3638cd1f5a11a558a4beb4339aa3497d6877a54a92ead0f05a9d25d5a1f2a52a5bcb43e94a44a7b697da84995fad2fa50942afda5f5a12a750a4c44305d29c14455ea34980861ba4e848914a6eb54988861ba4e86891ca6eb74980862ba4e888924a6eb94988862ba4e8a892ca6abb49816614c5789312dd298ae52635ac4315d25c7b4c863ba4a8f6911c8749520d32291e92a45a64524d355924c8b4ca6ab349916a14c5789322d5099ceaf570ae70096e9cda1ac527099de1cca2a0598e9cda1ac529099de1cca2a059ae9cda1ac52b099de1cca2a059ce9cda1ac52d099de1cca2a059ee9cda1ac52f099ce47e9332d009ade1cca2a05a1e9cda1ac52209ade1cca2a05a3e9cda1ac52409ade1cca2a05a5e9cda1ac52609ade1cca2a05a7e9cda1ac52809ade1cca2a05a9e97c9452d302aae9cda1ac52b09ade1cca2a05ace9cda1ac52d09ade1cca2a05aee9cda1ac52f09ade1cca2a05b0e9cda1ac52109bde1cca2a05b2e9cda1ac52309bce4769362d409bde1cca2a05b5e9cda1ac52609bde1cca2a05b7e9cda1ac52809bde1cca2a05b9e9cda1ac52a09bde1cca2a05bbe9cda1ac52c09bde1cca2a05bde97c947ad302bee9cda1ac52f09bde1cca2a05c0e9cda1ac52109cde1cca2a05c2e9cda1ac52309cde1cca2a05c4e9cda1ac52509cde1cca2a05c6e9cda1ac52709cce47e9382d809cde1cca2a05c9e9cda1ac52a09cde1cca2a05cbe9cda1ac52c09cde1cca2a05cde9cda1ac52e09cde1cca2a05cfe9cda1ac52009dde1cca2a05d1e97c94a2d302d2e9cda1ac52309dde1cca2a05d4e9cda1ac52509dde1cca2a05d6e9cda1ac52709dde1cca2a05d8e9cda1ac52909dde1cca2a05dae9cda1ac52b09dce47693bbd53680e63956eb09dde1cc62add603bbd398c55bac1767a7318ab7483edf4e63056e906dbe9cd61acd20db6d39bc358a51b6ca73787b14a37d84e6f0e63956eb09dce47693b6db09dde1cca2a05dbe9cda1ac52b09dde1cca2a05dbe9cda1ac52b09dde1cca2a05dbe9cda1ac52b09dde1cca2a05dbe9cda1ac52b09dde1cca2a05dbe97c94b6d306dbe9cda1ac52b09dde1cca2a05dbe9cda1ac52b09dde1cca2a05dbe9cda1ac52b09dde1cca2a05dbe9cda1ac52b09dde1cca2a05dbe9cda1ac52b29dd64759a5643b2da5edb4c9765a4adb6993edb494b6d326db69296da74db6d352da4e9b6ca7a5b49d36d94e4b693b6db29d96d276da643b2da5edb4c9765a4adb6993edb494b6d326db69296da74db6d352da4e9b6ca7a5b49d36d94e4b693b6db29d96d276da643b2da5edb4c9765a4adb6993edb494b6d326db69296da74db6d352da4e9b6ca7a5b49d36d94e4b693b6db29d96d276da643b2da5edb4c9765a4adb6993edb494b6d326db69296da74db6d352da4e9b6ca7a5b49d36d94e4b693b6db29d96d276da643b2da5edb4c9765a4adb6993edb494b6d326db69296da74db6d352da4e9b6ca7a5b49d36d94e4b693b6db29d96d276da643b2da5edb4c9765a4adb6993edb494b6d326db69296da74db6d352da4e9b6ca7a5b49d36d94e4b693b6db29d96d276da643b2da5edb4c9765a4adb6993edb494b6d326db69296da74db6d352da4e9b6ca7a5b49d36d94e4b693b6db29d96d276da643b2da5edb4c9765a4adb6993edb494b6d326db69296da74db6d352da4e9b6ca7a5b49d36d94e4b693b6db29d96d276da643b2da5edb4c9765a4adb6993edb494b6d326db69296da74db6d352da4e9b6ca7a5b49de24355aab49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca7a5b49d826ca777ad710eb29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d2760ab29db6d276ca0f55a9d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29db6d2764ab29de2a3ac52b29d42693b25d94ea1b49d926ca750da4e49b653286da724db2994b65392ed144adb29c9760aa5ed94643b85d2764ab29d42693b25d94ea1b49d926ca750da4e49b653286da724db2994b65392ed144adb29c9760aa5ed94643b85d2764ab29d42693b25d94ea1b49d926ca750da4e49b653286da724db2994b65392ed144adb29c9760aa5ed94643b85d2764ab29d42693b25d94ea1b49d926ca750da4e49b653286da724db2994b65392ed144adb29c9760aa5ed94643b85d2764ab29d42693b25d94ea1b49d926ca750da4e49b653286da724db2994b65392ed144adb29c9760aa5ed94643b85d2764ab29d42693b25d94ea1b49d926ca750da4e49b653286da724db2994b65392ed144adb29c9760aa5ed94643b85d2764ab29d42693b25d94ea1b49d926ca750da4e49b653286da724db2994b65392ed144adb29c9760aa5ed94643b85d2764ab29d42693b25d94ea1b49d926ca750da4e49b653286da724db2994b65392ed144adb29c9760aa5ed94643b85d2764ab29d42693b25d94ea1b49d926ca750da4e49b653286da724db2994b65392ed144adbe99d4e7318abb4c8760aa5ed54643b85d2762ab29d42693b15d94ea1b49d8a6ca750da4e45b653286da722db2994b65391ed144adba9c8760aa5ed54643b85d2762ab29d42693b15d94ea1b49d8a6ca750da4e45b653286da722db2994b65391ed144adba9c8760aa5ed54643b85d2762ab29d42693b15d94ea1b49d8a6ca750da4e45b653286da722db2994b65391ed144adba9c8760aa5ed54643b85d2762ab29d42693b15d94ea1b49d8a6ca7fc28ab946ca754da4e45b6532a6da722db2995b65391ed944adba9c8764aa5ed54643ba5d2762ab29d52693b15d94ea9b49d8a6ca754da4e45b6532a6da722db2995b65391ed944adba9c8764aa5ed54643ba5d2762ab29d52693b15d94ea9b49d8a6ca754da4e45b6532a6da722db2995b65391ed944adba9c8764aa5ed54643ba5d2762ab29d52693b15d94ea9b49d8a6ca754da4e45b6532a6da722db2995b65391ed944adba9c8764aa5ed54643ba5d2762ab29d52693b15d94ea9b49d8a6ca754da4e45b6532a6da722db2995b65391ed944adba9c8764aa5ed54643ba5d2762ab29d52693b15d94ea9b49d8a6ca754da4e45b6532a6da722db2995b65391ed944adba9c8764aa5ed54643ba5d2762ab29d52693b15d94ea9b49d8a6ca754da4e45b6532a6da722db2995b65391ed944adba9c8764aa5ed54643ba5d2762ab29d52693b15d94ea9b49d8a6ca754da4e45b6532a6da722db2995b65391ed944adba9c8764aa5ed54643ba5d2762ab29d52693b15d94ea9b49d8a6ca754da4e45b6532a6da722db2995b65391ed944adba9c8764aa5ed54643ba5d276faf54298c358a54db6532a6da726db2995b65393ed944adba9c9764aa5edd4643ba5d2766ab29d52693b35d94ea9b49d9a6ca754da4e4db6532a6da726db2995b65393ed944adba9c9764aa5edd4643ba5d2766ab29d52693b35d94ea9b49d9a6ca754da4e4db6532a6da726db2995b65393ed944adba9c9764aa5edd4643ba5d2766ab29d52693b35d94ea9b49d9a6ca754da4e4db6532a6da726db2995b65393ed944adba9c9764aa5edd4643bbdfb18e720dba994b65393ed544adba9c9762aa5edd4643b95d2766ab29d4a693b35d94ea5b49d9a6ca752da4e4db653296da726dba994b65393ed544adba9c9762aa5edd4643b95d2766ab29d4a693b35d94ea5b49d9a6ca752da4e4db653296da726dba994b65393ed544adba9c9762aa5edd4643b95d2766ab29d4a693b35d94ea5b49d9a6ca752da4e4db653296da726dba994b65393ed544adba9c9762aa5edd4643b95d2766ab29d4a693b35d94ea5b49d9a6ca752da4e4db653296da726dba994b65393ed544adba9c9762aa5edd4643b95d2766ab29d4a693b35d94ea5b49d9a6ca752da4e4db653296da726dba994b65393ed544adba9c9762aa5edd4643b95d2766ab29d4a693b35d94ea5b49d9a6ca752da4e4db653296da726dba994b65393ed544adba9c9762aa5edd4643b95d2766ab29d4a693b35d94ea5b49d9a6ca752da4e4db653296da726dba994b65393ed544adba9c9762aa5edd4643b95d2766ab29d4a693b35d94ea5b49d9a6ca752da4e4db653296da726dba994b65393ed544adba9c9762aa5edd4643b95d2766ab29d4a693b35d94ea5b49de64355aab49d866ca752da4e43b653296da721dba994b6d390ed544adb69c8762aa5ed34643b95d2761ab29d4a693b0dd94ea5b49d866ca752da4e43b653296da721dba994b6d390ed544adb69c8762aa5ed34643b95d2761ab29d4a693b0dd94ea5b49d866ca752da4e43b653296da721dba994b6d390ed544adb69c8762aa5ed34643b95d2761ab29d4a693b0dd94ea5b49d866ca752da4e43b653296da721dba994b6d390ed544adb69c876faf58ec239c8766aa5ed34643bb5d2761ab29d5a693b0dd94eadb49d866ca756da4e43b6532b6da721dba995b6d390edd44adb69c8766aa5ed34643bb5d2761ab29d5a693b0dd94eadb49d866ca756da4e43b6532b6da721dba995b6d390edd44adb69c8766aa5ed34643bb5d2761ab29d5a693b0dd94eadb49d866ca756da4e43b6532b6da721dba995b6d390edd44adb69c8766aa5ed34643bb5d2761ab29d5a693b0dd94eadb49d866ca756da4e43b6532b6da721dba995b6d390edd44adb69c8766aa5ed34643bb5d2761ab29d5a693b0dd94eadb49d866ca756da4e43b6532b6da721dba995b6d390edd44adb69c8766aa5ed34643bb5d2761ab29d5a693b0dd94eadb49d866ca756da4e43b6532b6da721dba995b6d390edd44adb69c8766aa5ed34643bb5d2761ab29d5a693b0dd94eadb49d866ca756da4e43b6532b6da721dba995b6d390edd44adb69c8766aa5ed34643bb5d2761ab29d5a693b0dd94eadb49d866ca756da4e43b6532b6da721dba995b6d390edd44adb69c8766aa5ed34643bb5d2761ab29d5a693b0dd94eadb49d866ca756da4e43b6532b6da7f3a12a55da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca756da4e876ca7f928ab946ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4e876ca751da4ef74355aab49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ea3b49d2ed94ee7a3ac52b29d8ed276ba643b1da5ed74c9763a4adbe992ed7494b6d325dbe9286da74bb6d351da4e976ca7a3b49d2ed94e47693b5db29d8ed276ba643b1da5ed74c9763a4adbe992ed7494b6d325dbe9286da74bb6d351da4e976ca7a3b49d2ed94e47693b5db29d8ed276ba643b1da5ed74c9763a4adbe992ed7494b6d325dbe9286da74bb6d351da4e976ca7a3b49d2ed94e47693b5db29d8ed276ba643b1da5ed74c9763a4adbe992ed7494b6d325dbe9286da74bb6d351da4e976ca7a3b49d2ed94e47693b5db29d8ed276ba643b1da5ed74c9763a4adbe992ed7494b6d325dbe9286da74bb6d351da4e976ca7a3b49d2ed94e47693b5db29d8ed276ba643b1da5ed74c9763a4adbe992ed7494b6d325dbe9286da74bb6d351da4e976ca7a3b49d2ed94e47693b5db29d8ed276ba643b1da5ed74c9763a4adbe992ed7494b6d325dbe9286da74bb6d351da4e976ca7a3b49d2ed94e47693b5db29d8ed276ba643b1da5ed74c9763a4adbe992ed7494b6d325dbe9286da74bb6d351da4e976ca7a3b49d2ed94e47693b5db29d8ed276ba643b1da5ed74c9763a4adbe992ed7494b6d325dbe9286da74bb6d311da4efffc5f69bcc0cc37220400, 0x1f8b080000000000000addd64b6e53411085e1bd7801a8bbaafae9d598c452220244b119a0287be7e6da1217c18f8009ca1958edee5bfd3835fa0e73f4f97c3fd3fe30db7c3ecd3a779fbe7c7c7f7cdaedef67f3d2f6ebdac3e1747c3aed96aab8949fa6cd9de5d7aabc9dd876e2df272fa7e5fcddd3e79b0fc7f37a4e9acf97b5d3ddfdf1e1765dcbdbb3cbba29a7b9bbfbfab85cfe78b839fe54646b51594e391fcedbf72dcfaa691d965f49eb5fdbdfce6ce932f16592def9ebf698bbf372c5e5f92faf5ba019fdb7cd48db66a46d33d2b619e9bf34c3feb51946cd18d08ceb85d7d1aea3afe3df04bfd663e6ebf75fc6fdf1057f16d421684d6241838266b1a085829a58d04a415d2c68a3a02116b453d022167450d02a163413b12a11ebcd26253f55f2d39b4d4a38aa6a38caa4a3a6a6a34c3c6a6a3ccae4a3a6e6a34c406a6a40ca24a4a626a44c446a6a44ca64a4a6662423233535231919a9a919c9c8484dcd484646ea6a463232525733929191ba9a918c8cd4d58c6464a4ae662423237535231919a9ab19c9c9485dcd484e46ea6a467232525733929391869a919c8c34d48ce464a4a1662427230d3523391969a819c9c94843cd484e461a6a460a32d25033529091869a91828c34d48c1460a4486a460a3052243523051829929a91028c1449cd4801468aa466a4002345523352809122a919a9809122a919a9809122a919a9809122a919a99091b29a910a1929ab19a99091b29a910a1929ab19a99091b29a910a1929ab19a99091b29a912a1929ab19a99291b29a912a1929ab19a992914ccd48958c646a46aa64245333522523999a912a19c9d48c54c948a666a44a46323523353292a919a991914ccd488d8c646a466a64245733522323b99a911a19c9d58cd4c848ae66a44646723523353292ab19a991915ccd489d8ce46a46ea64245733522723b99a913a1929d48cd4c948a166a44e460a3523753252a819a9939142cd489d8c146a46ea64a45033d22023859a91061929d48c34c848a166a441462a6a461a64a4a266a441462a6a461a64a4a266a441462a5a467af90674e20c67d9590000, 0x1f8b080000000000000a4bb432b0aaae050020e853a206000000, 0x1f8b080000000000000aed91c10ec2200c86df850730802b73ddd3e046b2c5a9cb9807b3ecdd2d60e27ff1ecc51b5f69e957ead9f236b26e3dd7bc4576ac6e8feb392caa1db9b6e4da1c9b7c0c4b54926574c98f52a8ac496906c1221c112a0442700835c209a1f9c01e45572df7ee12d6ac2556251687314c7d8e393425342534253425342534a5dc55c657c37396cf987d17f28760179b934834565fc4aa722d8f6aadb38234d3e56cd3eceff3b1ed591f4c2aaf58add2a268eda9e4cb729aff727ebc9cfd0504e3f07840030000, 0x1f8b080000000000000aed93310e83300c45efe2035449c0819ad3a4100954da2242870a71f79a04a91ecaccc2966ffdc4cffe8aa39ce68e54e5a8a039902578be1f373f42d5516130af62ad77c18f01d8a555f207320446af362d85912293229702a5b052145294525c7f62098c0be3abbefb29623155aa85b6f37d136b5692a22445498a921425294a528c5d797c683f032f6370b58f0b915d4c3421634c2e816debe547955211819ba97436ebecdb39ab1a5217bd5ecf09266e91b096f5ca4e38c519cee1e1983fe14417e753c219d0e101653bbfc7aa339c83c359be0190ed287e060000, 0x1f8b080000000000000a4bb432b0aaae050020e853a206000000, 0x1f8b080000000000000a4bb432b0aaae050020e853a206000000, 0x1f8b080000000000000a4bb432b0aaae050020e853a206000000, 0x1f8b080000000000000a4bb432b0aaae050020e853a206000000, 0x1f8b080000000000000a4bb432b0aaae050020e853a206000000, 0x1f8b080000000000000add904b0e83300c44efe20354c6102a4d4e934224507f08d34585b87b9d50a9e9a217e82a99d1f88dec00c13a827dc011aba205dd1ed7539cc92b1a9093ba495ff32f41e3ac6449c6baa9e569be77e7b864afd9316a3c9296fc88aa14528afa23768e0e63bcf405bb62d0f09cac6f0a5d4c7e55f2250f3a1b5cc257bdb532e7c7ef52de16e7da1e7c1097666db5c5f894635bcaff3e80fbff036c2fab2010c808020000, 10, 'a:4:{s:6:\"lasers\";a:3:{i:19;i:0;i:20;i:404;i:21;i:0;}s:7:\"rockets\";a:1:{i:26;i:98;}s:7:\"shields\";a:1:{i:25;i:164;}s:10:\"hyperspace\";a:3:{i:22;i:98;i:23;i:10;i:24;i:0;}}', 8871, 0x1f8b080000000000000a4bb432b3aaceb432b04eb432b0aaaecdb43284b38c802c13982494516c6564a56464a8640d5288c43142e618233818061a229b630a56608c2e6c0414868a43349ac059a650562d00e1f068b4b8000000, 805451, 529819, 5975, 155428, 93451, 32612, 15841, 9280, 3266, 978, 86, 5, 356, 459),
(3, 52146370, 0, 7, 8, 539, 349, 1, 'Line', 'Phalanx', 0x613a303a7b7d, '', '', '', '', '', '', '', '', '', '', 1, 'a:4:{s:6:\"lasers\";a:3:{s:3:\"low\";i:0;s:6:\"medium\";i:0;s:4:\"beam\";i:0;}s:7:\"rockets\";i:0;s:6:\"energy\";i:0;s:10:\"hyperspace\";a:3:{s:3:\"low\";i:0;s:6:\"medium\";i:0;s:4:\"high\";i:0;}}', 0, '', 10000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, 78145239, 0, 6, 7, 606, 156, 1, 'Line', 'Phalanx', 0x613a303a7b7d, '', '', '', '', '', '', '', '', '', '', 1, 'a:4:{s:6:\"lasers\";a:3:{s:3:\"low\";i:0;s:6:\"medium\";i:0;s:4:\"beam\";i:0;}s:7:\"rockets\";i:0;s:6:\"energy\";i:0;s:10:\"hyperspace\";a:3:{s:3:\"low\";i:0;s:6:\"medium\";i:0;s:4:\"high\";i:0;}}', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(5, 79120856, 0, 9, 2, 95, 157, 1, 'Echelon', 'Turtle', 0x1f8b080000000000000aed98d16a84301045ff251f5032a3461dbfc6ee0656ba6d17631f16d97f6fd4d0dacd856da14f21820cc464e2392fded80b17320fa2bb5e6a999d18516f1fafcf7654dd2075b70e9c7b6747a7fc142df3cdf9896a7c3fbcd8691d2bb7f5db4da172a8c55ab735ee34d8f371d787b4a8d3f5e27b5ffa835dc6e9bb977f5ef93553ff6017d2fe5a773a8a7ee26a59588a9a7c5fb5355ae623ba26113a86746d227405a4239d085e89f12811bc0ae371227806e31589e0d518af4c04afc1785522782dc63389e011ce2c944c6841a9c5098ba246dd33fab77a08b47c6cb65a856a42ad436d426d770276d2be44eea498bfeffb5f12cd0f8974e7b0581de26c445138cafeb03f9cbe384a5fd91ff687e31d47f12efbc3fe707ee4283f667fd81f0ea81c05d4ec0ffbc30998a3049cfd617f38627314b1b33fec0f67788e327cf607fd313e23707446c8feb03ffce794a35fa7d91ff687cf1f9ccf1fbff177fb04c36a44539e180000, 0x1f8b080000000000000a4bb432b0aaae050020e853a206000000, '', '', '', '', '', '', '', '', 0x1f8b080000000000000a4bb432b0aaae050020e853a206000000, 2, 'a:5:{s:6:\"lasers\";a:3:{i:19;i:0;i:20;i:0;i:21;i:50;}s:7:\"rockets\";a:1:{i:26;i:84;}s:7:\"shields\";a:1:{i:25;i:15;}s:10:\"hyperspace\";a:3:{i:22;i:0;i:23;i:0;i:24;i:0;}s:0:\"\";a:3:{i:75000;i:2;i:225000;i:2;i:0;i:2;}}', 30, '', 171956, 394771, 0, 155462, 124808, 19537, 7596, 1704, 1650, 280, 24, 3, 347, 400),
(6, 79168025, 0, 5, 3, 339, 113, 1, 'Line', 'Phalanx', 0x613a303a7b7d, '', '', '', '', '', '', '', '', '', '', 1, 'a:4:{s:6:\"lasers\";a:3:{s:3:\"low\";i:0;s:6:\"medium\";i:0;s:4:\"beam\";i:0;}s:7:\"rockets\";i:0;s:6:\"energy\";i:0;s:10:\"hyperspace\";a:3:{s:3:\"low\";i:0;s:6:\"medium\";i:0;s:4:\"high\";i:0;}}', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(7, 84216073, 0, 4, 4, 337, 104, 1, 'Line', 'Phalanx', 0x613a303a7b7d, '', '', '', '', '', '', '', '', '', '', 1, 'a:4:{s:6:\"lasers\";a:3:{s:3:\"low\";i:0;s:6:\"medium\";i:0;s:4:\"beam\";i:0;}s:7:\"rockets\";i:0;s:6:\"energy\";i:0;s:10:\"hyperspace\";a:3:{s:3:\"low\";i:0;s:6:\"medium\";i:0;s:4:\"high\";i:0;}}', 0, '', 0, 0, 10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(8, 95617823, 0, 4, 5, 160, 167, 1, 'Line', 'Phalanx', 0x613a303a7b7d, '', '', '', '', '', '', '', '', '', '', 1, 'a:4:{s:6:\"lasers\";a:3:{s:3:\"low\";i:0;s:6:\"medium\";i:0;s:4:\"beam\";i:0;}s:7:\"rockets\";i:0;s:6:\"energy\";i:0;s:10:\"hyperspace\";a:3:{s:3:\"low\";i:0;s:6:\"medium\";i:0;s:4:\"high\";i:0;}}', 0, '', 0, 0, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

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
-- Vypisuji data pro tabulku `usermovement`
--

INSERT INTO `usermovement` (`userID`, `targetUserID`, `attackedUserX`, `attackedUserY`, `targetMapLocation`, `fleet`, `fleetNumbers`, `travelTime`, `travelWay`, `returnWay`, `setAttack`, `missionType`, `type`, `lobbyID`, `rounds`, `report1`, `rewardsAttacker`, `rewardsDefender`, `report2`, `report3`, `defenseHours`, `reward`, `hudPresentAttacker`, `hudPresentTarget`, `formationHeavy`, `formationLight`, `formationCD`, `id`) VALUES
(27613408, 0, 293, 29, 1, 0x1f8b080000000000020ae5dc5d6a1b411045e1bdcc02c2545557ff69358a2db08993184b7908467bcf784488209c2c20f749eaa6a7d59ca70f4933c739da7c7f9eebe138b737e759e7f2edc7d7cfa7b7e5f03c5bade5b0cfbd1ccfa7b7f3b2ad2ab7e5e7e97371fb5865f703bf1fc49fc1f5bcedbfbc7d7ff872baecfbacf3fd36777e7a3ebd3cee7376bf77ee17d93a97a79fafdb87bf1e1f4e7f2df27d516ebb5c8e97fbf36dc7aaebfe72b80dfdf038c3b2f6fd5c8f73fd141f9796b95cb6ed6f47bf7e2c8710f93f84c875fdddc27cbd0dfe11c32946158c1114a309c62814a30bc6488a3104635488d156c1188d6298608c4e315c30c6a0182118c3889fad28d620833645831a21b42922d448a14d51a1460c6d8a0c3572685374a81144bb22448d24da15256a44d1ae4851238b76458b3a59b42b5ad4c9a25df2fb50b26857b4a89345bba2459d2cda152dea64d1ae6851278b0e458b3a5974285ad4c9a243d1a24e161d8a160db2e850b468904587a245832c3a247f9c278b0e458b065974285a34c8a243d1a201166daba245a3510d458b46a71a8a168d4135142d5a56aaa168d1625443d1a2c5a986a2454b500dc97f8a16aaa168d1925443d1a2852c6a8a162d645153b468218b9aa2450b59d4142d9a645153b46892454dd1a2491635458b2659d4142d9a645193bc6d892c6a8a164db2a82b5a34c9a2ae68d1248bbaa245932cea8a16ad645157b468258bbaa2452b59d4152d5ac9a2ae68d14a1675458b56b2a84bde434f160d458b56b268285ab4924543d1a2952c1a8a166d64d150b468238b86a2451b5934142ddac8a2a168d146160d458b36b268285ab491458be4039dc8a245d1a28d2c5a142ddac8a245d1a29d2c5a142ddac9a245d1a29d2c5a142ddac9a245d1a29d2c5a142ddac9a245d1a29d2c9a8a16ed64d1947cba285934152ddac9a2a968d141164d458b0eb2682a5a74904553d1a2832c9a8a161d64d154b4e8208ba6a2450759b4ea59f4fa0bca70f6a847660000, 'a:6:{i:0;i:0;i:1;i:0;i:2;i:97;i:3;i:0;i:4;i:0;i:5;i:0;}', 1574144494, 0, 1, 1572990888, 2, 'npc', 'fTlObXhm40QRoNzAsg19', 3, '<div class=\'table_wrapper\'><table class=\'table_round1_attacker\'>\r\n  <tbody>\r\n    <tr>\r\n      <th colspan=\'4\'><h3>Attacker</h3></th>\r\n    </tr>\r\n    <tr>\r\n      <th>Ship type</th>\r\n      <th>Pre-battle</th>\r\n      <th>Post-battle</th>\r\n      <th>Casulties</th>\r\n    </tr>\r\n    <tr>\r\n      <td>Hornet</td>\r\n      <td>0</td>\r\n      <td>0</td>\r\n      <td style=\'color:#fe3939\'>-0</td>\r\n    </tr>\r\n    <tr>\r\n      <td>Spacefire</td>\r\n      <td>0</td>\r\n      <td>0</td>\r\n      <td style=\'color:#fe3939\'>-0</td>\r\n    </tr>\r\n    <tr>\r\n      <td>Starhawk</td>\r\n      <td>100</td>\r\n      <td>98</td>\r\n      <td style=\'color:#fe3939\'>-2</td>\r\n    </tr>\r\n    <tr>\r\n      <td>Peacemaker</td>\r\n      <td>0</td>\r\n      <td>0</td>\r\n      <td style=\'color:#fe3939\'>-0</td>\r\n    </tr>\r\n    <tr>\r\n      <td>Centurion</td>\r\n      <td>0</td>\r\n      <td>0</td>\r\n      <td style=\'color:#fe3939\'>-0</td>\r\n    </tr>\r\n    <tr>\r\n      <td>Nathalis</td>\r\n      <td>0</td>\r\n      <td>0</td>\r\n      <td style=\'color:#fe3939\'>-0</td>\r\n    </tr>\r\n    </tbody>\r\n  </table><table class=\'table_round1_defender\'>\r\n  <tbody>\r\n  <tr>\r\n    <th colspan=\'4\'><h3>Defender</h3></th>\r\n  </tr>\r\n  <tr>\r\n    <th>Ship type</th>\r\n    <th>Pre-battle</th>\r\n    <th>Post-battle</th>\r\n    <th>Casulties</th>\r\n  </tr>\r\n  <tr>\r\n    <td>Hornet</td>\r\n    <td>165</td>\r\n    <td>33</td>\r\n    <td style=\'color:#fe3939\'>-132</td>\r\n  </tr>\r\n  <tr>\r\n    <td>Spacefire</td>\r\n    <td>53</td>\r\n    <td>12</td>\r\n    <td style=\'color:#fe3939\'>-41</td>\r\n  </tr>\r\n  <tr>\r\n    <td>Starhawk</td>\r\n    <td>23</td>\r\n    <td>6</td>\r\n    <td style=\'color:#fe3939\'>-17</td>\r\n  </tr>\r\n  <tr>\r\n    <td>Peacemaker</td>\r\n    <td>12</td>\r\n    <td>4</td>\r\n    <td style=\'color:#fe3939\'>-8</td>\r\n  </tr>\r\n  <tr>\r\n    <td>Centurion</td>\r\n    <td>8</td>\r\n    <td>8</td>\r\n    <td style=\'color:#fe3939\'>-0</td>\r\n  </tr>\r\n  <tr>\r\n    <td>Nathalis</td>\r\n    <td>3</td>\r\n    <td>3</td>\r\n    <td style=\'color:#fe3939\'>-0</td>\r\n  </tr>\r\n  </tbody>\r\n</table></div><p class=\'shotInfo\'>Both defending and attacking commanders have not been present during this round, therefore both fleets engaged each other in the same time</p>', 'a:3:{i:0;i:3319000;i:1;d:582000;i:2;d:0;}', 'a:3:{i:0;i:0;i:1;i:0;i:2;i:0;}', '<div class=\'table_wrapper\'><table class=\'table_round2_attacker\'>\r\n  <tbody>\r\n    <tr>\r\n      <th colspan=\'4\'><h3>Attacker</h3></th>\r\n    </tr>\r\n    <tr>\r\n      <th>Ship type</th>\r\n      <th>Pre-battle</th>\r\n      <th>Post-battle</th>\r\n      <th>Casulties</th>\r\n    </tr>\r\n    <tr>\r\n      <td>Hornet</td>\r\n      <td>0</td>\r\n      <td>0</td>\r\n      <td style=\'color:#fe3939\'>-0</td>\r\n    </tr>\r\n    <tr>\r\n      <td>Spacefire</td>\r\n      <td>0</td>\r\n      <td>0</td>\r\n      <td style=\'color:#fe3939\'>-0</td>\r\n    </tr>\r\n    <tr>\r\n      <td>Starhawk</td>\r\n      <td>98</td>\r\n      <td>97</td>\r\n      <td style=\'color:#fe3939\'>-1</td>\r\n    </tr>\r\n    <tr>\r\n      <td>Peacemaker</td>\r\n      <td>0</td>\r\n      <td>0</td>\r\n      <td style=\'color:#fe3939\'>-0</td>\r\n    </tr>\r\n    <tr>\r\n      <td>Centurion</td>\r\n      <td>0</td>\r\n      <td>0</td>\r\n      <td style=\'color:#fe3939\'>-0</td>\r\n    </tr>\r\n    <tr>\r\n      <td>Nathalis</td>\r\n      <td>0</td>\r\n      <td>0</td>\r\n      <td style=\'color:#fe3939\'>-0</td>\r\n    </tr>\r\n    </tbody>\r\n  </table><table class=\'table_round2_defender\'>\r\n  <tbody>\r\n  <tr>\r\n    <th colspan=\'4\'><h3>Defender</h3></th>\r\n  </tr>\r\n  <tr>\r\n    <th>Ship type</th>\r\n    <th>Pre-battle</th>\r\n    <th>Post-battle</th>\r\n    <th>Casulties</th>\r\n  </tr>\r\n  <tr>\r\n    <td>Hornet</td>\r\n    <td>0</td>\r\n    <td>0</td>\r\n    <td style=\'color:#fe3939\'>-0</td>\r\n  </tr>\r\n  <tr>\r\n    <td>Spacefire</td>\r\n    <td>20</td>\r\n    <td>7</td>\r\n    <td style=\'color:#fe3939\'>-13</td>\r\n  </tr>\r\n  <tr>\r\n    <td>Starhawk</td>\r\n    <td>23</td>\r\n    <td>10</td>\r\n    <td style=\'color:#fe3939\'>-13</td>\r\n  </tr>\r\n  <tr>\r\n    <td>Peacemaker</td>\r\n    <td>12</td>\r\n    <td>3</td>\r\n    <td style=\'color:#fe3939\'>-9</td>\r\n  </tr>\r\n  <tr>\r\n    <td>Centurion</td>\r\n    <td>8</td>\r\n    <td>8</td>\r\n    <td style=\'color:#fe3939\'>-0</td>\r\n  </tr>\r\n  <tr>\r\n    <td>Nathalis</td>\r\n    <td>3</td>\r\n    <td>3</td>\r\n    <td style=\'color:#fe3939\'>-0</td>\r\n  </tr>\r\n  </tbody>\r\n</table></div><p class=\'shotInfo\'>Both defending and attacking commanders have not been present during this round, therefore both fleets engaged each other in the same time</p>', '<div class=\'table_wrapper\'><table class=\'table_round3_attacker\'>\r\n  <tbody>\r\n    <tr>\r\n      <th colspan=\'4\'><h3>Attacker</h3></th>\r\n    </tr>\r\n    <tr>\r\n      <th>Ship type</th>\r\n      <th>Pre-battle</th>\r\n      <th>Post-battle</th>\r\n      <th>Casulties</th>\r\n    </tr>\r\n    <tr>\r\n      <td>Hornet</td>\r\n      <td>0</td>\r\n      <td>0</td>\r\n      <td style=\'color:#fe3939\'>-0</td>\r\n    </tr>\r\n    <tr>\r\n      <td>Spacefire</td>\r\n      <td>0</td>\r\n      <td>0</td>\r\n      <td style=\'color:#fe3939\'>-0</td>\r\n    </tr>\r\n    <tr>\r\n      <td>Starhawk</td>\r\n      <td>97</td>\r\n      <td>97</td>\r\n      <td style=\'color:#fe3939\'>-0</td>\r\n    </tr>\r\n    <tr>\r\n      <td>Peacemaker</td>\r\n      <td>0</td>\r\n      <td>0</td>\r\n      <td style=\'color:#fe3939\'>-0</td>\r\n    </tr>\r\n    <tr>\r\n      <td>Centurion</td>\r\n      <td>0</td>\r\n      <td>0</td>\r\n      <td style=\'color:#fe3939\'>-0</td>\r\n    </tr>\r\n    <tr>\r\n      <td>Nathalis</td>\r\n      <td>0</td>\r\n      <td>0</td>\r\n      <td style=\'color:#fe3939\'>-0</td>\r\n    </tr>\r\n    </tbody>\r\n  </table><table class=\'table_round3_defender\'>\r\n  <tbody>\r\n  <tr>\r\n    <th colspan=\'4\'><h3>Defender</h3></th>\r\n  </tr>\r\n  <tr>\r\n    <th>Ship type</th>\r\n    <th>Pre-battle</th>\r\n    <th>Post-battle</th>\r\n    <th>Casulties</th>\r\n  </tr>\r\n  <tr>\r\n    <td>Hornet</td>\r\n    <td>0</td>\r\n    <td>0</td>\r\n    <td style=\'color:#fe3939\'>-0</td>\r\n  </tr>\r\n  <tr>\r\n    <td>Spacefire</td>\r\n    <td>0</td>\r\n    <td>0</td>\r\n    <td style=\'color:#fe3939\'>-0</td>\r\n  </tr>\r\n  <tr>\r\n    <td>Starhawk</td>\r\n    <td>8</td>\r\n    <td>2</td>\r\n    <td style=\'color:#fe3939\'>-6</td>\r\n  </tr>\r\n  <tr>\r\n    <td>Peacemaker</td>\r\n    <td>12</td>\r\n    <td>4</td>\r\n    <td style=\'color:#fe3939\'>-8</td>\r\n  </tr>\r\n  <tr>\r\n    <td>Centurion</td>\r\n    <td>8</td>\r\n    <td>8</td>\r\n    <td style=\'color:#fe3939\'>-0</td>\r\n  </tr>\r\n  <tr>\r\n    <td>Nathalis</td>\r\n    <td>3</td>\r\n    <td>3</td>\r\n    <td style=\'color:#fe3939\'>-0</td>\r\n  </tr>\r\n  </tbody>\r\n</table></div><p class=\'shotInfo\'>Both defending and attacking commanders have not been present during this round, therefore both fleets engaged each other in the same time</p>', 0, 0, 0, 0, 'Line', 'Phalanx', 0, 218);

-- --------------------------------------------------------

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
-- Vypisuji data pro tabulku `usermsg`
--

INSERT INTO `usermsg` (`id`, `toUserID`, `fromUserID`, `sentTime`, `subject`, `msg`, `viewed`, `replymsg`, `token`) VALUES
(1, 95617823, '27613408', 1562257401, 'test', 0x63686c61706369206d6920726f7a62696c6920687275206d7573696d6520746573746f7661743a28, 1, NULL, 'c9c76f7da5ac483051ca'),
(3, 95617823, '27613408', 1562257835, 'RE:Test', 0x7a6b6f75c5a16b6120706f73c3ad6cc3a16ec3ad206f64706f76c49b6469, 1, 0x706f73c3ad6c616d20617369, 'e3c754385eb3508a9ac1'),
(4, 95617823, '27613408', 1562270568, 'testuju', 0x27, 1, NULL, '0e64eafac2d29c2840b5'),
(5, 95617823, '27613408', 1562270616, 'testskript', 0x3c7363726970743e616c6572742822736f6d746f68656b6c22293c2f7363726970743e, 1, NULL, '664d7b0823b21c066cb1'),
(20, 11, '1', 1562956099, 'Battle report', 0x3c7370616e3e54686573652074776f20666c65657473206861766520656e6761676564206f7665723a20313a3130383a37363c2f7370616e3e3c703e446566656e6465723a20207769746820666c65657420636f6e73697374696e67206f66203120486f726e6574732c203120537061636566697265732c203120537461726861776b732c20312050656163656d616b6572732c20312043656e747572696f6e732c2031204e612d5468616c69732064657374726f7965727320616e6420426174746c652d53746174696f6e3c703e41747461636b65723a20446577456e666f72636572207769746820666c65657420636f6e73697374696e67206f662035302030303020486f726e6574732c203020537061636566697265732c203020537461726861776b732c20302050656163656d616b6572732c20302043656e747572696f6e732c2030204e612d5468616c69732064657374726f796572733c703e205468652061747461636b657220686173206c6f737420696e20746f74616c203131207368697073202d20313120486f726e6574732c203020537061636566697265732c203020537461726861776b732c20302050656163656d616b6572732c20302043656e747572696f6e732c2030204e612d7468616c69732044657374726f796572733c703e2054686520446566656e64657220686173206c6f737420696e20746f74616c2036207368697073202d203120486f726e6574732c203120537061636566697265732c203120537461726861776b732c20312050656163656d616b6572732c20312043656e747572696f6e732c2031204e612d7468616c69732044657374726f796572733c703e3c623e426174746c65206f7574636f6d653a20546f74616c20766963746f727920666f72207468652061747461636b65722c2062757420646566656e64657273206261736520686173206265656e2064657374726f796564213c2f623e3c2f703e, 0, NULL, '5bfb4f8eb37fc10ee596'),
(22, 12, '1', 1562956312, 'Battle report', 0x3c7370616e3e54686573652074776f20666c65657473206861766520656e6761676564206f7665723a20313a3339353a38393c2f7370616e3e3c703e446566656e6465723a20207769746820666c65657420636f6e73697374696e67206f66203120486f726e6574732c203120537061636566697265732c203120537461726861776b732c20312050656163656d616b6572732c20312043656e747572696f6e732c2031204e612d5468616c69732064657374726f7965727320616e6420426174746c652d53746174696f6e3c703e41747461636b65723a20446577456e666f72636572207769746820666c65657420636f6e73697374696e67206f662031302030303020486f726e6574732c203020537061636566697265732c203020537461726861776b732c20302050656163656d616b6572732c20302043656e747572696f6e732c2030204e612d5468616c69732064657374726f796572733c703e205468652061747461636b657220686173206c6f737420696e20746f74616c203131207368697073202d20313120486f726e6574732c203020537061636566697265732c203020537461726861776b732c20302050656163656d616b6572732c20302043656e747572696f6e732c2030204e612d7468616c69732044657374726f796572733c703e2054686520446566656e64657220686173206c6f737420696e20746f74616c2036207368697073202d203120486f726e6574732c203120537061636566697265732c203120537461726861776b732c20312050656163656d616b6572732c20312043656e747572696f6e732c2031204e612d7468616c69732044657374726f796572733c703e3c623e426174746c65206f7574636f6d653a20546f74616c20766963746f727920666f72207468652061747461636b65722c2062757420646566656e64657273206261736520686173206265656e2064657374726f796564213c2f623e3c2f703e, 0, NULL, '48261a59877ff1e76e56'),
(24, 14, '1', 1562957558, 'Battle report', 0x3c7370616e3e54686573652074776f20666c65657473206861766520656e6761676564206f7665723a20313a3537323a3135383c2f7370616e3e3c703e446566656e6465723a20207769746820666c65657420636f6e73697374696e67206f66203620486f726e6574732c203220537061636566697265732c203120537461726861776b732c20312050656163656d616b6572732c20312043656e747572696f6e732c2031204e612d5468616c69732064657374726f7965727320616e6420426174746c652d53746174696f6e3c703e41747461636b65723a20446577456e666f72636572207769746820666c65657420636f6e73697374696e67206f662031302030303020486f726e6574732c203020537061636566697265732c203020537461726861776b732c20302050656163656d616b6572732c20302043656e747572696f6e732c2030204e612d5468616c69732064657374726f796572733c703e205468652061747461636b657220686173206c6f737420696e20746f74616c203132207368697073202d20313220486f726e6574732c203020537061636566697265732c203020537461726861776b732c20302050656163656d616b6572732c20302043656e747572696f6e732c2030204e612d7468616c69732044657374726f796572733c703e2054686520446566656e64657220686173206c6f737420696e20746f74616c203132207368697073202d203620486f726e6574732c203220537061636566697265732c203120537461726861776b732c20312050656163656d616b6572732c20312043656e747572696f6e732c2031204e612d7468616c69732044657374726f796572733c703e3c623e426174746c65206f7574636f6d653a20546f74616c20766963746f727920666f72207468652061747461636b65722c2062757420646566656e64657273206261736520686173206265656e2064657374726f796564213c2f623e3c2f703e, 0, NULL, 'f4129f9e4d552a3d06f1'),
(26, 11, '1', 1563494804, 'Battle report', 0x3c7370616e3e54686573652074776f20666c65657473206861766520656e6761676564206f7665723a20313a3138333a3131313c2f7370616e3e3c703e446566656e6465723a20207769746820666c65657420636f6e73697374696e67206f66203120486f726e6574732c203120537061636566697265732c203120537461726861776b732c20312050656163656d616b6572732c20312043656e747572696f6e732c2031204e612d5468616c69732064657374726f7965727320616e6420426174746c652d53746174696f6e3c703e41747461636b65723a20446577456e666f72636572207769746820666c65657420636f6e73697374696e67206f662033302030303020486f726e6574732c203020537061636566697265732c203020537461726861776b732c20302050656163656d616b6572732c20302043656e747572696f6e732c2030204e612d5468616c69732064657374726f796572733c703e205468652061747461636b657220686173206c6f737420696e20746f74616c203131207368697073202d20313120486f726e6574732c203020537061636566697265732c203020537461726861776b732c20302050656163656d616b6572732c20302043656e747572696f6e732c2030204e612d7468616c69732044657374726f796572733c703e2054686520446566656e64657220686173206c6f737420696e20746f74616c2036207368697073202d203120486f726e6574732c203120537061636566697265732c203120537461726861776b732c20312050656163656d616b6572732c20312043656e747572696f6e732c2031204e612d7468616c69732044657374726f796572733c703e3c623e426174746c65206f7574636f6d653a20546f74616c20766963746f727920666f72207468652061747461636b65722c2062757420646566656e64657273206261736520686173206265656e2064657374726f796564213c2f623e3c2f703e, 0, NULL, '62b52b678db6a319d655'),
(29, 20, '1', 1563553107, 'Battle report', 0x3c7370616e3e54686573652074776f20666c65657473206861766520656e6761676564206f7665723a20313a38373a3133383c2f7370616e3e3c703e446566656e6465723a20207769746820666c65657420636f6e73697374696e67206f6620312034363720486f726e6574732c2034363720537061636566697265732c2032303020537461726861776b732c203130302050656163656d616b6572732c2036372043656e747572696f6e732c203237204e612d5468616c69732064657374726f7965727320616e6420426174746c652d53746174696f6e3c703e41747461636b65723a20446577456e666f72636572207769746820666c65657420636f6e73697374696e67206f662035352030303020486f726e6574732c203020537061636566697265732c203020537461726861776b732c20302050656163656d616b6572732c20302043656e747572696f6e732c2030204e612d5468616c69732064657374726f796572733c703e205468652061747461636b657220686173206c6f737420696e20746f74616c203120313935207368697073202d20312031393520486f726e6574732c203020537061636566697265732c203020537461726861776b732c20302050656163656d616b6572732c20302043656e747572696f6e732c2030204e612d7468616c69732044657374726f796572733c703e2054686520446566656e64657220686173206c6f737420696e20746f74616c203220333238207368697073202d20312034363720486f726e6574732c2034363720537061636566697265732c2032303020537461726861776b732c203130302050656163656d616b6572732c2036372043656e747572696f6e732c203237204e612d7468616c69732044657374726f796572733c703e3c623e426174746c65206f7574636f6d653a20546f74616c20766963746f727920666f72207468652061747461636b65722c2062757420646566656e64657273206261736520686173206265656e2064657374726f796564213c2f623e3c2f703e, 0, NULL, '410c8a5f5e3d339b6eea'),
(33, 11, '1', 1564843847, 'Battle report', 0x3c7370616e3e54686573652074776f20666c65657473206861766520656e6761676564206f7665723a20313a34373a3337393c2f7370616e3e3c703e446566656e6465723a20207769746820666c65657420636f6e73697374696e67206f66203120486f726e6574732c203120537061636566697265732c203120537461726861776b732c20312050656163656d616b6572732c20312043656e747572696f6e732c2031204e612d5468616c69732064657374726f7965727320616e6420426174746c652d53746174696f6e3c703e41747461636b65723a20446577456e666f72636572207769746820666c65657420636f6e73697374696e67206f662035303020486f726e6574732c203020537061636566697265732c203020537461726861776b732c20302050656163656d616b6572732c20302043656e747572696f6e732c2030204e612d5468616c69732064657374726f796572733c703e205468652061747461636b657220686173206c6f737420696e20746f74616c203130207368697073202d20313020486f726e6574732c203020537061636566697265732c203020537461726861776b732c20302050656163656d616b6572732c20302043656e747572696f6e732c2030204e612d7468616c69732044657374726f796572733c703e2054686520446566656e64657220686173206c6f737420696e20746f74616c2036207368697073202d203120486f726e6574732c203120537061636566697265732c203120537461726861776b732c20312050656163656d616b6572732c20312043656e747572696f6e732c2031204e612d7468616c69732044657374726f796572733c703e3c623e426174746c65206f7574636f6d653a20546f74616c20766963746f727920666f72207468652061747461636b65722c2062757420646566656e64657273206261736520686173206265656e2064657374726f796564213c2f623e3c2f703e, 0, NULL, '9c31c6c46a4cfba67580'),
(35, 13, '1', 1564844617, 'Battle report', 0x3c7370616e3e54686573652074776f20666c65657473206861766520656e6761676564206f7665723a20313a3135353a3136323c2f7370616e3e3c703e446566656e6465723a20207769746820666c65657420636f6e73697374696e67206f66203120486f726e6574732c203120537061636566697265732c203120537461726861776b732c20312050656163656d616b6572732c20312043656e747572696f6e732c2031204e612d5468616c69732064657374726f7965727320616e6420426174746c652d53746174696f6e3c703e41747461636b65723a20446577456e666f72636572207769746820666c65657420636f6e73697374696e67206f662031303020486f726e6574732c203020537061636566697265732c203020537461726861776b732c20302050656163656d616b6572732c20302043656e747572696f6e732c2030204e612d5468616c69732064657374726f796572733c703e205468652061747461636b657220686173206c6f737420696e20746f74616c203130207368697073202d20313020486f726e6574732c203020537061636566697265732c203020537461726861776b732c20302050656163656d616b6572732c20302043656e747572696f6e732c2030204e612d7468616c69732044657374726f796572733c703e2054686520446566656e64657220686173206c6f737420696e20746f74616c2036207368697073202d203120486f726e6574732c203120537061636566697265732c203120537461726861776b732c20312050656163656d616b6572732c20312043656e747572696f6e732c2031204e612d7468616c69732044657374726f796572733c703e3c623e426174746c65206f7574636f6d653a20546f74616c20766963746f727920666f72207468652061747461636b65722c2062757420646566656e64657273206261736520686173206265656e2064657374726f796564213c2f623e3c2f703e, 0, NULL, '1d7ec128b3260a551a12'),
(37, 16, '1', 1564845514, 'Battle report', 0x3c7370616e3e54686573652074776f20666c65657473206861766520656e6761676564206f7665723a20313a3236343a3136353c2f7370616e3e3c703e446566656e6465723a20207769746820666c65657420636f6e73697374696e67206f6620353520486f726e6574732c20313820537061636566697265732c203820537461726861776b732c20342050656163656d616b6572732c20332043656e747572696f6e732c2031204e612d5468616c69732064657374726f7965727320616e6420426174746c652d53746174696f6e3c703e41747461636b65723a20446577456e666f72636572207769746820666c65657420636f6e73697374696e67206f662035303020486f726e6574732c203020537061636566697265732c203020537461726861776b732c20302050656163656d616b6572732c20302043656e747572696f6e732c2030204e612d5468616c69732064657374726f796572733c703e205468652061747461636b657220686173206c6f737420696e20746f74616c203437207368697073202d20343720486f726e6574732c203020537061636566697265732c203020537461726861776b732c20302050656163656d616b6572732c20302043656e747572696f6e732c2030204e612d7468616c69732044657374726f796572733c703e2054686520446566656e64657220686173206c6f737420696e20746f74616c203839207368697073202d20353520486f726e6574732c20313820537061636566697265732c203820537461726861776b732c20342050656163656d616b6572732c20332043656e747572696f6e732c2031204e612d7468616c69732044657374726f796572733c703e3c623e426174746c65206f7574636f6d653a20546f74616c20766963746f727920666f72207468652061747461636b65722c2062757420646566656e64657273206261736520686173206265656e2064657374726f796564213c2f623e3c2f703e, 0, NULL, 'a94e61b9f2199012e570'),
(919, 79120856, '27613408', 1573139834, 'Subject bro', 0x1f8b080000000000020a3590cd7143310884efa9620bf0bc2a925bae2980486b8719fd59028fcb0ff24b6e42c0b2fb7df6c90a1dcb2b722f7d62a9412aed82d4db62329a4f48d6a14b93b61b5834ba8b393640f5557b86b18ed8d696346bf666704391efd007edd426aadc9a408ade5d0e7c19d8b48638aaeec7234aa917dc5d175a5f363d834fcea426a6bdc14b919afaa9bc87c2d4bef492d411c3a084f31a9efa99204ed981f72d296e844e0f2767586d981c933f6c993392c7c7a3171f718e612792826b11494bf94714811c57bfa918da368421330a9f073e9e89c3e89b6330e8290953cc251f9ac5f646a418b36b66db1437a9389abc0cd9b9d1afd7c02cc85c9cbb5b7bd9366403d2c0b1feb87a3dde7e01f8a98999bf010000, 1, NULL, '8da12cef87b9b22d1828'),
(921, 79120856, '27613408', 1573144807, 'RE: Heyhey', 0x1f8b080000000000020af348adcc48ad5428c9c82c5600a24485a2d4821c203fb5b804002c1e9e8a1b000000, 1, 0x1f8b080000000000020a4b2aca070285bccce454859cfca2d45c85cc82e2d25c85b4c45c05b59c12ebe2e4a2cc8212b5f412ebc49cd4a2120db5c2d2fc12eb8cc4e4ecd414085b13a44c1fa10e003e1e269452000000, '6e2f0c602a235422cc1a'),
(925, 27613408, '1', 1573568352, 'Fleet return report', 0x1f8b080000000000020a8d91314f03310c85ff8ad50986c29d3a540a4717189810a22c8c56e26ba2bbc691ed53e9bf272798219b65f97b4f7e6f28a0769de97163f4655b9cd3293b4fd9481e36874f5e04c699c820a282902d9229c0287c86deedfbceedf6c37d390c7fca1c632a0a81fd54d9b048ca27b098148ab0275507ff6b40072f5ccded466fdbce8f053d8d49a891e8bbae428612f13255a6c9e48daac919279256e2a9ce3502ceadc02b6e3f625d2b3c939af0b5d1eb9d5039038fbf0dfeb4e7d6177340096bfac69ee7bb55eb1b643cd32909020000, 1, NULL, '00559339cca2f66d1a89'),
(926, 27613408, '1', 1573569146, 'Battle report', 0x1f8b080000000000020aed99cb6edb301045f705fa0f0374a14d52ebe1a6b5a008689d16e9a60d9a555701658e2c22322990b40dff7d47b22dbfe4079a3859443b6948ce1dd2bce481156541fc2347b406540a76aa60a0462326396a03199b20a01cb221725013d4603334485d94e642328b26042f0c7addd0733f451dca159982c9f8abb56cf048fd13147218c20d4ebfcb54e901852837704cb19458b6f7338129dc094d29a34e9522e26202839c1973ed683596dc73e2280b962133a62af5cc89ff946de0c122302f626dac65498e0f53cd8a0235a5a8de371be7e91fd8a268277eff0e20b289e2b3eab17cd18ba7f239a305c8cb22af9d6e55543ddfb97ac766cb719d7ae0568af83e1305d859816bdde72d771a2f132a266f6852c6ee6beb3333cead40738a3e8f6f959668a903df08bbc72360ec2cc76b871641e9f0438a412fe839f1e57ac703c2f7051b602a34be86b6653a63d3c71d21cf75dd93824f2ce00e69f223566d95179f7d1fa51d6ba1e42b68ff623663b9302f21dd5959979e4b8b1fb2fdf22cdab17db436995dcbdf2c866d5abe2e636370a3d9f75a7dbfd19b6ddea8d960f032d8f3b7029eb719d8b7d65f56fdf6e835f9ba8c07ee56e0ea3445bf7b5471d7cdd59482ad807f9aa077748a8deedddeb20defc737f01ec126c79e53afc1a5cf2c577bb37666872eeb382aea8b3d53f627b18213ff96f90ce6973241c28a4b084b0c81034a2834d188b420248189305039faa284148d441b4850a00b2d4c391c258e669096a8532148451e6557a03d6b2c288960d542ef63d42996956d73887f8843fc277388df7248cb212d87bc350ef1df16879ceb02db072157ffa777f55c08129c88206e8b202d821c4590e01082044f4690a0459016415a04796b0812b408724e04399b5e33826c13c889fff3f82d80b400b2032075656a6ca90624df27f1b7ca93b00885b0fa8c94283a25181f09cdf2c55724aa622226c8296b1257990ba0551ed22932a0f2cb33e7af1a6bd038659a9b9030c6eb7a40d70ef4357261cd057cfaec56815b3a3db4e01471817e2d311e9932e33fd443e211cb1a0000, 1, NULL, '50852bf78a053ee0376d'),
(927, 27613408, '1', 1573569280, 'Fleet return report', 0x1f8b080000000000020a8d91314fc4300c85ff8a75130c07ad8081d0bb050626843816462b712f517b7164bb82fbf7a48219b25996bff7e4f786026ae799761ba32fdbe29c8ed979ca46f2b0d97ff02230ce44061115846c914c0146e113f4eee6fed6f5dddd705df6c39f3a87988a42603f55382c92f2112c268522ec49d5c1ff1ad0c1335777bbd0cbb6f343414f63126a24faaeab90a144fc9c2ad364f24ad5e48413492bf158e71a01e756e005b7efb1ae159e484df8dce8f546a89c81c7df0a7fea73eb8b39a084357d63cff3d5aaf50d853e4c180a020000, 1, NULL, '8c1b148568a6e652b576'),
(928, 27613408, '1', 1573569672, 'Battle report', 0x1f8b080000000000020aed99cb6edb301045f705fa0f0374a14d52bd1a231614016dd222ddb441d34d57012d8d2d22322990940dff7d87b2adf8213f903409d068470ec9b933b2ae7800c779987c2b108d063904339590caf198890c95869c4d10508cd808339013546072d4485ba4cab8600675047e74d68f42bf17bb942ad62513c96763587a4fdb07c8c528822b9c7e1543a9520a516ac870885661b97ec315e58addfa709cf109a405d3fac251b21299ef24711e2e43baa2f2d4cc497ed935f0611198cbaf9c356c50e0dd54b1b2444529eaf9fae23cfd1d5b94eb24efdf01c46620b3593db413b518d9714e9d17b6c80be7535d54d3e95cdd35f9f29cdb1cdc4891dce6bc04332b7165fb7ce546e1e9808a295a96a436bbd62e99ae0ac3511fa39f25d7520934b4215b0b7b8723a0cdacc00b871e8254d1872186fdb0ef24a7ab1bf708df962cc52157f81ada86a99c4defb7847ccff38e0a3eb1801ba4e6c7ac7e555ebcfb4b14a6525c8a57d0fec14cce0aae5f42da7db02e8dadc5f7d97ef915dab27dbcd2ccb6e5af16c7d62ddf94b176b8d5ec3badbedbe8ed366fd56c3178fd4af73703ebf35d8fda3f6ff6ed906bb3b58df736e6de7182bd837adb56b6e1f071fd0587e45a8ddbd2cfb1fd798704dbccfa9c7a2d06fdc7728d2d1b53ba744f2771d9dce9b934df09109ce4a7286630bf8f890c1e58845044132da08052118108035c108c700db5994f2c982824c440e201552aaeed7114389ec1d0e24dcd1d356ed8ad40efab36200582910bbd8fb15b2e2bdb4490601f82044f4690a043900e413a04796b0812bc2d0479ae0b6c17803c9b5e3b80048f04ac0e403a00390820e13e00099f0c206107201d807400f2d60024ec00e43f0210ff71721d807400b20d204d65b232540392ef07c997da93b00845f05b1a56c084a7462a5291f5ff43b044094a3748ea9425d0e31dd1e723a5baedc7e68fac14289c3295e988f825f4fb40f70d5c2accb8d12770761ed4816bfa6c289e51c403fa997835d636e35f458ce280b61a0000, 1, NULL, '6989649a929bd6984e07'),
(929, 27613408, '1', 1573569686, 'Fleet return report', 0x1f8b080000000000020a8d91314fc4300c85ff8a75130c07ad104884de2d303021c4b1305a897b89da8b23db15dcbf2715cc90cdb2fcbd27bf3714503bcfb4db187dd916e774ccce53369287cdfe83178171263288a820648b640a300a9fa077b7f7eea6bf1baecb7ef853e610535108eca7ca8645523e82c5a450843da93af85f033a78e66a6e177ad9767e28e8694c428d44df75153294889f53659a4c5ea99a9c702269251eeb5c23e0dc0abce0f63dd6b5c213a9099f1bbdde089533f0f8dbe04f7b6e7d310794b0a66fec79be5ab5be0156fcfd0109020000, 1, NULL, '9166024488767479be51');

-- --------------------------------------------------------

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
-- Vypisuji data pro tabulku `userresearch`
--

INSERT INTO `userresearch` (`userID`, `researchDmg`, `researchHp`, `researchShd`, `researchSpeed`, `researchSubspace`, `researchTime`, `currentResearch`) VALUES
(18056943, 0, 0, 0, 0, 0, 0, 0),
(27613408, 50, 50, 50, 50, 2, 0, 0),
(52146370, 0, 0, 0, 0, 0, 0, 0),
(78145239, 0, 0, 0, 0, 0, 0, 0),
(79120856, 0, 0, 0, 1, 3, 0, 0),
(79168025, 0, 0, 0, 0, 0, 0, 0),
(84216073, 0, 0, 0, 0, 0, 0, 0),
(95617823, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

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
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`id`, `userID`, `sessionID`, `Username`, `ingameNick`, `Email`, `Password`, `newsletter`, `regDate`, `registerIPreal`, `registerIPproxy`, `playTime`, `lastUpdate`, `credits`, `hyperid`, `natium`, `userclan`, `clanJoinDate`, `nickChangeTime`, `defenseCooldown`, `loginBonusDay`, `claimed`, `blockedUsers`) VALUES
(1, 27613408, 'ej18h36s0lkn', 'DewEnforcer', 'DewEnforcer', 'pat.madt@seznam.cz', '$2y$10$a2OOxfhITW7Siaw3IM3fwuZ3l.BewNE85G/ZOB4FLg8i4F3XlhjIC', 0, 1562254538, 'noIP', 'noIP', 718741, 1574453862, 1064687887, 364261090, 2163188, 'DEWY', 0, 0, 1572183532, 7, 1, 0x1f8b080000000000020a4bb432b0aaae050020e853a206000000),
(2, 84216073, '7o5aez4mjk2g', 'Hyper', 'Hyper', 'smuhanad@yahoo.com', '$2y$10$7xPpJYIEnrRl0C0lcVcY5e31kd62VXN6YJGLKeMfnN4NgFHJf8ap.', 0, 1562255091, 'noIP', 'noIP', 0, 0, 500000, 50000, 100, 'none', 0, 0, 0, 1, 0, ''),
(3, 95617823, '256waty1qe09', 'vendis55', 'vendis55', 'vendis6@centrum.cz', '$2y$10$BzIsk2sdVHanb9DAfP2C5.byONSzWLgWgQQVC2VNFQi5WE6EGYxVi', 0, 1562256319, 'noIP', 'noIP', 0, 0, 5100000, 1009676, 1000100, 'none', 0, 0, 0, 4, 0, ''),
(4, 79120856, '4ncr3w6vot78', 'Dewtest', 'Admiral_Zap', 'test@test.cz', '$2y$10$C80.SIvrWyhdO/wp758wFuIDy1Dzp7yVEOUSCW0QKO4U.B1qJdIqK', 0, 1562270773, 'noIP', 'noIP', 83361, 1573160209, 685631, 103360691, 109200, 'BRUH', 0, 0, 0, 12, 1, ''),
(7, 79168025, 'u3p0h2q751e6', 'DewEnforcer1', 'NewDew', 'piranha863@gmail.com', '$2y$10$07erXiGu0iG/93KB/fpIGeQokJsBfVGMVpJvo/j/CGTqOy6nYiW7e', 0, 0, 'noIP', 'noIP', 0, 0, 1000000, 100000, 100, 'none', 0, 1563395877, 0, 2, 0, ''),
(8, 18056943, 'b9g2a7xd1mfq', 'PlanetTest1', 'PlanetTest1', 'planet@planet.cz', '$2y$10$UfXtX.7Fl2cmnCDMXbAyjuAdHtxm50yJ3KoKJKi0cbRuS1fcxHYGu', 0, 1567783141, 'noIP', 'noIP', 682, 1570187321, 600000, 75000, 100, 'none', 0, 0, 0, 2, 1, ''),
(9, 78145239, '2019-09-05 21:58:42', 'PlanetTest2', 'PlanetTest2', 'plane1@planet.cz', '$2y$10$0LbTXlMoZo8Qt69Y8QnLxO1xTAjmPPye6oAkHmOyNG49n4uakb7xC', 0, 1567713496, 'noIP', 'noIP', 0, 0, 500000, 50000, 100, 'none', 0, 0, 0, 1, 0, ''),
(10, 52146370, 'h51tkid6xsfl', 'PlanetTest3', 'PlanetTest3', 'planet3@planet.cz', '$2y$10$XxUzaRuU.sdAYelxn88FterBuWQzJUptsQQoEKA2TzfSb9pRyHFBi', 0, 1567713544, 'noIP', 'noIP', 0, 0, 600000, 75000, 100, 'none', 0, 0, 0, 2, 1, '');

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
-- Klíče pro tabulku `player_information`
--
ALTER TABLE `player_information`
  ADD PRIMARY KEY (`playerID`),
  ADD KEY `playerID` (`playerID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `userID_2` (`userID`),
  ADD KEY `playerID_2` (`playerID`),
  ADD KEY `playerID_3` (`playerID`),
  ADD KEY `userID_3` (`userID`),
  ADD KEY `playerID_4` (`playerID`,`userID`,`TokenId`),
  ADD KEY `playerID_5` (`playerID`),
  ADD KEY `userID_4` (`userID`);

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

--
-- AUTO_INCREMENT pro tabulku `player_information`
--
ALTER TABLE `player_information`
  MODIFY `playerID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
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
