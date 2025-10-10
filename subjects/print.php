<?php
require('../fpdf186/fpdf.php');

require('../functions.php');
require('../partials/database.php');

session_start();

$student = $_SESSION['student'];


if (empty($_SESSION['user'])) {
    header('location: /login.php');
    exit();
}

$total_price = 0;
foreach ($student['subjects'] as $subject) {
    $total_price += $subject['price_unit'] * $subject['units'];
}

$total_units = 0;
foreach ($student['subjects'] as $subject) {
    $total_units += $subject['units'];
}

$pdf = new FPDF();
$pdf->AddPage();

// Header
$pdf->SetFont('Arial', 'B', 20);
$pdf->Image('../images/logo.png', 90, 10, 25, 0, 'PNG');
$pdf->Cell(190, 55, 'DINO UNIVERSITY', 0, 0, 'C');
$pdf->Cell(200, 30, '', 0, 1, 'C');


// Student Info
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(75, 20, 'Student #: ' . $student['student_number'], 0, 0, 'L');
$pdf->Cell(75, 20, 'Name: ' . $student['student_name'], 0, 0, 'L');
$pdf->Cell(50, 20, 'Course: ' . $student['course_name'], 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Subject Code', 1, 0, 'C');
$pdf->Cell(25, 10, 'Days', 1, 0, 'C');
$pdf->Cell(30, 10, 'Time', 1, 0, 'C');
$pdf->Cell(30, 10, 'Room', 1, 0, 'C');
$pdf->Cell(35, 10, 'Teacher', 1, 0, 'C');
$pdf->Cell(30, 10, 'Units', 1, 1, 'C');


// Subject Table
$pdf->SetFont('Arial', '', 10);

foreach ($student['subjects'] as $subject) {
    $pdf->Cell(40, 10, $subject['code'], 1, 0, 'C');
    $pdf->Cell(25, 10, $subject['days'], 1, 0, 'C');
    $pdf->Cell(30, 10, $subject['time'], 1, 0, 'C');
    $pdf->Cell(30, 10, $subject['room_name'], 1, 0, 'C');
    $pdf->Cell(35, 10, $subject['teacher_name'], 1, 0, 'C');
    $pdf->Cell(30, 10, $subject['units'], 1, 1, 'C');
}

$pdf->Cell(177, 10, $total_units, 0, 1, 'R');

// Tuition Summary
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(165, 10, "Tuition Fee: Php" . number_format($total_price), 0, 0, 'L');

$pdf->Output();