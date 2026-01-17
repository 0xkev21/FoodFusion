<?php
require 'adminHeader.php';
require 'sidebar.php';
include 'checkLogin.php';

$error = "";
$success = "";

// 1. Handle Updating Existing Links
if (isset($_POST['update_links'])) {
  foreach ($_POST['url'] as $id => $url) {
    $stmt = $con->prepare("UPDATE social_links SET url = ? WHERE id = ?");
    $stmt->bind_param("si", $url, $id);
    if (!$stmt->execute()) {
      $error = "Failed to update some links.";
    }
  }
  if (!$error) $success = "Social links updated successfully!";
}

// 2. Handle Adding a New Platform
if (isset($_POST['add_platform'])) {
  $platform = strtolower(trim($_POST['new_platform']));
  $url = trim($_POST['new_url']);

  if (!empty($platform) && !empty($url)) {
    $stmt = $con->prepare("INSERT INTO social_links (platform, url) VALUES (?, ?)");
    $stmt->bind_param("ss", $platform, $url);
    if ($stmt->execute()) {
      $success = "New platform added!";
    } else {
      $error = "Platform already exists or database error.";
    }
  }
}

// 3. Handle Deletion
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $stmt = $con->prepare("DELETE FROM social_links WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  header("Location: manageSocials.php?status=success");
  exit();
}

$socials = $con->query("SELECT * FROM social_links");
?>

<section class="dashboard">
  <div class="header-flex">
    <h2>Manage Social Media Links</h2>
  </div>

  <?php if ($error): ?>
    <div class="auth-msg error-msg"><?php echo $error; ?></div>
  <?php endif; ?>
  <?php if ($success || isset($_GET['status'])): ?>
    <div class="auth-msg success-msg"><?php echo $success ?: "Action completed!"; ?></div>
  <?php endif; ?>

  <div class="category-grid">
    <div class="category-card">
      <h3><i class="bi bi-pencil-square"></i> Existing Platforms</h3>
      <form action="" method="POST" class="socials-form">
        <?php while ($row = $socials->fetch_assoc()): ?>
          <div class="input-group" style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
            <label style="display: flex; justify-content: space-between;">
              <span><i class="bi bi-<?php echo $row['platform']; ?>"></i> <?php echo ucfirst($row['platform']); ?></span>
              <a href="?delete=<?php echo $row['id']; ?>" style="color: #e74c3c; font-size: 0.8rem;" onclick="return confirm('Delete this platform?')">Delete</a>
            </label>
            <input type="url" name="url[<?php echo $row['id']; ?>]" value="<?php echo htmlspecialchars($row['url']); ?>" required>
          </div>
        <?php endwhile; ?>
        <button type="submit" name="update_links" class="submit-btn primary">Update All Links</button>
      </form>
    </div>

    <div class="category-card">
      <h3><i class="bi bi-plus-circle"></i> Add New Platform</h3>
      <form action="" method="POST" class="socials-form">
        <div class="input-group">
          <label>Platform Name (e.g., tiktok, pinterest)</label>
          <input type="text" name="new_platform" placeholder="Name must match Bootstrap Icon" required>
        </div>
        <div class="input-group">
          <label>Full URL</label>
          <input type="url" name="new_url" placeholder="https://..." required>
        </div>
        <button type="submit" name="add_platform" class="submit-btn primary">Add Platform</button>
      </form>
      <p style="font-size: 0.8rem; color: #666; margin-top: 10px;">
        Note: Ensure the platform name exists in <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a>.
      </p>
    </div>
  </div>
</section>

</main>
</body>

</html>