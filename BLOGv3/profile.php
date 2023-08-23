<?php
require_once __DIR__ . '/partials/PDO.php';
include __DIR__ . '/partials/header.php';
include __DIR__ . '/Config/util.php';
include __DIR__ . '/partials/navbar.php';
// Getting user info
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Selecting user users table from db.
$stmt_user = $db->stmt_init();
$stmt_user->prepare("SELECT created_at FROM Users WHERE user_id = ?");
$stmt_user->bind_param("s", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
if(mysqli_num_rows($result_user) > 0){
  $row_user = $result_user->fetch_assoc();
}else{
  $row_user = null;
}
// Selecting user profile table from db.
$row_profile = SelectProfile($db,$user_id);

// Selecting user profile picture
$row_pic = SelectProPicture($db,$user_id);
?>
<!-- doc -->
<!-- This is main container for all DOC -->
<div class="container-md">
  <!-- Profile info and profile -->
  <div class="row border border-3 border-secondary-subtle position-relative border-top-0" style = "padding-bottom: 10px">
    <!-- Profile pic, bio, edit, more -->
    <div class="row">
      <!-- Profile picture -->
      <div class="col-4">
        <img class="img-thumbnail" alt="" style="width: 200px; display:inline"
        src="Users/img/<?php echo $result = ($row_pic !== null) ? $row_pic['pic_url'] : 'default.png' ; ?>">
      </div>
      <!-- Bio -->
      <div class="col-6" style="margin-top: 2%; lg">
      <h4>About</h4>
        <?php if($row_profile !== null){
          echo $row_profile['about'];
        }  ?>
      </div>
      <!-- Edit and more-->
      <div class="col position-relative">
        <!-- edit and more -->
        <div class="position-absolute top-0 end-0">
          <!-- Edit -->
          <?php if ($_REQUEST['user'] === $_SESSION['username']): ?>
            <a href="add_profile.php" class="btn btn-link">
            <i class="bi bi-pencil-square"></i> Edit<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
              fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
              <path
                d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
            </svg>
            </a>
          <?php endif; ?>
          <!-- More -->
          <div class="position-absolute top-100 end-0">
            <a href="view_profile.php?user=<?php echo $_SESSION['username'] ?>">More...</a>
          </div>
        </div>
      </div>
    </div>
    <!-- Username, Autor or user, Created-at -->
    <div class="row">
      <div class="col"><b><?php echo $username ?></b></div>
      <!-- Autor or user -->
      <div class="row">
        <div class="col-flex">
          <small class="text-body-secondary">user</small>
        </div>
      </div>
    </div>
    <!-- Created-at -->
    <hr>
    <div class="col position-relative bottom-0 start-50 end-0">
    <small class="text-body-secondary">
    <?php if($row_user !== null){
          $timestamp = strtotime($row_user['created_at']);
          echo date("d-m-Y", $timestamp) ;
        }  ?>
      </small>
      </div>
    </div>
    <!-- Yazarsa paylaştığı yazılar -->
    <div class="card border border-bottom-0" style="margin-top: 10px">
      <div class="card-header">
        <h1>(yazarsa): Paylaştığı yazılar</h1>
      </div>
      <div class="card" style="margin-top: 10px">
        <!-- card body komple link olacak -->
        <div class="card-body">
          <div class="title">
            <h5>başlık</h5>
          </div>
          <!-- eğer resim varsa -->
          <img src="..." class="card-img-top" alt="...">
          <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat velit at facere ullam
            voluptates hic in necessitatibus commodi nemo ex.</p>
        </div>
        <div class="card-footer text-center">
          <small class="text-body-secondary">Last updated 3 mins ago</small>
        </div>
      </div>
    </div>
    <!-- Beğendiği yazılar -->
    <div class="card border border-bottom-0" style="margin-top: 10px">
      <div class="card-header">
        <h1>Beğenilen yazılar</h1>
      </div>
      <div class="card" style="margin-top: 10px">
        <!-- card body komple link olacak -->
        <div class="card-body">
          <div class="title">
            <h5>başlık</h5>
          </div>
          <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat velit at facere ullam
            voluptates hic in necessitatibus commodi nemo ex.</p>
        </div>
        <div class="card-footer text-center">
          <small class="text-body-secondary">Last updated 3 mins ago</small>
        </div>
      </div>
    </div>
    <!-- Yorumlar -->
    <div class="card border border-bottom-0" style="margin-top: 10px">
      <div class="card-header">
        <h1>Yorumlar</h1>
      </div>
      <div class="card" style="margin-top: 10px">

        <!-- card body komple link olacak -->
        <div class="card-body">
          <div class="title">
            <h5>başlık</h5>
          </div>
          <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat velit at facere ullam
            voluptates hic in necessitatibus commodi nemo ex.</p>
        </div>
        <hr>
        <div class="row" style="margin-top: 4px">
          <div class="col-1" style="margin-left: 20px">
            <img src="Users/Pictures/default.png" alt="..." style="width: 50px">
          </div>
          <div class="col">
            <b>Username: </b>
            <p style="display: inline">comment</p>
          </div>
        </div>
        <div class="card-footer text-center" style="margin-top : 20px">
          <small class="text-body-secondary">Last updated 3 mins ago</small>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- endOfDoc -->
<?php 
  $stmt_user->stmt_close();
  $stmt_profile->stmt_close();

  include __DIR__ . '/partials/footer.php' ?>