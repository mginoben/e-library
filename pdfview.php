
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Style CSS -->
    <link rel="stylesheet" href="style.css?<?php echo time(); ?>">
    <meta charset="utf-8">
    <title><?php echo $_POST['title']; ?></title>
  </head>
  <body>
    <iframe src="books/book_dummy.pdf#toolbar=0" class="pdf-container" frameborder="0"></iframe>
  </body>
</html>
