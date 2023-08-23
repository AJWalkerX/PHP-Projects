<?php
    // SQL serverdan içerik bilgilerini almak için buraya kod yazıcam
    require_once "pdo.php";
?>

<html lang="en">
<head>
    <title>Home</title>
</head>
<body>
    <h1>Welcome to my Survey</h1>
    <!-- Kulanıcı giriş yapmadığında çıkan görüntü -->
    <?php
        if(!isset($_SESSION['user']) && !isset($_SESSION['user_id'])){
            echo('<p>This survey is about food product. <a href="login.php">Start Survey...</a></p><br>');
            echo('<p>Try go to <a href="survey.php">survey</a> withot loged in.</p><br>');
            
            // table: user name and comment of the survey

        }
        else{
            // kullanıcı giriş yaptıktan sonra kullanıcının görecekleri.
            // table: user name and comment of the survey
            // edit, view and delete buttons/links.
        }

    ?>
    <!-- Eğer tablo boş ise tablo gözğkmeyecek -->
    <table border = 1>
        <tr>
            <th>
                <!-- başlıklar -->
            </th>
            </tr>
            <!-- foreach döngü -->
            <tr>
                <td>
                    <!-- içerik 1 -->
                </td>
                <td>
                    <!-- içerik 2 -->
                </td>
                <td>
                    <!-- içerik 3 -->
                </td>
            </tr>
            <!-- foreach döngü bitişi -->
    </table>

</body>
</html>