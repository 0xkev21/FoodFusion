<?php
session_start();
include 'db/connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo "<script>window.location.assign('index.php');</script>";
  exit();
}

$id = intval($_GET['id']);
$msg = "";
$error = "";

if (isset($_POST['reserve_spot_btn'])) {
  $r_name = trim($_POST['e-name']);
  $r_email = trim($_POST['e-email']);
  $r_event_id = intval($_POST['event_id']);

  $r_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;

  if (empty($r_name) || empty($r_email)) {
    $error = "Please fill in all fields.";
  } else {
    $check = $con->prepare("SELECT id FROM eventregistrations WHERE eventId = ? AND email = ?");
    $check->bind_param("is", $r_event_id, $r_email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
      $error = "You have already registered for this event with this email.";
    } else {
      $insert = $con->prepare("INSERT INTO eventregistrations (eventId, userId, name, email) VALUES (?, ?, ?, ?)");
      $insert->bind_param("iiss", $r_event_id, $r_user_id, $r_name, $r_email);

      if ($insert->execute()) {
        $msg = "You have successfully registered! We will email you the details.";
      } else {
        $error = "Something went wrong. Please try again.";
      }
    }
  }
}

$sqlEvent = "SELECT title, eventDetails, imagePath, eventDate, startTime, endTime, location FROM events WHERE id = ?";
$stmtEvent = $con->prepare($sqlEvent);
$stmtEvent->bind_param("i", $id);

if ($stmtEvent->execute()) {
  $result = $stmtEvent->get_result();
  $event = $result->fetch_assoc();

  if (!$event) {
    echo "<script>window.location.assign('index.php');</script>";
    exit();
  }
}

$pageTitle = $event['title'];
require 'includes/header.php';
?>

<section>
  <div class="event-image-container">
    <div class="event-image-texts">
      <h2><?php echo htmlspecialchars($event['title']); ?></h2>
      <p></p>
    </div>
    <div class="image-container">
      <img src="<?php echo './' . htmlspecialchars($event['imagePath']); ?>" alt="Event Image">
    </div>
  </div>

  <div class="event-details">
    <div class="event-about">
      <h3>About the Event</h3>
      <p><?php echo nl2br(htmlspecialchars($event['eventDetails'])); ?></p>

      <div class="event-about-details">
        <div class="event-about-date">
          <i class="bi bi-calendar-event"></i>
          <?php echo date("F j, Y", strtotime($event['eventDate'])); ?>
        </div>
        <div class="event-about-time">
          <i class="bi bi-alarm"></i>
          <?php echo date("g:i A", strtotime($event['startTime'])) . ' - ' . date("g:i A", strtotime($event['endTime'])); ?>
        </div>
        <div class="event-about-location">
          <i class="bi bi-geo-alt"></i>
          <?php echo htmlspecialchars($event['location']); ?>
        </div>
      </div>
    </div>

    <div class="event-form">
      <h3>Register to the Event</h3>

      <?php if ($error): ?>
        <div style="background:#ffe6e6; color:#d63031; padding:10px; border-radius:5px; margin-bottom:10px;">
          <?php echo $error; ?>
        </div>
      <?php endif; ?>

      <?php if ($msg): ?>
        <div style="background:#e6fff0; color:#00b894; padding:10px; border-radius:5px; margin-bottom:10px;">
          <?php echo $msg; ?>
        </div>
      <?php endif; ?>

      <form action="" method="post">
        <div class="input-container">
          <label for="name-event">Name</label>
          <input type="text" id="name-event" name="e-name" required
            value="<?php echo isset($_SESSION['user_id']) ? htmlspecialchars($_SESSION['first_name']) . ' ' . htmlspecialchars($_SESSION['last_name']) : ''; ?>">
        </div>

        <div>
          <label for="email-event">Email</label>
          <input type="email" id="email-event" name="e-email" required
            value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>">
        </div>

        <input type="hidden" name="event_id" value="<?php echo $id; ?>">

        <div>
          <button type="submit" name="reserve_spot_btn" class="primary">Reserve Spot</button>
        </div>
      </form>
    </div>
  </div>
</section>

<?php require 'includes/footer.php'; ?>