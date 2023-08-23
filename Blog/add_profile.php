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

    if(isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['gender']) &&
    isset($_POST['birth']) && isset($_POST['about']) && isset($_POST['numCode']) &&
    isset($_POST['number']) && isset($_POST['user_id'])){
        
        $msg = validateProfile();
        if(is_string($msg)){
            $_SESSION['error'] = $msg;
            header('Location: add_profile.php?user_id='.$_REQUEST['user_id']);
            return;
        }

        $query = "INSERT INTO Profiles
         (user_id, first_name, last_name, gender, birth_date, about, phone_code,
         phone_number)
        VALUES(:id, :fname, :lname, :gender, :birth, :about, :code, :number)";
        $stmt = $db->prepare($query);
        $stmt->execute(array(
            ':id' => $_REQUEST['user_id'],
            ':fname' => $_POST['fname'],
            ':lname' => $_POST['lname'],
            ':gender' => $_POST['gender'],
            ':birth' => $_POST['birth'],
            ':about' => $_POST['about'],
            ':code' => $_POST['numCode'],
            ':number' => $_POST['number']
        ));
        $_SESSION['success'] = "Added";
        header('Location: profile.php?user_id='.$_REQUEST['user_id']);
        return;
    }
?>

<html>
    <head>
        <title></title>
    </head>
    <body>
        <h1>Adding new profile</h1>
        <!-- flash massage -->
        <?php
            flashMessage();
        ?>
        <form method="post">
            <input type="hidden" name="user_id" value="<?=$_REQUEST['user_id']?>">
            <!-- Personal informations -->
                <!-- First Name -->
                <label for="fname">First Name:</label>
                <input type="text" name="fname" id="fname"><br>
                <!-- Last Name -->
                <label for="lname">Last Name:</label>
                <input type="text" name="lname" id="lname"><br>
                <!-- gender -->
                <label for="gender">Choose your gender:</label><br>
                <input type="radio" name="gender" id="gender" value="NULL" checked hidden>
                <input type="radio" name="gender" id="gender" value="Male">Male
                <input type="radio" name="gender" id="gender" value="Female">Female
                <input type="radio" name="gender" id="gender" value="Other">Other <br>
                <!-- birth day -->
                <label for="birth">Birth Date:</label>
                <input type="date" name="birth" id="birth"><br>
            <!-- about -->
            <label for="about">About:</label><br>
            <textarea name="about" id="about" cols="30" rows="10"></textarea><br>
            <!-- Phone number -->
            <label for="number">Phone Number:</label>
            <select name="numCode" id="number">
                <option value="NULL" selected>Select code</option>
                <option value="111">+111</option>
                <option value="222">+222</option>
                <option value="333">+333</option>
            </select>
            <input type="text" name="number" id="number"><br>
            <!-- Buttons -->
            <input type="submit" value="Add">
            <input type="submit" value="Cancel" name="cancel">
        </form>
    </body>
</html>