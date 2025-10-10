<?php
require('fpdf186/fpdf.php');

require('functions.php');
require('partials/database.php');

$query = "
    select s.student_number, s.name as student_name, s.gender, c.name as course_name
    from students s 
    join courses c on s.course_id = c.course_id
    order by s.gender, s.name
";

$stmt = $connection->prepare($query);

$stmt->execute();

$students = $stmt->fetchAll();

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(160, 30, 'Students', 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Student #', 1, 0, 'C');
$pdf->Cell(40, 10, 'Name', 1, 0, 'C');
$pdf->Cell(40, 10, 'Gender', 1, 0, 'C');
$pdf->Cell(40, 10, 'Course', 1, 1, 'C');

$pdf->SetFont('Arial', '', 10);

foreach ($students as $student) {
    $pdf->Cell(40, 10, $student['student_number'], 1, 0, 'L');
    $pdf->Cell(40, 10, $student['student_name'], 1, 0, 'L');
    $pdf->Cell(40, 10, $student['gender'], 1, 0, 'L');
    $pdf->Cell(40, 10, $student['course_name'], 1, 1, 'L');
}

$pdf->Cell(160, 10, 'Total Records: ' . count($students), 0, 1, 'R');


$pdf->Output();