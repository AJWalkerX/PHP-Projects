<?php
session_start();
require_once 'pdo.php';
if (!isset($_SESSION["email"])){
    die('Not logged in');
}
if(isset($_POST['cancel'])){
    header('Location: view.php');
    return;
}

if(isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])){
    

    if(is_numeric($_POST['mileage']) && is_numeric($_POST['year'])){

        if (strlen($_POST['make'] < 1)){
            $_SESSION["error"] = "Make is required";
            header( 'Location: autos.php' );
            return;
        }
        else{
            $stmt = $pdo->prepare('INSERT INTO autos
                (make, `year`, mileage) VALUES ( :mk, :yr, :mi)');
        
            $stmt->execute(array(
                ':mk' => $_POST['make'],
                ':yr' => $_POST['year'],
                ':mi' => $_POST['mileage'])
            );
            
            $_SESSION['success'] = "Record inserted";
            header("Location: view.php");
            return;
        }
    }
    else{
        $_SESSION["error"] = "Mileage and year must be numeric";
        header( 'Location: autos.php' );
        return;
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
    <h1>Tracking Autos for <?php echo htmlentities($_SESSION['email']);?></h1> 

    <?php
        if ( isset($_SESSION["error"]) ) {
            echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
            unset($_SESSION["error"]);
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
        <input type="submit" value="cancel" name="cancel"><br><br>
    </form>
</body>
</html>