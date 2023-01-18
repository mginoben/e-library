<?php
  session_start();

  // Check if a nav-item is the current page
  function currentPage($page) {
    $activePage = basename($_SERVER['PHP_SELF'], ".php");
    return ($activePage == $page) ? 'active disabled' : '';
  }

  $page = basename($_SERVER['PHP_SELF'], ".php");

  // Redirect if user not logged in
  if (!isset($_SESSION["admin_id"]) && $page != 'login') {
    header('location: login.php');
    exit();
  }
  // Redirect if user logged in
  if (isset($_SESSION["admin_id"]) && $page == 'login') {
    header('location: index.php');
    exit();
  }

?>

<header>
  <nav class="navbar navbar-expand-lg p-0">
    <div class="container-fluid px-2 d-flex <?php echo (!isset($_SESSION["admin_id"])) ? 'justify-content-start' : ''; ?>">
      <img class="brand-icon img-fluid d-none d-sm-block" src="../icons/brand_icon.png" alt="">
      <a class="navbar-brand brand-title px-3" href="index.php">UMAK<span class="brand-second-title"> E-LIBRARY </span><span class="brand-third-title">Admin</span></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span><i class="fa-solid fa-caret-down"></i></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end align-items-center" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto mb-2 mb-lg-0 d-flex align-items-center <?php echo (!isset($_SESSION["admin_id"])) ? 'd-none' : 'd-block'; ?>">
          <li class="nav-item">
            <a class="nav-link <?php echo currentPage("index"); ?>" href="index.php?page=1">ACCOUNTS</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo currentPage("books"); ?>" href="books.php?page=1">BOOKS</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo currentPage("requests"); ?>" href="requests.php?page=1">REQUESTS</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo currentPage("transactions"); ?>" href="transactions.php?page=1">TRANSACTIONS</a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-logout" href="includes/logout-inc.php">LOG OUT</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>
