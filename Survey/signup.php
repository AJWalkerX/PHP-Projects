<!-- Kullanıcı burada kayıt olucak -->
<?php
    session_start();
    require_once 'pdo.php';
    // Canceling the sing up session.
    if(isset($_POST['cancel'])){
        header('Location: index.php');
        return;
    }
    // error checks
    if(isset($_POST['email']) && isset($_POST['pass'])){
        // For sign up all fields must required!
        if(strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1){
            $_SESSION['error'] = "All fields are required";
            header('Location: signup.php');
            return;
        }
        // Chekking iş it real email address.
        else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $_SESSION['error'] = 'Email address must contain @';
            header('Location: signup.php');
            return;
        }
        else{
            // Checking the email is it allready siged up.
            $stmt = $pdo->query("SELECT email FROM user");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($rows as $row){
                if($rows === $_POST['email']){
                    $_SESSION['error'] = "this email allready singed up";
                    header('Location: signup.php');
                    return;
                }
            }
            // If there is not an error this is saving 
            $stmt = $pdo->prepare('INSERT INTO user(email, password) VALUES(:em, :pass)');
            $stmt->execute(array(
                ':em' => $_SESSION['email'],
                ':pass' => $_SESSION['pass']
            ));
            $_SESSION['success'] = "Signed up successfully."; //login.php sayfasında yeşil şekilde gözükecek.
            header('Location: login.php');
            return;
        }
        
    }
    
?>

<html lang="en">
<head>
    <title>Sing Up</title>
</head>
<body>
    <?php
    if(isset($_SESSION['error'])){
        echo('<p style="color:red">'.$_SESSION['error']."</p>\n");
        unset($_SESSION['error']);
    }
    ?>
    <form method="POST">
        <!-- Email -->
        <label for="email">Email:</label>
        <input type="text" id="email" name="email"><br>
        <!-- password -->
        <label for="pass">Password:</label>
        <input type="text" id="pass" name="pass"><br>
        <!-- buttons -->
        <input type="submit" value="Sign up">
        <input type="submit" value="cancel" name="cancel">
    </form>
</body>
</html>