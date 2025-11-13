<?php

require('../functions.php');
require('../partials/database.php');

session_start();

// Fetch student ID and semester ID 
$stmt = $connection->prepare("select student_id from students where student_number = ?");
$stmt->execute([$_POST['student_number']]);
$student_id = $stmt->fetch();

$stmt = $connection->prepare("select id from semesters where code = ?");
$stmt->execute([$_POST['semester']]);
$semester_id = $stmt->fetch();

// Either add new OR to collections or update existing OR
// Check if OR exists
$stmt = $connection->prepare("select * from collections where or_number = ?");
$stmt->execute([$_POST['or_number']]);
$existing_or = $stmt->fetch();

// Check if gcash or cash exists
$cash = $_POST['cash'] == '' ? 0.00 : floatval($_POST['cash']);
$gcash = $_POST['gcash'] == '' ? 0.00 : floatval($_POST['gcash']);

if (!$existing_or) {
  // Insert a new OR
  $stmt = $connection->prepare("insert into collections (or_number, student_id, semester_id, cash, gcash, gcash_refno) values (?, ?, ?, ?, ?, ?)");
  $stmt->execute([
    $_POST['or_number'],
    $student_id['student_id'],
    $semester_id['id'],
    $cash,
    $gcash,
    $_POST['gcash_refno'],
  ]);
} else {
  // Update existing OR
  $stmt = $connection->prepare("update collections set cash = ?, gcash = ?, gcash_refno = ? where or_number = ?");
  $stmt->execute([
    $cash,
    $gcash,
    $_POST['gcash_refno'],
    $_POST['or_number'],
  ]);
}

$action = $existing_or ? 'E' : 'A';

// Add record to audit_trait
$stmt = $connection->prepare("insert into audit_trait (user_id, module, refno, action) values (?, ?, ?, ?)");
$stmt->execute([
  $_SESSION['user']['id'],
  'Collections',
  $_POST['or_number'],
  $action,
]);

// Calculate new OR number
$stmt = $connection->prepare("select or_number from collections order by cast(or_number as unsigned) desc");
$stmt->execute();
$or_number = $stmt->fetch();

if (!$or_number) {
  $new_or = '0000000001';
} else {
  $new_or = str_pad($or_number['or_number'] + 1, 10, '0', STR_PAD_LEFT);
}

// Fetch student name
$stmt = $connection->prepare("select name from students where student_id = ?");
$stmt->execute([$student_id['student_id']]);
$student_name = $stmt->fetch()['name'];

// Calculate total tuition (sum of subjects units * price_unit for student in semester)
$stmt = $connection->prepare("SELECT SUM(sub.units * sub.price_unit) as total_tuition FROM student_subjects ss JOIN subjects sub ON ss.subject_id = sub.id JOIN students s ON ss.student_id = s.student_id JOIN semesters sem ON ss.semester_id = sem.id WHERE s.student_number = ? AND sem.code = ?");
$stmt->execute([$_POST['student_number'], $_POST['semester']]);
$total_tuition = $stmt->fetch()['total_tuition'] ?? 0;

// Calculate total paid
$stmt = $connection->prepare("SELECT SUM(cash + gcash) as total_paid FROM collections WHERE student_id = ? AND semester_id = (SELECT id FROM semesters WHERE code = ?)");
$stmt->execute([$student_id['student_id'], $_POST['semester']]);
$total_paid = $stmt->fetch()['total_paid'] ?? 0;

// Store in session for next form
$_SESSION['last_collection'] = [
  'student_number' => $_POST['student_number'],
  'student_name' => $student_name,
  'semester' => $_POST['semester'],
  'new_or' => $new_or,
  'balance' => $total_tuition - $total_paid
];

// Return to index page
header("Location: index.php");
exit();
