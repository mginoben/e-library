<?php
  require_once 'includes/dbc-inc.php';
  require_once 'includes/functions-inc.php';

  // If remove button was pressed
  if (isset($_POST['remove'])) {
    $req_id = $_POST['request_id'];
    removeRequest($conn, $req_id);
    header("location: requests.php?page=1");
    exit();
  }

  // If search button was pressed
  if (isset($_POST['search'])) {
    $search_term = $_POST['search_term'];
    $publication_year = $_POST['publication_year'];
  }

  // Display row Limit
  $limit = 10;
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

    <title>Requests</title>
  </head>
  <body>

    <?php
      // Show Nav Bar
      include_once 'header.php';

      // If search button was pressed
      if (isset($_POST['search'])){
        $_SESSION['search_requests_data'] = $_POST;
      }

      // If clear button was pressed
      if (isset($_POST['clear'])){
        if (isset($_SESSION['search_requests_data'])) {
          unset($_SESSION['search_requests_data']);
        }
        header("location: requests.php?page=1");
        exit();
      }

      //  TODO Get Post Variables from session
      if (isset($_SESSION['search_requests_data'])) {
        $acc_name = $_SESSION['search_requests_data']['acc_name'];
        $book_title = $_SESSION['search_requests_data']['book_title'];
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

      // Get Post Data
      if (isset($_SESSION['requests_data'])) {
        $data = $_SESSION['requests_data'];
      }
    ?>

    <div class="container-fluid admin-container d-flex flex-column justify-content-start">
      <?php
        include_once 'loading_animation.php';
      ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?page=1'; ?>" method="post" autocomplete="off">
          <div class="search-account-container py-4 px-5">
            <div class="row row-cols-md-2 row-cols-lg-4 d-flex justify-content-center mb-0 mb-md-2 mx-md-2">
              <div>
                <label class="text-white fs-6 my-1">Search Term</label>
                <input type="text" name="search_term" placeholder="Title, Author or Student" class="form-control"
                  value="<?php echo isset($_POST['search']) ? $search_term : '' ; ?>"
                  oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '').replace(/(\..*?)\..*/g, '$1');"/>
              </div>
              <div>
                <label class="text-white fs-6 my-1">Publication Year</label>
                <select name="publication_year" class="form-control" placeholder="All">
                  <?php $selected = isset($_POST['search']) ? $publication_year : ''; ?>
                  <option <?php echo ($selected == 'All') ? 'selected' : ''; ?> >All</option>
                  <?php
                    getOptions($conn, $selected, 'publication_year', 'requests');
                  ?>
                </select>
              </div>
              <div class="d-flex align-items-end col-6 col-lg-2">
                <button type="submit" name="search" class="form-control search-btn col-4 mt-4 mt-md-0">SEARCH</button>
              </div>
              <div class="d-flex align-items-end col-6 col-lg-2">
                <button type="submit" name="clear" class="form-control clear-btn mt-4 mt-lg-0">CLEAR</button>
              </div>
            </div>
          </div>
        </form>

        <?php
          // Get Account Results From DB
          if (isset($_POST['search'])) {
            $query = getRequestsQuery($conn, $search_term, $publication_year, $_GET['page'], $limit);
          }
          // Set query on startup
          else {
            $query = getRequestsQuery($conn, '', 'All', $_GET['page'], $limit);
          }

          $result = getQueryResult($conn, $query);
        ?>

        <!-- Accounts Table -->
        <div class="table-container table-responsive <?php echo ($result == false) ? 'd-none' : '' ; ?>">
          <table class="table table-hover table-striped table-sm">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Requesting Student</th>
                <th scope="col">Title</th>
                <th scope="col">Author</th>
                <th scope="col">ISBN</th>
                <th scope="col">Publication Year</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
              <?php
                // Display Requests Results
                displayRequestsResult($result);
              ?>
            </tbody>
          </table>
        </div>

        <!-- Show this if table has no result -->
        <div class="no-result-container <?php echo ($result != false) ? 'd-none' : '' ; ?>">
          <img src="../icons/no-result.svg" alt="" class="mt-5">
          <h1 class="text-center mt-3">No Result!</h1>
        </div>

      <!-- PAGINATION -->
      <div class="row m-0 d-flex justify-content-center py-3">
        <?php
        if (isset($_POST['search'])) {
          $query_all = getRequestsQuery($conn, $search_term, $publication_year, $page, 'None');
        }
        else {
          $query_all = $query = getRequestsQuery($conn, '', 'All', $_GET['page'], 'None');
        }
        getPageCount(getQueryResult($conn, $query_all), $limit);
        ?>
      </div>

      <!-- EXPORT -->
      <?php $export_link = 'includes/export-inc.php?query=' . urlencode($query) . '&page=' . basename($_SERVER['PHP_SELF'], ".php"); ?>
      <a href="<?php echo $export_link; ?>" class="export <?php echo (isset($result) && $result != false) ? 'active' : '' ?>" data-bs-toggle="tooltip" title="Export" target="_blank">
        <i class="fa-solid fa-download"></i>
      </a>

      <!-- Add book success popup -->
      <div class="modal fade <?php echo (isset($_SESSION['add_book']) && $_SESSION['add_book']=='success') ? 'show_modal': ''; ?>" id="add_success" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <div class="d-flex justify-content-center">
                <div class="d-flex flex-column justify-content-between align-items-center">
                  <i class="fa-solid fa-circle-check text-success"></i>
                  <span class="borrow-alert-text fs-4 text-success">Book Added Successfully!</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <?php
      include_once 'footer.php';

      // Reset session variables
      if (isset($_SESSION['add_book'])) {
        unset($_SESSION['add_book']);
      }
      if (isset($_SESSION['request_id'])) {
        unset($_SESSION['request_id']);
      }
      if (isset($_SESSION['add_book_data'])) {
        unset($_SESSION['add_book_data']);
      }
      if (isset($_SESSION['add_book_errors'])) {
        unset($_SESSION['add_book_errors']);
      }
    ?>

    <script src="../bootstrap_js/bootstrap.bundle.min.js"></script>
    <!-- ISBN COPY javascript -->
    <script type="text/javascript" src="../main.js"></script>



  </body>

</html>
