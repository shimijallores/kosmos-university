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

$stmt->execute([
    $_POST['midterm_grade'],
    $_POST['final_course_grade'],
    $_POST['subject_id'],
    $_POST['student_id'],
    $_POST['semester']
]);


header("Location: index.php?semester=" . $_POST['semester']);
exit();
