<?php
session_start();
unset($_SESSION['id']);
unset($_SESSION['user']);
header('Location: index.php');
?>