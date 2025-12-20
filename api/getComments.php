<?php
header('Content-Type: application/json');

include '../db/connect.php';

$response = [];

if (isset($_GET['post_id'])) {
  $post_id = intval($_GET['post_id']);

  $sql = "SELECT comment, userId, firstName, lastName, date 
            FROM comments 
            JOIN users ON users.id = userId 
            WHERE postId = ? 
            ORDER BY date DESC";

  $stmt = $con->prepare($sql);
  $stmt->bind_param("i", $post_id);

  if ($stmt->execute()) {
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
      $response[] = array(
        'name' => htmlspecialchars($row['firstName'] . ' ' . $row['lastName']),
        'date' => date("d M y, g : i A", strtotime($row['date'])),
        'comment' => nl2br(htmlspecialchars($row['comment']))
      );
    }
  } else {
    echo json_encode(["error" => $con->error]);
    exit;
  }
  $stmt->close();
}

echo json_encode($response);
