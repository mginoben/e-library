<?php

require_once 'dbc-inc.php';
require_once 'functions-inc.php';

session_start();

if (isset($_POST["submit"])) {
  $umak_id = $_SESSION["umak_id"];
  $title = $_POST["title"];
  $author = $_POST["author"];
  $publication_year = $_POST["publication_year"];
  $isbn = $_POST["isbn"];
  $url = $_POST["book_web"];
  $_SESSION['requests_data'] = $_POST;

  if (!bookExists($conn, $title, $isbn)) {
    requestBook($conn, $umak_id, $title, $author, $publication_year, $isbn, $url);
    $_SESSION['request'] = 'success';
  }
  else {
    $_SESSION['request_error'] = 'book_exists';
  }

  header("location: ../request.php");
  exit();
}
