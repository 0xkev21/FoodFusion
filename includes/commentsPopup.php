<div class="comments-popup-wrapper">
  <div class="comments-popup">
    <div class="comments-header">
      <h3>Comments</h3>
      <button onclick="closeModal()" class="close-btn"><i class="bi bi-x-lg"></i></button>
    </div>

    <div id="comments" class="comments">
      <p class="loading-text">Loading...</p>
    </div>

    <div class="comments-footer">
      <form id="commentForm">
        <input type="hidden" id="modalPostId" name="post_id">
        <input type="text" id="commentInput" placeholder="Write a comment..." required>
        <button type="submit"><i class="bi bi-send-fill"></i></button>
      </form>
    </div>
  </div>
</div>