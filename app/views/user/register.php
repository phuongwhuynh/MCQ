<div class="container d-flex flex-column align-items-center justify-content-center">
      <div class="register-container custom-register">
        <h3 class="text-center display- font-weight-bold">Register</h3>
        <div class="password-requirements">
          <h4>Password Requirements:</h4>
          <ul>
            <li>At least 8 characters</li>
            <li>At least one uppercase letter (A-Z)</li>
            <li>At least one lowercase letter (a-z)</li>
            <li>At least one number (0-9)</li>
            <li>At least one special character (e.g., !@#$%^&*)</li>
          </ul>
        </div>
        <form id="registerForm">
          <div class="mb-2">
            <input
              type="text"
              class="form-control"
              id="name"
              name="name"
              placeholder="Name"
              required
            />
          </div>
          <div class="mb-2">
            <input
              type="text"
              class="form-control"
              id="lastName"
              name="lastName"
              placeholder="Last Name"
              required
            />
          </div>

          <div class="mb-2">
            <input
              type="email"
              class="form-control"
              id="email"
              name="email"
              placeholder="Email"
              required
            />
          </div>
          <div class="mb-2">
            <input
              type="text"
              class="form-control"
              id="username"
              name="username"
              placeholder="Username"
              required
            /> 
          </div>
          <div class="mb-2 password-container">
            <input
              type="password"
              class="form-control"
              id="password"
              name="password"
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
            <i class="bi bi-eye-slash" id="toggleConfirmPassword"></i>
          </div>
          <div class="mb-4 text-center">
            <label class="form-label fw-bold d-block m-0">Register As:</label> 
            <div class="d-flex justify-content-center m-0">
              <div class="btn-group w-100" style="max-width: 400px;" role="group" aria-label="Role selection">
                <input type="radio" class="btn-check" name="role" id="userRole" value="user" autocomplete="off" checked />
                <label class="btn btn-outline-primary w-50" for="userRole">User</label>

                <input type="radio" class="btn-check" name="role" id="adminRole" value="admin" autocomplete="off" />
                <label class="btn btn-outline-primary w-50" for="adminRole">Admin</label>
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-primary w-100 sign-in-color">
              Register
            </button>
          </div>
        </form>
        <div class="text-center mt-3">
          Already have an account?
          <a href="index.php?page=login" class="fw-bold text-decoration-none text-dark">Login</a>
        </div>
      </div>
</div>

