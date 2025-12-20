<?php
if (!isset($_SESSION['admin_id'])) {
  echo "<script>window.location.assign('login.php');</script>";
}
