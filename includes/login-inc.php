<?php
require_once 'dbc-inc.php';
require_once 'functions-inc.php';

session_start();

if (isset($_POST["submit"])) {

  $umak_id = $_POST["umak_id"];
  $password = $_POST["password"];

  $_SESSION['login_data'] = $_POST;
  $_SESSION['login_error'] = '';

  // Check Account Status
  $_SESSION['account_status'] = checkAccountStatus($conn, $umak_id);

  if ($_SESSION['account_status'] == 'None') { // UMAK ID not found
    $_SESSION['login_error'] = 'no_id';
  }
  elseif ($_SESSION['account_status'] == 'Rejected') {
    $account = getAccount($conn, $umak_id);
    $hashedPass = $account['password'];
    // Get Reject Reason if passwords matched
    if (password_verify($password, $hashedPass)) {
      $_SESSION['reject_reason'] = getRejectReason($conn, $umak_id);
    }
    // Error if Passwords dont matched
    else {
      $_SESSION['login_error'] = 'wrong_pass';
    }
  }
  elseif ($_SESSION['account_status'] == 'Pending') {
    $account = getAccount($conn, $umak_id);
    $hashedPass = $account['password'];
    // Error if Passwords dont matched
    if (!password_verify($password, $hashedPass)) {
      $_SESSION['login_error'] = 'wrong_pass';
    }
  }
  elseif ($_SESSION['account_status'] == 'Verified') {
    // Get Account
    $account = getAccount($conn, $umak_id);
    // Compare password
    $hashedPass = $account['password'];
    if (password_verify($password, $hashedPass)) {
      // Remove all session variables
      unset($_SESSION['account_status']);
      unset($_SESSION['login_error']);

      $_SESSION['umak_id'] = $umak_id;
      $_SESSION['greet_alert'] = 'display';
      header("location: ../index.php");
      exit();
    }
    else {
      $_SESSION['login_error'] = 'wrong_pass';
    }
  }

  header("location: ../login.php?login=failed");
  exit();
}
