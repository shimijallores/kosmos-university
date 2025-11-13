<?php

require('../functions.php');
require('../partials/database.php');

session_start();

// Delete collection
$stmt = $connection->prepare("delete from collections where or_number = ?");
$stmt->execute([$_POST['or_number']]);

// Add record to audit_trait
$stmt = $connection->prepare("insert into audit_trait (user_id, module, refno, action) values (?, ?, ?, ?)");
$stmt->execute([
    $_SESSION['user']['id'],
    'Collections',
    $_POST['or_number'],
    'D',
]);

header("Location: index.php");
exit();
