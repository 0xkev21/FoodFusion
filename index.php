<?php
$pageTitle = 'Home';
require 'includes/header.php';
?>
<section class="hero-section">
  <div>
    <h2 class="hero-title">Discover the Joy of Home Cooking</h2>
    <p>FoodFusion is a vibrant community for food lovers to share, discover, and learn the art of home cooking.</p>
    <button class="primary">Join for Free</button>
  </div>
</section>
<section class="featured">
  <h3>Trending Now</h3>
  <div class="featured-container">
    <?php
    $stmt = $con->prepare("SELECT recipes.id, title, description, cuisineType, difficulty, dietaryPref, cookingTimeMinute, imagePath, AVG(rating) as rating from recipes
                          left join reciperating on recipes.id = recipeId 
                          join cuisineTypes on cuisineTypeId = cuisineTypes.id
                          join cookingDifficulty on difficultyId = cookingDifficulty.id
                          join dietaryPref on dietaryPrefId = dietaryPref.id
                          where isFeatured = true group by recipes.id;");
    if ($stmt->execute()) {
      $featuredRecipes = $stmt->get_result();
      while ($row = $featuredRecipes->fetch_assoc()) {
        require 'includes/recipeCard.php';
      }
    }
    ?>
  </div>
</section>
<section class="upcoming">
  <h3>Upcoming Culinary Events</h3>
  <div class="events-container">
    <?php
    $stmt = $con->prepare("select * from events order by eventDate limit 3;");
    if ($stmt->execute()) {
      $upcomingEvents = $stmt->get_result();
      while ($row = $upcomingEvents->fetch_assoc()) {
        require 'includes/eventCard.php';
      }
    }
    ?>
  </div>
</section>
<?php
require 'includes/footer.php';
?>