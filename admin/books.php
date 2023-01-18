<?php
  require_once 'includes/dbc-inc.php';
  require_once 'includes/functions-inc.php';

  // If remove button was pressed
  if (isset($_POST['remove'])) {
    $book_id = $_POST['book_id'];
    removeBook($conn, $book_id);
    header("location: books.php?page=1");
    exit();
  }

  // Display row Limit
  $limit = 15;
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Icon library -->
    <link rel="stylesheet" href="../fontawesome-6.1.1\css\all.css">
    <!-- Bootstrap CSS -->
    <link href="../bootstrap_css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Style CSS -->
    <link rel="stylesheet" href="style.css?<?php echo time(); ?>">

    <title>Books</title>
  </head>
  <body>

    <?php
      // Show Nav Bar
      include_once 'header.php';

      // If search button was pressed
      if (isset($_POST['search'])){
        $_SESSION['search_books_data'] = $_POST;
      }

      // If clear button was pressed
      if (isset($_POST['clear'])){
        if (isset($_SESSION['search_books_data'])) {
          unset($_SESSION['search_books_data']);
        }
        header("location: books.php?page=1");
        exit();
      }

      // Get Post Variables from session
      if (isset($_SESSION['search_books_data'])) {
        $search_term = $_SESSION['search_books_data']['search_term'];
        $genre = $_SESSION['search_books_data']['genre'];
        $year = $_SESSION['search_books_data']['year'];
      }

      // Return to previous page if $_GET['page'] has error
      if (empty($_GET['page']) || !isset($_GET['page']) || !preg_match('/^[0-9]*$/', $_GET['page'])) {
        header('location: ' . $_SESSION['prev_header']);
        exit();
      }
      // Get previous working url
      else {
        $_SESSION['prev_header'] = basename($_SERVER['REQUEST_URI']);
      }
    ?>

    <div class="container-fluid admin-container d-flex flex-column justify-content-between">

      <?php
        include_once 'loading_animation.php';
      ?>

      <div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?page=1'; ?>" method="post" autocomplete="off">
          <div class="search-account-container py-4 px-5">
            <div class="row row-cols-1 row-cols-lg-5 d-flex justify-content-center mb-0 mb-md-2 mx-md-2">
              <div>
                <label class="text-white fs-6 my-1">Search Term</label>
                <input type="text" name="search_term" placeholder="Title, Author or Book ID" class="form-control"
                  value="<?php echo isset($search_term) ? $search_term : '' ; ?>"/>
              </div>
              <div>
                <label class="text-white fs-6 my-1">Genre</label>
                <select name="genre" class="form-control" placeholder="All">
                  <?php $selected = isset($genre) ? $genre : ''; ?>
                  <option <?php echo ($selected == 'All') ? 'selected' : ''; ?> >All</option>
                  <?php
                    getOptions($conn, $selected, "genre", "books");
                  ?>
                </select>
              </div>
              <div>
                <label class="text-white fs-6 my-1">Year</label>
                <select name="year" class="form-control" placeholder="All">
                  <?php $selected = isset($year) ? $year : ''; ?>
                  <option <?php echo ($selected == 'All') ? 'selected' : ''; ?> >All</option>
                  <?php
                    getOptions($conn, $selected, "year", "books");
                  ?>
                </select>
              </div>
              <div class="d-flex align-items-end col-6 col-lg-2">
                <button type="submit" name="search" class="form-control search-btn mt-4 mt-md-0">SEARCH</button>
              </div>
              <div class="d-flex align-items-end col-6 col-lg-2">
                <button type="submit" name="clear" class="form-control clear-btn mt-4 mt-lg-0">CLEAR</button>
              </div>
            </div>
          </div>
        </form>

        <?php
          // Get Account Results From DB
          if (isset($_SESSION['search_books_data'])) {
            $query = getBooksQuery($conn, $search_term, $genre, $year, $_GET['page'], $limit);
          }
          // Set query on startup
          if (!isset($query)) {
            $query = getBooksQuery($conn, '', 'All', 'All', $_GET['page'], $limit);
          }
          $result = getQueryResult($conn, $query);
        ?>

        <!-- Accounts Table -->
        <div class="table-container table-responsive <?php echo ($result == false) ? 'd-none' : '' ; ?>">
          <table class="table table-hover table-striped table-sm">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">ID</th>
                <th scope="col">Title</th>
                <th scope="col">Author</th>
                <th scope="col">Genre</th>
                <th scope="col">ISBN</th>
                <th scope="col">Year</th>
                <th scope="col">Pages</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
              <?php
                // Display Books Results
                displayBooksResult($result);
              ?>
            </tbody>
          </table>
        </div>

        <!-- Show this if table has no result -->
        <div class="no-result-container <?php echo ($result != false) ? 'd-none' : '' ; ?>">
          <img src="../icons/no-result.svg" alt="" class="mt-5">
          <h1 class="text-center mt-3">No Result!</h1>
        </div>

      </div>

      <!-- PAGINATION -->
      <div class="row m-0 d-flex justify-content-center py-3">
        <?php
          if (isset($_SESSION['search_books_data'])) {
            $query_all = getBooksQuery($conn, $search_term, $genre, $year, $_GET['page'], 'None');
          }
          else {
            $query_all = getBooksQuery($conn, '', 'All', 'All', $_GET['page'], 'None');
          }
          getPageCount(getQueryResult($conn, $query_all), $limit);
        ?>
      </div>

      <!-- EXPORT -->
      <?php $export_link = 'includes/export-inc.php?query=' . urlencode($query) . '&page=' . basename($_SERVER['PHP_SELF'], ".php"); ?>
      <a href="<?php echo $export_link; ?>" class="export <?php echo (isset($result) && $result != false) ? 'active' : '' ?>" data-bs-toggle="tooltip" title="Export" target="_blank">
        <i class="fa-solid fa-download"></i>
      </a>

    </div>

    <?php
      include_once 'footer.php';

      // Reset Session Variables
      if (isset($_SESSION['edit_book_errors'])) {
        unset($_SESSION['edit_book_errors']);
      }
      if (isset($_SESSION['edit_book_data'])) {
        unset($_SESSION['edit_book_data']);
      }
    ?>

    <script src="../bootstrap_js/bootstrap.bundle.min.js"></script>
    <script src="../main.js"></script>

  </body>

</html>
