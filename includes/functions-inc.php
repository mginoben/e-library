<?php
require_once 'dbc-inc.php';

function invalidID($id_number) {
  if (preg_match("/^[KkAa][0-9]*$/", $id_number)) {
    return false;
  }
  else {
    return true;
  }
}

function invalidEmail($email) {
  $domain = explode("@", $email);

  if ($domain[1] == 'umak.edu.ph') {
    return false;
  }
  else {
    return true;
  }
}

function idExists($conn, $umak_id) {
  $umak_id = strtoupper($umak_id);

  $sql = "SELECT * FROM accounts WHERE umak_id=?;";
  $stmt = mysqli_stmt_init($conn);

  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $umak_id);
  $stmt->execute();
  $resultUmakId = $stmt->get_result();

  if ($row = $resultUmakId->fetch_assoc()) {
    return $row;
  }
  else {
    return false;
  }

  $stmt->close();
}

function emailExists($conn, $email) {
  $sql = "SELECT * FROM accounts WHERE email=?;";
  $stmt = mysqli_stmt_init($conn);

  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $email);
  $stmt->execute();
  $resultEmail = $stmt->get_result();

  if ($row = $resultEmail->fetch_assoc()) {
    return true;
  }
  else {
    return false;
  }

  $stmt->close();
}

function nameExists($conn, $name) {
  $sql = "SELECT * FROM accounts WHERE name=?;";
  $stmt = mysqli_stmt_init($conn);

  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $name);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->fetch_assoc()) {
    return true;
  }
  else {
    return false;
  }

  $stmt->close();
}

function createAccount($conn, $umak_id, $password, $course, $name, $contact, $email, $id_pic, $verified) {
  $sql = "INSERT INTO accounts (umak_id, password, course, name, contact, email, id_pic, verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
  $stmt = mysqli_stmt_init($conn);

  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
  $umak_id = strtoupper($umak_id);

  $stmt = $conn->prepare($sql);
  $stmt->bind_param('sssssssi', $umak_id, $hashedPassword, $course, $name, $contact, $email, $id_pic, $verified);
  $stmt->execute();
  $stmt->close();
}

function invalidName($name) {
  if (preg_match("/^[a-zA-Z.,ñÑ ]*$/", $name)) {
    return false;
  }
  else {
    return true;
  }
}

function invalidContact($contact) {
  if (preg_match("/09[0-9]{9}$/", $contact)) {
    return false;
  }
  else {
    return true;
  }
}

function checkAccountStatus($conn, $umak_id) {
  // CHECK if umak_id is present in accounts
  $sql = "SELECT * FROM accounts WHERE umak_id=?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $umak_id);
  $stmt->execute();
  $resultInAccount = $stmt->get_result();
  $stmt->close();

  // CHECK if account is pending or verified
  if ($account = $resultInAccount->fetch_assoc()) {
    if ($account['verified'] == 0) {
      // 0 = PENDING
      return 'Pending';
    }
    elseif ($account['verified'] == 1) {
      // 1 = VERIFIED
      return 'Verified';
    }
  }
  // Check if account is in rejected accounts
  else {
    // Get the last instance of selected umak_id (umak_id can be repeated)
    $sql = "SELECT * FROM rejected_accounts WHERE umak_id=? ORDER BY umak_id DESC LIMIT 1;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $umak_id);
    $stmt->execute();
    $resultInRejectedAccount = $stmt->get_result();
    $stmt->close();
    // Account is REJECTED
    if ($account = $resultInRejectedAccount->fetch_assoc()) {
      return 'Rejected';
    }
    // Account DOES NOT EXISTS
    else {
      return 'None';
    }
  }


}

function getImg($image_url, $class) {
  $imageData = base64_encode(file_get_contents($image_url));
  return '<img src="data:image/jpeg;base64,'.$imageData.'" class="'.$class.'">';
}

function getBooks($conn, $result, $type) {

  if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
      $book_id = $row["book_id"];
      $title = $row["title"];
      $subtitle = $row["subtitle"];
      $author = $row["author"];
      $genre = $row["genre"];
      $cover = $row["cover"];
      $description = $row["description"];
      $year = $row["year"];
      $page_count = $row["page_count"];
      $borrow_count = $row["borrow_count"];


      // Check if cover image is corrupted
      if(empty(@getimagesize($cover))){
        $cover = 'book_covers/no-cover.png';
      }
      else {
        $cover .= "?t=" . time();
      }


      // Convert author name 'daid;john;mark' to 'david, john and mark'
      $author = str_replace(";", ", ", $author);
      $author = preg_replace('/,(?=[^,]*$)/',' and', $author);
      // Get current url
      $url = basename($_SERVER['REQUEST_URI']);
      $url = (strpos($url, ".php")) ? $url : 'index.php';
      // Creating borrow message
      if ($borrow_count == 0) {
        $borrowed_message = 'Not yet borrowed.';
      }
      elseif ($borrow_count == 1) {
        $borrowed_message = 'Borrowed 1 time';
      }
      elseif ($borrow_count) {
        $borrowed_message = 'Borrowed '.$borrow_count.' times';
      }

      // Layout of book
      include 'book-layout.php';
    }
  }
}

