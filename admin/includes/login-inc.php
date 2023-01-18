<?php
require_once 'dbc-inc.php';
require_once 'functions-inc.php';

session_start();


if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $_SESSION['login_data'] = $_POST;

  $admin = adminExists($conn, $username);

  // Check if user exists
  if ($admin == false) {
    $_SESSION['login_error'] = 'no_user';
  }
  else {
    // Compare passwords
    $hashedPass = $admin["password"];
    if (!password_verify($password, $hashedPass)) {
      $_SESSION['login_error'] = 'wrong_pass';
    }
    else {
      // Log in user
      $_SESSION["admin_id"] = $admin["admin_id"];
      header("location: ../index.php?page=1");
      unset($_SESSION['login_error']);
      unset($_SESSION['login_data']);
      exit();
    }
  }

  header("location: ../login.php?login=failed");
  exit();
}
