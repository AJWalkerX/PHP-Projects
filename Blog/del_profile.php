<?php
session_start();
require_once 'Config\PDO.php';

if(empty($_SESSION['user']) && empty($_SESSION['id'])){
    die("ACCESS DENIED");
}
if(empty($_REQUEST['user_id'])){
    $_SESSION['error'] = "Missing user_id";
    header('Location: index.php');
    return;
}
if(isset($_POST['cancel'])){
    header('Location: settings.php?user_id='.$_REQUEST['user_id']);
    return;
}
if(isset($_POST['delete'])){
    $query = "DELETE FROM Users WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->execute(array(':id' => $_SESSION['id']));

    
    $_SESSION['success'] = "Account deleted";
    header('Location: logout.php');
    return;
}
?>
<html>
    <head>
        <title></title>
    </head>
    <body>
        Conform deleting Account: <?=$_SESSION['user']?><br>
        <form method="post">
            <input type="submit" value="Delete" name="delete">
            <input type="submit" value="Cancel" name="cancel">
        </form>
    </body>
</html>