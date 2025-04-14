<html lang="en">
  <head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCQ </title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <link rel="stylesheet" href="public/css/layout.css">
    <?php 
        $cssWebPath = "public/css/$page.css"; 
        $jsPath="public/js/$page.js";
    ?>
    <link rel="stylesheet" href="<?php echo $cssWebPath; ?>">
    <script src="<?php echo $jsPath?>" defer></script>
    <script src="public/js/main.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  </head>
  <body class="bg-light">
  <div class="container-fluid">
    <div class="row" style="min-height:100vh;">
      <!-- Sidebar/Navbar -->
      <nav class="col-md-3 col-lg-2 d-md-block bg-white sidebar navbar navbar-expand-md navbar-light p-0">
      <button
        class="navbar-toggler my-2 ms-2"
        type="button"
        data-toggle="collapse"
        data-target="#sidebarMenu"
        aria-controls="sidebarMenu"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>



        <div class="collapse navbar-collapse" id="sidebarMenu">
          <div
            class="sidebar-sticky d-flex flex-column align-items-center text-center mx-auto pt-3 px-3"
            style="overflow-y: auto; max-height: 100vh;"
          >
            <p class="text-muted">Teacher</p>
            <img
              alt="Profile picture of Nguyen Van B"
              class="rounded-circle img-fluid"
              style="max-height: 200px;"
              src="https://i.pinimg.com/736x/c6/70/2b/c6702b4c64ff1910d3fc220248cc1907.jpg"
            />
            <h5 class="mt-3">Nguyen Van B</h5>

            <ul class="nav flex-column mt-4 w-100">
              <li class="nav-item">
                <a class="nav-link <?php echo ($page == 'home_admin') ? 'text-primary' : 'text-dark'; ?> text-center" href="index.php?page=home">
                  <i class="fas fa-home mr-2"></i> HOME
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php echo ($page == 'question_admin') ? 'text-primary' : 'text-dark'; ?> text-center" href="index.php?page=question">
                  <i class="fas fa-pen mr-2"></i> CREATE QUESTION
                </a>
              </li>
            
              <li class="nav-item">
                <a class="nav-link <?php echo ($page == 'test_admin') ? 'text-primary' : 'text-dark'; ?> text-center" href="index.php?page=test">
                <i class="fas fa-pen mr-2"></i> CREATE TEST
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php echo ($page == 'manage_admin') ? 'text-primary' : 'text-dark'; ?> text-center" href="index.php?page=manage">
                  <i class="fas fa-cog mr-2"></i> MANAGE
                </a>
              </li>
            </ul>

            <button class="btn btn-lg btn-outline-secondary w-100 my-3" onclick="logout()">
              Logout
            </button>
          </div>
        </div>
      </nav>

      <!-- Main Content -->
      <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
        <?php include($content); ?>
      </main>
    </div>
  </div>
  <style>

  </style>

</body>
</html>