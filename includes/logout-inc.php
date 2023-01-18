<?php

session_start();

// Unset all session variables except admin_id
foreach($_SESSION as $key => $val) {
    if ($key !== 'admin_id') {
      unset($_SESSION[$key]);
    }
}

header("location: ../index.php");
exit();
