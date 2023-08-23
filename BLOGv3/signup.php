<?php
session_start();
require_once __DIR__ . '/partials/PDO.php';
include __DIR__ . '/partials/header.php';
include __DIR__ . '/Config/util.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  //Variables
  $email = $_POST['email'];
  $email_verify = $_POST['email_verify'];
  $username = $_POST['username'];
  $password = $_POST['pass'];
  $password_verify = $_POST['pass_verify'];

  // Email Validation
  $error_email = ValidateMailSQL($email,$db);
  if (is_string($error_email)) {
    $_SESSION['email_error'] = $error_email;
  } elseif ($error_email === true) {
    $_COOKIE['email'] = $email;
  }
  // Email Verify Validation
  $error_email_verify = ValidateMail_verify($email_verify, $email);
  if (is_string($error_email_verify)) {
    $_SESSION['email_verify_error'] = $error_email_verify;
  }
  // Username Validation
  $error_username = ValidateUsername($username, $db);
  if (is_string($error_username)) {
    $_SESSION['username_error'] = $error_username;
  } elseif ($error_username === true) {
    $_COOKIE['username'] = $username;
  }
  // Password Validation
  $error_pass = ValidatePass($password);
  if (is_string($error_pass)) {
    $_SESSION['pass_error'] = $error_pass;
  }
  // Password Verify Validation
  $error_pass_verify = ValidatePass_verify($password_verify, $password);
  if (is_string($error_pass_verify)) {
    $_SESSION['pass_verify_error'] = $error_pass_verify;
  }
  // Installing the user info to SQL db.
  if (!is_string($error_email) && !is_string($error_email_verify) && 
    !is_string($error_pass) && !is_string($error_username) && !is_string($error_pass_verify)) {
    $email = $_COOKIE['email'];
    $username = $_COOKIE['username'];
    $passHash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->stmt_init();
    $stmt->prepare("INSERT INTO Users(username, email, password) VALUES (?,?,?)");
    $stmt->bind_param("sss", $username, $email, $passHash);
    $stmt->execute();

    $_SESSION['success'] = "Signed up Successfully";

    unset($_COOKIE['email'], $_COOKIE['username']);
    $stmt->close();

    header('Location: login.php');
    return;
  }
}
?>
<!-- DOC -->
<div class="container">
  <div class="card">
    <div class="card-header">
      <h3>Sign up</h3>
    </div>
    <div class="card-body">
      <form class="form" method="post">
        <!-- Email -->
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <!-- if user enters invalid email -->
          <?php if (isset($_SESSION['email_error'])) : ?>
            <input type="text" name="email" id="email" placeholder="example@hotmail.com" class="form-control is-invalid">
            <div class="invalid-feedback">
              <?php echo $_SESSION['email_error'];
              unset($_SESSION['email_error'], $_COOKIE['email']) ?>
            </div>
            <!-- If User enters Valid Input -->
          <?php elseif (isset($_COOKIE['email'])) : ?>
            <input type="text" name="email" id="email" class="form-control is-valid" 
            value="<?php echo $_COOKIE['email']; ?>" required>
            <!-- Default input-->
          <?php else : ?>
            <input type="text" name="email" id="email" placeholder="example@hotmail.com" class="form-control">
          <?php endif; ?>
        </div>
        <!-- Verify email -->
        <div class="mb-3">
          <label for="email_verify" class="form-label">Verify Email address</label>
          <!-- If user enters Invalid input -->
          <?php if (isset($_SESSION['email_verify_error'])) : ?>
            <input type="text" name="email_verify" id="email_verify" placeholder="example@hotmail.com" class="form-control is-invalid">
            <div class="invalid-feedback">
              <?php echo $_SESSION['email_verify_error'];
              unset($_SESSION['email_verify_error'], $_COOKIE['email_verify']) ?>
            </div>
            <!-- Default input-->
          <?php else : ?>
            <input type="text" name="email_verify" id="email_verify" placeholder="example@hotmail.com" class="form-control">
          <?php endif; ?>
        </div>
        <!-- Username -->
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <!-- If user enters invalid username input -->
          <?php if (isset($_SESSION['username_error'])) : ?>
            <input type="text" name="username" class="form-control is-invalid" id="username">
            <div class="invalid-feedback">
              <?php echo $_SESSION['username_error'];
              unset($_SESSION['username_error'], $_COOKIE['username']) ?>
            </div>
          <!-- If user enters Valid Input -->
          <?php elseif (isset($_COOKIE['username'])) : ?>
            <input type="text" name="username" id="username" class="form-control is-valid" 
            value="<?php echo $_COOKIE['username'] ?>" required>
          <!-- Default input -->
          <?php else : ?>
            <input type="text" name="username" class="form-control " id="username">
          <?php endif; ?>
        </div>
        <!-- password -->
        <div class="mb-3">
          <label for="pass" class="form-label">Password</label>
          <!-- If user enters invalid input -->
          <?php if (isset($_SESSION['pass_error'])) : ?>
            <input type="password" name="pass" class="form-control is-invalid" id="pass">
            <div class="invalid-feedback">
              <?php echo $_SESSION['pass_error'];
              unset($_SESSION['pass_error']) ?>
            </div>
            <!-- Default input -->
          <?php else : ?>
            <input type="password" name="pass" class="form-control" id="pass">
          <?php endif; ?>
        </div>
        <!-- password Verify -->
        <div class="mb-3">
          <label for="pass_verify" class="form-label">Password Verify</label>
          <!-- If user enters invalid input -->
          <?php if (isset($_SESSION['pass_verify_error'])) : ?>
            <input type="password" name="pass_verify" class="form-control is-invalid" id="pass_verify">
            <div class="invalid-feedback">
              <?php echo $_SESSION['pass_verify_error'];
              unset($_SESSION['pass_verify_error']) ?>
            </div>
            <!-- Default input -->
          <?php else : ?>
            <input type="password" name="pass_verify" class="form-control" id="pass_verify">
          <?php endif; ?>
        </div>
        <!-- Buttons -->
        <input type="submit" class="btn btn-primary" value="Submit">
        <a href="index.php" class="btn btn-danger">Cancel</a>
      </form>
    </div>
  </div>
</div>
<!-- endOfDoc -->
<?php include  __DIR__ . '/partials/footer.php'; ?>