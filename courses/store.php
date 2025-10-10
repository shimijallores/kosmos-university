<?php

require('../functions.php');
require('../partials/database.php');

session_start();

try {
    // Store new course to courses table
    $stmt = $connection->prepare("
    insert into courses (code, name) values (?, ?);
");

    $stmt->execute([$_POST['code'], $_POST['name']]);
} catch (Exception $e) {
    $_SESSION['redirect'] = 'create.php';

    header("Location: 404.php");
    exit();
}


header("Location: index.php");
exit();