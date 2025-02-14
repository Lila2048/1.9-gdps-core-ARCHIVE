-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 12, 2025 at 06:17 PM
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
-- Database: `19-gd-custom`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `udid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `regdate` int NOT NULL,
  `lastlogin` int NOT NULL,
  `ip` text NOT NULL,
  `isActive` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `actions`
--

CREATE TABLE `actions` (
  `id` int NOT NULL,
  `type` int NOT NULL,
  `value1` text,
  `value2` text,
  `value3` text,
  `value4` int NOT NULL DEFAULT '0',
  `ip` text NOT NULL,
  `timestamp` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `actions_downloads`
--

CREATE TABLE `actions_downloads` (
  `id` int NOT NULL,
  `levelID` int NOT NULL,
  `ip` text NOT NULL,
  `timestamp` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- --------------------------------------------------------

--
-- Table structure for table `actions_likes`
--

CREATE TABLE `actions_likes` (
  `id` int NOT NULL,
  `itemID` int NOT NULL,
  `type` int NOT NULL,
  `isLike` int NOT NULL,
  `ip` text NOT NULL,
  `timestamp` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `actions_likes`
--

-- --------------------------------------------------------

--
-- Table structure for table `bans`
--

CREATE TABLE `bans` (
  `id` int NOT NULL,
  `banType` int NOT NULL,
  `expires` int NOT NULL,
  `user` varchar(255) NOT NULL,
  `reason` text NOT NULL,
  `timestamp` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `udid` text NOT NULL,
  `accountID` int NOT NULL,
  `userName` text NOT NULL,
  `userID` int NOT NULL,
  `levelID` int NOT NULL,
  `comment` text NOT NULL,
  `spam` int NOT NULL DEFAULT '0',
  `ip` text NOT NULL,
  `likes` int NOT NULL DEFAULT '0',
  `timestamp` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE `levels` (
  `levelID` int NOT NULL,
  `gameVersion` int NOT NULL,
  `udid` text NOT NULL,
  `accountID` int NOT NULL,
  `userID` int NOT NULL DEFAULT '0',
  `levelName` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `levelDesc` text NOT NULL,
  `levelVersion` int NOT NULL,
  `levelLength` int NOT NULL,
  `audioTrack` int NOT NULL,
  `password` int NOT NULL,
  `original` int NOT NULL,
  `twoPlayer` int NOT NULL,
  `songID` int NOT NULL,
  `objects` int NOT NULL,
  `extraString` text NOT NULL,
  `levelReplay` text NOT NULL,
  `difficulty` int NOT NULL DEFAULT '0',
  `stars` int NOT NULL DEFAULT '0',
  `featureScore` int NOT NULL DEFAULT '0',
  `auto` int NOT NULL DEFAULT '0',
  `demon` int NOT NULL DEFAULT '0',
  `rateDate` int DEFAULT '0',
  `userName` text NOT NULL,
  `commentLocked` int NOT NULL DEFAULT '0',
  `updateLocked` int NOT NULL DEFAULT '0',
  `editorTime` int NOT NULL DEFAULT '0',
  `editorTimeCopy` int NOT NULL DEFAULT '0',
  `ip` text NOT NULL,
  `uploadDate` int DEFAULT NULL,
  `updateDate` int DEFAULT NULL,
  `diffDenominator` int NOT NULL DEFAULT '0',
  `downloads` int NOT NULL DEFAULT '0',
  `likes` int NOT NULL DEFAULT '0',
  `dislikes` int NOT NULL DEFAULT '0',
  `copyID` int NOT NULL DEFAULT '0',
  `copiedID` int NOT NULL DEFAULT '0',
  `completes` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mappacks`
--

CREATE TABLE `mappacks` (
  `packID` int NOT NULL,
  `packName` text NOT NULL,
  `levels` text NOT NULL,
  `stars` int NOT NULL,
  `coins` int NOT NULL,
  `difficulty` int NOT NULL,
  `textColor` text NOT NULL,
  `barColor` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int NOT NULL,
  `levelID` int NOT NULL,
  `ip` text NOT NULL,
  `timestamp` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reports`
--
-- --------------------------------------------------------

--
-- Table structure for table `sends`
--

CREATE TABLE `sends` (
  `id` int NOT NULL,
  `levelID` int NOT NULL,
  `udid` text NOT NULL,
  `stars` int NOT NULL,
  `feature` int NOT NULL,
  `ip` text NOT NULL,
  `timestamp` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

CREATE TABLE `songs` (
  `id` int NOT NULL,
  `name` text NOT NULL,
  `authorID` int NOT NULL DEFAULT '4',
  `authorName` text NOT NULL,
  `size` varchar(255) NOT NULL,
  `download` text NOT NULL,
  `isBanned` int NOT NULL DEFAULT '0',
  `isReupload` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `songs`
--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `udid` text NOT NULL,
  `userName` text NOT NULL,
  `stars` int NOT NULL DEFAULT '0',
  `demons` int NOT NULL DEFAULT '0',
  `color1` int NOT NULL DEFAULT '0',
  `color2` int NOT NULL DEFAULT '0',
  `iconType` int NOT NULL DEFAULT '0',
  `coins` int NOT NULL DEFAULT '0',
  `special` int NOT NULL DEFAULT '0',
  `gameVersion` int NOT NULL DEFAULT '0',
  `time` int NOT NULL DEFAULT '0',
  `userID` int NOT NULL,
  `isRegistered` int NOT NULL DEFAULT '0',
  `creatorPoints` int NOT NULL DEFAULT '0',
  `icon` int NOT NULL DEFAULT '0',
  `accountID` int NOT NULL DEFAULT '0',
  `permLevel` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `actions`
--
ALTER TABLE `actions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `actions_downloads`
--
ALTER TABLE `actions_downloads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `actions_likes`
--
ALTER TABLE `actions_likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bans`
--
ALTER TABLE `bans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`levelID`),
  ADD UNIQUE KEY `levelID` (`levelID`);

--
-- Indexes for table `mappacks`
--
ALTER TABLE `mappacks`
  ADD PRIMARY KEY (`packID`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sends`
--
ALTER TABLE `sends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `songs`
--
ALTER TABLE `songs`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `id_2` (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
