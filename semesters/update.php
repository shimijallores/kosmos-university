<?php

require('../functions.php');
require('../partials/database.php');

session_start();

try {
    // Update student
    $stmt = $connection->prepare("
    update courses set code = ?, name = ? where course_id = ?;
");

    $stmt->execute([$_POST['code'], $_POST['name'], $_POST['course_id']]);
} catch (Exception $e) {
    $_SESSION['redirect'] = 'edit.php?id=' . $_POST['course_id'];

    header("Location: 404.php");
    exit();
}


header("Location: index.php");
exit();