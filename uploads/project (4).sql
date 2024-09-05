-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 05, 2024 at 05:37 AM
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
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `a_user` varchar(20) NOT NULL,
  `a_pws` varchar(15) NOT NULL,
  `a_na` varchar(50) NOT NULL,
  `a_la` varchar(50) NOT NULL,
  `a_email` varchar(50) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `a_st` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`a_user`, `a_pws`, `a_na`, `a_la`, `a_email`, `phone_number`, `a_st`) VALUES
('a123', '123', 'มังกร', 'พรมเจียม', 'oomty43@gmail.com', NULL, 1),
('g', '123', 'กร', 'เจียม', 'oomty2543@gmail.com', '0639584785', 1);

-- --------------------------------------------------------

--
-- Table structure for table `certi`
--

CREATE TABLE `certi` (
  `ce_id` int(3) NOT NULL,
  `ce_na` varchar(30) NOT NULL,
  `ce_year` varchar(4) NOT NULL,
  `og_na` varchar(50) NOT NULL,
  `ce_file` varchar(225) NOT NULL,
  `s_id` varchar(13) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `c_id` int(4) NOT NULL,
  `c_na` varchar(50) DEFAULT NULL,
  `c_add` varchar(50) DEFAULT NULL,
  `c_date` date DEFAULT NULL,
  `s_id` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`c_id`, `c_na`, `c_add`, `c_date`, `s_id`) VALUES
(1, 'ราชานักนอน23', 'บ้านฉันเอง23333', '2024-03-05', '631102064114');

-- --------------------------------------------------------

--
-- Table structure for table `edu_history`
--

