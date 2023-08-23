<?php
if (!isset($_GET['name'])|| strlen($_GET['name']) < 1){
    die("Name parameter missing");
}
if(isset($_POST['logout'])){
    header('Location: index.php');
    return;
}
require_once 'pdo.php';
$name = $_GET['name'];
$failure = false;
if(isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])){
    

    if(is_numeric($_POST['mileage']) && is_numeric($_POST['year'])){

        if (strlen($_POST['make'] < 1)){
            $failure = "Make is required"; 
        }
        else{
            $stmt = $pdo->prepare('INSERT INTO autos
                (make, `year`, mileage) VALUES ( :mk, :yr, :mi)');
        
            $stmt->execute(array(
                ':mk' => $_POST['make'],
                ':yr' => $_POST['year'],
                ':mi' => $_POST['mileage'])
            );
            $failure = "Record inserted";
        }
    }
    else{
        $failure = "Mileage and year must be numeric"; 
    }
}

$stmt = $pdo->query("SELECT make, `year`, mileage FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>alexander walker</title>
</head>
<body>
    <h1>Tracking Autos for <?php echo htmlentities($name);?></h1> 

    <?php
        if($failure == "Record inserted"){
            echo('<p style="color: green;">' .htmlentities($failure)."</p>\n");
        }
        else if ($failure !== false){
            echo('<p style="color: red;">' .htmlentities($failure)."</p>\n");
        }
    ?>
    <form method="POST">
        <label for="mk">make</label>
        <input type="text" id="mk" name="make"><br><br>
        <label for="yr">year</label>
        <input type="text" id="yr" name="year"><br><br>
        <label for="mi">mileage</label>
        <input type="text" id="mi" name="mileage"><br><br>
        <input type="submit" value="Add">
        <input type="submit" value="Logout" name="logout"><br><br>
    </form>

    <h2>Automobiles</h2>
<table border="1">
<?php
foreach ( $rows as $row ) {
    echo "<tr><td>";
    echo(htmlentities($row['make']));
    echo("</td><td>");
    echo(htmlentities($row['year']));
    echo("</td><td>");
    echo(htmlentities($row['mileage']));
    echo("</td></tr>\n");
}
?>
</table>

</body>
</html>