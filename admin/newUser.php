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

$error = "";

if(isset($_POST["submit"])){
    if($_POST["email"] != "" && $_POST["password"] != "" && $_POST["name"] != ""){

        //checking for duplicate email
        $query = "SELECT * FROM t4eusers WHERE userEmail = :mail LIMIT 1";
        $stmt = $con->prepare($query);
        $stmt->bindValue(':mail', $_POST["email"]);
        $stmt->execute();
        $emailCheck = $stmt->fetch(PDO::FETCH_ASSOC);
        if($emailCheck === false){
            $passwordHashed = EncryptPass($_POST["password"], $_POST["email"]);
            $sessionID = getToken(64);
        
            $query = "INSERT INTO t4eusers (userEmail, userName, password, role, sessionID) VALUES (:email, :name, :password, :role, :sessionid)";
            $stmt = $con->prepare($query);
            $stmt->bindvalue(":email", $_POST["email"]);
            $stmt->bindvalue(":name", $_POST["name"]);
            $stmt->bindvalue(":password", $passwordHashed);
            $stmt->bindvalue(":role", $_POST["role"]);
            $stmt->bindvalue(":sessionid", $sessionID);
    
            $stmt->execute();
    
            header("location:  /admin/users.php");
        }else{
            $error = "Deze email is al in gebruik.";
        }
    }else{
        $error = "Vul alle velden in.";
    }
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
            <?= $user["role"] >= $manager ? "<div class='menu-item'><a href='users.php'>gebruikers</a></div>" : "" ?>
        </div>
        <div id="nav_account"><?= $user["userName"] ?></div>
    </nav>
    <div id="wrapper">

        <form id="user_form" method="post">
            <?= $error == "" ? "" : "<div id='login_failed'>" . $error . "</div>" ?>

            <select name="role">
                <option value="1" selected>medewerker</option>
                <option value="2">manager</option>
                <?= $user["role"] >= $admin ? "<option value='3'>admin</option>" : "" ?>
            </select>

            <input type="text" name="name" placeholder="naam">

            <input type="email" name="email" placeholder="email">

            <input type="password" name="password" placeholder="wachtwoord">
            
            <button type="submit" name="submit" id="submit_button">Gebruiker aanmaken</button>
        </form>

    </div><!-- wrapper -->
    <footer>
        © 2021 - Tools Forever |&nbsp;<a href="logout.php">Uitloggen</a>
    </footer>
</body>
</html>
