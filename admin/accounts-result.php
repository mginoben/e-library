<tr>
  <td scope="num_col"><?php echo $row_num; ?></td>
  <td class="id_col"><?php echo $umak_id; ?></td>
  <td><?php echo $name; ?></td>
  <td><?php echo $course; ?></td>
  <td><?php echo $contact; ?></td>
  <td><?php echo $email; ?></td>
  <td><?php echo $status; ?></td>
  <td><?php echo $date_created; ?></td>
  <td>

    <!-- Buttons for pending Account -->

    <!-- Reject Btn -->
    <a href="#" data-bs-toggle="modal" data-bs-target="#rejectModal<?php echo $umak_id; ?>" class="<?php echo ($status == 'Verified') ? 'd-none' : ''; ?>">
      <button type="button" name="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Reject">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </a>
    <!-- Verify Btn -->
    <a href="#" data-bs-toggle="modal" data-bs-target="#verifyModal<?php echo $umak_id; ?>" class="<?php echo ($status == 'Verified') ? 'd-none' : ''; ?>">
      <button type="button" name="verify" class="btn btn-success mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Verify">
        <i class="fa-solid fa-check"></i>
      </button>
    </a>

    <!-- Buttons for verified Account -->

    <!-- Delete Btn -->
    <a href="#" data-bs-toggle="modal" data-bs-target="#dropModal<?php echo $umak_id; ?>" class="<?php echo ($status == 'Pending') ? 'd-none' : ''; ?>">
      <button type="button" name="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Drop">
        <i class="fa-solid fa-trash-can"></i>
      </button>
    </a>
    <!-- Edit Btn -->
    <a href="#" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $umak_id; ?>" class="<?php echo ($status == 'Pending') ? 'd-none' : ''; ?>">
      <button type="button" name="button" class="btn btn-warning mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
        <i class="fa-solid fa-pen-to-square"></i>
      </button>
    </a>

    <!-- Button for both -->

    <!-- Show ID Btn -->
    <a href="#" data-bs-toggle="modal" data-bs-target="#idModal<?php echo $umak_id; ?>">
      <button type="button" name="button" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="ID Photo">
        <i class="fa-solid fa-id-card"></i>
      </button>
    </a>

  </td>
</tr>

<!-- ID POPUP -->
<div class="modal fade" id="idModal<?php echo $umak_id; ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content mx-5">
      <div class="modal-body">
        <h4 class="modal-title mb-4">UMAK ID</h4>
        <?php echo '<img src="data:image/jpeg;base64,'.base64_encode($id_pic).'" class="img-fluid w-100"/>';?>
        <button type="button" class="btn btn-secondary w-100 mt-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php if (isset($_SESSION['edit_account_data'])) {
  $data = $_SESSION['edit_account_data'];
} ?>

