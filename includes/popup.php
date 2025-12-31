<?php
if (isset($_SESSION['user_id'])) {
  return;
}
?>
<div class="form-wrapper form-close">
  <form action="/foodfusion/auth.php" method="post" class="register-form active">
    <div>
      <h2>Join Our Community</h2>
      <p>Create an account to share youemailr favourite recipes.</p>
    </div>
    <div class="name-inputs">
      <div>
        <label for="first_name">First Name</label>
        <input type="text" name="first_name" id="first_name" autocomplete="given-name">
      </div>
      <div>
        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" id="last_name" autocomplete="family-name">
      </div>
    </div>
    <div>
      <label for="email">Email Address</label>
      <input type="text" name="email" id="email" autocomplete="email">
    </div>
    <div>
      <label for="password">Password</label>
      <input type="password" name="password" id="password" autocomplete="new-password">
    </div>
    <div>
      <label for="confirm_password">Confirm Password</label>
      <input type="password" name="confirm_password" id="confirm_password" autocomplete="new-password">
    </div>
    <div>
      <button class="submit-btn primary" name="register_btn" value="register" type="submit">Register</button>
    </div>
    <div>
      <p>Already a member? <button type="button" class="to-login-btn">Login</button></p>
    </div>
    <button class="close-btn form-close" type="button"><i class="bi bi-x"></i></button>
    <input type="hidden" name="redirect_to" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
  </form>
  <form action="/foodfusion/auth.php" method="post" class="login-form">
    <div>
      <h2>Welcome back!</h2>
      <p>Please login.</p>
    </div>
    <div class="name-inputs">
    </div>
    <div>
      <label for="email-l">Email Address</label>
      <input type="text" name="email" id="email-l" autocomplete="email">
    </div>
    <div>
      <label for="password-l">Password</label>
      <input type="password" name="password" id="password-l" autocomplete="current-password">
    </div>
    <div>
      <button name="login_btn" value="login" class="submit-btn primary" type="submit">Login</button>
    </div>
    <div>
      <p>Don't have an account yet? <button type="button" class="to-register-btn">Register</button></p>
    </div>
    <button class="close-btn form-close" type="button"><i class="bi bi-x"></i></button>
    <input type="hidden" name="redirect_to" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
  </form>
</div>