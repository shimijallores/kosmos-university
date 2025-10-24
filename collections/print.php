<?php
require('../fpdf186/fpdf.php');

require('../functions.php');
require('../partials/database.php');

session_start();

if (empty($_SESSION['user'])) {
    header('location: /login.php');
    exit();
}

# Get student info and grades
$student_number = $_GET['student'] ?? $_SESSION['student_number'];
$semester_code = $_GET['semester'];

$stmt = $connection->prepare("
select s.student_id, s.student_number, s.name as student_name, c.code as course_name
from students s
left join courses c on s.course_id = c.course_id
where s.student_number = ?;
");

$stmt->execute([$student_number]);
$student = $stmt->fetch();

# Get student subjects with grades
$stmt = $connection->prepare("
select sub.code, sub.description, sub.units, ss.midterm_grade, ss.final_course_grade
from students s
join student_subjects ss on ss.student_id = s.student_id
join subjects sub on ss.subject_id = sub.id
join semesters sem on ss.semester_id = sem.id
where s.student_number = ? and sem.code = ?
order by sub.code;
");

$stmt->execute([$student_number, $semester_code]);
$student_subjects = $stmt->fetchAll();

# Calculate GPA
$total_points = 0;
$total_units = 0;
foreach ($student_subjects as $subject) {
    if ($subject['final_course_grade'] > 0) {
        $total_points += $subject['final_course_grade'] * $subject['units'];
        $total_units += $subject['units'];
    }
}
$gpa = $total_units > 0 ? number_format($total_points / $total_units, 2) : '0.00';

$pdf = new FPDF();
$pdf->AddPage();

// Header
$pdf->SetFont('Arial', 'B', 20);
$pdf->Image('../images/logo.png', 90, 10, 25, 0, 'PNG');
$pdf->Cell(190, 55, 'DINO UNIVERSITY', 0, 0, 'C');
$pdf->Cell(200, 30, '', 0, 1, 'C');

// Title
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 10, 'Student Grades Report', 0, 1, 'C');
$pdf->Ln(5);

// Student Info
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(50, 10, 'Student #: ' . $student['student_number'], 0, 0, 'L');
$pdf->Cell(80, 10, 'Name: ' . $student['student_name'], 0, 0, 'L');
$pdf->Cell(60, 10, 'Semester: ' . $semester_code, 0, 1, 'L');
$pdf->Cell(50, 10, 'Course: ' . $student['course_name'], 0, 1, 'L');
$pdf->Ln(5);

// Grades Table Header
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(20, 10, 'Code', 1, 0, 'C');
$pdf->Cell(100, 10, 'Subject Description', 1, 0, 'C');
$pdf->Cell(15, 10, 'Units', 1, 0, 'C');
$pdf->Cell(20, 10, 'Midterm', 1, 0, 'C');
$pdf->Cell(20, 10, 'Final', 1, 1, 'C');

// Grades Table Body
$pdf->SetFont('Arial', '', 10);
foreach ($student_subjects as $subject) {
    $status = $subject['final_course_grade'] >= 1.0 && $subject['final_course_grade'] <= 3.0 ? 'Passed' : 'Failed';

    $pdf->Cell(20, 8, $subject['code'], 1, 0, 'C');
    $pdf->Cell(100, 8, substr($subject['description'], 0, 35) . (strlen($subject['description']) > 35 ? '...' : ''), 1, 0, 'L');
    $pdf->Cell(15, 8, $subject['units'], 1, 0, 'C');
    $pdf->Cell(20, 8, $subject['midterm_grade'] ? number_format((float)$subject['midterm_grade'], 2) : '-', 1, 0, 'C');
    $pdf->Cell(20, 8, $subject['final_course_grade'] ? number_format((float)$subject['final_course_grade'], 2) : '-', 1, 1, 'C');
}

// GPA Summary
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(145, 10, 'GPA: ' . $gpa, 0, 0, 'R');
$pdf->Cell(30, 10, 'Total Units: ' . $total_units, 0, 1, 'R');

$pdf->Output();