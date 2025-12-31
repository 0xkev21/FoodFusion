<?php
require 'adminHeader.php';
require 'sidebar.php';
require 'checkLogin.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $msg = "";
  if (isset($_POST['add_cuisine'])) {
    $name = $_POST['cuisine_name'];
    $stmt = $con->prepare("INSERT INTO cuisineTypes (cuisineType) VALUES (?)");
    $stmt->bind_param("s", $name);
    if ($stmt->execute()) $msg = "Cuisine added!";
  } elseif (isset($_POST['add_difficulty'])) {
    $name = $_POST['difficulty_name'];
    $stmt = $con->prepare("INSERT INTO cookingDifficulty (difficulty) VALUES (?)");
    $stmt->bind_param("s", $name);
    if ($stmt->execute()) $msg = "Difficulty level added!";
  } elseif (isset($_POST['add_dietary'])) {
    $name = $_POST['dietary_name'];
    $stmt = $con->prepare("INSERT INTO dietaryPref (dietaryPref) VALUES (?)");
    $stmt->bind_param("s", $name);
    if ($stmt->execute()) $msg = "Dietary preference added!";
  }

  if ($msg) echo "<script>window.location.href='manageCategories.php?status=success';</script>";
}
?>

<section class="dashboard">
  <div class="header-flex">
    <h2>Manage Recipe Categories</h2>
  </div>

  <div class="category-grid">
    <div class="category-card">
      <h3><i class="bi bi-globe-americas"></i> Cuisine Types</h3>
      <form action="" method="POST">
        <input type="text" name="cuisine_name" placeholder="e.g. Italian, Thai" required>
        <button type="submit" name="add_cuisine" class="btn-primary-sm">Add Cuisine</button>
      </form>
    </div>

    <div class="category-card">
      <h3><i class="bi bi-bar-chart"></i> Difficulty Levels</h3>
      <form action="" method="POST">
        <input type="text" name="difficulty_name" placeholder="e.g. Master Chef" required>
        <button type="submit" name="add_difficulty" class="btn-primary-sm">Add Difficulty</button>
      </form>
    </div>

    <div class="category-card">
      <h3><i class="bi bi-flower1"></i> Dietary Preferences</h3>
      <form action="" method="POST">
        <input type="text" name="dietary_name" placeholder="e.g. Keto, Vegan" required>
        <button type="submit" name="add_dietary" class="btn-primary-sm">Add Preference</button>
      </form>
    </div>
  </div>
</section>