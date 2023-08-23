<?php 
require_once 'pdo.php';

if(isset($_POST['cancel'])){
    header("Location: index.php");
    return;
}
$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1'; // Password is php123

$failure = false;

if(isset($_POST['who']) && isset($_POST['pass'])){
    if(strlen($_POST['who']) < 1 || strlen($_POST['pass']) < 1){
        $failure = "E-mail and password are required";
    }
    else if (!filter_var($_POST['who'], FILTER_VALIDATE_EMAIL)){
        $failure = "Email must have an at-sign (@)";
    }
    else{
        $HashCheck = hash('md5',$salt.$_POST['pass']);
        // $md5 = hash('md5', 'XyZzy12*_php123');
        if($HashCheck == $stored_hash){
            header("Location: autos.php?name=".urlencode($_POST['who']));
            error_log("Login success ".$_POST['who']);
            return;
        }
        else{
            $failure = "Incorrect password";
            error_log("Login fail ".$_POST['who']." $HashCheck");
        }
    }
}
?>

<html>
<head>
    <title>alexander walker</title>
</head>
<body>
    <h1>Please Log In</h1>
    <?php
        if($failure !== false){
            echo('<p style="color: red;">' .htmlentities($failure)."</p>\n");
        }
    ?>
    <form method="POST">
    <label for="NameLabel">E-mail</label>
    <input type="text" name="who" id="NameLabel"><br>
    <label for="passLabel">Password</label>
    <input type="text" name="pass" id="passLabel"><br>
    <input type="submit" value="Log In">
    <input type="submit" value="Cancel" name="cancel">

    </form>
</body>
</html>