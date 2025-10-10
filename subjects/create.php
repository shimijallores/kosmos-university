<?php
require('../functions.php');
require('../partials/database.php');

# Fetch semester id
$stmt = $connection->prepare("
    select id from semesters where code = ?
    ");

$stmt->execute([$_POST['semester_code']]);

$semester = $stmt->fetch();

# Add subjects to a student
$stmt = $connection->prepare("
    insert into student_subjects (student_id, subject_id, semester_id) values (?, ?, ?);
    ");

$stmt->execute([$_POST['student_id'], $_POST['subject'], $semester['id']]);

header("location: index.php?semester={$_POST['semester_code']}");
die();