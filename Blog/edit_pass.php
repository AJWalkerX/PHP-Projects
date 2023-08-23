<?php
session_start();
require_once 'Config\PDO.php';
include 'Config\util.php';

if(empty($_SESSION['user']) && empty($_SESSION['id'])){
    die("ACCESS DENIED");
}
if(empty($_REQUEST['user_id'])){
    $_SESSION['error'] = "Missing user_id";
    header('Location: index.php');
    return;
}
$query = "SELECT password FROM Users WHERE id= :id";
$stmt = $db->prepare($query);
$stmt->execute(array(':id' => $_REQUEST['user_id']));
$pass = $stmt->fetch(PDO::FETCH_ASSOC);

if($pass === false){
    $_SESSION['error'] = "Missing user";
    header('Location: index.php');
    return;
}

if(isset($_POST['old']) && isset($_POST['new']) && isset($_POST['newConform'])){
   
    if(strlen($_POST['old']) < 1 || strlen($_POST['new']) < 1 || strlen($_POST['newConform']) < 1){
        $_SESSION['error'] = "All fields are required";
        header('Location: edit_pass.php?user_id='.$_REQUEST['user_id']);
        return;
    }
    
    if(!password_verify($_POST['old'],$pass['password'])){
        $_SESSION['error'] = "Incorrect password";
        header('Location: edit_pass.php?user_id='.$_REQUEST['user_id']);
        return;
    }
    $msg = validatePassword();
    if(is_string($msg)){
        $_SESSION['error'] = $msg;
        header('Location: edit_pass.php?user_id='.$_REQUEST['user_id']);
        return;
    }
    else if((password_verify($_POST['old'],$pass['password'])) && ($_POST['new'] === $_POST['newConform'])){
        $hashPass = password_hash($_POST['new'], PASSWORD_DEFAULT);
        $query ="UPDATE Users SET password = :pass WHERE id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->execute(array(':pass' => $hashPass, ':user_id' => $_REQUEST['user_id']));

        $_SESSION['success'] = "password changed";
        header('Location: logout.php');
        return;
    }
    else{
        $_SESSION['error'] = "Unknown error";
        header('Location: edit_pass.php?user_id='.$_REQUEST['user_id']);
        return;
    }
}
?>
<html>
    <head>
        <title></title>
    </head>
    <body>
        <?=flashMessage()?>
        <form method="post">
            <label for="old">Old password:</label>
            <input type="password" name="old" id="old"><br>
            <label for="new">New password:</label>
            <input type="password" name="pass" id="new"><br>
            <label for="newConform">Password Conform:</label>
            <input type="password" name="passConform" id="newConform"><br>
            <input type="submit" value="Change">
        </form>
    </body>
</html>