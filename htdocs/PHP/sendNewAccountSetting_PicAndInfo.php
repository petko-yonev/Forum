<?php

include_once "sql_connection.php";
session_start();
$user_id = $_SESSION["userId"];

if(isset($_POST["submit"]) || isset($_POST["delete_pic"])){
    
    //Delete old Picture
    $sql_oldPic = "SELECT `profile_picture_name` FROM `user` WHERE `user_id` = ?";
    if(!mysqli_stmt_prepare($stmt, $sql_oldPic)){
        echo "Problem Selecting old Profile Pic from user-information with SQL";
    } else {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result_oldPic = mysqli_stmt_get_result($stmt);
        while($row = mysqli_fetch_assoc($result_oldPic)){
            $oldPicDir = "../Profile_Pictures/".$row["profile_picture_name"];
            $oldPicName = $row["profile_picture_name"];
            if($oldPicName !== "Default_User_Profile_Picture.png"){
                unlink($oldPicDir);
            }
        }
    }

    //Set Default Picture Name 
    if(isset($_POST["delete_pic"])){
        $fileNameNew ='Default_User_Profile_Picture.png';
        
    } else {
        //Set user`s Picture Name
        $file = $_FILES["file"];
        $fileName = $file["name"];
        $fileTmpName = $file["tmp_name"];
        $fileExt = explode(".", $fileName);
        $fileActualExt = strtolower(end($fileExt));
            
        $fileNameNew = uniqid('',true).".".$fileActualExt;
        $fileDestination = '../Profile_Pictures/'.$fileNameNew;

        move_uploaded_file($fileTmpName,$fileDestination);
        
    }

    //SQL query for updating the new profile picture
    $sql_NewProfilePicture=("UPDATE `user` SET `profile_picture_name` = '$fileNameNew' WHERE `user_id` = ?");
    if(!mysqli_stmt_prepare($stmt, $sql_NewProfilePicture)){
        echo "Problem Updating Profile Picture on this user with SQL";
    } else {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        header ("Location: accountSettings.php");
        exit();
    }
}

//Change the `about me` information`
if(isset($_POST["change_user_info"])){
    $newAboutMeInfo = mysqli_real_escape_string($conn, $_POST['about_user']);
    $sql_newInformationAboutUser = ("UPDATE `user` SET `about_me` = '$newAboutMeInfo' WHERE `user_id` = ?");

    if(!mysqli_stmt_prepare($stmt, $sql_newInformationAboutUser)){
        echo "Problem Updating user about_user on this user with SQL";
    } else {
        mysqli_stmt_bind_param($stmt, 'i', $user_id);
        mysqli_stmt_execute($stmt);
        header ("Location: accountSettings.php");
        exit();
    }
}