function getBrowseOptions($conn, $type, $selected) {
  if ($type == "genre") {
    $sql = "SELECT DISTINCT (genre) FROM `books` ORDER BY genre ASC;";
  }
  elseif ($type == "year") {
    $sql = "SELECT DISTINCT (year) FROM `books` ORDER BY year DESC;";
  }

  $stmt = mysqli_stmt_init($conn);

  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $resultOption = $stmt->get_result();

  if (mysqli_num_rows($resultOption) > 0) {
    echo "<option>All</option>";
    while($row = mysqli_fetch_assoc($resultOption)) {

      $option = $row[$type];

      if (isset($selected) and $selected==$option) {
        echo "<option selected>".$option."</option>";
      }
      else {
        echo "<option>".$option."</option>";
      }
    }
  }

  $stmt->close();
}

function getSearchBooksQuery($conn, $search_term, $genre, $year, $order_by, $page, $limit) {
  $sql = "SELECT *, (@row_number:=@row_number + 1) AS row_num FROM (SELECT @row_number:=0) AS t, books WHERE";

  // Sort by search_term, TRUE means no sorting
  $sql .= (empty($search_term)) ? " true" : " CONCAT(title,' ',author) LIKE '%{$search_term}%'";

  // Sort by genre, TRUE means no sorting
  $sql .= ($genre == "All") ? " AND true" : " AND genre='{$genre}'";

  // Sort by year, TRUE means no sorting
  $sql .= ($year == "All") ? " AND true" : " AND year='{$year}'";

  // Order of sorting
  if ($order_by == "Newest Release") {
    $sql .= " ORDER BY date_uploaded DESC";
  }
  elseif ($order_by == "Latest") {
    $sql .= " ORDER BY year DESC";
  }
  elseif ($order_by == "Oldest") {
    $sql .= " ORDER BY year ASC";
  }
  elseif ($order_by == "Most Borrowed") {
    $sql .= " ORDER BY borrow_count DESC";
  }
  elseif ($order_by == "Pages") {
    $sql .= " ORDER BY page_count DESC";
  }

  // 'None' to count how many PAGE NUMBERS are needed to display
  if ($limit == "None") {
    return $sql. ";";
  }
  // 'limit' for number of ROWS displayed at a time
  else {
    $start = ($page - 1) * $limit;
    $sql .= " LIMIT " .$start. ", " .$limit. ";";
    return $sql;
  }
}

// Show selected rows as table -- returns table
function getQueryResult($conn, $sql) {
    // Prepare and Execute Query
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are results
    if (mysqli_num_rows($result) > 0) {
      return $result;
    }
    else {
      return false;
    }
}

// Display Pagination of Results
function getPageCount($result, $limit) {

  // Get number of records
  if ($result != false) {
    while ($row = mysqli_fetch_assoc($result)) {
      $row_count = $row['row_num'];
    }

    // Get page count
    $page_count = ceil($row_count / $limit);
    // Get current url
    $url = $_SERVER["REQUEST_URI"];
    // Get url of prev and next button
    $url_prev = str_replace("page=".$_GET['page'], "page=".($_GET['page']-1), $url);
    $url_next = str_replace("page=".$_GET['page'], "page=".($_GET['page']+1), $url);
    // Disable prev or next button when page exceeds
    $disabled_prev = ($_GET['page'] - 1 == 0) ? "disabled" : "";
    $disabled_next = ($_GET['page'] + 1 > $page_count) ? "disabled" : "";

    if ($row_count > 0) {
      $display = ($row_count <= $limit) ? 'd-none' : '';
      echo '
        <div class="pagination px-2 '.$display.'">
          <div class="page-item '.$disabled_prev.'">
            <a class="page-link" href="'.$url_prev.'" aria-label="Previous">
              <span aria-hidden="true">&laquo;</span>
            </a>
          </div>';

            for ($i=1; $i <= $page_count; $i++) {
              // Set button to selected and disable
              if ($_GET["page"]==$i) {
                $toggle = "disabled selected";
              }
              else {
                $toggle = "";
              }
              echo '<div class="page-item"><a href="?page='.$i.'" class="page-link '.$toggle.'">'.$i.'</a></div>';
            }

      echo '
          <div class="page-item '.$disabled_next.'">
            <a class="page-link" href="'.$url_next.'"aria-label="Next">
              <span aria-hidden="true">&raquo;</span>
            </a>
          </div>
        </div>
      ';
    }
  }
}

