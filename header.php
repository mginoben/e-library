<?php
  session_start();

  // Current Active Page
  $activePage = basename($_SERVER['PHP_SELF'], ".php");

  // Check if a nav-item is the current page
  function currentPage($page) {
    $activePage = basename($_SERVER['PHP_SELF'], ".php");
    return ($activePage == $page) ? 'active disabled' : '';
  }

  $page = basename($_SERVER['PHP_SELF'], ".php");

  // Redirect if user not logged in
  if (!isset($_SESSION["umak_id"]) && ($page == 'profile' || $page == 'request')) {
    header('location: login.php');
    exit();
  }
  // Redirect if user logged in
  if (isset($_SESSION["umak_id"]) && ($page == 'login' || $page == 'signup')) {
    header('location: index.php');
    exit();
  }
?>

<header>
  <nav class="navbar navbar-expand-lg p-0">
    <div class="container-fluid px-2">
      <img class="brand-icon img-fluid" src="icons/brand_icon.png" alt="">
      <a class="navbar-brand brand-title px-3" href="index.php">UMAK<span class="brand-second-title"> E-LIBRARY</span></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span><i class="fa-solid fa-caret-down"></i></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end me-0 me-md-4 align-items-center" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto mb-2 mb-lg-0 d-flex align-items-center">
          <li class="nav-item">
            <a class="nav-link <?php echo currentPage('index'); ?>" href="index.php">HOME</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo currentPage('browse'); ?>" href="browse.php?page=1">BROWSE</a>
          </li>
          <li class="nav-item <?php echo (!isset($_SESSION['umak_id']))? 'd-none': ''; ?>">
            <a class="nav-link <?php echo currentPage('request'); ?>" href="request.php">REQUEST</a>
          </li>
          <li class="nav-item <?php echo (!isset($_SESSION['umak_id']))? 'd-none': ''; ?>">
            <a class="nav-link <?php echo currentPage('profile'); ?>" href="profile.php">PROFILE</a>
          </li>
          <li class="nav-item <?php echo (isset($_SESSION['umak_id']))? 'd-none': ''; ?>">
            <a class="nav-link <?php echo currentPage('login'); ?>" href="login.php">LOGIN</a>
          </li>
          <li class="nav-item <?php echo (isset($_SESSION['umak_id']))? 'd-none': ''; ?>">
            <a class="nav-link <?php echo currentPage('signup'); ?>" href="signup.php">SIGN UP</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo currentPage('about'); ?>" href="about.php">ABOUT</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>
