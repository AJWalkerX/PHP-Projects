<?php
session_start();
require_once 'Config\PDO.php';

if(!isset($_SESSION['id']) && !isset($_SESSION['user'])){
    die("ACCESS DENIED");
}
if(!isset($_REQUEST['user_id'])){
    $_SESSION['error'] = "Missing user_id";
    header('Location: index.php');
    return;
}

$query = "SELECT * FROM Profiles WHERE user_id =:user_id";
$stmt = $db->prepare($query);
$stmt->execute(array(':user_id' => $_REQUEST['user_id']));
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

if(empty($profile)){
    $_SESSION['error'] = "personal details not found.";
    header('Location: profile.php?user_id='.$_REQUEST['user_id']);
    return;
}
?>
<html>
    <head>
        <title><?=$_SESSION['user']?></title>
    </head>
    <body>
        <h1>Personal Details</h1>
        <p>First Name: <?=$profile['first_name']?></p>     
        <p>Last Name: <?=$profile['last_name']?></p>
        <p>Gender: <?=$profile['gender']?></p> 
        <p>Birth Date: <?=$profile['birth_date']?></p> 
        <p>Contact: (+<?=$profile['phone_code']?>) <?=$profile['phone_number']?></p>     
        <p>About:<br> <?=$profile['about']?></p>
        <a href="profile.php?user_id="<?=$_REQUEST['user_id']?>></a> 
    </body>
</html>