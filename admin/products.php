<?php

session_start();

require '../dependencies/PDOcon.php';
require '../dependencies/funcs.php';
require '../dependencies/checkLogin.php';

if(!$loggedIn){
    header("location: login.php");
}

//getting the role (and other data) of the employee

$query = "SELECT * FROM users WHERE userID = :userid AND sessionID = :sessionid";
$stmt = $con->prepare($query);
$stmt->bindValue(':userid', $_SESSION['lUserID']);
$stmt->bindValue(':sessionid', $_SESSION['lUserToken']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/css/index.css">
    <title>Beheer | Tools Forever</title>
</head>
<body>
    <nav>
        <div id="nav_menu">
            <div id="nav_logo"><a href="/">Tools Forever</a></div>
            <?= $user["role"] >= $manager ? "<div class='menu-item'><a href='leveringen.php'>Levering registreren</a></div>" : "" ?>
            <div class='menu-item'><a href='bestellingen.php'>Bestelling registreren</a></div>
            <div class='menu-item'><a href='#'>producten</a></div>
        </div>
        <div id="nav_account"><?= $user["userName"] ?></div>
    </nav>
    <div id="wrapper">

        <?php
        
        //making the list
        $query = "SELECT tools.toolID, tools.Name, tools.manufacturer, types.typeName FROM tools join types on tools.typeID = types.typeID";
        $stmt = $con->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        while($row = $stmt->fetch()){
            print_r($row);
            echo "<br><br>";
        }

        ?>

    </div><!-- wrapper -->
    <footer>
        © 2021 - Tools Forever
    </footer>
</body>
</html>
