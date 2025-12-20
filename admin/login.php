<?php
require 'adminHeader.php';
if (isset($_POST['login_btn'])) {

  $email = trim($_POST['email']);
  $password = $_POST['password'];

  if (empty($email) || empty($password)) {
    header("Location: login.php?error=All fields are required");
    exit();
  }

  $sql = "SELECT * FROM admin WHERE email = ?";
  $stmt = $con->prepare($sql);

  if ($stmt) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

      if (password_verify($password, $row['password'])) {

        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['email'] = $row['email'];


        $_SESSION['admin_name'] = $row['name'];

        header("Location: recipes.php");
        exit();
      } else {
        header("Location: login.php?error=Incorrect password");
        exit();
      }
    } else {
      header("Location: login.php?error=No admin found with this email");
      exit();
    }
  } else {
    header("Location: login.php?error=Database error");
    exit();
  }
}
?>
<div class="login-container">

  <div class="login-header">
    <div class="logo-circle">
      <svg viewBox="0 0 24 24" fill="currentColor" width="30" height="30">
        <path
          d="M21.3,3.23a1,1,0,0,0-1.05-.22l-6.23,2.27-5-2.14a1,1,0,0,0-1,0L2,5.76V16.2a1,1,0,0,0,.5.87l6,3.85a1,1,0,0,0,1,0l6-3.85a1,1,0,0,0,.5-.87V8.52l5.28-1.92A1,1,0,0,0,22,5.58V4.28A1,1,0,0,0,21.3,3.23ZM8,17.13,4,14.58V7.58l3.92,1.68Zm2,0V9.26l4-1.45v7.92Zm8.28-12.2-4.23,1.54L10,8.15V5.58L14,4l4.23,1.54Z">
        </path>
      </svg>
    </div>
    <h2>Welcome Back</h2>
    <p>Enter your credentials to access the admin panel.</p>
  </div>

  <?php if (isset($_GET['error'])): ?>
    <div class="error-banner">
      <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
  <?php endif; ?>

  <form action="login.php" method="POST">

    <div class="form-group">
      <label>Email Address</label>
      <input type="email" name="email" placeholder="admin@foodfusion.com" required>
    </div>

    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password" placeholder="••••••••" required>
    </div>

    <button type="submit" name="login_btn" class="btn-submit">Sign In</button>

  </form>

  <div class="login-footer">
    <a href="../index.php">← Back to Website</a>
  </div>

</div>
</main>
</body>

</html>