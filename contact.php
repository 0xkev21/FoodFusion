<?php
$pageTitle = 'Contact';
require 'includes/header.php';
?>
<section class="contact-hero-section">
  <p>Contact Us</p>
  <h2>Get in Touch</h2>
  <p>We'd love to hear from you! Whether you have a question, a recipe idea, or just want to say hello, drop us a line.</p>
</section>
<section class="contact-container">
  <div class="contact-left">
    <form action="">
      <div>
        <label for="">Name</label>
        <input type="text" placeholder="Enter your name">
      </div>
      <div>
        <label for="">Email Address</label>
        <input type="email" placeholder="Enter your email address">
      </div>
      <div>
        <label for="">Select Subject</label>
        <select name="" id="">
          <option value="">Request Recipe</option>
        </select>
      </div>
      <div>
        <label for="">Send Message</label>
        <textarea name="" id="" placeholder="Type your message here"></textarea>
      </div>
      <div>
        <button class="primary">Send Message</button>
      </div>
    </form>
  </div>
  <div class="contact-right">
    <div class="contact-info">
      <h3>Contact Information</h3>
      <p>Reach us out through the following channels</p>
      <div><i class="bi bi-envelope"></i>support@foodfusion.io</div>
      <div><i class="bi bi-globe"></i>
        <a href="">Instagram</a>
        <a href="">Facebook</a>
      </div>
    </div>
  </div>
</section>
<?php
require 'includes/footer.php';
?>