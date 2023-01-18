<?php
require_once 'dbc-inc.php';
require_once 'functions-inc.php';

$umak_id = $_GET['umak_id'];
$book_id = $_GET['book_id'];

returnBook($conn, $umak_id, $book_id, 0);

header("location: ../profile.php");

exit();
