<?php

require('../functions.php');
require('../partials/database.php');

session_start();

// Store new student to students table
try {
    $stmt = $connection->prepare("
    insert into students (student_number, name, gender, course_id) values (?, ?, ?, ?);
");

    $stmt->execute([$_POST['student_number'], $_POST['student_name'], $_POST['gender'], $_POST['course']]);
} catch (Exception $e) {
    $_SESSION['redirect'] = 'create.php';

    header("Location: 404.php");
    exit();
}


header("Location: index.php");
exit();