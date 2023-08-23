<?php
// All user Validations
function ValidateMail($email)
{
  if (!$email) {
    return "Email must contain!";
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "This must be valid email address!";
  }
  return true;
}
function ValidateMailSQL($email, $db)
{
  $error = ValidateMail($email);
  if (is_string($error)) {
    return $error;
  } else {
    $stmt = $db->stmt_init();
    $stmt = $db->prepare("SELECT email FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      return "This email already taken!";
    }
    $stmt->close();
    return true;
  }
}
function ValidateMail_verify($email_verify, $email)
{
  if (!$email_verify) {
    return "Email Verify must contain!";

  }
  if (!filter_var($email_verify, FILTER_VALIDATE_EMAIL)) {
    return "This must be valid email!";

  }
  if ($email !== $email_verify) {
    return "Please enter the same valid email address!";
  }
  return true;
}
function ValidateUsername($username, $db)
{
  if (!$username) {
    return "Please enter a Username";
  }
  if (strlen($username) <= 6 || strlen($username) >= 20) {
    return "Username must be 6-20 characters long!";
  }
  preg_match('/[!@+%&=?*-_()]+/', $username, $matches);
  if (count($matches) !== 0) {
    return "Not allowed to use special characters example: !@?*";
  }
  $stmt = $db->stmt_init();
  $stmt = $db->prepare("SELECT username FROM Users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    return "This username already taken!";
  }
  $stmt->close();
  return true;
}
function ValidatePass($password)
{
  if (!$password) {
    return "Please enter the password";
  }
  if (strlen($password) < 8) {
    return "Please choose a password longer than 8 character!";
  }
  preg_match('/[A-Z]+/', $password, $matches);
  if (count($matches) === 0) {
    return "password must have at least one Capital letter!";
  }
  preg_match('/[a-z]+/', $password, $matches);
  if (count($matches) === 0) {
    return "password must have at least one small letter!";
  }
  preg_match('/[0-9]+/', $password, $matches);
  if (count($matches) === 0) {
    return "password must have at least one number";
  }
  preg_match('/[!^@+%&=?*-_()]+/', $password, $matches);
  if (count($matches) === 0) {
    return "password must have at least one special character like !@^+%&=?*-_()";
  }
  return true;
}
function ValidatePass_verify($password_verify, $password)
{
  if (!$password_verify || ($password !== $password_verify)) {
    return "The password provided does not match!";
  }
  return true;
}
function ValidateName($name)
{
  if (is_numeric($name)) {
    return "First name must contain only letters";
  }
  preg_match('/[!^@+%&=?*-_()]+/', $name, $matches);
  if (count($matches) > 0) {
    return "First name must contain only letters";
  }
  return true;
} 
function ValidateBirthDate($date)
{

  if (new DateTime($date) > new DateTime()) {
    return "You cannot enter a future date!";
  }
  return true;
}
function ValidatePicture($picture)
{
  if ($picture['size'] === 0) {
    return true;
  } else {
    $img_name = $picture['name'];
    $img_size = $picture['size'];
    $error = $picture['error'];
    $maxSize = 500 * 1024 * 1024;
    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
    $img_ex_lc = strtolower($img_ex);

    $allowed_exs = array("jpg", "jpeg", "png");
    if ($error > 0) {
      print($error);
      return "Unknown Error!";
    }
    if ($img_size > $maxSize) {
      return "File is too large. Max allowed size is 500MB.";
    }
    if (!in_array($img_ex_lc, $allowed_exs)) {
      return "This file has to be an picture. Try .png or .jpg or .jpeg";
    }
  }
  return true;
}
// Validation of Article
// Validate Article Title
function ValidateTitle($title){
  if(!$title){
    return "Title must contain!";
  }
  if(!is_string($title)){
    return "Invalid Title!";
  }
  return true;
}
// Validate content
function ValidateContent($content){
  if(!$content){
    return "Content must contain!";
  }
  if(!is_string($content)){
    return "Invalid Content!";
  }
  return true;
}
function ValidateSubHeading($subheading,$content_2){
  if(!is_string($subheading)){
    return "Invalid Title!";
  }
  if($content_2 !== "" && !$subheading){
    return "Please enter the subheading!";
  }
  return true;
}
function ValidateSubContent($subheading,$content_2){
  if(!is_string($content_2)){
    return "Invalid content!";
  }
  if($subheading !== "" && !$content_2){
    return "Please enter the content!";
  }
  return true;
}
function ValidateCategory($category){
  if($category === ""){
    return "Please select a category";
  }
  return true;
}
// End of User Validation

// DB Selection 
// Selecting user profile table from db.
function SelectProfile($db, $user_id)
{
  $stmt_profile = $db->stmt_init();
  $stmt_profile->prepare("SELECT * FROM Profile WHERE user_id = ?");
  $stmt_profile->bind_param("s", $user_id);
  $stmt_profile->execute();
  $result_profile = $stmt_profile->get_result();
  if (mysqli_num_rows($result_profile) > 0) {
    return $result_profile->fetch_assoc();
  } else {
    return null;
  }
}
// Selecting user profile picture
function SelectProPicture($db, $user_id)
{
  $stmt_pic = $db->stmt_init();
  $stmt_pic->prepare("SELECT pic_url FROM Profile_photos WHERE user_id = ?");
  $stmt_pic->bind_param("s", $user_id);
  $stmt_pic->execute();
  $result_pic = $stmt_pic->get_result();
  if (mysqli_num_rows($result_pic) > 0) {
    return $result_pic->fetch_assoc();
  } else {
    return null;
  }
}
function SelectArticle($db){
  $stmt = $db ->stmt_init();
  $stmt->prepare("SELECT* FROM Articles ORDER BY created_at AND content LIMIT 30");
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_all(MYSQLI_ASSOC);
  return $data;
}
function SelectArtPicture($db,$article_id){
  $stmt = $db->stmt_init();
  $stmt->prepare("SELECT art_pic_url FROM Article_pictures WHERE article_id = ?");
  $stmt->bind_param("i", $article_id);
  $stmt-> execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  if($row !==0){
    return $row['art_pic_url'];
  }
}
// End of DB Selection 
