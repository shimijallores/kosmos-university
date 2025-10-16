<?php
require('../functions.php');
require('../partials/database.php');

session_start();

// Generate student number
$current_year = date('Y');

// Find the next available number for this year
$stmt = $connection->prepare("
    SELECT MAX(CAST(SUBSTRING(student_number, 6) AS UNSIGNED)) as max_num
    FROM students
    WHERE student_number LIKE ?
");
$stmt->execute(["S{$current_year}%"]);
$result = $stmt->fetch();

$next_num = ($result['max_num'] ?? 0) + 1;
$student_number = sprintf("S%s%03d", $current_year, $next_num);

// Insert new student
try {
  $stmt = $connection->prepare("
        INSERT INTO students (student_number, name, gender, course_id)
        VALUES (?, ?, ?, ?)
    ");

  $stmt->execute([
    $student_number,
    $_POST['student_name'],
    $_POST['gender'],
    $_POST['course']
  ]);

  $student_id = $connection->lastInsertId();

  // Store success message in session
  $_SESSION['enrollment_success'] = [
    'student_number' => $student_number,
    'student_name' => $_POST['student_name']
  ];
} catch (Exception $e) {
  $_SESSION['enrollment_error'] = 'Enrollment failed. Please try again.';
  header("Location: index.php");
  exit();
}

header("Location: success.php");
exit();
