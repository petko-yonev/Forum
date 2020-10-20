<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" type="text/css" href="../CSS/index_style.css">
    <link rel="stylesheet" type="text/css" href="../CSS/header.css">
    <link rel="stylesheet" type="text/css" href="../CSS/logInOrRegister.css">

    <script type="text/javascript" src="../Script/script.js"></script>
    <title>OverhoulForum</title>
</head>
<body>
    <header>
        <?php
            include_once "header.php";
        ?>
    </header>

    <!-- Log In or Register -->
    <div id="RegLogButtonHolder">
        <input class="RegLogButtons" type="button" value="LogIn" onclick="ShowSingInForm()">
        <input class="RegLogButtons" type="button" value="Register" onclick="ShowRegisterForm()">
    </div>
    <div id="logIn" class=<?php if( !$_GET["wrong_email"]){ echo '"'."hide".'"'; }?>>
        <form method="POST" action="logIn.php">
            <br>
            LogIn
            <br>
            <?php
                if ($_GET["wrong_user_pass"]){
                    echo 'E-mail: <input type="email" name="Email" title="Email" placeholder="Email" > <!--required  pattern="[a-zA-Z][a-zA-Z0-9-_]{1,20}$"-->
                        <br>
                        Парола: <input type="password" name="Password" title="Password must start with letter, contain from 5 to 30 chars and - or _ " placeholder="Password" required  pattern="[a-zA-Z0-9-_]{4,30}$">
                        <br>';
                } else {
                    echo 'E-mail: <input class="wrong" type="email" name="Email" title="Email" placeholder="Try Again" > <!--required  pattern="[a-zA-Z][a-zA-Z0-9-_]{1,20}$"-->
                        <br>
                        Парола: <input class="wrong" type="password" name="Password" title="Password must start with letter, contain from 5 to 30 chars and - or _ " placeholder="Try Again" required  pattern="[a-zA-Z0-9-_]{4,30}$">
                        <br>';
                }
            ?>
            <input type="submit" value="Вход">
        </form>
    </div>
    <div id="Register" class=<?php if($_GET["wrong_email"]){ echo '"'."hide".'"'; } ?>>
        <form method="POST" action="register.php">
            <br>
            Register
            <br>
            Име: <input type="text" name="Username" title="Username must start with letter, contain from 2 to 20 chars and - or _ " placeholder="Username" required  pattern="[a-zA-Z][a-zA-Z0-9-_]{1,20}$">
            <br>
            Парола: <input type="password" name="Password" title="Password must start with letter, contain from 5 to 30 chars and - or _ " placeholder="Password" required  pattern="[a-zA-Z0-9-_]{4,30}$">
            <br>
            <?php
                if($_GET["wrong_email"]){
                    echo 'Email: <input type="email" name="Email" placeholder="Email" required >
                    <br>';
                } else {
                    echo 'Email:  <input class="wrong" type="email" name="Email" placeholder="Email already taken" required >
                    <br>';
                }
            ?>
            <input type="submit" value="Регистрация">
        </form>
    </div>
</body>
</html>
