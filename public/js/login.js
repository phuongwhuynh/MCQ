document
.getElementById("togglePassword")
.addEventListener("click", function () {
  const passwordField = document.getElementById("password");
  if (passwordField.type === "password") {
    passwordField.type = "text";
    this.classList.replace("fa-eye", "fa-eye-slash");
  } else {
    passwordField.type = "password";
    this.classList.replace("fa-eye-slash", "fa-eye");
  }
});
