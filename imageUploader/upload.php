<?php
include "PDO.php";
if (isset($_POST['submit']) && isset($_FILES['img'])) {
  $img_name = $_FILES['img']['name'];
  $img_size = $_FILES['img']['size'];
  $tmp_name = $_FILES['img']['tmp_name'];
  $error = $_FILES['img']['error'];

  if ($error === 0) {
    if ($img_size > 125000) {
      $em = "file is too large.";
      header('Location: index.php?error=' . $em);
    } else {
      $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
      $img_ex_lc = strtolower($img_ex);

      $allowed_exs = array("jpg", "jpeg", "png");

      if (in_array($img_ex_lc, $allowed_exs)) {
        $new_img_name = uniqid("IMG-",true). '.'.$img_ex_lc;
        $img_upload_path = 'img/'.$new_img_name;
        move_uploaded_file($tmp_name, $img_upload_path);

        $sql = "INSERT INTO img_test(img_link) VALUES ('$new_img_name')";
        mysqli_query($db, $sql);
        header('Location: view.php');

      } else {
        $em = "unknown error!";
        header('Location: index.php?error=' . $em);
      }
    }
  } else {
    $em = "unknown error!";
    header('Location: index.php?error=' . $em);
  }
} else {
  header('Location: index.php');
}
?>