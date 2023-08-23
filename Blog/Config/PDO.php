<?php
    $host = 'localhost';
    $dbname = 'blog';
    $username = 'admin';
    $password = 'admin';

    try{
        $db = new PDO("mysql:host=$host; dbname=$dbname", $username, $password);
        $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        error_log("Connected to database");
    }
    catch(PDOException $e){
        error_log("Something went worng: ". $e->getMessage());
    } 
?>