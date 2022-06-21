var registerContainer = document.getElementById("registerContainer");
var overlayBlur = document.getElementById("overlayBlur");

function openRegister() {
  registerContainer.style.display = "flex";
  overlayBlur.style.display = "block";
}

function closeRegister() {
  registerContainer.style.display = "none";
  overlayBlur.style.display = "none";
}
