<?php
session_start();
require_once 'pdo.php';
if (!isset($_SESSION["email"])){
    die('Not logged in');
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>alexander walker</title>
</head>
<body>
    <h1>Tracking Autos for <?php echo htmlentities($_SESSION["email"]);?></h1> 
    <?php
    if(isset($_SESSION['success'])){
        echo('<p style="color:green">'.$_SESSION["success"]."</p>\n");
        unset($_SESSION["success"]);
    }
    $stmt = $pdo->query("SELECT make, `year`, mileage FROM autos");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <h2>Automobiles</h2>
    <ul>
        <?php
            foreach($rows as $row){
                echo"<li>";
                echo(htmlentities( $row['year']. " " . $row['make'] . " / ". $row['mileage']));
            }
        ?>
    </ul>
    <p>
        <a href="autos.php">Add New</a> | <a href="logout.php">Logout</a>
    </p>
</body>
</html>