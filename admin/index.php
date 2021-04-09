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
            Deze producten moeten worden bijbesteld.
        </div>

        <?php
        
        //making the list
        $locationsQuery = "SELECT * FROM t4elocations";
        $locationStmt = $con->prepare($locationsQuery);
        $locationStmt->execute();
        $locationStmt->setFetchMode(PDO::FETCH_ASSOC);

        while($locationRow = $locationStmt->fetch()){
            $toolsQuery = "SELECT t4etools.name, t4elocationstock.minStockAmount, t4elocationstock.stockAmount FROM t4elocationstock JOIN t4etools ON t4elocationstock.toolID = t4etools.toolID WHERE t4elocationstock.locationID = :locationID AND t4elocationstock.stockAmount < t4elocationstock.minStockAmount";

            //preparing the statement and binding values
            $toolsStmt = $con->prepare($toolsQuery);
            $toolsStmt->bindvalue(":locationID", $locationRow["locationID"]);
            $toolsStmt->execute();
            $toolsStmt->setFetchMode(PDO::FETCH_ASSOC);

            //checking if data is returned before printing anything
            if($toolsStmt->rowCount() > 0){
                ?>
                <div class="location-header">
                    <?= $locationRow["name"] ?>
                </div>
                <?php
            }

            //printing the tools if existing
            while($toolRow = $toolsStmt->fetch()){
            ?>

            <div class="product-table low-stock">
                <table>
                    <tr>
                        <th>Product</th>
                        <th>Vooraad</th>
                        <th>Minimale Voorraad</th>
                    </tr>
                    <tr>
                        <td><?= $toolRow["name"] ?></td>
                        <td><?= $toolRow["stockAmount"] ?></td>
                        <td><?= $toolRow["minStockAmount"] ?></td>
                    </tr>
                </table>
            </div>

            <?php
            }
        }

        ?>

    </div><!-- wrapper -->
    <footer>
        © 2021 - Tools Forever |&nbsp;<a href="logout.php">Uitloggen</a>
    </footer>
</body>
</html>
