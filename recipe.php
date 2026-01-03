<?php
$printRecipe = true;
$userRating = 0;
$hasRated = false;

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo "<script>window.location.assign('index.php');</script>";
}

$id = intval($_GET['id']);
include 'db/connect.php';

$sqlRecipe = "SELECT recipes.id, title, description, cuisineType, difficulty, dietaryPref, cookingTimeMinute, imagePath, AVG(rating) as rating from recipes
        left join reciperating on recipes.id = recipeId 
        join cuisinetypes on cuisineTypeId = cuisinetypes.id
        join cookingdifficulty on difficultyId = cookingdifficulty.id
        join dietarypref on dietaryPrefId = dietarypref.id
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

if (isset($_SESSION['user_id'])) {
  $userId = $_SESSION['user_id'];
}

$checkSql = "SELECT rating FROM reciperating WHERE userId = ? AND recipeId = ?";
$checkStmt = $con->prepare($checkSql);
$checkStmt->bind_param("ii", $userId, $id);

if ($checkStmt->execute()) {
  $checkResult = $checkStmt->get_result();
  if ($row = $checkResult->fetch_assoc()) {
    $userRating = intval($row['rating']);
    $hasRated = true;
  }
}
?>
<section class="recipe">
  <div class="recipe-container" id="printable-area">
    <div class="recipe-left">
      <div class="recipe-left-text">
        <p class="cuisintType"><?php echo $recipe['cuisineType'] ?></p>
        <h2><?php echo $recipe['title'] ?></h2>
        <p><?php echo $recipe['description'] ?></p>
        <div class="rating-avg">
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
                              join recipeingredients on ingredients.id = ingredientId
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
    <h4>Share to Community</h4>
    <?php if (isset($_SESSION['user_id'])): ?>
      <button type="button" class="primary" onclick="openShareModal()">
        <i class="bi bi-share"></i> Share Recipe
      </button>
    <?php else: ?>
      <p style="font-size: 0.85rem; color: #666;">Login to share this recipe</p>
      <button type="button" class="primary to-register-btn">Login to Share</button>
    <?php endif; ?>
  </div>
  <div>
    <h4><?php echo $hasRated ? "Update Your Rating" : "Rate this Recipe"; ?></h4>
    <?php if (isset($_SESSION['user_id'])): ?>
      <form action="api/rateRecipe.php" method="POST">
        <input type="hidden" name="recipe_id" value="<?php echo $id; ?>">
        <div class="rating">
          <?php for ($i = 5; $i >= 1; $i--): ?>
            <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>"
              <?php echo ($userRating == $i) ? 'checked' : ''; ?> required>
            <label for="star<?php echo $i; ?>">&#9733;</label>
          <?php endfor; ?>
        </div>
        <button type="submit" name="submit_rating" class="primary">
          <?php echo $hasRated ? "Update Rating" : "Submit"; ?>
        </button>
      </form>
    <?php else: ?>
      <div class="rating-placeholder">
        <p style="font-size: 0.85rem; color: #666; margin-bottom: 10px;">Login to rate this recipe</p>
        <button type="button" class="primary to-register-btn">Rate Now</button>
      </div>
    <?php endif; ?>
  </div>
</section>
<div id="share-modal" class="custom-modal" style="display:none;">
  <div class="modal-content">
    <div class="modal-header">
      <h3>Share: <?php echo htmlspecialchars($recipe['title']); ?></h3>
      <span class="close-btn" onclick="closeShareModal()">&times;</span>
    </div>
    <form action="api/shareRecipe.php" method="POST">
      <div class="modal-body">
        <input type="hidden" name="recipe_id" value="<?php echo $id; ?>">
        <textarea name="content" placeholder="Write something about this recipe..." required></textarea>
      </div>
      <div class="modal-footer" style="padding: 15px; text-align: right;">
        <button type="submit" name="submit_share" class="primary">Post to Community</button>
      </div>
    </form>
  </div>
</div>
<span class="toast" id="toast">
  Hello World
</span>
<script src="shareRecipe.js"></script>
<?php
require 'includes/footer.php';
?>