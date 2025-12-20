<div id="commentModal" class="modal-overlay">
  <div class="modal-content">
    <div class="modal-header">
      <h3>Comments</h3>
      <button onclick="closeModal()" class="close-btn">&times;</button>
    </div>

    <div id="modalBody" class="modal-body">
      <p class="loading-text">Loading...</p>
    </div>

    <div class="modal-footer">
      <form id="commentForm" method="post" action="./api/submitComment.php">
        <input type="hidden" id="modalPostId" name="post_id">
        <input type="text" id="commentInput" placeholder="Write a comment..." name="comment" required>
        <button type="submit" name="submit_comment"><i class="bi bi-send-fill"></i></button>
      </form>
    </div>
  </div>
</div>