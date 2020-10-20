<?php

include_once "sql_connection.php";
session_start();
$user_id = $_SESSION["userId"];

//Take user inputs
$newName = mysqli_real_escape_string($conn, $_POST["newName"]);
$newNameRepeat = mysqli_real_escape_string($conn, $_POST["newNameRepeat"]);
$newPassword = mysqli_real_escape_string($conn, $_POST["newPassword"]);
$newPasswordRepeat = mysqli_real_escape_string($conn, $_POST["newPasswordRepeat"]);
$oldPasswordConfirm = mysqli_real_escape_string($conn, $_POST["oldPasswordConfirm"]);
$emailConfirm = mysqli_real_escape_string($conn, $_POST["emailConfirm"]);

//SQL query for old password and email
$sql_oldPasswordAndEmail = "SELECT  AES_DECRYPT(`password`,'secret'), `email` FROM `user` WHERE `user_id` = ?";
if(!mysqli_stmt_prepare($stmt, $sql_oldPasswordAndEmail)){
    echo "Problem selectiong username and password from user with SQL";
} else {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_execute($stmt);
    $result_oldPasswordAndEmail = mysqli_stmt_get_result($stmt);
    while($row_oldPassAndEmail = mysqli_fetch_assoc($result_oldPasswordAndEmail)){

        //Old password and email
        $oldPasswordHolder = $row_oldPassAndEmail["AES_DECRYPT(`password`,'secret')"];
        $emailHolder = $row_oldPassAndEmail["email"];

        //Check old password and email
        if($emailConfirm === $emailHolder && $oldPasswordConfirm === $oldPasswordHolder){

            //Check if `new name` and `new name repeat` input fields are full and if they macth
            if($newName && $newNameRepeat && $newName === $newNameRepeat){

                //SQL sqery to change username
                $sql_nameChange=("UPDATE `user` SET `username` = '$newNameRepeat' WHERE `user_id` = ?");
                if(!mysqli_stmt_prepare($stmt, $sql_nameChange)){
                    echo "Problem Updating username in user with SQl";
                } else {
                    mysqli_stmt_bind_param($stmt, "i", $user_id);
                    mysqli_execute($stmt);
                    header ("Location: accountSettings.php");
                }
            }

            //Check if `new password` and `new password repeat` input fields are full and if they macth
            if($newPassword && $newPasswordRepeat && $newPassword === $newPasswordRepeat){

                //SQL sqery to change password
                $sql_passChange=("UPDATE `user` SET `password` = AES_ENCRYPT('$newPasswordRepeat', 'secret') WHERE `user_id` = ?");
                if(!mysqli_stmt_prepare($stmt, $sql_passChange)){
                    echo "Problem Updating password in user with SQL";
                } else {
                    mysqli_stmt_bind_param($stmt, "i", $user_id);
                    mysqli_execute($stmt);
                    echo $sql_passChange;
                    header ("Location: accountSettings.php");
                    exit();
                }
            }
        } else {
            header ("Location: accountSettings.php");
        }  
    }
}


