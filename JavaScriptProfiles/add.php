<?php
    session_start();
    require_once 'pdo.php';
    if (!isset($_SESSION["name"]) && !isset($_SESSION['user_id'])){
        die('ACCESS DENIED');
    }
    if(isset($_POST['cancel'])){
        header('Location: index.php');
        return;
    }
    if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) 
        && isset($_POST['headline']) && isset($_POST['summary']) ){
        
        
        if(strlen($_POST['first_name'] < 1) || strlen($_POST['last_name'] < 1) || strlen($_POST['email'] < 1) 
            || strlen($_POST['headline'] < 1)){
            $_SESSION["error"] = "All fields are required";
            header( 'Location: add.php' );
            return;
        }
        else{
            if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                $stmt = $pdo->prepare('INSERT INTO Profile
                (user_id, first_name, last_name, email, headline, summary)
                VALUES ( :uid, :fn, :ln, :em, :he, :su)');
              
              $stmt->execute(array(
                ':uid' => $_SESSION['user_id'],
                ':fn' => $_POST['first_name'],
                ':ln' => $_POST['last_name'],
                ':em' => $_POST['email'],
                ':he' => $_POST['headline'],
                ':su' => $_POST['summary'])
              );
                
                $_SESSION['success'] = "added";
                header("Location: index.php");
                return;
            }
            else {
                $_SESSION['error'] = 'Email address must contain @';
                header("Location: add.php");
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
    <?php
    if(isset($_SESSION['error'])){
        echo('<p style="color:red">'.$_SESSION['error']."</p>\n");
        unset($_SESSION['error']);
    }
    ?>
    <form method="POST">
        <!-- First Name -->
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name"><br>
        <!-- Last Name -->
        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name"><br>
        <!-- E-mail -->
        <label for="email">Email</label>
        <input type="text" id="email" name="email"><br>
        <!-- Headline -->
        <label for="headline">Headline</label>
        <input type="text" name="headline" id="headline"><br>
        <!-- Summary -->
        <label for="summary">Summary</label><br>
        <textarea name="summary" id="summary" cols="30" rows="10"></textarea><br><br>
        <!-- Buttons -->
        <input type="submit" value="Add">
        <input type="submit" value="Cancel" name="cancel">
    </form>
</body>
</html>