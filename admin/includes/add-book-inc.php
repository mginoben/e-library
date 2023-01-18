<?php
require_once 'dbc-inc.php';
require_once 'functions-inc.php';

session_start();

if (isset($_POST['submit'])) {
  $req_id = $_POST['req_id'];
  $title = ucfirst($_POST['title']);
  $subtitle = ucfirst($_POST['subtitle']);
  $author = ucfirst($_POST['author']);
  $isbn = $_POST['isbn'];
  $genre = ucwords($_POST['genre']);
  $publication_year = $_POST['publication_year'];
  $page_count = $_POST['page_count'];
  $cover = $_POST['cover'];
  $description = ucfirst($_POST['description']);

  // Error Handling
  $errors = [];
  if (!getimagesize($cover)) {
    $errors[] = 'url_inv';
  }
  if (bookExists($conn, $title, $isbn)) {
    $errors[] = 'book_tkn';
  }
  if (!empty($errors)) {
    $_SESSION['add_book_errors'] = $errors;
  }

  // Check if url is an image
  if (empty($errors)) {
    addBook($conn, $req_id, $title, $subtitle, $author, $isbn, $genre, $publication_year, $page_count, $image_url, $description);
    $_SESSION['add_book'] = "success";
  }
  else {
    $_SESSION['add_book_data'] = $_POST;
    $_SESSION['add_book'] = "failed";
    $_SESSION['request_id'] = $req_id;
  }

  header("location: ../requests.php");
}
