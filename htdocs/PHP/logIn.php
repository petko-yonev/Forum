<?php

include_once "sql_connection.php";

$password = mysqli_real_escape_string($conn, $_POST["Password"]);
$email = mysqli_real_escape_string($conn, $_POST["Email"]);

$sql_selectEmailAndPassword = ("SELECT `email`, AES_DECRYPT(`password`,'secret') FROM `user` WHERE email = ? AND AES_DECRYPT(`password`,'secret') = ?");

if(!mysqli_stmt_prepare($stmt, $sql_selectEmailAndPassword)){
    echo "Problem selecting Email or Password from SQL";
} else {
    mysqli_stmt_bind_param($stmt, "ss", $email, $password);
    mysqli_stmt_execute($stmt);
    $result_emailAndPassword = mysqli_stmt_get_result($stmt);
    if(mysqli_fetch_assoc($result_emailAndPassword)){
        $sql_username = "SELECT `user_id` FROM `user` WHERE `email` = ?";
        if(!mysqli_stmt_prepare($stmt, $sql_username)){
            echo "Problem selecting username or id from SQL";
        } else {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result_userId = mysqli_stmt_get_result($stmt);
            while($row = mysqli_fetch_assoc($result_userId)){
                session_start();
                $_SESSION["userId"] = $row['user_id'];
                header ("Location: ../index.php");
                exit();
            }
        }
    } 
}
header("location: logInOrRegister.php?wrong_user_pass=0&wrong_email=1");
