<?php
require_once 'dbc-inc.php';
require_once 'functions-inc.php';

session_start();

if ($_POST) {

  $umak_id = $_POST["umak_id"];
  $course = $_POST["course"];
  $password = $_POST["password"];
  $re_password = $_POST["re_password"];
  $name = $_POST["name"];
  $contact = $_POST["contact"];
  $email = $_POST["email"];
  $id_pic = file_get_contents($_FILES["id_pic"]["tmp_name"]);

  // Capitalize first letter of each word
  $name = ucwords($name, " ");

  // Save all Post Data to Session Variable
  $_SESSION['signup_data'] = $_POST;

  // PUT ALL ERRORS ON THE URL AND REMOVE IT IN THE FILLED
  $errors = array();

  // UMAK ID is taken
  if (idExists($conn, $umak_id)) {
    $errors[] = 'id_tkn';
  }

  // PASSWORDS does not matched
  if ($password != $re_password) {
    $errors[] = 'pw_match';
  }

  // EMAIL is taken
  if (emailExists($conn, $email)) {
    $errors[] = 'email_tkn';
  }

  // NAME is taken
  if (nameExists($conn, $name)) {
    $errors[] = 'name_tkn';
  }

  if (($_FILES["id_pic"]["size"]) > 1000000) {
    $errors[] = 'max_img_size';
  }

  if (empty($errors)) {
    unset($_SESSION['signup_data']);
    unset($_SESSION['signup_error']);
    createAccount($conn, $umak_id, $password, $course, $name, $contact, $email, $id_pic, 0);
    $_SESSION['signup_success'] = 'true';
  }
  else {
    $_SESSION['signup_error'] = $errors;
  }

  header("location: ../signup.php");
  exit();
}
