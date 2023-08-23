<?php
    session_start();
    if(isset($_POST['email']) && isset($_POST['pass'])){
        unset($_SESSION["email"]);

        if(strlen($_POST['email']) < 1 ||strlen($_POST['pass']) < 1){
            $_SESSION["error"] = "E-mail and password are required";
            header('Location: login.php');
            return;
        }
        else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $_SESSION["error"] = "Email must have an at-sign (@)";
            header( 'Location: login.php' ) ;
            return;
        }
        else{
            if($_POST['email'] == "umsi@umich.edu" && $_POST['pass'] == "php123"){
                $_SESSION["email"] = $_POST["email"];
                header('Location: index.php');
                error_log("Login success". $_POST['email']);
                return;
            }
            else{
            $_SESSION["error"] = "Incorrect password or E-mail";
            header( 'Location: login.php' ) ;
            error_log("Login fail ".$_POST['email']);
            return;
        }
         }
    }
    
?>
<html lang="en">
<head>
    <title>alexander walker</title>
</head>
<body>
    <h1>Please Log In</h1>

    <!-- error massage -->
    <?php
        if(isset($_SESSION["error"])){
            echo('<p style= "color:red">'.$_SESSION["error"]. "</p>\n");
            unset($_SESSION["error"]);
        }
    ?>


    <form method="POST">
        <!-- email -->
        <label for="email">E-mail</label>
        <input type="text" id="email" name="email">
        <br>
        <!-- Password -->
        <label for="pass">Password</label>
        <input type="password" name="pass" id="pass">
        <br>
        <!-- buttons -->
        <input type="submit" value="Log In">
        <a href="index.php">Cancel</a>

    </form>
</body>
</html>