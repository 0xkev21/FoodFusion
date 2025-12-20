<?php
require 'adminHeader.php';
require 'sidebar.php';
include 'checkLogin.php';
?>

<section class="dashboard">
  <h2>Create New Event</h2>

  <form action="submit_event_logic.php" method="POST" enctype="multipart/form-data" class="recipe-form">

    <div class="form-group">
      <label>Event Title</label>
      <input type="text" name="title" required placeholder="e.g., Italian Cooking Masterclass 2025">
    </div>

    <div class="recipe-meta-grid">

      <label class="meta-box" for="event-date">
        <div class="meta-icon"><i class="bi bi-calendar-event"></i></div>
        <div class="meta-content">
          <div>Event Date</div>
          <input type="date" id="event-date" name="event_date" required>
        </div>
      </label>

      <label class="meta-box" for="start-time">
        <div class="meta-icon"><i class="bi bi-clock"></i></div>
        <div class="meta-content">
          <div>Start Time</div>
          <input type="time" id="event-time" name="event_time" required>
        </div>
      </label>

      <label class="meta-box" for="end-time">
        <div class="meta-icon"><i class="bi bi-clock"></i></div>
        <div class="meta-content">
          <div>End Time</div>
          <input type="time" step="60" id="end-time" name="end-time" required>
        </div>
      </label>

    </div>

    <div class="form-group">
      <label>Location / Venue</label>
      <div class="input-with-icon">
        <input type="text" name="location" placeholder="e.g. 123 Culinary Ave, New York (or Zoom Link)" required>
      </div>
    </div>

    <div class="form-group">
      <label>Event Details</label>
      <textarea name="description" rows="8" required
        placeholder="Describe what will happen at the event..."></textarea>
    </div>

    <div class="form-group">
      <label>Event Banner Image</label>
      <input type="file" name="event_image" accept="image/*" required>
    </div>

    <button type="submit" name="submit_event" class="btn-submit">Publish Event</button>

  </form>
</section>
</main>
</body>

</html>