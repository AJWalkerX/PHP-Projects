<?php
session_start();
require_once 'Config\PDO.php';
include 'Config\util.php';

if(!isset($_REQUEST['article_id'])){
    $_SESSION['error'] = "Missing Article_id";
    header('Location: index.php');
    die();
}

$article_id = $_REQUEST['article_id'];

//Selecting the correct Article from DB.
$query = "SELECT * FROM Articles WHERE id = :article_id";
$stmt = $db->prepare($query);
$stmt->execute(array(':article_id' => $article_id));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
 
if($row ===false){
    $_SESSION['error'] = "Bad value for Article_id";
    header('Location: index.php');
    die();
}
$user_id = $row['user_id'];
// Sellecting other articles
$userQuery = "SELECT Articles.id, title, Users.username, Articles.created_at
    FROM Articles JOIN Users ON Users.id = Articles.user_id WHERE user_id = :user";
$userStmt = $db->prepare($userQuery);
$userStmt->execute(array(':user' => $user_id));
$otherArticles = $userStmt->fetchAll(PDO::FETCH_ASSOC); 

// Sellecting exact comment made for the current article from DB.
$comQuery = "SELECT Comments.content, article_id, Users.username, Comments.created_at, user_id
    FROM Comments JOIN Users ON Users.id = Comments.user_id
    WHERE article_id = :article_id ORDER BY created_at";
$comStmtShow = $db->prepare($comQuery);
$comStmtShow->execute(array(':article_id' => $article_id));
$comments = $comStmtShow->fetchAll(PDO::FETCH_ASSOC);

// Variables
$title = htmlentities($row["title"]);
$content = htmlentities($row["content"]);
// Inseting a comment to DB.
if(isset($_POST['comment'])){
    if(strlen($_POST['comment']) < 1){
        $_SESSION['warning'] = "Before submit must make a comment";
        header('Location: view.php?article_id='.$article_id);
        return;
    }
    else{
        $comInsert = "INSERT INTO Comments (content, article_id, user_id)
            VALUES (:content, :article_id, :user_id)";
        $comStmt = $db->prepare($comInsert);
        $comStmt ->execute(array(
            ':content' => $_POST['comment'],
            ':article_id' => $article_id,
            ':user_id' => $_SESSION['id']
        ));

        $_SESSION['success'] = "added";
        header('Location: view.php?article_id='.$article_id);
        return;
    }
}
?>
<html>
<head>
    <title><?=$title?></title>
</head>
<body>
    <?=searchBar()?>
    <a href="index.php">Home page </a> 
        <!-- Flash massage -->
        <?=flashMessage()?>
    <div>
        <h1><?=$title?></h1>
        <p><?=$content?></p>
    </div>
    <!-- links -->
    <div>
        <?php
            if(isset($_SESSION['id'])){
                if($row['user_id'] === $_SESSION['id']){
                    echo('
                        <p><a href="edit_article.php?article_id='.$article_id.'">edit</a>
                        <a href="del_article.php?article_id='.$article_id.'">delete</a>
                        </p>
                    ');
                }
            }
        ?>
    </div>
    <h3>Comments</h3>
    <div>
        <!-- Making comment -->
        <?php
        // Cheking the user login statement.
            if(isset($_SESSION['id']) && isset($_SESSION['user'])){
                echo('
                    <form method = "post">
                    <label for="comment"> Make a Comment : </label><br>
                    <textarea name="comment" id="comment" cols="40" rows="5"></textarea><br>
                    <input type="submit" value="Send">  
                    </form>
                ');
            }
            else{
                echo('Make a comment: <a href="login.php"> Let me login</a>');
            }
        ?>
    </div>
    <!-- Comments -->
    <div>
            <?php
            if($comments === 0){
                echo "Make the first comment";
            }
            else{
                foreach($comments as $comment){
                    echo('
                        <div>
                            <p><b><a href="profile.php?user_id='.$comment['user_id'].'">
                            '.htmlentities($comment['username']).': </a></b>
                            '.htmlentities($comment['content']).' <br>
                            <i>'.$comment['created_at'].'</i></p>
                        </div>
                    ');
                    if(count($comment) == 9){
                        break;
                    }
                }
            }
            ?>
    </div>
    <div>
        <?php
            echo'<h2>Other Articles</h2>';
            foreach($otherArticles as $article){
                if($article['id'] === $_REQUEST['article_id'] && count($otherArticles) == 1){
                    echo "No other Article found.";
                }
                else{
                    if($article['id'] === $_REQUEST['article_id']) continue;
                    echo('
                        <div>
                        <a href="view.php?article_id='.$article['id'].'">
                        <b>'.htmlentities($article['title']).'</b> /
                        '.htmlentities($article['username']).'<br>
                        <i>'.htmlentities($article['created_at']).'</i>
                        </a></div><br>
                    ');
                }
            }
           
        ?>
    </div>
</body>
</html>