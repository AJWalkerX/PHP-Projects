<?php
require_once "pdo.php";
require_once "util.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    die("ACCESS DENIED");
}

if (isset($_POST['cancel'])) {
    header('Location: index.php');
    return;
}

if (!isset($_REQUEST['profile_id'])) {
    $_SESSION['error'] = "Missing profile_id";
    header('Location: index.php');
    return;
}

$stmt = $pdo->prepare('SELECT * FROM Profile
        WHERE profile_id = :prof AND user_id = :uid');
$stmt->execute(array(
    ':prof' => $_REQUEST['profile_id'],
    ':uid' => $_SESSION['user_id']
));
$profile = $stmt->fetch(PDO::FETCH_ASSOC);
if ($profile === false) {
    $_SESSION['error'] = "Could not load profile";
    header('Location: index.php');
    return;
}

if (isset($_POST['first_name']) && isset($_POST['last_name'])
    && isset($_POST['email']) && isset($_POST['headline'])
    && isset($_POST['summary'])) {
    // Data validation
    $msg = validateProfile();
    if (is_string($msg)) {
        $_SESSION['error'] = $msg;
        header('Location: edit.php?profile_id=' . $_REQUEST["profile_id"]);
        return;
    }
    $msg = validatePos();
    if (is_string($msg)) {
        $_SESSION['error'] = $msg;
        header('Location: edit.php?profile_id=' . $_REQUEST["profile_id"]);
        return;
    }

    $sql = "UPDATE profile 
        SET first_name = :fn, last_name = :ln, 
            email = :em, headline = :he , summary = :su
        WHERE profile_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':id' => $_REQUEST['profile_id'],
        ':su' => $_POST['summary'])
    );
    $stmt = $pdo->prepare('DELETE FROM Position
             WHERE profile_id = :pid');
    $stmt->execute(array(
        ':pid' => $_REQUEST['profile_id']
    ));

    $rank = 1;
    for ($i = 1; $i <= 9; $i++) {
        if (!isset($_POST['year' . $i])) continue;
        if (!isset($_POST['desc' . $i])) continue;

        $year = $_POST['year' . $i];
        $desc = $_POST['desc' . $i];
        $stmt = $pdo->prepare('INSERT INTO Position
        (profile_id, rank, year, description)
        VALUES ( :pid, :rank, :year, :desc)');

        $stmt->execute(array(
            ':pid' => $_REQUEST['profile_id'],
            ':rank' => $rank,
            ':year' => $year,
            ':desc' => $desc)
        );

        $rank++;
    }

    $_SESSION['success'] = 'Record updated';
    header('Location: index.php');
    return;
}

$position = loadPos($pdo, $_REQUEST['profile_id']);

echo ('<h1>Editing Profiles</h1>');
// Flash pattern
flashMessage();
?>
<html>

<head>
    <title>alexander walker</title>
    <?php require_once "head.php"; ?>
</head>

<body>
    <form method="post" action="edit.php">
        <input type="hidden" name="profile_id" value="<?= htmlentities($_REQUEST['profile_id']); ?>" />
        <p>first_name:
            <input type="text" name="first_name" value="<?= htmlentities($profile['first_name']); ?>"></p>
        <p>last_name:
            <input type="text" name="last_name" value="<?= htmlentities($profile['last_name']); ?>"></p>
        <p>email:
            <input type="text" name="email" value="<?= htmlentities($profile['email']); ?>"></p>
        <p>headline:
            <input type="text" name="headline" value="<?= htmlentities($profile['headline']); ?>"></p>
        <p>Summary: <br>
            <textarea name="summary" cols="30" rows="10"><?= htmlentities($profile['summary']); ?></textarea>
            <!-- <div id="position_fields"></div> -->
            <?php
            $pos = 0;
            echo ('<p>Position: <input type="submit" id="addPos" value="+">' . "\n");
            echo ('<div id="position_fields">' . "\n");
            foreach ($position as $position) {
                $pos++;
                echo ('<div id="position' . $pos . '">' . "\n");
                echo ('<p>Year: <input type="text" name="year' . $pos . '" value="' . $position['year'] . '"/>' . "\n");
                echo ('<input type="button" value="-"');
                echo ('onclick="$(\'#position' . $pos . '\').remove(); return false;">' . "\n");
                echo ("</p>\n");
                echo ('<textarea name="desc' . $pos . '" rows="8" cols="80">' . "\n");
                echo (htmlentities($position['description']) . "\n");
                echo ("\n</textarea>\n</div>\n");
            }
            echo ("</div></p>\n");
            ?>
            
            </p>
            <input type="hidden" name="profile_id" value="<?= $_REQUEST['profile_id'] ?>">
            <p><input type="submit" value="Save" />
                <input type="submit" value="Cancel" name="cancel" /></p>
    </form>
    <!-- <script src="js/jquery-1.10.2.js"></script>
    <script src="js/jquery-ui-1.11.4.js"></script> -->
    <script>
        countPos = <?= $pos ?>;

        $(document).ready(function() {
            window.console && console.log('Document ready called');
            $('#addPos').click(function(event) {
                event.preventDefault();
                if (countPos >= 9) {
                    alert("Maximum of nine position entries exceeded");
                    return;
                }
                countPos++;
                window.console && console.log("Adding position" + countPos);
                $('#position_fields').append(
                    '<div id= "position' + countPos + '">\
                <p>Year: <input type="text" name="year' + countPos + '" value=""/>\
                <input type="button" value="-"\
                    onclick="$(\'#position' + countPos + '\').remove(); return false;"></p>\
                <textarea name="desc' + countPos + '" rows="8" cols="80"></textarea>\
                </div>'
                );
            });
        });
    </script>
</body>

</html>

