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
    <title>Leveringen | Tools Forever</title>
</head>
<body>
    <nav>
        <div id="nav_menu">
            <div id="nav_logo"><a href="/">Tools Forever</a></div>
            <?= $user["role"] >= $manager ? "<div class='menu-item'><a href='#'>Levering registreren</a></div>" : "" ?>
            <div class='menu-item'><a href='bestellingen.php'>Bestelling registreren</a></div>
            <div class='menu-item'><a href='products.php'>producten</a></div>
            <div class='menu-item'><a href='locations.php'>locaties</a></div>
        </div>
        <div id="nav_account"><?= $user["userName"] ?></div>
    </nav>
    <div id="wrapper">
        <div id="delivery_order_controls">
            <select name="store_location" id="delivery_order_location_box">
                <option disabled selected hidden>Kies een locatie</option>
                <?php
                
                $locStmt = $con->prepare("SELECT * FROM locations ORDER BY name");
                $locStmt->execute();
                $locStmt->setFetchMode(PDO::FETCH_ASSOC);

                while($locationRow = $locStmt->fetch()){
                ?>
                <option value="<?= $locationRow["locationID"] ?>"><?= $locationRow["name"] ?></option>
                <?php
                }

                ?>
            </select>

            <div class="location-header">Kies de producten die zijn geleverd</div>
            
            <select name="store_location" id="delivery_order_product_box">
                <option disabled selected hidden>Kies een product</option>
            </select>

            <button id="submit_button">registreer levering</button>
        </div>
        
        <div id="tool_delivery_order_wrapper"></div>
        
    </div><!-- wrapper -->
    <footer>
        © 2021 - Tools Forever
    </footer>
    <script src="/dependencies/jquery.js"></script>
    <script src="/js/delivery.js"></script>
</body>
</html>
