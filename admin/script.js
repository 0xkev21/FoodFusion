// Ingredients
function addIngredient() {
  const container = document.getElementById("ingredients-container");
  const newRow = document.createElement("div");
  newRow.className = "ingredient-row";
  newRow.style.cssText = "display: flex; gap: 10px; margin-bottom: 10px;";

  newRow.innerHTML = `
  <input type="text" name="ing_name[]" placeholder="Ingredient Name" style="flex: 1;" required>
      <input type="number" name="ing_amount[]" placeholder="Qty" step="0.1" style="width: 80px;" required>
      <select name="ing_unit[]" style="width: 100px;" required>
        ${unitOptionsHTML}
      </select>
      <button type="button" class="remove-btn" onclick="removeRow(this)">
        <i class="bi bi-trash"></i>
      </button>
    `;
  container.appendChild(newRow);
  newRow.querySelector("input").focus();
  updateRemoveButtons("ingredients-container");
}

function addInstruction() {
  const container = document.getElementById("instructions-container");
  const stepCount = container.children.length + 1;
  const newRow = document.createElement("div");
  newRow.className = "instruction-row";
  newRow.innerHTML = `
      <span class="step-number">${stepCount}</span>
      <textarea name="instructions[]" rows="2" placeholder="Next step..." required></textarea>
      <button type="button" class="remove-btn" onclick="removeRow(this)"><i class="bi bi-trash"></i></button>
    `;
  container.appendChild(newRow);
  newRow.querySelector("textarea").focus();
  updateRemoveButtons("instructions-container");
}

function removeRow(button) {
  const row = button.parentElement;
  const container = row.parentElement;
  if (container.children.length > 1) {
    row.remove();
    if (container.id === "instructions-container") {
      const rows = container.getElementsByClassName("instruction-row");
      for (let i = 0; i < rows.length; i++) {
        rows[i].querySelector(".step-number").innerText = i + 1;
      }
    }
    updateRemoveButtons(container.id);
  }
}

function updateRemoveButtons(containerId) {
  const container = document.getElementById(containerId);
  const buttons = container.querySelectorAll(".remove-btn");
  const visibility = buttons.length > 1 ? "visible" : "hidden";
  buttons.forEach((btn, i) =>
    i > 0 ? (btn.style.visibility = visibility) : null,
  );
}

function toggleInput() {
  const typeSelect = document.getElementById("res-type");
  const fileContainer = document.getElementById("file-input-container");
  const videoContainer = document.getElementById("video-link-container");

  const fileInput = document.getElementById("resource-file");
  const videoInput = document.getElementById("video-link");

  if (typeSelect.value === "Video") {
    videoContainer.style.display = "block";
    fileContainer.style.display = "none";

    videoInput.setAttribute("required", "required");
    fileInput.removeAttribute("required");
    fileInput.value = "";
  } else {
    videoContainer.style.display = "none";
    fileContainer.style.display = "block";

    fileInput.setAttribute("required", "required");
    videoInput.removeAttribute("required");
    videoInput.value = "";
  }
}

function openModal(modalId) {
  document.getElementById(modalId).style.display = "block";
}

function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none";
}

window.onclick = function (event) {
  if (event.target.classList.contains("custom-modal")) {
    event.target.style.display = "none";
  }
};

// sidebar
const sidebarToggles = document.querySelectorAll(".sidebar-toggle");
sidebarToggles.forEach((btn) => {
  btn.addEventListener("click", (e) => {
    const sidebar = document.querySelector(".sidebar");
    sidebar.classList.toggle("open");
    e.stopPropagation();

    const closeSidebar = (event) => {
      if (!sidebar.contains(event.target)) {
        sidebar.classList.remove("open");
        window.removeEventListener("click", closeSidebar);
      }
    };

    if (sidebar.classList.contains("open")) {
      window.addEventListener("click", closeSidebar);
    }
  });
});
