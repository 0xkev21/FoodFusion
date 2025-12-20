<div class="cookbook" data-id="<?php echo $row['id'] ?>">
  <div class="cookbook-content">
    <div class="community-profile">
      <div class="community-profile-image">
        <img src="images/static/profile.png" alt="">
      </div>
      <div>
        <h4><?php echo $row['firstName'] . ' ' . $row['lastName'] ?></h4>
        <p><?php echo date("d M Y, g:i A", strtotime($row['uploadAt'])); ?></p>
      </div>
    </div>
    <p class="post-content"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
    <?php if (isset($row['imagePath'])) : ?>
      <div class="post-image-container">
        <img src="<?php echo $row['imagePath'] ?>" alt="<?php echo $row['firstName'] . "'s Post Image" ?>">
      </div>
    <?php endif; ?>
    <div class="post-actions">
      <button class="like-btn" data-id="<?php echo $row['id'] ?>"><i class="bi <?php echo $row['user_liked'] ? "bi-heart-fill" : "bi-heart" ?>"></i><span><?php echo $row['likeCount'] ?></span></button>
      <button class="comment-btn" data-id="<?php echo $row['id'] ?>">
        <i class="bi bi-chat-left"></i> <?php echo $row['commentCount'] ?>
      </button>
    </div>
  </div>
</div>