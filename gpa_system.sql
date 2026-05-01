-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 01 مايو 2026 الساعة 20:03
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gpa_system`
--

-- --------------------------------------------------------

--
-- بنية الجدول `assignments`
--

CREATE TABLE `assignments` (
  `id` int(11) NOT NULL,
  `professor_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `assignments`
--

INSERT INTO `assignments` (`id`, `professor_id`, `course_id`, `semester_id`, `assigned_at`) VALUES
(5, 14, 12, 3, '2026-05-01 16:55:09');

-- --------------------------------------------------------

--
-- بنية الجدول `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `credits` int(11) NOT NULL CHECK (`credits` > 0),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `courses`
--

INSERT INTO `courses` (`id`, `semester_id`, `name`, `credits`, `created_at`) VALUES
(1, 1, 'رياضيات 1', 3, '2026-04-29 11:53:31'),
(2, 1, 'برمجة 1', 4, '2026-04-29 11:53:31'),
(3, 1, 'قواعد بيانات', 3, '2026-04-29 11:53:31'),
(4, 1, 'هندسة برمجيات', 3, '2026-04-29 11:53:31'),
(5, 1, 'Mathematics', 4, '2026-05-01 09:05:35'),
(6, 1, 'Physics', 3, '2026-05-01 09:05:35'),
(7, 1, 'Programming', 4, '2026-05-01 09:05:35'),
(8, 1, 'English', 2, '2026-05-01 09:05:35'),
(11, 3, 'Base de donneé', 3, '2026-05-01 09:44:50'),
(12, 3, 'Développement d&#039;applictons', 2, '2026-05-01 09:45:44'),
(13, 3, 'Réseaaux', 3, '2026-05-01 09:47:21'),
(15, 3, 'Théorie des langages', 3, '2026-05-01 09:49:47'),
(16, 3, 'Systéme d&#039;explotion 1', 3, '2026-05-01 10:02:44'),
(17, 4, 'Base de donneé', 2, '2026-05-01 17:03:47'),
(18, 4, 'Développement d&#039;applictons', 3, '2026-05-01 17:04:04'),
(19, 4, 'Réseaaux', 3, '2026-05-01 17:04:19'),
(20, 4, 'ALGO', 2, '2026-05-01 17:04:44'),
(21, 4, 'Base de donneé', 2, '2026-05-01 17:20:10');

-- --------------------------------------------------------

--
-- بنية الجدول `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `gpa_records`
--

CREATE TABLE `gpa_records` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `gpa` decimal(3,2) NOT NULL,
  `calculated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `grades`
--

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `professor_id` int(11) NOT NULL,
  `grade` decimal(3,1) NOT NULL CHECK (`grade` in (0.0,1.0,2.0,3.0,4.0)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `semesters`
--

CREATE TABLE `semesters` (
  `id` int(11) NOT NULL,
  `label` varchar(20) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `semesters`
--

INSERT INTO `semesters` (`id`, `label`, `academic_year`, `is_active`, `created_at`) VALUES
(1, 'S1', '2024/2025', 0, '2026-04-29 11:53:31'),
(2, 'S1', '2024/2025', 1, '2026-05-01 09:05:35'),
(3, 'S1', '2025/2026', 0, '2026-05-01 09:15:37'),
(4, 'S2', '2025/2026', 0, '2026-05-01 17:02:24');

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','professor','student') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(13, 'مدير النظام', 'admin@system.com', '$2y$10$6K7khSXT.FNfCBWbQhUa/.9r4BT5lXHXLHtaFNYj0TFyiikNH1Hgu', 'admin', '2026-04-29 12:26:18'),
(14, 'دكتور أحمد', 'professor@system.com', '$2y$10$QSWHPCQXENIj3OFyCx5W0u2vWSLl5cSTWlltSAVgA7Yi5sWTbOoua', 'professor', '2026-04-29 12:26:19'),
(16, 'Yassin Berchaoua', 'student@school.com', '$2y$10$G1nWJJApCdYkVlZWMTjQne8ZVXAQA8UCH92BN7qWH7.ARJmgT.iTq', 'student', '2026-05-01 17:07:36'),
(17, 'عبد الناصر ولابي', 'school@student.com', '$2y$10$jCaM5WEhP/3HOWoHlDVueeHsc0.iN.ZksKtmDGGIArUNGxoUYstTC', 'student', '2026-05-01 17:10:40'),
(18, 'ايمن جديد', 'amin@student.com', '$2y$10$B/3apA4q0QZYcFlnr0DEdeGrXRmD3HvBDee3Lz208GljzVToNbenq', 'student', '2026-05-01 17:11:30'),
(19, 'محمد منصوري', 'mahmed@stuent.com', '$2y$10$AlHDkxvCzdDTsymCzj8UruORi0.9nRovlnu7XEqkeQg60Tvq36tEG', 'student', '2026-05-01 17:12:26'),
(20, 'بونجاح محمد المكي', 'baonhga@student.com', '$2y$10$v/tCIPAzdk/mgDuIuD0sW.eF78PGJHVhlotLA5bRknClDd0d6PP4q', 'student', '2026-05-01 17:13:22'),
(21, 'دكتور عبد الفتاح', 'Abd-FATAH@professo.com', '$2y$10$T1Vs50wDoNP1.44EJcOHKewy8gWxh.wRGts0UurU7XdqKotVmqyDG', 'professor', '2026-05-01 17:19:47'),
(22, 'Admin', 'admin@school.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2026-05-01 17:58:20'),
(23, 'Professor Smith', 'prof@school.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'professor', '2026-05-01 17:58:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_assign` (`professor_id`,`course_id`,`semester_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `semester_id` (`semester_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `semester_id` (`semester_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_enroll` (`student_id`,`semester_id`),
  ADD KEY `semester_id` (`semester_id`);

--
-- Indexes for table `gpa_records`
--
ALTER TABLE `gpa_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_gpa` (`student_id`,`semester_id`),
  ADD KEY `semester_id` (`semester_id`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_grade` (`student_id`,`course_id`,`semester_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `semester_id` (`semester_id`),
  ADD KEY `professor_id` (`professor_id`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `gpa_records`
--
ALTER TABLE `gpa_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `assignments_ibfk_1` FOREIGN KEY (`professor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assignments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assignments_ibfk_3` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `gpa_records`
--
ALTER TABLE `gpa_records`
  ADD CONSTRAINT `gpa_records_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gpa_records_ibfk_2` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grades_ibfk_3` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grades_ibfk_4` FOREIGN KEY (`professor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
