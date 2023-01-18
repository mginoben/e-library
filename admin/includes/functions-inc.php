<?php

// Creates admin account
function createAdminAccount($conn, $username, $password) {
  $sql = "INSERT INTO admin_accounts (username, password) VALUES (?, ?);";
  $stmt = mysqli_stmt_init($conn);

  $password = password_hash($password, PASSWORD_DEFAULT);

  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss', $username, $password);
  $stmt->execute();
  $stmt->close();
}

// Checks if admin username exists in Database
function adminExists($conn, $username) {
  // Select from db
  $sql = "SELECT * FROM admin_accounts WHERE username=?;";
  $stmt = mysqli_stmt_init($conn);
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $username);
  $stmt->execute();
  $result = $stmt->get_result();
  // If found return the whole row
  if ($row = $result->fetch_assoc()) {
    return $row;
  }
  else {
    return false;
  }
  $stmt->close();
}

// Get Course options
function getOptions($conn, $selected, $column, $table) {
  $sql = 'SELECT DISTINCT '.$column.' FROM '.$table.' ORDER BY '.$column.' DESC;';
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $result = $stmt->get_result();

  if (mysqli_num_rows($result) > 0) {

    while($row = mysqli_fetch_assoc($result)) {

      $option = $row[$column];

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

// Convert POST search variables as SQL Query
function getSearchAccountsQuery($conn, $search_term, $course, $date_created, $status, $page, $limit) {

  $sql = "SELECT (@row_number:=@row_number + 1) AS row_num, umak_id, name, course, contact, email, verified, date_created, id_pic FROM (SELECT @row_number:=0) AS t, accounts WHERE";

  // Sort by UMAK_ID, TRUE means no sorting
  $sql .= (empty($search_term)) ? " true" : " CONCAT(umak_id, ' ', name, ' ', course, ' ', contact, ' ', email) LIKE '%{$search_term}%'";

  // Sort by COURSE, TRUE means no sorting
  $sql .= ($course =="All") ? " AND true" : " AND course='{$course}'";

  // Sort by DATE_CREATED, TRUE means no sorting
  $sql .= ($date_created =="All") ? " AND true" : " AND date_created='{$date_created}'";

  // Sort by STATUS, TRUE means no sorting
  $sql .= ($status =="All") ? " AND true" : " AND verified={$status}";

  // 'None' to count how many PAGE NUMBERS are needed to display
  if ($limit == "None") {
    return $sql. ";";
  }
  // 'limit' for number of ROWS displayed at a time
  else {
    $start = ($page - 1) * $limit;
    $sql .= " LIMIT " .$start. ", " .$limit. ";";
    //Replace the starting count for row number
    $sql = str_replace("row_number:=0", "row_number:=".$start ,$sql);
    return $sql;
  }
}

// Convert POST transaction variables as SQL Query
function getTransactionsQuery($conn, $name, $title, $type, $date, $page, $limit) {
  $sql = 'SELECT (@row_number:=@row_number + 1) AS row_num, accounts.name, books.title, borrow, date, time
          FROM (SELECT @row_number:=0) AS t, transactions INNER JOIN accounts ON accounts.umak_id=transactions.umak_id
          INNER JOIN books ON books.book_id=transactions.book_id
          WHERE';

  // Sort by Account name, TRUE means no sorting
  $sql .= (empty($name)) ? " true" : " accounts.name LIKE '%{$name}%'";

  // Sort by Book title, TRUE means no sorting
  $sql .= (empty($title)) ? " AND true" : " AND books.title LIKE '%{$title}%'";

  // Sort by Transaction type, TRUE means no sorting
  $sql .= ($type == 'All') ? " AND true" : " AND borrow={$type}";

  // Sort by Date, TRUE means no sorting
  $sql .= ($date == 'All') ? " AND true" : " AND date='{$date}'";

  // Scope of return for page count

  // 'None' to count how many page numbers are needed to display
  if ($limit == "None") {
    return $sql. ";";
  }
  // 'limit' for number of rows displayed at a time
  else {
    $start = ($page - 1) * $limit;
    $sql .= " LIMIT " .$start. ", " .$limit. ";";
    //Replace the starting count for row number
    $sql = str_replace("row_number:=0", "row_number:=".$start ,$sql);
    return $sql;
  }
}

// Convert POST transaction variables as SQL Query
function getRequestsQuery($conn, $search_term, $publication_year, $page, $limit) {

  $sql = 'SELECT (@row_number:=@row_number + 1) AS row_num, request_id, accounts.name, title, author, isbn, url, publication_year
          FROM (SELECT @row_number:=0) AS t, requests INNER JOIN accounts ON accounts.umak_id=requests.umak_id WHERE';

  // Sort by date, TRUE means no sorting
  $sql .= ($publication_year == 'All') ? " true" : " publication_year='{$publication_year}'";

  // Search by Title, Author TRUE means no sorting
  $sql .= (empty($search_term)) ? " AND true" : " AND CONCAT(title, ' ', author, ' ', accounts.name) LIKE '%{$search_term}%'";

  // Scope of return for page count

  // 'None' to count how many page numbers are needed to display
  if ($limit == "None") {
    return $sql . ";";
  }
  // 'limit' for number of rows displayed at a time
  else {
    $start = ($page - 1) * $limit;
    $sql .= " LIMIT " .$start. ", " .$limit. ";";
    //Replace the starting count for row number
    $sql = str_replace("row_number:=0", "row_number:=".$start ,$sql);
    return $sql;
  }
}

// Convert POST transaction variables as SQL Query
function getBooksQuery($conn, $search_term, $genre, $year, $page, $limit) {

  $sql = 'SELECT *, (@row_number:=@row_number + 1) AS row_num
          FROM (SELECT @row_number:=0) AS t, books WHERE';

  // Sort by genre, TRUE means no sorting
  $sql .= ($genre == 'All') ? " true" : " genre='{$genre}'";

  // Sort by year, TRUE means no sorting
  $sql .= ($year == 'All') ? " AND true" : " AND year='{$year}'";

  // Search by Title, Author or Book ID TRUE means no sorting
  $sql .= (empty($search_term)) ? " AND true" : " AND CONCAT(title,' ', author, ' ', book_id) LIKE '%{$search_term}%'";

  // Order by
  $sql .= ' ORDER BY date_uploaded DESC';

  // Scope of return for page count

  // 'None' to count how many page numbers are needed to display
  if ($limit == "None") {
    return $sql . ";";
  }
  // 'limit' for number of rows displayed at a time
  else {
    $start = ($page - 1) * $limit;
    $sql .= " LIMIT " .$start. ", " .$limit. ";";
    //Replace the starting count for row number
    $sql = str_replace("row_number:=0", "row_number:=".$start ,$sql);
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

// Display Accounts from Database as table
function displayAccountResult($result) {
  while($row = mysqli_fetch_assoc($result)) {
    $row_num = $row['row_num'];
    $umak_id = $row["umak_id"];
    $name = $row["name"];
    $email = $row["email"];
    $contact = $row["contact"];
    $course = $row["course"];
    $date_created = $row["date_created"];
    $id_pic = $row["id_pic"];

    if ($row["verified"] == 0) {
      $status = 'Pending';
    }
    elseif ($row["verified"] == 1) {
      $status = 'Verified';
    }
    else {
      $status = 'Error';
    }

    // Table Design
    include 'accounts-result.php';
  }
}

// Display Transactions from Database as table
function displayTransactionsResult($result, $layout) {
  while($row = mysqli_fetch_assoc($result)) {
    $row_num = $row["row_num"];
    $name = $row["name"];
    $title = $row["title"];
    $type = $row["borrow"];
    $date = $row["date"];
    $time = $row["time"];

    // Convert type Value to their word equvalent 0-Return 1-Borrow
    if ($type == 1) {
      $type = 'Borrow';
    }
    elseif ($type == 0) {
      $type = 'Return';
    }

    // Table Design
    include $layout;
  }
}

// Display Requests from Database as table
function displayRequestsResult($result) {
  while($row = mysqli_fetch_assoc($result)) {
    $row_num = $row["row_num"];
    $req_id = $row["request_id"];
    $student = $row["name"];
    $title = $row["title"];
    $author = $row["author"];
    $isbn = $row["isbn"];
    $publication_year = $row["publication_year"];
    $url = $row["url"];

    // Table Design
    include 'requests-result.php';
  }
}

// Display Books from Database as table
function displayBooksResult($result) {
  while($row = mysqli_fetch_assoc($result)) {
    $row_num = $row["row_num"];
    $book_id = $row["book_id"];
    $title = $row["title"];
    $subtitle = $row['subtitle'];
    $author = $row["author"];
    $genre = $row["genre"];
    $isbn = $row["isbn"];
    $year = $row["year"];
    $cover = $row["cover"];
    $page_count = $row["page_count"];
    $description = $row['description'];

    // Check if cover image is corrupted
    if(@getimagesize("../" . $cover) == false){
      $cover = 'book_covers/no-cover.png';
    }

    // Convert author name 'daid;john;mark' to 'david, john and mark'
    $author = str_replace(";", ", ", $author);
    $author = preg_replace('/,(?=[^,]*$)/',' and', $author);

    // Table Design
    include 'books-result.php';
  }
}

// Return Table as 2d Array for file export
function getTable($result, $colnames) {
  $table = array();
  $table[] = $colnames;
  while($row = mysqli_fetch_assoc($result)) {
    $data = array();
    foreach ($row as $key => $value) {
      // Converting transaction.borrow values
      if ($key == 'borrow') {
        if ($value == 1) {
          $value = "Borrow";
        }
        elseif ($value == 0) {
          $value = "Return";
        }
      }
      // Converting accounts.verified values
      if ($key == 'verified') {
        if ($value == 1) {
          $value = "Verify";
        }
        elseif ($value == 0) {
          $value = "Pending";
        }
      }
      $data[$key] = $value;
    }
    $table[] = $data;
  }
  return $table;
}

function delete_col(&$array, $key)
{
    // Check that the column ($key) to be deleted exists in all rows before attempting delete
    foreach ($array as &$row)   { if (!array_key_exists($key, $row)) { return false; } }
    foreach ($array as &$row)   { unset($row[$key]); }

    unset($row);

    return true;
}

// Change Student Information
function editStudentInfo($conn, $new_name, $new_course, $new_contact, $new_email, $umak_id) {
  $sql = "UPDATE accounts SET name=?, course=?, contact=?, email=? WHERE umak_id=?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('sssss', $new_name, $new_course, $new_contact, $new_email, $umak_id);
  $stmt->execute();
  $stmt->close();
}

// Check if email is valid
function invalidEmail($email) {
  $domain = explode("@", $email);

  if ($domain[1] == 'umak.edu.ph') {
    return false;
  }
  else {
    return true;
  }
}

// Check if contact is valid
function invalidContact($contact) {
  if (preg_match("/09[0-9]{9}$/", $contact)) {
    return false;
  }
  else {
    return true;
  }
}

// Check if name is valid
function invalidName($name) {
  if (preg_match("/^[a-zA-Z.,ñÑ ]*$/", $name)) {
    return false;
  }
  else {
    return true;
  }
}

// Check if name already EXISTS
function nameExists($conn, $umak_id, $name) {
  $sql = "SELECT * FROM accounts WHERE name=? AND umak_id!=?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss', $name, $umak_id);
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

// Check if new email exists in Database
function emailExists($conn, $umak_id, $new_email) {
  $sql = "SELECT * FROM accounts WHERE email=? AND umak_id!=?;";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss', $new_email, $umak_id);
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


// Verify pending accounts
function verifyAccount($conn, $umak_id) {
  $sql = "UPDATE accounts SET verified=1 WHERE umak_id=?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $umak_id);
  $stmt->execute();
  $stmt->close();

  $sql = "DELETE FROM rejected_accounts WHERE umak_id=?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $umak_id);
  $stmt->execute();
  $stmt->close();
}

// Reject pending accounts
function rejectAccount($conn, $umak_id, $reason) {
  // Transfer umak_id and password from accounts to rejected accounts

  $sql = "INSERT INTO rejected_accounts (umak_id, password) SELECT umak_id, password FROM accounts WHERE umak_id=? ON DUPLICATE KEY UPDATE password = accounts.password;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $umak_id);
  $stmt->execute();
  $stmt->close();

  // Insert reason to the same row
  $sql = "UPDATE rejected_accounts SET reason=? WHERE umak_id =?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss', $reason, $umak_id);
  $stmt->execute();
  $stmt->close();

  // Remove row in accounts using umak_id
  $sql = "DELETE FROM accounts WHERE umak_id =?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $umak_id);
  $stmt->execute();
  $stmt->close();
}

// Remove student
function dropStudent($conn, $umak_id) {
  $sql = "DELETE FROM accounts WHERE umak_id=?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $umak_id);
  $stmt->execute();
  $stmt->close();
}

// Remove request
function removeRequest($conn, $req_id) {
  $sql = "DELETE FROM requests WHERE request_id=?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $req_id);
  $stmt->execute();
  $stmt->close();
}

// Downloads image from image address/url
function file_get_contents_curl($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

// Book Already exists
function bookExists($conn, $title, $isbn) {
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

// Get Book
function getBook($conn, $book_id) {
  // Check on accounts table
  $sql = "SELECT * FROM books WHERE book_id=?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $book_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();

  if ($book = $result->fetch_assoc()) {
    return $book;
  }
}

// Adds book to Database
function addBook($conn, $req_id, $title, $subtitle, $author, $isbn, $genre, $publication_year, $page_count, $cover, $description) {
  // Insert book to database
  $sql = "INSERT INTO books (title, subtitle, author, isbn, genre, year, page_count, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('sssssiis', $title, $subtitle, $author, $isbn, $genre, $publication_year, $page_count, $description);
  $stmt->execute();
  $stmt->close();

  // Update Image Cover Path in database
  $sql = "SELECT * FROM books WHERE isbn=? AND title=?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss', $isbn, $title);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    $path = 'book_covers/' . $row['book_id'] . '.' . 'png';
    $sql = "UPDATE books SET cover=? WHERE book_id=?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $path, $row['book_id']);
    $stmt->execute();

    // Download Image using url
    $data = file_get_contents_curl($cover);
    file_put_contents('../../'.$path, $data);
  }

  // Remove book request
  $sql = "DELETE FROM requests WHERE request_id=?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $req_id);
  $stmt->execute();
  $stmt->close();
}

// Remove Book
function removeBook($conn, $book_id){
  $sql = "DELETE FROM books WHERE book_id=?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $book_id);
  $stmt->execute();

  // Delete Book Cover
  $sql = "SELECT cover FROM books WHERE book_id=?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $book_id);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($row = $result->fetch_assoc()) {
    unlink('../../' . $row['cover']);
  }

  $stmt->close();
}

// TODO Edit Book
function editBook($conn, $book_id, $title, $subtitle, $author, $isbn, $genre, $year, $page_count, $cover, $description) {
  // Update Values except cover
  $sql = "UPDATE books SET title=?, subtitle=?, author=?, isbn=?, genre=?, year=?, page_count=?, description=? WHERE book_id=?;";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('sssssiisi', $title, $subtitle, $author, $isbn, $genre, $year, $page_count, $description, $book_id);
  $stmt->execute();
  $stmt->close();

  if (!empty($cover)) {
    $path = 'book_covers/' . $book_id . '.' . 'png';
    $sql = "UPDATE books SET cover=? WHERE book_id=?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $path, $book_id);
    $stmt->execute();
    $stmt->close();

    // Download Image using url
    $data = file_get_contents_curl($cover);
    file_put_contents('../../' . $path, $data);
  }
}

// Get Account
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
