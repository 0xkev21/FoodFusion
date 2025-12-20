<?php
include '../db/connect.php';
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (isset($_POST['submit_comment']) || isset($_POST['post_id']) || isset($_POST['comment'])) {
  if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
  }
  $user_id = $_SESSION['user_id'];
  $post_id = $_POST['post_id'];
  $comment = $_POST['comment'];
  $image_path = NULL;

  $sql = "INSERT INTO comments (userId, comment, postId) VALUES (?, ?, ?)";
  $stmt = $con->prepare($sql);
  $stmt->bind_param("isi", $user_id, $comment, $post_id);

  if ($stmt->execute()) {
    header("Location: ../community.php?success=commented&pid=$post_id");
  } else {
    echo "Error: " . $con->error;
  }

  $stmt->close();
  $con->close();
} else {
  header("Location: community.php");
  exit();
}
