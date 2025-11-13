<?php

require('../functions.php');
require('../partials/database.php');

// Fetch OR Number 
$stmt = $connection->prepare("select * from collections where or_number = ?");
$stmt->execute([$_GET['or']]);
$collection = $stmt->fetch();

if (!empty($collection)) {
    // Fetch student related to OR Number
    $stmt = $connection->prepare("select * from students where student_id = ?");
    $stmt->execute([$collection['student_id']]);
    $student = $stmt->fetch();

    // Fetch semester related to OR Number
    $stmt = $connection->prepare("select * from semesters where id = ?");
    $stmt->execute([$collection['semester_id']]);
    $semester = $stmt->fetch();

    // Fetch remaining balance
    // Fetch student current balance
    $stmt = $connection->prepare("select * from student_subjects ss join subjects s where ss.student_id = ? and ss.semester_id = ? and ss.subject_id = s.id");

    $stmt->execute([$student['student_id'], $semester['id']]);
    $tuitions = $stmt->fetchAll();

    $total_tuition = 0;
    foreach ($tuitions as $tuition) {
        $total_tuition += floatval($tuition['price_unit']) * intval($tuition['units']);
    }

    // Fetch past collections if existent
    $stmt = $connection->prepare("select * from collections where student_id = ? and semester_id = ?");
    $stmt->execute([$student['student_id'], $semester['id']]);
    $payments = $stmt->fetchAll();

    $total_payments = 0;
    foreach ($payments as $payment) {
        $total_tuition -= floatval($payment['cash']);
        $total_tuition -= floatval($payment['gcash']);
    }

    header('Content-Type: application/json');
    echo json_encode([$collection, $student, $semester, $total_tuition]);
}
