<?php
    session_start();
    require_once 'Config\PDO.php';

    $search = $_REQUEST['search'];

    // Selecting user by search
    $queryUser = "SELECT username, id FROM Users WHERE username LIKE :search";
    $stmtUser = $db->prepare($queryUser);
    $stmtUser->execute(array(':search' => '%'.$search.'%'));
    $users = $stmtUser->fetchAll(PDO::FETCH_ASSOC);
    if($users === false){
        $_SESSION['error'] ="No article found";
        header('Location: index.php');
        return;
    }

    // Selecting Articles by searching title
    $queryArt = "SELECT Articles.id, title, Users.username, Articles.created_at
        FROM Articles JOIN Users ON Users.id = Articles.user_id
        WHERE title LIKE :search ORDER BY created_at";
    $stmtArt = $db->prepare($queryArt);
    $stmtArt-> execute(array(':search' => '%'. $search .'%'));
    $articles = $stmtArt->fetchAll(PDO::FETCH_ASSOC);
    if($articles === false){
        $_SESSION['error'] ="No article found";
        header('Location: index.php');
        return;
    }
?>
<html>
    <head>
        <title></title>
    </head>
    <body>
    <form method="get">
            <label for="search"><b>Searching:</b></label>
            <input type text="search" name="search" id="search" value="<?=$_REQUEST['search']?>">
            <input type="submit" value="Go"><br>
        </form>
        <!-- Searching users -->
        <h2>Users</h2>
        <?php
           if(count($users) === 0){
            echo"Sorry no user found like ".$_REQUEST['search'];
           }
           else if(count($users) === 1){
            echo('
                <p><a href="profile.php?user_id='.$users[0]['id'].'">'.$users[0]['username'].'</a></p>
            ');
           }
           else{
            foreach($users as $user){
                echo('
                    <p><a href="profile.php?user_id='.$user['id'].'">'.$user['username'].'</a></p>
                ');
            }
           }
        ?>
        <h2>Articles</h2>
        <?php
             if(count($articles) === 0){
                echo"Sorry no article found like ".$_REQUEST['search'];
               }
               else if(count($articles) === 1){
                echo('
                    <p>
                    <a href="view.php?article_id='.$articles[0]['id'].'">'
                    .$articles[0]['title'].' / '.$articles[0]['username'].'<br>
                    <i>'.$articles[0]['created_at'].'</i>
                    </a></p>
                ');
               }
               else{
                foreach($articles as $article){
                    echo('
                    <p>
                    <a href="view.php?article_id='.$article['id'].'">'
                    .$article['title'].' / '.$article['username'].'<br>
                    <i>'.$article['created_at'].'</i>
                    </a></p>
                ');
                }
               }
        ?>
    </body>
</html>