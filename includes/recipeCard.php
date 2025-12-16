<a class="card" href="recipe.php?id=<?php echo $row['id'] ?>">
  <div class="image-container">
    <img src="<?php echo './' . $row['imagePath'] ?>" alt="">
    <div class="recipe-des">
      <p>
        <?php
        echo $row['description'];
        ?>
      </p>
      <h4>Dietary: <?php echo $row['dietaryPref'] ?></h4>
    </div>
  </div>
  <h4 class="cuisine-type"><?php echo $row['cuisineType'] ?></h4>
  <div class="card-content">
    <h4><?php echo $row['title'] ?></h4>
    <div class="card-content-info">
      <span><i class="bi bi-stopwatch"></i><?php echo $row['cookingTimeMinute'] ?> mins</span>
      <?php echo $row['rating'] ? '<span>:</span><span class="rating"><i class="bi bi-star"></i>' . $row['rating'] . '</span>' : '' ?>
      <span>:</span>
      <span><i class="bi bi-bar-chart"></i>
        <?php echo $row['difficulty'] ?>
      </span>
    </div>
  </div>
</a>