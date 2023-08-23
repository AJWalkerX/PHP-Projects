<?php
include __DIR__.'/partials/header.php';
require __DIR__.'/Users/users.php';

$user = [
  'id' => '',
  'name' => '',
  'username' => '',
  'email' => '',
  'phone' => '',
  'website' => ''
];
$isValid = true;
$errors = [
  'name' => "",
  'username' => "",
  'email' => "",
  'phone' => "",
  'website' => "",
];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user = array_merge($user, $_POST);

  $isValid =  ValidateUser($user, $errors);

  if($isValid){
    $user = createUser($_POST);

    uploadImage($_FILES['picture'], $user);

    header('Location: index.php');
    return;

  }
}
?>
<!-- DOC -->
<?php include __DIR__.'/partials/_form.php' ?>
