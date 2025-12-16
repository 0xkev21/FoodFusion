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

// Form Popup
const currentPath = window.location.pathname;
if (currentPath === "/foodfusion/index.php" || currentPath === "/foodfusion/") {
  setTimeout(() => {
    formWrapper.classList.add("active");
  }, 10000);
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
if (postPopupWrapper) {
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
} else {
  postBtn.addEventListener("click", () => {
    formWrapper.classList.add("active");
  });
}
