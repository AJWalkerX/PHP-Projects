<?php
    require_once "pdo.php";
    session_start();
    
    if ( ! isset($_GET['profile_id']) ) {
        $_SESSION['error'] = "hessing profile_id";
        header('Location: index.php');
        return;
    }
    
    $stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
    $stmt->execute(array(":xyz" => $_GET['profile_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ( $row === false ) {
        $_SESSION['error'] = 'Bad value for profile_id';
        header( 'Location: index.php' ) ;
        return;
    }
    $fn = $row['first_name'];
    $ln = $row['last_name'];
    $em = $row['email'];
    $he = $row['headline'];
    $su = $row['summary'];
?>
<html>
    <body>
        <h1>Profile Information</h1>
        <p>First Name: <?=$fn?></p>
        <p>Last Name: <?=$ln?></p>
        <p>Email: <?=$em?></p>
        <p>Headline: <br>
            <?=$he?>
        </p>
        <p>Summary: <br>
            <?=$su?>
        </p>
        <a href="index.php">Done</a>
    </body>
</html>