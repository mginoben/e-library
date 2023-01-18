<?php
  require_once 'includes/dbc-inc.php';
  require_once 'includes/functions-inc.php';

  // If Remove Button was pressed
  if (isset($_POST['remove'])) {
    $umak_id = $_POST['umak_id'];
    dropStudent($conn, $umak_id);
    header('location: index.php?page=1');
    exit();
  }

  // If Reject Button was pressed
  if (isset($_POST['reject'])) {
    $umak_id = $_POST['umak_id'];
    $reason = $_POST['reason'];
    rejectAccount($conn, $umak_id, $reason);
    header("location: index.php?page=1");
    exit();
  }

  // If Verify Button was pressed
  if (isset($_POST['verify'])) {
    $umak_id = $_POST['umak_id'];
    verifyAccount($conn, $umak_id);
    header("location: index.php?page=1");
    exit();
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
    <link rel="stylesheet" href="../fontawesome-6.1.1/css/all.css">
    <!-- Bootstrap CSS -->
    <link href="../bootstrap_css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Style CSS -->
    <link rel="stylesheet" href="style.css?<?php echo time(); ?>">

    <title>Accounts</title>
  </head>
  <body>

    <?php
      // Show Nav Bar
      include_once 'header.php';

      // If search button was pressed
      if (isset($_POST['search'])){
        $_SESSION['search_accounts_data'] = $_POST;
      }

      // If clear button was pressed
      if (isset($_POST['clear'])){
        if (isset($_SESSION['search_accounts_data'])) {
          unset($_SESSION['search_accounts_data']);
        }
        header("location: index.php?page=1");
        exit();
      }

      // Get Post Variables from session
      if (isset($_SESSION['search_accounts_data'])) {
        $search_term = $_SESSION['search_accounts_data']['search_term'];
        $course = $_SESSION['search_accounts_data']['course'];
        $date_created = $_SESSION['search_accounts_data']['date_created'];
        $status = $_SESSION['search_accounts_data']['status'];
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

    <div class="container-fluid d-flex flex-column justify-content-between">
      <?php
        include_once 'loading_animation.php';
      ?>
      <div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?page=1'; ?>" method="post" autocomplete="off">
          <div class="search-account-container py-4 px-5">
            <div class="row row-cols-md-2 row-cols-lg-3 d-flex justify-content-center mb-0 mb-md-2 mx-md-5 px-md-5">

              <div class="">
                <label class="text-white fs-6 my-1">Search Term</label>
                <input type="text" name="search_term" placeholder="ID, Name, Email or Contact" class="form-control"
                  value="<?php echo isset($search_term) ? $search_term : ""; ?>"/>
              </div>
              <div class="">
                <label class="text-white fs-6 my-1">Course</label>
                <select name="course" class="form-control" placeholder="All">
                  <?php
                    $selected = isset($course) ? $course : '';
                  ?>
                  <option <?php echo ($selected == 'All') ? 'selected' : ''; ?> >All</option>
                  <option <?php echo ($selected == 'Computer Science') ? 'selected' : ''; ?> >Computer Science</option>
                  <option <?php echo ($selected == 'Psychology') ? 'selected' : ''; ?> >Psychology</option>
                  <option <?php echo ($selected == 'Arts & Letters') ? 'selected' : ''; ?> >Arts & Letters</option>
                  <option <?php echo ($selected == 'Education') ? 'selected' : ''; ?> >Education</option>
                  <option <?php echo ($selected == 'Nursing') ? 'selected' : ''; ?> >Nursing</option>
                  <option <?php echo ($selected == 'Tourism') ? 'selected' : ''; ?> >Tourism</option>
                </select>
              </div>
              <div class="d-flex align-items-end order-last order-lg-0 col-6 col-lg-2">
                <button type="submit" name="search" class="form-control search-btn col-4 mt-4 mt-lg-0">SEARCH</button>
              </div>
              <div>
                <label class="text-white fs-6 my-1">Date Created</label>
                <select name="date_created" class="form-control" placeholder="All">
                  <?php $selected = isset($date_created) ? $date_created : ''; ?>
                  <option <?php echo ($selected == 'All') ? 'selected' : ''; ?> >All</option>
                  <?php
                    getOptions($conn, $selected, 'date_created', 'accounts');
                  ?>
                </select>
              </div>
              <div class="">
                <label class="text-white fs-6 my-1">Status</label>
                <select name="status" class="form-control">
                  <option <?php echo (isset($status) && $status == 'All') ? 'selected' : ''; ?> value="All">All</option>
                  <option <?php echo (isset($status) && $status == '1') ? 'selected' : ''; ?> value="1">Verified</option>
                  <option <?php echo (isset($status) && $status == '0') ? 'selected' : ''; ?> value="0">Pending</option>
                </select>
              </div>
              <div class="d-flex align-items-end order-last col-6 col-lg-2">
                <button type="submit" name="clear" class="form-control clear-btn col-4 mt-4 mt-lg-0">CLEAR</button>
              </div>
            </div>
          </div>
        </form>

        <?php
          // Get Account Results From DB
          if (isset($_SESSION['search_accounts_data'])) {
            $query = getSearchAccountsQuery($conn, $search_term, $course, $date_created, $status, $_GET['page'], $limit);
          }
          // Set query on startup
          if (!isset($query)) {
            $query = getSearchAccountsQuery($conn, '', 'All', 'All', 'All', $_GET['page'], $limit);
          }

          $result = getQueryResult($conn, $query);
        ?>

        <!-- Accounts Table -->
        <div class="table-container table-responsive <?php echo ($result == false) ? 'd-none' : '' ; ?>">
          <table class="table table-hover table-striped table-sm">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">UMak ID</th>
                <th scope="col">Name</th>
                <th scope="col">Course</th>
                <th scope="col">Contact</th>
                <th scope="col">Email</th>
                <th scope="col">Status</th>
                <th scope="col">Date Created</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
              <?php
                displayAccountResult($result);
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
          if (isset($_SESSION['search_accounts_data'])) {
            $query_all = getSearchAccountsQuery($conn, $search_term, $course, $date_created, $status, $page, 'None');
          }
          else {
            $query_all = getSearchAccountsQuery($conn, '', 'All', 'All', 'All', $_GET['page'], 'None');
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

      // Reset session variables
      if (isset($_SESSION['adit_account_errors'])) {
        unset($_SESSION['adit_account_errors']);
      }
      if (isset($_SESSION['edit_account_data'])) {
        unset($_SESSION['edit_account_data']);
      }
    ?>

    <script src="../bootstrap_js/bootstrap.bundle.min.js"></script>
    <script src="../main.js"></script>


  </body>

</html>
