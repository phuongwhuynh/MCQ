<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MCQ - <?php echo ucfirst($page); ?></title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <script
    src="https://kit.fontawesome.com/a076d05399.js"
    crossorigin="anonymous"
  ></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="public/css/layout.css">
  <?php 
    $cssWebPath = "public/css/$page.css"; 
    $jsPath="public/js/$page.js";
  ?>
  <link rel="stylesheet" href="<?php echo $cssWebPath; ?>">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="<?php echo $jsPath?>" defer></script>
</head>
<body>
<header>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand text-primary fw-bold" href="#">iExam</a>
    <button
      class="navbar-toggler"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbarNav"
    >
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php?page=home">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?page=about">About</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?page=resources">Resources</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?page=contact">Contact</a></li>
      </ul>
      <a href="index.php?page=register" class="btn btn-primary ms-3">Get Started</a>
    </div>
  </div>
</nav>
</header>
<main>
  <?php include($content); ?>
</main>
<footer class="footer text-center">
  <div class="container">
    <p>© iExam 2025</p>
    <div class="social-icons">
      <span>Follow us:</span>
      <a href="#"><i class="fab fa-facebook-f"></i></a>
      <a href="#"><i class="fab fa-twitter"></i></a>
      <a href="#"><i class="fab fa-instagram"></i></a>
    </div>
  </div>
</footer>

</body>
</html>

