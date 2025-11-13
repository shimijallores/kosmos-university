<?php
require('../fpdf186/fpdf.php');

require('../functions.php');
require('../partials/database.php');

session_start();

if (empty($_SESSION['user'])) {
    header('location: /login.php');
    exit();
}

// Fetch student info
$student_number = $_GET['student'];
$semester_code = $_GET['semester'];

$stmt = $connection->prepare("
select s.student_id, s.student_number, s.name as student_name, c.code as course_name
from students s
left join courses c on s.course_id = c.course_id
where s.student_number = ?;
");

$stmt->execute([$student_number]);
$student = $stmt->fetch();

// Fetch Collections related to the student
$stmt = $connection->prepare("
select * from collections where student_id = ? and semester_id = (select id from semesters where code = ?); 
");

$stmt->execute([$student['student_id'], $semester_code]);
$collections = $stmt->fetchAll();

$pdf = new FPDF();
$pdf->AddPage();

// Fetch students total tuition
$stmt = $connection->prepare("
select * from student_subjects ss join subjects s where ss.student_id = ? and ss.semester_id = (select id from semesters where code = ?) and ss.subject_id = s.id
");

$stmt->execute([$student['student_id'], $semester_code]);
$tuitions = $stmt->fetchAll();

$total_tuition = 0;
foreach ($tuitions as $tuition) {
    $total_tuition += floatval($tuition['price_unit']) * intval($tuition['units']);
}

// Initialize
$pdf = new FPDF();
$pdf->AddPage();

// Header
$pdf->SetFont('Arial', 'B', 20);
$pdf->Image('../images/logo.png', 90, 10, 25, 0, 'PNG');
$pdf->Cell(190, 55, 'DINO UNIVERSITY', 0, 0, 'C');
$pdf->Cell(200, 30, '', 0, 1, 'C');

// Title
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 10, 'Account Ledger', 0, 1, 'C');
$pdf->Ln(5);

// Student Info
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(50, 10, 'Student #: ' . $student['student_number'], 0, 0, 'L');
$pdf->Cell(50, 10, 'Name: ' . $student['student_name'], 0, 0, 'L');
$pdf->Cell(50, 10, 'Semester: ' . $semester_code, 0, 0, 'L');
$pdf->Cell(50, 10, 'Course: ' . $student['course_name'], 0, 1, 'L');
$pdf->Ln(5);

// Header
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'OR #', 1, 0, 'C');
$pdf->Cell(60, 10, 'Date', 1, 0, 'C');
$pdf->Cell(60, 10, 'Payments', 1, 1, 'C');

// Table
$pdf->SetFont('Arial', '', 10);
$total_paid = 0;
foreach ($collections as $collection) {
    $payment = floatval($collection['cash']) + floatval($collection['gcash']);
    $pdf->Cell(60, 8, $collection['or_number'], 1, 0, 'C');
    $pdf->Cell(60, 8, $collection['or_date'], 1, 0, 'C');
    $pdf->Cell(60, 8, number_format($payment, 2), 1, 1, 'C');

    $total_paid += $payment;
}

if (!count($collections)) {
    $pdf->Cell(180, 8, 'No existing payment', 1, 1, 'C');
}

// Total Balance
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(130, 8, 'Total Tuition: ' . number_format($total_tuition), 0, 0, 'R');
$pdf->Cell(30, 8, 'Balance: ' . number_format($total_tuition - $total_paid), 0, 0, 'R');

$pdf->Output();
