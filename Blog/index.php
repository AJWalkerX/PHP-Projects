<?php
    session_start();
    require_once 'Config\PDO.php';
    include 'Config\util.php';

    // Selectin Articles from Db.
    $query = "SELECT title, content, username , Articles.created_at, Articles.id 
        FROM Articles JOIN Users ON Articles.user_id = Users.id";
    $stmt = $db->query($query);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<html>
<head>
    <title>index</title>
</head>
<body>
    <h1>Welcome to my very first blog page</h1>
    <!-- Flash massage -->
    <?=flashMessage();?>
    <!-- DoCs -->
    <?php
        searchBar();
        if(!isset($_SESSION['id'])){
            echo('<a href="login.php"> Please log in</a>');
            
            echo('<h2>Articles</h2>');
            if(count($rows) === 0){
                echo('<p> No article entry found.</p>');
            }
            else{
                foreach($rows as $row){
                    echo('<a href="view.php?article_id='.$row['id'].'">');
                    echo('<div><b>'.htmlentities($row['title']).' / '.htmlentities($row['username']).'</b>');
                    echo('<br><i>'.htmlentities($row['created_at']).'</i>');
                    echo('</div></a><br>'."\n");
                }
            }

        }
        else{
            echo('<h2>Articles</h2>');            
            if(count($rows) === 0){
                echo('<p> No article entry found.</p>');
            }
            else{
                foreach($rows as $row){
                    echo('<a href="view.php?article_id='.$row['id'].'">');
                    echo('<div><b>'.htmlentities($row['title']).' / '.htmlentities($row['username']).'</b>');
                    echo('<br><i>'.htmlentities($row['created_at']).'</i>');
                    echo('</div></a><br>'."\n");
                }
            }
            
            echo('<p> Add a <a href="add.php"> New Article </a></p>');
            echo('<a href="profile.php?user_id='.$_SESSION['id'].'">Profile</a> / <a href="logout.php"> Log Out</a>');
        }
    ?>

</body>
</html>