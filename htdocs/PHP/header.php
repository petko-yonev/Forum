
<?php
    session_start();
?>

<div class="user_info">
    <h1>Добре дошли</h1>
    
    <div class="user_name">
        <?php
            if($_SESSION){
                include_once "sql_connection.php";
                $userId = $_SESSION["userId"];

                //SQL query for username and user`s Profile Picture
                $sql_profilePic = "SELECT `profile_picture_name`,`username` FROM `user` WHERE user_id = ?";
                if(!mysqli_stmt_prepare($stmt, $sql_profilePic)){
                    echo "Problem selecting Profile Picture and Username from user with SQL";
                } else {
                    mysqli_stmt_bind_param($stmt, "i", $userId);
                    mysqli_stmt_execute($stmt);
                    $resultProfilePicture = mysqli_stmt_get_result($stmt);
                    while($row = mysqli_fetch_assoc($resultProfilePicture)){
                        $holder_profilePicture = $row["profile_picture_name"];
                        echo "<img class='user_pic' src='../Profile_Pictures/".$holder_profilePicture."'>";    
                        echo $row['username'];
                        echo "<br><a href='/PHP/accountSettings.php?page=1&show=info'>Settings</a>";
                    }
                }                
            } else {
                echo "Логнете се или <br> Направете регистрация";
            }
        ?>
    </div>      
</div>

<a href="../index.php"><img src="../Images/logo.png" alt=""></a>

<nav>
    <ul>
        <li class="logo_nav"><a class="underline_remove" href="/PHP/threads.php?page=1&category=1">Игри</a></li>    
        <li class="logo_nav"><a class="underline_remove" href="/PHP/threads.php?page=1&category=2">Филми</a></li>
        <li class="logo_nav"><a class="underline_remove" href="/PHP/threads.php?page=1&category=3">Новини</a></li>
        <li class="logo_nav"><a class="underline_remove" href="/PHP/threads.php?page=1&category=4">Работа</a></li>    
        <?php
        if($_SESSION){
           echo '<li class="logo_nav"><a class="underline_remove" id="exit" href="/PHP/logOut.php">Изход</a></li>';
        } else {
            echo '<li class="logo_nav"><a class="underline_remove" id="loginОrRegister" href="/PHP/logInOrRegister.php?wrong_user_pass=1&wrong_email=1">Вход/Регистрация</a></li>';
        }
        ?>
    </ul>
</nav>
            