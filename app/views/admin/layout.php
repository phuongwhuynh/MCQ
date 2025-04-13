<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Dashboard</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
      rel="stylesheet"
    />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>
  <body class="bg-light">
    <div class="container-fluid">
      <div class="row">
        <!-- Sidebar/Navbar -->
        <nav
          class="col-md-3 col-lg-2 d-md-block bg-white sidebar navbar navbar-expand-md navbar-light"
        >
          <button
            aria-controls="sidebarMenu"
            aria-expanded="false"
            aria-label="Toggle navigation"
            class="navbar-toggler"
            data-target="#sidebarMenu"
            data-toggle="collapse"
            type="button"
          >
            <span class="navbar-toggler-icon"> </span>
          </button>
          <div class="collapse navbar-collapse" id="sidebarMenu">
            <div class="sidebar-sticky pt-3 text-center">
              <p class="text-muted">Teacher</p>
              <img
                alt="Profile picture of Nguyen Van B"
                class="rounded-circle img-responsive"
                style="max-height: 300px"
                src="https://i.pinimg.com/736x/c6/70/2b/c6702b4c64ff1910d3fc220248cc1907.jpg"
              />
              <h5>Nguyen Van B</h5>
              <ul class="nav flex-column mt-4">
                <li class="nav-item">
                  <a class="nav-link active text-primary" href="#">
                    <i class="fas fa-home mr-2"> </i>
                    OVERVIEW
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-dark" href="#">
                    <i class="fas fa-pen mr-2"> </i>
                    MANAGE
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-dark" href="#">
                    <i class="fas fa-cog mr-2"> </i>
                    SETTINGS
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </nav>
        <!-- Main Content -->
        <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4" role="main">
          <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
          >
            <h1 class="h2">Welcome Mr. B</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              <div class="btn-group">
                <i class="fas fa-envelope text-primary pt-4"> </i>
                <div class="d-flex align-items-center">
                  <img
                    alt="Profile picture of Nguyen Van B"
                    class="rounded-circle img-responsive mx-2"
                    style="max-height: 50px"
                    src="https://i.pinimg.com/736x/c6/70/2b/c6702b4c64ff1910d3fc220248cc1907.jpg"
                  />
                  <span class="p-1"> Nguyen Van B </span>
                </div>
                <button class="btn btn-sm btn-outline-secondary pl-3">
                  logout
                </button>
              </div>
            </div>
          </div>
          <div class="card mb-4">
            <div class="card-body">
              <h5 class="card-title">Activity</h5>
              <p class="card-text text-muted">Test taken</p>
              <canvas id="activityChart"> </canvas>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Statistics</h5>
              <p class="card-text text-muted">TIME REMAINING</p>
              <canvas id="timeRemainingChart"> </canvas>
            </div>
          </div>
        </main>
      </div>
    </div>
    <script>
      const activityCtx = document
        .getElementById("activityChart")
        .getContext("2d");
      const activityChart = new Chart(activityCtx, {
        type: "bar",
        data: {
          labels: [
            "TEST",
            "M1003",
            "M1005",
            "H1004",
            "P1001",
            "P3004",
            "P1004",
            "P3001",
            "P3008",
            "P3009",
          ],
          datasets: [
            {
              label: "Test taken",
              data: [10, 20, 60, 30, 60, 10, 60, 80, 60, 80],
              backgroundColor: "rgba(99, 102, 241, 0.5)",
              borderColor: "rgba(99, 102, 241, 1)",
              borderWidth: 1,
            },
          ],
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
              max: 100,
            },
          },
        },
      });

      const timeRemainingCtx = document
        .getElementById("timeRemainingChart")
        .getContext("2d");
      const timeRemainingChart = new Chart(timeRemainingCtx, {
        type: "bar",
        data: {
          labels: ["M1003", "M1005", "H1004", "P1001", "P3004"],
          datasets: [
            {
              label: "Time Remaining",
              data: [20, 36, 20, 36, 85],
              backgroundColor: "rgba(99, 102, 241, 0.5)",
              borderColor: "rgba(99, 102, 241, 1)",
              borderWidth: 1,
            },
          ],
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
              max: 100,
            },
          },
        },
      });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  </body>
</html>
