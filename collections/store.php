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

// Return to index page
header("Location: index.php");
exit();
