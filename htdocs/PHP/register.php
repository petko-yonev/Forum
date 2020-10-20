<?php

include_once "sql_connection.php";

$username = mysqli_real_escape_string($conn, $_POST['Username']);
$password = mysqli_real_escape_string($conn, $_POST['Password']);
$email = mysqli_real_escape_string($conn, $_POST['Email']);
$defaultProfilePicture = "Default_User_Profile_Picture.png";
$stmt = mysqli_stmt_init($conn);

//  Check Email if it`s already taken
$sql_checkUsernameEmail = "SELECT email FROM user WHERE `email` = ? ";

if(!mysqli_stmt_prepare($stmt, $sql_checkUsernameEmail)){
    echo "Problem check Username Email";
} else {
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result_CheckUsernameEmail = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result_CheckUsernameEmail)){
        if ($email === $row['email']){
            header("Location: logInOrRegister.php?wrong_user_pass=1&wrong_email=0");
            exit();
        } 
    } 
}

// SQL query for inserting user data
$sql_user = "INSERT INTO user (`username`, `password`, `email`, `profile_picture_name`) VALUES (?, AES_ENCRYPT(?, 'secret'), ?, ?)";
if(!mysqli_stmt_prepare($stmt, $sql_user)){
    echo "Problem inserting data";
} else {
        mysqli_stmt_bind_param($stmt, "ssss", $username, $password, $email, $defaultProfilePicture);
        mysqli_stmt_execute($stmt);
}

//Session start
$sql_userId = "SELECT user_id FROM user WHERE `email` = ?";
if(!mysqli_stmt_prepare($stmt, $sql_userId)){
    echo "Problem find Id";
} else {
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result_CheckUsernameId = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result_CheckUsernameId)){
        session_start();
        $_SESSION["userId"] = $row['user_id'];
        header("Location: ../index.php");
        exit();
    } 
}


