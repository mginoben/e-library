<?php include 'includes/dbc-inc.php'; ?>
<tr>
  <td scope="row"><?php echo $row_num; ?></td>
  <td><?php echo $student; ?></td>
  <td><?php echo $title; ?></td>
  <td><?php echo $author; ?></td>
  <td>
    <div id="req<?php echo $row_num; ?>">
      <?php echo $isbn; ?>
      <a href="#" onclick="copy_data(req<?php echo $row_num; ?>)">
        <i class="fa-regular fa-copy"></i>
      </a>
    </div>
  </td>
  <td><?php echo $publication_year; ?></td>
  <td>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="p-0 m-0">
      <input type="hidden" name="request_id" value="<?php echo $req_id; ?>">
      <!-- Book URL -->
      <a href="<?php echo $url; ?>" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Book URL">
        <i class="fa-solid fa-arrow-up-right-from-square btn btn-warning fs-6 fw-bold"></i>
      </a>
      <!-- Add Book button -->
      <a href="#" data-bs-toggle="modal" class="mx-1" data-bs-target="#addBook<?php echo $req_id; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Book">
        <i class="fa-solid fa-plus btn btn-warning fs-6 fw-bold"></i>
      </a>
      <!-- Remove Request -->
      <button type="submit" name="remove" class="btn btn-danger fs-6" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove Request">
        <i class="fa-solid fa-trash-can"></i>
      </button>
    </form>

  </td>
</tr>

<?php if (isset($_SESSION['add_book_data'])) {
  $data = $_SESSION['add_book_data'];
} ?>

<!-- Add Book Popup -->
<div class="modal fade <?php echo (isset($_SESSION['add_book']) && $_SESSION['add_book']=='failed' && isset($_SESSION['request_id']) && $_SESSION['request_id'] == $req_id) ? 'show_modal' : ''; ?>"
  id="addBook<?php echo $req_id; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content mx-5">
      <div class="modal-body">
        <h4 class="text-center">Add Book</h4>
        <form action="includes/add-book-inc.php" method="post">

          <!-- Title-->
          <label class="mt-1">Title</label>
          <input type="text" name="title" class="form-control" value="<?php echo (isset($data['title'])) ? $data['title'] : $title; ?>" required/>

          <!-- Subtitle-->
          <label class="mt-1">Subtitle</label>
          <textarea name="subtitle" class="form-control"rows="2"><?php echo (isset($data['subtitle'])) ? $data['subtitle'] : ''; ?></textarea>

          <!-- Author-->
          <label class="mt-1">Author</label>
          <input type="text" name="author" class="form-control" value="<?php echo (isset($data['author'])) ? $data['author'] : $author; ?>" required/>

          <!-- Genre-->
          <label class="mt-1">Genre</label>
          <input type="search" list="genre_list" class="form-control" name="genre" autocomplete="off"
            value="<?php echo (isset($data) && isset($data['genre'])) ? $data['genre'] : ''; ?>" required>
          <datalist id="genre_list">
            <?php getOptions($conn, $genre, "genre", "books"); ?>
          </datalist>

          <!-- ISBN -->
          <label class="mt-1">ISBN</label>
          <input type="text" name="isbn" class="form-control" value="<?php echo (isset($data['isbn'])) ? $data['isbn'] : $isbn; ?>"
          pattern=".{13,13}" title="ISBN should contain 13 digits" maxlength="13"
          oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*?)\..*/g, '$1');" required/>

          <!-- Publication Year -->
          <label class="mt-1">Publication Year</label>
          <input type="text" name="publication_year" class="form-control" maxlength="4"
            value="<?php echo (isset($data['publication_year'])) ? $data['publication_year'] : $publication_year; ?>"
            oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*?)\..*/g, '$1');" required/>

          <!-- Pages -->
          <label class="mt-1">Page Count</label>
          <input type="text" name="page_count" class="form-control" maxlength="5"
            value="<?php echo (isset($data['page_count'])) ? $data['page_count'] : ''; ?>"
            oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*?)\..*/g, '$1');" required/>

          <!-- Book Cover link -->
          <label class="mt-1">Cover Image Address</label>
          <input type="url" name="cover" class="form-control" placeholder="cover-image.com/image.jpg"
            value="<?php echo (isset($data) && isset($data['cover']) && $data['req_id'] == $req_id) ? $data['cover'] : ''; ?>" required/>
          <?php echo (isset($_SESSION['add_book_errors']) && in_array('url_inv', $_SESSION['add_book_errors']) && $data['req_id'] == $req_id) ? "<p class='error-text'>Url is not a PNG or JPG content</p>": ""; ?>

          <!-- Description -->
          <label class="mt-1">Description</label>
          <textarea name="description" class="form-control"  rows="3"><?php echo (isset($data) && isset($data['description']) && $data['req_id'] == $req_id) ? $data['description'] : ''; ?></textarea>

          <!-- Request ID -->
          <input type="hidden" name="req_id" value="<?php echo $req_id; ?>">
          <?php echo (isset($_SESSION['add_book_errors']) && in_array('book_tkn', $_SESSION['add_book_errors']) && $data['req_id'] == $req_id) ? "<p class='error-text text-center'>This Book already exists</p>": ""; ?>

          <!-- Submit -->
          <div class="d-flex justify-content-center mt-3">
            <button type="button" class="btn btn-secondary me-2 w-100" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="submit" class="btn btn-primary w-100">Add</button>
          </div>

        </form>


      </div>
    </div>
  </div>
</div>
