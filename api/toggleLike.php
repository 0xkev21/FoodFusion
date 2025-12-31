<?php
session_start();
require '../db/connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  http_response_code(401);
  echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
  exit();
}

if (!isset($_POST['post_id'])) {
  echo json_encode(['status' => 'error', 'message' => 'No post ID provided']);
  exit();
}

$userId = $_SESSION['user_id'];
$postId = intval($_POST['post_id']);

$checkSql = "SELECT id FROM likes WHERE userId = ? AND postId = ?";
$stmt = $con->prepare($checkSql);
$stmt->bind_param("ii", $userId, $postId);
$stmt->execute();
$result = $stmt->get_result();

$action = '';

if ($result->num_rows > 0) {
  $deleteSql = "DELETE FROM likes WHERE userId = ? AND postId = ?";
  $delStmt = $con->prepare($deleteSql);
  $delStmt->bind_param("ii", $userId, $postId);
  $delStmt->execute();
  $action = 'unliked';
} else {
  $insertSql = "INSERT INTO likes (userId, postId) VALUES (?, ?)";
  $insStmt = $con->prepare($insertSql);
  $insStmt->bind_param("ii", $userId, $postId);
  $insStmt->execute();
  $action = 'liked';
}

$countSql = "SELECT COUNT(*) as total FROM likes WHERE postId = ?";
$countStmt = $con->prepare($countSql);
$countStmt->bind_param("i", $postId);
$countStmt->execute();
$countResult = $countStmt->get_result();
$row = $countResult->fetch_assoc();

echo json_encode([
  'status' => $action,
  'new_count' => $row['total']
]);
