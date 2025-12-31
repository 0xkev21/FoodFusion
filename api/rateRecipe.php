<?php
session_start();
include '../db/connect.php';

if (isset($_POST['submit_rating']) && isset($_SESSION['user_id'])) {
  $userId = $_SESSION['user_id'];
  $recipeId = intval($_POST['recipe_id']);
  $rating = intval($_POST['rating']);

  $sql = "INSERT INTO reciperating (rating, userId, recipeId) VALUES (?, ?, ?) 
            ON DUPLICATE KEY UPDATE rating = VALUES(rating)";

  $stmt = $con->prepare($sql);
  $stmt->bind_param("iii", $rating, $userId, $recipeId);

  if ($stmt->execute()) {
    header("Location: ../recipe.php?id=$recipeId&status=rated");
  } else {
    header("Location: ../recipe.php?id=$recipeId&status=error");
  }
  exit();
}
