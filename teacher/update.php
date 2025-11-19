<?php
require('../functions.php');
require('../partials/database.php');

session_start();

if (empty($_SESSION['user'])) {
    header('location: /menu.php');
    exit();
}

// Verify teacher
$teacher_code = $_SESSION['user']['name'];
$stmt = $connection->prepare("SELECT id FROM teachers WHERE code = ?");
$stmt->execute([$teacher_code]);
$teacher = $stmt->fetch();

if (!$teacher) {
    header('location: index.php');
    exit();
}

// Get form data
$semester = $_POST['semester'];
$subject_id = $_POST['subject'];
$term = $_POST['term'];
$student_ids = $_POST['student_ids'];
$grades = $_POST['grades'];

// Get semester ID
$stmt = $connection->prepare("SELECT id FROM semesters WHERE code = ?");
$stmt->execute([$semester]);
$semester_data = $stmt->fetch();
$semester_id = $semester_data['id'];

// Update grades for each student
foreach ($student_ids as $index => $student_id) {
    $grade = $grades[$index];

    // Skip empty grades
    if (empty($grade)) {
        continue;
    }

    // Determine which column to update based on term
    if ($term == 'midterm') {
        $stmt = $connection->prepare("
            UPDATE student_subjects 
            SET midterm_grade = ? 
            WHERE student_id = ? 
            AND subject_id = ? 
            AND semester_id = ?
        ");
    } else {
        $stmt = $connection->prepare("
            UPDATE student_subjects 
            SET final_course_grade = ? 
            WHERE student_id = ? 
            AND subject_id = ? 
            AND semester_id = ?
        ");
    }

    $stmt->execute([$grade, $student_id, $subject_id, $semester_id]);
}

// Redirect back to grading page with filters
$redirect_url = "grading.php?semester=" . urlencode($semester) .
    "&subject=" . urlencode($subject_id) .
    "&term=" . urlencode($term);

$_SESSION['message'] = 'Grades updated successfully!';
header("Location: " . $redirect_url);
exit();
