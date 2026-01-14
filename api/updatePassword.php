<?php
require 'adminHeader.php';
require 'sidebar.php';
include 'checkLogin.php';

$error = "";
$success = "";

if (isset($_POST['change_password'])) {
    $userId = $_SESSION['user_id'];
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_new_password'];

    // 1. Validation: New passwords must match
    if ($newPassword !== $confirmPassword) {
        $error = "New passwords do not match.";
    } else {
        // 2. Fetch current hashed password from users table
        $stmt = $con->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // 3. Verify the current password
        if (password_verify($currentPassword, $user['password'])) {
            // 4. Hash the new password and update
            $newHashed = password_hash($newPassword, PASSWORD_DEFAULT);
            $update = $con->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->bind_param("si", $newHashed, $userId);

            if ($update->execute()) {
                // Redirect with status to trigger your JS Toast notification
                echo "<script>window.location.href='settings.php?status=success_password';</script>";
                exit();
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