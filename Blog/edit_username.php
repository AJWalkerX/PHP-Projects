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
$query = "SELECT username, password FROM Users WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->execute(array(':id' => $_REQUEST['user_id']));
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if($user === 0){
    $_SESSION['error'] = "Missing user";
    header('Location: index.php');
    return;
}

if(isset($_POST['user']) && isset($_POST['pass'])){
    if(strlen($_POST['user']) < 1 || strlen($_POST['pass']) < 1){
        $_SESSION['error'] = "All fields are required";
        header('Location: edit_username.php?user_id='.$_REQUEST['user_id']);
        return;
    }
    if(passwordVerify($user['password']) === false){
        $_SESSION['error'] = "Incorect password";
        header('Location: edit_username.php?user_id='.$_REQUEST['user_id']);
        return;
    }
    $msg = validateUsername();
    if(is_string($msg)){
        $_SESSION['error'] = $msg;
        header('Location: edit_username.php?user_id='.$_REQUEST['user_id']);
        return;
    }
    else{
        $query = "SELECT username FROM Users";
        $stmt = $db->query($query);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($users as $user){
            if($user['username'] === $_POST['user']){
                $_SESSION['error'] = "This user name already taken";
                header('Location: edit_username.php?user_id='.$_REQUEST['user_id']);
                return;
            }
        }           
            $query = "UPDATE Users SET username = :user WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->execute(array(':user' => $_POST['user'], ':id' => $_REQUEST['user_id']));
            
            $_SESSION['success'] = "User name has benn changed";
            $_SESSION['user'] = $_POST['user'];
            header('Location: profile.php?user_id='.$_REQUEST['user_id']);
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
            <label for="pass">Password:</label>
            <input type="password" name="pass" id="pass"><br>
            <label for="user">User Name:</label>
            <input type="text" name="user" id="user" value="<?=$user['username']?>"><br>
            <input type="submit" value="Change">
        </form>
    </body>
</html>