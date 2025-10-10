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

-- Dumping structure for table intelligent_systems.courses
CREATE TABLE IF NOT EXISTS `courses` (
  `course_id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(250) NOT NULL,
  PRIMARY KEY (`course_id`),
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table intelligent_systems.courses: ~3 rows (approximately)
INSERT INTO `courses` (`course_id`, `code`, `name`) VALUES
	(1, 'CS101', 'Computer Science'),
	(2, 'IT201', 'Information Technology'),
	(3, 'ENG301', 'English Literature');

-- Dumping structure for table intelligent_systems.rooms
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table intelligent_systems.rooms: ~3 rows (approximately)
INSERT INTO `rooms` (`id`, `name`) VALUES
	(1, 'Room A'),
	(2, 'Room B'),
	(3, 'Lab 1');

-- Dumping structure for table intelligent_systems.students
CREATE TABLE IF NOT EXISTS `students` (
  `student_id` int NOT NULL AUTO_INCREMENT,
  `student_number` varchar(50) NOT NULL,
  `name` varchar(250) NOT NULL,
  `gender` tinytext NOT NULL,
  `course_id` int NOT NULL,
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `student_number` (`student_number`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `course_id` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table intelligent_systems.students: ~4 rows (approximately)
INSERT INTO `students` (`student_id`, `student_number`, `name`, `gender`, `course_id`) VALUES
	(1, 'S2023001', 'Michael Brown', 'Male', 1),
	(2, 'S2023002', 'Sophia Taylor', 'Female', 2),
	(3, 'S2023003', 'Daniel Wilson', 'Male', 1),
	(4, 'S2023004', 'Emma Davis', 'M', 3);

-- Dumping structure for table intelligent_systems.student_subjects
CREATE TABLE IF NOT EXISTS `student_subjects` (
  `id` int NOT NULL,
  `subject_id` int NOT NULL,
  `student_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_student_subjects_subjects` (`subject_id`),
  KEY `FK_student_subjects_students` (`student_id`),
  CONSTRAINT `FK_student_subjects_students` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_student_subjects_subjects` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table intelligent_systems.student_subjects: ~5 rows (approximately)
INSERT INTO `student_subjects` (`id`, `subject_id`, `student_id`) VALUES
	(1, 1, 1),
	(2, 2, 1),
	(3, 2, 2),
	(4, 3, 3),
	(5, 1, 4);

-- Dumping structure for table intelligent_systems.subjects
CREATE TABLE IF NOT EXISTS `subjects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `days` varchar(50) NOT NULL DEFAULT '',
  `time` varchar(50) NOT NULL DEFAULT '',
  `room_id` int NOT NULL DEFAULT '0',
  `teacher_id` int NOT NULL DEFAULT '0',
  `price_unit` int NOT NULL DEFAULT '0',
  `units` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `code` (`code`),
  KEY `FK_subjects_rooms` (`room_id`) USING BTREE,
  KEY `FK_subjects_teachers` (`teacher_id`) USING BTREE,
  CONSTRAINT `FK_subjects_rooms` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_subjects_teachers` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table intelligent_systems.subjects: ~3 rows (approximately)
INSERT INTO `subjects` (`id`, `code`, `description`, `days`, `time`, `room_id`, `teacher_id`, `price_unit`, `units`) VALUES
	(1, 'MATH101', 'Calculus I', 'Mon/Wed/Fri', '09:00-10:30', 1, 1, 500, 3),
	(2, 'CS201', 'Data Structures', 'Tue/Thu', '11:00-12:30', 3, 2, 700, 4),
	(3, 'ENG150', 'Intro to Poetry', 'Mon/Wed', '14:00-15:30', 2, 3, 400, 2);

-- Dumping structure for table intelligent_systems.teachers
CREATE TABLE IF NOT EXISTS `teachers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table intelligent_systems.teachers: ~3 rows (approximately)
INSERT INTO `teachers` (`id`, `name`) VALUES
	(1, 'Dr. Alice Smith'),
	(2, 'Prof. John Doe'),
	(3, 'Ms. Clara Johnson');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
