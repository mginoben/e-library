<?php
require_once 'dbc-inc.php';
require_once 'functions-inc.php';

session_start();

if (isset($_POST['save'])) {
  echo $_POST["new_contact"];
  $umak_id = $_SESSION['umak_id'];
  $new_contact = $_POST["new_contact"];
  $new_email = $_POST["new_email"];
  $new_profile_pic = $_FILES["new_profile_pic"]["tmp_name"];
  $account = getAccount($conn, $_SESSION['umak_id']);

  $_SESSION['edit_data'] = $_POST;

  // Handling Empty Inputs

  // Profile Picture
  if (!empty($new_profile_pic)) {
    $new_profile_pic = file_get_contents($new_profile_pic);
  }
  // Contact
  if (empty($new_contact)) {
    $new_contact = $account['contact'];
  }
  // Email
  if (empty($new_email)) {
    $new_email = $account['email'];
  }

  // Error Handling
  $errors = array();

  // Empty Fields
  if(empty($_POST["new_contact"]) && empty($_POST["new_email"]) && empty($new_profile_pic)){
    $errors[] = 'input_empty';
  }
  // Max Image Size 1 MB
  if (isset($new_profile_pic) && ($_FILES["new_profile_pic"]["size"]) > 1000000) {
    $errors[] = 'max_img_size';
  }
  // Invalid Email
  if (invalidEmail($new_email)) {
    $errors[] = 'email_inv';
  }
  // Email Taken
  elseif ($account['email'] != $new_email && emailExists($conn, $new_email)) {
    $errors[] = 'email_tkn';
  }
  // Contact Invalid
  if (invalidContact($new_contact)) {
  $errors[] = 'con_inv';
  }

  if (empty($errors)) {
    changeUserInfo($conn, $umak_id, $new_contact, $new_email, $new_profile_pic);
    header("location: ../profile.php?edit=success" . $umak_id . $wow);
    unset($_SESSION['edit_data']);
    exit();
  }
  else {
    $_SESSION['edit_profile_errors'] = $errors;
  }

  header("location: ../profile.php?");
  exit();
}
