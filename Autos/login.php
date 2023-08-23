<?php
if(isset($_POST['cancel'])){
    header("location: index.php");
    return;
}
$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1'; // Password is php123


session_start();
if(isset($_POST['email']) && isset($_POST['pass'])){
    unset($_SESSION["email"]);

    if(strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1){
        $_SESSION["error"] = " E-mail and password are required";
        header( 'Location: login.php' ) ;
        return;
    }
    else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $_SESSION["error"] = "Email must have an at-sign (@)";
        header( 'Location: login.php' ) ;
        return;
    }
    else{
        $HashCheck = hash('md5', $salt.$_POST['pass']);

        if($HashCheck == $stored_hash){
            $_SESSION["email"] = $_POST["email"];
            header( 'Location: view.php' );
            error_log("Login success ".$_POST['email']);
            return;
        }
        else{
            $_SESSION["error"] = "Incorrect password";
            header( 'Location: login.php' ) ;
            error_log("Login fail ".$_POST['email']." $HashCheck");
            return;
        }
    }
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alexander Walker</title>
</head>
<body>
    <H1>Please Log In</H1>
   <?php
        if ( isset($_SESSION["error"]) ) {
            echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
            unset($_SESSION["error"]);
        }
   ?>
    <form method="post">
        <!-- E-Mail -->
        <label for="Email">Email</label>
        <input type="text" id="email" name="email"><br>
        <!-- password -->
        <label for="Pass">Password</label>
        <input type="text" id="Pass" name="pass"><br>
        <!-- buttons -->
        <input type="submit" value="Log In">
        <input type="submit" value="Cancel" name="cancel">
    </form>
</body>
</html>