var overlayBlur = document.getElementById("overlayBlur");
var getMoneyPanel = document.getElementById("getMoneyPanel");
var putProductPanel = document.getElementById("putProductPanel");

function openUpdateProductPanel() {
  putProductPanel.style.display = "flex";
  overlayBlur.style.display = "block";
}

function closeUpdateProductPanel() {
  putProductPanel.style.display = "none";
  overlayBlur.style.display = "none";
}
