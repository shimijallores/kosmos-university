<?php

require('../functions.php');
require('../partials/database.php');

$stmt = $connection->prepare("select * from students where name like ?");

$name = "%" . $_GET['name'] . "%";

$stmt->execute([$name]);

$student = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode($student);