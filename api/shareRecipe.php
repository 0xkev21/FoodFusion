<?php
session_start();
include '../db/connect.php';

if (isset($_POST['submit_share']) && isset($_SESSION['user_id'])) {
  $userId = $_SESSION['user_id'];
  $recipeId = intval($_POST['recipe_id']);
  $content = trim($_POST['content']);

  $postSql = "INSERT INTO communityposts (userId, content, uploadAt) VALUES (?, ?, NOW())";
  $stmtPost = $con->prepare($postSql);
  $stmtPost->bind_param("is", $userId, $content);

  if ($stmtPost->execute()) {
    $newPostId = $con->insert_id;

    $shareSql = "INSERT INTO Shares (postId, shareId) VALUES (?, ?)";
    $stmtShare = $con->prepare($shareSql);
    $stmtShare->bind_param("ii", $newPostId, $recipeId);

    if ($stmtShare->execute()) {
      header("Location: ../recipe.php?id=$recipeId&status=shared");
    } else {
      header("Location: ../recipe.php?id=$recipeId&status=error");
    }
  } else {
    header("Location: ../recipe.php?id=$recipeId&status=error");
  }
  exit();
}
