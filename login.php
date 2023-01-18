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

    <title>Login</title>
  </head>
  <body>

    <?php
      include_once 'header.php';
    ?>

    <div class="container-fluid d-flex justify-content-center align-items-center">
      <div class="form-wrapper row p-0 m-lg-5 m-1">
        <?php
          include_once 'loading_animation.php';
        ?>

        <!-- LEFT IMAGE -->
        <div class="col-md-6 col-lg-8 p-0">
          <img src="img/books_login.jpg" class="img-fluid h-100 w-100" alt="">
        </div>

        <!-- RIGHT FORM -->
        <div class="col-md-6 col-lg-4 d-flex flex-column p-4">

          <img src="icons/brand_icon_full.png" class="login-logo img-fluid align-self-center mb-5" alt="">

          <form action="includes/login-inc.php" method="post">
              <div class="mb-4">
                  <input type="text" name="umak_id" class="form-control" placeholder="UMak ID" value="<?php echo (isset($_SESSION['login_data'])) ? $_SESSION['login_data']['umak_id'] : '' ; ?>" required>
                  <?php echo (isset($_SESSION['login_error']) && $_SESSION['login_error'] == 'no_id') ? "<p class='error-text m-0'>Umak ID does not exists</p>" : ""; ?>
              </div>
              <div>
                  <input type="password" name="password" class="form-control" placeholder="Password" required>
                  <?php
                    if (isset($_SESSION['login_error']) && $_SESSION['login_error'] == 'wrong_pass') {
                      echo "<p class='error-text m-0'>Incorrect password</p>";
                    }
                  ?>
              </div>
              <div class="mt-5">
                <button type="submit" name="submit" class="login-btn my-3">Login</button>
                <p class="m-0">Don't have an account? <a href="signup.php" class="fw-bold">Sign Up</a></p>
                <a href="admin/index.php" class="fw-bold"><i class="fa-solid fa-user-gear"></i> Admin</a>
              </div>
          </form>
        </div>
      </div>

    </div>

    <?php
      include_once 'footer.php';
    ?>

    <!-- PENDING ACCOUNT MESSAGE -->
    <div class="modal fade" id="accountPendingModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <div class="d-flex justify-content-center">
              <div class="d-flex flex-column justify-content-between align-items-center">
                <i class="fa-solid fa-triangle-exclamation borrow-alert-color"></i>
                <span class="borrow-alert-text borrow-alert-color mt-2">Your account is currently under review.</span>
                <p class="text-center">Please wait for the confirmation of the admin.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- REJECT ACCOUNT MESSAGE -->
    <div class="modal fade" id="accountRejectedModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <div class="d-flex justify-content-center">
              <div class="d-flex flex-column justify-content-between align-items-center">
                <i class="fa-solid fa-circle-exclamation text-danger"></i>
                <span class="borrow-alert-text text-danger mt-2">Sorry, your account has been rejected.</span>
                <span class="text-center fw-bold">REASON</span>
                <p class="text-center"><?php echo (isset($_SESSION['reject_reason'])) ? $_SESSION['reject_reason'] : ''; ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="bootstrap_js/bootstrap.bundle.min.js"></script>

    <!-- SHOW pending account message IF status == pending -->
    <?php
      if (empty($_SESSION['login_error']) && isset($_SESSION['account_status']) && $_SESSION['account_status'] == 'Pending') {
        echo '
        <script type="text/javascript">
          var accountStatus = new bootstrap.Modal(document.getElementById("accountPendingModal"), {});
          accountStatus.toggle();
        </script>
        ';
        unset($_SESSION['account_status']);
      }
      elseif (empty($_SESSION['login_error']) && isset($_SESSION['account_status']) && $_SESSION['account_status'] == 'Rejected') {
        echo '
        <script type="text/javascript">
          var accountStatus = new bootstrap.Modal(document.getElementById("accountRejectedModal"), {});
          accountStatus.toggle();
        </script>
        ';
        unset($_SESSION['account_status']);
        unset($_SESSION['reject_reason']);
      }

      unset($_SESSION['login_error']);
    ?>

  </body>

</html>
