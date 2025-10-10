<?php
require('../functions.php');
require('../partials/database.php');

$stmt = $connection->prepare("
    insert into student_subjects (student_id, subject_id) values (?, ?);
    ");

$stmt->execute([$_POST['student_id'], $_POST['subject']]);

header('location: index.php');
die();
