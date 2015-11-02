-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Oct 08, 2015 at 04:11 AM
-- Server version: 5.5.42
-- PHP Version: 5.5.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `ezadvising`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `first` varchar(100) NOT NULL,
  `middle` varchar(100) DEFAULT NULL,
  `last` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `password`, `type`, `first`, `middle`, `last`, `email`) VALUES
(1, 'crystal', 'crystal', 'faculty', 'Crystal', 'Kay', 'Cox', 'crystal@coastal.edu'),
(2, 'tori', 'tori', 'student', 'Tori', 'Brooke', 'Jordan', 'tbjordan@g.coastal.edu');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `dept` varchar(10) NOT NULL,
  `num` varchar(10) NOT NULL,
  `dif` DOUBLE NOT NULL,   -- difficulty rating (1-10)
  `dr` FLOAT NOT NULL,     -- drop rate %
  `fr` FLOAT NOT NULL,     -- fail rate %
  `prereqs` text,
  `defaultCreditHours` int(11) NOT NULL,
  `title` text,
  `description` text,
  `semestersOffered` varchar(30) NOT NULL COMMENT 'bitmask -positions match semester code, Y for offered, N for no, M for maybe'
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
--
--  Difficulty determining algorithm
--



--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `dept`, `num`,`dif`,`dr`, `fr`, `prereqs`, `defaultCreditHours`, `title`, `description`, `semestersOffered`) VALUES
(1, 'CSCI', '140',0.0 , 0.05, 0.05, '' , 3, 'Introduction to Algorithm Design I', 'description for CSCI 140', 'YYNNNM');
-- (2, 'CSCI', '140L', , 0.05, 0.05, '' , 1, 'Introduction to Algorithm Design I Lab', 'description for lab', 'YYNNNM'),
-- (3, 'CSCI', '150', '1 and 2', 3, 'Introduction to Algorithm Design II', 'description for CSCI 150', 'YYNNNM'),
-- (4, 'CSCI', '150L', '1 and 2', 1, 'Introduction to Algorithm Design II Lab', 'descriptino for CSCI 150L', 'YYNNNM'),
-- (5, 'CSCI', '225', '', 3, 'Introduction to Relational Database and SQL', 'description for CSCI 225', 'YYNNNM'),
-- (6, 'CSCI', '203', '6 and 7', 3, 'Introduction to Web Application Development', 'description for CSCI 203', 'YYNNNM'),
-- (7, 'ENGL', '211', NULL, 3, 'Technical Writing', 'description for technical writing', 'YYNNNM'),
-- (8, 'ENGL', '290', NULL, 3, 'Business Communication', 'description for Engl 290', 'YYNNNM'),
-- (9, 'CSCI', '330', NULL, 3, 'Software Engineering I', 'description for SE I', 'YYNNNM'),
-- (10, 'CSCI', '490', '8', 3, 'Software Engineering II', 'SE II description', 'YNNNNN'),
-- (11, 'CSCI', '434', NULL, 3, 'Forensics', 'Description of forensics', 'YYNNNM'),
-- (12, 'CSCI', '211', NULL, 3, 'Computer Infrastructure', 'description of 211', 'NYNNNM'),
-- (13, 'CSCI', '350', '', 3, 'Programming Languages', 'description of programming languages', 'YNNNNN');

-- --------------------------------------------------------

--
-- Table structure for table `course_groups`
--

