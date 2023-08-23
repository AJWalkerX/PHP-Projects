<?php
    session_start();
    require_once 'Config\PDO.php';
    include 'Config\util.php';

    // If the user already signed up this will progress
    if(isset($_SESSION['id'])){
        header('Location: index.php');
        $_SESSION['warnning'] = "Look like already loged in";
        return;
    }

    // if user canceling to sign up it will return to login page
    if(isset($_POST['cancel'])){
        header('Location: login.php');
        return;
    }
    
    // After entiring the submit button all post data will be set
    if(isset($_POST['user']) && isset($_POST['email']) && isset($_POST['pass'])){
        
        // Variables
        $username = $_POST['user'];
        $email = $_POST['email'];
        $password = $_POST['pass'];
    
        if(strlen($username) < 1 || strlen($email) < 1 || strlen($password) < 1){
            $_SESSION['error'] = "All field must contain";
            header('Location: signup.php');
            return;
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $_SESSION['error'] = "E-mail must contain @ sign";
            header('Location: signup.php');
            return;
        }
        if($_POST['email'] !== $_POST['emailConform']){
            $_SESSION['error'] = "email not maching";
            header('Location: signup.php');
            return;
        }
        $msg = validateUsername();
        if(is_string($msg)){
            $_SESSION['error'] = $msg;
            header('Location: signup.php');
            return;
        }
        $msg = validatePassword();
        if(is_string($msg)){
            $_SESSION['error'] = $msg;
            header('Location: signup.php');
            return;
        }
        else{
            // Selecting datas from the db.
            $query = "SELECT * FROM users 
                    WHERE email = :email OR username = :username";
            $stmt = $db->prepare($query);
            $stmt -> execute(array(
                ':email' => $email,
                ':username' => $username
            ));
            $checkUser = $stmt->fetch(PDO::FETCH_ASSOC);

            if($checkUser['email'] === $email){
                $_SESSION['error'] = "email already taken";
                header('Location: signup.php');
                return;
            }
            if($checkUser['username'] === $username){
                $_SESSION['error'] = "user name already taken";
                header('Location: signup.php');
                return;
            }
            else{
                $passHash = passwordHashing();
                $query = "INSERT INTO users (username, password, email)
                    VALUES (:username, :password, :email)";
                $stmt= $db->prepare($query);
                $stmt->execute(array(
                    ':username' => $username,
                    ':email' => $email,
                    ':password' =>$passHash
                ));
                $_SESSION['success'] = "successfully signed in";
                header('Location: login.php');
                return;
            }
        }
    }
    

    ?>
<html>
    <header>
        <title>Sign Up</title>
    </header>
    <body>
       <!-- Flash massages for the sign up errors -->
       <?=flashMessage()?>
       <!-- Flash massages for the sign up errors -->
       
       <!-- This is the form for the sign up an user -->
        <form method="POST">
            <!-- İnputs -->
                <!-- Email -->
                <label for="email">Email:</label>
                <input type="text" name="email" id="email"/>
                <label for="emailConform">Conform Email:</label>
                <input type="text" name="emailConform" id="emailConform"><br>
                <!-- Username -->
                <label for="user">Username:</label>
                <input type="text" name="user" id="user"/><br>
                <!-- Password -->
                <label for="pass">Password:</label>
                <input type="password" name="pass" id="pass">
                <label for="passConform">Conform Password:</label>
                <input type="password" name="passConform" id="passConform"><br>
            <!-- Buttons -->
                <!-- Submit button -->
                <input type="submit" value="Sign Up">
                <!-- Cancel button -->
                <input type="submit" value="Cancel" name="cancel">
        </form>
    </body>
</html>