<?php

$msg = "";
$error = "";

$pageTitle = 'Contact';
require 'includes/header.php';

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
    $sql = "INSERT INTO enquiries (senderName, senderEmail, enquiry, enquiryTypeId) VALUES (?, ?, ?, ?)";

    if ($stmt = $con->prepare($sql)) {
      $stmt->bind_param("sssi", $name, $email, $message, $typeId);

      if ($stmt->execute()) {
        header("Location: contact.php?status=sent");
        exit();
      } else {
        $error = "Something went wrong. Please try again.";
      }
      $stmt->close();
    } else {
      $error = "Database error: " . $con->error;
    }
  }
}

if (isset($_GET['status']) && $_GET['status'] == 'sent') {
  $msg = "Message sent successfully! We will get back to you soon.";
}

?>

<section class="contact-hero-section">
  <p>Contact Us</p>
  <h2>Get in Touch</h2>
  <p>We'd love to hear from you! Whether you have a question, a recipe idea, or just want to say hello, drop us a line.</p>
</section>

<section class="contact-container">
  <div class="contact-left">

    <?php if ($error): ?>
      <div style="background:#ffe6e6; color:#d63031; padding:10px; border-radius:5px; margin-bottom:15px;">
        <?php echo $error; ?>
      </div>
    <?php endif; ?>

    <?php if ($msg): ?>
      <div style="background:#e6fff0; color:#00b894; padding:10px; border-radius:5px; margin-bottom:15px;">
        <?php echo $msg; ?>
      </div>
    <?php endif; ?>

    <form action="" method="POST">
      <div>
        <label for="name">Name</label>
        <input type="text" id="name" name="sender_name" placeholder="Enter your name" required>
      </div>
      <div>
        <label for="email-c">Email Address</label>
        <input type="email" id="email-c" name="sender_email" placeholder="Enter your email address" required autocomplete="email">
      </div>
      <div>
        <label for="subject">Select Subject</label>
        <select name="enquiry_type" id="subject">
          <?php
          $stmtTypes = $con->prepare("select id, enquiryType from enquiryTypes");
          if ($stmtTypes->execute()) {
            $result = $stmtTypes->get_result();
            while ($row = $result->fetch_assoc()) {
          ?>
              <option value="<?php echo $row['id'] ?>"><?php echo $row['enquiryType'] ?></option>
          <?php
            }
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
      <div><i class="bi bi-envelope"></i>support@foodfusion.io</div>
      <div>
        <i class="bi bi-globe"></i>
        <a href="#">Instagram</a>
        <a href="#">Facebook</a>
      </div>
    </div>
  </div>
</section>

<?php require 'includes/footer.php'; ?>