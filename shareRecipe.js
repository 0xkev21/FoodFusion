function openShareModal() {
  document.getElementById("share-modal").style.display = "block";
}

function closeShareModal() {
  document.getElementById("share-modal").style.display = "none";
}

window.onclick = function (event) {
  let modal = document.getElementById("share-modal");
  if (event.target == modal) {
    modal.style.display = "none";
  }
};
