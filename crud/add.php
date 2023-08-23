<?php
    session_start();
    require_once 'pdo.php';
    if (!isset($_SESSION["email"])){
        die('ACCESS DENIED');
    }
    if(isset($_POST['cancel'])){
        header('Location: index.php');
        return;
    }

    if(isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) && isset($_POST['mileage'])){
        
        
        if(strlen($_POST['make'] < 1) || strlen($_POST['model'] < 1) || strlen($_POST['year'] < 1) || strlen($_POST['mileage'] < 1)){
            $_SESSION["error"] = "All fields are required";
            header( 'Location: add.php' );
            return;
        }
        else{
            if (is_numeric($_POST['mileage']) && is_numeric($_POST['year'])){
                $stmt = $pdo->prepare('INSERT INTO autos
                    (make, model, `year`, mileage) VALUES ( :mk, :md, :yr, :mi)');
            
                $stmt->execute(array(
                    ':mk' => $_POST['make'],
                    ':md' => $_POST['model'],
                    ':yr' => $_POST['year'],
                    ':mi' => $_POST['mileage'])
                );
                
                $_SESSION['success'] = "added";
                header("Location: index.php");
                return;
            }
            else if (!is_numeric($_POST['year']) ) {
                $_SESSION['error'] = 'Year must be numeric';
                header("Location: add.php");
                return;
            }
            else if (!is_numeric($_POST['mileage']) ) {
                $_SESSION['error'] = 'mileage must be numeric';
                header("Location: add.php");
                return;
            }
        }
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
        <label for="md">Model</label>
        <input type="text" id="md" name="model"><br><br>
        <label for="yr">year</label>
        <input type="text" id="yr" name="year"><br><br>
        <label for="mi">mileage</label>
        <input type="text" id="mi" name="mileage"><br><br>
        <input type="submit" value="Add">
        <input type="submit" value="cancel" name="cancel"><br><br>
    </form>
</body>
</html>