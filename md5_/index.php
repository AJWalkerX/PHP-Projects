<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MD5 Cracker</title>
</head>
<body>
    <!-- This is the Cracker -->
    <h1> MD5 Cracker</h1>
    <!--Introduction for users -->
    <p>
        This application takes an MD5
        hash of a four digit pin and
        check all 10,000
        possible four digit PINs to determine the PIN.    </p>
    </p>
    
    <!-- We are cooking the Cracker down here -->
    <pre>
    <?php 
    echo "Debug Output: \n";
    echo "\n";
    $output = "Not found";
    $numbers = array(0,1,2,3,4,5,6,7,8,9);
    // $numbers ="0123456789"; 
    $show = 15;
    if(isset($_GET['MD5hashedCode'])){
        $md5Hash = $_GET['MD5hashedCode'];

        foreach($numbers as  $key){
            $ch = $numbers[$key];
            foreach($numbers as  $key1){
                $ch1 = $numbers[$key1];
                foreach($numbers as  $key2){
                    $ch2 = $numbers[$key2];
                    foreach($numbers as  $key3){
                        $ch3 = $numbers[$key3];
                        
                        $pinTry = $ch.$ch1.$ch2.$ch3;
                        $checkHash = hash('md5', $pinTry);
                        
                        if($checkHash == $md5Hash){
                            $output = $pinTry;
                            break;
                        }
                        
                        if($show > 0){
                            echo "$checkHash = $pinTry \n";
                            $show = $show - 1;
                        }
                    }
                }
            }
        }
    }
    ?></pre>
    
    <p>
        PIN: <?=htmlentities($output);?>
    </p>
    <!-- Getting input from users -->
    <form>
        <input type="text" name="MD5hashedCode" autofocus>
        <input type="submit" name="" value="Lest Cook">
    </form>

    <!-- listed links -->
    <ul>
        <li><a href="index.php">Reset</a></li>
        <li>If you don't have MD5Hash press <a href="md5.php">here</a></li>
    </ul>
</body>
</html>