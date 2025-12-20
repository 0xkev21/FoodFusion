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
        <div class="radio-container">
          <input type="radio" name="cuisine-type" id="cuisine-any" value="any" checked>
          <label for="cuisine-any">Any</label>
        </div>
        <div class="radio-group">
          <div class="radio-container">
            <input type="radio" name="cuisine-type" id="indian" Value="Indian">
            <label for="indian">Indian</label>
          </div>
          <div class="radio-container">
            <input type="radio" name="cuisine-type" id="thailand" value="Thai">
            <label for="thailand">Thailand</label>
          </div>
          <div class="radio-container">
            <input type="radio" name="cuisine-type" id="turkish" value="Turkish">
            <label for="turkish">Turkish</label>
          </div>
        </div>
      </div>
      <div class="filter-container">
        <h4>Dietary Preferences</h4>
        <div class="radio-container">
          <input type="radio" name="dietary-pref" id="dietary-any" value="any" checked>
          <label for="dietary-any">Any</label>
        </div>
        <div class="radio-group">
          <div class="radio-container">
            <input type="radio" name="dietary-pref" id="vege" value="Vegetarian">
            <label for="vege">Vegetarian</label>
          </div>
          <div class="radio-container">
            <input type="radio" name="dietary-pref" id="vegan" value="Vegan">
            <label for="vegan">Vegan</label>
          </div>
          <div class="radio-container">
            <input type="radio" name="dietary-pref" id="glu-free" value="Gluten-Free">
            <label for="glu-free">Gluten-Free</label>
          </div>
          <div class="radio-container">
            <input type="radio" name="dietary-pref" id="halal" value="Halal">
            <label for="halal">Halal</label>
          </div>
          <div class="radio-container">
            <input type="radio" name="dietary-pref" id="diet-none" value="None">
            <label for="diet-none">None</label>
          </div>
        </div>
      </div>
      <div class="filter-container">
        <h4>Cooking Difficulty</h4>
        <div class="radio-group">
          <div class="radio-container">
            <input type="radio" name="difficulty" id="cooking-any" value="any" checked>
            <label for="cooking-any">Any</label>
          </div>
          <div class="radio-container">
            <input type="radio" name="difficulty" id="easy" value="Easy">
            <label for="easy">Easy</label>
          </div>
          <div class="radio-container">
            <input type="radio" name="difficulty" id="medium" value="Medium">
            <label for="medium">Medium</label>
          </div>
          <div class="radio-container">
            <input type="radio" name="difficulty" id="hard" value="Hard">
            <label for="hard">Hard</label>
          </div>
          <div class="radio-container">
            <input type="radio" name="difficulty" id="profession" value="Profession">
            <label for="profession">Profession</label>
          </div>
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