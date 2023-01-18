<?php
  if (isset($_SESSION['borrow_result']) && $_SESSION['borrow_result'] == "success") {
    $borrow_result = '<div class="d-flex flex-column justify-content-between align-items-center">
                        <i class="fa-solid fa-circle-check text-success"></i>
                        <span class="borrow-alert-text text-success mt-2">Book borrowed successfully!</span>
                        <p class="text-center">Please check your profile to access borrowed books.</p>
                      </div>';
  }
  elseif (isset($_SESSION['borrow_result']) && $_SESSION['borrow_result'] == "failed") {
    $borrow_result = '<div class="d-flex flex-column justify-content-between align-items-center">
                        <i class="fa-solid fa-circle-exclamation text-danger"></i>
                        <span class="borrow-alert-text text-danger mt-2">You\'ve reached the book limit!</span>
                        <p class="text-center">Please remove atleast one borrowed book from your profile.</p>
                      </div>
                      ';
  }
  else {
    $borrow_result = '';
  }
?>

<div class="modal fade" id="borrow_result" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="d-flex justify-content-center">
          <?php echo $borrow_result; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
  if (isset($_SESSION['borrow_result'])) {
    echo '
    <script type="text/javascript">
      var borrowResult = new bootstrap.Modal(document.getElementById("borrow_result"), {});
      borrowResult.toggle();
    </script>
    ';
    unset($_SESSION['borrow_result']);
  }
?>
