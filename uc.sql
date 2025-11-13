-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2025 at 05:04 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uc`
--

-- --------------------------------------------------------

--
-- Table structure for table `athletesprofile`
--

CREATE TABLE `athletesprofile` (
  `idNum` varchar(100) NOT NULL,
  `eventID` varchar(100) NOT NULL,
  `deptID` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `middleName` varchar(100) NOT NULL,
  `course` enum('BSIT','BSCS','BPED','HM') NOT NULL,
  `year` enum('1st year','2nd year','3rd year','4th year') NOT NULL,
  `civilStatus` enum('single','maried','widowed','') NOT NULL,
  `gender` enum('male','female','','') NOT NULL,
  `birthdate` date NOT NULL,
  `contactNo` varchar(100) NOT NULL,
  `address` varchar(200) NOT NULL,
  `coachID` varchar(100) NOT NULL,
  `deanID` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coach`
--

CREATE TABLE `coach` (
  `userName` varchar(100) NOT NULL,
  `fName` varchar(100) NOT NULL,
  `lName` varchar(100) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `deptID` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coach`
--

INSERT INTO `coach` (`userName`, `fName`, `lName`, `mobile`, `deptID`) VALUES
('kim', 'kim', 'edem', '894651', '1');

-- --------------------------------------------------------

--
-- Table structure for table `dean`
--

CREATE TABLE `dean` (
  `userName` varchar(100) NOT NULL,
  `fName` varchar(100) NOT NULL,
  `lName` varchar(100) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `deptID` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dean`
--

INSERT INTO `dean` (`userName`, `fName`, `lName`, `mobile`, `deptID`) VALUES
('deanLaygan', 'daisy', 'laygan', '123113', '1');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `deptID` varchar(100) NOT NULL,
  `deptName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`deptID`, `deptName`) VALUES
('1', 'CHM'),
('123', 'CCS');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `eventID` varchar(100) NOT NULL,
  `category` varchar(200) NOT NULL,
  `eventName` varchar(200) NOT NULL,
  `noOfParticipant` int(11) NOT NULL,
  `tournamentManager` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`eventID`, `category`, `eventName`, `noOfParticipant`, `tournamentManager`) VALUES
('54561', 'Academic', 'studycamp', 40, 'managermav');

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `userName` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `confirmPassword` varchar(100) NOT NULL,
  `role` enum('dean','student','manager','coach','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`userName`, `password`, `confirmPassword`, `role`) VALUES
('Adminjane', 'jane123!', 'jane123!', 'admin'),
('deanLaygan', 'laygan123!', 'laygan123!', 'dean'),
('kim', 'edem123!', 'edem123!', 'coach'),
('managermav', 'maverick1!', 'maverick1!', 'manager'),
('princess', 'princess123!', 'princess123!', 'student');

-- --------------------------------------------------------

--
-- Table structure for table `tournamentmanager`
--

CREATE TABLE `tournamentmanager` (
  `userName` varchar(100) NOT NULL,
  `fName` varchar(100) NOT NULL,
  `lName` varchar(100) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `deptID` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tournamentmanager`
--

INSERT INTO `tournamentmanager` (`userName`, `fName`, `lName`, `mobile`, `deptID`) VALUES
('managermav', 'mave', 'rick', '12311', '123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `athletesprofile`
--
ALTER TABLE `athletesprofile`
  ADD PRIMARY KEY (`idNum`),
  ADD KEY `eventID` (`eventID`),
  ADD KEY `deptID` (`deptID`),
  ADD KEY `coachID` (`coachID`),
  ADD KEY `deanID` (`deanID`);

--
-- Indexes for table `coach`
--
ALTER TABLE `coach`
  ADD PRIMARY KEY (`userName`),
  ADD KEY `deptID` (`deptID`);

--
-- Indexes for table `dean`
--
ALTER TABLE `dean`
  ADD PRIMARY KEY (`userName`),
  ADD KEY `deptID` (`deptID`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`deptID`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`eventID`),
  ADD KEY `tournamentManager` (`tournamentManager`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`userName`);

--
-- Indexes for table `tournamentmanager`
--
ALTER TABLE `tournamentmanager`
  ADD PRIMARY KEY (`userName`),
  ADD KEY `deptID` (`deptID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `athletesprofile`
--
ALTER TABLE `athletesprofile`
  ADD CONSTRAINT `athletesprofile_ibfk_1` FOREIGN KEY (`idNum`) REFERENCES `registration` (`userName`),
  ADD CONSTRAINT `athletesprofile_ibfk_2` FOREIGN KEY (`eventID`) REFERENCES `event` (`eventID`),
  ADD CONSTRAINT `athletesprofile_ibfk_3` FOREIGN KEY (`deptID`) REFERENCES `department` (`deptID`),
  ADD CONSTRAINT `athletesprofile_ibfk_4` FOREIGN KEY (`coachID`) REFERENCES `coach` (`userName`),
  ADD CONSTRAINT `athletesprofile_ibfk_5` FOREIGN KEY (`deanID`) REFERENCES `dean` (`userName`);

--
-- Constraints for table `coach`
--
ALTER TABLE `coach`
  ADD CONSTRAINT `coach_ibfk_1` FOREIGN KEY (`userName`) REFERENCES `registration` (`userName`);

--
-- Constraints for table `dean`
--
ALTER TABLE `dean`
  ADD CONSTRAINT `dean_ibfk_1` FOREIGN KEY (`deptID`) REFERENCES `department` (`deptID`),
  ADD CONSTRAINT `dean_ibfk_2` FOREIGN KEY (`userName`) REFERENCES `registration` (`userName`);

--
-- Constraints for table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`tournamentManager`) REFERENCES `tournamentmanager` (`userName`);

--
-- Constraints for table `tournamentmanager`
--
ALTER TABLE `tournamentmanager`
  ADD CONSTRAINT `tournamentmanager_ibfk_1` FOREIGN KEY (`userName`) REFERENCES `registration` (`userName`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
