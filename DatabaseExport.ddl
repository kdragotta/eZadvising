-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Oct 06, 2015 at 09:55 AM
-- Server version: 5.5.42
-- PHP Version: 5.5.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `ezadvising`
--

-- --------------------------------------------------------

--
-- Table structure for table `course_records`
--

CREATE TABLE `course_records` (
  `id` int(11) NOT NULL,
  `plan` varchar(10) NOT NULL,
  `studentId` int(11) NOT NULL,
  `courseId` int(11) NOT NULL,
  `grade` varchar(5) DEFAULT NULL,
  `year` int(11) NOT NULL,
  `reqId` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL COMMENT '2-planned, 1-completed, etc',
  `proposedReqId` int(11) DEFAULT NULL,
  `hours` int(11) NOT NULL,
  `semesterCode` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=247 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `course_records`
--

INSERT INTO `course_records` (`id`, `plan`, `studentId`, `courseId`, `grade`, `year`, `reqId`, `type`, `proposedReqId`, `hours`, `semesterCode`) VALUES
(245, '020163', 1, 7, NULL, 2016, 1, 2, NULL, 3, 3),
(246, '120163', 1, 7, NULL, 2016, 1, 2, NULL, 3, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `course_records`
--
ALTER TABLE `course_records`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `course_records`
--
ALTER TABLE `course_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=247;