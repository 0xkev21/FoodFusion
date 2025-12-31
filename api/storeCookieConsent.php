<?php
session_start();
include '../db/connect.php';

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$isAccepted = 1;

$sql = "INSERT INTO cookie_consent (userId, is_accepted) VALUES (?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("ii", $userId, $isAccepted);
$stmt->execute();
