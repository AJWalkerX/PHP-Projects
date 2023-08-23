<?php
session_start();
if(!isset($_SESSION['user_id'], $_SESSION['username'])){
  die("ACCESS DENIED");
}
else{
  unset($_SESSION['user_id'], $_SESSION['username']);
  session_destroy();
  header('Location: index.php');
  return;
}
?>