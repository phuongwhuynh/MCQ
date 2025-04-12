<div class="container d-flex justify-content-center">
      <div class="login-container custom-width box">
        <h1 class="text-center display-6 font-weight-bold">Sign In</h1>
        <br />
        <form>
        <div>
          <div class="mb-3">
            <input
              type="text"
              class="form-control"
              placeholder="Email, phone & username"
              required
            />
          </div>
          <div class="mb-3 password-container">
            <input
              type="password"
              class="form-control"
              id="password"
              placeholder="Password"
              required
            />
            <i class="bi bi-eye-slash" id="togglePassword"></i>
          </div>
          <div class="mb-3 text-end">
            <a href="#" class="text-decoration-none text-dark"
              >Forgot Password?</a
            >
          </div>
          <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-primary w-100 sign-in-color">
              Sign in
            </button>
          </div>
        </form>

        <div class="divider">
          <hr />
          <span><div class="text-center my-3">or</div></span>
          <hr />
        </div>

        <div class="d-flex justify-content-center gap-3">
          <button class="btn btn-outline-danger">
            <i class="fab fa-google"></i>
          </button>
          <button class="btn btn-outline-primary">
            <i class="fab fa-facebook-f"></i>
          </button>
          <button class="btn btn-outline-dark">
            <i class="fab fa-apple"></i>
          </button>
        </div>
        <div class="text-center mt-3">
          Don't have an account?
          <a href="index.php?page=register" class="fw-bold text-decoration-none text-dark"
            >Register Now</a
          >
        </div>
    </div>
    </div>
</div>

