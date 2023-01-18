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

    <title>Request a Book</title>

  </head>
  <!-- Change to own 'Request' CSS Style if needed -->
  <body>

    <?php
      include_once 'header.php';

      // Request data
      if (isset($_SESSION['request_data'])) {
        $data = $_SESSION['request_data'];
      }
    ?>

    <div class="container-fluid d-flex justify-content-center align-items-center p-2">
      <?php
        include_once 'loading_animation.php';
      ?>
      <div class="form-wrapper col-12 col-sm-8 col-lg-6 p-5">

          <div class="align-self-center justify-content-center">
            <p class="signup-title text-center mb-3">Request a Book!</p>
          </div>

          <form class="px-0 px-sm-5 d-flex flex-column" action="includes/request-inc.php" method="post">

            <!-- TITLE -->
            <div class="mb-3">
              <label>Title</label>
              <input type="text" name="title" class="form-control" placeholder="Umak Student Handbook"  value="<?php echo (isset($data['title'])) ? $data['title'] : '' ; ?>" required/>
            </div>

            <!-- AUTHOR -->
            <div class="mb-3">
              <label>Author</label>
              <input type="text" name="author" class="form-control" placeholder="John Doe"  value="<?php echo (isset($data['author'])) ? $data['author'] : '' ; ?>" required/>
            </div>

            <!-- PUBLICATION YEAR -->
            <div class="mb-3">
              <label>Publication Year</label>
              <input type="text" name="publication_year" class="form-control" maxlength="4" placeholder="2022"
                value="<?php echo (isset($data['publication_year'])) ? $data['publication_year'] : '' ; ?>"
                pattern="[12]{1,1}[0-9]{3,3}" title="Invalid year format"
                oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*?)\..*/g, '$1');" required/>
            </div>

            <!-- ISBN -->
            <div class="mb-3">
              <label>ISBN 13</label>
              <input type="text" name="isbn" class="form-control" maxlength="13"  placeholder="1234567890123"
                value="<?php echo (isset($data['isbn'])) ? $data['isbn'] : '' ; ?>"
                pattern=".{13,13}" title="ISBN should contain 13 digits"
                oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*?)\..*/g, '$1');" required/>
            </div>

            <!-- BOok Website -->
            <div class="">
              <label>Book Website</label>
              <input type="url" name="book_web" class="form-control" placeholder="www.umak.edu.ph"
                value="<?php echo (isset($data['book_web'])) ? $data['book_web'] : '' ; ?>" required/>
            </div>

            <!-- Request Error -->
            <div class="align-self-center mt-1">
              <?php
                echo (isset($_SESSION['request_error']) && $_SESSION['request_error'] == 'book_exists') ? "<p class='error-text'>Book already exists</p>": "";
                unset($_SESSION['request_error']);
              ?>
            </div>

            <!-- Submit and Clear Button -->
            <div class="row row-cols-1 row-cols-md-2 d-flex justify-content-center mt-2">
              <div class="mt-4 mt-sm-4 col-sm-4 col-md-4">
                <button type="submit" name="submit" class="signup-form-btn">SUBMIT</button>
              </div>
              <div class="mt-2 mt-sm-4 col-sm-4 col-md-4">
                <button onclick="location.href='request.php'" name="reset" class="signup-form-btn-clear">CLEAR</button>
              </div>
            </div>

          </form>
      </div>
    </div>

    <?php
      include_once 'footer.php';
    ?>

    <!-- POPUP MESSAGE -->
    <div class="modal fade" id="request_result" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <div class="d-flex justify-content-center">
              <div class="d-flex flex-column justify-content-between align-items-center">
                <i class="fa-solid fa-circle-check text-success"></i>
                <span class="borrow-alert-text text-success">Request sent!</span>
                <p class="text-center">Your request will be processed by the admin. Thank you!</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  <script src="bootstrap_js/bootstrap.bundle.min.js"></script>

  <!-- TOGGLE POPUP MESSAGE -->
  <?php
    if (isset($_SESSION['request'])) {
      echo '
      <script type="text/javascript">
        var requestResult = new bootstrap.Modal(document.getElementById("request_result"), {});
        requestResult.toggle();
      </script>
      ';
      unset($_SESSION['request']);
    }
  ?>

  </body>
</html>
