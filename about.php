<?php
$pageTitle = 'About';
require 'includes/header.php';
?>
<section class="about-hero-section">
  <div class="about-container">
    <h2 class="hero-title">Celebrating the Joy of Home Cooking</h2>
    <p>Discover, create, and share delicious recipes with a passionate community of food lovers. Welcome to our
      kitchen !</p>
  </div>
</section>
<section class="mission">
  <h2>Mission</h2>
  <p>
    At FoodFusion, our core philosophy is to make cooking accessible, enjoyable, and a shared experience. We believe
    that everyone has a chef within, and our mission is to provide the tools, inspiration, and community support to
    unlock that potential. We're dedicated to bring people together through the universal language of food, one
    recipe at a time.
  </p>
</section>
<section class="values">
  <h2>Core Values</h2>
  <div class="values-container">
    <div class="cta-card">
      <div class="icon-container value-share-icon"><i class="bi bi-share"></i></div>
      <h4>Share</h4>
      <p>Fostering a space where culinary creations and traditions are shared openly and joyfully.</p>
    </div>
    <div class="cta-card">
      <div class="icon-container value-learn-icon"><i class="bi bi-mortarboard"></i></div>
      <h4>Learn</h4>
      <p>Empowering home cooks of all levels to expand their skills and discover new flavors with confidence.</p>
    </div>
    <div class="cta-card">
      <div class="icon-container value-connect-icon"><i class="bi bi-people"></i></div>
      <h4>Connect</h4>
      <p>Building a vibrant, supportive community where food lovers can connect and inspire one another.</p>
    </div>
  </div>
</section>
<section class="team">
  <h2>Meet the Team</h2>
  <div class="team-container">
    <?php
    $stmt = $con->prepare("SELECT name, role, description, imagePath from admin where 1");
    if ($stmt->execute()) {
      $result = $stmt->get_result();
      while ($row = $result->fetch_assoc()) {
    ?>
        <div class="team-member">
          <div class="member-image-container">
            <img src="<?php echo $row['imagePath'] ?>" alt="<?php echo $row['name'] ?>">
          </div>
          <h3><?php echo $row['name'] ?></h3>
          <p><?php echo $row['role'] ?></p>
          <p><?php echo $row['description'] ?></p>
        </div>
    <?php
      }
    }
    ?>
  </div>
</section>
<section>
  <div class="about-cta">
    <div>
      <h2>Join Our Community</h2>
      <p>Ready to start your culinary adventure? Sign up today to share your own recipes, discover new favourites,
        and
        connect with fellow food lovers.</p>
    </div>
    <div>
      <button class="cta-button">Share Your Recipe</button>
    </div>
  </div>
</section>
<?php
require 'includes/footer.php';
?>