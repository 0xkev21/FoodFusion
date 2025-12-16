<?php
$pageTitle = 'Community';
require 'includes/header.php';
?>
<section class="community-hero-section">
  <h2>Community Cookbook</h2>
  <p>Share, discover, connect. Your kitchen stories start here.</p>
</section>
<section class="community">
  <div class="cookbooks-container">
    <?php
    include 'db/connect.php';
    $stmt = $con->prepare("select communityposts.id, firstName, lastName, uploadAt, content, imagePath, count(likes.id) as likeCount, count(comments.id) as commentCount from communityposts
                          join users on users.id = communityposts.userId
                          left join likes on communityPosts.id = likes.postId
                          left join comments on communityPosts.id = comments.postId
                          group by communityposts.id order by uploadAt desc;");
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
        <h4>Have a recipe to share ?</h4>
        <p>Contribute to growing collection of culinary creations and inspire fellow food lovers.</p>
        <button class="primary" id="post">Share Now</button>
      </div>
    </div>
  </div>
  <div class="community-contributers">
    <h3>Top Contributers</h3>
    <div class="contributers-container">
      <div class="community-profile">
        <div class="community-profile-image">
          <img src="images/static/profile.png" alt="">
        </div>
        <div>
          <h4>George Lobko</h4>
        </div>
      </div>
      <div class="community-profile">
        <div class="community-profile-image">
          <img src="images/static/profile.png" alt="">
        </div>
        <div>
          <h4>George Lobko</h4>
        </div>
      </div>
      <div class="community-profile">
        <div class="community-profile-image">
          <img src="images/static/profile.png" alt="">
        </div>
        <div>
          <h4>George Lobko</h4>
        </div>
      </div>
      <div class="community-profile">
        <div class="community-profile-image">
          <img src="images/static/profile.png" alt="">
        </div>
        <div>
          <h4>George Lobko</h4>
        </div>
      </div>
    </div>
  </div>
</section>
<?php

if (isset($_SESSION['user_id'])) {
  require 'includes/postPopup.php';
  require 'includes/commentsPopup.php';
}
require 'includes/footer.php';
?>