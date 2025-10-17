<?php

require('../functions.php');
require('../partials/database.php');

# Fetch semester id
$stmt = $connection->prepare("
    select id from semesters where code = ?
    ");

$stmt->execute([$_POST['semester_code']]);

$semester = $stmt->fetch();

# Fetch subject id
$stmt = $connection->prepare("
    select midterm_grade, final_course_grade from student_subjects where subject_id = ? and semester_id = ?
    ");

$stmt->execute([$_POST['subject_id'], $semester['id']]);

$subject = $stmt->fetch();

// Delete from students table
if (empty($subject) || $subject[0] === '' || ($subject['midterm_grade'] === '0.000000' && $subject['final_course_grade'] === '0.000000')) {
    $stmt = $connection->prepare("
    delete from student_subjects where subject_id = ? and student_id = ? and semester_id = ?
");

    $stmt->execute([$_POST['subject_id'], $_POST['student_id'], $semester['id']]);

    header("Location: index.php?semester={$_POST['semester_code']}");
    exit();
} else {
    $_SESSION['redirect'] = 'index.php';

    header("Location: failed.php");
    exit();
}