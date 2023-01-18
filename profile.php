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
    <link href="bootstrap_css/bootstrap.min.css" rel="stylesheet">
    <!-- Style CSS -->
    <link rel="stylesheet" href="style.css?<?php echo time(); ?>">

    <title>Profile</title>
  </head>
  <body>

    <?php
      include_once 'header.php';

      // Get User Account
      $account = getAccount($conn, $_SESSION['umak_id']);
    ?>

    <div class="container-fluid p-0 m-0">

      <?php
        include_once 'loading_animation.php';
      ?>

      <div class="row w-100 m-0 d-flex profile-info-container d-flex p-4">

        <div class="col-4 col-md-3 d-flex justify-content-md-end">
          <?php
          if (!empty($account["profile_pic"])) {
            echo '<img src="data:image/jpeg;base64,'.base64_encode($account["profile_pic"]).'" class="profile-image"/>';
          }
          else {
            echo '<img src="img/user_image.png" class="profile-image">';
          }
          ?>
        </div>

        <div class="col-md-6 my-3 my-md-0">
          <p class="profile-name m-0"><?php echo $account['name']; ?></p>
          <p class="profile-info-text"><span class="profile-info-title">UMAK ID </span><?php echo $account['umak_id']; ?></p>
          <p class="profile-info-text"><span class="profile-info-title">COURSE </span><?php echo $account['course']; ?></p>
          <p class="profile-info-text"><span class="profile-info-title">CONTACT </span><?php echo $account['contact']; ?></p>
          <p class="profile-info-text"><span class="profile-info-title">EMAIL </span><?php echo $account['email']; ?></p>
        </div>

        <div class="col-md-3 d-flex justify-content-end justify-content-md-start align-items-end">

          <!-- SHOW ID BUTTON -->
          <a href="#" class="card-link" data-bs-toggle="modal" data-bs-target="#idModal">
            <i class="fa-solid fa-id-card"></i>
          </a>

          <!-- EDIT BUTTON -->
          <a href="#" class="card-link" data-bs-toggle="modal" data-bs-target="#editModal">
            <i class="fa-solid fa-pen-to-square"></i>
          </a>

          <!-- LOGOUT BUTTON -->
          <a href="includes\logout-inc.php" class="card-link"><i class="fa-solid fa-right-from-bracket"></i></a>

        </div>
      </div>

      <?php
      // Get borrowed books from db
        $sql = "SELECT * FROM accounts_has_books INNER JOIN accounts ON accounts_has_books.umak_id = accounts.umak_id INNER JOIN books ON accounts_has_books.book_id = books.book_id WHERE accounts_has_books.umak_id='".$account['umak_id']."';";
        $result = getQueryResult($conn, $sql);
      ?>

      <div class="row d-flex justify-content-center mb-5 mx-2">
        <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center <?php echo ($result == false) ? 'd-none' : ''; ?>">
          <div class="text-center borrowed-books-title my-3">BOOK COLLECTION</div>
          <?php
            getBooks($conn, $result, 'borrowed');
          ?>
        </div>
        <!-- Show this if table has no result -->
        <div class="no-result-container <?php echo ($result != false) ? 'd-none' : '' ; ?>">
          <img src="icons/no-result.svg" alt="" class="mt-5">
          <h1 class="text-center mt-3">No Borrowed Books!</h1>
          <p>You can borrow atleast three books at a time.</p>
        </div>
      </div>
      <!-- EDIT POPUP -->
      <div class="modal fade <?php echo (isset($_SESSION['edit_profile_errors'])) ? 'show_modal' : ''; ?>" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content mx-5">
            <div class="modal-body">
              <h4 class="text-center">Edit Account Information</h4>
              <form action="includes/edit-profile-inc.php" method="post" enctype="multipart/form-data" autocomplete="off">

                <label>Profile Picture (1:1)</label>
                <input type="file" name="new_profile_pic" class="form-control" accept="image/*"/>
                <?php echo (isset($_SESSION['edit_profile_errorss']) && in_array("max_img_size", $errors)) ? "<p class='error-text'>Maximum of 1 MB</p>" : ""; ?>

                <label class="mt-3">Contact Number</label>
                <input type="text" name="new_contact" class="form-control" placeholder="<?php echo $account['contact']; ?>" maxlength="11" value="<?php echo (isset($_SESSION['edit_data']['new_contact'])) ? $_SESSION['edit_data']['new_contact'] : ''; ?>"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*?)\..*/g, '$1');"/>
                <?php echo (isset($_SESSION['edit_profile_errors']) && in_array("con_inv", $_SESSION['edit_profile_errors'])) ? "<p class='error-text'>Contact number must be 11 disgits starting with 09</p>" : ""; ?>

                <label class="mt-3">Email</label>
                <input type="text" name="new_email" class="form-control" placeholder="<?php echo $account['email']; ?>" value="<?php echo (isset($_SESSION['edit_data']['new_email'])) ? $_SESSION['edit_data']['new_email'] : ''; ?>"/>
                <?php
                  echo (isset($_SESSION['edit_profile_errors']) && in_array("email_inv", $_SESSION['edit_profile_errors'])) ? "<p class='error-text'>Invalid email</p>" : "";
                  echo (isset($_SESSION['edit_profile_errors']) && in_array("email_tkn", $_SESSION['edit_profile_errors'])) ? "<p class='error-text'>Email already exists</p>": "";
                    echo (isset($_SESSION['edit_profile_errors']) && in_array("input_empty", $_SESSION['edit_profile_errors'])) ? "<p class='error-text text-center mt-2'>Please fill up atleast one field</p>": "";
                ?>

                <div class="d-flex justify-content-center mt-5">
                  <button type="button" name="close" class="btn btn-secondary me-2 col-6" data-bs-dismiss="modal">Close</button>
                  <button type="submit" name="save" class="btn btn-primary col-6">Save</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- ID POPUP -->
      <div class="modal fade" id="idModal" tabindex="-1" aria-labelledby="idTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content mx-5">
            <div class="modal-body">
              <h4 class="text-center mb-4">UMak ID</h4>
              <div class="">
                <?php echo '<img src="data:image/jpeg;base64,'.base64_encode($account["id_pic"]).'" class="profile-id-pic"/>';?>
              </div>
              <div class="d-flex justify-content-center mt-4">
                <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <?php
      include_once 'footer.php';

      // Reset session variables
      if (isset($_SESSION['edit_data'])) {
        unset($_SESSION['edit_data']);
      }
      if (isset($_SESSION['edit_profile_errors'])) {
        unset($_SESSION['edit_profile_errors']);
      }
    ?>

    <script src="bootstrap_js/bootstrap.bundle.min.js"></script>
    <script src="main.js"></script>


  </body>

</html>
