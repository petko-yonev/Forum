<?php

include_once "sql_connection.php";
session_start();
$userId = $_SESSION["userId"];

$idOnThread = mysqli_real_escape_string($conn, $_GET['idOnThread']);
$commentContent = mysqli_real_escape_string($conn, $_POST['TextNewComment']);
$commentContent = $_POST['TextNewComment'];
$currentDate = date('Y.m.d H:i:s', strtotime('1 hour'));

//SQl query for inserting the data of the new comment
$sql_newComment = ("INSERT INTO `comments` (`user_id`, `thread_id`, `date`, `content`) VALUES (?, ?, ?, ?)");
if(!mysqli_stmt_prepare($stmt, $sql_newComment)){
    echo "Problem inserting comments in DB with SQL";
} else {
    mysqli_stmt_bind_param($stmt, "iiss", $userId, $idOnThread, $currentDate, $commentContent);
    mysqli_stmt_execute($stmt);
    header ("Location: threadTemplate.php?idOnThread=".$idOnThread);
    exit();
}


