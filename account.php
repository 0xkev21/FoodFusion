<?php
session_start();
include 'db/connect.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?open=login");
    exit();
}

require 'includes/header.php';

$userId = $_SESSION['user_id'];
$error = "";
$success = "";

// 1. Handle Password Update
if (isset($_POST['update_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_new_password'];

    $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';

    if ($newPassword !== $confirmPassword) {
        $error = "New passwords do not match.";
    } elseif (!preg_match($passwordPattern, $newPassword)) {
        $error = "Password must be 8+ chars with uppercase, lowercase, number, and special char.";
    } else {
        $stmt = $con->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if (password_verify($currentPassword, $user['password'])) {
            $newHashed = password_hash($newPassword, PASSWORD_DEFAULT);
            $update = $con->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->bind_param("si", $newHashed, $userId);
            
            if ($update->execute()) {
                $success = "Password updated successfully!";
            } else {
                $error = "Database error. Please try again.";
            }
        } else {
            $error = "Current password is incorrect.";
        }
    }
}

$userQuery = $con->prepare("SELECT firstName, lastName, email, createdAt FROM users WHERE id = ?");
$userQuery->bind_param("i", $userId);
$userQuery->execute();
$userData = $userQuery->get_result()->fetch_assoc();
?>

<div class="account-container">
    <div class="account-grid">
        <div class="profile-card">
            <div class="profile-header">
                <div class="avatar"><?php echo strtoupper($userData['firstName'][0]); ?></div>
                <h2><?php echo htmlspecialchars($userData['firstName'] . ' ' . $userData['lastName']); ?></h2>
                <p>Member since: <?php echo date('M Y', strtotime($userData['createdAt'])); ?></p>
            </div>
            <div class="profile-info">
                <div class="info-row">
                    <span>Email: </span>
                    <strong><?php echo htmlspecialchars($userData['email']); ?></strong>
                </div>
            </div>
        </div>

        <div class="security-card">
            <h3>Change Password</h3>
            
            <?php if ($error): ?>
                <div class="auth-msg error-msg"><i class="bi bi-exclamation-circle"></i> <?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="auth-msg success-msg"><i class="bi bi-check-circle"></i> <?php echo $success; ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="input-group">
                    <label>Current Password</label>
                    <input type="password" name="current_password" required>
                </div>
                <div class="input-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" id="new_password" required>
                </div>
                <div class="input-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_new_password" required>
                </div>
                <button type="submit" name="update_password" class="primary">Update Security Settings</button>
            </form>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>