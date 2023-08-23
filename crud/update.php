<?php
include __DIR__.'/partials/header.php';
require __DIR__.'/Users/users.php';

if (!isset($_REQUEST['id'])) {
  include "partials/not_found.php";
  exit;
}

$userId = $_REQUEST['id'];
$user = getuserById($userId);
if (!$user) {
  include "partials/not_found.php";
  exit;
}

$errors = [
  'name' => "",
  'username' => "",
  'email' => "",
  'phone' => "",
  'website' => "",
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user = array_merge($user, $_POST);
  $isValid = ValidateUser($user, $errors);

  if ($isValid) {
    $user = updateUser($_POST, $userId);
    uploadImage($_FILES['picture'], $user);
    header('Location: index.php');
    return;
  }
}
?>
<!-- DOC -->
<?php include __DIR__.'/partials/_form.php' ?>
