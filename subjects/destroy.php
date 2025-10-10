<?php

require('../functions.php');
require('../partials/database.php');

# Fetch semester id
$stmt = $connection->prepare("
    select id from semesters where code = ?
    ");

$stmt->execute([$_POST['semester_code']]);

$semester = $stmt->fetch();

// Delete from students table
$stmt = $connection->prepare("
    delete from student_subjects where subject_id = ? and student_id = ? and semester_id = ?
");

$stmt->execute([$_POST['subject_id'], $_POST['student_id'], $semester['id']]);

header("Location: index.php?semester={$_POST['semester_code']}");
exit();