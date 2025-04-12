<div class="container d-flex justify-content-center">
      <div class="register-container custom-register box">
        <h3 class="text-center display-6 font-weight-bold">Register</h3>
        <br />
        <form id="registerForm">
          <div class="mb-2">
            <input
              type="text"
              class="form-control"
              placeholder="Name"
              required
            />
          </div>
          <div class="mb-2">
            <input
              type="text"
              class="form-control"
              placeholder="Last Name"
              required
            />
          </div>
          <div class="mb-2">
            <input
              type="text"
              class="form-control"
              placeholder="Phone"
              required
            />
          </div>
          <div class="mb-2">
            <input
              type="email"
              class="form-control"
              placeholder="Email"
              required
            />
          </div>
          <div class="mb-2 password-container">
            <input
              type="password"
              class="form-control"
              id="password"
              placeholder="Password"
              required
            />
            <i class="bi bi-eye-slash" id="togglePassword"></i>
          </div>
          <div class="mb-2 password-container">
            <input
              type="password"
              class="form-control"
              id="confirmPassword"
              placeholder="Enter Password again"
              required
            />
            <i class="bi bi-eye-slash" id="togglePassword"></i>
          </div>
          <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-primary w-100 sign-in-color">
              Sign in
            </button>
          </div>
        </form>
        <div class="text-center mt-3">
          Already have an account?
          <a href="#" class="fw-bold text-decoration-none text-dark">Login</a>
        </div>
      </div>
    </div>
