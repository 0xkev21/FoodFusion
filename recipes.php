<?php
$pageTitle = 'Recipes';

require 'includes/header.php';
$recipeData = [];
$stmt = $con->prepare("SELECT recipes.id, title, description, cuisineType, difficulty, dietaryPref, cookingTimeMinute, imagePath, AVG(rating) as rating, 
                          createdAt from recipes
                          left join reciperating on recipes.id = recipeId 
                          join cuisineTypes on cuisineTypeId = cuisineTypes.id
                          join cookingDifficulty on difficultyId = cookingDifficulty.id
                          join dietaryPref on dietaryPrefId = dietaryPref.id
                          group by recipes.id order by createdAt desc, recipes.id asc");
if ($stmt->execute()) {
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $recipeData[] = $row;
  }
}
?>
<script>
  const recipes = <?php echo json_encode($recipeData); ?>;
  console.log(recipes);
</script>
<script src="recipes.js" defer></script>
<section class="recipes-hero-section">
  <h2>Explore our Recipes</h2>
  <p>Discover a world of flavors with our curated collection</p>
</section>
<section class="filter-recipes">
  <div class="filter-side">
    <h3>Filter by:</h3>
    <div class="filter-options">
      <div class="search-container">
        <div class="search-input">
          <i class="bi bi-search"></i>
          <input type="text" name="search-input" id="search-input" placeholder="Search recipes...">
        </div>
      </div>
      <div class="filter-container">
        <h4>Cuisine Type</h4>

        <div class="radio-group">
          <div class="radio-container">
            <input type="radio" name="cuisine-type" id="cuisine-any" value="any" checked>
            <label for="cuisine-any">Any</label>
          </div>
          <?php
          $stmtDiff = $con->query("select cuisineType, id from cuisineTypes");
          while ($row = $stmtDiff->fetch_assoc()) {
            $cuisine = $row["cuisineType"];
          ?>
            <div class="radio-container">
              <input type="radio" name="cuisine-type" id="cuisine-<?php echo $cuisine ?>" value="<?php echo $cuisine ?>">
              <label for="cuisine-<?php echo $cuisine ?>"><?php echo $cuisine ?></label>
            </div>
          <?php
          }
          ?>
        </div>
      </div>
      <div class="filter-container">
        <h4>Dietary Preferences</h4>
        <div class="radio-group">
          <div class="radio-container">
            <input type="radio" name="dietary-pref" id="dietary-any" value="any" checked>
            <label for="dietary-any">Any</label>
          </div>
          <?php
          $stmtDiff = $con->query("select dietaryPref, id from dietaryPref");
          while ($row = $stmtDiff->fetch_assoc()) {
            $diet = $row["dietaryPref"];
          ?>
            <div class="radio-container">
              <input type="radio" name="dietary-pref" id="diet-<?php echo $diet ?>" value="<?php echo $diet ?>">
              <label for="diet-<?php echo $diet ?>"><?php echo $diet ?></label>
            </div>
          <?php
          }
          ?>
        </div>
      </div>
      <div class="filter-container">
        <h4>Cooking Difficulty</h4>
        <div class="radio-container">
          <input type="radio" name="difficulty" id="difficulty-any" value="any" checked>
          <label for="difficulty">Any</label>
        </div>
        <div class="radio-group">
          <?php
          $stmtDiff = $con->query("select difficulty, id from cookingDifficulty");
          while ($row = $stmtDiff->fetch_assoc()) {
            $diff = $row["difficulty"];
          ?>
            <div class="radio-container">
              <input type="radio" name="difficulty" id="cooking-<?php echo $diff ?>" value="<?php echo $diff ?>">
              <label for="cooking-<?php echo $diff ?>"><?php echo $diff ?></label>
            </div>
          <?php
          }
          ?>
        </div>
      </div>
      <div class="filter-buttons-container">
        <button type="button" class="clear-filters-btn">Clear All</button>
      </div>
    </div>
  </div>
  <div class="recipe-results">
    <div class="sort-container">
      <p>Showing Recipes</p>
      <div>
        <p>Sort by:</p>
        <select name="sort" id="sort">
          <option value="newest">Newest</option>
          <option value="name">Name</option>
          <option value="rating">Rating</option>
        </select>
      </div>
    </div>
    <div class="recipes-container">
    </div>
  </div>
</section>
<?php
require 'includes/footer.php';
?>