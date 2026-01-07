// Nav Menu
const menu = document.querySelector(".menu");
menu.addEventListener("click", () => {
  document.body.classList.toggle("nav-open");
});
const navCloseBtn = document.querySelector(".nav-close-btn");
navCloseBtn.addEventListener("click", () => {
  document.body.classList.remove("nav-open");
});

// Form
const formWrapper = document.querySelector(".form-wrapper");
if (formWrapper) {
  const forms = document.querySelectorAll(".form-wrapper form");
  const formCloseBtns = document.querySelectorAll(".form-close");
  formCloseBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      formWrapper.classList.remove("active");
    });
  });
  forms.forEach((form) => {
    form.addEventListener("click", (e) => {
      e.stopPropagation();
    });
  });

  const currentPath = window.location.pathname;
  if (
    currentPath === "/foodfusion/index.php" ||
    currentPath === "/foodfusion/"
  ) {
    setTimeout(() => {
      formWrapper.classList.add("active");
    }, 10000);
  }

  const toLoginBtns = document.querySelectorAll(".to-login-btn");
  const toRegisterBtns = document.querySelectorAll(".to-register-btn");
  const loginForm = document.querySelector(".login-form");
  const registerForm = document.querySelector(".register-form");
  toLoginBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      formWrapper.classList.add("active");
      loginForm.classList.add("active");
      registerForm.classList.remove("active");
    });
  });
  toRegisterBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      formWrapper.classList.add("active");
      registerForm.classList.add("active");
      loginForm.classList.remove("active");
    });
  });
}

document.addEventListener("DOMContentLoaded", () => {
  const urlParams = new URLSearchParams(window.location.search);
  const openParam = urlParams.get("open");
  const errorParam = urlParams.get("error");

  if (openParam) {
    formWrapper.classList.add("active");

    if (openParam === "login") {
      loginForm.classList.add("active");
      registerForm.classList.remove("active");
    } else if (openParam === "register") {
      registerForm.classList.add("active");
      loginForm.classList.remove("active");
    }
  }

  if (errorParam) {
    console.log("Error:", errorParam);
  }
});

// Print Download Function
const printBtn = document.querySelector(".print-btn");
function printMe() {
  window.print();
}
if (printBtn) {
  printBtn.addEventListener("click", printMe);
}

// post popup
const postPopupWrapper = document.querySelector(".post-popup-wrapper");
const postBtn = document.querySelector("#post");
if (postPopupWrapper && postBtn) {
  const postPopup = document.querySelector(".post-popup");
  const postPopupCloseBtns = document.querySelectorAll(".post-popup-close");
  postBtn.addEventListener("click", () => {
    postPopupWrapper.classList.add("active");
  });
  postPopupCloseBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      postPopupWrapper.classList.remove("active");
    });
  });
  postPopup.addEventListener("click", (e) => {
    e.stopPropagation();
  });

  // post validate
  const fileInput = document.querySelector("#fileUpload");
  const validateFile = () => {
    const filePath = fileInput.value;
    const allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
    if (!allowedExtensions.exec(filePath)) {
      alert("Invalid file type! Please upload an image only.");
      fileInput.value = "";
      return false;
    }
    const fileSize = input.files[0].size;
    const maxSize = 5 * 1024 * 1024;
    if (fileSize > maxSize) {
      alert("File is too big! Please select an image under 5MB.");
      input.value = "";
      return false;
    }
  };
  fileInput.addEventListener("change", validateFile);
} else if (postBtn) {
  postBtn.addEventListener("click", () => {
    formWrapper.classList.add("active");
  });
}

function acceptCookies() {
  fetch("api/storeCookieConsent.php", {
    method: "POST",
  }).then(() => {
    document.getElementById("cookie-banner").style.display = "none";
    let d = new Date();
    d.setTime(d.getTime() + 30 * 24 * 60 * 60 * 1000);
    document.cookie =
      "foodfusion_cookies=accepted; expires=" + d.toUTCString() + "; path=/";
  });
}

if (document.querySelector("#acceptCookiesBtn")) {
  acceptCookiesBtn.addEventListener("click", acceptCookies);
  rejectCookiesBtn.addEventListener("click", () => {
    document.querySelector("#cookie-banner").style.display = "none";
  });
}

const showToast = (msg, status = "success") => {
  const toast = document.querySelector("#toast");
  toast.classList.toggle(status);
  toast.classList.add("show");
  toast.textContent = msg;
  setTimeout(() => {
    toast.classList.remove("show");
  }, 3000);
};
document.addEventListener("DOMContentLoaded", () => {
  const urlParams = new URLSearchParams(window.location.search);
  const status = urlParams.get("status");

  const messages = {
    posted: "Successfully posted to community!",
    shared: "Successfully shared to community!",
    sent: "Enquiry submitted successfully!",
    rated: "Your rating has been updated!",
    success: "Action completed successfully!",
    error: "Something went wrong! Please try again.",
  };

  if (status && messages[status]) {
    if (typeof showToast === "function") {
      showToast(messages[status], status === "error" ? "error" : "success");
    } else {
      setTimeout(() => {
        if (typeof showToast === "function")
          showToast(messages[status], status);
      }, 500);
    }
  }
});

// Clean up auth errors from URL after 2 seconds
if (window.location.search.includes('error=')) {
    setTimeout(() => {
        const url = new URL(window.location);
        url.searchParams.delete('error');
        url.searchParams.delete('open');
        window.history.replaceState({}, document.title, url.pathname);
    }, 2000);
}