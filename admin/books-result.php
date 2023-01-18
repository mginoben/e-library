<?php include 'includes/dbc-inc.php'; ?>
<tr>
  <td scope="num_col"><?php echo $row_num; ?></td>
  <td><?php echo $book_id; ?></td>
  <td><?php echo $title; ?></td>
  <td><?php echo $author; ?></td>
  <td><?php echo $genre; ?></td>
  <td>
    <div id="isbn_book<?php echo $book_id; ?>">
      <?php echo $isbn; ?>
      <a href="#" onclick="copy_data(isbn_book<?php echo $book_id; ?>)">
        <i class="fa-regular fa-copy"></i>
      </a>
    </div>
  </td>
  <td><?php echo $year; ?></td>
  <td><?php echo $page_count; ?></td>
  <td>

    <!-- Edit Btn -->
    <a href="#" data-bs-toggle="modal" data-bs-target="#edit_book<?php echo $book_id; ?>">
      <button type="button" name="button" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
        <i class="fa-solid fa-pen-to-square"></i>
      </button>
    </a>
    <!-- Show Book cover -->
    <a href="#" data-bs-toggle="modal" data-bs-target="#book_cover<?php echo $book_id; ?>">
      <button type="button" name="button" class="btn btn-warning mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Cover Photo">
        <i class="fa-solid fa-image"></i>
      </button>
    </a>
    <!-- Delete Btn -->
    <a href="#" data-bs-toggle="modal" data-bs-target="#drop_book<?php echo $book_id; ?>">
      <button type="button" name="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Drop">
        <i class="fa-solid fa-trash-can"></i>
      </button>
    </a>

  </td>
</tr>

<?php
  // POST values of edit form
  if (isset($_SESSION['edit_book_data'])) {
    $data = $_SESSION['edit_book_data'];
    unset($_SESSION['edit_book_data']);
  }
?>

<!-- Edit Book Popup -->
<div class="modal fade <?php echo (isset($_SESSION['edit_book_errors']) && $data['book_id'] == $book_id) ? 'show_modal' : ''; ?>" id="edit_book<?php echo $book_id; ?>"
    data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content mx-5">
      <div class="modal-body">
        <h4 class="text-center">Edit Book Information</h4>
        <form class="" action="includes/edit-book-inc.php" method="post">

          <!-- Error if book is taken -->
          <?php echo (isset($_SESSION['edit_book_errors']) && in_array("book_tkn", $_SESSION['edit_book_errors'])) ? "<p class='error-text'>Book is already taken</p>": ""; ?>

          <!-- Book Title -->
          <label class="mt-1">Title</label>
          <input type="text" name="title" class="form-control" placeholder="<?php echo $title; ?>"
          value="<?php echo (isset($data['title'])) ? $data['title'] : '' ; ?>"/>

          <!-- Subtitle-->
          <label class="mt-1">Subtitle</label>
          <textarea name="subtitle" class="form-control" rows="2" placeholder="<?php echo $subtitle; ?>"><?php echo (isset($data) && isset($data['subtitle'])) ? $data['subtitle'] : ''; ?></textarea>

          <!-- Author -->
          <label class="mt-1">Author</label>
          <input type="text" name="author" class="form-control" placeholder="<?php echo $author; ?>"
          value="<?php echo (isset($data['author'])) ? $data['author'] : '' ; ?>"/>

          <!-- Genre-->
          <label class="mt-1">Genre</label>
          <input type="search" list="genre_list" class="form-control" name="genre" placeholder="<?php echo $genre; ?>" autocomplete="off"
            value="<?php echo (isset($data['genre'])) ? $data['genre'] : '' ; ?>">
          <datalist id="genre_list">
            <?php getOptions($conn, $genre, "genre", "books"); ?>
          </datalist>

          <!-- ISBN -->
          <label class="mt-1">ISBN 13</label>
          <input type="text" name="isbn" class="form-control" maxlength="13"  placeholder="<?php echo $isbn; ?>"
            value="<?php echo (isset($data['isbn'])) ? $data['isbn'] : '' ; ?>"
            pattern=".{13,13}" title="ISBN should contain 13 digits"
            oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*?)\..*/g, '$1');" />

          <!-- Publication Year -->
          <label>Publication Year</label>
          <input type="text" name="year" class="form-control" maxlength="4"
            value="<?php echo (isset($data['year'])) ? $data['year'] : '' ; ?>"
            pattern="[12]{1,1}[0-9]{3,3}" title="Invalid year format" placeholder="<?php echo $year; ?>"
            oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*?)\..*/g, '$1');"/>

          <!-- Pages -->
          <label class="mt-1">Page Count</label>
          <input type="text" name="page_count" class="form-control" maxlength="5" placeholder="<?php echo $page_count; ?>"
            value="<?php echo (isset($data) && isset($data['page_count'])) ? $data['page_count'] : ''; ?>"
            oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*?)\..*/g, '$1');"/>

          <!-- Description -->
          <label class="mt-1">Description</label>
          <textarea name="description" class="form-control"  rows="4" placeholder="<?php echo $description; ?>"><?php echo (isset($data) && isset($data['description'])) ? $data['description'] : ''; ?></textarea>

          <!-- Book Cover link -->
          <label class="mt-1">Cover Image Address</label>
          <input type="url" name="cover" class="form-control" placeholder="cover-image.com/image.jpg"/>
          <?php echo (isset($_SESSION['edit_book_errors']) && in_array("url_inv", $_SESSION['edit_book_errors'])) ? "<p class='error-text'>Url is not a PNG or JPG content</p>": ""; ?>

          <!-- BOOK ID value -->
          <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">

          <!-- Submit -->
          <div class="d-flex justify-content-center mt-4">
            <button type="button" class="btn btn-secondary me-2 w-100" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="submit" class="btn btn-primary w-100">Save Edit</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<!-- Cover Popup -->
<div class="modal fade" id="book_cover<?php echo $book_id; ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content mx-5">
      <div class="modal-body">
        <img src="../<?php echo $cover; ?>" alt="<?php echo $title; ?>" class="book-cover">
        <button type="button" class="btn btn-secondary w-100 mt-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Remove Book Popup-->
<div class="modal fade" id="drop_book<?php echo $book_id; ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content mx-5">
      <div class="modal-body">
        <h4 class="modal-title mb-4">DELETE BOOK</h4>
        <p class="text-center fs-4">Are you sure you want to remove <span class="fw-bold fst-italic"><?php echo $title; ?></span>?</p>
        <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <input type="hidden" name="book_id" class="form-control" value="<?php echo $book_id; ?>"/>
          <button type="submit" name="remove" class="btn w-100 btn-danger mt-4">Remove</button>
          <button type="button" class="btn btn-secondary w-100 mt-2" data-bs-dismiss="modal">Close</button>
        </form>
      </div>
    </div>
  </div>
</div>
