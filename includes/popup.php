<?php
if (isset($_SESSION['user_id'])) {
  return;
}

// Get the parameters from the URL
$errorMsg = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : "";
$openForm = isset($_GET['open']) ? $_GET['open'] : "register";
?>

<div class="form-wrapper <?php echo empty($errorMsg) ? 'form-close' : ''; ?>">

  <form action="auth.php" method="post" class="register-form <?php echo ($openForm === 'register') ? 'active' : ''; ?>">
    <div>
      <h2>Join Our Community</h2>
      <?php if ($openForm === 'register' && $errorMsg): ?>
        <p class="auth-error-msg" style="color: #e74c3c; font-weight: bold;"><?php echo $errorMsg; ?></p>
      <?php else: ?>
        <p>Create an account to share your favourite recipes.</p>
      <?php endif; ?>
    </div>

    <div class="name-inputs">
      <div>
        <label for="first_name">First Name</label>
        <input type="text" name="first_name" id="first_name" required>
      </div>
      <div>
        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" id="last_name" required>
      </div>
    </div>
    <div>
      <label for="email">Email Address</label>
      <input type="text" name="email" id="email" required>
    </div>
    <div>
      <label for="password">Password</label>
      <input type="password" name="password" id="password" required>
    </div>
    <div>
      <label for="confirm_password">Confirm Password</label>
      <input type="password" name="confirm_password" id="confirm_password" required>
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

  <form action="auth.php" method="post" class="login-form <?php echo ($openForm === 'login') ? 'active' : ''; ?>">
    <div>
      <h2>Welcome back!</h2>
      <?php if ($openForm === 'login' && $errorMsg): ?>
        <p class="auth-error-msg" style="color: #e74c3c; font-weight: bold;"><?php echo $errorMsg; ?></p>
      <?php else: ?>
        <p>Please login.</p>
      <?php endif; ?>
    </div>

    <div>
      <label for="email-l">Email Address</label>
      <input type="text" name="email" id="email-l" required>
    </div>
    <div>
      <label for="password-l">Password</label>
      <input type="password" name="password" id="password-l" required>
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