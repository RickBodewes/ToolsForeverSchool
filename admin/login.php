<?php

session_start();

require '../dependencies/PDOcon.php';
require '../dependencies/funcs.php';
require '../dependencies/checkLogin.php';

$loginFailed = false;

if($loggedIn){
    header("location: /admin");
}else{
    if(isset($_POST["submit"])){
        if(isset($_POST["email"]) && isset($_POST["password"])){
            $passwordHashed = EncryptPass($_POST["password"], $_POST["email"]);

            $query = "SELECT * FROM t4eusers WHERE userEmail = :email AND password = :password LIMIT 1";
            $stmt = $con->prepare($query);
            $stmt->bindvalue(":email", $_POST["email"]);
            $stmt->bindvalue(":password", $passwordHashed);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!($user === false)){
                $_SESSION['loggedIn'] = true;
                $_SESSION['lUserID'] = $user['userID'];
                $_SESSION['lUserToken'] = $user['sessionID'];
                        
                header("location: /admin");
            }else{
                $loginFailed = true;
            }
        }
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
    <title>Inloggen | Tools Forever</title>
</head>
<body>
    <nav>
        <div id="nav_menu">
            <div id="nav_logo"><a href="/">Tools Forever</a></div>
            <div class="menu-item"><a href="#">Beheer</a></div>
        </div>
        <div id="nav_account"></div>
    </nav>
    <div id="wrapper">
    <div id="login_wrapper">
            <?= $loginFailed ? "<div id='login_failed'>Inlog gegevens onjuist!</div>" : "" ?>
            <form method="post">
                <input type="email" name="email" id="login_form_email" placeholder="email">
                <input type="password" name="password" id="login_form_password" placeholder="wachtwoord">
                <button type="submit" name="submit" id="submit_button">Inloggen</button>
            </form>
        </div><!-- search_box -->
        
    </div><!-- wrapper -->
    <footer>
        © 2021 - Tools Forever
    </footer>
</body>
</html>