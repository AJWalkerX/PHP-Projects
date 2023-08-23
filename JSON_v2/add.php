<?php
    session_start();
    require_once 'pdo.php';
    require_once "util.php";

    if (!isset($_SESSION["name"]) && !isset($_SESSION['user_id'])){
        die('ACCESS DENIED');
    }
    if(isset($_POST['cancel'])){
        header('Location: index.php');
        return;
    }
    if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) 
        && isset($_POST['headline']) && isset($_POST['summary']) ){

        $msg = validateProfile();
        if(is_string($msg)){
            $_SESSION['error'] = $msg;
            header("Location: add.php");
            return;
        }

        $msg= validateEdu();
        if(is_string($msg)){
            $_SESSION['error'] = $msg;
            header("Location: add.php");
            return;
        }
        
        $msg= validatePos();
        if(is_string($msg)){
            $_SESSION['error'] = $msg;
            header("Location: add.php");
            return;
        }
        
        

        $stmt = $pdo->prepare('INSERT INTO Profile
        (user_id, first_name, last_name, email, headline, summary)
        VALUES ( :uid, :fn, :ln, :em, :he, :su)');
      
      $stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'])
      );
      
      $profile_id = $pdo->lastInsertId();

      //   Education
        insertEdu($pdo, $profile_id);
      //   Positions
        insertPos($pdo, $profile_id);

      $_SESSION['success'] = "added";
      header("Location: index.php");
      return;           
    }
?>

<html lang="en">
<head>
<?= require_once "head.php";?>
<title>alexander walker</title>
</head>
<body>
    <?=flashMessage();?>
    <form method="POST">
        <!-- First Name -->
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name"><br>
        <!-- Last Name -->
        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name"><br>
        <!-- E-mail -->
        <label for="email">Email</label>
        <input type="text" id="email" name="email"><br>
        <!-- Headline -->
        <label for="headline">Headline</label>
        <input type="text" name="headline" id="headline"><br>
        <!-- Summary -->
        <label for="summary">Summary</label><br>
        <textarea name="summary" id="summary" cols="30" rows="10"></textarea><br><br>
        
        <!-- Education -->
        Education: <input type="submit" value="+" id="addEdu">
        <div id="education_fields"></div>        
        <!-- Position -->
        Positions: <input type="submit" value="+" id="addPos">
        <div id="position_fields"></div>
        
        <!-- Buttons -->
        <input type="submit" value="Add">
        <input type="submit" value="Cancel" name="cancel">
    </form>
    <script>
        countPos = 0;
        countEdu = 0;

        $(document).ready(function(){
            window.console && console.log('Document ready called');
            // Education
            $('#addEdu').click(function(event){
                event.preventDefault();
                if(countPos >= 9){
                    alert("Maximum of nine education entries exceeded");
                    return;
                }
                countEdu++;
                window.console && console.log("Adding Education"+countEdu);
                $('#education_fields').append(
                    '<div id="education'+countEdu+'"> \
                    <p>Year: <input type="text" name="edu_year'+countEdu+'" value=""/> \
                    <input type="button" value="-" \
                        onclick="$(\'#education'+countEdu+'\').remove(); false;">\
                    <br>School: <input type="text" name="school'+countEdu+'" value="" size="80" class="school"/>\
                    </p></div>'
                );
                $('.school').autocomplete({ source: "school.php" });
            });
            $('.school').autocomplete({ source: "school.php" });
            
            // Position
            $('#addPos').click(function(event){
                event.preventDefault();
                if(countPos >= 9){
                    alert("Maximum of nine position entries exceeded");
                    return;
                }
                countPos++;
                window.console && console.log("Adding position"+countPos);
                $('#position_fields').append(
                    '<div id="position'+countPos+'"> \
                    <p>Year: <input type="text" name="year'+countPos+'" value=""/> \
                    <input type="button" value="-" \
                        onclick="$(\'#position'+countPos+'\').remove(); false;"></p>\
                    <textarea name = "desc'+countPos+'" rows="8" cols="80"></textarea>\
                    </div>'
                );
            });
        });
    </script>
</body>
</html>