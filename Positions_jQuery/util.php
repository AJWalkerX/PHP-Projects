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
}
function validateProfile(){
    if(strlen($_POST['first_name']) == 0 || strlen($_POST['last_name']) == 0 
        || strlen($_POST['email']) == 0 || strlen($_POST['headline'] ) == 0 
        || strlen($_POST['summary']) == 0){
            
            return"All fields are required";
        }
    
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        return "Email address must contain @";
    }
    return true;
}
function validatePos(){
    for($i=1; $i<=9; $i++){
        if(!isset($_POST['year'.$i])) continue;
        if(!isset($_POST['desc'.$i])) continue;
        
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];

        if(strlen($year) == 0 || strlen($desc) == 0){
            return "All field are required";
        }
        if(!is_numeric($year)){
            return "Position year must be numeric";
        }
        return true;
    }
}

function loadPos($pdo, $profile_id){
    $stmt = $pdo->prepare
    ('SELECT * FROM Position WHERE profile_id = :prof ORDER BY rank');
    $stmt->execute(array(':prof' => $profile_id));
    $positions = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $positions[] = $row;
    }
    return $positions;
}