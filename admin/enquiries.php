<?php
require 'adminHeader.php';
require 'sidebar.php';
include 'checkLogin.php';

if (isset($_GET['delete_id'])) {
  $delId = intval($_GET['delete_id']);

  // Prepare statement to prevent SQL injection
  $stmt = $con->prepare("DELETE FROM enquiries WHERE id = ?");
  $stmt->bind_param("i", $delId);

  if ($stmt->execute()) {
    header("Location: enquiries.php?msg=Enquiry deleted successfully");
    exit();
  } else {
    $error = "Error deleting record.";
  }
}

// 3. Fetch Enquiries
// We JOIN with enquiryTypes to get the name (e.g., "General") instead of just ID
// Note: Using 'sentAt' based on your screenshot
$sql = "SELECT e.*, t.enquiryType as typeName 
        FROM enquiries e
        LEFT JOIN enquiryTypes t ON e.enquiryTypeId = t.id
        ORDER BY e.sentAt DESC";

$result = $con->query($sql);

?>

<section class="dashboard">

  <div class="header-flex">
    <h2>Customer Enquiries</h2>
  </div>

  <?php if (isset($_GET['msg'])): ?>
    <div class="alert-success">
      <?php echo htmlspecialchars($_GET['msg']); ?>
    </div>
  <?php endif; ?>

  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Sender</th>
          <th>Subject (Type)</th>
          <th>Message</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>

              <td data-label="Date">
                <div style="font-weight:600; color:var(--subtle-dark); white-space:nowrap;">
                  <?php echo date('M d, Y', strtotime($row['sentAt'])); ?>
                </div>
                <small class="suffix">
                  <?php echo date('h:i A', strtotime($row['sentAt'])); ?>
                </small>
              </td>

              <td data-label="Sender">
                <strong><?php echo htmlspecialchars($row['senderName']); ?></strong><br>
                <a href="mailto:<?php echo htmlspecialchars($row['senderEmail']); ?>" style="color:var(--primary); font-size:0.85rem;">
                  <?php echo htmlspecialchars($row['senderEmail']); ?>
                </a>
              </td>

              <td data-label="Subject">
                <span class="badge" style="background:#e3f2fd; color:#0d47a1;">
                  <?php echo htmlspecialchars($row['typeName'] ?? 'General'); ?>
                </span>
              </td>

              <td data-label="Message">
                <div style="color:#555; font-size:0.9rem;">
                  <?php
                  $fullMsg = htmlspecialchars($row['enquiry']);
                  // Truncate to 50 chars for the table view
                  echo strlen($fullMsg) > 50 ? substr($fullMsg, 0, 50) . '...' : $fullMsg;
                  ?>
                </div>
              </td>

              <td data-label="Actions">
                <div class="action-btns">
                  <button type="button" class="btn-icon view" onclick="openModal('modal-<?php echo $row['id']; ?>')" title="Read Full Message">
                    <i class="bi bi-eye"></i>
                  </button>

                  <a href="enquiries.php?delete_id=<?php echo $row['id']; ?>" class="btn-icon delete"
                    onclick="return confirm('Are you sure you want to delete this message?');" title="Delete">
                    <i class="bi bi-trash"></i>
                  </a>
                </div>
              </td>
            </tr>

            <div id="modal-<?php echo $row['id']; ?>" class="custom-modal">
              <div class="modal-content">

                <div class="modal-header">
                  <h3>Message from <?php echo htmlspecialchars($row['senderName']); ?></h3>
                  <span class="close-btn" onclick="closeModal('modal-<?php echo $row['id']; ?>')">&times;</span>
                </div>

                <div class="modal-body" style="padding: 20px; font-size: 1rem; line-height: 1.6; color: #333;">

                  <div style="margin-bottom: 15px; color: #666; font-size: 0.9rem;">
                    <strong>Category:</strong> <?php echo htmlspecialchars($row['typeName'] ?? 'General'); ?> <br>
                    <strong>Sent:</strong> <?php echo date('F d, Y \a\t h:i A', strtotime($row['sentAt'])); ?>
                  </div>

                  <hr style="border:0; border-top:1px solid #eee; margin:15px 0;">

                  <p style="white-space: pre-wrap;"><?php echo htmlspecialchars($row['enquiry']); ?></p>

                  <div style="margin-top:30px; text-align:right;">
                    <a href="mailto:<?php echo htmlspecialchars($row['senderEmail']); ?>" class="btn-primary-sm" style="display:inline-flex; align-items:center; gap:8px; text-decoration:none;">
                      <i class="bi bi-reply-fill"></i> Reply via Email
                    </a>
                  </div>
                </div>

              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" style="text-align:center; padding:30px; color:#888;">
              <i class="bi bi-inbox" style="font-size: 2rem; display:block; margin-bottom:10px;"></i>
              No new enquiries found.
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</section>
</main>


</body>

</html>