<?php

require('../functions.php');
require('../partials/database.php');

session_start();

// Update student
try {
    $stmt = $connection->prepare("
        update students set student_number = ?, name = ?, gender = ?, course_id = ? where student_id = ?;
    ");

    $stmt->execute([$_POST['student_number'], $_POST['student_name'], $_POST['gender'], $_POST['course'], $_POST['student_id']]);
} catch (Exception $e) {
    $_SESSION['redirect'] = 'edit.php?id=' . $_POST['student_id'];

    header("Location: 404.php");
    exit();
}

header("Location: index.php");
exit();