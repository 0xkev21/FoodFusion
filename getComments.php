<?php
include 'db/connect.php';

if (isset($_GET['post_id'])) {
  $post_id = intval($_GET['post_id']);

  $sql = "SELECT content, date, firstName, lastName 
            FROM comments
            join users ON users.id = userId 
            join communityPosts on communityPosts.id = postId
            where communityPosts.id = 1
            ORDER BY date DESC;";

  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    while ($comment = mysqli_fetch_assoc($result)) {
      // Output simple HTML for each comment
      echo '<div class="comment-item" style="margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom:5px;">';
      echo '<strong>' . htmlspecialchars($comment['firstName'] . ' ' . $comment['lastName']) . '</strong> ';
      echo '<span style="color:#777; font-size:0.8em;">' . date("M j", strtotime($comment['created_at'])) . '</span>';
      echo '<p style="margin: 5px 0 0 0;">' . nl2br(htmlspecialchars($comment['content'])) . '</p>';
      echo '</div>';
    }
  } else {
    echo '<p style="text-align:center; color:#888;">No comments yet. Be the first!</p>';
  }
}
