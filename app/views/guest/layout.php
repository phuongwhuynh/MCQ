<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MCQ - <?php echo ucfirst($page); ?></title>
  <link rel="stylesheet" href="public/css/variables.css">
  <link rel="stylesheet" href="public/css/style.css">

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
    <div class="left-header">
      <a href="index.php?page=home" class="logo">Bánh Me!</a>
    </div>
    <nav>
      <ul>
      <li><a href="index.php?page=home" class="navbar-button <?php echo ($page == 'home') ? 'active' : ''; ?>">Trang chủ</a></li>
      <li><a href="index.php?page=order" class="navbar-button <?php echo ($page == 'order') ? 'active' : ''; ?>">Đặt hàng </a></li>
      <li><a href="index.php?page=activity" class="navbar-button <?php echo ($page == 'activity') ? 'active' : ''; ?>">Hoạt động</a></li>
      <li><a href="index.php?page=history" class="navbar-button <?php echo ($page == 'history') ? 'active' : ''; ?>">Lịch sử </a></li>
      </ul>
    </nav>
    <div class="right-header"> 
        <a href="index.php?page=home">
          <img class="cart-image" src="public/images/cart.png">
        </a>
        <a href="index.php?page=home" class="login-button">Đăng nhập</a>
    </div>
  </header>
  <?php include($content); ?>

  <footer></footer>

</body>
</html>

