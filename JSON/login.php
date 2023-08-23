<?php
    session_start();
    require_once "pdo.php";
    require_once "util.php";

    if(isset($_POST['email']) && isset($_POST['pass'])){
        unset($_SESSION["name"]);
        unset($_SESSION["user_id"]);
        $salt = 'XyZzy12*_';
        $check = hash('md5', $salt.$_POST['pass']);
        $stmt = $pdo->prepare('SELECT user_id, name FROM users
            WHERE email = :em AND password = :pw');
        $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(strlen($_POST['email']) < 1 ||strlen($_POST['pass']) < 1){
            // $_SESSION["error"] = "E-mail and password are required";
            header('Location: login.php');
            return;
        }
        else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            // $_SESSION["error"] = "Email must have an at-sign (@)";
            header( 'Location: login.php' ) ;
            return;
        }
        else{
            if ( $row !== false ) {

                $_SESSION['name'] = $row['name'];
                $_SESSION['user_id'] = $row['user_id'];
       
                // Redirect the browser to index.php
                header("Location: index.php");
                return;
            }
            else{
            // $_SESSION["error"] = "Incorrect password or E-mail";
            header( 'Location: login.php' ) ;
            error_log("Login fail ".$_POST['email']);
            return;
        }
         }
    }
    
?>
<html lang="en">
<head>,
    <<?= require_once "head.php";?>
    <title>alexander walker</title>
</head>
<body>
    <h1>Please Log In</h1>

    <!-- error massage -->
    <?=flashMessage();?>
    <script>
        function doValidate() {
         console.log('Validating...');
         try {
             pw = document.getElementById('id_1723').value;
             console.log("Validating pw="+pw);
             if (pw == null || pw == "") {
                 alert("Both fields must be filled out");
                 return false;
             }
             return true;
         } catch(e) {
             return false;
         }
         return false;
     }
    </script>
    

    <form method="POST">
        <!-- email -->
        <label for="email">E-mail</label>
        <input type="text" id="email" name="email">
        <br>
        <!-- Password -->
        <label for="id_1723">Password</label>
        <input type="password" name="pass" id="id_1723">
        <br>
        <!-- buttons -->
        <input type="submit" onclick="return doValidate();" value="Log In">
        <a href="index.php">Cancel</a>

    </form>
</body>
</html>