<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>image uploader</title>
</head>
<body>
  <?php if (isset($_GET['error'])):?>
    <p><?php echo $_GET['error']; ?></p>
  <?php endif; ?>
  <form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="img">
    <input type="submit" value="submit" name="submit">
  </form>
</body>
</html>