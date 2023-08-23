<?php
    try{
        $pdo = new PDO('mysql:host=localhost; port=3306; dbname=surveys', 'admin', 'admin');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "Database sucssesfully connected.";
    }
    catch(PDOException $e){
        echo"ERROR cannot connect to database.".$e->getMessage();
    }
?>