CREATE TABLE `edu_history` (
  `eh_id` int(3) NOT NULL,
  `eh_na` varchar(50) NOT NULL,
  `eh_level` varchar(20) NOT NULL,
  `eh_end` varchar(4) NOT NULL,
  `s_id` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `edu_history`
--

INSERT INTO `edu_history` (`eh_id`, `eh_na`, `eh_level`, `eh_end`, `s_id`) VALUES
(3, 'เทศบาล3ชาญวิทยา', 'มัธยมศึกษา', '2018', '631102064114');

-- --------------------------------------------------------

--
-- Table structure for table `ev`
--

CREATE TABLE `ev` (
  `e_id` int(3) NOT NULL,
  `e_na` varchar(30) NOT NULL,
  `e_add` varchar(40) NOT NULL,
  `e_date` varchar(4) NOT NULL,
  `e_pic` blob NOT NULL,
  `s_id` varchar(13) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ev`
--

INSERT INTO `ev` (`e_id`, `e_na`, `e_add`, `e_date`, `e_pic`, `s_id`) VALUES
(7, 'วิ่ง10เมตร', 'วัด', '2022', '', '631102064114');

-- --------------------------------------------------------

--
-- Table structure for table `information`
--

CREATE TABLE `information` (
  `i_id` int(3) NOT NULL,
  `i_head` varchar(30) NOT NULL,
  `i_deltail` varchar(300) NOT NULL,
  `i_pic` blob NOT NULL,
  `i_cover` blob NOT NULL,
  `i_date` date NOT NULL,
  `a_id` varchar(20) NOT NULL,
  `itype_id` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `information`
--

INSERT INTO `information` (`i_id`, `i_head`, `i_deltail`, `i_pic`, `i_cover`, `i_date`, `a_id`, `itype_id`) VALUES
(21, 'หิว', 'โคตรหิวเลยเวลานี้', '', 0x4a6170616e2d466f6f645f4465736b746f70342d3132303078313230302e6a7067, '2024-08-20', 'a123', 1),
(22, 'ง่วงอยากนอน', 'นอนอนอนอนอน', '', 0x3337353033373437375f333634353330343838393033323933305f363334393831373537363630333830303631305f6e2e6a7067, '2024-08-20', 'a123', 3);

-- --------------------------------------------------------

--
-- Table structure for table `info_type`
--

CREATE TABLE `info_type` (
  `itype_id` int(3) NOT NULL,
  `itype_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `info_type`
--

INSERT INTO `info_type` (`itype_id`, `itype_name`) VALUES
(1, 'ประชาสัมพันธ์'),
(2, 'หาบุคลากร'),
(3, 'นอน');

-- --------------------------------------------------------

--
-- Table structure for table `its_history`
--

CREATE TABLE `its_history` (
  `its_id` int(3) NOT NULL,
  `its_name` varchar(50) NOT NULL,
  `its_date` int(5) NOT NULL COMMENT 'ชั่วโมงฝึกงาน',
  `its_file` varchar(225) NOT NULL,
  `s_id` varchar(13) DEFAULT NULL,
  `its_province` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `its_history`
--

INSERT INTO `its_history` (`its_id`, `its_name`, `its_date`, `its_file`, `s_id`, `its_province`) VALUES
(3, 'qweqwe', 2020, 'uploads/จิ๋วแต่แจ๋วไมโครอินเวอร์เตอร์ อินเวอร์เตอร์ขนาดเล็กที่ข้อดีมากมาย.pdf', '631102064114', 'กาฬสินธุ์');

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE `program` (
  `pg_id` int(3) NOT NULL,
  `pg_na` varchar(50) NOT NULL,
  `s_id` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`pg_id`, `pg_na`, `s_id`) VALUES
(1, 'ใช้ AI', '631102064114');

-- --------------------------------------------------------

--
-- Table structure for table `skill`
--

CREATE TABLE `skill` (
  `sk_id` int(3) NOT NULL,
  `sk_na` varchar(50) NOT NULL,
  `s_id` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skill`
--

INSERT INTO `skill` (`sk_id`, `sk_na`, `s_id`) VALUES
(2, 'กินจุมาก', '631102064114'),
(3, 'นอน', '631102064114');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `s_id` varchar(13) NOT NULL,
  `s_pws` varchar(15) NOT NULL,
  `s_pna` tinyint(2) NOT NULL COMMENT '1=นาย \r\n2=นาง \r\n3=นางสาว\r\n',
  `s_na` varchar(50) NOT NULL,
  `s_la` varchar(50) DEFAULT NULL,
  `s_email` varchar(50) DEFAULT NULL,
  `s_address` varchar(255) DEFAULT NULL,
  `s_stat` tinyint(2) DEFAULT NULL,
  `s_pic` blob DEFAULT NULL,
  `s_bloodtype` tinyint(1) DEFAULT NULL,
  `s_race` varchar(20) DEFAULT NULL,
  `s_birth` date DEFAULT NULL,
  `s_nationlity` varchar(20) DEFAULT NULL,
  `religious` tinyint(1) DEFAULT NULL,
  `s_marriage` tinyint(1) DEFAULT NULL,
  `s_province` varchar(20) DEFAULT NULL,
  `s_country` varchar(20) DEFAULT NULL,
  `s_gender` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`s_id`, `s_pws`, `s_pna`, `s_na`, `s_la`, `s_email`, `s_address`, `s_stat`, `s_pic`, `s_bloodtype`, `s_race`, `s_birth`, `s_nationlity`, `religious`, `s_marriage`, `s_province`, `s_country`, `s_gender`) VALUES
('631102064114', '123', 1, 'มัง', 'ครับผม', 'o@gmail.com', NULL, 1, 0x3337353033373437375f333634353330343838393033323933305f363334393831373537363630333830303631305f6e2e6a7067, 2, '7', '0000-00-00', '3', 4, 3, '1', 'ไทย', NULL),
('631102064115', '123', 1, 'มังงงง', 'กรรรร', 'oa@gmail.com', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wk`
--

CREATE TABLE `wk` (
  `w_id` int(3) NOT NULL,
  `w_na` varchar(50) NOT NULL,
  `w_date` varchar(4) NOT NULL,
  `s_id` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wk`
--

INSERT INTO `wk` (`w_id`, `w_na`, `w_date`, `s_id`) VALUES
(6, 'SY', '2024', '631102064114'),
(7, 'SYA', '2022', '631102064114');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`a_user`);

--
-- Indexes for table `certi`
--
ALTER TABLE `certi`
  ADD PRIMARY KEY (`ce_id`),
  ADD KEY `fk_certificate_student` (`s_id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `fk_student_id` (`s_id`);

--
-- Indexes for table `edu_history`
--
ALTER TABLE `edu_history`
  ADD PRIMARY KEY (`eh_id`),
  ADD KEY `fk_edu_history_student` (`s_id`);

--
-- Indexes for table `ev`
--
ALTER TABLE `ev`
  ADD PRIMARY KEY (`e_id`),
  ADD KEY `s_id` (`s_id`);

--
-- Indexes for table `information`
--
ALTER TABLE `information`
  ADD PRIMARY KEY (`i_id`);

--
-- Indexes for table `info_type`
--
ALTER TABLE `info_type`
  ADD PRIMARY KEY (`itype_id`);

--
-- Indexes for table `its_history`
--
ALTER TABLE `its_history`
  ADD PRIMARY KEY (`its_id`),
  ADD KEY `fk_its_history_student` (`s_id`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`pg_id`),
  ADD KEY `fk_program_student` (`s_id`);

--
-- Indexes for table `skill`
--
ALTER TABLE `skill`
  ADD PRIMARY KEY (`sk_id`),
  ADD KEY `fk_work_student` (`s_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`s_id`);

--
-- Indexes for table `wk`
--
ALTER TABLE `wk`
  ADD PRIMARY KEY (`w_id`),
  ADD KEY `fk_wk_student` (`s_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `certi`
--
ALTER TABLE `certi`
  MODIFY `ce_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `c_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `edu_history`
--
ALTER TABLE `edu_history`
  MODIFY `eh_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ev`
--
ALTER TABLE `ev`
  MODIFY `e_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `information`
--
ALTER TABLE `information`
  MODIFY `i_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `info_type`
--
ALTER TABLE `info_type`
  MODIFY `itype_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `its_history`
--
ALTER TABLE `its_history`
  MODIFY `its_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `program`
--
ALTER TABLE `program`
  MODIFY `pg_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `skill`
--
ALTER TABLE `skill`
  MODIFY `sk_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wk`
--
ALTER TABLE `wk`
  MODIFY `w_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certi`
--
ALTER TABLE `certi`
  ADD CONSTRAINT `fk_certificate_student` FOREIGN KEY (`s_id`) REFERENCES `student` (`s_id`);

--
-- Constraints for table `edu_history`
--
ALTER TABLE `edu_history`
  ADD CONSTRAINT `fk_edu_history_student` FOREIGN KEY (`s_id`) REFERENCES `student` (`s_id`);

--
-- Constraints for table `ev`
--
ALTER TABLE `ev`
  ADD CONSTRAINT `s_id` FOREIGN KEY (`s_id`) REFERENCES `student` (`s_id`);

--
-- Constraints for table `its_history`
--
ALTER TABLE `its_history`
  ADD CONSTRAINT `fk_its_history_student` FOREIGN KEY (`s_id`) REFERENCES `student` (`s_id`);

--
-- Constraints for table `program`
--
ALTER TABLE `program`
  ADD CONSTRAINT `fk_program_student` FOREIGN KEY (`s_id`) REFERENCES `student` (`s_id`);

--
-- Constraints for table `skill`
--
ALTER TABLE `skill`
  ADD CONSTRAINT `fk_skill_student` FOREIGN KEY (`s_id`) REFERENCES `student` (`s_id`),
  ADD CONSTRAINT `fk_work_student` FOREIGN KEY (`s_id`) REFERENCES `student` (`s_id`);

--
-- Constraints for table `wk`
--
ALTER TABLE `wk`
  ADD CONSTRAINT `fk_wk_student` FOREIGN KEY (`s_id`) REFERENCES `student` (`s_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
