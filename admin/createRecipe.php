<?php
require 'adminHeader.php';
require 'sidebar.php';
include 'checkLogin.php';

$msg = "";
$error = "";

$unitOptions = "";
$unitRes = $con->query("SELECT * FROM unit");
if ($unitRes) {
  while ($row = $unitRes->fetch_assoc()) {
    $unitOptions .= '<option value="' . $row['id'] . '">' . $row['unit'] . '</option>';
  }
}

if (isset($_POST['submit_recipe'])) {

  $title = trim($_POST['title']);
  $desc  = trim($_POST['description']);
  $time  = intval($_POST['cooking_time']);
  $cuisineId = intval($_POST['cuisine']);
  $diffId    = intval($_POST['difficulty']);
  $dietaryId = intval($_POST['dietary_preference']);

  $ingNames = $_POST['ing_name'];
  $ingAmounts = $_POST['ing_amount'];
  $ingUnits = $_POST['ing_unit'];

  $instructions = $_POST['instructions'];

  $imagePath = "";
  if (isset($_FILES['recipe_image']) && $_FILES['recipe_image']['error'] === 0) {
    $fileName = $_FILES['recipe_image']['name'];
    $fileTmp  = $_FILES['recipe_image']['tmp_name'];
    $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($fileExt, $allowed)) {
      $newName = uniqid('recipe_', true) . "." . $fileExt;
      $uploadDir = '../images/';
      if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
      $destPath = $uploadDir . $newName;
      if (move_uploaded_file($fileTmp, $destPath)) {
        $imagePath = 'images/' . $newName;
      } else {
        $error = "Error: Failed to move uploaded file.";
      }
    } else {
      $error = "Error: Invalid file type.";
    }
  } else {
    $error = "Error: Image is required.";
  }

  if (empty($error)) {
    $sql = "INSERT INTO recipes (title, description, imagePath, cookingTimeMinute, cuisineTypeId, difficultyId, dietaryPrefId, createdAt) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssiiii", $title, $desc, $imagePath, $time, $cuisineId, $diffId, $dietaryId);

    if ($stmt->execute()) {
      $recipeId = $stmt->insert_id;

      $checkIng = $con->prepare("SELECT id FROM ingredients WHERE ingredient = ?");
      $insertIng = $con->prepare("INSERT INTO ingredients (ingredient) VALUES (?)");
      $linkIng = $con->prepare("INSERT INTO recipeingredients (recipeId, ingredientId, amount, unitId) VALUES (?, ?, ?, ?)");

      if (!empty($ingNames)) {
        foreach ($ingNames as $index => $name) {
          $name = trim($name);
          $amount = floatval($ingAmounts[$index]);
          $unitId = intval($ingUnits[$index]);

          if (!empty($name)) {
            $checkIng->bind_param("s", $name);
            $checkIng->execute();
            $res = $checkIng->get_result();

            if ($row = $res->fetch_assoc()) {
              $ingId = $row['id'];
            } else {
              $insertIng->bind_param("s", $name);
              $insertIng->execute();
              $ingId = $insertIng->insert_id;
            }

            $linkIng->bind_param("iidi", $recipeId, $ingId, $amount, $unitId);
            $linkIng->execute();
          }
        }
      }

      $instSql = "INSERT INTO instructions (recipeId, stepNumber, instruction) VALUES (?, ?, ?)";
      $instStmt = $con->prepare($instSql);
      if (!empty($instructions)) {
        foreach ($instructions as $index => $inst) {
          $inst = trim($inst);
          $stepNum = $index + 1;
          if (!empty($inst)) {
            $instStmt->bind_param("iis", $recipeId, $stepNum, $inst);
            $instStmt->execute();
          }
        }
      }

      header("Location: recipes.php?msg=Recipe published successfully");
      exit();
    } else {
      $error = "Database Error: " . $con->error;
    }
  }
}

?>

