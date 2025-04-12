<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About Us - iExam</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <style>
      body {
        background-color: #f8f9fa;
        /* Light grey background */
        font-family: sans-serif;
        /* Basic clean font */
      }

      .section {
        margin: 40px 0;
        /* Spacing between sections */
        padding: 30px;
        background-color: #ffffff;
        /* White background for sections */
        border-radius: 8px;
        /* Slightly rounded corners */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        /* Subtle shadow */
      }

      .section h2 {
        margin-bottom: 20px;
        /* Space below section titles */
        color: #343a40;
        /* Darker heading color */
      }

      .feature-icon {
        font-size: 2rem;
        /* Icon size */
        color: #0d6efd;
        /* Bootstrap primary blue */
        width: 40px;
        /* Fixed width for alignment */
        text-align: center;
      }

      .team-icon {
        font-size: 1.5rem;
        /* Slightly smaller icon for team */
        color: #6c757d;
        /* Bootstrap secondary grey */
        width: 30px;
        /* Fixed width */
        text-align: center;
      }

      .map-container {
        overflow: hidden;
        padding-bottom: 56.25%;
        /* 16:9 aspect ratio */
        position: relative;
        height: 0;
      }

      .map-container iframe {
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        position: absolute;
        border: 0;
      }
    </style>
  </head>

  <body>
    <div class="container mt-5 mb-5">
      <h1 class="text-center my-4">About iExam</h1>

      <div class="section">
        <h2>Our Purpose</h2>
        <p>
          Welcome to iExam! Our mission is to provide a seamless, efficient, and
          reliable platform for students and educators to conduct and
          participate in online examinations. We aim to enhance the assessment
          experience by making it more accessible, insightful, and convenient
          for everyone involved.
        </p>
      </div>

      <div class="section">
        <h2>Platform Features</h2>
        <ul class="list-unstyled">
          <li class="d-flex align-items-center mb-3">
            <i class="feature-icon fas fa-users me-3"></i>
            <span
              >Intuitive and easy-to-use interface designed for both students
              and administrators.</span
            >
          </li>
          <li class="d-flex align-items-center mb-3">
            <i class="feature-icon fas fa-book me-3"></i>
            <span
              >Support for a wide range of question types and customizable exam
              formats.</span
            >
          </li>
          <li class="d-flex align-items-center mb-3">
            <i class="feature-icon fas fa-chart-pie me-3"></i>
            <span
              >Real-time analytics and insightful reporting for educators to
              track performance.</span
            >
          </li>
          <li class="d-flex align-items-center mb-3">
            <i class="feature-icon fas fa-shield-alt me-3"></i>
            <span
              >Secure, robust, and reliable platform focused on maintaining exam
              integrity.</span
            >
          </li>
          <li class="d-flex align-items-center mb-3">
            <i class="feature-icon fas fa-mobile-screen-button me-3"></i>
            <span
              >Accessible across various devices, enabling exams anytime,
              anywhere.</span
            >
          </li>
        </ul>
      </div>

      <div class="section">
        <h2>Meet Our Team</h2>
        <p>
          iExam is built by a passionate team of educators, developers, and
          designers committed to improving the online assessment landscape
          through technology.
        </p>
        <ul class="list-unstyled">
          <li class="d-flex align-items-center mb-3">
            <i class="team-icon fas fa-user-tie me-3"></i>
            <span><strong>Huỳnh Lan Phương</strong> - Founder & CEO </span>
          </li>
          <li class="d-flex align-items-center mb-3">
            <i class="team-icon fas fa-user-cog me-3"></i>
            <span
              ><strong>Phan Quang Nhân</strong> - Chief Technology Officer
            </span>
          </li>
          <li class="d-flex align-items-center mb-3">
            <i class="team-icon fas fa-laptop-code me-3"></i>
            <span
              ><strong>Huỳnh Nhật Quang</strong> - Head of Product Development
            </span>
          </li>
          <li class="d-flex align-items-center mb-3">
            <i class="team-icon fas fa-bullhorn me-3"></i>
            <span
              ><strong>Phan Quang Minh</strong> - Marketing & Outreach
            </span>
          </li>
        </ul>
      </div>

      <div class="section container">
        <div class="row justify-content-between">
          <div class="col-12 col-md-4">
            <h2>Get In Touch</h2>
            <p>
              Have questions, feedback, or need support? We'd love to hear from
              you!
            </p>
            <p>
              Reach out to us at:
              <a href="mailto:support@iexam-platform.com"
                >support@iexam-platform.com</a
              >
            </p>
            <img
              src="https://i.pinimg.com/736x/c6/5f/0a/c65f0a75ba033887a49c4adb4c5e4a11.jpg"
              class="img-fluid"
              alt="..."
              style="max-height: 50%"
            />
          </div>
          <div class="col-12 col-md-7">
            <h3 class="">Our Location</h3>
            <p>
              We are proudly associated with or based near Ho Chi Minh City
              University of Technology (HCMUT).
            </p>
            <div class="map-container">
              <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1959.7498120010166!2d106.66173998774035!3d10.772993548584699!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f265219560b%3A0x5c5dd04dfb3977cd!2zxJDhuqFpIGjhu41jIELDoWNoIEtob2EgVFAuSENNIC0gSOG7mWkgVHLGsOG7nW5nIEE1!5e0!3m2!1sen!2s!4v1744456910550!5m2!1sen!2s"
                width="100%"
                height="auto"
                style="border: 0"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
              ></iframe>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
