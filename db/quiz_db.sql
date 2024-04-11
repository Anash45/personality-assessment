-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2024 at 04:43 AM
-- Server version: 8.0.35
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quiz_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `allowed_emails`
--

CREATE TABLE `allowed_emails` (
  `aeID` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `createdAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `aID` int NOT NULL,
  `resultID` int DEFAULT NULL,
  `answer` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `opt_type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `noteID` int NOT NULL,
  `resultID` int DEFAULT NULL,
  `noteText` text COLLATE utf8mb4_general_ci,
  `private` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `opt_id` int NOT NULL,
  `popular` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `power` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `perfect` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `peaceful` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `opt_type` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`opt_id`, `popular`, `power`, `perfect`, `peaceful`, `opt_type`) VALUES
(1, 'Animated', ' Adventurous', ' Analytical', ' Adaptable', 'Strengths'),
(2, 'Playful', ' Persuasive', ' Persistent', ' Peaceful ', 'Strengths'),
(3, 'Sociable', ' Strong willed', ' Self sacrificing', ' Submissive ', 'Strengths'),
(4, 'Convincing', ' Competitive', ' Considerate', ' Controlled ', 'Strengths'),
(5, 'Refreshing', ' Resourceful', ' Respectful', ' Reserved ', 'Strengths'),
(6, 'Spirited', ' Self reliant', ' Sensitive', ' Satisfied ', 'Strengths'),
(7, 'Promoter', ' Positive', ' Planner', ' Patient ', 'Strengths'),
(8, 'Spontaneous', ' Sure', ' Schedled', ' Shy ', 'Strengths'),
(9, 'Optimistic', ' Outspoken', ' Orderly', ' Obliging ', 'Strengths'),
(10, 'Funny', ' Forceful', ' Faithful', ' Friendly ', 'Strengths'),
(11, 'Delightful', ' Daring', ' Detailed', ' Diplomatic ', 'Strengths'),
(12, 'Cheerful', ' Confident', ' Cultured', ' Consistent ', 'Strengths'),
(13, 'Inspiring', ' Independent', ' Idealistic', ' Inoffensive ', 'Strengths'),
(14, 'Demonstrative', ' Decisive', ' Deep', ' Dry humor ', 'Strengths'),
(15, 'Mixes easily', 'Mover', 'Musical', 'Mediator ', 'Strengths'),
(16, 'Talker', ' Tenacous', ' Thoughtful', ' Tolerant ', 'Strengths'),
(17, 'Lively', ' Leader', ' Loyal', ' Listener ', 'Strengths'),
(18, 'Cute', ' Chief', ' Chart maker', 'Contented ', 'Strengths'),
(19, 'Popular', ' Productive', ' Perfectionist', ' Permissive ', 'Strengths'),
(20, 'Bouncy', ' Bold', ' Behaved', ' Balanced', 'Strengths'),
(21, 'Brassy', ' Bossy', ' Boastful', ' Blank', 'Weaknesses'),
(22, 'Undisciplined', ' Unsympathetic', ' Unforgiving', ' Unenthusiastic', 'Weaknesses'),
(23, 'Repetitious', ' Resiteant', ' Resentful', ' Reticent', 'Weaknesses'),
(24, 'Forgetful', ' Frank', ' Fussy', ' Fearful', 'Weaknesses'),
(25, 'Interrupts', ' Impatient', ' Insecure', ' Indecisive', 'Weaknesses'),
(26, 'Unpredictable', ' Unaffectionate', ' Unpopular', ' Uninvolved', 'Weaknesses'),
(27, 'Haphazard', ' Headstrong', ' Hard to please', ' Hesitant', 'Weaknesses'),
(28, 'Permissive', ' Prous', ' Pessimistic', ' Plain', 'Weaknesses'),
(29, 'Easily angered', ' Argumentative', ' Alienated', ' Aimless', 'Weaknesses'),
(30, 'Naive', ' Nervy', ' Negative', ' Nonchanlant', 'Weaknesses'),
(31, 'Wants credit', ' Workaholic', ' Withdrawn', ' Worrier', 'Weaknesses'),
(32, 'Talkative', ' Tactless', ' Too sensitive', ' Timid', 'Weaknesses'),
(33, 'Disorganized', ' Domineering', ' Depressed', ' Doubtful', 'Weaknesses'),
(34, 'Inconsistent', ' Intolerant', ' Introvert', ' Indifference', 'Weaknesses'),
(35, 'Messy', ' Manipulative', ' Moody', ' Mumbles', 'Weaknesses'),
(36, 'Show off', ' Stubborn', ' Skeptical', ' Slow', 'Weaknesses'),
(37, 'Loud', ' Lord over others', 'Loner', ' Lazy', 'Weaknesses'),
(38, 'Scatter', ' brain Short tempered', ' Suspicious', ' Sluggish', 'Weaknesses'),
(39, 'Restless', ' Rash', ' Revengeful', ' Reluctant', 'Weaknesses'),
(40, 'Changeable', ' Crafty', ' Critical', ' Compromise', 'Weaknesses');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `resultID` int NOT NULL,
  `userID` int DEFAULT NULL,
  `score1` int DEFAULT NULL,
  `score2` int DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_general_ci NOT NULL,
  `createdAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) DEFAULT '1',
  `extendedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `name`, `email`, `password`, `role`, `createdAt`, `active`, `extendedAt`) VALUES
(1, 'Admin Xyz', 'futuretest45@gmail.com', '$2y$10$RtJnnjBm8cHYXsKZPxt8EuTAVy2CFXjw5gEdB1.MNYERCXglmeIV2', 'admin', '2024-04-10 23:50:23', 1, '2024-04-10 23:51:49'),
(2, 'Syed Muhammad Anas Bukhari', 'anas14529@gmail.com', '$2y$10$VKdBYBhaqqhreloaHe/4KeZqv5BXEbTfla29IrgbHO3RbUs1NKJ5S', 'user', '2024-04-11 00:08:04', 1, '2024-04-11 00:08:04'),
(3, 'Test', 'test1@gmail.com', '$2y$10$Fk1NYpekkxxo1NWbijeVxuZoMPPzGuPAkd/Fs7VZz4M7zsdg88b/i', 'user', '2024-04-11 00:08:16', 1, '2024-04-11 00:08:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `allowed_emails`
--
ALTER TABLE `allowed_emails`
  ADD PRIMARY KEY (`aeID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`aID`),
  ADD KEY `resultID` (`resultID`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`noteID`),
  ADD KEY `resultID` (`resultID`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`opt_id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`resultID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `allowed_emails`
--
ALTER TABLE `allowed_emails`
  MODIFY `aeID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `aID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `noteID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `opt_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `resultID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`resultID`) REFERENCES `results` (`resultID`);

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`resultID`) REFERENCES `results` (`resultID`);

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
