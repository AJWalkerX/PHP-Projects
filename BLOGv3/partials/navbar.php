<?php
session_start();
?>

<nav class="navbar nav-underline navbar-expand-lg bg-dark text-light">
  <div class="container-fluid text-light d-flex">
    <a class="navbar-brand text-light" href="index.php"><b>Logo</b></a>
    <div class="text-light p-2 flex-fill" >
      <ul class="navbar-nav">
        <li class="nav-item">
          <a 
          class="nav-link text-light <?php 
          echo ($_SERVER['REQUEST_URI'] === '/Projects/BLOGv3/index.php') ? 'active' :  '' ?>" 
          href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a 
          class="nav-link text-light <?php 
          echo ($_SERVER['REQUEST_URI'] === '/Projects/BLOGv3/article.php') ? 'active' :  '' ?>" 
          href="article.php">Articles</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-light" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Catagories
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">1</a></li>
            <li><a class="dropdown-item" href="#">2</a></li>
            <li><a class="dropdown-item" href="#">3</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">other</a></li>
          </ul>
        </li>
        <li class="nav-item">
        <?php if (isset($_SESSION['user_id'], $_SESSION['username'])): ?>
          <a class="nav-link text-light <?php 
          echo ($_SERVER['REQUEST_URI'] === '/Projects/BLOGv3/profile.php') ? 'active' :  '' ?>" 
          href="profile.php?user=<?php echo $_SESSION['username'] ?>">Profile</a>
        <?php else: ?>
          <a class="nav-link text-light" href="login.php">Profile</a>
        <?php endif; ?>
        </li>
      </ul>
    </div>
    <div class="p-2 sm flex-sm-fill">
    <form class="d-flex" role="search">
      <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-light" type="submit">Search</button>
    </form>
  </div>
    <!-- navbar for user -->
    <?php if (isset($_SESSION['user_id'], $_SESSION['username'])): ?>
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active text-danger" href="logout.php">Logout</a>
        </li>
    <?php else: ?>
      <!-- navbar for guest -->
      <ul class="navbar-nav">
        <li class="nav-item" style="margin-right: 10px">
          <a class="nav-link active btn btn-success" href="login.php">Login</a>
        </li>
          <li class="nav-item">
            <a class="nav-link active btn btn-light" href="signup.php">Signup</a>
          </li>
        </ul>
      <?php endif; ?>
  </div>
  
</nav>