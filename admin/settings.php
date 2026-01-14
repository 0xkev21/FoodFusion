<?php
require 'adminHeader.php';
require 'sidebar.php';
include 'checkLogin.php';

$error = "";
$success = "";

if (isset($_POST['change_password'])) {
  $userId = $_SESSION['admin_id'];
  $currentPassword = $_POST['current_password'];
  $newPassword = $_POST['new_password'];
  $confirmPassword = $_POST['confirm_new_password'];

  // Regex for: 8 chars, 1 special, 1 number, 1 uppercase, 1 lowercase
  $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';

  if ($newPassword !== $confirmPassword) {
    $error = "New passwords do not match.";
  } elseif (!preg_match($passwordPattern, $newPassword)) {
    $error = "Password must be at least 8 characters long and include uppercase, lowercase, a number, and a special character.";
  } else {
    // Verify current password from users table
    $stmt = $con->prepare("SELECT password FROM admin WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (password_verify($currentPassword, $user['password'])) {
      // Update password column
      $newHashed = password_hash($newPassword, PASSWORD_DEFAULT);
      $update = $con->prepare("UPDATE admin SET password = ? WHERE id = ?");
      $update->bind_param("si", $newHashed, $userId);

      if ($update->execute()) {
        $success = "Password updated successfully!";
      } else {
        $error = "Database error. Please try again.";
      }
    } else {
      $error = "The current password you entered is incorrect.";
    }
  }
}
?>

<div class="settings-container">
  <form action="" method="POST" class="settings-form">
    <div>
      <h2>Security Settings</h2>
      <p>Update your password to keep your account secure.</p>

      <?php if ($error): ?>
        <p class="auth-error-msg" style="color: #e74c3c; font-weight: bold; margin-bottom: 15px;">
          <i class="bi bi-exclamation-circle"></i> <?php echo $error; ?>
        </p>
      <?php endif; ?>
      <?php if ($success): ?>
        <p class="auth-msg success-msg">
          <i class="bi bi-check-circle"></i> <?php echo $success; ?>
        </p>
      <?php endif; ?>
    </div>

    <div class="input-group">
      <label for="current_password">Current Password</label>
      <input type="password" name="current_password" id="current_password" required>
    </div>

    <div class="input-group">
      <label for="new_password">New Password</label>
      <input type="password" name="new_password" id="new_password" required>
    </div>

    <div class="input-group">
      <label for="confirm_new_password">Confirm New Password</label>
      <input type="password" name="confirm_new_password" id="confirm_new_password" required>
    </div>

    <div>
      <button type="submit" name="change_password" class="submit-btn primary">
        Update Password
      </button>
    </div>
  </form>
</div>

</main>
</body>

</html>