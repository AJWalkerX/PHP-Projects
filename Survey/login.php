<!-- buraya login sistemi gelicek fakat ilk önce kayıt olmadıysa kayıt sistemine yönlendirme olucak sonra da bilgiler sql severda
kayıt edilecek sonra kayıt olunduktan sonra buraya geri yönlendirme yapılıp kullanıcının giriş yapılması sağlanıcak -->


<!-- $salt = 'XyZzy12*_'; -->
<?php

?>

<html lang="en">
<head>
    <title>login</title>
</head>
<body>
    <form method="POST">
        <!-- email -->
        <label for="email">Email:</label>
        <input type="text" id="email" name="email"><br>
        <!-- password -->
        <label for="pass">Password:</label>
        <input type="text" id="pass" name="pass">
        <!-- buttons -->

        <p>If you are not Signed up already. <a href="signup.php">Please sign up.</a></p>
    </form>
</body>
</html>