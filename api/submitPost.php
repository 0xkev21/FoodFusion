<?php
include '../api/db/connect.php';
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (isset($_POST['submit_post'])) {
  if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
  }
  $user_id = $_SESSION['user_id'];
  $content = $_POST['post_content'];
  $image_path = NULL;

  if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] == 0) {
    $file_name = $_FILES['post_image']['name'];
    $file_tmp = $_FILES['post_image']['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $unique_name = "post_" . $user_id . "_" . time() . "." . $file_ext;
    $upload_destination = "uploads/" . $unique_name;

    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($file_ext, $allowed_ext)) {
      if (move_uploaded_file($file_tmp, $upload_destination)) {
        $image_path = $upload_destination;
      } else {
        echo "Failed to upload image.";
        exit();
      }
    } else {
      echo "Invalid file type. Only JPG, PNG, and GIF allowed.";
      exit();
    }
  }

  $sql = "INSERT INTO communityPosts (userId, content, imagePath) VALUES (?, ?, ?)";
  $stmt = $con->prepare($sql);
  $stmt->bind_param("iss", $user_id, $content, $image_path);

  if ($stmt->execute()) {
    header("Location: community.php?success=posted");
  } else {
    echo "Error: " . $con->error;
  }

  $stmt->close();
  $con->close();
} else {
  header("Location: community.php");
  exit();
}
