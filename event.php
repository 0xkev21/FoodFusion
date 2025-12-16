<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo "<script>window.location.assign('index.php');</script>";
}

$id = intval($_GET['id']);
include 'db/connect.php';

$sqlEvent = "select title, eventDetails, imagePath, eventDate, startTime, endTime, location from events where id = ?;";
$stmtEvent = $con->prepare($sqlEvent);
$stmtEvent->bind_param("i", $id);
$Event = [];
if ($stmtEvent->execute()) {
  $result = $stmtEvent->get_result();
  $event = $result->fetch_assoc();
}
$pageTitle = $event['title'];
require 'includes/header.php';
?>
<section>
  <div class="event-image-container">
    <div class="event-image-texts">
      <h2><?php echo $event['title'] ?></h2>
      <!-- <p>12 People Registered</p> -->
    </div>
    <div class="image-container">
      <img src="<?php echo './'.$event['imagePath'] ?>" alt="">
    </div>
  </div>
  <div class="event-details">
    <div class="event-about">
      <h3>About the Event</h3>
      <p><?php echo $event['eventDetails'] ?></p>
      <div class="event-about-details">
        <div class="event-about-date"><i class="bi bi-calendar-event"></i><?php echo $event['eventDate'] ?></div>
        <div class="event-about-time">
          <i class="bi bi-alarm"></i>
          <?php echo date("h:i A", strtotime($event['startTime'])).' - '.date("h:i A", strtotime($event['endTime'])) ?>
        </div>
        <div class="event-about-location"><i class="bi bi-geo-alt"></i><?php echo $event['location'] ?></div>
      </div>
    </div>
    <div class="event-form">
      <h3>Register to the Event</h3>
      <form action="">
        <div class="input-container">
          <label for="name-event">Name</label>
          <input type="text" id="name-event" name="name" value="">
        </div>
        <div>
          <label for="email-event">Email</label>
          <input type="text" id="email-event" name="email" value="">
        </div>
        <div>
          <button class="primary">Reserve Spot</button>
        </div>
      </form>
    </div>
  </div>
</section>
<?php
require 'includes/footer.php';
?>