</main>
<footer>
  <div>
    <div class="logo-container">
      <div class="logo">
        <svg viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M21.3,3.23a1,1,0,0,0-1.05-.22l-6.23,2.27-5-2.14a1,1,0,0,0-1,0L2,5.76V16.2a1,1,0,0,0,.5.87l6,3.85a1,1,0,0,0,1,0l6-3.85a1,1,0,0,0,.5-.87V8.52l5.28-1.92A1,1,0,0,0,22,5.58V4.28A1,1,0,0,0,21.3,3.23ZM8,17.13,4,14.58V7.58l3.92,1.68Zm2,0V9.26l4-1.45v7.92Zm8.28-12.2-4.23,1.54L10,8.15V5.58L14,4l4.23,1.54Z">
          </path>
        </svg>
      </div>
      <h1>FoodFusion</h1>
    </div>
    <p>Sharing the joy of home cooking.</p>
  </div>
  <div>
    <h4>Quick Links</h4>
    <div>
      <a href="">About Us</a>
      <a href="">Contact</a>
      <a href="">Community</a>
    </div>
  </div>
  <div>
    <h4>Legal</h4>
    <div>
      <a href="">Privacy Policy</a>
      <a href="">Terms of Service</a>
      <a href="">Cookie Policy</a>
    </div>
  </div>
  <div>
    <h4>Follow Us</h4>
    <div class="social-links">
      <a href=""><i class="bi bi-youtube"></i></a>
      <a href=""><i class="bi bi-facebook"></i></a>
      <a href=""><i class="bi bi-instagram"></i></a>
    </div>
  </div>
  <?php if (isset($_COOKIE['foodfusion_cookies'])): ?>
    <div id="cookie-banner" class="cookie-container">
      <div class="cookie-content">
        <i class="bi bi-cookie"></i>
        <p>We use cookies to improve your experience on FoodFusion. By clicking "Accept", you agree to our storage of cookies.</p>
        <button id="rejectCookiesBtn">Reject</button>
        <button class="primary" id="acceptCookiesBtn">Accept</button>
      </div>
    </div>
  <?php endif; ?>
</footer>
<span class="toast" id="toast">
  Hello World
</span>
<?php
require 'includes/popup.php';
?>
</body>

</html>