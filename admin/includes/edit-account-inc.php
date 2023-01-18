<?php
require_once 'dbc-inc.php';
require_once 'functions-inc.php';

session_start();

if (isset($_POST['save'])) {
  // If field is empty then get value from database
  $umak_id = $_POST['umak_id'];
  $account = getAccount($conn, $umak_id);
  $new_name = empty($_POST["new_name"]) ? $account['name'] : ucwords($_POST["new_name"], " ");
  $new_contact = empty($_POST["new_contact"]) ? $account['contact'] : $_POST["new_contact"];
  $new_email = empty($_POST["new_email"]) ? $account['email'] : $_POST["new_email"];
  $new_course = $_POST["new_course"];

  // Error handling
  $errors = array();

  // Name Validation
  if (nameExists($conn, $umak_id, $new_name)) {
    $errors[] = 'name_tkn';
  }

  // Email Validation
  if (emailExists($conn, $umak_id, $new_email)) {
    $errors[] = 'email_tkn';
  }

  // If All Fields are Good, Change Student Info
  if (empty($errors)) {
    editStudentInfo($conn, $new_name, $new_course, $new_contact, $new_email, $umak_id);
  }
  else {
    $_SESSION['adit_account_errors'] = $errors;
    $_SESSION['edit_account_data'] = $_POST;
  }

  header("location: ../index.php?page=1");
  exit();
}
