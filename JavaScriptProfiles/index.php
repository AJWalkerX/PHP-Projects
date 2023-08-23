<?php
require_once "pdo.php";
session_start();

$stmt = $pdo->query("SELECT * FROM profile");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<html>
<head>
    <title>alexander walker</title>
</head>
<body>
    <h1>Resume Registry</h1>
    <?php
        if(isset($_SESSION['name']) && isset($_SESSION['user_id'])){
            // flash-message for the user
            if(isset($_SESSION['success'])){
                echo('<p style="color:green">'. $_SESSION['success']. '</p>');
                unset($_SESSION['success']);
            }
            if(isset($_SESSION['error'])){
                echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
                unset($_SESSION['error']);
            }
            // logout link
            echo('<a href="logout.php">Logout</a><br>');
            // add.php link for the entry
            echo('<a href="add.php">Add New Entry</a>'."\n");
            //Table of user 
            if(count($rows) === 0){
                echo '<br><br><b>No rows found</b> <br><br>';
            }
            else{
                echo('<table border="1">');
                // Başlıklar
                echo('<tr>');
                echo('<th>Name</th>');
                echo('<th>Headline</th>');
                echo('<th>Actions</th>');
                echo('</tr>');
                // içerikler
                foreach($rows as $row){
                    echo('<tr>');
                    echo('<td> <a href="view.php?profile_id='.$row['profile_id'].'">'
                    .htmlentities($row['first_name']). " " .htmlentities($row['last_name']). '</a></td>');
                    echo('<td>'.htmlentities($row['headline']). '</td>');
                    echo('<td> <a href="edit.php?profile_id='.$row['profile_id'].'">edit </a>'. " ".
                    '<a href="delete.php?profile_id='.$row['profile_id'].'"> delete</a> </td>');
                    echo('</tr>');
                }
                echo('</table>');
            }
            
        }
        else{
            // login link
            echo('<a href="login.php">Please Log in</a><br>');
            // table of user
            if(count($rows) !== 0){
                echo('<table border="1">');
                // Başlıklar
                echo('<tr>');
                echo('<th>Name</th>');
                echo('<th>Headline</th>');
                echo('</tr>');
                // içerikler
                foreach($rows as $row){
                    echo('<tr>');
                    echo('<td> <a href="view.php?profile_id='.$row['profile_id'].'">'. 
                        htmlentities($row['first_name']). " " .htmlentities($row['last_name']). '</a></td>');
                    echo('<td>'.htmlentities($row['headline']). '</td>');
                    echo('</tr>');
                }
                echo('</table>');
        }
        }
    ?>
</body>
</html>