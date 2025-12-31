<?php
require 'adminHeader.php';
require 'sidebar.php';
include 'checkLogin.php';

$msg = isset($_GET['msg']) ? $_GET['msg'] : "";
$error = "";

if (isset($_POST['submit_resource'])) {

  $title    = trim($_POST['title']);
  $category = $_POST['category'];
  $type     = $_POST['type'];
  $desc     = trim($_POST['description']);

  $adminId = $_SESSION['admin_id'];

  $resourcePath = "";

  if ($type === 'Video') {
    if (!empty($_POST['video_link'])) {
            $rawUrl = trim($_POST['video_link']);
            
            $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
            
            if (preg_match($pattern, $rawUrl, $matches)) {
                $videoId = $matches[1];
                $resourcePath = "https://www.youtube.com/embed/" . $videoId;
            } else {
                $error = "Error: Could not extract Video ID. Please ensure it is a valid YouTube URL.";
            }

        } else {
            $error = "Error: Please provide a YouTube link.";
        }
  } else {
    if (isset($_FILES['resource_file']) && $_FILES['resource_file']['error'] === 0) {
      $fName = $_FILES['resource_file']['name'];
      $fTmp  = $_FILES['resource_file']['tmp_name'];
      $fExt  = strtolower(pathinfo($fName, PATHINFO_EXTENSION));

      $allowedRes = [];
      if ($type === 'PDF') {
        $allowedRes = ['pdf'];
      } elseif ($type === 'Infographic') {
        $allowedRes = ['jpg', 'jpeg', 'png', 'webp'];
      }

      if (in_array($fExt, $allowedRes)) {
        $newResName = uniqid('res_', true) . "." . $fExt;
        $uploadDir = '../images/resources/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        if (move_uploaded_file($fTmp, $uploadDir . $newResName)) {
          $resourcePath = '/images/resources/' . $newResName;
        } else {
          $error = "Error: Failed to upload resource file.";
        }
      } else {
        if ($type === 'PDF') {
          $error = "Error: You selected 'PDF Document' but uploaded a .$fExt file. Please upload a PDF.";
        } else {
          $error = "Error: You selected 'Infographic' but uploaded a .$fExt file. Please upload an image.";
        }
      }
    } else {
      $error = "Error: Please upload a resource file.";
    }
  }

  $coverPath = "";
  if (empty($error)) {
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === 0) {
      $imgName = $_FILES['cover_image']['name'];
      $imgTmp  = $_FILES['cover_image']['tmp_name'];
      $imgExt  = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
      $allowedImg = ['jpg', 'jpeg', 'png', 'webp'];

      if (in_array($imgExt, $allowedImg)) {
        $newImgName = uniqid('thumb_', true) . "." . $imgExt;
        $uploadDir = '../images/resources/covers/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        if (move_uploaded_file($imgTmp, $uploadDir . $newImgName)) {
          $coverPath = '/images/resources/covers/' . $newImgName;
        } else {
          $error = "Error: Failed to upload cover image.";
        }
      } else {
        $error = "Error: Cover image must be JPG, PNG, or WEBP.";
      }
    } else {
      $error = "Error: Cover image is required.";
    }
  }

  if (empty($error)) {
    $sql = "INSERT INTO resources (title, category, type, description, filePath, coverImagePath, adminId, uploadedAt) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $con->prepare($sql);
    if ($stmt) {
      $stmt->bind_param("ssssssi", $title, $category, $type, $desc, $resourcePath, $coverPath, $adminId);

      if ($stmt->execute()) {
        header("Location: createResource.php?msg=Resource posted successfully");
        exit();
      } else {
        $error = "Database Error: " . $stmt->error;
      }
    } else {
      $error = "SQL Error: " . $con->error;
    }
  }
}

?>

<section class="dashboard">
  <h2>Post New Resource</h2>

  <?php if ($error): ?>
    <div style="background:#ffe6e6; color:#d63031; padding:15px; border-radius:8px; margin-bottom:20px;">
      <?php echo $error; ?>
    </div>
  <?php endif; ?>
  <?php if ($msg): ?>
    <div style="background:#e6fff0; color:#00b894; padding:15px; border-radius:8px; margin-bottom:20px;">
      <?php echo $msg; ?>
    </div>
  <?php endif; ?>

  <form action="" method="POST" enctype="multipart/form-data" class="recipe-form">

    <div class="form-group">
      <label>Resource Title</label>
      <input type="text" name="title" required placeholder="e.g., Weekly Meal Planner Template"
        value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
    </div>

    <div class="recipe-meta-grid">

      <label class="meta-box" for="res-category">
        <div class="meta-icon"><i class="bi bi-bookmarks-fill"></i></div>
        <div class="meta-content">
          <div>Category</div>
          <div class="select-wrapper">
            <select id="res-category" name="category" required>
              <option value="" disabled selected>Select...</option>
              <option value="Culinary">Culinary</option>
              <option value="RenewableEnergy">Educational</option>
            </select>
            <i class="bi bi-chevron-down dropdown-arrow"></i>
          </div>
        </div>
      </label>

      <label class="meta-box" for="res-type">
        <div class="meta-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
        <div class="meta-content">
          <div>File Type</div>
          <div class="select-wrapper">
            <select id="res-type" name="type" required onchange="toggleInput()">
              <option value="PDF">PDF Document</option>
              <option value="Infographic">Infographic (Image)</option>
              <option value="Video">Video (YouTube)</option>
            </select>
            <i class="bi bi-chevron-down dropdown-arrow"></i>
          </div>
        </div>
      </label>

    </div>

    <div class="form-group">
      <label>Description</label>
      <textarea name="description" rows="5" required placeholder="Explain what this resource is for..."><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
    </div>

    <div class="form-row">

      <div class="form-col" id="file-input-container">
        <div class="form-group">
          <label>Upload Resource File <small>(PDF, JPG, PNG)</small></label>
          <input type="file" id="resource-file" name="resource_file" accept=".pdf, .jpg, .jpeg, .png, .webp">
        </div>
      </div>

      <div class="form-col" id="video-link-container" style="display: none;">
        <div class="form-group">
          <label>YouTube Video Link</label>
          <input type="url" id="video-link" name="video_link" placeholder="https://www.youtube.com/...">
        </div>
      </div>

      <div class="form-col">
        <div class="form-group">
          <label>Cover Image <small>(Thumbnail)</small></label>
          <input type="file" name="cover_image" accept="image/*" required>
        </div>
      </div>

    </div>

    <button type="submit" name="submit_resource" class="btn-submit">Upload Resource</button>

  </form>
</section>
</main>

</body>

</html>