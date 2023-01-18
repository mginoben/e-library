<?php
// Show button layout type
if ($type == 'display') {
  include 'book-display-container.php';
}
elseif ($type == 'borrowed') {
  include 'book-borrowed-container.php';
}
?>

<!-- BOOK POPUP/MODAL -->
<div class="modal fade" id="bookInfo<?php echo $book_id; ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        <div class="row d-flex justify-content-center">
          <div class="col-md-5 d-flex justify-content-center modal-img-container">
            <img src="<?php echo $cover; ?>" alt="<?php echo $title; ?>" class="modal-img">
          </div>
          <div class="col-md-7 modal-info">
            <div class="d-flex flex-column">
              <span class="modal-title"><?php echo $title; ?></span>
              <span class="modal-text mb-3"><i><?php echo $subtitle; ?></i></span>
            </div>

            <span class="modal-title-secondary mb-3"><?php echo $genre; ?></span>

            <div class="d-flex flex-column">
              <span class="modal-text"><i class="fa-solid fa-user"></i> <?php echo $author; ?></span>
              <span class="modal-text"><i class="fa-solid fa-calendar"></i> <?php echo $year; ?></span>
              <span class="modal-text"><i class="fa-solid fa-file-lines"></i> <?php echo $page_count; ?></span>
            </div>

          </div>
        </div>
        <hr>
        <div class="row">
          <span class="modal-title-secondary">Description</span>
          <p class="modal-text m-0">
            <?php echo $description; ?>
          </p>
        </div>
        <hr>
        <div class="row d-flex justify-content-start">
          <div class="col-6">
            <span class="modal-text text-muted"><?php echo $borrowed_message; ?></span>
          </div>
          <div class="col-6 d-flex justify-content-end">
            <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Close</button>
            <form action="includes/borrow-inc.php" method="post" class="w-100 <?php echo isset($_SESSION['umak_id']) ? '': 'd-none'; ?>">
              <input type="hidden" name="url" value="<?php echo $url; ?>">
              <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
              <input type="hidden" name="umak_id" value="<?php echo isset($_SESSION['umak_id']) ? $_SESSION['umak_id'] : ""; ?>">
              <?php // Borrow button display dynamically
                $borrow_btn_toggle = (isset($_SESSION['umak_id']) && !bookAlreadyBorrowed($conn, $book_id)) ? 'd-block' : 'd-none';
                $borrowed_btn_toggle = (isset($_SESSION['umak_id']) && bookAlreadyBorrowed($conn, $book_id)) ? 'd-block' : 'd-none';
              ?>
              <button type="button" class="btn btn-danger ms-1 disabled w-100 <?php echo $borrowed_btn_toggle; ?>">Borrowed</button>
              <button type="submit" name="borrow-btn" class="btn btn-primary ms-1 w-100 <?php echo $borrow_btn_toggle; ?>">Borrow</button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
