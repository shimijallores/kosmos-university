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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table university.audit_trait: ~0 rows (approximately)

-- Dumping structure for table university.collections
CREATE TABLE IF NOT EXISTS `collections` (
  `id` int NOT NULL AUTO_INCREMENT,
  `or_number` varchar(50) NOT NULL DEFAULT '',
  `or_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `student_id` int DEFAULT NULL,
  `semester_id` int DEFAULT NULL,
  `cash` decimal(8,2) NOT NULL DEFAULT '0.00',
  `gcash` decimal(8,2) NOT NULL DEFAULT '0.00',
  `gcash_refno` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `or_number` (`or_number`),
  KEY `FK_collections_students` (`student_id`),
  KEY `FK_collections_semesters` (`semester_id`),
  CONSTRAINT `FK_collections_semesters` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`),
  CONSTRAINT `FK_collections_students` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table university.collections: ~0 rows (approximately)

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table university.users: ~2 rows (approximately)
INSERT INTO `users` (`id`, `name`, `password`, `role`) VALUES
	(1, 'shimi', 'shimi', 'admin'),
	(2, 'S2025001', 'S2025001', 'student');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
