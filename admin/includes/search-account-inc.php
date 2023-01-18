<?php
require_once 'dbc-inc.php';
require_once 'functions-inc.php';

session_start();

if (isset($_POST['search_btn'])) {
  $umak_id = $_POST['umak_id'];
  $name = $_POST['name'];
  $email = $_POST['email'];
  $contact = $_POST['contact'];
  $course = $_POST['course'];
  $date_created = $_POST['date_created'];
  $status = $_POST['status'];
  $page = $_POST['page'];

  $_SESSION['search_data'] = $_POST;

  header("location: ../index.php?page=1");
  exit();
}
