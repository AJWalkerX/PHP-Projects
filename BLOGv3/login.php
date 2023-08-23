<?php
session_start();
require_once __DIR__.'/partials/PDO.php';
include __DIR__.'/partials/header.php';
include __DIR__.'/Config/util.php';
 
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  // Variables
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Validate Email
  $error = ValidateMail($email);
  if(is_string($error)){
    $_SESSION['email_error'] = $error;
  }
  if(!$password){
    $_SESSION['pass_error'] =  "Please enter the password";
    header('Location: login.php');
    return;
  }

  // Validation with db. If user enters all input correctly user will be signed in.
  $stmt = $db->prepare("SELECT * FROM Users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  // If user enters wrong email address.
  if($result->num_rows === 0){
    $_SESSION['email_error'] = "Wrong email address";
  }
  // If user enters valid email address this will be run.
  elseif($result->num_rows === 1){
    $_COOKIE['email'] = $email;
    $row = $result->fetch_assoc();
    // Checks the password is matches with email. 
    if(!password_verify($password, $row['password'])){
      $_SESSION['pass_error'] = "Wrong password! Make sure this email address is yours.";
      // $passCount ++;
      // If passCount hits 3 send email validation mail.
    }
    else{
      $_SESSION['username'] = $row['username'];
      $_SESSION['user_id'] = $row['user_id'];
      header('Location: index.php');
      return;
    }
  }

}
?>
<!-- DOC -->
<!-- Alert message -->
<?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success">
    <?php echo $_SESSION['success']; unset($_SESSION['success'])?>
  </div>
<?php endif; ?>
<div class="container">
  <div class="card">
    <div class="card-header">
      <h3>Log In</h3>
    </div>
    <div class="card-body">
      <form class="form" method="post">
        <!-- Email -->
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <!-- If user enters invalid input -->
          <?php if(isset($_SESSION['email_error'])):?>
            <input type="text" name="email" class="form-control is-invalid" id="email" placeholder="example@hotmail.com">
            <div class="invalid-feedback">
              <?php echo $_SESSION['email_error']; unset($_SESSION['email_error'], $_POST['email']) ?>
            </div>
          <!-- If user enters valid email address -->
          <?php elseif(isset($_COOKIE['email'])): ?>
            <input type="text" name="email" class="form-control is-valid" id="email" value="<?php echo $_COOKIE['email']?>" required>
          <?php else: ?>
            <!-- Default input -->
            <input type="text" name="email" class="form-control" id="email" placeholder="example@hotmail.com">
          <?php endif; ?>
        </div>
        <!-- password -->
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <!-- Invalid input -->
          <?php if(isset($_SESSION['pass_error'])): ?>
            <input type="password" name="password" class="form-control is-invalid" id="password">
            <div class="invalid-feedback">
              <?php echo $_SESSION['pass_error'];  unset($_SESSION['pass_error'], $_POST['password'])?>
            </div>
          <?php else: ?>
            <!-- Default input -->
            <input type="password" name="password" class="form-control" id="password">
          <?php endif; ?>
        </div>
        <!-- Buttons -->
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="index.php" class="btn btn-danger">Cancel</a><br>
        <a href="signup.php">Sing up</a>
      </form>
    </div>
  </div>
</div>
<!-- endOfDoc -->
<?php include  __DIR__.'/partials/footer.php'; ?>
