<?php
$pageTitle = 'Community';
require 'includes/header.php';

$isLoggedIn = isset($_SESSION['user_id']);
$currentUserId = $isLoggedIn ? $_SESSION['user_id'] : 0;
?>

<!-- <script>
  const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
</script> -->
<script src="comments.js" defer></script>

<section class="community-hero-section">
  <h2>Community Cookbook</h2>
  <p>Share, discover, connect. Your kitchen stories start here.</p>
</section>

<section class="community">
  <div class="cookbooks-container">
    <?php
    $sql = "SELECT 
            cp.id, u.firstName, u.lastName, 
            cp.uploadAt, 
            cp.content, 
            cp.imagePath, 
            COUNT(DISTINCT l.id) as likeCount, 
            COUNT(DISTINCT c.id) as commentCount,
            (SELECT COUNT(*) FROM likes WHERE postId = cp.id AND userId = ?) as user_liked
            FROM communityposts cp
            JOIN users u ON u.id = cp.userId
            LEFT JOIN likes l ON cp.id = l.postId
            LEFT JOIN comments c ON cp.id = c.postId
            GROUP BY cp.id
            ORDER BY cp.uploadAt DESC";

    $stmt = $con->prepare($sql);

    $stmt->bind_param("i", $currentUserId);

    if ($stmt->execute()) {
      $result = $stmt->get_result();
      if ($result->num_rows == 0) {
        echo "<h3>No Posts yet</h3>";
      } else {
        while ($row = $result->fetch_assoc()) {
          require "includes/postCard.php";
        }
      }
    }
    ?>
  </div>

  <div class="community-sidebar">
    <div class="community-actions">
      <div class="call-to-share">
        <div class="share-icon icon-container"><i class="bi bi-pencil-square"></i></div>
        <h4>Have a recipe to share?</h4>
        <p>Contribute to growing collection of culinary creations and inspire fellow food lovers.</p>
        <button class="primary" id="post">Share Now</button>
      </div>
    </div>
  </div>

  <div class="community-contributers">
    <h3>Top Contributors</h3>
    <div class="contributers-container">
      <?php
      $contributersStmt = $con->prepare("SELECT users.id, firstName, lastName, COUNT(communityposts.id) as total_posts
                                        FROM users
                                        JOIN communityposts ON users.id = userId
                                        GROUP BY users.id
                                        having total_posts > 0
                                        ORDER BY total_posts DESC
                                        LIMIT 5;");
      if ($contributersStmt->execute()) {
        $result = $contributersStmt->get_result();
        while ($row = $result->fetch_assoc()) {
      ?>
          <div class="community-profile">
            <div class="community-profile-image">
              <img src="images/static/profile.png" alt="">
            </div>
            <div>
              <h4><?php echo $row['firstName'] . ' ' . $row['lastName']; ?></h4>
            </div>
          </div>
      <?php
        }
      }
      ?>
    </div>
  </div>
</section>

<?php

if ($isLoggedIn) {
  require 'includes/postPopup.php';
  require 'includes/commentsPopup.php';
}

require 'includes/footer.php';
?>