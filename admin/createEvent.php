<?php
require 'adminHeader.php';
require 'sidebar.php';
include 'checkLogin.php';

$msg = "";
$error = "";

if (isset($_POST['submit_event'])) {

  $title = trim($_POST['title']);
  $shortDesc = trim($_POST['short_description']);
  $location = trim($_POST['location']);
  $details = trim($_POST['details']);

  $date = $_POST['event_date'];
  $start = $_POST['event_time'];
  $end = $_POST['end-time'];

  $adminId = $_SESSION['admin_id'];

  $imagePath = "";
  if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === 0) {
    $fileName = $_FILES['event_image']['name'];
    $fileTmp  = $_FILES['event_image']['tmp_name'];
    $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($fileExt, $allowed)) {
      $newName = uniqid('event_', true) . "." . $fileExt;
      $uploadDir = '../images/events/';

      if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

      $destPath = $uploadDir . $newName;
      if (move_uploaded_file($fileTmp, $destPath)) {
        $imagePath = 'images/events/' . $newName;
      } else {
        $error = "Error: Failed to move uploaded file.";
      }
    } else {
      $error = "Error: Invalid file type (JPG, PNG, WEBP only).";
    }
  } else {
    $error = "Error: Event image is required.";
  }

  if (empty($error)) {
    $sql = "INSERT INTO events (title, description, eventDate, startTime, endTime, adminId, imagePath, eventDetails, location) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $con->prepare($sql);
    if ($stmt) {
      $stmt->bind_param("sssssisss", $title, $shortDesc, $date, $start, $end, $adminId, $imagePath, $details, $location);

      if ($stmt->execute()) {
        header("Location: events.php?msg=Event created successfully");
        exit();
      } else {
        $error = "Database Error: " . $stmt->error;
      }
    } else {
      $error = "SQL Prepare Error: " . $con->error;
    }
  }
}

?>

<section class="dashboard">
  <h2>Create New Event</h2>

  <?php if ($error): ?>
    <div style="background:#ffe6e6; color:#d63031; padding:15px; border-radius:8px; margin-bottom:20px;">
      <?php echo $error; ?>
    </div>
  <?php endif; ?>

  <form action="" method="POST" enctype="multipart/form-data" class="recipe-form">

    <div class="form-group">
      <label>Event Title</label>
      <input type="text" name="title" required placeholder="e.g., Italian Cooking Masterclass 2025"
        value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
    </div>

    <div class="form-group">
      <label>Short Summary</label>
      <input type="text" name="short_description" required placeholder="e.g. Live masterclass with Chef Somchai on Pad Thai"
        value="<?php echo isset($_POST['short_description']) ? htmlspecialchars($_POST['short_description']) : ''; ?>">
    </div>

    <div class="recipe-meta-grid">

      <label class="meta-box" for="event-date">
        <div class="meta-icon"><i class="bi bi-calendar-event"></i></div>
        <div class="meta-content">
          <div>Event Date</div>
          <input type="date" id="event-date" name="event_date" required
            value="<?php echo isset($_POST['event_date']) ? $_POST['event_date'] : ''; ?>">
        </div>
      </label>

      <label class="meta-box" for="event-time">
        <div class="meta-icon"><i class="bi bi-clock"></i></div>
        <div class="meta-content">
          <div>Start Time</div>
          <input type="time" id="event-time" name="event_time" required
            value="<?php echo isset($_POST['event_time']) ? $_POST['event_time'] : ''; ?>">
        </div>
      </label>

      <label class="meta-box" for="end-time">
        <div class="meta-icon"><i class="bi bi-clock"></i></div>
        <div class="meta-content">
          <div>End Time</div>
          <input type="time" id="end-time" name="end-time" required
            value="<?php echo isset($_POST['end-time']) ? $_POST['end-time'] : ''; ?>">
        </div>
      </label>

    </div>

    <div class="form-group">
      <label>Location / Venue</label>
      <div class="input-with-icon">
        <input type="text" name="location" placeholder="e.g. 123 Culinary Ave, New York (or Zoom Link)" required
          value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>">
      </div>
    </div>

    <div class="form-group">
      <label>Event Details</label>
      <textarea name="details" rows="8" required placeholder="Describe what will happen at the event..."><?php echo isset($_POST['details']) ? htmlspecialchars($_POST['details']) : ''; ?></textarea>
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