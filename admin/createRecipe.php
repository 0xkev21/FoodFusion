<?php
require 'adminHeader.php';
require 'sidebar.php';
include 'checkLogin.php';
?>
<section class="dashboard">
  <h2>Add New Recipe</h2>

  <form action="submit_recipe_logic.php" method="POST" enctype="multipart/form-data" class="recipe-form">

    <div class="form-group">
      <label for="recipe-title">Recipe Title</label>
      <input id="recipe-title" type="text" name="title" required placeholder="e.g., Spicy Chicken Curry">
    </div>

    <div class="form-group">
      <label for="description">Short Description</label>
      <textarea id="description" name="description" rows="2"
        placeholder="A brief overview of the dish..."></textarea>
    </div>

    <div class="recipe-meta-grid">

      <label class="meta-box" for="cuisine-type">
        <div class="meta-icon"><i class="bi bi-globe-americas"></i></div>
        <div class="meta-content">
          <div>Cuisine Type</div>
          <div class="select-wrapper">
            <select id="cuisine-type" name="cuisine">
              <option value="" disabled selected>Select...</option>
              <option value="Italian">Italian</option>
              <option value="Indian">Indian</option>
              <option value="Chinese">Chinese</option>
              <option value="Mexican">Mexican</option>
              <option value="American">American</option>
              <option value="Other">Other</option>
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
              <option value="Easy">Easy</option>
              <option value="Medium" selected>Medium</option>
              <option value="Hard">Hard</option>
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
        <div class="ingredient-row">
          <input type="text" name="ingredients[]" placeholder="e.g. 2 cups Flour" required>
          <button type="button" class="remove-btn" style="visibility: hidden;">
            <i class="bi bi-trash"></i>
          </button>
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
          <textarea name="instructions[]" rows="2" placeholder="e.g. Preheat the oven to 180°C..."
            required></textarea>
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
</body>

</html>