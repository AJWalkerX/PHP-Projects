<?php include "PDO.php"; ?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>view</title>
</head>
<body>
  <a href="index.php">&#8592;</a>
  <?php
    $sql = "SELECT * FROM img_test ORDER BY created_at";
    $res = mysqli_query($db, $sql);

    if(mysqli_num_rows($res) > 0){
      while($images = mysqli_fetch_assoc($res)){ ?>
        <div>
            <img src="img/<?=$images['img_link']?>" alt="">
        </div>
    <?php }
    }?>
</body>
</html>