<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<section class="sidebar">
  <div class="top">
    <div class="logo">
      <svg viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M21.3,3.23a1,1,0,0,0-1.05-.22l-6.23,2.27-5-2.14a1,1,0,0,0-1,0L2,5.76V16.2a1,1,0,0,0,.5.87l6,3.85a1,1,0,0,0,1,0l6-3.85a1,1,0,0,0,.5-.87V8.52l5.28-1.92A1,1,0,0,0,22,5.58V4.28A1,1,0,0,0,21.3,3.23ZM8,17.13,4,14.58V7.58l3.92,1.68Zm2,0V9.26l4-1.45v7.92Zm8.28-12.2-4.23,1.54L10,8.15V5.58L14,4l4.23,1.54Z"></path>
      </svg>
    </div>
    <div>
      <h1>FoodFusion</h1>
      <p>Admin Panel</p>
    </div>
    <button title="Toggle Sidebar" class="sidebar-toggle">
      <i class="bi bi-layout-sidebar-inset"></i>
    </button>
  </div>

  <div class="side-nav">

    <a href="recipes.php"
      class="<?php echo ($current_page == 'recipes.php') ? 'active' : ''; ?>">
      <i class="bi bi-fork-knife"></i>Recipes
    </a>

    <a href="createRecipe.php"
      class="<?php echo ($current_page == 'createRecipe.php') ? 'active' : ''; ?>">
      <i class="bi bi-patch-plus"></i>Create New Recipe
    </a>

    <a href="events.php"
      class="<?php echo ($current_page == 'events.php' || $current_page == 'admin_events.php') ? 'active' : ''; ?>">
      <i class="bi bi-calendar-event"></i>Events
    </a>

    <a href="createEvent.php"
      class="<?php echo ($current_page == 'createEvent.php' || $current_page == 'create_event.php') ? 'active' : ''; ?>">
      <i class="bi bi-calendar2-plus"></i>Create New Event
    </a>

    <a href="createResource.php"
      class="<?php echo ($current_page == 'createResource.php') ? 'active' : ''; ?>">
      <i class="bi bi-cloud-arrow-up"></i>Post New Resource
    </a>

    <a href="enquiries.php"
      class="<?php echo ($current_page == 'enquiries.php') ? 'active' : ''; ?>">
      <i class="bi bi-envelope-paper"></i>Enquiries
    </a>

    <a href="manageCategories.php"
      class="<?php echo ($current_page == 'manageCategories.php') ? 'active' : ''; ?>">
      <i class="bi bi-menu-button-wide"></i>Recipe Categories
    </a>

    <a href="settings.php"
      class="<?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>">
      <i class="bi bi-gear"></i>Settings
    </a>

    <a class="logout-btn" href="adminLogout.php">
      <i class="bi bi-box-arrow-left"></i>Logout
    </a>

  </div>
</section>
<div class="sidebar-btn-container">
  <button title="Toggle Sidebar" class="sidebar-toggle">
    <i class="bi bi-layout-sidebar-inset"></i>
  </button>
</div>