<?php

session_start();

require 'dependencies/PDOcon.php';
require 'dependencies/funcs.php';

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <title>Tools Forever</title>
</head>
<body>
    <nav>
        <div id="nav_menu">
            <div id="nav_logo">Tools Forever</div>
            <div class="menu-item"><a href="/admin">Beheer</a></div>
        </div>
        <div id="nav_account">Rick B.</div>
    </nav>
    <div id="wrapper">
        <div id="search_wrapper">
            <form>
                <select name="store_location" id="location_box">
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
                <select name="product_type" id="type_box">
                    <option disabled selected hidden>Kies een type product</option>
                    <?php
                    
                    $typeStmt = $con->prepare("SELECT * FROM types ORDER BY typeName");
                    $typeStmt->execute();
                    $typeStmt->setFetchMode(PDO::FETCH_ASSOC);

                    while($typeRow = $typeStmt->fetch()){
                    ?>
                    <option value="<?= $typeRow["typeID"] ?>"><?= $typeRow["typeName"] ?></option>
                    <?php
                    }

                    ?>
                </select>
                <button type="submit" id="submit_button">zoeken</button>
            </form>
        </div><!-- search_box -->

        <?php
        
        //making the list
        $locationsQuery = "SELECT * FROM locations";
        $locationsQuery .= isset($_GET["store_location"]) ? " WHERE locationID = :locationID" : "";

        //preparing the statement and binding values
        $locationStmt = $con->prepare($locationsQuery);
        if(isset($_GET["store_location"])){
            $locationStmt->bindvalue(":locationID", $_GET["store_location"]);
        }

        //executing
        $locationStmt->execute();
        $locationStmt->setFetchMode(PDO::FETCH_ASSOC);

        while($locationRow = $locationStmt->fetch()){
            $toolsQuery = "SELECT tools.name, tools.manufacturer, tools.sellprice, locationstock.stockAmount FROM locationstock JOIN tools ON locationstock.toolID = tools.toolID WHERE locationstock.locationID = :locationID";
            $toolsQuery .= isset($_GET["product_type"]) ? " AND tools.typeID = :typeID" : "";

            //preparing the statement and binding values
            $toolsStmt = $con->prepare($toolsQuery);
            $toolsStmt->bindvalue(":locationID", $locationRow["locationID"]);

            if(isset($_GET["product_type"])){
                $toolsStmt->bindvalue(":typeID", $_GET["product_type"]);
            }

            //executing
            $toolsStmt->execute();
            $toolsStmt->setFetchMode(PDO::FETCH_ASSOC);

            //checking if data is returned before printing anything
            if($toolsStmt->rowCount() > 0){
                ?>
                <div class="location-header">
                    <?= $locationRow["name"] ?>
                </div>
                <?php
            }else if(isset($_GET["store_location"])){
                ?>
                <div class="location-header">
                    We hebben helaas niks gevonden voor uw zoek criteria.
                </div>
                <?php
            }

            //printing the tools if existing
            while($toolRow = $toolsStmt->fetch()){
            ?>

            <div class="product-table">
                <table>
                    <tr>
                        <th>Product</th>
                        <th>Fabriek</th>
                        <th>Vooraad</th>
                        <th>Verkoopprijs</th>
                    </tr>
                    <tr>
                        <td><?= $toolRow["name"] ?></td>
                        <td><?= $toolRow["manufacturer"] ?></td>
                        <td><?= $toolRow["stockAmount"] ?></td>
                        <td>&euro; <?= $toolRow["sellprice"] ?></td>
                    </tr>
                </table>
            </div>

            <?php
            }
        }

        ?>
        
    </div><!-- wrapper -->
    <footer>
        © 2021 - Tools Forever
    </footer>
</body>
</html>