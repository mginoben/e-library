<?php

session_start();

// Unset all session variables except umak_id
foreach($_SESSION as $key => $val) {
    if ($key !== 'umak_id') {
      unset($_SESSION[$key]);
    }
}

header("location: ../login.php");
exit();
