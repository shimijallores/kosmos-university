<?php

require('../functions.php');
require('../partials/database.php');

session_start();

$stmt = $connection->prepare("update semesters set code = ?, start_date = ?, end_date = ?, summer = ? where id = ?");

$summer = $_POST['summer'] == 'on' ? 'Y' : 'N';

$stmt->execute([$_POST['code'], $_POST['start_date'], $_POST['end_date'], $summer, $_POST['semester_id'],]);

header("Location: index.php");
exit();