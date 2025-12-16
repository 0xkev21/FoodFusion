<div class="event-card">
  <div class="image-container">
    <img src="<?php echo './' . $row['imagePath'] ?>" alt="">
  </div>
  <div class="event-content">
    <span><i class="bi bi-calendar-event"></i><?php echo $row['eventDate'] ?></span>
    <h4><?php echo $row['title'] ?></h4>
    <p><?php echo $row['description'] ?></p>
    <a href="event.php?id=<?php echo $row['id'] ?>">Learn more<i class="bi bi-arrow-right"></i></a>
  </div>
</div>