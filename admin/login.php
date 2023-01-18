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

    <title>Login</title>
  </head>
  <body>

    <?php
      include_once 'header.php';
    ?>

    <div class="container-fluid d-flex justify-content-center align-items-center">
      <div class="form-wrapper row p-4 m-lg-5">

        <form action="includes/login-inc.php" method="post">
          <h3 class="text-center mb-3">Admin Login</h3>

          <div class="mb-4">
              <input type="text" name="username" class="form-control" placeholder="Username" value="<?php echo (isset($_SESSION['login_data']['username'])) ? $_SESSION['login_data']['username'] : '' ; ?>" required>
              <?php echo (isset($_SESSION['login_error']) && $_SESSION['login_error'] == 'no_user') ? "<p class='error-text m-0'>Username does not exists</p>" : ""; ?>
          </div>
          <div>
              <input type="password" name="password" class="form-control" placeholder="Password" required>
              <?php echo (isset($_SESSION['login_error']) && $_SESSION['login_error'] == 'wrong_pass') ? "<p class='error-text m-0'>Incorrect password</p>" : ""; ?>
          </div>
          <div class="mt-4">
            <button type="submit" name="submit" class="login-btn my-3">Login</button>
            <a href="../login.php" class="fw-bold"><i class="fa-solid fa-user"></i> User</a>
          </div>
        </form>

      </div>
    </div>

    <?php
      include_once 'footer.php';
    ?>


    <script src="../bootstrap_js/bootstrap.bundle.min.js"></script>

  </body>

</html>
