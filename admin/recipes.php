<?php
require 'adminHeader.php';
require 'sidebar.php';
include 'checkLogin.php';

$sql = "SELECT recipes.id, imagePath, title, createdAt, cuisineType, difficulty, isFeatured 
        FROM recipes
        JOIN cuisineTypes ON cuisineTypeId = cuisineTypes.id
        JOIN cookingDifficulty ON difficultyId = cookingdifficulty.id 
        ORDER BY createdAt DESC";

$result = $con->query($sql);
?>

<section class="dashboard">

  <div class="header-flex">
    <h2>Manage Recipes</h2>
    <a href="createRecipe.php" class="btn-primary-sm">+ New Recipe</a>
  </div>

  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>Image</th>
          <th>Title</th>
          <th>Cuisine</th>
          <th>Difficulty</th>
          <th>Date</th>
          <th>Featured?</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td>
                <div class="table-img">
                  <?php if ($row['imagePath']): ?>
                    <img src="<?php echo '/foodfusion/' . $row['imagePath']; ?>" alt="Recipe">
                  <?php else: ?>
                    <div class="no-img">?</div>
                  <?php endif; ?>
                </div>
              </td>

              <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
              <td><?php echo htmlspecialchars($row['cuisineType']); ?></td>

              <td>
                <?php
                $diff = $row['difficulty'];
                $badgeClass = ($diff == 'Easy') ? 'badge-success' : (($diff == 'Medium') ? 'badge-warning' : 'badge-danger');
                ?>
                <span class="badge <?php echo $badgeClass; ?>"><?php echo $diff; ?></span>
              </td>

              <td class="text-muted"><?php echo date('M d, Y', strtotime($row['createdAt'])); ?></td>

              <td>
                <label class="switch">
                  <input type="checkbox"
                    class="featured-toggle"
                    data-id="<?php echo $row['id']; ?>"
                    <?php echo ($row['isFeatured'] == 1) ? 'checked' : ''; ?>>
                  <span class="slider round"></span>
                </label>
              </td>

            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" style="text-align:center;">No recipes found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</section>
</main>

<script src="toggleFeatured.js"> </script>

</body>

</html>