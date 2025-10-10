<?php

require('../functions.php');
require('../partials/database.php');

// Delete from students table
$stmt = $connection->prepare("
    delete from student_subjects where subject_id = ? and student_id = ?
");

$stmt->execute([$_POST['subject_id'], $_POST['student_id']]);

header("Location: index.php");
exit();