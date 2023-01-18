<?php

require_once 'dbc-inc.php';
require_once 'functions-inc.php';
include 'excel_generator.php';

$query = $_GET['query'];
$page_name = $_GET['page'];
$new_query = '';
$query_split = explode(' ', $query);

// file name
$filename = $page_name . "_" . date('Ymd') . ".xlsx"; // File Name
// Remove Limit from query
foreach ($query_split as $word) {
  if (strtolower($word) == 'limit') {
    $new_query .= ';';
    break;
  }
  $new_query .= $word . ' ';
}
// Get result of query
$result = getQueryResult($conn, $new_query);

if ($page_name == 'transactions') {
  $colnames = array("#", "Name", "Title", "Type", "Date", "Time");
  $table = getTable($result, $colnames);

}
elseif ($page_name == 'index') {
  $colnames = array('#', "UMak ID", "Name", "Course", "Contact", "Email", "Status", "Date Created");
  $table = getTable($result, $colnames);
  // Remove ID PIC column
  for($i = 0, $length = count($table); $i < $length; ++$i) {
    unset($table[$i]['id_pic']);
  }
}
// TODO asdasd
elseif ($page_name == 'books') {
  $colnames = array('#', "Book ID", "ISBN", "Title", "Subtitle", "Author", "Genre", "Description", "Publication Year", "Pages", "Borrow Count", "Datetime Uploaded");
  $table = getTable($result, $colnames);

  for($i = 0, $length = count($table); $i < $length; ++$i) {
    // Remove Cover column
    unset($table[$i]['cover']);
    unset($table[$i]['row_num']);
    // Convert author name 'daid;john;mark' to 'david, john and mark'
    foreach ($table[$i] as $key => $value) {
      if ($key == 'author') {
        $value = str_replace(";", ", ", $value);
        $value = preg_replace('/,(?=[^,]*$)/',' and', $value);
        $table[$i][$key] = $value;
      }
    }
  }
}
elseif ($page_name == 'requests') {
  $colnames = array('#', "Request ID", "Student Name", "Book Title", "Author", "Publisher", "ISBN", "URL", "Date", "Time");
  $table = getTable($result, $colnames);
}

$xlsx = Shuchkin\SimpleXLSXGen::fromArray($table);
$xlsx->downloadAs($filename);
?>
