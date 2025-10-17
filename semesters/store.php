<?php

require('../functions.php');
require('../partials/database.php');

session_start();

$stmt = $connection->prepare("select code from semesters where code = ?");
$stmt->execute([$_POST['code']]);
$semester = $stmt->fetch();

try {
    if (empty($semester)) {
        // Store new course to courses table
        $stmt = $connection->prepare("
    insert into semesters (code, start_date, end_date, summer) values (?, ?, ?, ?);
");

        $summer = $_POST['type'] == 'on' ? 'Y' : 'N';

        $stmt->execute([$_POST['code'], $_POST['start_date'], $_POST['end_date'], $summer]);
    }
} catch (Exception $e) {
    $_SESSION['redirect'] = 'create.php';

    header("Location: 404.php");
    exit();
}


header("Location: index.php");
exit();