<?php
require 'adminHeader.php';
require 'sidebar.php';
include 'checkLogin.php';
?>

<section class="dashboard">

  <div class="header-flex">
    <h2>Customer Enquiries</h2>
  </div>

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
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td class="text-muted" style="white-space: nowrap;">
                <?php
                if (isset($row['createdAt'])) {
                  echo date('M d, Y', strtotime($row['createdAt']));
                  echo '<br><small>' . date('h:i A', strtotime($row['createdAt'])) . '</small>';
                } else {
                  echo "-";
                }
                ?>
              </td>

              <td>
                <strong>
                  <?php echo htmlspecialchars($row['senderName']); ?>
                </strong><br>
                <a href="mailto:<?php echo htmlspecialchars($row['senderEmail']); ?>" class="text-link">
                  <?php echo htmlspecialchars($row['senderEmail']); ?>
                </a>
              </td>

              <td>
                <span class="badge badge-info">
                  <?php echo htmlspecialchars($row['typeName'] ?? 'General'); ?>
                </span>
              </td>

              <td>
                <div class="msg-preview" title="<?php echo htmlspecialchars($row['enquiry']); ?>">
                  <?php
                  $msg = htmlspecialchars($row['enquiry']);
                  // Show only first 60 characters
                  echo strlen($msg) > 60 ? substr($msg, 0, 60) . '...' : $msg;
                  ?>
                </div>
              </td>

              <td>
                <div class="action-btns">
                  <button class="btn-icon"
                    onclick="alert('<?php echo htmlspecialchars(addslashes($row['enquiry'])); ?>')"
                    title="Read Full Message">
                    <i class="bi bi-eye"></i>
                  </button>

                  <a href="delete_enquiry.php?id=<?php echo $row['id']; ?>" class="btn-icon delete"
                    onclick="return confirm('Are you sure you want to delete this message?');" title="Delete">
                    <i class="bi bi-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" style="text-align:center; padding:20px;">No new enquiries found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</section>
</main>
</body>

</html>