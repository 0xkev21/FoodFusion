<?php
require 'adminHeader.php';
require 'sidebar.php';
include 'checkLogin.php';
?>

<section class="dashboard">
  <h2>Post New Resource</h2>

  <form action="submit_resource_logic.php" method="POST" enctype="multipart/form-data" class="recipe-form">

    <div class="form-group">
      <label>Resource Title</label>
      <input type="text" name="title" required placeholder="e.g., Weekly Meal Planner Template">
    </div>

    <div class="recipe-meta-grid">

      <label class="meta-box" for="res-category">
        <div class="meta-icon"><i class="bi bi-bookmarks-fill"></i></div>
        <div class="meta-content">
          <div>Category</div>
          <div class="select-wrapper">
            <select id="res-category" name="category" required>
              <option value="" disabled selected>Select...</option>
              <option value="Nutrition">Nutrition Guide</option>
              <option value="Budgeting">Budgeting</option>
              <option value="Safety">Kitchen Safety</option>
              <option value="Techniques">Cooking Techniques</option>
            </select>
            <i class="bi bi-chevron-down dropdown-arrow"></i>
          </div>
        </div>
      </label>

      <label class="meta-box" for="res-type">
        <div class="meta-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
        <div class="meta-content">
          <div>File Type</div>
          <div class="select-wrapper">
            <select id="res-type" name="type" required onchange="toggleInput()">
              <option value="PDF">PDF Document</option>
              <option value="Image">Infographic (Image)</option>
              <option value="Video">Video (YouTube)</option>
            </select>
            <i class="bi bi-chevron-down dropdown-arrow"></i>
          </div>
        </div>
      </label>

    </div>

    <div class="form-group">
      <label>Description</label>
      <textarea name="description" rows="5" required placeholder="Explain what this resource is for..."></textarea>
    </div>

    <div class="form-row">

      <div class="form-col" id="file-input-container">
        <div class="form-group">
          <label>Upload Resource File <small>(PDF, JPG, PNG)</small></label>
          <input type="file" id="resource-file" name="resource_file" accept=".pdf, .jpg, .jpeg, .png, .webp">
        </div>
      </div>

      <div class="form-col" id="video-link-container" style="display: none;">
        <div class="form-group">
          <label>YouTube Video Link</label>
          <input type="url" id="video-link" name="video_link" placeholder="https://www.youtube.com/watch?v=...">
        </div>
      </div>

      <div class="form-col">
        <div class="form-group">
          <label>Cover Image <small>(Thumbnail)</small></label>
          <input type="file" name="cover_image" accept="image/*" required>
        </div>
      </div>

    </div>

    <button type="submit" name="submit_resource" class="btn-submit">Upload Resource</button>

  </form>
</section>
</main>
</body>

</html>