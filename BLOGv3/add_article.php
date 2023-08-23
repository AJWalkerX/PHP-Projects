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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // variables
  $title = $_POST['title'];
  $content = $_POST['content'];
  $subheading = $_POST['subheading'];
  $content_2 = $_POST['content_2'];
  $category = $_POST['category'];
  $pic = $_FILES['pic'];

  // Validation 
  $title_error = ValidateTitle($title);
  if (is_string($title_error)) {
    $_SESSION['title_error'] = $title_error;
  } else {
    $_COOKIE['title'] = $title;
  }
  $content_error = ValidateContent($content);
  if (is_string($content_error)) {
    $_SESSION['content_error'] = $content_error;
  } else {
    $_COOKIE['content'] = $content;
  }
  $sub_error = ValidateSubHeading($subheading, $content_2);
  if (is_string($sub_error)) {
    $_SESSION['sub_error'] = $sub_error;
  } else {
    $_COOKIE['subheading'] = $subheading;
  }
  $subContent_error = ValidateSubContent($subheading, $content_2);
  if (is_string($subContent_error)) {
    $_SESSION['subContent_error'] = $subContent_error;
  } elseif(!is_string($subContent_error) && $content_2 !== "") {
    $_COOKIE['content_2'] = $content_2;
  }
  $cat_error = ValidateCategory($category);
  if(is_string($cat_error)){
    $_SESSION['cat_error'] = $cat_error;
  }

  if(isset($pic)){
    $pic_error = ValidatePicture($pic);
    if(is_string($pic_error)){
      $_SESSION['pic_error'] = $pic_error;
    }
  }
  if(!is_string($title_error) && !is_string($content_error) && !is_string($sub_error) && !is_string($subContent_error) && !is_string($cat_error)){
    $stmt = $db->stmt_init();
    $stmt->prepare("INSERT INTO Articles(user_id,title,content,subheading,content_2) VALUES(?,?,?,?,?)");
    $stmt->bind_param("issss", $user_id, $title, $content, $subheading, $content_2);
    $stmt->execute();
    $article_id = $db->insert_id;
    
    $stmt = $db->stmt_init();
    $stmt->prepare("INSERT INTO Catagories(Catagories, article_id) VALUES(?,?)");
    $stmt->bind_param("si", $category, $article_id);
    $stmt->execute();
    
    if(!is_string($pic_error)){
      if(!is_dir('Users/Article_img')){
        mkdir('Users/Article_img');
      }
      $filename = $pic['name'];
      $dotPosition = strpos($filename, '.');
      $extension = substr($filename, $dotPosition + 1);
      $pic_url = uniqid("IMG-",true). '.'.$extension;
      move_uploaded_file($pic['tmp_name'],"Users/Article_img/".$pic_url);      

      $stmt = $db->stmt_init();
      $stmt->prepare("INSERT INTO Article_pictures(art_pic_url,article_id) VALUES(?,?)");
      $stmt->bind_param("si", $pic_url, $article_id);
      $stmt->execute();
    }
    $_SESSION['success'] = "Article added!";
    header('Location: add_article.php');
    $stmt->close();
    return;
  }
}
?>
<!-- DoC -->
<!-- Alert massage -->
<?php if(isset($_SESSION['success'])): ?>
  <div class="alert alert-success">
    <?php echo $_SESSION['success']; unset($_SESSION['success'])?>
  </div>
