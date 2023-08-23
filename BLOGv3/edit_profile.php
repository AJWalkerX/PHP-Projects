<?php
session_start();
require_once __DIR__ . '/partials/PDO.php';
include __DIR__ . '/partials/header.php';
include __DIR__ . '/Config/util.php';

if (!isset($_SESSION['user_id'], $_SESSION['username'])) {
  die("ACCESS DENIED");
}
// User info Variables
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Looking for user_id
$stmt = $db->prepare("SELECT * FROM Profile WHERE user_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if(mysqli_num_rows($result) > 0){
  $row = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Variables
  $user_id = $_SESSION['user_id'];
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $birthDate = $_POST['date'];
  $about = $_POST['about'];
  $profile_pic = $_FILES['picture'];
  $aboutLength =  mb_strlen($about, 'UTF-8');

  // Validations
  // Validation First name
  $fname_error = ValidateName($fname);
  if (is_string($fname_error)) {
    $_SESSION['fname_error'] = $fname_error;
    unset($_COOKIE['fname']);
  } else {
    $_COOKIE['fname'] = $fname;
  }
  // Validation Last name
  $lname_error = ValidateName($lname);
  if (is_string($lname_error)) {
    $_SESSION['lname_error'] = $lname_error;
    unset($_COOKIE['lname']);
  } else {
    $_COOKIE['lname'] = $lname;
  }
  // Validation Birth Date
  $date_error = ValidateBirthDate($birthDate);
  if(is_string($date_error)){
    $_SESSION['date_error'] = $date_error;
  }
  // Validation About
  if($aboutLength > 250){
    unset($about,$aboutLength);
  }
  // Validation Profile Picture
  if(isset($profile_pic)){
    $profile_pic_error = ValidatePicture($profile_pic);
    if(is_string($profile_pic_error)){
      $_SESSION['pic_error'] = $profile_pic_error;
    }
  }
  // Installing profile info into DB.
  if(!is_string($fname_error) && !is_string($lname_error) && !is_string($date_error)
    && $aboutLength < 250){
      $stmt_profile = $db->stmt_init();
      $stmt_profile->prepare("UPDATE Profile SET first_name = ?, last_name = ?, birth_date = ?, about = ?
        WHERE user_id = ?");
      $stmt_profile->bind_param("sssss", $fname, $lname, $birthDate, $about,$user_id);
      $stmt_profile->execute();
      // Installing profile Picture into DB.
      if(!is_string($profile_pic_error)){
        if(!is_dir('Users/img')){
          mkdir('Users/img');
        }
        if($profile_pic['size'] > 0){
          // Get the file extension from the filename
          $filename = $profile_pic['name'];
          // Search for the dot in the filename
          $dotPosition = strpos($filename, '.');
          // Take the substring from the dot position till the end of the string
          $extension = substr($filename, $dotPosition + 1);
          
          $new_img_name = $user_id.'.'.$extension; 
      
          move_uploaded_file($profile_pic['tmp_name'],"Users/img/".$new_img_name);
  
          $stmt_pic = $db->stmt_init();
          $stmt_pic->prepare("UPDATE Profile_photos SET pic_url = ? WHERE user_id = ?");
          $stmt_pic->bind_param("ss", $new_img_name, $user_id);
          $stmt_pic->execute();
        }        
      }
      $_SESSION['success'] = "Saved!";
      header('Location: add_profile.php');
      return;
    }
  }
  $stmt->close();
  ?>
<!-- DOC -->
<!-- Alert Massage -->
<?php if(isset($_SESSION['success'])):?>
  <div class="alert alert-success">
    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
  </div>
<?php endif; ?>
<div class="container">
  <div class="card border border-top-0">
    <div class="card-header">
      <div class="row">
        <div class="col-10">
          <h1>Editing user</h1>
        </div>
        <div class="col-2 text-end">
            <a href="profile.php?user=<?=$username?>" class="btn btn-danger">Return</a>
        </div>
      </div>
    </div>
    <div class="card-body">
      <form class="from" method="post" enctype="multipart/form-data">
        <!-- Username -->
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control" id="username" name="username" value="<?php echo $username ?>"
            disabled>
        </div>
        <!-- First Name -->
        <!-- If user enters invalid first name input -->
        <?php if (isset($_SESSION['fname_error'])): ?>
          <div class="mb-3">
            <label for="fname" class="form-label">First Name</label>
            <input type="text" class="form-control is-invalid" id="fname" name="fname" value="<?=$row['first_name']?>">
            <div class="invalid-feedback">
              <?php echo $_SESSION['fname_error'];
              unset($_SESSION['fname_error']); ?>
            </div>
          </div>
          <!-- If user enters valid fist name input -->
        <?php elseif (isset($_COOKIE['fname']) && $_COOKIE['fname'] !== ''): ?>
          <div class="mb-3">
            <label for="fname" class="form-label">First Name</label>
            <input type="text" class="form-control is-valid" id="fname" name="fname"
              value="<?php echo $_COOKIE['fname'] ?>">
          </div>
          <!-- default first name input -->
        <?php else: ?>
          <div class="mb-3">
            <label for="fname" class="form-label">First Name</label>
            <input type="text" class="form-control" id="fname" name="fname" value="<?=$row['first_name']?>">
          </div>
        <?php endif; ?>
        <!-- Last Name -->
        <!-- If user enters invalid Last name input -->
        <?php if (isset($_SESSION['lname_error'])): ?>
          <div class="mb-3">
            <label for="lname" class="form-label">Last Name</label>
            <input type="text" id="lname" name="lname" class="form-control is-invalid" value="<?=$row['last_name']?>">
            <div class="invalid-feedback">
              <?php echo $_SESSION['lname_error']; unset($_SESSION['lname_error']);
              ?>
            </div>
          </div>
          <!--  If user enters valid last name input -->
          <?php elseif(isset($_COOKIE['lname']) && $_COOKIE['lname'] !== ''): ?>
          <div class="mb-3">
            <label for="lname" class="form-label">Last Name</label>
            <input type="text" id="lname" name="lname" class="form-control is-valid"
              value="<?php echo $_COOKIE['lname'] ?>">
          </div>
          <!-- default last name input -->
        <?php else: ?>
          <div class="mb-3">
            <label label for="lname" class="form-label">Last Name</label>
            <input type="text" id="lname" name="lname" class="form-control" value="<?=$row['last_name']?>">
          </div>
        <?php endif; ?>
        <!-- Birth date -->
        <!-- If user select future date (invalid) -->
        <?php if(isset($_SESSION['date_error'])): ?>
          <div class="mb3" style="margin-top: 25px">
            <label for="date" class="form-label">Birth Date</label>
            <input type="date" class="form-control is-invalid" name="date" id="date"
              value="<?=$row['birth_date'] ?>">
            <div class="invalid-feedback">
              <?php echo $_SESSION['date_error']; unset($_SESSION['date_error']); ?>
            </div>
          </div>
        <!-- Default Birth date input -->
        <?php else: ?>
          <div class="mb3" style="margin-top: 25px">
            <label for="date" class="form-label">Birth Date</label>
            <input type="date" class="form-control" name="date" id="date" value="<?=$row['birth_date'] ?>">
          </div>
        <?php endif; ?>
        <!-- About -->
        <!-- Default about input -->
        <div class="mb-3">
          <div class="input-group" style="margin-top: 25px">
            <span class="input-group-text">About</span>
            <textarea class="form-control" aria-label="With textarea" id="about" name="about"
            style="outline: none"><?=$row['about'] ?></textarea>
          </div>
          <div class="mb-3 text-end">
            <p style="padding: 5px;" id="result"></p>
          </div>
        </div>
        <!-- Profile picture -->
        <!-- If user enters invalid profile picture -->
        <?php if(isset($_SESSION['pic_error'])):?>
          <div class="mb-3" style="margin-top: 25px">
            <label for="pic" class="form-label">Select Profile picture</label>
            <input class="form-control is-invalid" type="file" id="pic" name="picture">
            <div class="invalid-feedback">
              <?php echo $_SESSION['pic_error']; unset($_SESSION['pic_error'])?>
            </div>
          </div>
        <!-- Default profile picture input -->
        <?php else: ?>
        <div class="mb-3" style="margin-top: 25px">
          <label for="pic" class="form-label">Select Profile picture</label>
          <input class="form-control" type="file" id="pic" name="picture">
        </div>
        <?php endif; ?>
        <!-- Buttons -->
        <div class="mb-3" style="margin-top: 25px">
          <input type="submit" class="btn btn-success" value="Save" id="submit">
          <a href="profile.php?user=<?php echo $_SESSION['username'] ?>" class="btn btn-danger">Cancel</a>
        </div>
      </form>
    </div>
  </div>

</div>
<!-- endOfDoc -->
<!-- Javascript,jQuery codes-->
<script>
//text counter 
var text = document.getElementById("about");
var result = document.getElementById("result");
var limit = 250;
result.textContent = 0 + "/" + limit;

window.onload = function() {
  var savedResult = localStorage.getItem("result");
  var savedText = localStorage.getItem("text");
  if (savedResult !== null) {
    result.textContent = savedResult;
  }
};

text.addEventListener("input", function(){
  var textLength = text.value.length;
  result.textContent = textLength + "/" + limit;

  if (textLength > limit){
    text.style.borderColor = "#ff2851";
    result.style.color = "#ff2851";
    $('#submit').click(function(){
      alert('Too much word!');
      return;
    })
  } else {
    text.style.borderColor = "black";
    result.style.color = "black";
  }
  localStorage.setItem("result", result.textContent);
});

</script>
<?php include __DIR__ . '/partials/footer.php' ?>