function getAccount($conn, $umak_id) {
  // Check on accounts table
  $sql = "SELECT * FROM accounts WHERE umak_id=?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $umak_id);
  $stmt->execute();
  $resultAccount = $stmt->get_result();
  $stmt->close();

  if ($account = $resultAccount->fetch_assoc()) {
    return $account;
  }
  else {
    // Check on rejected accounts table
    $sql = "SELECT * FROM rejected_accounts WHERE umak_id=?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $umak_id);
    $stmt->execute();
    $resultRejectedAccount = $stmt->get_result();
    $stmt->close();
    if ($account = $resultRejectedAccount->fetch_assoc()) {
      return $account;
    }
  }
}

function changeUserInfo($conn, $umak_id, $new_contact, $new_email, $new_profile_pic) {
  if (!empty($new_profile_pic)) {
    $sql = "UPDATE accounts SET contact=?, email=?, profile_pic=? WHERE umak_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $new_contact, $new_email, $new_profile_pic, $umak_id);
  }
  else {
    $sql = "UPDATE accounts SET contact=?, email=? WHERE umak_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $new_contact, $new_email, $umak_id);
  }

  $stmt->execute();
  $stmt->close();
}


function borrowBook($conn, $umak_id, $book_id, $borrow) {
  $sql = "INSERT INTO accounts_has_books (umak_id, book_id) VALUES (?, ?);";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('si', $umak_id, $book_id);
  $stmt->execute();
  $stmt->close();

  $sql = "INSERT INTO transactions (umak_id, book_id, borrow) VALUES (?, ?, ?);";
  $stmt = mysqli_stmt_init($conn);
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('sii', $umak_id, $book_id, $borrow);
  $stmt->execute();
  $stmt->close();

  $sql = "UPDATE books SET borrow_count = borrow_count + 1 WHERE book_id =?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $book_id);
  $stmt->execute();
  $stmt->close();
}

function bookAlreadyBorrowed($conn, $book_id) {
  $sql = "SELECT EXISTS(SELECT book_id FROM accounts_has_books WHERE book_id = ?) as 'book_found';";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $book_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $result = mysqli_fetch_assoc($result);

  if (($result['book_found']==1)) {
    return true;
  }
  else {
    return false;
  }
}

function bookLimitReached($conn, $umak_id) {
  $sql = 'SELECT COUNT(umak_id) as book_count FROM accounts_has_books WHERE umak_id=?;';
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $umak_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $result = mysqli_fetch_assoc($result);

  if ($result['book_count'] < 3) {
    return false;
  }
  else {
    return true;
  }
}

function returnBook($conn, $umak_id, $book_id, $borrow) {
  $sql = "DELETE FROM accounts_has_books WHERE umak_id=? AND book_id=?;";
  $stmt = mysqli_stmt_init($conn);
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('si', $umak_id, $book_id);
  $stmt->execute();
  $stmt->close();

  $sql = "INSERT INTO transactions (umak_id, book_id, borrow) VALUES (?, ?, ?);";
  $stmt = mysqli_stmt_init($conn);
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('sii', $umak_id, $book_id, $borrow);
  $stmt->execute();
  $stmt->close();
}

function requestBook($conn, $umak_id, $title, $author, $publication_year, $isbn, $url) {
  $sql = "INSERT INTO requests (umak_id, title, author, publication_year, isbn, url) VALUES (?, ?, ?, ?, ?, ?);";
  $stmt = mysqli_stmt_init($conn);
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('sssiss', $umak_id, $title, $author, $publication_year, $isbn, $url);
  $stmt->execute();
  $stmt->close();
}

function getRejectReason($conn, $umak_id) {
  $sql = "SELECT * FROM rejected_accounts WHERE umak_id=?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $umak_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();
  if ($account = $result->fetch_assoc()) {
    return $account['reason'];
  }
}

// Book Already exists
function bookExists($conn, $title, $isbn) {
  $umak_id = strtoupper($umak_id);

  $sql = "SELECT * FROM books WHERE title=? OR isbn=?;";
  $stmt = mysqli_stmt_init($conn);

  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss', $title, $isbn);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();

  if ($row = $result->fetch_assoc()) {
    return true;
  }
  else {
    return false;
  }
}
