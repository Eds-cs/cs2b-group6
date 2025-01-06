-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2025 at 08:15 AM
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
-- Database: `ccs`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `account_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `middle_initial` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`account_id`, `first_name`, `last_name`, `middle_initial`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Carlos', 'Machutes', 'Q', 'machutes@gmail.com', '$2y$10$4BqQhFJwGSIvbj2OVJITQuAPyfFm2KqHIkEULSW1Bf/LahOrQhp4S', '2024-12-15 13:55:41', '2025-01-05 18:44:45'),
(2, 'Kiara', 'Cando', 'M', 'cando@gmail.com', '$2y$10$4RQPGxzZL6h4.DOriScC8u5eu.8uk2EVWG0Ic0kuFc1JFWEY8XjHC', '2024-12-15 14:04:11', '2025-01-05 18:44:53'),
(3, 'Chester', 'Candido', 'W', 'candido@gmail.com', '$2y$10$0UDLyr77G8dwiG.L.xHJM.IzFJx86zic0UTW.EpwLoYIR7IepVUAq', '2024-12-15 14:06:56', '2025-01-05 18:44:34'),
(5, 'Mezjra Mae', 'Bagtasos', '', 'mabs@email.com', '$2y$10$/1dCwCDec8zP8fvUB4OjmecNBSxcdzfvAIMEBB8UDRzTQaqKqYfuC', '2024-12-15 15:34:09', '2025-01-05 06:42:18'),
(6, 'Dean', 'Name', 'A', 'dean@gmail.com', '$2y$10$6WmJ06hQh37j/4DvMp6YUORB6kVAHDjdIDD5YXn7HRgI8xynQ8Msa', '2024-12-20 05:23:50', '2024-12-24 23:34:46'),
(8, 'edilberto ', 'dela cruz', 'M', 'eds@email.com', '123', '2024-12-21 02:21:39', '2024-12-21 02:21:39'),
(16, 'neiljam', 'test', '', 'bam@gmail.com', '$2y$10$uerjKc9t3VMcGGXkf4ltDO.4eoDFUfeuW9CrcGAhS1x', '2024-12-22 06:19:51', '2024-12-22 06:19:51'),
(29, 'adviser', 'test', 'a', 'adv@email.com', '$2y$10$w.OlfHHGii98OOa7cHUeB.3yNzRMWR8QlnfSKaCandUL58RiVz7JS', '2024-12-23 18:32:41', '2025-01-03 19:19:10'),
(31, 'edds', 'test', '', '123@email.com', '$2y$10$nHvRQYspsyr8AqFKYhUFEuj.nyM7rZ6/eUwtF8KAPTPvXSN7YSCd.', '2024-12-24 08:49:29', '2024-12-24 08:49:29'),
(32, 'Finn', 'Human', 'the', 'finn@email.com', '$2y$10$RreTstKWjycfF4qZXij2gO1ldeUF3uRrZmRGy3E5VrI214N8crxLi', '2024-12-24 23:36:00', '2024-12-24 23:36:18'),
(40, 'test', 'acc', '', 'test@email.com', '$2y$10$ogw0JhXbHm5P8CgommX4ZOwFlGEF3KiLMqAJlgevGWh2DmO25C0uu', '2025-01-03 19:40:39', '2025-01-03 19:40:39');

-- --------------------------------------------------------

--
-- Table structure for table `account_roles`
--

CREATE TABLE `account_roles` (
  `account_roles_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `date_updated` date NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_roles`
--

INSERT INTO `account_roles` (`account_roles_id`, `account_id`, `role_id`, `date_updated`, `is_active`) VALUES
(1, 1, 4, '2025-01-06', 1),
(2, 2, 4, '2025-01-06', 1),
(3, 3, 3, '2025-01-06', 1),
(5, 5, 2, '2025-01-05', 1),
(6, 6, 5, '2024-12-25', 1),
(8, 8, 1, '2024-12-21', 1),
(25, 29, 4, '2025-01-04', 1),
(27, 31, 1, '2024-12-24', 1),
(28, 32, 3, '2024-12-25', 1),
(36, 40, 6, '2025-01-04', 1);

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `course_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `course_name` varchar(200) NOT NULL,
  `yr_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`course_id`, `teacher_id`, `course_name`, `yr_level`) VALUES
(5, 3, 'BSCS', 1),
(6, 1, 'BSCS', 2),
(8, 2, 'BSIT', 1),
(9, 2, 'BSIT', 2),
(10, 11, 'ACT', 1),
(11, 2, 'ACT', 2),
(12, 3, 'BSCS', 3),
(13, 1, 'BSCS ', 4),
(14, 1, 'BSIT', 3),
(15, 2, 'BSIT', 4);

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `organization_id` int(11) NOT NULL,
  `organization_name` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`organization_id`, `organization_name`, `created_at`, `updated_at`) VALUES
(1, 'College Student Council', '2024-12-15 13:48:05', '2024-12-15 13:48:05'),
(2, 'PHICCS', '2024-12-15 13:48:05', '2024-12-15 13:48:05'),
(3, 'Gender Club', '2024-12-15 13:48:14', '2024-12-15 13:48:14'),
(12, 'Venom Publication', '2025-01-04 02:17:27', '2025-01-04 02:17:27');

-- --------------------------------------------------------

--
-- Table structure for table `organization_fees`
--

CREATE TABLE `organization_fees` (
  `organization_fee_id` int(11) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `fee_name` varchar(200) NOT NULL,
  `fee_amount` decimal(15,0) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `old_fee` decimal(15,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organization_fees`
