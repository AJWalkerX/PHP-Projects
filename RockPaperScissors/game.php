<?php
if(!isset($_GET['name']) || strlen($_GET['name']) < 1){
    die("Name parameter missing");
}
if(isset($_POST['logout'])){
    header('Location: index.php');
    return;
}

$names = array('Rock', 'Paper', 'Scissors');
$human = isset($_POST["human"]) ? $_POST['human']+0 : -1;

$computer = rand(0,2);

function check($computer, $human){
    if($human  == $computer){
        return "Tie";
    }
    elseif( ($human == 0 && $computer == 1) ||
            ($human == 1 && $computer == 2) ||
            ($human == 2 && $computer == 0)){
            
                return "You Lose";
    }
    elseif( ($human == 1 && $computer == 0) ||
            ($human == 2 && $computer == 1) ||
            ($human == 0 && $computer == 2)){

                return "You Win";
    }
}

$results = check($computer, $human);
?>
<html>
<head>
    <title>alexander walker</title>
</head>
<body>
    <h1>Rock Paper Scissors</h1>
    <?php
        if(isset($_REQUEST['name'])){
            echo('<p>Welcome: '. htmlentities($_REQUEST['name']). '</p>'."\n");
        }
    ?>
    <form action="" method="POST">
        <select name="human" id="">
            <option value="-1">Select</option>
            <option value="0">Rock</option>
            <option value="1">Paper</option>
            <option value="2">Scissors</option>
            <option value="3">Test</option>
        </select>
        <input type="submit" value="Play">
        <input type="submit" value="Logout" name="logout">

    </form>
<pre>
    <?php
    if($human == -1){
        print "Please select a strategy and press Play.\n";
    }
    elseif($human == 3){
        for($c=0;$c<3;$c++) {
            for($h=0;$h<3;$h++) {
                $r = check($c, $h);
                print "Human=$names[$h] Computer=$names[$c] Result=$r\n";
            }
   }
        // foreach ($names as $i => $x) {
        //     foreach($names as $b => $y){
        //         $testing = check($b,$i);
        //         print "Human=$names[$i] Computer=$names[$b] Results=$testing\n";
        //     }
        // }
    }
    else{
        print "Your Play=$names[$human] Computer Play=$names[$computer] Result=$results\n";
    }
    ?>
</pre>
</body>
</html>