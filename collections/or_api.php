<?php

require('../functions.php');
require('../partials/database.php');

# Fetch OR Number
$stmt = $connection->prepare("select or_number from collections order by cast(or_number as unsigned) desc");

$stmt->execute();
$or_number = $stmt->fetch();
$or_number = $or_number['or_number'] + 1;

# Current Balance
$stmt = $connection->prepare("
select * from student_subjects ss join subjects s where ss.student_id = (select student_id from students where student_number = ?) and ss.semester_id = (select id from semesters where code = ?) and ss.subject_id = s.id
");

$stmt->execute([$_GET['studentNumber'], $_GET['semester']]);
$tuitions = $stmt->fetchAll();

$total_tuition = 0;
foreach ($tuitions as $tuition) {
    $total_tuition += floatval($tuition['price_unit']) * intval($tuition['units']);
}

header('Content-Type: application/json');
echo json_encode([$or_number, $total_tuition]);