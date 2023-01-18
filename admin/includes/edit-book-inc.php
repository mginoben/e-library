<?php
require_once 'dbc-inc.php';
require_once 'functions-inc.php';

session_start();

if (isset($_POST['submit'])) {
  $book_id = $_POST['book_id'];
  $book = getBook($conn, $book_id);

  // Check for empty fields
  $title = (empty($_POST['title'])) ? $book['title'] : $_POST['title'];
  $subtitle = (empty($_POST['subtitle'])) ? $book['subtitle'] : $_POST['subtitle'];
  $author = (empty($_POST['author'])) ? $book['author'] : $_POST['author'];
  $isbn = (empty($_POST['isbn'])) ? $book['isbn'] : $_POST['isbn'];
  $genre = (empty($_POST['genre'])) ? $book['genre'] : $_POST['genre'];
  $year = (empty($_POST['year'])) ? $book['year'] : $_POST['year'];
  $page_count = (empty($_POST['page_count'])) ? $book['page_count'] : $_POST['page_count'];
  $cover = $_POST['cover'];
  $description = (empty($_POST['description'])) ? $book['description'] : $_POST['description'];


  // Error Handling
  $errors = [];
  if(!@getimagesize($cover) && !empty($_POST['cover'])){
      $errors[] = 'url_inv';
  }
  if (bookExists($conn, $title, $isbn) && (!empty($_POST['title']) || !empty($_POST['isbn']))) {
    $errors[] = 'book_tkn';
  }

  // Check if url is an image
  if (empty($errors)) {
    editBook($conn, $book_id, $title, $subtitle, $author, $isbn, $genre, $year, $page_count, $cover, $description);
  }
  else {
    $_SESSION['edit_book_data'] = $_POST;
    $_SESSION['edit_book_errors'] = $errors;
  }

  header("location: ../books.php");
}
