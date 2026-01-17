<?php
$msg = "";
$error = "";

$pageTitle = 'Contact';
require 'includes/header.php';

// Handle Form Submission
if (isset($_POST['send_message_btn'])) {
  $name = trim($_POST['sender_name']);
  $email = trim($_POST['sender_email']);
  $typeId = intval($_POST['enquiry_type']);
  $message = trim($_POST['enquiry_message']);

  if (empty($name) || empty($email) || empty($message)) {
    $error = "Please fill in all required fields.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Please enter a valid email address.";
  } else {
    // Insert into enquiries table
    $sql = "INSERT INTO enquiries (senderName, senderEmail, enquiry, enquiryTypeId) VALUES (?, ?, ?, ?)";

    if ($stmt = $con->prepare($sql)) {
      $stmt->bind_param("sssi", $name, $email, $message, $typeId);

      if ($stmt->execute()) {
        // Redirect to clear POST data and trigger success state
        header("Location: contact.php?status=sent");
        exit();
      } else {
        $error = "Something went wrong. Please try again.";
      }
      $stmt->close();
    }
  }
}

// Check for status from URL
if (isset($_GET['status']) && $_GET['status'] == 'sent') {
  $msg = "Message sent successfully! We will get back to you soon.";
}

// Fetch Dynamic Social Links for the right sidebar
$socials = $con->query("SELECT platform, url FROM social_links");
?>

<section class="contact-hero-section">
  <p>Contact Us</p>
  <h2>Get in Touch</h2>
  <p>We'd love to hear from you! Whether you have a question, a recipe idea, or just want to say hello, drop us a line.</p>
</section>

<section class="contact-container">
  <div class="contact-left">
    <?php if ($error): ?>
      <div class="auth-msg error-msg">
        <i class="bi bi-exclamation-circle"></i> <?php echo $error; ?>
      </div>
    <?php endif; ?>

    <?php if ($msg): ?>
      <div class="auth-msg success-msg">
        <i class="bi bi-check-circle"></i> <?php echo $msg; ?>
      </div>
    <?php endif; ?>

    <form action="" method="POST">
      <div>
        <label for="name">Name</label>
        <input type="text" id="name" name="sender_name" placeholder="Enter your name" required>
      </div>
      <div>
        <label for="email-c">Email Address</label>
        <input type="email" id="email-c" name="sender_email" placeholder="Enter your email address" required>
      </div>
      <div>
        <label for="subject">Select Subject</label>
        <select name="enquiry_type" id="subject">
          <?php
          $stmtTypes = $con->query("SELECT id, enquiryType FROM enquirytypes");
          while ($row = $stmtTypes->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['enquiryType']}</option>";
          }
          ?>
        </select>
      </div>
      <div>
        <label for="message">Send Message</label>
        <textarea name="enquiry_message" id="message" rows="5" placeholder="Type your message here" required></textarea>
      </div>
      <div>
        <button type="submit" name="send_message_btn" class="primary">Send Message</button>
      </div>
    </form>
  </div>

  <div class="contact-right">
    <div class="contact-info">
      <h3>Contact Information</h3>
      <p>Reach us out through the following channels</p>
      <div class="info-item"><i class="bi bi-envelope"></i> support@foodfusion.io</div>
      <div class="info-item social-icons">
        <i class="bi bi-globe"></i>
        <?php while($s = $socials->fetch_assoc()): ?>
          <a href="<?php echo htmlspecialchars($s['url']); ?>" target="_blank">
            <?php echo ucfirst($s['platform']); ?>
          </a>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</section>

<?php require 'includes/footer.php'; ?>