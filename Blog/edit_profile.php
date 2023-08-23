<?php
    session_start();
    require_once 'Config\PDO.php';
    include 'Config\util.php';

    if(empty($_SESSION['id']) && empty($_SESSION['user'])){
        die("ACCESS DENIED");
    }

    if(empty($_REQUEST['user_id'])){
        $_SESSION['error'] = "Missing user_id";
        header('Location: index.php');
        return;
    }

    if(isset($_POST['cancel'])){
        header('Location: profile.php?user_id='.$_REQUEST['user_id']);
        return;
    }

    $query = "SELECT * FROM Profiles WHERE user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->execute(array(':user_id' => $_REQUEST['user_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($row === false){
        $_SESSION['error'] = "Enter your personal Info";
        header('Location: add_profile.php?user_id='.$_REQUEST['user_id']);
        return;
    }

    if(isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['gender']) &&
    isset($_POST['birth']) && isset($_POST['about']) && isset($_POST['numCode']) &&
    isset($_POST['number']) && isset($_POST['user_id'])){
        
        $msg = validateProfile();
        if(is_string($msg)){
            $_SESSION['error'] =$msg;
            header('Location: edit_profile.php?user_id='.$_REQUEST['user_id']);
            return;
        }

        $query = "UPDATE Profiles SET
        first_name = :fname, last_name = :lname, gender = :gender, 
        birth_date = :birth, about = :about, phone_code = :code, phone_number = :number
        WHERE user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->execute(array(
            ':user_id' => $_REQUEST['user_id'],
            ':fname' => $_POST['fname'],
            ':lname' => $_POST['lname'],
            ':gender' => $_POST['gender'],
            ':birth' => $_POST['birth'],
            ':about' => $_POST['about'],
            ':code' => $_POST['numCode'],
            ':number' => $_POST['number']
        ));
        $_SESSION['success'] = "Updated";
        header('Location: profile.php?user_id='.$_REQUEST['user_id']);
        return;
    }
    
?>

<html>
    <head>
        <title></title>
    </head>
    <body>
        <h1>Editing user profile</h1>
        <!-- flash massage -->
        <?=flashMessage()?>
        <form method="post">
            <input type="hidden" name="user_id" value="<?=$_REQUEST['user_id']?>">
            <!-- Personal informations -->
                <!-- First Name -->
                <label for="fname">First Name:</label>
                <input type="text" name="fname" id="fname" value="<?=$row['first_name']?>"><br>
                <!-- Last Name -->
                <label for="lname">Last Name:</label>
                <input type="text" name="lname" id="lname" value="<?=$row['last_name']?>"><br>
                <!-- gender -->
                <label for="gender">Choose your gender:</label><br>
                <?php
                    if($row['gender'] === "Male"){
                        echo('
                        <input type="radio" name="gender" id="gender" value="Male" checked>Male
                        <input type="radio" name="gender" id="gender" value="Female" >Female
                        <input type="radio" name="gender" id="gender" value="Other" >Other
                        ');
                    }
                    else if($row['gender'] === "Female"){
                        echo('
                        <input type="radio" name="gender" id="gender" value="Male" >Male
                        <input type="radio" name="gender" id="gender" value="Female" checked>Female
                        <input type="radio" name="gender" id="gender" value="Other" >Other
                        ');
                        
                    }
                    else{
                        echo('
                        <input type="radio" name="gender" id="gender" value="Male" >Male
                        <input type="radio" name="gender" id="gender" value="Female" >Female
                        <input type="radio" name="gender" id="gender" value="Other" checked>Other
                        ');

                    }
                ?>
                <!-- birth day -->
                <br><label for="birth">Birth Date:</label>
                <input type="date" name="birth" id="birth" value="<?=$row['birth_date']?>"><br>
            <!-- about -->
            <label for="about">About:</label><br>
            <textarea name="about" id="about" cols="30" rows="10"><?=$row['about']?></textarea><br>
            <!-- Phone number -->
            <label for="number">Phone Number:</label>
            <select name="numCode" id="number">
                <?php
                    if($row['phone_code'] === "111"){
                        echo('
                        <option value="111" selected>+111</option>
                        <option value="222" >+222</option>
                        <option value="333" >+333</option>
                        ');
                    }
                    else if($row['phone_code'] === "222"){
                        echo('
                        <option value="111" >+111</option>
                        <option value="222" selected>+222</option>
                        <option value="333" >+333</option>
                        ');
                    }
                    else{
                        echo('
                        <option value="111" >+111</option>
                        <option value="222" >+222</option>
                        <option value="333" selected>+333</option>
                        ');

                    }
                ?>
            </select>
            <input type="text" name="number" id="number" value="<?=$row['phone_number']?>"><br>
            <!-- Buttons -->
            <input type="submit" value="Update">
            <input type="submit" value="Cancel" name="cancel">
        </form>
    </body>
</html>                                                                                                                                                    