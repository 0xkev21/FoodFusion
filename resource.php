<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo "<script>window.location.assign('index.php');</script>";
}

$id = intval($_GET['id']);
include 'db/connect.php';

$sqlResource = "select title, description, category, type, filePath, uploadedAt from resources where id = ?";
$stmtResource = $con->prepare($sqlResource);
$stmtResource->bind_param("i", $id);
$resource = [];
if ($stmtResource->execute()) {
  $result = $stmtResource->get_result();
  $resource = $result->fetch_assoc();
}
$pageTitle = $resource['title'];
require 'includes/header.php';
?>
<script>
  console.log(<?php json_encode($resource) ?>);
</script>
<section>
  <div class="resource-top">
    <div>
      <h2><?php echo $resource['title'] ?></h2>
      <p><?php echo $resource['description'] ?></p>
    </div>
    <div>
      <div class="resource-types">
        <p><i class="bi bi-<?php echo $resource['type'] == "Video" ? "play-btn" : ($resource['type'] == "PDF" ? "book" : "diagram-2"); ?>"></i><?php echo $resource['type'] ?></p>
        <p><?php echo $resource['category'] ?></p>
      </div>
      <p>Uploaded: <?php echo date("d M Y", strtotime($resource['uploadedAt'])) ?></p>
    </div>
  </div>
  <div>
    <?php if ($resource['type'] == "Video") : ?>
      <div class="iframe-resource-container">
        <iframe class="video" src="<?php echo $resource['filePath'] ?>"
          title="<?php echo $resource['title'].' '.'Video' ?>" frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
          referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
        </iframe>
      </div>
    <?php elseif ($resource['type'] == "Infographic") : ?>
      <div class="info-resource-container">
        <div class="resource-infographic">
          <div class="resource-image-container">
            <img src="./<?php echo $resource['filePath'] ?>" alt="<?php echo $resource['title'] . ' ' . 'Photo' ?>">
          </div>
          <div class="resource-actions">
            <a href="./<?php echo $resource['filePath'] ?>" download="<?php echo basename($resource['filePath']) ?>"><i class="bi bi-save"></i>Download</a>
          </div>
        </div>
      </div>
    <?php elseif ($resource['type'] == "PDF") : ?>
      <div class="pdf-resource-container">
        <iframe class="pdf" src="./<?php echo $resource['filePath'] ?>" frameborder="0"></iframe>
        <div class="resource-actions">
          <a href="./<?php echo $resource['filePath'] ?>" download="<?php echo basename($resource['filePath']) ?>"><i class="bi bi-save"></i>Download</a>
        </div>
      </div>
  </div>
<?php endif; ?>
</section>
<?php 
require 'includes/footer.php';
?>