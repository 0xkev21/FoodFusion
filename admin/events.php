<?php

require 'adminHeader.php';
require 'sidebar.php';
require 'checkLogin.php';

$sql = "SELECT e.*, COUNT(r.id) as attendeeCount 
        FROM events e 
        LEFT JOIN eventregistrations r ON e.id = r.eventId 
        GROUP BY e.id 
        ORDER BY e.eventDate DESC";
$result = $con->query($sql);

?>

<section class="dashboard">
  <div class="header-flex">
    <h2>Manage Events</h2>
    <a href="createEvent.php" class="btn-primary-sm">+ Create Event</a>
  </div>

  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>Image</th>
          <th>Event Title</th>
          <th>Date & Time</th>
          <th>Location</th>
          <th>Attendees</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td data-label="Image">
                <div class="table-img">
                  <img src="<?php echo !empty($row['imagePath']) ? '../' . $row['imagePath'] : '../images/default.jpg'; ?>" alt="Event">
                </div>
              </td>
              <td data-label="Event Title"><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
              <td data-label="Date & Time">
                <div><?php echo date('M d, Y', strtotime($row['eventDate'])); ?></div>
                <small class="suffix"><?php echo date('h:i A', strtotime($row['startTime'])); ?></small>
              </td>
              <td data-label="Location"><?php echo htmlspecialchars($row['location']); ?></td>
              <td data-label="Attendees">
                <span class="badge" style="background:#e3f2fd; color:#0d47a1;">
                  <?php echo $row['attendeeCount']; ?> Registered
                </span>
              </td>
              <td data-label="Actions">
                <div class="action-btns">
                  <button type="button" class="btn-icon view" onclick="openModal('modal-<?php echo $row['id']; ?>')">
                    <i class="bi bi-people-fill"></i>
                  </button>

                  <div id="modal-<?php echo $row['id']; ?>" class="custom-modal">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h3>Participants: <?php echo htmlspecialchars($row['title']); ?></h3>
                        <span class="close-btn" onclick="closeModal('modal-<?php echo $row['id']; ?>')">&times;</span>
                      </div>
                      <div class="modal-body">
                        <?php
                        $evtId = $row['id'];
                        $partSql = "SELECT * FROM eventregistrations WHERE eventId = $evtId ORDER BY registeredAt DESC";
                        $partResult = $con->query($partSql);
                        ?>
                        <?php if ($partResult && $partResult->num_rows > 0): ?>
                          <div class="modal-table-wrapper">
                            <table class="modal-table">
                              <thead>
                                <tr>
                                  <th>Name</th>
                                  <th>Email</th>
                                  <th>Status</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php while ($p = $partResult->fetch_assoc()): ?>
                                  <tr>
                                    <td><?php echo htmlspecialchars($p['name']); ?></td>
                                    <td><?php echo htmlspecialchars($p['email']); ?></td>
                                    <td><?php echo $p['userId'] ? 'Member' : 'Guest'; ?></td>
                                  </tr>
                                <?php endwhile; ?>
                              </tbody>
                            </table>
                          </div>
                        <?php else: ?>
                          <p style="text-align:center; padding:20px; color:var(--gray);">No registrations yet.</p>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>
</main>


</body>

</html>