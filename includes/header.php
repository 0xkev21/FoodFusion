<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>
    <?php
    if (isset($pageTitle)) {
      echo $pageTitle . " | Food Fusion";
    } else {
      echo "Food Fusion";
    }
    ?>
  </title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com" rel="preconnect" />
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap"
    rel="stylesheet" />

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

  <link rel="stylesheet" href="style.css">
  <?php
  echo isset($printRecipe) && $printRecipe ? "<link rel='stylesheet' href='print.css'>" : "";
  ?>
  <script src="script.js" defer></script>

  <!-- Favicons -->
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="images/favicons/favicon-16x16.png">
  <link rel="shortcut icon" href="images/favicons/favicon.ico">
  <link rel="apple-touch-icon" sizes="180x180" href="images/favicons/apple-touch-icon.png">
</head>

<body>
  <header>
    <?php
    include "db/connect.php";
    ?>
    <div class="top-container">
      <div class="top-left">
        <a class="logo-container <?php echo $pageTitle == 'Home' ? 'active' : '' ?>" href="index.php">
          <div class="logo">
            <svg viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M21.3,3.23a1,1,0,0,0-1.05-.22l-6.23,2.27-5-2.14a1,1,0,0,0-1,0L2,5.76V16.2a1,1,0,0,0,.5.87l6,3.85a1,1,0,0,0,1,0l6-3.85a1,1,0,0,0,.5-.87V8.52l5.28-1.92A1,1,0,0,0,22,5.58V4.28A1,1,0,0,0,21.3,3.23ZM8,17.13,4,14.58V7.58l3.92,1.68Zm2,0V9.26l4-1.45v7.92Zm8.28-12.2-4.23,1.54L10,8.15V5.58L14,4l4.23,1.54Z">
              </path>
            </svg>
          </div>
          <h1>FoodFusion</h1>
        </a>
        <div class="navitems-container">
          <nav>
            <button class="nav-close-btn" type="button"><i class="bi bi-x"></i></button>
            <a href="recipes.php" class="<?php echo $pageTitle == 'Recipes' ? 'active' : '' ?>">Recipes</a>
            <a href="community.php" class="<?php echo $pageTitle == 'Community' ? 'active' : '' ?>">Community</a>
            <a href="resources.php" class="<?php echo $pageTitle == 'Resources' ? 'active' : '' ?>">Resources</a>
            <a href="about.php" class="<?php echo $pageTitle == 'About' ? 'active' : '' ?>">About Us</a>
            <a href="contact.php" class="<?php echo $pageTitle == 'Contact' ? 'active' : '' ?>">Contact</a>
            <div class="mobile-nav-container">
              <div class="search-container">
                <form class="search-input" action="recipes.php" method="GET">
                  <i class="bi bi-search"></i>
                  <input type="text" name="s"
                    placeholder="Search recipes...">
                </form>
              </div>
              <div class="buttons-container">
                <?php if (isset($_SESSION['user_id'])): ?>
                  <span class="user-greeting">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                  <a href="profile.php" class="icon-btn" title="My Profile">
                    <i class="bi bi-person-circle"></i>
                  </a>
                  <a href="logout.php" class="icon-btn" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                  </a>
                <?php else: ?>
                  <button class="to-login-btn secondary">Login</button>
                  <button class="to-register-btn primary">Sign Up</button>
                <?php endif; ?>
              </div>
            </div>
          </nav>
        </div>
      </div>
      <div class="top-right">
        <div class="search-container">
          <form class="search-input" action="recipes.php" method="GET">
            <i class="bi bi-search"></i>
            <input type="text" name="s"
              placeholder="Search recipes...">
          </form>
        </div>
        <div class="buttons-container">
          <?php if (isset($_SESSION['user_id'])): ?>
            <span class="user-greeting">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <a href="profile.php?id=<?php echo $_SESSION['user_id'] ?>" class="icon-btn" title="My Profile">
              <i class="bi bi-person-circle"></i>
            </a>
            <a href="logout.php" class="icon-btn" title="Logout">
              <i class="bi bi-box-arrow-right"></i>
            </a>
          <?php else: ?>
            <button class="to-login-btn secondary">Login</button>
            <button class="to-register-btn primary">Sign Up</button>
          <?php endif; ?>
        </div>
      </div>
      <div class="menu-container">
        <button class="menu"><i class="bi bi-list"></i></button>
      </div>
    </div>
  </header>
  <main>