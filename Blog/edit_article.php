<?php
session_start();
require_once 'Config\PDO.php';
include 'Config\util.php';

if(empty($_SESSION['id'])){
    die("ACCESS DENIED");
}

if(empty($_REQUEST['article_id'])){
    $_SESSION['error'] = "Missing article_id";
    header('Location: index.php');
    die();
}
$_SESSION['article_id'] =$_REQUEST['article_id'];
if(isset($_POST['cancel'])){
    header('Location: view.php?article_id='.$_SESSION['article_id']);
    unset($_SESSION['$article_id']);
    return;
}

$querySelect ="SELECT * FROM Articles 
    WHERE id = :article_id AND user_id = :user_id";
$querySelectStmt = $db->prepare($querySelect);
$querySelectStmt->execute(array(
    ':article_id' => $_REQUEST['article_id'],
    ':user_id' => $_SESSION['id']
));
$article = $querySelectStmt->fetch(PDO::FETCH_ASSOC);

if($article === false){
    $_SESSION['error'] = "Missing article id";
    header('Location: index.php');
    return;
}

if(isset($_POST['title']) && isset($_POST['content'])){
    if(empty($_POST['title']) ||empty($_POST['content'])){
        $_SESSION['error'] = "All fields must required";
        header('location: edit_article.php?article_id='.$_REQUEST['article_id']);
        return;
    }
    else{
        $_SESSION['article_id'] = $_REQUEST['article_id'];
        $queryUpdate = "UPDATE Articles SET title = :title, content = :content 
            WHERE id = :article_id";
        $queryUpdateStmt = $db->prepare($queryUpdate);
        $queryUpdateStmt->execute(array(
            ':title' => $_POST['title'],
            ':content' => $_POST['content'],
            ':article_id' => $_REQUEST['article_id']
        ));
        $_SESSION['success'] = "Updated";
        header('Location: view.php?article_id='.$_SESSION['article_id']);
        unset($_SESSION['article_id']);
        return;
    }
}
?>

<html>
    <head>
        <title><?=htmlentities($article['title'])?></title>
    </head>
    <body>
        <!-- Flash massages -->
        <?=flashMessage()?>

        <form method="post">
            <!-- title -->
            <label for="title"><b>Title:</b></label>
            <input type="text" name="title" id="title" value="<?=htmlentities($article['title'])?>"><br>
            <!-- content -->
            <label for="content"><b>Content:</b></label><br>
            <textarea name="content" id="content" cols="60" rows="40"><?=htmlentities($article['content'])?></textarea>
            <br>
            <!-- buttons -->
            <input type="submit" value="Submit">
            <input type="submit" value="Cancel" name="cancel">
        </form>
    </body>
</html>