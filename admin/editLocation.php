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

//getting location data
$query = "SELECT * FROM t4elocations WHERE locationID = :locationid";
$stmt = $con->prepare($query);
$stmt->bindValue(':locationid', $_GET['id']);
$stmt->execute();
$location = $stmt->fetch(PDO::FETCH_ASSOC);

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
            <?= $user["role"] >= $manager ? "<div class='menu-item'><a href='users.php'>gebruikers</a></div>" : "" ?>
        </div>
        <div id="nav_account"><?= $user["userName"] ?></div>
    </nav>
    <div id="wrapper">

        <div class="location-header">
            Producten op locatie <?= $location["name"] ?>
        </div>

        <form id="location_form">
            <h1>Voeg product toe</h1>
            <select name="type" id="prudct_name">

            <option disabled selected hidden>Kies product</option>

            <?php
                
            $stmt = $con->prepare("SELECT * FROM t4etools WHERE toolID NOT IN (SELECT toolID FROM t4elocationStock WHERE locationID = :locationid)");
            $stmt->bindValue(':locationid', $location["locationID"]);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while($row = $stmt->fetch()){
            ?>

            <option value="<?= $row["toolID"] ?>"><?= $row["Name"] ?></option>

            <?php
            }
            ?>

            </select>

            <input type="number" name="amount" id="product_amount" placeholder="voorraad">

            <input type="number" name="amount" id="min_product_amount" placeholder="minimale voorraad">

            <input type="hidden" name="locationID" id="location_id" value="<?= $location["locationID"] ?>">
            
            <button type="submit" id="submit_button">Voeg product toe</button>
        </form>

        <?php
        
        //making the list
        $query = "SELECT t4elocationstock.toolID, t4etools.Name FROM t4elocationstock JOIN t4etools on t4elocationstock.toolID = t4etools.toolID WHERE t4elocationstock.locationID = :locationid";
        $stmt = $con->prepare($query);
        $stmt->bindValue(':locationid', $location["locationID"]);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        while($row = $stmt->fetch()){

        ?>

            <div class="tool-list-box">
                <div class="tool-list-name"><?= $row["Name"] ?></div>
                <div class="tool-list-buttons">
                    <a href="#" onclick="removeFromLocation(<?= $location["locationID"] ?>, <?= $row["toolID"] ?>)">Verwijder product uit deze winkel</a>
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
    <script src="../js/editStore.js"></script>
</body>
</html>
