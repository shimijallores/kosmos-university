<?php

require('../functions.php');
require('../partials/database.php');

session_start();

// Update grades in student_subjects table

$stmt = $connection->prepare("
        update student_subjects
        set midterm_grade = ?, final_course_grade = ?
        where subject_id = ? and student_id = ? and semester_id = (
            select id from semesters where code = ?
        );
    ");


$midterms = $_POST['midterms_grade'] == 'null' ? NULL : $_POST['midterms_grade'];
$finals = $_POST['final_course_grade'] == 'null' ? NULL : $_POST['final_course_grade'];
$stmt->execute([
    $midterms,
    $finals,
    $_POST['subject_id'],
    $_POST['student_id'],
    $_POST['semester']
]);


header("Location: index.php?semester=" . $_POST['semester']);
exit();