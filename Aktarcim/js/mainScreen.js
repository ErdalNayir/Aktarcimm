var overlayBlur = document.getElementById("overlayBlur");
var getMoneyPanel = document.getElementById("getMoneyPanel");
var putProductPanel = document.getElementById("putProductPanel");
var buyProductPanel = document.getElementById("buyProductPanel");
var overlayBlur_nd = document.getElementById("overlayBlur_nd");
var form = document.getElementsByClassName("myForm");

function openMoneyDeposit() {
  getMoneyPanel.style.display = "flex";
  overlayBlur.style.display = "block";
}

function closeMoneyDeposit() {
  getMoneyPanel.style.display = "none";
  overlayBlur.style.display = "none";
}

function openProductDeposit() {
  putProductPanel.style.display = "flex";
  overlayBlur.style.display = "block";
}

function closeProductDeposit() {
  putProductPanel.style.display = "none";
  overlayBlur.style.display = "none";
}

function openbuyProductPanel() {
  buyProductPanel.style.display = "flex";
  overlayBlur_nd.style.display = "block";
}

function closebuyProductPanel() {
  window.location.href = "mainScreen.php";
  buyProductPanel.style.display = "none";
  overlayBlur_nd.style.display = "none";
}
