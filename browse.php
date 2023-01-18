<?php
  require_once 'includes/dbc-inc.php';
  require_once 'includes/functions-inc.php';

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
    <link rel="stylesheet" href="fontawesome-6.1.1\css\all.css">
    <!-- Bootstrap CSS -->
    <link href="bootstrap_css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Style CSS -->
    <link rel="stylesheet" href="style.css?<?php echo time(); ?>">

    <title>Browse</title>
  </head>
  <body>

    <?php
      include_once 'header.php';

      // Default books query
      if (!isset($query)) {
        $query = getSearchBooksQuery($conn, '', 'All', 'All', 'New Release', $_GET['page'], $limit);
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
      if (isset($_SESSION['browse_data'])) {
        $data = $_SESSION['browse_data'];
      }
    ?>

    <div class="container-fluid p-0">

        <?php
          include_once 'loading_animation.php';
        ?>

        <form action="includes/browse-inc.php" method="post">
          <div class="search-container p-4">
            <div class="row d-flex justify-content-center">
              <div class="col-12 col-sm-9">
                <label class="text-white fs-5 mb-1 fw-bold">Search Term</label>
              </div>
            </div>
            <div class="row d-flex justify-content-center mb-2">
              <div class="col-8 col-sm-7">
                <input type="text" name="search_term" class="form-control" value="<?php echo (isset($data['search_term'])) ? $data['search_term'] : "" ; ?>"/>
              </div>
              <div class="col-4 col-sm-2">
                <button type="submit" name="search_btn" class="form-control search-btn col-4">SEARCH</button>
              </div>
            </div>

            <div class="row d-flex justify-content-center pb-3">
              <div class="col-sm-3">
                <label class="text-white fs-6 my-1">Genre</label>
                <select name="genre" class="form-control" placeholder="All">
                    <?php getBrowseOptions($conn, "genre", $data["genre"]); ?>
                </select>
              </div>
              <div class="col-6 col-sm-3">
                <label class="text-white fs-6 my-1">Year</label>
                <select name="year" class="form-control" placeholder="All">
                    <?php getBrowseOptions($conn, "year", $data["year"]); ?>
                </select>
              </div>
              <div class="col-6 col-sm-3">
                <label class="text-white fs-6 my-1">Order By</label>
                <select name="order_by" class="form-control">
                  <option <?php echo (isset($data["order_by"]) && $data["order_by"]=='Newest Release') ? 'selected' : ''; ?>>Newest Release</option>
                  <option <?php echo (isset($data["order_by"]) && $data["order_by"]=='Latest') ? 'selected' : ''; ?>>Latest</option>
                  <option <?php echo (isset($data["order_by"]) && $data["order_by"]=='Oldest') ? 'selected' : ''; ?>>Oldest</option>
                  <option <?php echo (isset($data["order_by"]) && $data["order_by"]=='Most Borrowed') ? 'selected' : ''; ?>>Most Borrowed</option>
                  <option <?php echo (isset($data["order_by"]) && $data["order_by"]=='Pages') ? 'selected' : ''; ?>>Pages</option>
                </select>
              </div>
            </div>
          </div>
        </form>
        <?php
        // Get Account Results From DB
          if (isset($data)) {
            $search_term = $data["search_term"];
            $genre = $data["genre"];
            $year = $data["year"];
            $order_by = $data["order_by"];
            $page = $_GET['page'];
            $query = getSearchBooksQuery($conn, $search_term, $genre, $year, $order_by, $page, $limit);
          }
          else {
            $query = getSearchBooksQuery($conn, '', 'All', 'All', 'Newest Release', $_GET['page'], $limit);
          }
          $result = getQueryResult($conn, $query);
        ?>

        <!-- PAGINATION -->
        <div class="row m-0 d-flex justify-content-center my-4">
          <?php
            if (isset($data)) {
              $query_all = getSearchBooksQuery($conn, $search_term, $genre, $year, $order_by, $page, 'None');
            }
            else {
              $query_all = getSearchBooksQuery($conn, '', 'All', 'All', 'Most Borrowed', $_GET['page'], 'None');
            }
            getPageCount(getQueryResult($conn, $query_all), $limit);
          ?>
        </div>

        <div class="mb-5 mt-2 mx-lg-5 mx-3">
          <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 justify-content px-lg-5 <?php echo ($result == false) ? 'd-none' : '' ; ?>">

            <?php
              getBooks($conn, $result, 'display');
            ?>

          </div>
          <!-- Show this if table has no result -->
          <div class="no-result-container <?php echo ($result != false) ? 'd-none' : '' ; ?>">
            <img src="icons/no-result.svg" alt="" class="mt-5">
            <h1 class="text-center mt-3">No Result!</h1>
          </div>
        </div>

    </div>

    <!-- Borrow Success Alert -->
    <div class="modal fade <?php echo (isset($_SESSION['borrow_result']) && $_SESSION['borrow_result'] == 'success') ? 'show_modal' : ''; ?>" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <div class="d-flex justify-content-center">
              <div class="d-flex flex-column justify-content-between align-items-center text-success">
                <i class="fa-solid fa-circle-check"></i>
                <span class="borrow-alert-text mt-2">Book borrowed successfully!</span>
                <p>Please check your profile to access borrowed books.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Borrow Failed Alert -->
    <div class="modal fade <?php echo (isset($_SESSION['borrow_result']) && $_SESSION['borrow_result'] == 'failed') ? 'show_modal' : ''; ?>" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <div class="d-flex justify-content-center">
              <div class="d-flex flex-column justify-content-between align-items-center text-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span class="borrow-alert-text mt-2">You've reached the book limit!</span>
                <p>Please remove atleast one borrowed book from your profile.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <?php
      include_once 'footer.php';

      // Reset session variable
      if (isset($_SESSION['borrow_result'])) {
        unset($_SESSION['borrow_result']);
      }
    ?>

    <script src="bootstrap_js/bootstrap.bundle.min.js"></script>
    <script src="main.js"></script>


  </body>
</html>
