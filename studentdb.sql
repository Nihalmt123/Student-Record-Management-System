-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2025 at 12:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Database: `u369022262_studentdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `mark`
--

CREATE TABLE `mark` (
  `id` int(11) NOT NULL,
  `admission_no` int(11) NOT NULL,
  `exam_name` varchar(100) NOT NULL,
  `english` int(11) NOT NULL,
  `sec_language` int(11) NOT NULL,
  `maths` int(11) NOT NULL,
  `php` int(11) NOT NULL,
  `java` int(11) NOT NULL,
  `dbms` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mark`
--

INSERT INTO `mark` (`id`, `admission_no`, `exam_name`, `english`, `sec_language`, `maths`, `php`, `java`, `dbms`) VALUES
(1, 17, '1st sem exam', 33, 34, 66, 55, 55, 55),

-- --------------------------------------------------------

--
-- Table structure for table `marks`
--

CREATE TABLE `marks` (
  `id` int(11) NOT NULL,
  `admission_no` varchar(50) NOT NULL,
  `exam_name` varchar(50) NOT NULL,
  `english` int(11) NOT NULL,
  `sec_language` int(11) NOT NULL,
  `maths` int(11) NOT NULL,
  `php` int(11) NOT NULL,
  `dbms` int(11) NOT NULL,
  `java` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `marks`
--

INSERT INTO `marks` (`id`, `admission_no`, `exam_name`, `english`, `sec_language`, `maths`, `php`, `dbms`, `java`) VALUES
(0, '20', '2nd sem exam', 45, 34, 66, 55, 55, 55),

-- --------------------------------------------------------
-- Table structure for table `student`

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `admission_no` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phno` varchar(13) NOT NULL,
  `department` varchar(50) NOT NULL,
  `semester` varchar(10) NOT NULL,
  `image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `admission_no`, `name`, `phno`, `department`, `semester`, `image`) VALUES
(20, 17, 'Naryan', '9855588', 'BVOC MAD', '2ND', 'Capture.PNG'),
(21, 21, 'Roshan', '46564', 'BVOC MAD', '2ND', '1748773406_bitnami.ico'),
(31, 22, 'Hakm', '34565555', 'BVOC MAD', '3RD', '1748773453_module_table_top.png'),
(40, 51, 'roshan', '9842662970', 'BVOC MAD', '1ST', 'null'),
(42, 1, 'nabin', '9860205615', 'BVOC MAD', '3RD', 'null'),
(44, 2, 'indra', '98448', 'BVOC MAD', '3RD', 'null'),
(46, 1222, 'Rayyan', '12345666', 'BVOC MAD', '3RD', '1748531116_WhatsApp_Image_2024-12-25_at_20.37.15_180a30a5.jpg'),
(47, 2333, 'Nihal', '8656556565', 'BSC CS', '1st', '1748535776_WhatsApp_Image_2024-12-25_at_20.37.15_180a30a5.jpg'),
(48, 66178, 'Ameen', '993475678', 'BSC MATHS', '3rd', '1748773339_bitnami.ico');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mark`
--
ALTER TABLE `mark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rollno` (`admission_no`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mark`
--
ALTER TABLE `mark`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