<!-- EDIT POPUP -->
<div class="modal fade <?php echo (isset($_SESSION['adit_account_errors']) && $data['umak_id'] == $umak_id) ? 'show_modal' : ''; ?>" id="editModal<?php echo $umak_id; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content mx-5">
      <div class="modal-body">
        <h4 class="text-center">Edit Account Information</h4>
        <form class="" action="includes/edit-account-inc.php" method="post">

          <!-- New Name -->
          <label>Account Name</label>
          <input type="text" name="new_name" class="form-control" placeholder="<?php echo $name; ?>"
          value="<?php echo (isset($data) && isset($data['new_name']) && $data['umak_id'] == $umak_id) ? $data['new_name'] : ''; ?>"
            pattern="^[a-zA-Z.,ñÑ ]*$" title="Only letters, comma (,), and periods (.) are allowed"/>
          <?php echo (isset($_SESSION['adit_account_errors']) && in_array("name_tkn", $_SESSION['adit_account_errors']) && $data['umak_id'] == $umak_id) ? "<p class='error-text'>Name is already taken</p>": ""; ?>

          <!-- New Course -->
          <label class="mt-3">Course</label>
          <select name="new_course" class="form-control">
            <option <?php echo ($course=='Computer Science') ? 'selected' : ''; ?> >Computer Science</option>
            <option <?php echo ($course=='Psychology') ? 'selected' : ''; ?> >Psychology</option>
            <option <?php echo ($course=='Arts & Letters') ? 'selected' : ''; ?> >Arts & Letters</option>
            <option <?php echo ($course=='Education') ? 'selected' : ''; ?> >Education</option>
            <option <?php echo ($course=='Nursing') ? 'selected' : ''; ?> >Nursing</option>
            <option <?php echo ($course=='Tourism') ? 'selected' : ''; ?> >Tourism</option>
          </select>

          <!-- New Contact Number -->
          <label class="mt-3">Contact Number</label>
          <input type="text" name="new_contact" class="form-control" maxlength="11" placeholder="<?php echo $contact; ?>"
            value="<?php echo (isset($data) && isset($data['new_contact']) && $data['umak_id'] == $umak_id) ? $data['new_contact'] : ''; ?>"
            oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*?)\..*/g, '$1');"
            pattern="09[0-9]{9}$" title="Must be 11 digits and starts with 09"/>

          <!-- New Email -->
          <label class="mt-3">Email</label>
          <input type="email" name="new_email" class="form-control" placeholder="<?php echo $email; ?>"
          value="<?php echo (isset($data) && isset($data['new_email']) && $data['umak_id'] == $umak_id) ? $data['new_email'] : ''; ?>"
          pattern="[A-Za-z]+\.[AaKk][0-9]+@umak\.edu\.ph" title="Email format must be like [name].[studentID]@umak.edu.ph"/>
          <?php
            echo (isset($_SESSION['adit_account_errors']) && in_array("email_tkn", $_SESSION['adit_account_errors']) && $data['umak_id'] == $umak_id) ? "<p class='error-text'>Email already exists</p>": "";
          ?>

          <!-- UMAK ID value -->
          <input type="hidden" name="umak_id" class="form-control" value="<?php echo $umak_id; ?>"/>

          <!-- Submit -->
          <div class="d-flex justify-content-center mt-5">
            <button type="button" class="btn btn-secondary me-2 w-100" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="save" class="btn btn-primary w-100">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- VERIFY POPUP -->
<div class="modal fade" id="verifyModal<?php echo $umak_id; ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content mx-5">
      <div class="modal-body">
        <h4 class="modal-title mb-4">VERIFY ACCOUNT</h4>
        <p class="text-center fs-4">Are you sure you want to verify student <?php echo $umak_id; ?>?</p>
        <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <input type="hidden" name="umak_id" class="form-control" value="<?php echo $umak_id; ?>"/>
          <button type="submit" name="verify" class="btn w-100 btn-success">Verify</button>
          <button type="button" class="btn btn-secondary w-100 mt-2" data-bs-dismiss="modal">Close</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- REJECT POPUP -->
<div class="modal fade" id="rejectModal<?php echo $umak_id; ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content mx-5">
      <div class="modal-body">
        <h4 class="modal-title mb-4">REJECT ACCOUNT</h4>
        <p class="text-center fs-4">Are you sure you want to reject student <?php echo $umak_id; ?>?</p>
        <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <!-- List of Reasons -->
          <label>Reason</label>
          <input type="search" list="reasonList" class="form-control" name="reason" autocomplete="off" required>
          <datalist id="reasonList">
             <option>Invalid ID picture</option>
             <option>ID picture is not visible</option>
             <option>ID number does not exists</option>
             <option>Account and ID information do not match</option>
          </datalist>

          <input type="hidden" name="umak_id" class="form-control" value="<?php echo $umak_id; ?>"/>
          <button type="submit" name="reject" class="btn w-100 btn-danger mt-4">Reject</button>
          <button type="button" class="btn btn-secondary w-100 mt-2" data-bs-dismiss="modal">Close</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- REMOVE POPUP -->
<div class="modal fade" id="dropModal<?php echo $umak_id; ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content mx-5">
      <div class="modal-body">
        <h4 class="modal-title mb-4">REJECT ACCOUNT</h4>
        <p class="text-center fs-4">Are you sure you want to drop <b><?php echo $name; ?></b>?</p>
        <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <input type="hidden" name="umak_id" class="form-control" value="<?php echo $umak_id; ?>"/>
          <button type="submit" name="remove" class="btn w-100 btn-danger mt-4">Remove</button>
          <button type="button" class="btn btn-secondary w-100 mt-2" data-bs-dismiss="modal">Close</button>
        </form>
      </div>
    </div>
  </div>
</div>
