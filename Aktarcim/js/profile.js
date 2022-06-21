var changePassword = document.getElementById("changePassword");
var overlayBlur = document.getElementById("overlayBlur");

function openPasswordPanel() {
  changePassword.style.display = "flex";
  overlayBlur.style.display = "block";
}

function closePasswordPanel() {
  changePassword.style.display = "none";
  overlayBlur.style.display = "none";
}
