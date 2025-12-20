const container = document.querySelector(".resources-container");

const renderResources = (data) => {
  container.innerHTML = "";
  const frag = document.createDocumentFragment();
  data.forEach((resource) => {
    const resourceDard = document.createElement("a");
    resourceDard.setAttribute("href", `resource.php?id=${resource.id}`);
    resourceDard.classList.add("resource-card");
    resourceDard.innerHTML = `<div class="image-container">
        <img src="./${resource.coverImagePath}" alt="${resource.title}">
      </div>
      <span class="resource-type">
        <i class="bi bi-${
          resource.type == "Video"
            ? "play-btn"
            : resource.type == "PDF"
            ? "book"
            : "diagram-2"
        }"></i>
        ${resource.type}
      </span>
      <div class="resource-content">
        <h4>${resource.title}</h4>
        <p>${resource.description}</p>
      </div>`;
    frag.appendChild(resourceDard);
  });
  container.appendChild(frag);
};

const categoryBtns = document.querySelectorAll(".resource-category-btn");
const typeBtns = document.querySelectorAll(".resource-type-btn");
const resourceSearch = document.querySelector("#resource-search");

const filterResources = () => {
  const searchValue = resourceSearch.value;
  const categoryValue = document
    .querySelector(".resource-category-btn.active")
    .getAttribute("data-category");
  const typeValue = document
    .querySelector(".resource-type-btn.active")
    .getAttribute("data-type");

  let filteredResources = resources;
  if (typeValue != "All") {
    filteredResources = filteredResources.filter(
      (resource) => resource.type == typeValue,
    );
  }
  if (categoryValue != "All") {
    filteredResources = filteredResources.filter(
      (resource) => resource.category == categoryValue,
    );
  }
  if (searchValue != "") {
    filteredResources = filteredResources.filter((resource) =>
      resource.title.toLowerCase().includes(searchValue.toLowerCase()),
    );
  }
  return filteredResources;
};

categoryBtns.forEach((btn) => {
  btn.addEventListener("click", (e) => {
    categoryBtns.forEach((btn) => {
      btn.classList.remove("active");
    });
    e.target.classList.add("active");
    renderResources(filterResources());
  });
});
typeBtns.forEach((btn) => {
  btn.addEventListener("click", (e) => {
    typeBtns.forEach((btn) => {
      btn.classList.remove("active");
    });
    e.target.classList.add("active");
    renderResources(filterResources());
  });
});
resourceSearch.addEventListener("input", () => {
  renderResources(filterResources());
});

renderResources(resources);
