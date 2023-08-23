<?php
session_start();
require_once __DIR__ . '/partials/PDO.php';
include __DIR__ . '/partials/header.php';
include __DIR__ . '/Config/util.php';

if (!isset($_SESSION['user_id']) && !isset($_SESSION['username'])) {
  die("ACCESS DENIED");
}
// User info
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
// Selecting user profile table from db.
$row_profile = SelectProfile($db, $user_id);

// Selecting user profile picture
$row_pic = SelectProPicture($db, $user_id);
?>
<!-- DoC -->
<div class="container">
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-10">
          <h1>User
            <?= $username ?>
          </h1>
        </div>
        <div class="col-2 text-end">
          <a href="index.php" class="btn btn-danger">Return</a>
        </div>
      </div>
    </div>
    <div class="card-body">
      <!-- image and about -->
      <hr>
      <div class="row">
        <!-- Image -->
        <div class="col-3">
          <img width="150px" alt=""
            src="Users/img/<?php echo $result = ($row_pic !== null) ? $row_pic['pic_url'] : 'default.png'; ?>">
        </div>
        <!-- about -->
        <div class="col-9">
          <h4>About</h4>
          <p>
            <?php if ($row_profile !== null) {
              echo $row_profile['about'];
            } ?>
          </p>
        </div>
      </div>
      <hr>
      <!-- Personnel Information -->
      <!-- First Name -->
      <div class="row">
        <div class="col-2"><b>First Name:</b></div>
        <div class="col-10">
          <?php if ($row_profile !== null) {
            echo $row_profile['first_name'];
          } ?>
        </div>
      </div>
      <!-- Last Name -->
      <div class="row">
        <div class="col-2"><b>Last Name:</b></div>
        <div class="col-10">
          <?php if ($row_profile !== null) {
            echo $row_profile['last_name'];
          } ?>
        </div>
      </div>
      <!-- Gender -->
      <div class="row">
        <div class="col-2"><b>Gender:</b></div>
        <div class="col-10">
          <?php if ($row_profile !== null) {
            echo $row_profile['gender'];
          } ?>
        </div>
      </div>
      <!-- Birth Date -->
      <div class="row">
        <div class="col-2"><b>Birth Date:</b></div>
        <div class="col-10">
          <?php if ($row_profile !== null) {
            $timestamp = strtotime($row_profile['birth_date']);
            echo date("d-m-Y", $timestamp);
          } ?>
        </div>
      </div>
      <hr>
      <!-- Traffic information -->
      <div class="row">
        <!-- Likes -->
        <div class="col">
          <b>Likes:</b>
        </div>
        <!-- Total Articles -->
        <div class="col">
          <b>Articles:</b>
        </div>
        <!-- Comments-->
        <div class="col">
          <b>Comments:</b>
        </div>
      </div>
      <hr>
    </div>
  </div>
</div>
<!-- EndDoc -->
<?php include __DIR__ . '/partials/footer.php' ?>