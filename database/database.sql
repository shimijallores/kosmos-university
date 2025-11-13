-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table university.audit_trait
CREATE TABLE IF NOT EXISTS `audit_trait` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `module` varchar(50) DEFAULT 'Collections',
  `refno` varchar(50) DEFAULT NULL,
  `datetime` datetime DEFAULT CURRENT_TIMESTAMP,
  `action` enum('A','E','D') NOT NULL DEFAULT 'A',
  PRIMARY KEY (`id`),
  KEY `FK_audit_trait_users` (`user_id`),
  CONSTRAINT `FK_audit_trait_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table university.audit_trait: ~25 rows (approximately)
INSERT INTO `audit_trait` (`id`, `user_id`, `module`, `refno`, `datetime`, `action`) VALUES
	(1, 1, 'Collections', '0000000004', '2025-11-14 01:55:40', 'A'),
	(2, 1, 'Collections', '0000000005', '2025-11-14 01:57:45', 'A'),
	(4, 1, 'Collections', '0000000006', '2025-11-14 02:36:23', 'A'),
	(5, 1, 'Collections', '0000000006', '2025-11-14 02:45:32', 'E'),
	(6, 1, 'Collections', '0000000006', '2025-11-14 02:47:48', 'E'),
	(7, 1, 'Collections', '0000000006', '2025-11-14 02:49:29', 'E'),
	(8, 1, 'Collections', '0000000006', '2025-11-14 02:49:51', 'E'),
	(9, 1, 'Collections', '0000000007', '2025-11-14 02:50:05', 'A'),
	(10, 1, 'Collections', '0000000008', '2025-11-14 02:51:32', 'A'),
	(11, 1, 'Collections', '0000000008', '2025-11-14 02:52:44', 'E'),
	(12, 1, 'Collections', '0000000009', '2025-11-14 03:42:47', 'A'),
	(13, 1, 'Collections', '0000000009', '2025-11-14 03:43:07', 'E'),
	(14, 1, 'Collections', '0000000009', '2025-11-14 03:43:14', 'E'),
	(15, 1, 'Collections', '0000000009', '2025-11-14 03:43:30', 'D'),
	(16, 1, 'Collections', '0000000009', '2025-11-14 04:08:34', 'A'),
	(17, 1, 'Collections', '0000000009', '2025-11-14 04:08:55', 'E'),
	(18, 1, 'Collections', '0000000010', '2025-11-14 04:14:55', 'A'),
	(19, 1, 'Collections', '0000000011', '2025-11-14 04:17:25', 'A'),
	(20, 1, 'Collections', '0000000012', '2025-11-14 04:20:09', 'A'),
	(21, 1, 'Collections', '0000000012', '2025-11-14 04:23:59', 'E'),
	(22, 1, 'Collections', '0000000012', '2025-11-14 04:25:31', 'E'),
	(23, 1, 'Collections', '0000000013', '2025-11-14 04:27:08', 'A'),
	(24, 1, 'Collections', '0000000014', '2025-11-14 04:29:20', 'A'),
	(25, 1, 'Collections', '0000000015', '2025-11-14 04:29:38', 'A'),
	(26, 1, 'Collections', '0000000015', '2025-11-14 04:29:59', 'D'),
	(27, 1, 'Collections', '0000000015', '2025-11-14 04:31:02', 'A'),
	(28, 1, 'Collections', '0000000016', '2025-11-14 04:31:40', 'A'),
	(29, 1, 'Collections', '0000000017', '2025-11-14 04:31:51', 'A'),
	(30, 1, 'Collections', '0000000017', '2025-11-14 04:32:28', 'E');

-- Dumping structure for table university.collections
CREATE TABLE IF NOT EXISTS `collections` (
  `id` int NOT NULL AUTO_INCREMENT,
  `or_number` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `or_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `student_id` int DEFAULT NULL,
  `semester_id` int DEFAULT NULL,
  `cash` decimal(8,2) NOT NULL DEFAULT '0.00',
  `gcash` decimal(8,2) NOT NULL DEFAULT '0.00',
  `gcash_refno` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `or_number` (`or_number`),
  KEY `FK_collections_students` (`student_id`),
  KEY `FK_collections_semesters` (`semester_id`),
  CONSTRAINT `FK_collections_semesters` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`),
  CONSTRAINT `FK_collections_students` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table university.collections: ~17 rows (approximately)
INSERT INTO `collections` (`id`, `or_number`, `or_date`, `student_id`, `semester_id`, `cash`, `gcash`, `gcash_refno`) VALUES
	(1, '0000000001', '2025-11-13 15:58:37', 1, 1, 1000.00, 0.00, '0'),
	(2, '0000000002', '2025-11-13 15:58:37', 1, 1, 0.00, 200.00, '0'),
	(3, '0000000003', '2025-11-13 15:58:37', 3, 1, 200.00, 0.00, '0'),
	(4, '0000000004', '2025-11-13 17:55:40', 1, 1, 50.00, 0.00, ''),
	(5, '0000000005', '2025-11-13 17:57:45', 1, 1, 10.00, 0.00, ''),
	(7, '0000000006', '2025-11-13 18:36:23', 1, 1, 0.00, 8.00, ''),
	(8, '0000000007', '2025-11-13 18:50:05', 1, 1, 1.00, 0.00, ''),
	(9, '0000000008', '2025-11-13 18:51:32', 3, 2, 0.00, 200.00, '328947'),
	(11, '0000000009', '2025-11-13 20:08:34', 1, 1, 1.00, 0.00, ''),
	(12, '0000000010', '2025-11-13 20:14:55', 1, 1, 10.00, 0.00, ''),
	(13, '0000000011', '2025-11-13 20:17:25', 1, 2, 2.00, 0.00, ''),
	(14, '0000000012', '2025-11-13 20:20:09', 1, 2, 3.00, 0.00, ''),
	(15, '0000000013', '2025-11-13 20:27:08', 1, 1, 10.00, 10.00, ''),
	(16, '0000000014', '2025-11-13 20:29:20', 1, 1, 5.00, 5.00, ''),
	(18, '0000000015', '2025-11-13 20:31:02', 1, 1, 5.00, 0.00, ''),
	(19, '0000000016', '2025-11-13 20:31:40', 3, 1, 10.00, 10.00, ''),
	(20, '0000000017', '2025-11-13 20:31:51', 3, 2, 5.00, 10.00, '');

-- Dumping structure for table university.courses
CREATE TABLE IF NOT EXISTS `courses` (
  `course_id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`course_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table university.courses: ~3 rows (approximately)
INSERT INTO `courses` (`course_id`, `code`, `name`) VALUES
	(1, 'CS101', 'Computer Science'),
	(2, 'IT201', 'Information Technology'),
	(3, 'ENG301', 'English Literature');

-- Dumping structure for table university.rooms
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table university.rooms: ~3 rows (approximately)
INSERT INTO `rooms` (`id`, `name`) VALUES
	(1, 'Room A'),
	(2, 'Room B'),
	(3, 'Lab 1');

-- Dumping structure for table university.semesters
CREATE TABLE IF NOT EXISTS `semesters` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `start_date` varchar(50) DEFAULT NULL,
  `end_date` varchar(50) DEFAULT NULL,
  `summer` varchar(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table university.semesters: ~2 rows (approximately)
INSERT INTO `semesters` (`id`, `code`, `start_date`, `end_date`, `summer`) VALUES
	(1, '1st25-26', 'August 7, 2025', 'December 7, 2025', 'N'),
	(2, '2nd25-26', 'January 7, 20255', 'May 7, 2025', 'N');

-- Dumping structure for table university.students
CREATE TABLE IF NOT EXISTS `students` (
  `student_id` int NOT NULL AUTO_INCREMENT,
  `student_number` varchar(50) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '0',
  `gender` varchar(10) NOT NULL DEFAULT 'Male',
  `course_id` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`student_id`),
  KEY `FK_students_courses` (`course_id`),
  CONSTRAINT `FK_students_courses` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table university.students: ~3 rows (approximately)
INSERT INTO `students` (`student_id`, `student_number`, `name`, `gender`, `course_id`) VALUES
	(1, 'S2023001', 'Michael Brown', 'Male', 1),
	(3, 'S2023003', 'Daniel Wilson', 'Male', 1),
	(6, 'S2025001', 'Emma Davis', 'M', 3);

-- Dumping structure for table university.student_subjects
CREATE TABLE IF NOT EXISTS `student_subjects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `subject_id` int DEFAULT NULL,
  `student_id` int DEFAULT NULL,
  `semester_id` int DEFAULT NULL,
  `midterm_grade` decimal(20,6) DEFAULT NULL,
  `final_course_grade` decimal(20,6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_student_subjects_semesters` (`semester_id`),
  KEY `FK_student_subjects_students` (`student_id`),
  KEY `FK_student_subjects_subjects` (`subject_id`),
  CONSTRAINT `FK_student_subjects_semesters` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_student_subjects_students` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_student_subjects_subjects` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table university.student_subjects: ~3 rows (approximately)
INSERT INTO `student_subjects` (`id`, `subject_id`, `student_id`, `semester_id`, `midterm_grade`, `final_course_grade`) VALUES
	(4, 3, 3, 1, 2.000000, 1.750000),
	(7, 1, 3, 1, 1.000000, 1.000000),
	(8, 1, 3, 2, 1.000000, NULL),
	(12, 1, 1, 1, NULL, 1.000000),
	(19, 2, 3, 2, NULL, NULL),
	(21, 2, 1, 2, NULL, NULL),
	(22, 3, 1, 2, NULL, 1.750000),
	(23, 1, 6, 1, 2.250000, 2.250000),
	(24, 2, 6, 1, 2.000000, 2.000000),
	(25, 1, 6, 2, NULL, NULL),
	(26, 2, 6, 2, NULL, NULL);

-- Dumping structure for table university.subjects
CREATE TABLE IF NOT EXISTS `subjects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `description` text,
  `days` varchar(50) DEFAULT NULL,
  `time` varchar(50) DEFAULT NULL,
  `room_id` int DEFAULT NULL,
  `teacher_id` int DEFAULT NULL,
  `price_unit` int DEFAULT NULL,
  `units` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_subjects_rooms` (`room_id`),
  KEY `FK_subjects_teachers` (`teacher_id`),
  CONSTRAINT `FK_subjects_rooms` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_subjects_teachers` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table university.subjects: ~3 rows (approximately)
INSERT INTO `subjects` (`id`, `code`, `description`, `days`, `time`, `room_id`, `teacher_id`, `price_unit`, `units`) VALUES
	(1, 'MATH101', 'Calculus 1', 'Mon/Wed/Fri', '09:00-10:30', 1, 1, 500, 3),
	(2, 'CS201', 'Data Structures', 'Tue/Thu', '11:00-12:30', 3, 2, 700, 4),
	(3, 'ENG150', 'Intro to Poetry', 'Mon/Wed', '14:00-15:30', 2, 3, 400, 2);

-- Dumping structure for table university.teachers
CREATE TABLE IF NOT EXISTS `teachers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table university.teachers: ~0 rows (approximately)
INSERT INTO `teachers` (`id`, `name`) VALUES
	(1, 'Dr. Alice Smith'),
	(2, 'Prof. John Doe'),
	(3, 'Ms. Clara Johnson');

-- Dumping structure for table university.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'student',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table university.users: ~2 rows (approximately)
INSERT INTO `users` (`id`, `name`, `password`, `role`) VALUES
	(1, 'shimi', 'shimi', 'admin'),
	(2, 'S2025001', '123', 'student'),
	(3, 'S2023001', '123', 'student'),
	(4, 'S2023003', '123', 'student');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
