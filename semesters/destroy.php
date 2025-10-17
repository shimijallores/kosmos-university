<?php

require('../functions.php');
require('../partials/database.php');

session_start();

$stmt = $connection->prepare("
    select * from student_subjects where semester_id = ?;
");

$stmt->execute([$_POST['semester_id']]);

$students = $stmt->fetchAll();

if (empty($students)) {
    // Delete from course table
    $stmt = $connection->prepare("
    delete from semesters where id = ?
");

    $stmt->execute([$_POST['semester_id']]);

    header("Location: index.php");
    exit();
} else {
    $_SESSION['redirect'] = 'index.php';
    $_SESSION['linked_students'] = $students;

    header('Location: failed.php');
    exit();
}