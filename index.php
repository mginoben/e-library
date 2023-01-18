<?php
  require_once 'includes/dbc-inc.php';
  require_once 'includes/functions-inc.php';
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

    <title>Home</title>
  </head>
  <body>

    <?php
      include_once 'header.php';
    ?>

    <div class="container my-5">

        <?php
          include_once 'loading_animation.php';
        ?>
        <!-- WELCOME GREETINGS -->
        <div class="alert alert-success alert-dismissible fade show <?php echo isset($_SESSION['greet_alert']) ? '' : 'd-none'; ?>" role="alert">
          <strong>Hi <?php echo getAccount($conn, $_SESSION['umak_id'])['name'] ?>!</strong> We are glad to see you here.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          <?php unset($_SESSION['greet_alert']); ?>
        </div>

        <!-- Most Borrowed Section -->
        <div class="mt-4">
          <span class="books-group-title">MOST BORROWED</span>
          <hr class="bg-white m-0 ">
          <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 justify-content">
            <?php
            $sql = "SELECT * FROM books ORDER BY borrow_count DESC LIMIT 5;";
            $result = getQueryResult($conn, $sql);
            getBooks($conn, $result, 'display');
            ?>
          </div>
        </div>

        <!-- New Release Section -->
        <div class="mt-4">
          <span class="books-group-title">NEW RELEASE</span>
          <hr class="bg-white m-0 ">
          <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 justify-content">
            <?php
            $sql = "SELECT * FROM books ORDER BY date_uploaded DESC LIMIT 5;";
            $result = getQueryResult($conn, $sql);
            getBooks($conn, $result, 'display');
            ?>
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