CREATE TABLE `course_groups` (
  `id` int(11) NOT NULL,
  `groupId` int(11) NOT NULL,
  `courseId` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `course_groups`
--

INSERT INTO `course_groups` (`id`, `groupId`, `courseId`) VALUES
(1, 1, 7),
(2, 1, 8),
(3, 2, 5),
(4, 2, 6),
(5, 3, 10),
(6, 3, 11),
(7, 4, 9),
(8, 5, 1),
(9, 6, 2),
(10, 7, 3),
(11, 8, 4),
(12, 2, 12),
(13, 3, 13);

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
  `groupId` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL COMMENT '2-planned, 1-completed, etc',
  `proposedReqId` int(11) DEFAULT NULL,
  `hours` int(11) NOT NULL,
  `semesterCode` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=289 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `course_records`
--

INSERT INTO `course_records` (`id`, `plan`, `studentId`, `courseId`, `grade`, `year`, `groupId`, `type`, `proposedReqId`, `hours`, `semesterCode`) VALUES
(283, '020151', 1, 7, NULL, 2015, 1, 2, NULL, 3, 1),
(284, '020162', 1, 5, NULL, 2016, 2, 2, NULL, 3, 2),
(285, '020164', 1, 10, NULL, 2016, 3, 2, NULL, 3, 4),
(286, '120166', 1, 4, NULL, 2016, 8, 2, NULL, 1, 6),
(287, '120162', 1, 7, NULL, 2016, 1, 2, NULL, 3, 2),
(288, '120162', 1, 9, NULL, 2016, 4, 2, NULL, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text,
  `programId` int(11) NOT NULL COMMENT '0 for all programs'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`, `programId`) VALUES
(1, 'Communication', NULL, 1),
(2, 'CSCI Elective 2XX', NULL, 1),
(3, 'CSCI Elective', 'CSCI Elective 300+', 1),
(4, 'Software Engineering', 'SE Class', 1),
(5, 'CSCI 140', 'CS1 ', 1),
(6, 'CSCI 140L', 'CS1', 1),
(7, 'CSCI 150', 'CS2 ', 1),
(8, 'CSCI 150L', 'CS2', 1);

-- --------------------------------------------------------

--
-- Table structure for table `prereqs`
--

CREATE TABLE `prereqs` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `courseId` int(11) NOT NULL,
  `expression` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prereqs`
--

INSERT INTO `prereqs` (`id`, `type`, `courseId`, `expression`) VALUES
(1, 1, 3, '1 and 2'),
(2, 1, 4, '1 and 2'),
(3, 1, 6, '6 and 7'),
(4, 1, 10, '8');

-- --------------------------------------------------------

--
-- Table structure for table `prereq_detail`
--

CREATE TABLE `prereq_detail` (
  `id` int(11) NOT NULL,
  `prereqId` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `courseId` int(11) DEFAULT NULL,
  `minGrade` int(11) DEFAULT NULL,
  `hours` int(11) DEFAULT NULL,
  `courseGroup` int(11) DEFAULT NULL,
  `testScore` float DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prereq_detail`
--

INSERT INTO `prereq_detail` (`id`, `prereqId`, `type`, `courseId`, `minGrade`, `hours`, `courseGroup`, `testScore`) VALUES
(1, 1, 2, 1, 2, 0, 0, 0),
(2, 2, 2, 2, 2, 0, 0, 0),
(5, 1, 2, 2, 2, NULL, NULL, NULL),
(6, 3, 2, 5, 2, NULL, NULL, NULL),
(7, 3, 2, 1, 2, NULL, NULL, NULL),
(8, 4, 2, 9, 2, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT 'major, minor, etc',
  `title` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `type`, `title`) VALUES
(1, 1, 'Information Systems');

-- --------------------------------------------------------

--
-- Table structure for table `program_requirements`
--

CREATE TABLE `program_requirements` (
  `id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `category` int(11) NOT NULL COMMENT '1-core, 2-foundation, 3- major',
  `programId` int(11) NOT NULL,
  `groupId` int(11) NOT NULL,
  `numCreditHours` int(11) NOT NULL,
  `minGrade` int(11) NOT NULL,
  `catalogYear` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `program_requirements`
--

INSERT INTO `program_requirements` (`id`, `title`, `category`, `programId`, `groupId`, `numCreditHours`, `minGrade`, `catalogYear`) VALUES
(1, 'Communication', 2, 1, 1, 2, 2, 2014),
(2, 'CSCI Elective 2XX', 3, 1, 2, 6, 2, 2014),
(3, 'CSCI Elective', 3, 1, 3, 6, 2, 2014),
(4, 'Software Engineering', 3, 1, 4, 3, 2, 2014),
(5, 'CSCI 140', 2, 1, 5, 3, 2, 2014),
(6, 'CSCI 140L', 2, 1, 6, 1, 23, 2014),
(7, 'CSCI 150', 2, 1, 7, 3, 2, 2014),
(8, 'CSCI 150L', 2, 1, 8, 1, 2, 2014);

-- --------------------------------------------------------

--
-- Table structure for table `semester_codes`
--

CREATE TABLE `semester_codes` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `level` int(11) NOT NULL COMMENT '1-fall,spring, 2-summer, 3-fall I,II, etc, 4-other',
  `duration` int(11) NOT NULL COMMENT 'in weeks',
  `order` int(11) NOT NULL COMMENT 'chronological'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `semester_codes`
--

INSERT INTO `semester_codes` (`id`, `name`, `level`, `duration`, `order`) VALUES
(1, 'Fall', 1, 16, 50),
(2, 'Spring', 1, 16, 10),
(3, 'May', 2, 3, 20),
(4, 'Summer 1', 2, 5, 30),
(5, 'Summer II', 2, 5, 40),
(6, 'Summer 8-week', 2, 8, 42);

-- --------------------------------------------------------

--
-- Table structure for table `plan_title`
--

CREATE TABLE plan_title (
  id int NOT NULL AUTO_INCREMENT,
  title varchar(25) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `accountId` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `accountId`) VALUES
(1, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `student_programs`
--

CREATE TABLE `student_programs` (
  `id` int(11) NOT NULL,
  `studentId` int(11) NOT NULL,
  `programId` int(11) NOT NULL,
  `catalogYear` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student_programs`
--

INSERT INTO `student_programs` (`id`, `studentId`, `programId`, `catalogYear`) VALUES
(1, 1, 1, 2014);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course_groups`
--
ALTER TABLE `course_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course_records`
--
ALTER TABLE `course_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prereqs`
--
ALTER TABLE `prereqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prereq_detail`
--
ALTER TABLE `prereq_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `program_requirements`
--
ALTER TABLE `program_requirements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `semester_codes`
--
ALTER TABLE `semester_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_programs`
--
ALTER TABLE `student_programs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `course_groups`
--
ALTER TABLE `course_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `course_records`
--
ALTER TABLE `course_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=289;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `prereqs`
--
ALTER TABLE `prereqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `prereq_detail`
--
ALTER TABLE `prereq_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `program_requirements`
--
ALTER TABLE `program_requirements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `semester_codes`
--
ALTER TABLE `semester_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `student_programs`
--
ALTER TABLE `student_programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;