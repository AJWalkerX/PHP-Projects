<?php
include __DIR__.'/partials/header.php';
require __DIR__.'/Users/users.php';

if (!isset($_REQUEST['id'])) {
  include "partials/not_found.php";
  exit;
}

$userId = $_REQUEST['id'];
deleteUser($userId);
header('Location: index.php');

?>
