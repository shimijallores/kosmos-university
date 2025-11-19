<?php

require('../functions.php');
require('../partials/database.php');

// Fetch OR Number 
// if last OR number is nonexistent, generate a new one, else, just increment from last
$stmt = $connection->prepare("select or_number from collections order by cast(or_number as unsigned) desc");
$stmt->execute();
$or_number = $stmt->fetch();

if (!$or_number) {
    $or_number = '0000000001';
} else {
    $or_number = str_pad($or_number['or_number'] + 1, 10, '0', STR_PAD_LEFT);
}

# Fetch Current Balance
$stmt = $connection->prepare("
select * from student_subjects ss join subjects s where ss.student_id = (select student_id from students where student_number = ?) and ss.semester_id = (select id from semesters where code = ?) and ss.subject_id = s.id
");

$stmt->execute([$_GET['studentNumber'], $_GET['semester']]);
$tuitions = $stmt->fetchAll();

$total_tuition = 0;
foreach ($tuitions as $tuition) {
    $total_tuition += floatval($tuition['price_unit']) * intval($tuition['units']);
}

# Fetch past collections if existent
$stmt = $connection->prepare("
select * from collections where student_id = (select student_id from students where student_number = ?) and semester_id = (select id from semesters where code = ?)
");

$stmt->execute([$_GET['studentNumber'], $_GET['semester']]);
$payments = $stmt->fetchAll();

$total_payments = 0;
foreach ($payments as $payment) {
    $total_tuition -= floatval($payment['cash']);
    $total_tuition -= floatval($payment['gcash']);
}

# Fetch all student collections for history display
$stmt = $connection->prepare("
    SELECT 
        c.or_number,
        c.or_date,
        s.code as semester,
        c.cash,
        c.gcash,
        c.gcash_refno,
        (c.cash + c.gcash) as total
    FROM collections c
    LEFT JOIN semesters s ON c.semester_id = s.id
    WHERE c.student_id = (SELECT student_id FROM students WHERE student_number = ?)
    ORDER BY c.or_date DESC
");

$stmt->execute([$_GET['studentNumber']]);
$collectionHistory = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode([$or_number, $total_tuition, $collectionHistory]);
