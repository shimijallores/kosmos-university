<?php

require('../functions.php');
require('../partials/database.php');

session_start();

// Check if the semester with the given code already exists, but ignore the current semester
$stmt = $connection->prepare("SELECT code FROM semesters WHERE code = ? AND id != ?");
$stmt->execute([$_POST['code'], $_POST['semester_id']]);
$existingSemester = $stmt->fetch();

// If the code already exists and is different from the current semester, don't allow update
if ($existingSemester) {
    // Optionally, you can return an error message or handle it however you like
    echo "A semester with this code already exists, update not allowed.";
} else {
    // No existing semester found with the same code, proceed with the update
    $stmt = $connection->prepare("UPDATE semesters SET start_date = ?, end_date = ?, summer = ? WHERE id = ?");

    // Only update the `code` if it's being changed, otherwise skip updating it
    if ($_POST['code'] != $semester['code']) {
        $stmt = $connection->prepare("UPDATE semesters SET code = ?, start_date = ?, end_date = ?, summer = ? WHERE id = ?");
        $stmt->execute([$_POST['code'], $_POST['start_date'], $_POST['end_date'], ($_POST['summer'] == 'on' ? 'Y' : 'N'), $_POST['semester_id']]);
    } else {
        // Code is the same, only update `summer`, `start_date`, and `end_date`
        $stmt->execute([$_POST['start_date'], $_POST['end_date'], ($_POST['summer'] == 'on' ? 'Y' : 'N'), $_POST['semester_id']]);
    }
}


header("Location: index.php");
exit();