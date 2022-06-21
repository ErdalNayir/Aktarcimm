var inputEmail = document.getElementById("inputEmail");
var inputPassword = document.getElementById("inputPassword");

var registerName = document.getElementById("registerName");
var registerSurname = document.getElementById("registerSurname");
var registerEmail = document.getElementById("registerEmail");
var registerPassword = document.getElementById("registerPassword");
var registerPasswordAgain = document.getElementById("registerPasswordAgain");
var userAgreement = document.getElementById("userAgreement");

function deleteText() {
  inputEmail.textContent = "";
  inputPassword.textContent = "";
  registerName.textContent = "";
  registerSurname.textContent = "";
  registerEmail.textContent = "";
  registerPassword.textContent = "";
  registerPasswordAgain.textContent = "";
  userAgreement.checked = false;
}

function redirectLoginRegister() {
  location.href = "loginRegisterPage.php";
}