<?php endif; ?>
<div class="container">
  <div class="card">
    <!-- Header and button -->
    <div class="card-header">
      <div class="row">
        <!-- Header -->
        <div class="col-10">
          <h1>Adding Article</h1>
        </div>
        <!-- Button -->
        <div class="col-2 text-end">
          <a class="btn btn-danger" href="article.php">Return</a>
        </div>
      </div>
    </div>
    <!-- Inputs -->
    <div class="card-body">
      <form method="post" enctype="multipart/form-data">
        <!-- Main title -->
        <div class="mb-3">
          <!-- If user enter invalid title -->
          <?php if (isset($_SESSION['title_error'])): ?>
            <label for="main_title" class="form-label">Main Title</label>
            <input type="text" id="main_title" name="title" class="form-control is-invalid">
            <div class="invalid-feedback">
              <?php echo $_SESSION['title_error'];
              unset($_SESSION['title_error'], $_COOKIE['title']); ?>
            </div>
          <!-- If user enters valid title input -->
          <?php elseif(isset($_COOKIE['title'])): ?>
            <label for="main_title" class="form-label">Main Title</label>
            <input type="text" id="main_title" name="title" class="form-control is-valid" value="<?=$_COOKIE['title']?>">
          <!-- Default Main Title -->
          <?php else: ?>
            <label for="main_title" class="form-label">Main Title</label>
            <input type="text" id="main_title" name="title" class="form-control">
          <?php endif; ?>
        </div>
        <!-- Content -->
        <div class="mb-3">
          <!-- If user enters invalid content input -->
          <?php if(isset($_SESSION['content_error'])): ?>
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control is-invalid" rows="10" aria-label="With textarea" id="content" name="content"></textarea>
            <div class="is-invalid">
              <?php echo $_SESSION['content_error']; unset($_SESSION['content_error'], $_COOKIE['content']); ?>
            </div>
          <!-- If user enters valid content input -->
          <?php elseif(isset($_COOKIE['content'])): ?>
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control is-valid" rows="10" aria-label="With textarea" id="content" name="content"><?=$_COOKIE['content']?></textarea>           
          <!-- Default content input -->
          <?php else: ?>
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" rows="10" aria-label="With textarea" id="content" name="content"></textarea>
          <?php endif; ?>
        </div>
        <!-- Subheading -->
        <div class="mb-3">
          <!-- If user enters invalid subheading input -->
          <?php if(isset($_SESSION['sub_error'])): ?>
            <label for="Subheading" class="form-label">Subheading</label>
            <input type="text" id="Subheading" name="subheading" class="form-control is-invalid">
            <div class="invalid-feedback">
              <?php echo $_SESSION['sub_error']; unset($_SESSION['sub_error'],$_COOKIE['subheading']); ?>
            </div>
          <!-- If user enters valid subheading input -->
          <?php elseif(isset($_COOKIE['subheading'])): ?>
            <label for="Subheading" class="form-label">Subheading</label>
            <input type="text" id="Subheading" name="subheading" class="form-control is-valid" value="<?=$_COOKIE['subheading']?>">
          <!-- Default Subheading input -->
          <?php else: ?>
            <label for="Subheading" class="form-label">Subheading</label>
            <input type="text" id="Subheading" name="subheading" class="form-control">
          <?php endif; ?>  
        </div>
        <!-- Content2 -->
        <div class="mb-3">
          <!-- If user enters invalid content input -->
          <?php if(isset($_SESSION['subContent_error'])): ?>
            <label for="content2" class="form-label">Content of subheading</label>
            <textarea class="form-control is-invalid" rows="10" aria-label="With textarea" id="content2" name="content_2"></textarea>
            <div class="invalid-feedback">
              <?php echo $_SESSION['subContent_error']; unset($_SESSION['subContent_error'], $_COOKIE['content_2']) ?>
            </div>
          <!-- If user enters valid content input -->
          <?php elseif(isset($_COOKIE['content_2'])): ?>
            <label for="content2" class="form-label">Content of subheading</label>
            <textarea class="form-control is-valid" rows="10" aria-label="With textarea" id="content2" name="content_2"
            ><?=$_COOKIE['content_2']?></textarea>
          <!-- default content input -->
          <?php else: ?>
            <label for="content2" class="form-label">Content of subheading</label>
            <textarea class="form-control" rows="10" aria-label="With textarea" id="content2" name="content_2"></textarea>
          <?php endif; ?>
        </div>
        <!-- Image -->
        <div class="mb-3">
          <label for="pic" class="form-label">Select Picture</label>
          <input class="form-control" type="file" id="pic" name="pic">
        </div>
        <!-- Catagories -->
        <div class="mb-3">
          <select name="category" class="form-select form-select-lg mb-3" aria-label="Large select example"
          <?= $result = (isset($_SESSION['cat_error'])) ? 'is-invalid' : '' ; ?>>
            <option selected value=""> Select a Category </option>
            <option value="1">One</option>
            <option value="2">Two</option>
            <option value="3">Three</option>
            <option value="other">Other</option>
          <?php if(isset($_SESSION['$cat_error'])): ?>
            <div class="invalid-feedback">
              <?php echo $_SESSION['cat_error']; unset($_SESSION['cat_error'] )?>
            </div>
          <?php endif; ?>
          </select>
        </div>
        <!-- Buttons -->
        <div class="mb-3" style="margin-top: 25px">
          <input type="submit" class="btn btn-success" value="Upload" id="submit">
          <a href="article.php" class="btn btn-danger">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End of Content -->
<?php include __DIR__ . '/partials/footer.php' ?>