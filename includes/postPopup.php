<?php
$userid = $_SESSION['user_id'];
?>

<div class="post-popup-wrapper post-popup-close">
  <div class="post-popup">
    <button class="post-popup-close">
      <i class="bi bi-x-lg"></i>
    </button>
    <form action="submitPost.php" method="POST" enctype="multipart/form-data">
      <h3>Share a Recipe</h3>
      <div>
        <textarea name="post_content" id="" title="Post Content" placeholder="Write your recipe or tip here..." required></textarea>
      </div>
      <div>
        <input id="fileUpload" type="file" name="post_image" accept="image/*">
      </div>
      <div>
        <button class="primary" type="submit" name="submit_post">Post to Community</button>
      </div>
    </form>
  </div>
</div>