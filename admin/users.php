<?php

session_start();

require '../dependencies/PDOcon.php';
require '../dependencies/funcs.php';
require '../dependencies/checkLogin.php';

if(!$loggedIn){
    header("location: login.php");
}

//getting the role (and other data) of the employee

$query = "SELECT * FROM t4eusers WHERE userID = :userid AND sessionID = :sessionid";
$stmt = $con->prepare($query);
$stmt->bindValue(':userid', $_SESSION['lUserID']);
$stmt->bindValue(':sessionid', $_SESSION['lUserToken']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if(!($user["role"] >= $manager)){
    header("location: /admin");
}

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
            <div class='menu-item'><a href='products.php'>producten</a></div>
            <div class='menu-item'><a href='locations.php'>locaties</a></div>
            <?= $user["role"] >= $manager ? "<div class='menu-item'><a href='#'>gebruikers</a></div>" : "" ?>
        </div>
        <div id="nav_account"><?= $user["userName"] ?></div>
    </nav>
    <div id="wrapper">

        <div class="location-header">
            <a href="newUser.php" type="submit" name="submit" class="button">Nieuwe gebruiker</a>
        </div>

        <?php
        
        //making the list
        $query = "SELECT * FROM t4eusers";
        $stmt = $con->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        while($row = $stmt->fetch()){

        ?>

            <div class="user-list-box">
                <div class="user-list-name"><?= $roles[$row["role"]] ?> - <?= $row["userName"] ?></div>
                <div class="user-list-buttons">
                    <a href="#" onclick="removeUser(<?= $row["userID"] ?>)">Hef dit account op</a>
                </div>
            </div>

        <?php

        }

        ?>

    </div><!-- wrapper -->
    <footer>
        © 2021 - Tools Forever |&nbsp;<a href="logout.php">Uitloggen</a>
    </footer>
    <script src="/dependencies/jquery.js"></script>
    <script src="../js/editUser.js"></script>
</body>
</html>
