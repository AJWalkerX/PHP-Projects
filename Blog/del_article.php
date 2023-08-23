<?php
session_start();
require_once 'Config\PDO.php';
include 'Config\util.php';

if(empty($_REQUEST['article_id'])){
    $_SESSION['error'] = "Missing article_id";
    header('Location: index.php');
    return;
}

if(isset($_POST['cancel'])){
    header('Location: view.php?article_id='.$_REQUEST['article_id']);
    return;
}

if(isset($_POST['delete']) && isset($_POST['article_id'])){
    $query = "DELETE FROM Articles WHERE id = :article_id";
    $stmt = $db->prepare($query);
    $stmt->execute(array(':article_id' => $_REQUEST['article_id']));
    $_SESSION['success'] = "Article deleted.";
    header('Location: index.php');
    return;
}

$query ="SELECT title FROM Articles WHERE id = :article_id";
$stmt = $db->prepare($query);
$stmt->execute(array(':article_id' => $_REQUEST['article_id']));
$article = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<html>
    <head>
        <title>Deleting</title>
    </head>
    <body>
        <h1>Deliting Article</h1>
        <p>
            Confirm: <?=$article['title']?>
        </p>
        <form method="post">
            <input type="hidden" name="article_id" value="<?=$_REQUEST['article_id']?>">
            <input type="submit" value="Delete" name="delete">
            <input type="submit" value="Cancel" name="cancel">
        </form>
    </body>
</html>