<div class="borrowed-book-container m-0 d-flex flex-row p-0 mb-2">
  <form action="pdfview.php" method="post" target="_blank" class="w-100">
    <input type="hidden" name="book_id" value="book_<?php echo $book_id; ?>">
    <input type="hidden" name="title" value="<?php echo $title; ?>">
    <button type="submit" class="btn d-flex justify-content-between align-items-center">

      <div class="d-flex">
        <div>
            <img src="<?php echo $cover; ?>" alt="<?php echo $title; ?>">
        </div>

        <div class="d-flex flex-column align-items-start justify-content-center ms-3">
            <span class="title mb-2"><?php echo $title; ?></span>
            <span class="subtitle">by <?php echo $author; ?></span>
            <span class="subtitle"><?php echo $year; ?></span>
        </div>
      </div>

    </button>

    <div class="function-buttons p-2">
      <a href="" data-bs-toggle="modal" data-bs-target="#bookInfo<?php echo $book_id; ?>">
        <i class="fa-solid fa-circle-info me-2"></i>
      </a>
      <a href="includes/return-inc.php?umak_id=<?php echo $_SESSION['umak_id']; ?>&book_id=<?php echo $book_id; ?>">
        <i class="fa-solid fa-xmark"></i>
      </a>
    </div>
    
  </form>


</div>
