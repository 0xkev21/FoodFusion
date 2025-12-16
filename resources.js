const currentResources = resources;

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

const filterResources = () => {};

renderResources(currentResources);
