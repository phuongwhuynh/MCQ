<div class="container d-flex justify-content-center">
  <script src="https://accounts.google.com/gsi/client" async defer></script>
  <script src="https://cdn.jsdelivr.net/npm/jwt-decode@3.1.2/build/jwt-decode.min.js"></script>
  <div class="login-container custom-width box">
    <h1 class="text-center display-6 font-weight-bold">Sign In</h1>
    <br />
    <form onsubmit="login(event)">

      <div class="mb-3">
        <input type="text" class="form-control" name="username" placeholder="username" required />
      </div>
      <div class="mb-3 password-container">
        <input type="password" class="form-control" id="password" name="password" placeholder="Password"
          required />
        <i class="bi bi-eye-slash" id="togglePassword"></i>
      </div>

      <div class="d-flex justify-content-center">
        <button type="submit" class="btn btn-primary w-100 sign-in-color">
          Sign in
        </button>
      </div>
    </form>

    <div class="divider">

    </div>

    <div class="d-flex justify-content-center gap-3">
      <div class="" id="googleSignInButton"></div>
    </div>
    <div class="text-center mt-3">
      Don't have an account?
      <a href="/MCQ/register" class="fw-bold text-decoration-none text-dark">Register Now</a>
    </div>
  </div>

  <script>
    function handleCredentialResponse(response) {
      const userObject = jwt_decode(response.credential);
      console.log(userObject);
      // Gửi thông tin userObject (bao gồm email, google_id, tên...) đến server của bạn
      // ví dụ sử dụng fetch API hoặc Ajax để gửi data đến server
      fetch('index.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            ajax: 1,
            controller: "user",
            action: "googleLogin",
            google_id: userObject.sub,
            email: userObject.email,
            name: userObject.name,
            role: "user"
          })
        })
        .then(response => {
          if (!response.ok) {
            throw new Error("Request failed with status: " + response.status);
          }
          return response.json();
        })
        .then(data => {
          // Xử lý kết quả trả về từ server
          console.log(data);
          window.location.href = "/MCQ/home";
        });
    }

    window.onload = function() {
      google.accounts.id.initialize({
        client_id: "214663455323-0muhc3kha1h813kg6kthjmthf6choh4m.apps.googleusercontent.com",
        callback: handleCredentialResponse
      });

      google.accounts.id.renderButton(
        document.getElementById("googleSignInButton"), {
          theme: "outline",
          size: "large"
        }
      );
    }
  </script>
</div>