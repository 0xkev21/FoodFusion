<?php
$pageTitle = 'Resources';

include 'db/connect.php';
$resourcesData = [];
$stmt = $con->prepare("SELECT id, title, description, type, category, coverImagePath from resources;");
if ($stmt->execute()) {
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $resourcesData[] = $row;
  }
}
require 'includes/header.php'
?>
<script>
  const resources = <?php echo json_encode($resourcesData); ?>;
  console.log(resources);
</script>
<script src="resources.js" defer></script>
<section class="resources-hero-section">
  <h2>Explore Our Culinary Library</h2>
</section>
<section class="resources">
  <div class="resource-filters">
    <div class="search-container">
      <div class="search-input"><i class="bi bi-search"></i><input type="text" name="" id=""
          placeholder="Search for a resource..."></div>
    </div>
    <div class="select-btn-group">
      <button class="active resource-type-btn" data-type="All">All</button>
      <button class="resource-type-btn" data-type="Video">Video</button>
      <button class="resource-type-btn" data-type="PDF">PDF</button>
      <button class="resource-type-btn" data-type="Infographic">Infographic</button>
    </div>
    <div class="select-btn-group">
      <button class="active resource-category-btn" data-category="All">All</button>
      <button class="resource-category-btn" data-category="Culinary">Culinary</button>
      <button class="resource-category-btn" data-category="RenewableEnergy">Educational</button>
    </div>
  </div>
  <div class="resources-container">
    <!-- Resources -->
  </div>
</section>
<?php
require 'includes/footer.php';
?>