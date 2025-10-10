-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping data for table intelligent_systems.courses: ~3 rows (approximately)
INSERT INTO `courses` (`course_id`, `code`, `name`) VALUES
	(1, 'CS101', 'Computer Science'),
	(2, 'IT201', 'Information Technology'),
	(3, 'ENG301', 'English Literature');

-- Dumping data for table intelligent_systems.rooms: ~3 rows (approximately)
INSERT INTO `rooms` (`id`, `name`) VALUES
	(1, 'Room A'),
	(2, 'Room B'),
	(3, 'Lab 1');

-- Dumping data for table intelligent_systems.semesters: ~2 rows (approximately)
INSERT INTO `semesters` (`id`, `code`) VALUES
	(1, '1st25-26'),
	(2, '2nd25-26');

-- Dumping data for table intelligent_systems.students: ~4 rows (approximately)
INSERT INTO `students` (`student_id`, `student_number`, `name`, `gender`, `course_id`) VALUES
	(1, 'S2023001', 'Michael Brown', 'Male', 1),
	(2, 'S2023002', 'Sophia Taylor', 'Female', 2),
	(3, 'S2023003', 'Daniel Wilson', 'Male', 1),
	(4, 'S2023004', 'Emma Davis', 'M', 3);

-- Dumping data for table intelligent_systems.student_subjects: ~4 rows (approximately)
INSERT INTO `student_subjects` (`id`, `subject_id`, `student_id`, `semester_id`, `midterm_grade`, `final_course_grade`) VALUES
	(2, 2, 1, 1, NULL, NULL),
	(3, 2, 2, 1, NULL, NULL),
	(4, 3, 3, 1, NULL, NULL),
	(6, 2, 4, 1, NULL, NULL);

-- Dumping data for table intelligent_systems.subjects: ~3 rows (approximately)
INSERT INTO `subjects` (`id`, `code`, `description`, `days`, `time`, `room_id`, `teacher_id`, `price_unit`, `units`) VALUES
	(1, 'MATH101', 'Calculus 1', 'Mon/Wed/Fri', '09:00-10:30', 1, 1, 500, 3),
	(2, 'CS201', 'Data Structures', 'Tue/Thu', '11:00-12:30', 3, 2, 700, 4),
	(3, 'ENG150', 'Intro to Poetry', 'Mon/Wed', '14:00-15:30', 2, 3, 400, 2);

-- Dumping data for table intelligent_systems.teachers: ~3 rows (approximately)
INSERT INTO `teachers` (`id`, `name`) VALUES
	(1, 'Dr. Alice Smith'),
	(2, 'Prof. John Doe'),
	(3, 'Ms. Clara Johnson');

-- Dumping data for table intelligent_systems.users: ~0 rows (approximately)
INSERT INTO `users` (`id`, `name`, `password`) VALUES
	(1, 'shimi', 'shimi');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
