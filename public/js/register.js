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

document
.getElementById("registerForm")
.addEventListener("submit", function (event) {
  event.preventDefault();
  const password = document.getElementById("password").value;
  const confirmPassword =document.getElementById("confirmPassword").value;
  if (password !== confirmPassword) {
    alert("Passwords do not match!");
    return;
  }
  if (!validatePassword(password)) {
    alert("Password must be at least 8 characters long, including one uppercase letter, one lowercase letter, one number, and one special character.");
    return;
  }
  const formData = new FormData(event.target);
  formData.append("ajax", 1);
  formData.append("controller", "user");
  formData.append("action", "registerAttempt");
  
  const controller = new AbortController();
  
  const timeoutId = setTimeout(() => controller.abort(), 5000); 
  
  fetch("index.php", {
    method: "POST",
    body: formData,
    signal: controller.signal, 
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
        alert("Registration successful. Please log in again. Redirecting...");
        setTimeout(() => {
          window.location.href = "index.php?page=login";
        }, 1000);
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
  });

function validatePassword(password) {
  const minLength = 8;
  const hasUpperCase = /[A-Z]/.test(password);
  const hasLowerCase = /[a-z]/.test(password);
  const hasNumber = /[0-9]/.test(password);
  const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);

  return password.length >= minLength && hasUpperCase && hasLowerCase && hasNumber && hasSpecialChar;
}