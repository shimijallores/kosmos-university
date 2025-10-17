<?php

require('../functions.php');
require('../partials/database.php');


session_start();
# Fetch semester id
$stmt = $connection->prepare("
    select id from semesters where code = ?
    ");

$stmt->execute([$_POST['semester_code']]);

$semester = $stmt->fetch();

# Fetch subject id
$stmt = $connection->prepare("
    select midterm_grade, final_course_grade from student_subjects where subject_id = ? and semester_id = ? and student_id = ?
    ");

$stmt->execute([$_POST['subject_id'], $semester['id'], $_POST['student_id']]);

$subject = $stmt->fetch();
// Delete from students table
if (is_null($subject['midterm_grade']) && is_null($subject['final_course_grade'])) {
    $stmt = $connection->prepare("
    delete from student_subjects where subject_id = ? and student_id = ? and semester_id = ?
");

    $stmt->execute([$_POST['subject_id'], $_POST['student_id'], $semester['id']]);

    header("Location: index.php?semester={$_POST['semester_code']}");
    exit();
} else {
    $_SESSION['redirect'] = "index.php?semester={$_POST['semester_code']}";

    header("Location: failed.php");
    exit();
}