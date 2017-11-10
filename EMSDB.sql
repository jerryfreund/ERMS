-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 08, 2017 at 03:36 AM
-- Server version: 5.7.20-0ubuntu0.16.04.1
-- PHP Version: 7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `EMSDB`
--
CREATE DATABASE IF NOT EXISTS `EMSDB` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `EMSDB`;

-- --------------------------------------------------------

--
-- Table structure for table `Additional_ESF`
--

DROP TABLE IF EXISTS `Additional_ESF`;
CREATE TABLE `Additional_ESF` (
  `Username` varchar(30) NOT NULL,
  `ResourceID` int(11) NOT NULL,
  `ESF_ID` int(11) NOT NULL,
  `Additional_ESF` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Capabilities`
--

DROP TABLE IF EXISTS `Capabilities`;
CREATE TABLE `Capabilities` (
  `Username` varchar(30) NOT NULL,
  `ResourceID` int(11) NOT NULL,
  `Capabilities` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Companies`
--

DROP TABLE IF EXISTS `Companies`;
CREATE TABLE `Companies` (
  `Username` varchar(30) NOT NULL,
  `Headquarter` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Costs`
--

DROP TABLE IF EXISTS `Costs`;
CREATE TABLE `Costs` (
  `Username` varchar(30) NOT NULL,
  `ResourceID` int(11) NOT NULL,
  `Dollar` decimal(10,2) DEFAULT NULL,
  `Unit` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ESF`
--

DROP TABLE IF EXISTS `ESF`;
CREATE TABLE `ESF` (
  `ESF_ID` int(11) NOT NULL,
  `ESF_Description` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Governments`
--

DROP TABLE IF EXISTS `Governments`;
CREATE TABLE `Governments` (
  `Username` varchar(30) NOT NULL,
  `Jurisdiction` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Incidents`
--

DROP TABLE IF EXISTS `Incidents`;
CREATE TABLE `Incidents` (
  `Username` varchar(30) NOT NULL,
  `IncidentID` int(11) NOT NULL,
  `IncidentDate` date NOT NULL,
  `Description` text NOT NULL,
  `Longitude` float NOT NULL,
  `Latitude` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Individuals`
--

DROP TABLE IF EXISTS `Individuals`;
CREATE TABLE `Individuals` (
  `Username` varchar(30) NOT NULL,
  `JobTitle` varchar(50) NOT NULL,
  `HireDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Municipalities`
--

DROP TABLE IF EXISTS `Municipalities`;
CREATE TABLE `Municipalities` (
  `Username` varchar(30) NOT NULL,
  `PopulationSize` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Repairs`
--

DROP TABLE IF EXISTS `Repairs`;
CREATE TABLE `Repairs` (
  `Username` varchar(30) NOT NULL,
  `ResourceID` int(11) NOT NULL,
  `StartDate` datetime DEFAULT NULL,
  `EndDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Requests`
--

DROP TABLE IF EXISTS `Requests`;
CREATE TABLE `Requests` (
  `Username` varchar(30) NOT NULL,
  `IncidentID` int(11) NOT NULL,
  `ResourceID` int(11) NOT NULL,
  `ReturnDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Resources`
--

DROP TABLE IF EXISTS `Resources`;
CREATE TABLE `Resources` (
  `Username` varchar(30) NOT NULL,
  `ResourceID` int(11) NOT NULL,
  `ResourceName` varchar(50) DEFAULT NULL,
  `ESF_ID` int(11) NOT NULL,
  `ResourceOwner` varchar(30) DEFAULT NULL,
  `CurrentResourceUser` varchar(50) DEFAULT NULL,
  `Status` varchar(20) DEFAULT NULL,
  `Latitude` float DEFAULT NULL,
  `Longitude` float DEFAULT NULL,
  `Model` varchar(30) DEFAULT NULL,
  `DateOfRequest` date DEFAULT NULL,
  `Additional_ESF` int(11) DEFAULT NULL,
  `ExpectedReturnDate` datetime DEFAULT NULL,
  `IncidentID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users` (
  `Username` varchar(30) NOT NULL,
  `Password` varchar(28) NOT NULL,
  `Name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Additional_ESF`
--
ALTER TABLE `Additional_ESF`
  ADD UNIQUE KEY `Username` (`Username`,`ResourceID`,`ESF_ID`,`Additional_ESF`);

--
-- Indexes for table `Capabilities`
--
ALTER TABLE `Capabilities`
  ADD UNIQUE KEY `Username` (`Username`,`ResourceID`,`Capabilities`);

--
-- Indexes for table `Companies`
--
ALTER TABLE `Companies`
  ADD PRIMARY KEY (`Username`);

--
-- Indexes for table `Costs`
--
ALTER TABLE `Costs`
  ADD PRIMARY KEY (`Username`,`ResourceID`);

--
-- Indexes for table `ESF`
--
ALTER TABLE `ESF`
  ADD PRIMARY KEY (`ESF_ID`);

--
-- Indexes for table `Governments`
--
ALTER TABLE `Governments`
  ADD PRIMARY KEY (`Username`);

--
-- Indexes for table `Incidents`
--
ALTER TABLE `Incidents`
  ADD PRIMARY KEY (`Username`,`IncidentID`);

--
-- Indexes for table `Individuals`
--
ALTER TABLE `Individuals`
  ADD PRIMARY KEY (`Username`);

--
-- Indexes for table `Municipalities`
--
ALTER TABLE `Municipalities`
  ADD PRIMARY KEY (`Username`);

--
-- Indexes for table `Repairs`
--
ALTER TABLE `Repairs`
  ADD PRIMARY KEY (`Username`,`ResourceID`);

--
-- Indexes for table `Requests`
--
ALTER TABLE `Requests`
  ADD PRIMARY KEY (`Username`,`IncidentID`,`ResourceID`);

--
-- Indexes for table `Resources`
--
ALTER TABLE `Resources`
  ADD PRIMARY KEY (`Username`,`ResourceID`,`ESF_ID`),
  ADD KEY `ESF_ID` (`ESF_ID`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ESF`
--
ALTER TABLE `ESF`
  MODIFY `ESF_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `Additional_ESF`
--
ALTER TABLE `Additional_ESF`
  ADD CONSTRAINT `Additional_ESF_ibfk_1` FOREIGN KEY (`Username`,`ResourceID`,`ESF_ID`) REFERENCES `Resources` (`Username`, `ResourceID`, `ESF_ID`);

--
-- Constraints for table `Capabilities`
--
ALTER TABLE `Capabilities`
  ADD CONSTRAINT `Capabilities_ibfk_1` FOREIGN KEY (`Username`,`ResourceID`) REFERENCES `Resources` (`Username`, `ResourceID`);

--
-- Constraints for table `Companies`
--
ALTER TABLE `Companies`
  ADD CONSTRAINT `Companies_ibfk_1` FOREIGN KEY (`Username`) REFERENCES `Users` (`Username`);

--
-- Constraints for table `Costs`
--
ALTER TABLE `Costs`
  ADD CONSTRAINT `Costs_ibfk_1` FOREIGN KEY (`Username`,`ResourceID`) REFERENCES `Resources` (`Username`, `ResourceID`);

--
-- Constraints for table `Governments`
--
ALTER TABLE `Governments`
  ADD CONSTRAINT `Governments_ibfk_1` FOREIGN KEY (`Username`) REFERENCES `Users` (`Username`);

--
-- Constraints for table `Incidents`
--
ALTER TABLE `Incidents`
  ADD CONSTRAINT `Incidents_ibfk_1` FOREIGN KEY (`Username`) REFERENCES `Users` (`Username`);

--
-- Constraints for table `Individuals`
--
ALTER TABLE `Individuals`
  ADD CONSTRAINT `Individuals_ibfk_1` FOREIGN KEY (`Username`) REFERENCES `Users` (`Username`);

--
-- Constraints for table `Municipalities`
--
ALTER TABLE `Municipalities`
  ADD CONSTRAINT `Municipalities_ibfk_1` FOREIGN KEY (`Username`) REFERENCES `Users` (`Username`);

--
-- Constraints for table `Repairs`
--
ALTER TABLE `Repairs`
  ADD CONSTRAINT `Repairs_ibfk_1` FOREIGN KEY (`Username`,`ResourceID`) REFERENCES `Resources` (`Username`, `ResourceID`);

--
-- Constraints for table `Requests`
--
ALTER TABLE `Requests`
  ADD CONSTRAINT `Requests_ibfk_1` FOREIGN KEY (`Username`,`IncidentID`) REFERENCES `Incidents` (`Username`, `IncidentID`);

--
-- Constraints for table `Resources`
--
ALTER TABLE `Resources`
  ADD CONSTRAINT `Resources_ibfk_1` FOREIGN KEY (`Username`) REFERENCES `Users` (`Username`),
  ADD CONSTRAINT `Resources_ibfk_2` FOREIGN KEY (`ESF_ID`) REFERENCES `ESF` (`ESF_ID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
