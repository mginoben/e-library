<?php

session_start();

if (isset($_POST["search_btn"])) {

  $_SESSION['browse_data'] = $_POST;

  header("location: ../browse.php?page=1");
  exit();
}
