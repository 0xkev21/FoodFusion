<?php
$printRecipe = true;

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo "<script>window.location.assign('index.php');</script>";
}

$id = intval($_GET['id']);
include 'db/connect.php';

$sqlRecipe = "SELECT recipes.id, title, description, cuisineType, difficulty, dietaryPref, cookingTimeMinute, imagePath, AVG(rating) as rating from recipes
        left join reciperating on recipes.id = recipeId 
        join cuisineTypes on cuisineTypeId = cuisineTypes.id
        join cookingDifficulty on difficultyId = cookingDifficulty.id
        join dietaryPref on dietaryPrefId = dietaryPref.id
        where recipes.id = ?
        group by recipes.id;";
$stmtRecipe = $con->prepare($sqlRecipe);
$stmtRecipe->bind_param("i", $id);
$recipe = [];
if ($stmtRecipe->execute()) {
  $result = $stmtRecipe->get_result();
  $recipe = $result->fetch_assoc();
}
$pageTitle = $recipe['title'];
require 'includes/header.php';
?>
<section class="recipe">
  <div class="recipe-container" id="printable-area">
    <div class="recipe-left">
      <div class="recipe-left-text">
        <p class="cuisintType"><?php echo $recipe['cuisineType'] ?></p>
        <h2><?php echo $recipe['title'] ?></h2>
        <p><?php echo $recipe['description'] ?></p>
        <div>
          <?php
          if (isset($recipe['rating'])) {
            for ($i = 0; $i < $recipe['rating']; $i++) {
              echo "<i class='bi bi-star-fill'></i>";
            }
            for ($i = 0; $i < 5 - $recipe['rating']; $i++) {
              echo "<i class='bi bi-star'></i>";
            }
          }
          ?>
        </div>
      </div>
      <div class="image-container">
        <img src="<?php echo './' . $recipe['imagePath'] ?>" alt="">
      </div>
    </div>
    <div class="recipe-right">
      <div class="recipe-info">
        <div class="time">
          <i class="bi bi-stopwatch"></i>
          <div>
            <p>Cooking Time</p>
            <p><?php echo $recipe['cookingTimeMinute'] ?> Minutes</p>
          </div>
        </div>
        <div class="difficulty">
          <i class="bi bi-bar-chart"></i>
          <div>
            <p>Difficulty</p>
            <p><?php echo $recipe['difficulty'] ?></p>
          </div>
        </div>
        <div class="dietary-pref">
          <i class="bi bi-fork-knife"></i>
          <div>
            <p>Dietary Preference</p>
            <p><?php echo $recipe['dietaryPref'] ?></p>
          </div>
        </div>
      </div>
      <div>
        <h3>Ingredients</h3>
        <ul>
          <?php
          $sqlIngredients = "SELECT ingredient, amount, unit FROM ingredients
                              join recipeIngredients on ingredients.id = ingredientId
                              join unit on unitId = unit.id
                              join recipes on recipes.id = recipeId
                              where recipes.id = ?";
          $stmtIngredients = $con->prepare($sqlIngredients);
          $stmtIngredients->bind_param("i", $id);
          if ($stmtIngredients->execute()) {
            $result = $stmtIngredients->get_result();
            while ($row = $result->fetch_assoc()) {
              $unit = $row['unit'] == 'g' ? 'g' : ($row['amount'] > 1 ? $row['unit'] . 's' : $row['unit']);
              echo "<li>{$row['ingredient']} - {$row['amount']} {$unit}</li>";
            }
          }
          ?>
        </ul>
      </div>
      <div>
        <h3>Instructions</h3>
        <ol>
          <?php
          $sqlInstructions = "select * from instructions where recipeId = ? order by stepNumber";
          $stmtInstructions = $con->prepare($sqlInstructions);
          $stmtInstructions->bind_param("i", $id);
          if ($stmtInstructions->execute()) {
            $result = $stmtInstructions->get_result();
            while ($row = $result->fetch_assoc()) {
              echo "<li>{$row['instruction']}</li>";
            }
          }
          ?>
        </ol>
      </div>
    </div>
</section>
<section class="recipe-actions">
  <div>
    <h4>Save this Recipe</h4>
    <button class="primary print-btn"><i class="bi bi-download"></i>Download</button>
  </div>
  <div>
    <h4>Rate this Recipe</h4>
    <form action="">
      <div class="rating"><input type="radio" id="star5" name="rating" value="5">
        <label for="star5">&#9733;</label>
        <input type="radio" id="star4" name="rating" value="4">
        <label for="star4">&#9733;</label>
        <input type="radio" id="star3" name="rating" value="3">
        <label for="star3">&#9733;</label>
        <input type="radio" id="star2" name="rating" value="2">
        <label for="star2">&#9733;</label>
        <input type="radio" id="star1" name="rating" value="1">
        <label for="star1">&#9733;</label>
      </div>
      <button class="primary">Submit</button>
    </form>
  </div>
</section>
<?php
require 'includes/footer.php';
?>