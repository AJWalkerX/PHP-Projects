<?php
session_start();
require_once 'Config\PDO.php';
include 'Config\util.php';

if(isset($_POST['cancel'])){
    header('Location: index.php');
    return;
}

if(isset($_POST['email']) && isset($_POST['pass'])){
    unset($_SESSION['user']);
    unset($_SESSION['id']);

    // Variables
    $email = $_POST['email'];
    $password = $_POST['pass'];
    
    if(strlen($email) < 1 || strlen($password) < 1){
        $_SESSION['error'] = "All fields must contain";
        header('Location: login.php');
        return;
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $_SESSION['error'] = "Email must contain @ sign";
        header('Location: login.php');
        return;
    }
    else{
        // Selecting entered email from db.
        $queryUser = "SELECT * FROM Users WHERE email = :email";
        $stmtUser = $db->prepare($queryUser);
        $stmtUser->execute(array(':email' => $email));
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

        if($user === false){
            $_SESSION['error'] = "User not found try again or sign up";
            header('Location: login.php');
            return;
        }

        if(passwordVerify($user['password'])){
            $_SESSION['id'] = $user['id'];
            $_SESSION['user'] = $user['username'];
    
            $_SESSION['success'] = "Successfully loged in";
            header('Location: index.php');
            return;
        }
        else{
            $_SESSION['error'] = "Incorrect E-mail or password";
            header('Location: login.php');
            return;
        }
    }

}
?>
<html>
    <header>
        <title>login</title>
    </header>
    <body>
            <!-- Flash massage -->
            <?=flashMessage()?>

    
        <form method="POST">
            <!-- email -->
            <label for="email">Email: </label>
            <input type="text" name="email" id="email"/><br>
            <!-- password -->
            <label for="pass">Password: </label>
            <input type="password" name="pass" id="pass"><br>
            <!-- Buttons -->
            <input type="submit" value="Login">
            <input type="submit" value="Cancel" name="cancel"><br> 
        </form>
        <a href="signup.php">Sing Up</a>
    </body>
</html>