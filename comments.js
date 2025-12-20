const modal = document.getElementById("commentModal");
const modalBody = document.getElementById("modalBody");
const modalPostId = document.getElementById("modalPostId");
const urlParams = new URLSearchParams(window.location.search);

if (modal) {
  document.querySelectorAll(".comment-btn").forEach((button) => {
    button.addEventListener("click", function () {
      const url = new URL(window.location.href);
      url.searchParams.delete("pid");
      url.searchParams.delete("success");
      window.history.replaceState({}, document.title, url);
      const postId = this.getAttribute("data-id");

      modalPostId.value = postId;

      modal.style.display = "flex";
      modalBody.innerHTML = "Loading...";

      fetch(`api/getComments.php?post_id=${postId}`)
        .then((response) => response.json())
        .then((data) => {
          showComments(data);
        });
    });
  });

  document.querySelectorAll(".like-btn").forEach((button) => {
    button.addEventListener("click", function () {
      const postId = this.getAttribute("data-id");
      const icon = this.querySelector("i");
      const countSpan = this.querySelector("span");

      if (this.classList.contains("disabled")) return;
      this.classList.add("disabled");

      const formData = new FormData();
      formData.append("post_id", postId);

      fetch("api/toggleLike.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          this.classList.remove("disabled");

          if (data.status === "liked") {
            icon.classList.remove("bi-heart");
            icon.classList.add("bi-heart-fill", "text-danger");
          } else {
            icon.classList.remove("bi-heart-fill", "text-danger");
            icon.classList.add("bi-heart");
          }

          if (countSpan) countSpan.innerText = data.new_count;
        })
        .catch((error) => {
          console.error("Error:", error);
          this.classList.remove("disabled");
        });
    });
  });
} else {
  document.querySelectorAll(".comment-btn").forEach((button) => {
    button.addEventListener("click", () => {
      formWrapper.classList.add("active");
    });
  });
  document.querySelectorAll(".like-btn").forEach((button) => {
    button.addEventListener("click", () => {
      formWrapper.classList.add("active");
    });
  });
}

function closeModal() {
  modal.style.display = "none";
}

window.onclick = function (event) {
  if (event.target == modal) {
    closeModal();
  }
};

const postIdParam = urlParams.get("pid");

if (postIdParam) {
  modalPostId.value = postIdParam;

  modal.style.display = "flex";
  modalBody.innerHTML = "Loading...";

  fetch(`api/getComments.php?post_id=${postIdParam}`)
    .then((response) => response.json())
    .then((data) => {
      showComments(data);
    });
}

function showComments(data) {
  modalBody.innerHTML = "";

  if (data.length === 0) {
    modalBody.innerHTML = "<p>No comments yet.</p>";
    return;
  }

  data.forEach((item) => {
    const div = document.createElement("div");
    div.className = "comment-item";
    div.style.cssText = "margin-bottom: 10px; border-bottom: 1px solid #eee;";

    div.innerHTML = `
              <strong>${item.name}</strong>
              <span style="color:#777; font-size:0.8em;">${item.date}</span>
              <p style="margin: 5px 0 0 0;">${item.comment}</p>
          `;

    modalBody.appendChild(div);
  });
}
