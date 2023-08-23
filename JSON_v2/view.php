<?php
require_once "pdo.php";
require_once "head.php";

session_start();

if (! isset($_GET['profile_id']) ) {
    $_SESSION['error'] = "Missing profile_id";
    header("Location: index.php");
    die();
}

$sql = "SELECT * FROM profile WHERE profile_id = :profile_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(":profile_id" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header('Location: index.php');
    die();
}

$fn = htmlentities($row["first_name"]);
$ln = htmlentities($row["last_name"]);
$em = htmlentities($row["email"]);
$he = htmlentities($row["headline"]);
$su = htmlentities($row["summary"]);

$sql = "SELECT * FROM position WHERE profile_id = :profile_id";
$stmt1 = $pdo->prepare($sql);
$stmt1->execute(array(":profile_id" => $_GET['profile_id']));
$position_rows = $stmt1->fetchAll(PDO::FETCH_ASSOC);

$stmt2 = $pdo->prepare
    ('SELECT year, name FROM Education 
    JOIN Institution 
        ON Education.institution_id = Institution.institution_id 
    WHERE profile_id = :prof ORDER BY rank');
    $stmt2->execute(array(':prof' => $profile_id));
    $educations = $stmt ->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Alexander Walker</title>
</head>
<body>
<div class="container">
    <h1>Profile</h1>
    <p>First Name: <?php echo $fn ?></p>
    <p>Last Name: <?php echo $ln ?></p>
    <p>Email: <?php echo $em ?></p>
    <p>Headline:<?php echo $he ?></p>
    <p>Summary:<?php echo $su ?></p>

    <?php
    if ($position_rows !== false) {
        echo '<p>Position:' . "\n" . '<ul>' . "\n";
    }
    foreach ($position_rows as $row) {
        echo '<li>' . $row["year"] . ': ' . $row["description"] . '</li>' . "\n";
    }
            echo '</ul>'. "\n" . '</p>';

    //Edu
    if ($educations !== false) {
        echo '<p>Education:' . "\n" . '<ul>' . "\n";
    }
    foreach ($educations as $edu) {
        echo '<li>' . $edu["year"] . ': ' . $edu["name"] . '</li>' . "\n";
    }
            echo '</ul>'. "\n" . '</p>';
    ?>
    <a href="index.php">Done</a>
</body>
</html>

