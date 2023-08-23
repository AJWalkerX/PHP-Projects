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

function validateEdu(){
    for($i=1; $i<=9; $i++){
        if(!isset($_POST['edu_year'.$i])) continue;
        if(!isset($_POST['school'.$i])) continue;
        
        $year = $_POST['edu_year'.$i];
        $school = $_POST['school'.$i];

        if(strlen($year) == 0 || strlen($school) == 0){
            return "All field are required";
        }
        if(!is_numeric($year)){
            return "Position year must be numeric";
        }
        return true;
    }
}

function insertPos($pdo, $profile_id){
      $rank= 1;
      for($i=1; $i<=9; $i++){
            if(!isset($_POST['year'.$i])){continue;}
            if(!isset($_POST['desc'.$i])){continue;}
            $year = $_POST['year'.$i];
            $desc = $_POST['desc'.$i];

            $st = $pdo->prepare('INSERT INTO Position
                    (profile_id, rank, year, description)
                    VALUES (:pid, :rank, :year, :desc)');
            $st->execute(array(
                ':pid' => $profile_id,
                ':rank' => $rank,
                ':year' => $year,
                ':desc' => $desc)
            );
            $rank++;
        }
}

function insertEdu($pdo, $profile_id){
    $rank= 1;
    for($i=1; $i<=9; $i++){
          if(!isset($_POST['edu_year'.$i])){continue;}
          if(!isset($_POST['school'.$i])){continue;}
          $year = $_POST['edu_year'.$i];
          $school = $_POST['school'.$i];

          $institution_id=false;
            $stmt=$pdo->prepare(
                'SELECT institution_id FROM institution
                WHERE name = :name');
        $stmt->execute(array(':name' => $school));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row !== false){
            $institution_id = $row['institution_id'];
        }

        // If there was no institution, insert it
        if($institution_id === false){
            $stmt = $pdo->prepare(
                'INSERT INTO Institution(name)
                VALUES(:name)');
            $stmt->execute(array(':name' => $school));
            $institution_id = $pdo->lastInsertId();
        }

        $stmt = $pdo->prepare(
        'INSERT INTO education (profile_id, institution_id, rank, year)
         VALUES ( :pid, :iid, :rank, :year)');
        $stmt->execute(array(
            ':pid' => $profile_id,
            ':rank' => $rank,
            ':year' => $year,
            ':iid' => $institution_id)
        );

        $rank++;
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

function loadEdu($pdo, $profile_id){
    $stmt = $pdo->prepare
    ('SELECT year, name FROM Education 
    JOIN Institution 
        ON Education.institution_id = Institution.institution_id 
    WHERE profile_id = :prof ORDER BY rank');
    $stmt->execute(array(':prof' => $profile_id));
    $educations = $stmt ->fetchAll(PDO::FETCH_ASSOC);
    return $educations;
}