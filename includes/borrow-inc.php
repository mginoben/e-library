<?php
session_start();
require_once 'dbc-inc.php';
require_once 'functions-inc.php';

if (isset($_POST['borrow-btn'])) {
  $umak_id = $_POST['umak_id'];
  $book_id = $_POST['book_id'];
  $current_page = $_POST['url'];

  if (!bookLimitReached($conn, $umak_id)) {
    borrowBook($conn, $umak_id, $book_id, 1);
    $_SESSION["borrow_result"] = 'success';
  }
  else {
    $_SESSION["borrow_result"] = 'failed';
  }

  header("location: ../".$current_page);
  exit();
}
