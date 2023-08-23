<?php
$dbname = 'blog';
$host = 'localhost';
$username = 'admin';
$password = 'admin';

// Create connection
$db = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$db) {
  error_log("Connection failed: ". mysqli_connect_error());
}
error_log("Connected to database");
?>
