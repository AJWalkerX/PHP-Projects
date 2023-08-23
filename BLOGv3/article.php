<?php
require_once __DIR__ . '/partials/PDO.php';
include __DIR__ . '/partials/header.php';
include __DIR__ . '/Config/util.php';
include __DIR__ . '/partials/navbar.php';
// Getting user info
$rows = SelectArticle($db);
if ($rows === 0) {
  $_SESSION['error'] = "No article found!";
}
?>
<!-- DoC -->
<div class="container">
  <div class="card">
    <div class="card-header">
      <!-- Header and buttons -->
      <div class="row">
        <!-- Header -->
        <div class="col-10">
          <h1>Articles</h1>
        </div>
        <!-- Buttons -->
        <div class="col-2 text-end">
          <a href="add_article.php" class="btn btn-success">Create</a>
        </div>
      </div>
    </div>
    <div class="card-body">
      <!-- Articles and Catagories -->
      <div class="row">
        <!-- Header of content -->
        <h3>Featured</h3>
        <hr>
        <!-- Articles -->
        <div class="col-8">
          <!-- Emtpy -->
          <div class="row">
            <div class="col"></div>
          </div>
          <!-- Article cards -->
          <?php foreach ($rows as $row): ?>
            <?php 
              $article_id = $row['article_id'];
              $pic = SelectArtPicture($db,$article_id);
            ?>
            <div class="row">
              <div class="col">
                <div class="card">
                  <div class="row g-0">
                    <div class="col-md-4">
                      <img src="Users/Article_img/<?php echo $pic ?>" class="img-fluid rounded-start" width="150px" alt="No pic">
                    </div>
                    <div class="col-md-8">
                      <div class="card-body">
                        <h5 class="card-title"><?= $row['title'] ?></h5>
                        <p class="card-text"><?= $row['content'] ?></p>
                        <p class="card-text"><small class="text-body-secondary"><?=$row['created_at']?></small></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <!-- Catagories -->
        <div class="col-4">
          <div class="card" style="width: 18rem;">
            <div class="card-header">
              Catagories
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">1</li>
              <li class="list-group-item">2</li>
              <li class="list-group-item">3</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End of DoC -->
<?php include __DIR__ . '/partials/footer.php' ?>