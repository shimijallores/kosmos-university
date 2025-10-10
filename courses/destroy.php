<?php

require('../functions.php');
require('../partials/database.php');

session_start();

$stmt = $connection->prepare("
    select * from students where course_id = ?;
");

$stmt->execute([$_POST['course_id']]);

$students = $stmt->fetchAll();

if (empty($students)) {
    // Delete from course table
    $stmt = $connection->prepare("
    delete from courses where course_id = ?
");

    $stmt->execute([$_POST['course_id']]);

    header("Location: index.php");
    exit();
} else {
    $_SESSION['redirect'] = 'index.php';
    $_SESSION['linked_students'] = $students;

    header('Location: failed.php');
    exit();
}