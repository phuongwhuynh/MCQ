function logout() {
    const formData = new FormData();
    formData.append("ajax", "1");
    formData.append("controller", "user");
    formData.append("action", "logOut");
  
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 5000); // 5 seconds timeout
  
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
          window.location.href = "/MCQ/home";
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
  