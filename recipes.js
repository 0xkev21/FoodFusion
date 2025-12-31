const container = document.querySelector(".recipes-container");
const searchInput = document.getElementById("search-input");
const radios = document.querySelectorAll("input[type='radio']");
const clearFiltersBtn = document.querySelector(".clear-filters-btn");
const sortSelect = document.getElementById("sort");

let filteredRecipes = recipes;

// innerHTML sanitizer
const escapeHTML = (str) => {
  if (!str) return "";
  return str.replace(/[&<>"']/g, function (m) {
    return {
      "&": "&amp;",
      "<": "&lt;",
      ">": "&gt;",
      '"': "&quot;",
      "'": "&#039;",
    }[m];
  });
};

const renderRecipes = (recipes) => {
  if (recipes.length == 0) {
    container.innerHTML = "<h4>🍽️ No recipes found...</h4>";
    return;
  }
  container.innerHTML = "";
  let recipesHtml = "";
  recipes.forEach((recipe) => {
    const ratingHtml = recipe.rating
      ? `<span>:</span><span class="rating">${parseFloat(
          recipe.rating,
        ).toFixed(1)}<i class="bi bi-star"></i></span>`
      : "";

    const cardHtml = `
    <a class="card" href="recipe.php?id=${recipe.id}" data-id="${recipe.id}">
      <div class="image-container">
        <img src="./${escapeHTML(recipe.imagePath)}" alt="${escapeHTML(
      recipe.title,
    )}">
        <div class="recipe-des">
          <p>
            ${escapeHTML(recipe.description)}
          </p>
          <h4>Dietary: ${recipe.dietaryPref}</h4>
        </div>
      </div>
      <h4 class="cuisine-type">${recipe.cuisineType}</h4>
      <div class="card-content">
        <h4>${escapeHTML(recipe.title)}</h4>
        <div class="card-content-info">
          <span><i class="bi bi-stopwatch"></i>${escapeHTML(
            String(recipe.cookingTimeMinute),
          )} mins</span>
          ${ratingHtml}
          <span>:</span>
          <span><i class="bi bi-bar-chart"></i>${recipe.difficulty}</span>
        </div>
      </div>
    </a>`;

    recipesHtml += cardHtml;
  });
  container.innerHTML = recipesHtml;
};

const filter = () => {
  const cuisineTypeValue = document.querySelector(
    "input[name='cuisine-type']:checked",
  ).value;
  const dietaryValue = document.querySelector(
    "input[name='dietary-pref']:checked",
  ).value;
  const difficultyValue = document.querySelector(
    "input[name='difficulty']:checked",
  ).value;
  const searchValue = searchInput.value;

  filteredRecipes = recipes;

  if (cuisineTypeValue != "any") {
    filteredRecipes = filteredRecipes.filter(
      (recipe) => recipe.cuisineType == cuisineTypeValue,
    );
  }

  if (dietaryValue != "any") {
    filteredRecipes = filteredRecipes.filter(
      (recipe) => recipe.dietaryPref == dietaryValue,
    );
  }

  if (difficultyValue != "any") {
    filteredRecipes = filteredRecipes.filter(
      (recipe) => recipe.difficulty == difficultyValue,
    );
  }

  if (searchValue != "") {
    filteredRecipes = filteredRecipes.filter((recipe) =>
      recipe.title.toLowerCase().includes(searchInput.value.toLowerCase()),
    );
  }

  renderRecipes(filteredRecipes);
};

const sort = (recipes) => {
  let sortedRecipes;
  sortValue = sortSelect.value;
  if (sortValue == "newest") {
    sortedRecipes = recipes.sort((a, b) => {
      const dateA = new Date(a.createdAt);
      const dateB = new Date(b.createdAt);
      const dateDiff = dateB - dateA;
      if (dateDiff != 0) {
        return dateDiff;
      }
      return a.id - b.id;
    });
  } else if (sortValue == "name") {
    sortedRecipes = recipes.sort((a, b) => {
      return a.title.toLowerCase().localeCompare(b.title.toLowerCase());
    });
  } else if (sortValue == "rating") {
    sortedRecipes = recipes.sort((a, b) => {
      return b.rating - a.rating;
    });
  }
  console.log(sortedRecipes);
  return sortedRecipes;
};

const clearFilters = () => {
  renderRecipes(recipes);
  searchInput.value = "";
  const anyInputs = document.querySelectorAll("input[value='any']");
  anyInputs.forEach((input) => {
    input.checked = true;
  });
};

const urlParams = new URLSearchParams(window.location.search);
const searchParam = urlParams.get("s");

searchInput.addEventListener("input", filter);
radios.forEach((radio) => {
  radio.addEventListener("change", filter);
});
clearFiltersBtn.addEventListener("click", clearFilters);
sortSelect.addEventListener("change", () => {
  renderRecipes(sort(filteredRecipes));
});

if (searchParam) {
  searchInput.value = searchParam;

  filter();
} else {
  renderRecipes(recipes);
}
