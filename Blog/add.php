<?php
    session_start();
    require_once 'Config\PDO.php';
    include 'Config\util.php';

    if(!isset($_SESSION['id']) && !isset($_POST['user'])){
        die('ACCESS DENIED');
    }

    if(isset($_POST['cancel'])){
        header('location: index.php');
        return;
    }

    if(isset($_POST['title']) && isset($_POST['content'])){
        
        // variables
        $user_id = $_SESSION['id'];
        $title = $_POST['title'];
        $content=$_POST['content'];

        if(strlen($title)<1 ||strlen($content)<1){
            $_SESSION['error'] = "All field must required";
            header('Location: add.php');
            return;
        }
        else{
            $query = "INSERT INTO Articles (user_id, title, content)
                VALUES (:user_id, :title, :content)";
            $stmt = $db->prepare($query);
            $stmt->execute(array(
                ':user_id' => $user_id,
                ':title' => $title,
                'content' => $content
            ));
            $_SESSION['success'] = "Added";
            header('Location: index.php');
            return;
        }
    }
?>

<html>
<head>
    <title>Adding</title>
</head>
<body>
    <?=flashMessage()?>
    <form method="POST">
        <!-- title -->
        <label for="title">Title: </label>
        <input type="text" name="title" id="title"> <br>
        <!-- Content -->
        <label for="content">Content</label><br>
        <textarea name="content" id="content" cols="80" rows="10"></textarea><br>
        <!-- Buttons -->
        <input type="submit" value="Add">
        <input type="submit" value="Cancel" name="cancel">
    </form>
</body>
</html>