--

INSERT INTO `organization_fees` (`organization_fee_id`, `organization_id`, `fee_name`, `fee_amount`, `created_at`, `updated_at`, `old_fee`) VALUES
(1, 1, 'athletics fee', 100, '2024-12-15 15:38:50', '2024-12-15 15:38:50', 0),
(2, 3, 'clearance fee', 200, '2024-12-15 15:39:39', '2024-12-15 15:39:39', NULL),
(3, 2, 'clearance fee', 500, '2024-12-15 15:40:05', '2024-12-15 15:40:05', NULL),
(4, 3, 'confidential', 500, '2024-12-19 09:31:42', '2024-12-19 09:31:42', 0),
(11, 12, 'Test Fee', 100, '2025-01-04 02:17:40', '2025-01-04 02:17:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `organization_officers`
--

CREATE TABLE `organization_officers` (
  `officer_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `organization_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organization_officers`
--

INSERT INTO `organization_officers` (`officer_id`, `student_id`, `organization_id`) VALUES
(1, 1, 1),
(2, 9, 3),
(3, 9, 3),
(4, 9, 12);

-- --------------------------------------------------------

--
-- Table structure for table `payment_history`
--

CREATE TABLE `payment_history` (
  `payment_history_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `organization_fee_id` int(11) NOT NULL,
  `amount_paid` decimal(15,0) NOT NULL,
  `date_paid` date NOT NULL DEFAULT current_timestamp(),
  `balance` decimal(10,0) NOT NULL,
  `status` enum('paid','not_paid','partial') NOT NULL,
  `received_by` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_history`
--

INSERT INTO `payment_history` (`payment_history_id`, `student_id`, `organization_fee_id`, `amount_paid`, `date_paid`, `balance`, `status`, `received_by`) VALUES
(2, 1, 3, 3, '2024-12-19', 0, 'paid', '1'),
(3, 1, 3, 3, '2024-12-19', 0, 'paid', '1'),
(4, 1, 3, 3, '2024-12-19', 0, 'paid', '1'),
(5, 1, 3, 4, '2024-12-19', 0, 'paid', '1'),
(6, 1, 3, 5, '2024-12-19', 0, 'paid', '1'),
(7, 1, 3, 5, '2024-12-19', 0, 'paid', '1'),
(8, 1, 3, 6, '2024-12-19', 0, 'paid', '1'),
(9, 1, 3, 7, '2024-12-19', 0, 'paid', '1'),
(10, 1, 3, 3, '2024-12-19', 0, 'paid', '1'),
(11, 1, 3, 5, '2024-12-19', 0, 'paid', '1'),
(12, 1, 3, 4, '2024-12-19', 0, 'paid', '1'),
(13, 1, 3, 2, '2024-12-19', 0, 'paid', '1'),
(14, 1, 3, 10, '2024-12-19', 0, 'paid', '1'),
(15, 1, 3, 5, '2024-12-19', 0, 'paid', '1'),
(16, 1, 3, 5, '2024-12-19', 0, 'paid', '1'),
(17, 1, 3, 430, '2024-12-19', 0, 'paid', '1'),
(18, 1, 2, 200, '2024-12-19', 0, 'paid', '1'),
(21, 1, 4, 100, '2024-12-19', 0, 'paid', '1'),
(22, 1, 4, 400, '2024-12-19', 0, 'paid', '1'),
(23, 1, 1, 20, '2024-12-20', 0, 'paid', '5'),
(24, 1, 1, 80, '2024-12-20', 0, 'paid', '5'),
(25, 8, 1, 100, '2025-01-05', 0, 'paid', '5'),
(26, 8, 11, 100, '2025-01-05', 0, 'paid', '5'),
(27, 8, 3, 100, '2025-01-05', 0, 'paid', '5'),
(28, 8, 3, 400, '2025-01-05', 0, 'paid', '5'),
(29, 8, 2, 200, '2025-01-05', 0, 'paid', '5'),
(30, 8, 4, 500, '2025-01-05', 0, 'paid', '5'),
(31, 1, 11, 100, '2025-01-05', 0, 'paid', '5'),
(32, 9, 1, 100, '2025-01-06', 0, 'paid', '5');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'system_admin'),
(2, 'organization_officer'),
(3, 'student'),
(4, 'adviser'),
(5, 'dean'),
(6, 'student_affairs');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `student_number` int(11) NOT NULL,
  `is_regular` tinyint(1) NOT NULL,
  `contact_number` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `clearance_id` int(11) DEFAULT NULL COMMENT 'provided only when dean approves'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `student_number`, `is_regular`, `contact_number`, `course_id`, `account_id`, `clearance_id`) VALUES
(1, 123, 1, 4321, 5, 3, 1),
(8, 43407133, 1, 2147483647, 5, 32, 1),
(9, 123321, 1, 918273645, 13, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_clearance`
--

CREATE TABLE `student_clearance` (
  `student_clearance_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `approver_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `organization_id` int(11) DEFAULT NULL,
  `date_approved` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_clearance`
--

INSERT INTO `student_clearance` (`student_clearance_id`, `student_id`, `approver_id`, `role_id`, `organization_id`, `date_approved`) VALUES
(1, 1, 1, 4, NULL, '2024-12-15'),
(2, 1, 1, 4, 1, '2024-12-19'),
(4, 1, 1, 4, 2, '2024-12-19'),
(5, 1, 1, 4, 3, '2024-12-19'),
(14, 1, 5, 1, 12, '2025-01-06'),
(15, 8, 5, 1, 1, '2025-01-06'),
(16, 8, 5, 1, 2, '2025-01-06'),
(17, 8, 5, 1, 3, '2025-01-06'),
(18, 8, 5, 1, 12, '2025-01-06'),
(30, 1, 2, 4, 0, '2025-01-06'),
(31, 1, 40, 6, 0, '2025-01-06'),
(34, 1, 6, 5, 0, '2025-01-06'),
(35, 8, 6, 5, 0, '2025-01-06'),
(36, 9, 6, 5, 0, '2025-01-06');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_profiles`
--

CREATE TABLE `teacher_profiles` (
  `teacher_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_profiles`
--

INSERT INTO `teacher_profiles` (`teacher_id`, `account_id`) VALUES
(1, 1),
(2, 2),
(3, 29),
(11, 40);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `account_roles`
--
ALTER TABLE `account_roles`
  ADD PRIMARY KEY (`account_roles_id`),
  ADD KEY `account_roles_ibfk_1` (`account_id`),
  ADD KEY `account_roles_ibfk_2` (`role_id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`course_id`),
  ADD KEY `course_ibfk_1` (`teacher_id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`organization_id`);

--
-- Indexes for table `organization_fees`
--
ALTER TABLE `organization_fees`
  ADD PRIMARY KEY (`organization_fee_id`),
  ADD KEY `organization_id` (`organization_id`);

--
-- Indexes for table `organization_officers`
--
ALTER TABLE `organization_officers`
  ADD PRIMARY KEY (`officer_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `organization_id` (`organization_id`);

--
-- Indexes for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD PRIMARY KEY (`payment_history_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `organization_fee_id` (`organization_fee_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `account_role_id` (`account_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `clearance_id` (`clearance_id`);

--
-- Indexes for table `student_clearance`
--
ALTER TABLE `student_clearance`
  ADD PRIMARY KEY (`student_clearance_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `approver_id` (`approver_id`) USING BTREE,
  ADD KEY `organization_id` (`organization_id`);

--
-- Indexes for table `teacher_profiles`
--
ALTER TABLE `teacher_profiles`
  ADD PRIMARY KEY (`teacher_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `account_roles`
--
ALTER TABLE `account_roles`
  MODIFY `account_roles_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `organization_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `organization_fees`
--
ALTER TABLE `organization_fees`
  MODIFY `organization_fee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `organization_officers`
--
ALTER TABLE `organization_officers`
  MODIFY `officer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payment_history`
--
ALTER TABLE `payment_history`
  MODIFY `payment_history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `student_clearance`
--
ALTER TABLE `student_clearance`
  MODIFY `student_clearance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `teacher_profiles`
--
ALTER TABLE `teacher_profiles`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account_roles`
--
ALTER TABLE `account_roles`
  ADD CONSTRAINT `account_roles_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`),
  ADD CONSTRAINT `account_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher_profiles` (`teacher_id`);

--
-- Constraints for table `organization_fees`
--
ALTER TABLE `organization_fees`
  ADD CONSTRAINT `organization_fees_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`organization_id`);

--
-- Constraints for table `organization_officers`
--
ALTER TABLE `organization_officers`
  ADD CONSTRAINT `organization_officers_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `organization_officers_ibfk_2` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`organization_id`);

--
-- Constraints for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD CONSTRAINT `payment_history_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `payment_history_ibfk_2` FOREIGN KEY (`organization_fee_id`) REFERENCES `organization_fees` (`organization_fee_id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `clearance_id` FOREIGN KEY (`clearance_id`) REFERENCES `student_clearance` (`student_clearance_id`),
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`),
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`);

--
-- Constraints for table `student_clearance`
--
ALTER TABLE `student_clearance`
  ADD CONSTRAINT `student_clearance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `student_clearance_ibfk_2` FOREIGN KEY (`approver_id`) REFERENCES `accounts` (`account_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
