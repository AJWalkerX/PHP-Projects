<?php
require_once "pdo.php";
session_start();

if(isset($_POST['cancel'])){
    header('Location: index.php');
    return;
}

if ( isset($_POST['first_name']) && isset($_POST['last_name'])
     && isset($_POST['email']) && isset($_POST['profile_id'])
     && isset($_POST['headline']) && isset($_POST['summary'])) {
     // Data validation
     if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 
          || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1) {
        $_SESSION['error'] = "All fields are required";
        header("Location: edit.php?profile_id=".$_REQUEST['profile_id']);
        return;
    }
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Email address must contain @';
        header("Location: edit.php?profile_id=".$_REQUEST['profile_id']);
        return;
    }
    
    $sql = "UPDATE profile 
        SET first_name = :fn, last_name = :ln, 
            email = :em, headline = :he , summary = :su
        WHERE profile_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':id' => $_POST['profile_id'],
        ':su' => $_POST['summary'])
    );
    
    $_SESSION['success'] = 'Record updated';
    header( 'Location: index.php' ) ;
    return;                        
}

// Guardian: first_name sure that profile_id is present
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
echo('<h1>Editing Profiles</h1>');
// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$fn = htmlentities($row['first_name']);
$ln = htmlentities($row['last_name']);
$em = $row['email'];
$he = htmlentities($row['headline']);
$id = htmlentities($row['profile_id']);
$su = htmlentities($row['summary']);
?>
<form method="post">
<p>first_name:
<input type="text" name="first_name" value="<?= $fn ?>"></p>
<p>last_name:
<input type="text" name="last_name" value="<?= $ln ?>"></p>
<p>email:
<input type="text" name="email" value="<?= $em ?>"></p>
<p>headline:
<input type="text" name="headline" value="<?= $he ?>"></p>
<p>Summary: <br>
<textarea name="summary"cols="30" rows="10" value=""><?= $su ?></textarea>

</p>
<input type="hidden" name="profile_id" value="<?= $id ?>">
<p><input type="submit" value="Save"/>
<input type="submit" value="Cancel"/></p>
</form>
