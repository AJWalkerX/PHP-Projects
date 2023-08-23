<?php
session_start();
require_once 'Config\PDO.php';
include 'Config\util.php';

if(!isset($_SESSION['id']) && !isset($_SESSION['user'])){
    $_SESSION['error'] = "Please log in";
    header('Location: login.php');
    return;
}
if(empty($_REQUEST['user_id'])){
    $_SESSION['error'] = "Missing user_id";
    header('Location: index.php');
    return;
}

// Selecting username
$query = "SELECT username FROM Users WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->execute(array(':id' => $_REQUEST['user_id']));
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Selecting article entries
$articleQuery = "SELECT id, title, created_at
    FROM Articles  WHERE user_id = :user_id";
$articleStmt = $db->prepare($articleQuery);
$articleStmt->execute(array(':user_id' => $_REQUEST['user_id']));
$articles = $articleStmt->fetchAll(PDO::FETCH_ASSOC);

// Sellecting comments
$commentQuery = "SELECT Articles.title, Comments.content, Comments.created_at, Articles.id
    FROM Comments JOIN Articles ON Articles.user_id = Comments.user_id";
$commentStmt = $db->query($commentQuery);
$comments = $commentStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<html>
    <head>
        <title></title>
    </head>
    <body>
        <!-- Flash massage -->
        <?=flashMessage()?>
        <?=searchBar()?>
        <a href="index.php">Home page</a> <?php
            if($_SESSION['id'] === $_REQUEST['user_id']){
                echo(' /<a href="settings.php?user_id='.$_REQUEST['user_id'].'">Settings</a>');
            }
        ?>
         <h1><?=$user['username']?></h1>
        <a href="view_profile.php?user_id=<?=$_REQUEST['user_id']?>">details</a> <br>
        <h2>Articles</h2>
        <?php
        if(empty($articles)){
            echo('No article entered');
        }
        else{
            foreach($articles as $article){
                echo('
                    <div>
                    <a href="view.php?article_id='.$article['id'].'">
                    <b>'.htmlentities($article['title']).'</b>
                    <i>'.htmlentities($article['created_at']).'</i>
                    </a>
                    </div><br>
                ');
            }
        }
        ?>
    </body>
</html>