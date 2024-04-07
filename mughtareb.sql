-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2024 at 02:19 AM
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
-- Database: `mughtareb`
--

-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

CREATE TABLE `ads` (
  `ID` int NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sub_category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UserID` int DEFAULT NULL,
  `active` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ads`
--

INSERT INTO `ads` (`ID`, `category`, `sub_category`, `title`, `description`, `price`, `image`, `created_at`, `UserID`, `active`) VALUES
(7, 'activities', 'Boys relaxing', 'Abcd11', 'AAaaa1', '110.00', 'img_700650.png', '2024-04-06 21:06:50', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int NOT NULL,
  `FName` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `LName` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `UserName` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Birthday` date NOT NULL,
  `PhoneNum` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `Country` enum('Kingdom of Saudi Arabia','Kuwait','Qatar','United Arab Emirates','Oman','Egypt','Yemen','Iraq','Syria','Palestine','Lebanon','Jordan','India') COLLATE utf8mb4_general_ci NOT NULL,
  `Gender` enum('Male','Female') COLLATE utf8mb4_general_ci NOT NULL,
  `University` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ProfilePic` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Major` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Area` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `UserType` enum('Admin','Member') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Member'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `FName`, `LName`, `UserName`, `Email`, `Password`, `Birthday`, `PhoneNum`, `Country`, `Gender`, `University`, `ProfilePic`, `Major`, `Area`, `UserType`) VALUES
(1, 'Admin', '123', 'admin', 'admin@gmail.com', '$2y$10$k1t82lLJeP7vIjzIIkiEKu/r0GLqnnppP36Qx5a2SDnEQuCPmk2em', '2024-04-09', '21321312', 'Kingdom of Saudi Arabia', 'Male', 'ABCD', 'Screenshot 2024-04-01 022330.png', 'Abcd', 'Abcd', 'Admin'),
(2, 'Test', '123', 'test123', 'test@123.com', '$2y$10$I43DnRF61y6Ohc69AAu0punxSwByAD02U8pIwuFLHuZyIOrugtYne', '2001-01-01', '123412341234', 'Syria', 'Male', NULL, NULL, NULL, NULL, 'Member');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ads`
--
ALTER TABLE `ads`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `UserName` (`UserName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ads`
--
ALTER TABLE `ads`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ads`
--
ALTER TABLE `ads`
  ADD CONSTRAINT `ads_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
