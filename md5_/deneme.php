<?php
 if(isset($_GET['MD5Hash'])){ // this is checking the input from URL
    $md5 = $_GET['MD5Hash'];

}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="">
        <input type="text" name="MD5Hash" placeholder="Exp:1234" autofocus>
        <input type="submit" value="Make hash">
        <input type="checkbox" name="a" id="v" checked>
    </form>
    <?=htmlentities($md5)?>
</body>
</html>