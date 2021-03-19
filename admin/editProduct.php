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

$new = false;

if(isset($_GET["new"])){
    $new = $_GET["new"] == "true" ? true : false;
}

//getting tool data if it's an edit

$query = "SELECT * FROM tools WHERE toolID = :toolid LIMIT 1";
$stmt = $con->prepare($query);
$stmt->bindValue(':toolid', $_GET['id']);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

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
        </div>
        <div id="nav_account"><?= $user["userName"] ?></div>
    </nav>
    <div id="wrapper">

        <form id="product_form">
            <select name="type" id="prudct_type">
                <?= $new ? "<option disabled selected hidden>Kies een type product</option>" : "" ?>
                <?php
                
                $typeStmt = $con->prepare("SELECT * FROM types ORDER BY typeName");
                $typeStmt->execute();
                $typeStmt->setFetchMode(PDO::FETCH_ASSOC);
                while($typeRow = $typeStmt->fetch()){
                ?>
                <option value="<?= $typeRow["typeID"] ?>" <?= $product["toolID"] == $typeRow["typeID"] ? "selected" : ""?>><?= $typeRow["typeName"] ?></option>
                <?php
                }
                ?>
            </select>

            <input type="text" name="manufacturer" id="product_manufacturer" placeholder="Fabriek" value="<?= $new ? "" : $product["manufacturer"] ?>">

            <input type="text" name="name" id="product_name" placeholder="Product naam" value="<?= $new ? "" : $product["Name"] ?>">

            <input type="number" name="buyPrice" id="product_buy_price" step=".01" placeholder="Inkoopprijs" value="<?= $new ? "" : $product["buyPrice"] ?>">

            <input type="number" name="sellPrice" id="product_sell_price" step=".01" placeholder="Verkoopprijs" value="<?= $new ? "" : $product["sellPrice"] ?>">

            <?= $new ? "" : "<input type='hidden' value='" . $product["toolID"] . "' id='product_id'>" ?>
            
            <button type="submit" id="submit_button"><?= $new ? "Maak nieuw product aan" : "Update product"?></button>
        </form>

    </div><!-- wrapper -->
    <footer>
        © 2021 - Tools Forever
    </footer>
    <script src="/dependencies/jquery.js"></script>
    <script src="../js/<?= $new ? "newTool" : "editTool"?>.js"></script>
</body>
</html>
