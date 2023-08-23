<?php
// flash message
function flashMessage(){
    if(isset($_SESSION['success'])){
        echo('<p style="color:green">'. $_SESSION['success']. '</p>');
        unset($_SESSION['success']);
    }
    if(isset($_SESSION['error'])){
        echo ('<p style="color:red">'.$_SESSION['error']."</p>\n");
        unset($_SESSION['error']);
    }
    if(isset($_SESSION['warning'])){
        echo ('<p style="color:orange">'.$_SESSION['warning']."</p>\n");
        unset($_SESSION['warning']);
    }
}

// Validate Profile
function validateProfile(){
    if(strlen($_POST['fname']) == 0  || strlen($_POST['lname']) == 0 ||
        empty($_POST['birth']) || strlen($_POST['about']) == 0  || 
        strlen($_POST['number']) == 0){
        return  "All field required";
    }
    if(is_numeric($_POST['fname'])){
        return "First Name must be stiring variable";
    }
    if(is_numeric($_POST['lname'])){
        return "Last Name must be string variable";
    }
    if(!is_numeric($_POST['number'])){
        return "Phone number must be numeric variable";
    }
    return true;
}
function searchBar(){
    echo('
        <form action="search.php" method="get">
            <input type="text" name="search" cols="80" placeholder="search">
            <input type="submit" value="Go">
        </form>
    ');
}

function validatePassword(){
    if($_POST['pass'] !== $_POST['passConform']){
        return "The password provided does not match.";
    }
    if(strlen($_POST['pass']) < 8){
        return "The password must consist of at least 8 characters.
         Please choose a password that is longer than 8 characters";
    }
    preg_match('/[A-Z]+/',$_POST['pass'], $matches);
    if(count($matches) === 0){
        return "password must have at least one Capital letter";
    }
    preg_match('/[a-z]+/',$_POST['pass'], $matches);
    if(count($matches) === 0){
        return "password must have at least one small letter";
    }
    preg_match('/[0-9]+/',$_POST['pass'], $matches);
    if(count($matches) === 0){
        return "password must have at least one number";
    }
    preg_match('/[!^@+%&=?*-_()]+/',$_POST['pass'], $matches);
    if(count($matches) === 0){
        return "password must have at least one special character like !@^+%&=?*-_()";
    }
    return true;
}
function passwordHashing(){
    $passHash = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    return $passHash;
}
function passwordVerify($userPass){
    $passHash = passwordHashing();
    $passVerify = password_verify($passHash,$userPass);
    return $passVerify;
}
function validateUsername(){
    if(strlen($_POST['user']) < 5){
        return "User name must consist of at least 5 characters.";
    }
    if(preg_match('/[!^@+%&=?*-_()]+/',$_POST['user'])){
        return "No special character allowed for User name";
    }
    return true;
}
?>