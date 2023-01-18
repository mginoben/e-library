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

    <title>Sign Up</title>
  </head>
  <body>

    <?php
      include_once 'header.php';
    ?>

    <div class="container-fluid d-flex justify-content-center align-items-center">
      <?php
        include_once 'loading_animation.php';
      ?>
      <div class="form-wrapper col-sm-10 col-lg-6 p-5 m-2 m-sm-0">
        <div class="row d-flex justify-content-center">
          <p class="signup-title text-center my-1 mb-3">CREATE YOUR ACCOUNT</p>
        </div>
        <form action="includes/signup-inc.php" method="post" enctype="multipart/form-data" autocomplete="off">
          <div class="row justify-content-evenly">

            <!-- LEFT FORM -->
            <div class="col-lg-6 px-2">

              <div class="mb-4">
                <label>UMAK ID Number</label>
                <input type="text" name="umak_id" class="form-control" placeholder="K#########"
                  value="<?php echo (isset($_SESSION['signup_data'])) ? $_SESSION['signup_data']['umak_id'] : '' ; ?>"
                  pattern="^[KkAa][0-9]*$" title="ID should start with K or A followed by numbers" required/>
                <?php echo (isset($_SESSION['signup_error']) && in_array("id_tkn", $_SESSION['signup_error'])) ? "<p class='error-text'>ID number already exists</p>": ""; ?>
              </div>

              <div class="mb-4">
                <label>Name <span class="fst-italic text-secondary">(Refer to your UMak ID)</span></label>
                <input type="text" name="name" class="form-control" placeholder="Juan Dela Cruz"
                  value="<?php echo (isset($_SESSION['signup_data'])) ? $_SESSION['signup_data']['name'] : '' ; ?>"
                  pattern="^[a-zA-Z.,ñÑ ]*$" title="Only letters, comma (,), and periods (.) are allowed" required/>
                <?php echo (isset($_SESSION['signup_error']) && in_array("name_tkn", $_SESSION['signup_error'])) ? "<p class='error-text'>Name is already taken</p>": ""; ?>
              </div>

              <div class="mb-4">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="********" pattern=".{6,}" title="6 or more characters" required/>
              </div>

              <div class="mb-4">
                <label>Re-Type Password</label>
                <input type="password" name="re_password" class="form-control" placeholder="********" pattern=".{6,}" title="6 or more characters" required/>
              </div>

            </div>
            <!-- RIGHT FORM -->
            <div class="col-lg-6 px-4">

              <div class="mb-4">
                <label>Course</label>
                <select name="course" class="form-control">
                  <?php
                    $selected = (isset($_SESSION['signup_data'])) ? $_SESSION['signup_data']['course'] : '';
                  ?>
                  <option <?php echo ($selected == 'Computer Science') ? 'selected' : ''; ?> >Computer Science</option>
                  <option <?php echo ($selected == 'Psychology') ? 'selected' : ''; ?> >Psychology</option>
                  <option <?php echo ($selected == 'Arts & Letters') ? 'selected' : ''; ?> >Arts & Letters</option>
                  <option <?php echo ($selected == 'Education') ? 'selected' : ''; ?> >Education</option>
                  <option <?php echo ($selected == 'Nursing') ? 'selected' : ''; ?> >Nursing</option>
                  <option <?php echo ($selected == 'Tourism') ? 'selected' : ''; ?> >Tourism</option>
                </select>
              </div>

              <div class="mb-4">
                <label>Contact Number</label>
                <input type="text" name="contact" class="form-control" placeholder="09#########" maxlength="11" value="<?php echo (isset($_SESSION['signup_data'])) ? $_SESSION['signup_data']['contact'] : ''; ?>"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*?)\..*/g, '$1');"
                    pattern="09[0-9]{9}$" title="Must be 11 digits and starts with 09" required/>
              </div>

              <div class="mb-4">
                <label>UMAK Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="juan.k11@umak.edu.ph"
                value="<?php echo (isset($_SESSION['signup_data'])) ? $_SESSION['signup_data']['email'] : '';?>"
                pattern="[A-Za-z]+\.[AaKk][0-9]+@umak\.edu\.ph" title="Email format must be like [name].[studentID]@umak.edu.ph" required/>
                <?php echo (isset($_SESSION['signup_error']) && in_array("email_tkn", $_SESSION['signup_error'])) ? "<p class='error-text'>Email already exists</p>": ""; ?>
              </div>

              <div class="mb-4">
                <label>ID Picture</label>
                <input type="file" name="id_pic" class="form-control" accept="image/*"  required/>
                <?php if (isset($errors)) {
                  echo (isset($_SESSION['signup_error']) && in_array("max_img_size", $_SESSION['signup_error'])) ? "<p class='error-text'>Maximum of 1 MB</p>": "";
                } ?>
              </div>

            </div>
            <div class="col-lg-12 col-sm-6 mt-3">
              <div class="row d-flex justify-content-center">
                <div class="col-sm-6 col-md-4 col-lg-3 mb-4 mb-sm-0">
                  <button type="submit" name="submit" class="signup-form-btn">SUBMIT</button>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                  <button onclick="location.href='signup.php'" name="reset" class="signup-form-btn-clear">CLEAR</button>
                </div>
              </div>
            </div>
            <div class="col-12 mt-2 m-0">
              <p class="text-center">Already have an account? <a href="login.php" class="fw-bold">LOG IN</a></p>
            </div>

          </div>

        </form>
      </div>

      <!-- Success MESSAGE -->
      <div class="modal fade <?php echo isset($_SESSION['signup_success']) ? 'show_modal' : ''; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <div class="d-flex flex-column justify-content-between align-items-center">
                <i class="fa-solid fa-circle-check text-success"></i>
                <span class="borrow-alert-text text-success">Thank you for signing up!</span>
                <p class="text-center">Please wait for the admin for your account validation.
                You can check your account status by logging in your account credentials</p>
                <div class="">
                  <button type="button" class="btn btn-secondary p-2" data-bs-dismiss="modal">Close</button>
                  <a href="login.php"><button type="button" class="btn btn-primary p-2" data-bs-dismiss="modal">Go to Login</button></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php
      include_once 'footer.php';

      // Reset Session variable
      if (isset($_SESSION['signup_success'])) {
        unset($_SESSION['signup_success']);
      }
      if (isset($_SESSION['signup_error'])) {
        unset($_SESSION['signup_error']);
      }
      if (isset($_SESSION['signup_data'])) {
        unset($_SESSION['signup_data']);
      }
    ?>

    <script src="bootstrap_js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="main.js">

    </script>
  </body>

</html>
