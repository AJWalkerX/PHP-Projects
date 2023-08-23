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

?>
<html>
    <head>
        <title></title>
    </head>
    <body>
        <ul>
            <li><a href="edit_profile.php?user_id=<?=$_REQUEST['user_id']?>">Edit Profile</a></li>
            <li><a href="edit_pass.php?user_id=<?=$_REQUEST['user_id']?>">Change password</a></li>
            <li><a href="edit_username.php?user_id=<?=$_REQUEST['user_id']?>">Change User name</a></li>
            <li><a href="del_profile.php?user_id=<?=$_REQUEST['user_id']?>">Delete Account</a></li>
        </ul>
    </body>
</html>