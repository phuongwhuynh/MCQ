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

function login(event) {
  console.log(123456789)
  event.preventDefault();

  const formData = new FormData(event.target);
  formData.append("ajax", 1);
  formData.append("controller", "user");
  formData.append("action", "loginAttempt");

  const controller = new AbortController();
  const timeoutId = setTimeout(() => controller.abort(), 5000); // 5 seconds timeout
  fetch("index.php", {
    method: "POST",
    body: formData,
    signal: controller.signal
  })
    .then(response => {
      clearTimeout(timeoutId);
      if (!response.ok) {
        throw new Error("Request failed with status: " + response.status);
      }
      return response.json();
    })
    .then(data => {
      if (data.success) {
        window.location.href = "index.php?page=home";
      } else {
        alert(data.message);
      }
    })
    .catch(error => {
      if (error.name === "AbortError") {
        alert("Request timed out. Please try again.");
      } else {
        alert(error.message || "Request failed. Please try again.");
      }
    });
}
