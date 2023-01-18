<?php
  require_once 'includes/dbc-inc.php';
  require_once 'includes/functions-inc.php';

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

    <title>Transactions</title>
  </head>
  <body>

    <?php
      // Show Nav Bar
      include_once 'header.php';

      // If search button was pressed
      if (isset($_POST['search'])){
        $_SESSION['search_transactions_data'] = $_POST;
      }

      // If clear button was pressed
      if (isset($_POST['clear'])){
        if (isset($_SESSION['search_transactions_data'])) {
          unset($_SESSION['search_transactions_data']);
        }
        header("location: transactions.php?page=1");
        exit();
      }

      // Get Post Variables from session
      if (isset($_SESSION['search_transactions_data'])) {
        $acc_name = $_SESSION['search_transactions_data']['acc_name'];
        $book_title = $_SESSION['search_transactions_data']['book_title'];
        $type = $_SESSION['search_transactions_data']['type'];
        $date = $_SESSION['search_transactions_data']['date'];
      }

      // Return to previous page if $_GET['page'] has error
      if (empty($_GET['page']) || !isset($_GET['page']) || !preg_match('/^[0-9]*$/', $_GET['page'])) {
        header('location: ' . $_SESSION['prev_header']);
        exit();
      }
      else {
        // Get previous working url
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
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 d-flex justify-content-center mb-0 mb-md-2 mx-md-2">
              <div>
                <label class="text-white fs-6 my-1">Account Name</label>
                <input type="text" name="acc_name" placeholder="Juan Dela Cruz" class="form-control"
                  value="<?php echo (isset($acc_name)) ? $acc_name : '' ; ?>"
                  oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '').replace(/(\..*?)\..*/g, '$1');"/>
              </div>
              <div>
                <label class="text-white fs-6 my-1">Book Title</label>
                <input type="text" name="book_title" class="form-control" placeholder="Harry Potter"
                  value="<?php echo (isset($book_title)) ? $book_title : '' ; ?>"
                  oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '').replace(/(\..*?)\..*/g, '$1');"/>
              </div>
              <div class="d-flex align-items-end col-6 col-lg-2 order-last order-lg-0">
                <button type="submit" name="search" class="form-control search-btn mt-4 mt-md-0">SEARCH</button>
              </div>
              <div>
                <label class="text-white fs-6 my-1">Type</label>
                <select name="type" class="form-control">
                  <option <?php echo (isset($type) && $type == 'All') ? 'selected' : ''; ?> value="All">All</option>
                  <option <?php echo (isset($type) && $type == '1') ? 'selected' : ''; ?> value="1">Borrow</option>
                  <option <?php echo (isset($type) && $type == '0') ? 'selected' : ''; ?> value="0">Return</option>
                </select>
              </div>
              <div>
                <label class="text-white fs-6 my-1">Date</label>
                <select name="date" class="form-control">
                  <?php $selected = (isset($date)) ? $date : ''; ?>
                  <option <?php echo ($selected == 'All') ? 'selected' : ''; ?> >All</option>
                  <?php
                    getOptions($conn, $selected, 'date', 'transactions');
                  ?>
                </select>
              </div>
              <div class="d-flex align-items-end order-last col-6 col-lg-2">
                <button type="submit" name="clear" class="form-control clear-btn mt-4 mt-lg-0">CLEAR</button>
              </div>
            </div>
          </div>
        </form>

        <?php
          // Get Account Results From DB
          if (isset($_SESSION['search_transactions_data'])) {
            $query = getTransactionsQuery($conn, $acc_name, $book_title, $type, $date, $_GET['page'], $limit);
          }
          // Set query on startup
          if (!isset($query)) {
            $query = getTransactionsQuery($conn, '', '', 'All', 'All', $_GET['page'], $limit);
          }

          $result = getQueryResult($conn, $query);
        ?>

        <!-- Accounts Table -->
        <div class="table-container table-responsive <?php echo ($result == false) ? 'd-none' : '' ; ?>">
          <table class="table table-hover table-striped table-sm">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Student Name</th>
                <th scope="col">Book Title</th>
                <th scope="col">Type</th>
                <th scope="col">Date</th>
                <th scope="col">Time</th>
              </tr>
            </thead>
            <tbody>
              <?php
                // Display Transaction Results
                displayTransactionsResult($result, "transaction-result.php");
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
      <div class="row m-0 d-flex justify-content-center py-3">>
        <?php
        if (isset($_SESSION['search_transactions_data'])) {
          $query_all = getTransactionsQuery($conn, $acc_name, $book_title, $type, $date, $_GET['page'], 'None');
        }
        else {
          $query_all = getTransactionsQuery($conn, '', '', 'All', 'All', 1, 'None');
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
    ?>

    <script src="../bootstrap_js/bootstrap.bundle.min.js"></script>

  </body>

</html>
