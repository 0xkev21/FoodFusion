// Ingredients
function addIngredient() {
  const container = document.getElementById("ingredients-container");
  const newRow = document.createElement("div");
  newRow.className = "ingredient-row";

  newRow.innerHTML = `
        <input type="text" name="ingredients[]" placeholder="e.g. 1 tsp Salt" required>
        <button type="button" class="remove-btn" onclick="removeRow(this)">
            <i class="bi bi-trash"></i>
        </button>
    `;
  container.appendChild(newRow);
  newRow.querySelector("input").focus();
}

function removeRow(button) {
  const row = button.closest(".ingredient-row");
  row.remove();
}

// Instruction Inputs
function addInstruction() {
  const container = document.getElementById("instructions-container");
  const stepCount = container.children.length + 1; // Calculate next step number
  const newRow = document.createElement("div");
  newRow.className = "instruction-row";
  newRow.innerHTML = `
        <span class="step-number">${stepCount}</span>
        <textarea name="instructions[]" rows="2" placeholder="What is Step ${stepCount}?" required></textarea>
        <button type="button" class="remove-btn" onclick="removeInstruction(this)">
            <i class="bi bi-trash"></i>
        </button>
    `;

  container.appendChild(newRow);
  newRow.querySelector("textarea").focus();
}

function removeInstruction(button) {
  const row = button.closest(".instruction-row");
  const container = document.getElementById("instructions-container");
  row.remove();
  const rows = container.getElementsByClassName("instruction-row");
  for (let i = 0; i < rows.length; i++) {
    rows[i].querySelector(".step-number").innerText = i + 1;
    rows[i].querySelector("textarea").placeholder = `What is Step ${i + 1}?`;
  }
}

// Resource type
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

