<?php
require_once 'dbc-inc.php';
require_once 'functions-inc.php';

session_start();

if (isset($_POST['search_btn'])) {

  $_SESSION['books_data'] = $_POST;

  header("location: ../books.php?page=1");
  exit();
}
