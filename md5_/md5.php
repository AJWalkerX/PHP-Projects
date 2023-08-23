<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MD5 Hash Maker</title>
</head>
<body>
    <!-- This is the introduction for the user -->
    <h1>MD5 Hash Maker</h1>
    <p>please add 4 digit number to generete a MD5 hash.</p>
    
    <!-- The php code will generete here -->
        <?php 
            if(isset($_GET['MD5Hash'])){ // this is checking the input from URL
                $md5 = $_GET['MD5Hash'];
                
                if(strlen($md5) != 4){// If the input longer than 4 digit or never entered this statement will run.
                    echo "Input must be exactly 4 digit number!";
                }
                // Checking the input is a number or not.
                else if($md5[0] < 0 || $md5[0] > 9 ||
                        $md5[1] < 0 || $md5[1] > 9 ||
                        $md5[2] < 0 || $md5[2] > 9 ||
                        $md5[3] < 0 || $md5[3] > 9){
                            
                    echo" Input must be a number";
                }
                // If all statements is good to go the hash code will generete succesfully.
                else{
                    $hash = hash('md5', $md5);
                    echo "MD5 Hash : $hash";
                }
            }
        ?>
    <!-- This is the from for the user input that 
    the user wanted to generete 4 digit long number -->
    <form action="">
        <input type="text" name="MD5Hash" placeholder="Exp:1234" autofocus>
        <input type="submit" value="Make hash">
    </form>
    
    <!-- Listed links -->
    <ul>
        <li> <a href="md5.php">Reset</a></li>
        <li><a href="index.php">MD5 code Cracker</a></li>
    </ul>
</body>
</html>