<section class="dashboard">
  <h2>Add New Recipe</h2>

  <?php if ($error): ?>
    <div style="background: #ffe6e6; color: red; padding: 10px; margin-bottom: 10px;">
      <?php echo $error; ?>
    </div>
  <?php endif; ?>

  <form action="" method="POST" enctype="multipart/form-data" class="recipe-form">

    <div class="form-group">
      <label for="recipe-title">Recipe Title</label>
      <input id="recipe-title" type="text" name="title" required placeholder="e.g., Spicy Chicken Curry">
    </div>

    <div class="form-group">
      <label for="description">Short Description</label>
      <textarea id="description" name="description" rows="2" placeholder="A brief overview of the dish..."></textarea>
    </div>

    <div class="recipe-meta-grid">

      <label class="meta-box" for="cuisine-type">
        <div class="meta-icon"><i class="bi bi-globe-americas"></i></div>
        <div class="meta-content">
          <div>Cuisine Type</div>
          <div class="select-wrapper">
            <select id="cuisine-type" name="cuisine">
              <?php
              $stmtTypes = $con->query("select cuisineType, id from cuisinetypes");
              while ($row = $stmtTypes->fetch_assoc()) {
                echo '<option value="' . $row['id'] . '">' . $row['cuisineType'] . '</option>';
              }
              ?>
            </select>
            <i class="bi bi-chevron-down dropdown-arrow"></i>
          </div>
        </div>
      </label>

      <label class="meta-box" for="difficulty">
        <div class="meta-icon"><i class="bi bi-bar-chart-fill"></i></div>
        <div class="meta-content">
          <div>Difficulty</div>
          <div class="select-wrapper">
            <select id="difficulty" name="difficulty">
              <?php
              $stmtDiff = $con->query("select difficulty, id from cookingdifficulty");
              while ($row = $stmtDiff->fetch_assoc()) {
                echo '<option value="' . $row['id'] . '">' . $row['difficulty'] . '</option>';
              }
              ?>
            </select>
            <i class="bi bi-chevron-down dropdown-arrow"></i>
          </div>
        </div>
      </label>

      <label class="meta-box" for="dietary">
        <div class="meta-icon"><i class="bi bi-flower1"></i></div>
        <div class="meta-content">
          <div>Dietary Pref.</div>
          <div class="select-wrapper">
            <select id="dietary" name="dietary_preference" required>
              <?php
              $stmtDiet = $con->query("select dietaryPref, id from dietarypref");
              while ($row = $stmtDiet->fetch_assoc()) {
                echo '<option value="' . $row['id'] . '">' . $row['dietaryPref'] . '</option>';
              }
              ?>
            </select>
            <i class="bi bi-chevron-down dropdown-arrow"></i>
          </div>
        </div>
      </label>

      <label class="meta-box" for="time">
        <div class="meta-icon"><i class="bi bi-clock-history"></i></div>
        <div class="meta-content">
          <div>Cooking Time</div>
          <div class="input-with-suffix">
            <input id="time" type="number" name="cooking_time" placeholder="0" min="1">
            <span class="suffix">mins</span>
          </div>
        </div>
      </label>

    </div>

    <div class="form-group">
      <label>Ingredients</label>
      <div id="ingredients-container">
        <div class="ingredient-row" style="display: flex; gap: 10px; margin-bottom: 10px;">
          <input type="text" name="ing_name[]" placeholder="Ingredient Name (e.g. Flour)" style="flex: 1;" required>
          <input type="number" name="ing_amount[]" placeholder="Qty" step="0.1" style="width: 80px;" required>

          <select name="ing_unit[]" style="width: 100px;" required>
            <?php echo $unitOptions; ?>
          </select>


          <button type="button" class="remove-btn" style="visibility: hidden;"><i class="bi bi-trash"></i></button>
        </div>
      </div>

      <button type="button" class="btn-add-item" onclick="addIngredient()">
        <i class="bi bi-plus-circle-fill"></i> Add Ingredient
      </button>
    </div>

    <div class="form-group">
      <label>Cooking Instructions</label>
      <div id="instructions-container">
        <div class="instruction-row">
          <span class="step-number">1</span>
          <textarea name="instructions[]" rows="2" placeholder="e.g. Preheat the oven to 180°C..." required></textarea>
          <button type="button" class="remove-btn" style="visibility: hidden;">
            <i class="bi bi-trash"></i>
          </button>
        </div>
      </div>
      <button type="button" class="btn-add-item" onclick="addInstruction()">
        <i class="bi bi-plus-circle-fill"></i> Add Next Step
      </button>
    </div>

    <div class="form-group">
      <label>Recipe Image</label>
      <input type="file" name="recipe_image" accept="image/*" required>
    </div>

    <button type="submit" name="submit_recipe" class="btn-submit">Publish Recipe</button>
  </form>
</section>
</main>

<script>
  const unitOptionsHTML = <?php echo json_encode($unitOptions); ?>;
</script>


</body>

</html>